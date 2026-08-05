<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "keluar_barang";

$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>