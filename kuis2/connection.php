<?php

$host = "127.0.0.1"; // gunakan 127.0.0.1 jika localhost bermasalah
$user = "root";
$password = "";
$database = "kuis2";

$conn = mysqli_connect($host, $user, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Debug: sukses
// echo "Koneksi ke database berhasil!";
?>
