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
        <?php if (session()->getFlashdata('errors')): // Untuk error validasi dari form POST ?>
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
    $(document).ready(function() {
        const baseUrl = '<?= base_url() ?>';
        const csrfName = $('meta[name="csrf-name"]').attr('content') || '';
        let csrfHash = $('meta[name="csrf-token"]').attr('content') || '';

        // Update CSRF token on AJAX complete (for delete operation)
        $(document).ajaxComplete(function(event, xhr, settings) {
            if (settings.type === 'POST' || settings.type === 'post') {
                const newToken = xhr.getResponseHeader('X-CSRF-TOKEN');
                if (newToken && newToken !== csrfHash) {
                    csrfHash = newToken;
                    $('meta[name="csrf-token"]').attr('content', newToken);
                } else {
                    const currentMetaToken = $('meta[name="csrf-token"]').attr('content');
                    if (currentMetaToken && currentMetaToken !== csrfHash) {
                        csrfHash = currentMetaToken;
                    }
                }
            }
        });

        // Handle klik tombol hapus quote (SweetAlert Konfirmasi)
        $('.btn-delete').on('click', function() {
            const quoteId = $(this).data('quote-id');
            const quoteText = $(this).data('quote-text');

            Swal.fire({
                title: 'Konfirmasi Penghapusan',
                text: `Anda yakin ingin menghapus quote "${quoteText}" (ID: ${quoteId})? Aksi ini tidak dapat dibatalkan!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Warna merah untuk hapus
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika dikonfirmasi, kirim form POST untuk penghapusan (full page refresh)
                    const deleteForm = $(`<form action="${baseUrl}admin/quotes/delete/${quoteId}" method="post"></form>`);
                    
                    if (csrfName && csrfHash) {
                        deleteForm.append(`<input type="hidden" name="${csrfName}" value="${csrfHash}">`);
                    }
                    $('body').append(deleteForm);
                    deleteForm.submit(); // Submit form
                }
            });
        });

        // Tampilkan SweetAlert dari flashdata saat halaman dimuat
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