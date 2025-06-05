<?php
include 'db.php';

$id = $_GET['id'];
$query = mysqli_query($conn, "SELECT * FROM gejala WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST['nama'];
  $deskripsi = $_POST['deskripsi'];
  $resiko = $_POST['resiko'];
  mysqli_query($conn, "UPDATE gejala SET nama='$nama', deskripsi='$deskripsi', resiko='$resiko' WHERE id=$id");
  header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Gejala</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="content" style="margin: 2rem;">
    <h2>Edit Gejala</h2>
    <form method="POST">
      <label>Nama Gejala</label>
      <input type="text" name="nama" value="<?= htmlspecialchars($data['nama']) ?>" required>
      <label>Deskripsi</label>
      <textarea name="deskripsi" required><?= htmlspecialchars($data['deskripsi']) ?></textarea>
      <label>Tingkat Resiko</label>
      <input type="text" name="resiko" value="<?= htmlspecialchars($data['resiko']) ?>" required>
      <br><br>
      <button type="submit" class="btn-submit">Simpan</button>
      <a href="admin_gejala.php" class="btn-cancel">Batal</a>
    </form>
  </div>
</body>
</html>
