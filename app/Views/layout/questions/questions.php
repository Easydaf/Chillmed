<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pilih Pertanyaan</title>
    <link rel="stylesheet" href="<?= base_url('css/questionscss.css') ?>">
</head>

<body>
    <header class="navbar">
        <div class="logo"><span>Chill</span>Med</div>
        <nav>
            <a href="<?= base_url('dashboard') ?>">Beranda</a>
            <a href="<?= base_url('chatbot') ?>">Chatbot</a>
            <a href="<?= base_url('artikel') ?>">Artikel</a>
            <a href="<?= base_url('/') ?>"><button class="btn logout">Logout</button></a>
        </nav>
    </header>

    <main>
        <h1>Questions For You</h1>
        <div class="grid-container">
            <a href="<?= base_url('questions/anxiety') ?>" class="card">🧠 Anxiety Disorder <br><span>(Gangguan Kecemasan)</span></a>
            <a href="<?= base_url('questions/depression') ?>" class="card">💧 Depression <br><span>(Depresi)</span></a>
            <a href="<?= base_url('questions/burnout') ?>" class="card">🔥 Burnout <br><span>(Kelelahan Mental)</span></a>
            <a href="<?= base_url('questions/ducksyndrome') ?>" class="card">📘 Duck Syndrome <br><span>(Terlalu Banyak Pikiran)</span></a>
            <a href="<?= base_url('questions/insomnia') ?>" class="card">🛌 Insomnia <br><span>(Gangguan Tidur)</span></a>
            <a href="<?= base_url('questions/eatingdisorders') ?>" class="card">🍽️ Eating Disorder <br><span>(Gangguan Pola Makan)</span></a>
        </div>
    </main>
</body>

</html>