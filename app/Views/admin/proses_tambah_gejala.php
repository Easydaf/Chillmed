<?php
include 'db.php';

$nama = $_POST['nama'];
$deskripsi = $_POST['deskripsi'];
$resiko = $_POST['resiko'];

mysqli_query($conn, "INSERT INTO gejala (nama, deskripsi, resiko) VALUES ('$nama', '$deskripsi', '$resiko')");
header("Location: admin_gejala.php");
