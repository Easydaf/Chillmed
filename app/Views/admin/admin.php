<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Dashboard Admin') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

</head>

<body>
    <div class="navbar">
        <div class="logo"><span style="color:#00796b;">Chill</span>Med</div>
        <div class="profile">
            <span>Admin</span>
        </div>
    </div>


    <div class="content">
        <a href="<?= base_url('dashboard') ?>" class="back-button">
            <i class="fas fa-arrow-left"></i> </a>
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