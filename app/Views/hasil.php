<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Tes - ChillMed</title>
    <!-- Menambahkan font Inter dari Google Fonts -->
    <link href="<?= base_url('css/hasilcss.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="result-card">
            <h1>Hasil Tes Anda</h1>
            
            <div>
                <p class="score-label">Skor Total:</p>
                <p class="score-value"><?= esc($skor) ?></p>
            </div>
            
            <div>
                <p class="result-text"><?= esc($hasil) ?></p>
            </div>
            
            <a href="<?= base_url('dashboard') ?>" class="back-button">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>
