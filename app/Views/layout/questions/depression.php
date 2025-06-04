<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Depression Self-Check - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/questionstyle.css') ?>">
</head>

<body>
    <div class="container">
        <h1>Depression Self-Check (PHQ-9)</h1>
        <!-- Pastikan action form mengarah ke Questions::submit untuk kategori 'depression' -->
        <form method="post" action="<?= base_url('questions/depression') ?>">
            <p>Dalam 2 minggu terakhir, seberapa sering kamu terganggu oleh masalah-masalah berikut?</p>

            <?php
            $questions = [
                "Sedikit minat atau kesenangan dalam melakukan sesuatu.",
                "Merasa murung, depresi, atau putus asa.",
                "Sulit tidur atau tetap tidur, atau tidur terlalu banyak.",
                "Merasa lelah atau kurang energi.",
                "Nafsu makan berkurang atau makan terlalu banyak.",
                "Merasa tidak enak tentang diri sendiri—atau bahwa kamu adalah kegagalan atau telah mengecewakan diri sendiri atau keluarga.",
                "Sulit berkonsentrasi pada hal-hal, seperti membaca koran atau menonton televisi.",
                "Bergerak atau berbicara begitu lambat sehingga orang lain mungkin memperhatikannya. Atau sebaliknya—begitu gelisah atau gelisah sehingga kamu bergerak jauh lebih banyak dari biasanya.",
                "Pikiran bahwa kamu akan lebih baik mati atau dengan cara tertentu menyakiti diri sendiri."
            ];

            // Pilihan skala 1-5
            $scaleOptions = [
                1 => "Tidak sama sekali",
                2 => "Beberapa hari",
                3 => "Lebih dari separuh hari",
                4 => "Hampir setiap hari",
                5 => "Setiap hari" // Menambahkan opsi 5 untuk "Setiap hari"
            ];

            foreach ($questions as $i => $q) {
                echo "<div class='question'>";
                echo "<label><strong>Pertanyaan " . ($i + 1) . ":</strong> $q</label><br>";
                foreach ($scaleOptions as $value => $label) {
                    echo "<label>
                            <input type='radio' name='q" . $i . "' value='$value' required> $label
                          </label><br>"; // Tambahkan <br> agar pilihan ke bawah
                }
                echo "</div><hr>";
            }
            ?>

            <button type="submit">Lihat Hasil</button>
        </form>
    </div>
</body>

</html>