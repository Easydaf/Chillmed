<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Manajemen Quotes') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/manage_quotescss.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                            <button class="btn-edit"
                                    onclick="showEditQuoteModal(
                                        <?= esc($quote['id']) ?>,
                                        '<?= htmlspecialchars(json_encode($quote['quote_text']), ENT_QUOTES, 'UTF-8') ?>',
                                        '<?= htmlspecialchars(json_encode($quote['author'] ?? ''), ENT_QUOTES, 'UTF-8') ?>'
                                    )">Edit</button>
                            <button class="btn-delete" onclick="deleteQuote(<?= esc($quote['id']) ?>)">Hapus</button>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum ada quotes di database.</p>
            <?php endif; ?>
        </div>

        <div class="modal" id="editQuoteModal">
            <div class="modal-content">
                <h3>Edit Quote</h3>
                <form id="editQuoteForm">
                    <input type="hidden" id="edit_quote_id" name="id">
                    <label for="edit_quote_text">Teks Quote</label>
                    <textarea id="edit_quote_text" name="quote_text" rows="4" required></textarea>
                    <label for="edit_author">Penulis (Opsional)</label>
                    <input type="text" id="edit_author" name="author">
                    <div class="modal-actions">
                        <button type="button" class="btn-cancel" onclick="hideEditQuoteModal()">Batal</button>
                        <button type="submit" class="btn-submit">Update</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Pastikan baseUrl ini dideklarasikan di scope window agar manage_quotes.js bisa mengaksesnya
        window.baseUrl = '<?= base_url() ?>';
    </script>
    <script src="<?= base_url('js/manage_quotes.js') ?>"></script>
</body>
</html>