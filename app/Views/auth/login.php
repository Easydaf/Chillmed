<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ChillMed | Login</title>
    <link rel="stylesheet" href="<?= base_url('css/logincss.css') ?>" />
</head>

<body>
    <div class=" login-container">
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>
        <h2>LogIn</h2>
        <form action="<?= base_url('login') ?>" method="post" class="form-box">
            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" required>

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>

            <button type="submit" class="login-btn">LogIn</button>

            <div class="extra-links">
                <span>Don't Have Account? <a href="<?= base_url('register') ?>">Register</a></span>
            </div>
        </form>
    </div>
</body>

</html>