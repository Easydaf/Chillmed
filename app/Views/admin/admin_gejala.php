<?php
include 'db.php';
$gejala = mysqli_query($conn, "SELECT * FROM gejala ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>ChillMed - Data Gejala</title>
  <link rel="stylesheet" href="admin_gejala.css">
</head>
<body>
  <!-- Navbar -->
  <div class="navbar">
    <div class="logo"><span style="color:#00796b;">Chill</span>Med</div>
    <div class="profile">
      <span>Admin</span>
      <img src="https://via.placeholder.com/32x32" style="border-radius: 50%;" />
    </div>
  </div>

  <!-- Sidebar -->
  <div class="sidebar">
    <a href="#" class="active">Data Gejala</a>
    <a href="#">Data Quote</a>
    <a href="#">Data Artikel</a>
    <a href="#">Logout</a>
  </div>

  <!-- Konten -->
  <div class="content">
    <button class="btn-tambah" onclick="showModal()">+ Tambah Gejala</button>

    <?php while($row = mysqli_fetch_assoc($gejala)) : ?>
    <div class="card">
      <strong><?= htmlspecialchars($row['nama']) ?></strong>
      <p><?= htmlspecialchars($row['deskripsi']) ?></p>
      <span class="badge <?= strtolower($row['resiko']) ?>"><?= htmlspecialchars($row['resiko']) ?></span>
      <div class="actions">
        <a href="proses_edit.php?id=<?= $row['id'] ?>" class="btn-edit">Edit</a>
        <a href="proses_hapus.php?id=<?= $row['id'] ?>" class="btn-hapus" onclick="return confirm('Hapus data ini?')">Hapus</a>
      </div>
    </div>
    <?php endwhile; ?>
  </div>

  <!-- Modal Tambah -->
  <div class="modal" id="modalForm">
    <div class="modal-content">
      <h3>Tambah Gejala</h3>
      <form action="proses_tambah.php" method="POST">
        <label>Nama Gejala</label>
        <input type="text" name="nama" required>
        <label>Deskripsi</label>
        <textarea name="deskripsi" rows="3" required></textarea>
        <label>Tingkat Resiko</label>
        <input type="text" name="resiko" placeholder="Tinggi / Sedang / Rendah" required>
        <div class="modal-actions">
          <button type="button" class="btn-cancel" onclick="hideModal()">Batal</button>
          <button type="submit" class="btn-submit">Simpan</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function showModal() {
      document.getElementById("modalForm").style.display = "block";
    }
    function hideModal() {
      document.getElementById("modalForm").style.display = "none";
    }
    window.onclick = function(event) {
      const modal = document.getElementById("modalForm");
      if (event.target == modal) hideModal();
    }
  </script>
</body>
</html>
