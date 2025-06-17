<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Reset Password - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/logincss.css') ?>" /> <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
</head>

<body>
    <div class="login-container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger" style="display:none;" data-sweetalert-type="error">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success" style="display:none;" data-sweetalert-type="success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <h2>Reset Password</h2>
        <form action="<?= base_url('reset-password') ?>" method="post" class="form-box">
            <?= csrf_field() ?>
            <input type="hidden" name="token" value="<?= esc($token ?? old('token')) ?>">

            <p style="font-size: 0.9em; margin-bottom: 20px;">Masukkan password baru Anda.</p>

            <label for="password" class="form-label">Password Baru</label>
            <input type="password" class="form-control" name="password" required>

            <label for="password_confirm" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" class="form-control" name="password_confirm" required>

            <button type="submit" class="login-btn">Reset Password</button>

            <div class="extra-links" style="margin-top: 15px;">
                <span><a href="<?= base_url('/') ?>">Kembali ke Login</a></span>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>

    <script>
    $(document).ready(function() {
        const successMessage = $('.alert.alert-success').text().trim();
        const errorMessage = $('.alert.alert-danger').text().trim();

        if (successMessage) {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: successMessage,
                showConfirmButton: false,
                timer: 3000
            });
        }
        if (errorMessage) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: errorMessage,
                showConfirmButton: false,
                timer: 5000 
            });
        }
    });
    </script>
</body>

</html>