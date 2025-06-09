<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Helpers\url_helper;

class Artikel extends BaseController
{
    private $allArticles = [
        [
            'title' => 'Apa Itu Gangguan Cemas? Penjelasan Lengkap dan Solusinya',
            'image' => 'cemas.jpg',
            'date' => '01/01/2025, 10:00 WITA',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.'
        ],
        [
            'title' => 'PTSD: Ketika Trauma Tak Hilang Meski Waktu Telah Berlalu',
            'image' => 'ptsd.jpg',
            'date' => '05/02/2025, 11:30 WITA',
            'content' => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem.'
        ],
        [
            'title' => 'Kuliah Kok Capek Terus? Kenali Tanda-Tanda Burnout Sebelum Terlambat',
            'image' => 'burnout.jpg',
            'date' => '20/05/2025, 14:00 WITA',
            'content' => 'BANJARMASIN, ChillMed.com  Kamu merasa capek terus meskipun tidak sedang banyak tugas? Semangat belajar hilang, mudah marah, atau merasa semua jadi beban? Bisa jadi kamu sedang mengalami burnout. Burnout adalah kondisi kelelahan fisik, emosional, dan mental yang disebabkan oleh stres berkepanjangan, terutama ketika seseorang merasa kewalahan, terlalu banyak tuntutan, dan tidak memiliki cukup waktu atau energi untuk menghadapinya. Walaupun sering dikaitkan dengan dunia kerja, burnout juga sangat umum terjadi pada mahasiswa.

            Burnout berbeda dengan malas. Malas biasanya terjadi sesekali dan bisa hilang dengan motivasi atau dorongan kecil. Burnout bukan sekadar malas: ia melibatkan kelelahan yang dalam dan terus-menerus, bahkan untuk hal-hal yang dulu kamu sukai dan kuasai.'
        ],
        [
            'title' => 'Tidur Tak Nyenyak Sejak Masuk Kuliah? Bisa Jadi Kamu Alami Sleep Disorder',
            'image' => 'sleepdisorder.jpg',
            'date' => '10/03/2025, 09:00 WITA',
            'content' => 'At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus.'
        ],
        [
            'title' => 'Malu Makan di Depan Teman? Bisa Jadi Itu Awal dari Eating Disorder',
            'image' => 'eating.jpg',
            'date' => '15/04/2025, 16:00 WITA',
            'content' => 'Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.'
        ],
        [
            'title' => 'Cek Pintu Berkali-Kali Sebelum Berangkat Kuliah? Waspadai OCD',
            'image' => 'ocd.webp',
            'date' => '25/05/2025, 10:30 WITA',
            'content' => 'Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur? At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.'
        ],
    ];

    public function __construct()
    {
        helper('url');
    }

    public function home()
    {
        $data = [
            'articles' => $this->allArticles,
            'pageTitle' => 'Daftar Artikel ChillMed'
        ];
        return view('layout/artikel/artikelhome', $data);
    }

    public function detail($title_slug = null)
    {
        if ($title_slug === null) {
            return redirect()->to(base_url('artikel'));
        }

        $selectedArticle = null;
        foreach ($this->allArticles as $article) {
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
        foreach ($this->allArticles as $article) {
            if ($article['title'] !== $selectedArticle['title']) {
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