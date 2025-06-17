<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Manajemen Quotes') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/manage_quotescss.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
        <?php if (session()->getFlashdata('errors')):  ?>
            <div class="alert error" style="display:none;" data-sweetalert-type="error">
                <ul>
                    <?php foreach (session()->getFlashdata('errors') as $error): ?>
                        <li><?= esc($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>


        <a href="<?= base_url('admin/quotes/add') ?>" class="add-button">+ Tambah Quote</a>

        <div class="data-list" id="quotes-list">
            <?php if (!empty($quotes)): ?>
                <?php foreach ($quotes as $quote): ?>
                    <div class="data-item" id="quote-item-<?= esc($quote['id']) ?>">
                        <div class="data-item-content">
                            <strong>"<?= esc($quote['quote_text']) ?>"</strong>
                            <span>- <?= esc($quote['author'] ?? 'Anonim') ?> (ID: <?= esc($quote['id']) ?>)</span>
                        </div>
                        <div class="item-actions">
                            <a href="<?= base_url('admin/quotes/edit/' . esc($quote['id'])) ?>" class="btn-edit">Edit</a>
                            
                            <button type="button" class="btn-delete" data-quote-id="<?= esc($quote['id']) ?>" data-quote-text="<?= esc($quote['quote_text']) ?>">Hapus</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada quotes di database.</p>
            <?php endif; ?>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
    
    <script>
        
        window.baseUrl = '<?= base_url() ?>'; 
    </script>
    <script src="<?= base_url('js/manage_quotes.js') ?>"></script>
</body>
</html>