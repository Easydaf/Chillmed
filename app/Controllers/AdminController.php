<?php

namespace App\Controllers;

use App\Models\QuoteModel;
use App\Models\ArticleModel;
use App\Models\UserModel;
use CodeIgniter\Controller;

class AdminController extends BaseController
{
    protected $quoteModel;
    protected $articleModel;
    protected $userModel;

    public function __construct()
    {
        $this->quoteModel = new QuoteModel();
        $this->articleModel = new ArticleModel();
        $this->userModel = new UserModel();
    }

    // Dashboard Admin
    public function index()
    {
        $totalQuotes = $this->quoteModel->countAllResults();
        $totalArticles = $this->articleModel->countAllResults();
        $totalUsers = $this->userModel->countAllResults();

        $data = [
            'pageTitle'     => 'Dashboard Admin ChillMed',
            'totalQuotes'   => $totalQuotes,
            'totalArticles' => $totalArticles,
            'totalUsers'    => $totalUsers,
        ];
        return view('admin/admin', $data); // Menggunakan admin/admin.php
    }

    // --- Manajemen Quotes ---
    public function quotes()
    {
        $data = [
            'pageTitle' => 'Manajemen Quotes',
            'quotes'    => $this->quoteModel->findAll(),
        ];
        return view('admin/manage_quotes', $data);
    }

    // UBAH FUNGSI addQuote INI
    public function addQuote()
    {
        // PERBAIKAN: Ubah 'post' menjadi 'POST' (huruf kapital)
        if ($this->request->getMethod() === 'POST') {
            // Logika pemrosesan form submit (POST)
            $validation = \Config\Services::validation();
            $rules = [
                'quote_text' => 'required|min_length[5]|max_length[500]',
                'author'     => 'permit_empty|max_length[100]',
            ];
            $validation->setRules($rules);

            if ($validation->withRequest($this->request)->run()) {
                $data = [
                    'quote_text' => $this->request->getPost('quote_text'),
                    'author'     => $this->request->getPost('author'),
                ];

                if ($this->quoteModel->insert($data)) {
                    return redirect()->to(base_url('admin/quotes'))->with('success', 'Quote berhasil ditambahkan!');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Gagal menambahkan quote.');
                }
            } else {
                // Validasi gagal, kembali ke form dengan input dan error
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        }
        // Menampilkan form tambah quote (GET)
        return view('admin/quotes_add', ['pageTitle' => 'Tambah Quote Baru']);
    }

    // Metode untuk Edit Quote (Masih AJAX)
    public function editQuote($id)
    {
        // PERBAIKAN: Ubah 'post' menjadi 'POST' (huruf kapital)
        if ($this->request->isAJAX() && $this->request->getMethod() === 'POST') {
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

    // Metode untuk Delete Quote (Masih AJAX)
    public function deleteQuote($id)
    {
        // PERBAIKAN: Ubah 'post' menjadi 'POST' (huruf kapital)
        if ($this->request->isAJAX() && $this->request->getMethod() === 'POST') {
            if ($this->quoteModel->delete($id)) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Quote berhasil dihapus!']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal menghapus quote.']);
            }
        }
        return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    }

    // --- Manajemen Artikel ---
    public function articles()
    {
        $data = [
            'pageTitle' => 'Manajemen Artikel',
            'articles'  => $this->articleModel->findAll(),
        ];
        return view('admin/manage_articles', $data);
    }

    public function addArticle()
    {
        if ($this->request->getMethod() === 'POST') { // PASTIKAN 'POST'
            $validation = \Config\Services::validation();
            $rules = [
                'title'   => 'required|min_length[5]|max_length[255]',
                'content' => 'required|min_length[20]',
                'image'   => 'uploaded[image]|max_size[image,1024]|is_image[image]', // Validasi gambar
            ];
            $validation->setRules($rules);

            if ($validation->withRequest($this->request)->run()) {
                $file = $this->request->getFile('image');
                $newName = $file->getRandomName(); // Buat nama unik
                $file->move(FCPATH . 'images', $newName); // Pindahkan ke public/images

                $data = [
                    'title'   => $this->request->getPost('title'),
                    'content' => $this->request->getPost('content'),
                    'author'  => $this->request->getPost('author') ?? 'ChillMed Team',
                    'image'   => $newName, // Simpan nama file
                ];

                if ($this->articleModel->insert($data)) {
                    return redirect()->to(base_url('admin/articles'))->with('success', 'Artikel berhasil ditambahkan!');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Gagal menambahkan artikel.');
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        }
        return view('admin/add_article_form', ['pageTitle' => 'Tambah Artikel Baru']);
    }

    public function editArticle($id)
    {
        $article = $this->articleModel->find($id);

        if (!$article) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->request->getMethod() === 'POST') { // PASTIKAN 'POST'
            $validation = \Config\Services::validation();
            $rules = [
                'title'   => 'required|min_length[5]|max_length[255]',
                'content' => 'required|min_length[20]',
            ];
            // Hanya validasi gambar jika ada yang diupload
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
                    // Hapus gambar lama jika ada
                    if ($article['image'] && file_exists(FCPATH . 'images/' . $article['image'])) {
                        unlink(FCPATH . 'images/' . $article['image']);
                    }
                    $newName = $file->getRandomName();
                    $file->move(FCPATH . 'images', $newName);
                    $data['image'] = $newName; // Update nama file gambar baru
                }

                if ($this->articleModel->update($id, $data)) {
                    return redirect()->to(base_url('admin/articles'))->with('success', 'Artikel berhasil diperbarui!');
                } else {
                    return redirect()->back()->withInput()->with('error', 'Gagal memperbarui artikel.');
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $validation->getErrors());
            }
        }
        return view('admin/edit_article_form', ['pageTitle' => 'Edit Artikel', 'article' => $article]);
    }

    public function deleteArticle($id)
    {
        if ($this->request->getMethod() === 'POST') { // PASTIKAN 'POST'
            $article = $this->articleModel->find($id);
            if ($article) {
                // Hapus file gambar terkait jika ada
                if ($article['image'] && file_exists(FCPATH . 'images/' . $article['image'])) {
                    unlink(FCPATH . 'images/' . $article['image']);
                }

                if ($this->articleModel->delete($id)) {
                    return redirect()->to(base_url('admin/articles'))->with('success', 'Artikel berhasil dihapus!');
                } else {
                    return redirect()->to(base_url('admin/articles'))->with('error', 'Gagal menghapus artikel.');
                }
            } else {
                return redirect()->to(base_url('admin/articles'))->with('error', 'Artikel tidak ditemukan.');
            }
        }
        return redirect()->to(base_url('admin/articles'))->with('error', 'Metode penghapusan tidak diizinkan.');
    }

    // --- Manajemen Users ---
    public function users()
    {
        $data = [
            'pageTitle' => 'Manajemen Users',
            'users'     => $this->userModel->findAll(), // Mengambil semua user dari database
        ];
        return view('admin/manage_users', $data);
    }

    // Mengedit role user (memproses AJAX POST)
    public function editUserRole($id)
    {
        if ($this->request->isAJAX() && $this->request->getMethod() === 'POST') { // PASTIKAN 'POST'
            $user = $this->userModel->find($id);

            if (!$user) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'User tidak ditemukan.']);
            }

            $newRole = $this->request->getPost('role');

            if (!in_array($newRole, ['admin', 'user'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Role tidak valid.']);
            }

            // Mencegah admin mengubah role-nya sendiri menjadi non-admin
            if (session()->get('user')['id'] == $id && $newRole !== 'admin') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Anda tidak bisa mengubah role Anda sendiri.']);
            }

            if ($user['role'] === $newRole) { // Jika role tidak berubah
                return $this->response->setJSON(['status' => 'success', 'message' => 'Role user tidak berubah.']);
            }

            if ($this->userModel->update($id, ['role' => $newRole])) {
                return $this->response->setJSON(['status' => 'success', 'message' => 'Role user berhasil diperbarui!']);
            } else {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Gagal memperbarui role user.']);
            }
        }
        return $this->response->setStatusCode(405)->setJSON(['status' => 'error', 'message' => 'Metode tidak diizinkan.']);
    }

    // Menghapus user (memproses POST dari form HTML)
    public function deleteUser($id)
    {
        if ($this->request->getMethod() === 'POST') { // PASTIKAN 'POST'
            // Mencegah admin menghapus dirinya sendiri
            if (session()->get('user')['id'] == $id) {
                return redirect()->to(base_url('admin/users'))->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
            }

            if ($this->userModel->delete($id)) {
                return redirect()->to(base_url('admin/users'))->with('success', 'User berhasil dihapus!');
            } else {
                return redirect()->to(base_url('admin/users'))->with('error', 'Gagal menghapus user.');
            }
        }
        return redirect()->to(base_url('admin/users'))->with('error', 'Metode penghapusan tidak diizinkan.');
    }
}
