<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pilih Pertanyaan</title>
    <link rel="stylesheet" href="<?= base_url('css/questionscss.css') ?>" >
</head>

<body>
    <header>
        <div class="logo"><span>Chill</span>Med</div>
    </header>

    <main>
        <h1>Questions For You</h1>
        <div class="grid-container">
            <!-- Ubah href untuk setiap kartu agar mengarah ke rute yang benar -->
            <a href="<?= base_url('questions/anxiety') ?>" class="card">🧠 Anxiety Disorder <br><span>(Gangguan Kecemasan)</span></a>
            <a href="<?= base_url('questions/depression') ?>" class="card">💧 Depression <br><span>(Depresi)</span></a>
            <a href="<?= base_url('questions/ducksyndrome') ?>" class="card">📘 Duck Syndrome <br><span>(Terlalu Banyak Pikiran)</span></a> <!-- Asumsi ini untuk Duck Syndrome -->
            <a href="<?= base_url('questions/insomnia') ?>" class="card">🛌 Insomnia <br><span>(Gangguan Tidur)</span></a>
            <a href="<?= base_url('questions/eatingdisorder') ?>" class="card">🍽️ Eating Disorder <br><span>(Gangguan Pola Makan)</span></a>
        </div>
    </main>
</body>

</html>
