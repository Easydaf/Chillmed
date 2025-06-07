<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Dashboard Admin') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admin_dashboard.css') ?>">
    <style>
        /* CSS Dasar untuk layout admin, bisa dipindahkan ke admin_dashboard.css */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            background-color: #f4f7f6;
            display: flex; /* Untuk layout sidebar dan konten */
            min-height: 100vh;
        }
        .navbar {
            background-color: #ffffff;
            padding: 15px 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 60px;
        }
        .navbar .logo {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .navbar .logo span {
            color: #00796b; /* Warna khas ChillMed */
        }
        .navbar .profile {
            display: flex;
            align-items: center;
        }
        .navbar .profile span {
            margin-right: 10px;
            font-weight: bold;
            color: #555;
        }
        .navbar .profile img {
            border-radius: 50%;
            border: 1px solid #ddd;
        }
        .sidebar {
            width: 220px; /* Lebar sidebar */
            background-color: #2c3e50; /* Warna gelap untuk sidebar */
            padding-top: 80px; /* Offset untuk navbar tetap */
            color: #ecf0f1; /* Warna teks terang */
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .sidebar a {
            padding: 15px 20px;
            text-decoration: none;
            color: #ecf0f1;
            display: block;
            transition: background-color 0.3s;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #34495e; /* Warna hover/aktif */
            border-left: 5px solid #00796b; /* Garis indikator aktif */
            padding-left: 15px; /* Sesuaikan padding setelah border */
        }
        .content {
            margin-left: 220px; /* Offset untuk sidebar */
            padding: 80px 30px 30px 30px; /* Offset untuk navbar + padding konten */
            flex-grow: 1; /* Konten mengambil sisa ruang */
        }
        h1 {
            color: #00796b;
            margin-bottom: 25px;
            font-size: 2em;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            font-weight: bold;
            opacity: 0.9;
        }
        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        /* Card untuk ringkasan di dashboard */
        .summary-cards {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }
        .summary-card {
            background-color: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            text-align: center;
            flex: 1;
            min-width: 220px; /* Ukuran minimum agar responsif */
            transition: transform 0.2s ease;
        }
        .summary-card:hover {
            transform: translateY(-5px);
        }
        .summary-card h3 {
            color: #00796b;
            margin-bottom: 10px;
            font-size: 1.2em;
        }
        .summary-card p {
            font-size: 2.5em;
            font-weight: bold;
            color: #333;
            margin: 0;
        }
        .action-buttons {
            margin-top: 20px;
        }
        .action-buttons a {
            display: inline-block;
            background-color: #26a69a;
            color: white;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            margin-right: 15px;
            transition: background-color 0.3s ease;
        }
        .action-buttons a:hover {
            background-color: #00796b;
        }

        /* Responsiveness */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: static;
                padding-top: 0;
                box-shadow: none;
            }
            .sidebar a {
                border-left: none !important;
                text-align: center;
                border-bottom: 1px solid #34495e;
            }
            .content {
                margin-left: 0;
                padding-top: 20px;
            }
            .navbar {
                position: static;
                height: auto;
                flex-direction: column;
                padding: 10px;
            }
            .navbar .profile {
                margin-top: 10px;
            }
            .summary-cards {
                flex-direction: column;
            }
            .summary-card {
                min-width: unset;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="navbar">
        <div class="logo"><span style="color:#00796b;">Chill</span>Med</div>
        <div class="profile">
            <span>Admin</span>
            <img src="https://via.placeholder.com/32x32" alt="Admin Profile" />
        </div>
    </div>

    <div class="sidebar">
        <a href="<?= base_url('admin') ?>" class="active">Dashboard</a>
        <a href="<?= base_url('admin/quotes') ?>">Manajemen Quotes</a>
        <a href="<?= base_url('admin/articles') ?>">Manajemen Artikel</a>
        <a href="<?= base_url('logout') ?>">Logout</a>
    </div>

    <div class="content">
        <h1><?= esc($pageTitle) ?></h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <div class="summary-cards">
            <div class="summary-card">
                <h3>Total Quotes</h3>
                <p><?= esc($totalQuotes ?? 0) ?></p>
            </div>
            <div class="summary-card">
                <h3>Total Artikel</h3>
                <p><?= esc($totalArticles ?? 0) ?></p>
            </div>
        </div>

        <h2>Aksi Cepat</h2>
        <div class="action-buttons">
            <a href="<?= base_url('admin/quotes') ?>">Kelola Quotes</a>
            <a href="<?= base_url('admin/articles') ?>">Kelola Artikel</a>
        </div>
        </div>
</body>
</html>