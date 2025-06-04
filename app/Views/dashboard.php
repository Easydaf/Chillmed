<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/dashboardcss.css') ?>" />
</head>

<body>
    <header class="navbar">
        <div class="logo">Chill<span>Med</span></div>
        <div class="nav-buttons">
            <a href="<?= base_url('/') ?>" class="btn-logout">Logout</a>
        </div>
    </header>
    <section class="hero">
        <div class="quote-top">
            <p id="quote">"Kamu cukup, bahkan ketika kamu merasa tidak."</p>
        </div>
    </section>

    <section class="features-section">
        <h2>Fitur Unggulan</h2>
        <div class="feature-cards">
            <div class="feature-card">
                <a href="<?= base_url('chatbot') ?>" name="chatbot">
                    <h3>Chatbot</h3>
                    <p>
                        Deteksi dini gejala mental health dengan percakapan interaktif.
                    </p>
                </a>
            </div>
            <div class="feature-card">
                <a href="<?= base_url('questions') ?>" name="questions">
                    <h3>Pertanyaan Lanjutan</h3>
                    <p>Jawab pertanyaan mendalam berdasarkan hasil chatbot.</p>
                </a>
            </div>
            <div class="feature-card">
                <a href="<?= base_url('artikel') ?>" name="chatbot">
                    <h3>Artikel Penyemangat</h3>
                    <p>Baca artikel dari sumber terpercaya untuk menguatkan diri.</p>
                </a>
            </div>
        </div>
    </section>
    <script src="<?= base_url('js/quotes.js') ?>"></script>
</body>

</html>