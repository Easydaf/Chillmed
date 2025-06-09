<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle ?? 'Manajemen Users') ?></title>
    <link rel="stylesheet" href="<?= base_url('css/admincss.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/manage_userscss.css') ?>"> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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
                                    <select class="role-select" data-user-id="<?= esc($user['id']) ?>">
                                        <option value="user" <?= ($user['role'] === 'user') ? 'selected' : '' ?>>User</option>
                                        <option value="admin" <?= ($user['role'] === 'admin') ? 'selected' : '' ?>>Admin</option>
                                    </select>
                                <?php endif; ?>
                            </td>
                            <td data-label="Tanggal Registrasi"><?= esc($user['created_at']) ?></td>
                            <td class="item-actions">
                                <?php if ($user['id'] != session()->get('user')['id']): ?>
                                    <form action="<?= base_url('admin/users/delete/' . esc($user['id'])) ?>" method="post" style="display:inline;" onsubmit="return confirm('Anda yakin ingin menghapus user <?= esc($user['name']) ?>?');">
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
    <script>
        window.baseUrl = '<?= base_url() ?>';
        // console.log("Base URL for manage_users.js:", window.baseUrl); // Untuk debugging
    </script>
    <script src="<?= base_url('js/manage_users.js') ?>"></script>
</body>
</html>