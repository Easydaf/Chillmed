<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Helpers\url_helper;
use App\Models\ArticleModel;

class Artikel extends BaseController
{
    protected $articleModel;

    public function __construct()
    {
        $this->articleModel = new ArticleModel();
        helper('url');
    }

    public function home()
    {
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
        
        $allArticles = $this->articleModel->findAll();
        $selectedArticle = null;
        foreach ($allArticles as $article) {
            if (url_title($article['title'], '-', TRUE) === $title_slug) {
                $selectedArticle = $article;
                break;
            }
        }

        if ($selectedArticle === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }


        $relatedArticles = [];
        $countRelated = 0;
        foreach ($allArticles as $article) {
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