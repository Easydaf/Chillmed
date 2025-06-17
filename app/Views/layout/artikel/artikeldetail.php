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
        <div class="logo"><span>Chill</span>Med</div>
        <nav>
            <a href="<?= base_url('dashboard') ?>">Beranda</a>
            <a href="<?= base_url('chatbot') ?>">Chatbot</a>
            <a href="<?= base_url('questions') ?>">Pertanyaan</a>
            <a href="<?= base_url('/') ?>"><button class="btn logout">Logout</button></a>
        </nav>
    </header>

    <main class="container">
        <div class="content">
            <?php if (isset($article)): ?>
                <h1><?= esc($article['title']) ?></h1>
                <div class="meta">
                    Oleh: <?= esc($article['author'] ?? 'ChillMed Team') ?>
                    <br>
                    Diposting: <?= esc(date('d/m/Y, H:i WITA', strtotime($article['created_at']))) ?>
                </div>
                <img src="<?= base_url('images/' . esc($article['image'])) ?>" alt="<?= esc($article['title']) ?>">
                <p><?= nl2br(esc($article['content'])) ?></p>
            <?php else: ?>
                <p>Artikel tidak ditemukan.</p>
            <?php endif; ?>
        </div>

        <aside class="sidebar">
            <h2>Artikel Lainnya</h2>
            <?php
            if (isset($relatedArticles) && is_array($relatedArticles) && !empty($relatedArticles)) {
                foreach ($relatedArticles as $relArticle) {
                    if (isset($article['id']) && $relArticle['id'] !== $article['id']) {
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