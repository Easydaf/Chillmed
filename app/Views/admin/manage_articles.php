<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Manajemen Artikel') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/manage_articles.css') ?>"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
    <?= csrf_meta() ?>
</head>
<body>
    <div class="navbar">
        <div class="logo"><span style="color:#00796b;">Chill</span>Med</div>
        <div class="profile">
            <span>Admin</span>
        </div>
    </div>

    <div class="content">
        <a href="<?= base_url('admin') ?>" class="back-button">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard Admin
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
        <?php if (session()->getFlashdata('errors')): // Untuk error validasi dari form POST ?>
            <div class="alert error" style="display:none;" data-sweetalert-type="error">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <a href="<?= base_url('admin/articles/add') ?>" class="add-button">+ Tambah Artikel</a>

        <?php if (!empty($articles)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Gambar</th>
                        <th>Judul</th>
                        <th>Penulis</th>
                        <th>Tanggal Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($articles as $article): ?>
                        <tr id="article-item-<?= esc($article['id']) ?>">
                            <td data-label="ID"><?= esc($article['id']) ?></td>
                            <td data-label="Gambar">
                                <?php if ($article['image']): ?>
                                    <img src="<?= base_url('images/' . esc($article['image'])) ?>" alt="Thumbnail" class="article-image-thumb">
                                <?php else: ?>
                                    Tidak ada gambar
                                <?php endif; ?>
                            </td>
                            <td data-label="Judul"><?= esc($article['title']) ?></td>
                            <td data-label="Penulis"><?= esc($article['author'] ?? 'Anonim') ?></td>
                            <td data-label="Tanggal Dibuat"><?= esc($article['created_at']) ?></td>
                            <td class="item-actions">
                                <a href="<?= base_url('admin/articles/edit/' . esc($article['id'])) ?>" class="btn-edit">Edit</a>
                                
                                <button type="button" class="btn-delete" data-article-id="<?= esc($article['id']) ?>" data-article-title="<?= esc($article['title']) ?>">Hapus</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data-message">Belum ada artikel.</p>
        <?php endif; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <script>
        window.baseUrl = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('js/manage_articles.js') ?>"></script>

    <script>
    // Tampilkan SweetAlert dari flashdata saat halaman dimuat
    $(document).ready(function() {
        const successMessage = $('.alert.success').text().trim();
        const errorMessage = $('.alert.error').text().trim(); // Bisa error umum atau error validasi

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
                text: errorMessage,
                showConfirmButton: false,
                timer: 3000
            }).then(() => {
                $('.alert.error').remove();
            });
        }
    });
    </script>
</body>
</html>