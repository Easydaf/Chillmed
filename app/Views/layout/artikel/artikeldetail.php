<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= esc($article['title'] ?? 'Detail Artikel - ChillMed') ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('css/artikel.css') ?>">
    </head>
<body>

    <header class="navbar">
        <div class="logo"><span class="chill">Chill</span><span class="med">Med</span></div>
        <nav>
            <a href="<?= base_url('dashboard') ?>">Beranda</a>
            <a href="<?= base_url('chatbot') ?>">Chatbot</a>
            <a href="<?= base_url('questions') ?>">Pertanyaan</a>
            <a href="<?= base_url('/') ?>"><button class="btn logout">Logout</button></a>
    </header>

    <main class="container">
        <div class="content">
            <?php if (isset($article)): ?>
                <h1><?= esc($article['title']) ?></h1>
                <div class="meta">ChillMed Team<br><?= esc($article['date']) ?></div>
                <img src="<?= base_url('images/' . $article['image']) ?>">
                <p><?= nl2br(esc($article['content'])) ?></p>
            <?php else: ?>
                <p>Artikel tidak ditemukan.</p>
            <?php endif; ?>
        </div>

        <aside class="sidebar">
            <h2>Artikel Lainnya</h2>
            <?php
            if (isset($relatedArticles) && is_array($relatedArticles)) {
                foreach ($relatedArticles as $relArticle) {
                    if (isset($article['title']) && $relArticle['title'] !== $article['title']) {
                        echo '<a href="' . base_url('artikel/detail/' . url_title($relArticle['title'], '-', TRUE)) . '">' . esc($relArticle['title']) . '</a>';
                    }
                }
            }
            ?>
            <a class="btn-more" href="<?= base_url('artikel') ?>">Selengkapnya</a>
        </aside>
    </main>

</body>
</html>