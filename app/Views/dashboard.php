<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/dashboardcss.css') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
</head>

<body>
    <header class="navbar">
        <div class="logo"><strong>Chill</strong>Med</div>
        <div class="nav-buttons">
            <?php
            if (session()->get('isLoggedIn') && session()->get('user')['role'] === 'admin'):
            ?>
                <a href="<?= base_url('admin') ?>" class="btn admin">Admin Dashboard</a>
            <?php endif; ?>
            <a href="#" class="btn logout" id="logoutButton">Logout</a>
        </div>
    </header>
    <section class="hero">
        <div class="quote-top">
            <p id="quote">"Memuat quote..."</p>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>

    <script>
        // Menerima data quotes dari PHP (JSON string) dan menggunakannya di JavaScript
        const quotesData = <?= $quotesJson ?? '[]' ?>;

        let currentQuoteIndex = 0;
        const quoteElement = document.getElementById("quote");

        // Fallback quotes jika database kosong
        const fallbackQuotes = [
            "Kamu cukup, bahkan ketika kamu merasa tidak.",
            "Satu langkah kecil tetaplah kemajuan.",
            "Izinkan dirimu beristirahat, bukan menyerah.",
            "Pikiranmu tidak selamanya benar, terutama saat kamu lelah.",
            "Hari yang berat bukan berarti hidup yang buruk.",
            "sebenarnya kau hebat",
            "semua patut disyukuri",
            "Bersyukur aja nanti juga dapat yang lebih baik kok."
        ];

        // Gunakan quotes dari database jika ada, jika tidak, gunakan fallback
        const displayQuotes = quotesData.length > 0 ? quotesData : fallbackQuotes;

        function displayInitialQuote() {
            if (displayQuotes.length > 0) {
                quoteElement.textContent = `"${displayQuotes[currentQuoteIndex]}"`;
                quoteElement.style.opacity = 1;
            } else {
                quoteElement.textContent = `"Tidak ada quote yang tersedia."`;
                quoteElement.style.opacity = 1;
            }
        }

        function changeQuote() {
            if (displayQuotes.length === 0) return;

            quoteElement.style.opacity = 0; // Fade out

            setTimeout(() => {
                currentQuoteIndex = (currentQuoteIndex + 1) % displayQuotes.length;
                quoteElement.textContent = `"${displayQuotes[currentQuoteIndex]}"`;
                quoteElement.style.opacity = 1; // Fade in
            }, 1000); // Durasi fade
        }

        // Tampilkan quote pertama kali saat DOMContentLoaded
        document.addEventListener('DOMContentLoaded', displayInitialQuote);

        // Ganti quote setiap 5 detik
        setInterval(changeQuote, 5000);


        // SweetAlert Konfirmasi Logout
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButton = document.getElementById('logoutButton');

            logoutButton.addEventListener('click', function(e) {
                e.preventDefault(); // Mencegah navigasi langsung
                
                Swal.fire({
                    title: 'Konfirmasi Logout',
                    text: 'Anda yakin ingin logout dari akun ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#00796b',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Logout!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Jika user mengkonfirmasi, baru lakukan navigasi ke URL logout
                        window.location.href = '<?= base_url('logout') ?>';
                    }
                });
            });
        });
    </script>
</body>

</html>