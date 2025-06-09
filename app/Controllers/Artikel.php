<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Helpers\url_helper;
use App\Models\ArticleModel; // Import ArticleModel

class Artikel extends BaseController
{
    protected $articleModel;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        helper('url'); // Pastikan helper 'url' dimuat
    }

    public function home()
    {
        // Ambil semua artikel dari database
        $articles = $this->articleModel->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'articles' => $articles,
            'pageTitle' => 'Daftar Artikel ChillMed'
        ];
        return view('layout/artikel/artikelhome', $data);
    }

    public function detail($title_slug = null)
    {
        if ($title_slug === null) {
            return redirect()->to(base_url('artikel'));
        }

        // Cari artikel berdasarkan slug.
        // Anda perlu menambahkan kolom 'slug' ke tabel artikel jika ingin performa terbaik.
        // Untuk saat ini, kita akan mencari secara manual dari semua artikel yang ada,
        // yang mungkin kurang efisien untuk jumlah artikel yang sangat banyak.
        // ALTERNATIF: Anda bisa menambahkan kolom 'slug' di DB dan mencari langsung berdasarkan slug
        // $article = $this->articleModel->where('slug', $title_slug)->first();
        
        $allArticles = $this->articleModel->findAll();
        $selectedArticle = null;
        foreach ($allArticles as $article) {
            // Bandingkan slug dari judul artikel di database dengan title_slug dari URL
            if (url_title($article['title'], '-', TRUE) === $title_slug) {
                $selectedArticle = $article;
                break;
            }
        }

        if ($selectedArticle === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Ambil artikel terkait (kecuali artikel yang sedang dilihat)
        // Batasi jumlah artikel terkait jika diperlukan, misalnya 3 atau 5 saja
        $relatedArticles = [];
        $countRelated = 0;
        foreach ($allArticles as $article) {
            // Pastikan ID berbeda dan ambil maksimal 5 artikel terkait
            if ($article['id'] !== $selectedArticle['id']) {
                $relatedArticles[] = $article;
                $countRelated++;
                if ($countRelated >= 5) break; 
            }
        }

        $data = [
            'article' => $selectedArticle,
            'relatedArticles' => $relatedArticles,
            'pageTitle' => $selectedArticle['title']
        ];
        return view('layout/artikel/artikeldetail', $data);
    }
}