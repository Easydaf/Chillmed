<?php

namespace App\Controllers;

use App\Models\ArticleModel;
use App\Models\QuoteModel;


class AdminController extends BaseController
{
    protected $quoteModel;
    protected $articleModel;

    public function __construct()
    {
        // Pastikan Model sudah di-load di sini karena akan digunakan di AdminController
        $this->quoteModel = new QuoteModel();
        $this->articleModel = new ArticleModel();
    }

    // Dashboard Admin
    public function index()
    {
        // Ambil data untuk ditampilkan di dashboard admin
        $totalQuotes = $this->quoteModel->countAllResults();
        $totalArticles = $this->articleModel->countAllResults();

        $data = [
            'pageTitle'     => 'Dashboard Admin ChillMed',
            'totalQuotes'   => $totalQuotes,
            'totalArticles' => $totalArticles,
        ];
        // View ini akan diisi oleh tim frontend
        return view('admin/admin', $data);
    }

    // --- Manajemen Quotes ---

    // Menampilkan daftar quotes
    public function quotes()
    {
        $data = [
            'pageTitle' => 'Manajemen Quotes',
            'quotes'    => $this->quoteModel->findAll(), // Mengambil semua quotes dari database
        ];
        // View ini akan diisi oleh tim frontend
        return view('admin/manage_quotes', $data);
    }

    // Menambah quote baru (API endpoint, akan dipanggil dari AJAX form)
    public function addQuote()
    {
        if ($this->request->isAJAX() && $this->request->getMethod() === 'post') {
            $data = [
                'quote_text' => $this->request->getPost('quote_text'),
                'author'     => $this->request->getPost('author'),
            ];

            if ($this->quoteModel->insert($data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Quote berhasil ditambahkan!', 'id' => $this->quoteModel->getInsertID()]);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menambahkan quote.']);
            }
        }
        return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    }

    // Mengedit quote (API endpoint, akan dipanggil dari AJAX form)
    public function editQuote($id)
    {
        if ($this->request->isAJAX() && $this->request->getMethod() === 'post') {
            $data = [
                'quote_text' => $this->request->getPost('quote_text'),
                'author'     => $this->request->getPost('author'),
            ];

            if ($this->quoteModel->update($id, $data)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Quote berhasil diperbarui!']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui quote atau tidak ada perubahan.']);
            }
        }
        return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    }

    // Menghapus quote (API endpoint, akan dipanggil dari AJAX)
    public function deleteQuote($id)
    {
        if ($this->request->isAJAX() && $this->request->getMethod() === 'post') {
            if ($this->quoteModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Quote berhasil dihapus!']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus quote.']);
            }
        }
        return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    }

    // --- Manajemen Artikel ---

    // Menampilkan daftar artikel
    public function articles()
    {
        $data = [
            'pageTitle' => 'Manajemen Artikel',
            'articles'  => $this->articleModel->findAll(), // Mengambil semua artikel dari database
        ];
        return view('admin/manage_articles', $data);
    }

    // Menampilkan form tambah artikel dan memproses submitnya
    public function addArticle()
    {
        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $validation->setRules([
                'title'   => 'required|min_length[5]|max_length[255]',
                'content' => 'required|min_length[20]',
                'image'   => 'uploaded[image]|max_size[image,1024]|is_image[image]',
            ]);

            if ($validation->withRequest($this->request)->run()) {
                $file = $this->request->getFile('image');
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'images', $newName);

                $data = [
                    'title'   => $this->request->getPost('title'),
                    'content' => $this->request->getPost('content'),
                    'author'  => $this->request->getPost('author') ?? 'ChillMed Team',
                    'image'   => $newName,
                ];

                if ($this->articleModel->insert($data)) {
                    return redirect()->to('/admin/articles')->with('success', 'Artikel berhasil ditambahkan!');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Gagal menambahkan artikel.');
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        }
        return view('admin/add_article_form', ['pageTitle' => 'Tambah Artikel Baru']);
    }

    // Menampilkan form edit artikel dan memproses submitnya
    public function editArticle($id)
    {
        $article = $this->articleModel->find($id);

        if (!$article) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod() === 'post') {
            $validation = \Config\Services::validation();
            $rules = [
                'title'   => 'required|min_length[5]|max_length[255]',
                'content' => 'required|min_length[20]',
            ];

            if ($this->request->getFile('image')->isValid() && ! $this->request->getFile('image')->hasMoved()) {
                $rules['image'] = 'uploaded[image]|max_size[image,1024]|is_image[image]';
            }

            $validation->setRules($rules);

            if ($validation->withRequest($this->request)->run()) {
                $data = [
                    'title'   => $this->request->getPost('title'),
                    'content' => $this->request->getPost('content'),
                    'author'  => $this->request->getPost('author') ?? 'ChillMed Team',
                ];

                $file = $this->request->getFile('image');
                if ($file->isValid() && ! $file->hasMoved()) {
                    if ($article['image'] && file_exists(FCPATH . 'images/' . $article['image'])) {
                        unlink(FCPATH . 'images/' . $article['image']);
                    }
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'images', $newName);
                    $data['image'] = $newName;
                }

                if ($this->articleModel->update($id, $data)) {
                    return redirect()->to('/admin/articles')->with('success', 'Artikel berhasil diperbarui!');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Gagal memperbarui artikel.');
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        }

        $data = [
            'pageTitle' => 'Edit Artikel',
            'article'   => $article,
        ];
        return view('admin/edit_article_form', $data);
    }

    public function deleteArticle($id)
    {
        if ($this->request->isAJAX() && $this->request->getMethod() === 'post') {
            $article = $this->articleModel->find($id);
            if ($article) {
                if ($article['image'] && file_exists(FCPATH . 'images/' . $article['image'])) {
                    unlink(FCPATH . 'images/' . $article['image']);
                }

                if ($this->articleModel->delete($id)) {
                    return $this->response->setJSON(['status' => 'success', 'message' => 'Artikel berhasil dihapus!']);
                } else {
                    return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus artikel.']);
                }
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Artikel tidak ditemukan.']);
            }
        }
        return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    }
}