<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Duck Syndrome Self-Check - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/questionstyle.css') ?>">
</head>

<body>
    <div class="container">
        <h1>Duck Syndrome Self-Check</h1>
        <!-- Pastikan action form mengarah ke Questions::submit untuk kategori 'ducksyndrome' -->
        <form method="post" action="<?= base_url('questions/ducksyndrome') ?>">
            <p>Dalam 2 minggu terakhir, seberapa sering kamu mengalami hal berikut?</p>

            <?php
            $questions = [
                "Saya merasa perlu untuk selalu terlihat baik-baik saja dan bahagia di mata orang lain.",
                "Saya menyembunyikan kesulitan atau tekanan yang saya alami dari teman atau keluarga.",
                "Saya merasa cemas tentang bagaimana orang lain memandang saya atau kinerja saya.",
                "Saya sering membandingkan diri dengan orang lain di media sosial dan merasa kurang.",
                "Saya merasa harus mencapai standar yang sangat tinggi (perfeksionis) dalam segala hal.",
                "Saya sulit meminta bantuan meskipun saya merasa kewalahan.",
                "Saya merasa lelah secara emosional karena terus-menerus 'berpura-pura' baik-baik saja.",
                "Saya takut mengecewakan orang lain jika mereka tahu saya sedang tidak baik-baik saja.",
                "Saya merasa sendirian dalam menghadapi masalah saya, meskipun saya dikelilingi banyak orang."
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