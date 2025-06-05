<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eating Disorder Self-Check - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/questionstyle.css') ?>">
</head>

<body>
    <div class="container">
        <h1>Eating Disorder Self-Check</h1>
        <form method="post" action="<?= base_url('questions/eatingdisorders') ?>">
            <p>Dalam 2 minggu terakhir, seberapa sering kamu mengalami hal berikut?</p>

            <?php
            $questions = [
                "Saya merasa sangat khawatir tentang berat badan atau bentuk tubuh saya.",
                "Saya merasa tidak nyaman atau tidak puas dengan penampilan tubuh saya.",
                "Saya merasa kehilangan kendali saat makan, seperti tidak bisa berhenti makan meskipun sudah kenyang.",
                "Saya merasa bersalah atau malu setelah makan.",
                "Saya mencoba mengontrol berat badan dengan cara yang tidak sehat (misalnya, memuntahkan makanan, menggunakan obat pencahar, olahraga berlebihan).",
                "Saya menghindari makan di depan orang lain atau di tempat umum.",
                "Pikiran tentang makanan, berat badan, atau bentuk tubuh mendominasi pikiran saya sepanjang hari.",
                "Saya sering berdiet atau membatasi asupan makanan secara ekstrem.",
                "Masalah makan saya berdampak negatif pada kehidupan sosial atau akademik saya."
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
                                <input type='radio' name='q" . ($i + 1) . "' value='$value' required> $label
                            </label><br>"; // <-- PERUBAHAN DI SINI: name='q" . ($i + 1) . "'
                }
                echo "</div><hr>";
            }
            ?>

            <button type="submit">Lihat Hasil</button>
        </form>
    </div>
</body>

</html>