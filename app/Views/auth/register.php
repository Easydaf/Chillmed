<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - ChillMed</title>
    <link rel="stylesheet" href="<?= base_url('css/registercss.css') ?>" />
</head>

<body>
    <div class="signup-container">
        <form class="form-box" action="<?= base_url('register') ?>" method="post"><?= csrf_field() ?> 
            <h2>Register</h2>

            <label for="name" class="form-label">Full Name</label>
            <input type="text" class="form-control" name="name" required>

            <label for="email" class="form-label">Email address</label>
            <input type="email" class="form-control" name="email" required>

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" required>

            <button type="submit" class="btn">Register</button>

            <p class="small-text">
                Already Have Account? <a href="<?= base_url('/') ?>">LogIn</a>
            </p>
        </form>
    </div>
</body>

</html>