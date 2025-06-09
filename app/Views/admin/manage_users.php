<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Manajemen Users') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/manage_userscss.css') ?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <!-- --- Mulai Tambahkan CDN SweetAlert CSS di sini --- -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css">
    <!-- --- Selesai Tambahkan CDN SweetAlert CSS di sini --- -->

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
            <!-- Alert success bawaan masih bisa ditampilkan atau diganti SweetAlert jika diinginkan -->
            <div class="alert success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
            <!-- Alert error bawaan masih bisa ditampilkan atau diganti SweetAlert jika diinginkan -->
            <div class="alert error"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <?php if (!empty($users)): ?>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Tanggal Registrasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr id="user-item-<?= esc($user['id']) ?>">
                            <td data-label="ID"><?= esc($user['id']) ?></td>
                            <td data-label="Nama"><?= esc($user['name']) ?></td>
                            <td data-label="Email"><?= esc($user['email']) ?></td>
                            <td data-label="Role">
                                <?php if ($user['id'] == session()->get('user')['id']): ?>
                                    <span class="current-role"><?= esc($user['role']) ?></span> (Anda)
                                <?php else: ?>
                                    <select class="role-select" data-user-id="<?= esc($user['id']) ?>" data-initial-role="<?= esc($user['role']) ?>">
                                        <option value="user" <?= ($user['role'] === 'user') ? 'selected' : '' ?>>User</option>
                                        <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                <?php endif; ?>
                            </td>
                            <td data-label="Tanggal Registrasi"><?= esc($user['created_at']) ?></td>
                            <td class="item-actions">
                                <?php if ($user['id'] != session()->get('user')['id']): ?>
                                    <!-- Form delete diubah, tanpa onsubmit confirm, akan ditangani oleh JS -->
                                    <form class="delete-user-form" data-user-id="<?= esc($user['id']) ?>" data-user-name="<?= esc($user['name']) ?>" action="<?= base_url('admin/users/delete/' . esc($user['id'])) ?>" method="post" style="display:inline;">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="btn-delete">Hapus</button>
                                    </form>
                                <?php else: ?>
                                    <button class="btn-delete" disabled title="Tidak bisa menghapus akun Anda sendiri">Hapus</button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="no-data-message">Belum ada user terdaftar.</p>
        <?php endif; ?>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- --- Mulai Tambahkan CDN SweetAlert JavaScript di sini --- -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>
    <!-- --- Selesai Tambahkan CDN SweetAlert JavaScript di sini --- -->
    
    <script>
        window.baseUrl = '<?= base_url() ?>';
        // console.log("Base URL for manage_users.js:", window.baseUrl); // Untuk debugging
    </script>
    <script src="<?= base_url('js/manage_users.js') ?>"></script>
</body>
</html>
