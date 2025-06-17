<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillMed | Login</title>
    <link rel="stylesheet" href="<?= base_url('css/logincss.css') ?>" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
</head>

<body>
    <div class=" login-container">
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
        <h2>Login</h2>
        <form action="<?= base_url('login') ?>" method="post" class="form-box">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" required>

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>

            <button type="submit" class="login-btn">Login</button>

            <div class="extra-links">
                <span>Don't Have Account? <a href="<?= base_url('register') ?>">Register</a></span> <br>
                <span>Forgot Password? <a href="<?= base_url('forgot-password') ?>" style="text-decoration: none; color: #00725e; font-weight: bold;">Lupa Password?</a></span>
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
                    timer: 2000
                }).then(() => {
                    
                    $('.alert.alert-success').remove();
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
                    
                    $('.alert.alert-danger').remove();
                });
            }
        });
    </script>
</body>

</html>