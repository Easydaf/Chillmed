<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insomnia Self-Check - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/questionstyle.css') ?>">
</head>

<body>
    <div class="container">
        <h1>Insomnia Self-Check</h1>
        <form method="post" action="<?= base_url('questions/insomnia') ?>">
            <p>Dalam 2 minggu terakhir, seberapa sering kamu mengalami hal berikut?</p>

            <?php
            $questions = [
                "Saya kesulitan untuk tertidur di malam hari.",
                "Saya sering terbangun di tengah malam dan sulit tidur kembali.",
                "Saya terbangun terlalu pagi dan tidak bisa tidur lagi.",
                "Saya merasa tidak segar atau lelah setelah bangun tidur.",
                "Saya merasa mengantuk di siang hari, meskipun sudah mencoba tidur cukup.",
                "Masalah tidur saya memengaruhi konsentrasi atau fokus saya di kampus/pekerjaan.",
                "Saya merasa mudah tersinggung atau *moody* karena kurang tidur.",
                "Saya merasa khawatir atau cemas tentang waktu tidur saya.",
                "Masalah tidur saya berdampak negatif pada kualitas hidup saya secara keseluruhan."
            ];

            
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