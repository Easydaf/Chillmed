<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Burnout Self-Check - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/questionstyle.css') ?>">
</head>

<body>
    <div class="container">
        <h1>Burnout Self-Check</h1>
        <!-- Pastikan action form mengarah ke Questions::submit untuk kategori 'burnout' -->
        <form method="post" action="<?= base_url('questions/burnout') ?>">
            <p>Dalam 2 minggu terakhir, seberapa sering kamu mengalami hal berikut?</p>

            <?php
            $questions = [
                "Saya merasa lelah secara fisik dan emosional hampir setiap hari.",
                "Saya merasa sinis atau tidak peduli terhadap kuliah atau tugas-tugas saya.",
                "Motivasi saya untuk belajar atau menyelesaikan pekerjaan sangat menurun.",
                "Saya kesulitan berkonsentrasi atau mengingat hal-hal yang berkaitan dengan studi.",
                "Saya merasa tidak efektif atau tidak mampu dalam pekerjaan akademik saya.",
                "Saya sering menunda-nunda tugas atau merasa kewalahan dengan beban kerja.",
                "Saya merasa terisolasi atau menarik diri dari teman dan kegiatan sosial.",
                "Saya mengalami masalah tidur (sulit tidur, sering terbangun, atau tidur terlalu banyak) karena stres.",
                "Saya sering merasa frustrasi atau mudah marah terkait dengan tuntutan akademik."
            ];

            // Pilihan skala 1-5
            $scaleOptions = [
                1 => "Tidak sama sekali",
                2 => "Beberapa hari",
                3 => "Lebih dari separuh hari",
                4 => "Hampir setiap hari",
                5 => "Setiap hari"
            ];

            foreach ($questions as $i => $q) {
                echo "<div class='question'>";
                echo "<label><strong>Pertanyaan " . ($i + 1) . ":</strong> $q</label><br>";
                foreach ($scaleOptions as $value => $label) {
                    echo "<label>
                            <input type='radio' name='q" . $i . "' value='$value' required> $label
                          </label><br>";
                }
                echo "</div><hr>";
            }
            ?>

            <button type="submit">Lihat Hasil</button>
        </form>
    </div>
</body>

</html>