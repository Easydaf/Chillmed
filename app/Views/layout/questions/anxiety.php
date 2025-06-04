<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anxiety Disorder Test - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/questionstyle.css') ?>">
</head>

<body>
    <div class="container">
        <h1>Anxiety Disorder Self-Check</h1>
        <!-- Ubah action form agar mengarah ke Questions::submit -->
        <form method="post" action="<?= base_url('questions/anxiety') ?>">
            <p>Dalam 2 minggu terakhir, seberapa sering kamu mengalami hal berikut?</p>

            <?php
            $questions = [
                "Saya merasa gugup, cemas, atau tegang.",
                "Saya tidak dapat menghentikan atau mengontrol rasa khawatir.",
                "Saya terlalu sering khawatir tentang berbagai hal.",
                "Saya kesulitan untuk bersantai.",
                "Saya merasa gelisah atau sulit diam.",
                "Saya mudah terganggu atau mudah marah.",
                "Saya takut sesuatu yang buruk akan terjadi.",
                "Saya mengalami ketegangan fisik seperti jantung berdebar atau berkeringat.",
                "Kecemasan saya mengganggu aktivitas sehari-hari saya."
            ];

            foreach ($questions as $i => $q) {
                echo "<div class='question'>";
                // Perhatikan nama input: q0, q1, dst. Jika ingin q1, q2, dst. ubah $i menjadi ($i + 1)
                echo "<label><strong>Pertanyaan " . ($i + 1) . ":</strong> $q</label><br>";
                for ($j = 1; $j <= 5; $j++) {
                    echo "<label>
                            <input type='radio' name='q" . $i . "' value='$j' required> $j
                          </label> ";
                }
                echo "</div><hr>";
            }
            ?>

            <button type="submit">Lihat Hasil</button>
        </form>
    </div>
</body>

</html>