<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Edit Quote') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/manage_quotescss.css') ?>"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
</head>
<body>
    <div class="navbar">
        <div class="logo"><span style="color:#00796b;">Chill</span>Med</div>
        <div class="profile">
            <span>Admin</span>
        </div>
    </div>

    <div class="content">
        <a href="<?= base_url('admin/quotes') ?>" class="back-button">
            <i class="fas fa-arrow-left"></i> Kembali ke Manajemen Quotes
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

        <div class="form-container"> <?php if (isset($quote)): ?>
                <form action="<?= base_url('admin/quotes/edit/' . esc($quote['id'])) ?>" method="post">
                    <?= csrf_field() ?>

                    <label for="quote_text">Teks Quote:</label>
                    <textarea id="quote_text" name="quote_text" rows="4" required><?= old('quote_text', $quote['quote_text']) ?></textarea>

                    <label for="author">Penulis (Opsional):</label>
                    <input type="text" id="author" name="author" value="<?= old('author', $quote['author']) ?>">

                    <div class="form-actions"> <a href="<?= base_url('admin/quotes') ?>" class="btn-cancel">Batal</a>
                        <button type="submit" class="btn-submit">Update Quote</button>
                    </div>
                </form>
            <?php else: ?>
                <p>Quote tidak ditemukan.</p>
            <?php endif; ?>
        </div> </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    <script>
    // Tampilkan SweetAlert dari flashdata saat halaman dimuat
    $(document).ready(function() {
        const successMessage = $('.alert.success').text().trim();
        const errorMessage = $('.alert.error').text().trim();
        const errorValidation = $('.alert.error ul').html();

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
        if (errorValidation) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                html: errorValidation,
                showConfirmButton: false,
                timer: 4000
            }).then(() => {
                $('.alert.error').remove();
            });
        } else if (errorMessage) {
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