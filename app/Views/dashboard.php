<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/dashboardcss.css') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <header class="navbar">
        <div class="logo"><span>Chill</span>Med</div>
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

    <section class="about-us-section">
        <h2>Tentang Kami</h2>
        <p class="subtitle">Kami membantu mahasiswa memahami, mengelola, dan menenangkan pikiran agar dapat menjalani perkuliahan sebagai individu yang lebih sehat dan bahagia.</p>

        <div class="about-us-content-wrapper">
            <div class="about-us-text-block">
                <h3>Kami ada di sini untuk membantu sebanyak mungkin mahasiswa <strong>berdamai</strong> dengan diri.</h3>
                <p>Bukan hanya mereka yang sudah tangguh secara mental, tetapi setiap individu. Kami menawarkan pendekatan yang lebih mudah diakses dan berkelanjutan, sebagai alternatif dari anggapan "kuat tanpa keluh kesah."</p>
                <p>Kami tidak percaya bahwa kesehatan mental harus dikaitkan dengan perjuangan yang menyakitkan. Memberikan perhatian pada diri adalah sebuah kebutuhan. Ketika dilakukan dengan benar, hal itu tidak hanya meredakan tekanan, tetapi juga membentengi diri dari tantangan ke depan.</p>
            </div>
            <div class="about-us-image-gallery">
                <img src="<?= base_url('images/team1.jpg') ?>" alt="Team 1">
                <img src="<?= base_url('images/team2.jpg') ?>" alt="Team 2">
            </div>
        </div>
    </section>

        <footer class="new-footer">
                <div class="footer-top">
                    <div class="footer-section contact-info">
                        <p><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white' width='18px' height='18px'%3E%3Cpath d='M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5S10.62 6.5 12 6.5s2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z'/%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3C/svg%3E" alt="Location Icon" class="icon">Banjarmasin, Kalimantan Selatan</p>
                        <p><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white' width='18px' height='18px'%3E%3Cpath d='M20.01 15.38c-1.23 0-2.42-.25-3.5-.79-.1-.05-.2-.08-.31-.08-.26 0-.51.1-.71.29l-2.2 2.2c-2.83-1.44-5.15-3.75-6.59-6.59l2.2-2.2c.2-.2.29-.45.29-.71 0-.11-.03-.21-.08-.31-.54-1.08-.79-2.27-.79-3.51 0-.55-.45-1-1-1H3c-.55 0-1 .45-1 1C2 13.11 8.89 22 17.01 22c.55 0 1-.45 1-1v-4.01c0-.55-.45-1-1-1z'/%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3C/svg%3E" alt="Phone Icon" class="icon"> +62 895-3663-12331, +62 821-5535-3602, +62 857-5085-5013</p>
                        <p><img src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='white' width='18px' height='18px'%3E%3Cpath d='M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z'/%3E%3Cpath d='M0 0h24v24H0z' fill='none'/%3E%3C/svg%3E" alt="Email Icon" class="icon"> chillmed@gmail.com</p>
                    </div>

        <div class="footer-section about-company">
            <h3>Ikuti Kami</h3>
            <div class="social-icons">
                <a href="https://www.instagram.com/your_instagram_id" target="_blank" class="social-icon-circle"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
        </div>

        <div class="footer-middle">
            <div class="chillmed-logo-placeholder">
                <div class="logo"><span>Chill</span>Med</div>
            </div>
        </div>

        <div class="footer-bottom-bar">
            <p>&copy; <?= date('Y') ?> ChillMed. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>

    <script>
        
        const quotesData = <?= $quotesJson ?? '[]' ?>;

        let currentQuoteIndex = 0;
        const quoteElement = document.getElementById("quote");

        
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

            quoteElement.style.opacity = 0; 

            setTimeout(() => {
                currentQuoteIndex = (currentQuoteIndex + 1) % displayQuotes.length;
                quoteElement.textContent = `"${displayQuotes[currentQuoteIndex]}"`;
                quoteElement.style.opacity = 1; 
            }, 1000); 
        }

        
        document.addEventListener('DOMContentLoaded', displayInitialQuote);

        
        setInterval(changeQuote, 5000);


        
        document.addEventListener('DOMContentLoaded', function() {
            const logoutButton = document.getElementById('logoutButton');

            logoutButton.addEventListener('click', function(e) {
                e.preventDefault(); 

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
                        
                        window.location.href = '<?= base_url('logout') ?>';
                    }
                });
            });
        });
    </script>
</body>

</html>