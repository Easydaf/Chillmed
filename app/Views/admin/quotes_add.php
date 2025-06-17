<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Tambah Quote Baru') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* CSS spesifik untuk form, bisa dipindahkan ke admincss.css jika ingin diglobalisasi */
        .form-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            max-width: 800px;
            margin: 30px auto;
        }
        .form-container label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #555;
        }
        .form-container input[type="text"],
        .form-container textarea {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1em;
        }
        .form-container textarea {
            min-height: 150px;
            resize: vertical;
        }
        .form-actions {
            text-align: right;
            margin-top: 20px;
        }
        .btn-submit {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }
        .btn-submit:hover {
            background-color: #45a049;
        }
        .btn-cancel {
            background-color: #9e9e9e;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            text-decoration: none;
            margin-left: 10px;
            transition: background-color 0.3s;
        }
        .btn-cancel:hover {
            background-color: #757575;
        }
    </style>
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
            <div class="alert success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('errors')): // Untuk error validasi dari form POST ?>
            <div class="alert error">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="form-container">
            <form action="<?= base_url('admin/quotes/add') ?>" method="post">
                <?= csrf_field() ?>

                <label for="quote_text">Teks Quote:</label>
                <textarea id="quote_text" name="quote_text" rows="4" required><?= old('quote_text') ?></textarea>

                <div class="form-actions">
                    <a href="<?= base_url('admin/quotes') ?>" class="btn-cancel">Batal</a>
                    <button type="submit" class="btn-submit">Simpan Quote</button>
                </div>
            </form>
        </div>

    </div>
</body>
</html>