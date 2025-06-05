<?php
include 'db.php';

$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM gejala WHERE id = $id");
header("Location: admin_gejala.php");
