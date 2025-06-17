<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Tambah Artikel Baru') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/manage_articles.css') ?>">
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
        <a href="<?= base_url('admin/articles') ?>" class="back-button">
            <i class="fas fa-arrow-left"></i> Kembali ke Manajemen Artikel
        </a>
        <h1><?= esc($pageTitle) ?></h1>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert success" style="display:none;" data-sweetalert-type="success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error" style="display:none;" data-sweetalert-type="error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): ?>
            <div class="alert error" style="display:none;" data-sweetalert-type="error">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="<?= base_url('admin/articles/add') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <label for="title">Judul Artikel:</label>
                <input type="text" id="title" name="title" value="<?= old('title') ?>" required>

                <label for="image">Gambar Artikel:</label>
                <input type="file" id="image" name="image" accept="image/*">
                <small>Max 1MB, format: JPG, JPEG, PNG, GIF</small>

                <label for="content">Konten Artikel:</label>
                <textarea id="content" name="content" rows="10" required><?= old('content') ?></textarea>

                <div class="form-actions">
                    <a href="<?= base_url('admin/articles') ?>" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Artikel</button>
                </div>
            </form>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <script>

    $(document).ready(function() {
        const successMessage = $('.alert.success').text().trim();
        const errorMessage = $('.alert.error').text().trim();

        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                showConfirmButton: false,
                timer: 2000
            }).then(() => {
                $('.alert.success').remove();
            });
        }
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: errorMessage.startsWith('<ul>') ? errorMessage : `<p>${errorMessage}</p>`, 
                showConfirmButton: false,
                timer: 4000 
            }).then(() => {
                $('.alert.error').remove();
            });
        }
    });
    </script>
</body>
</html>