<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title><?= esc($pageTitle ?? 'ChillMed - Artikel') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/artikel.css') ?>">
</head>

<body>

    <header class="navbar">
        <div class="logo"><span>Chill</span>Med</div>
        <nav>
            <a href="<?= base_url('dashboard') ?>">Beranda</a>
            <a href="<?= base_url('chatbot') ?>">Chatbot</a>
            <a href="<?= base_url('questions') ?>">Pertanyaan</a> <a href="<?= base_url('/') ?>"><button class="btn logout">Logout</button></a>
        </nav>
    </header>

    <main>
        <h1 class="artikel-heading">Artikel</h1>
        <div class="artikel-grid">
            <?php
            if (isset($articles) && is_array($articles)) {
                foreach ($articles as $article) {
                    echo '<div class="card">';
                    echo '<img src="' . base_url('images/' . esc($article['image'])) . '" alt="Gambar Artikel ' . esc($article['title']) . '">';
                    echo '<h3>' . esc($article['title']) . '</h3>';
                    echo '<a class="btn-read" href="' . base_url('artikel/detail/' . url_title($article['title'], '-', TRUE)) . '">Baca Selengkapnya</a>';
                    echo '</div>';
                }
            } else {
                echo '<p>Tidak ada artikel yang tersedia.</p>';
            }
            ?>
        </div>
    </main>

</body>

</html>