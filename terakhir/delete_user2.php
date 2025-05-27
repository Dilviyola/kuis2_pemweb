<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "cobaa");

// 🔒 Cek apakah yang login adalah admin
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Hanya admin yang dapat menghapus user.");
}

// Pastikan ada ID user yang ingin dihapus (dikirim via GET)
if (!isset($_GET['id'])) {
    die("ID user tidak ditemukan.");
}

$user_id = (int) $_GET['id'];

// Cek apakah user yang akan dihapus ada di database
$stmt = $mysqli->prepare("SELECT photo, role FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan.");
}

// 🔒 Jangan izinkan admin menghapus sesama admin
if ($user['role'] === 'admin') {
    die("Tidak bisa menghapus sesama admin.");
}

// Hapus foto dari folder (jika ada)
if (!empty($user['photo']) && file_exists($user['photo'])) {
    unlink($user['photo']);
}

// Hapus user dari database
$delete = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$delete->bind_param("i", $user_id);

if ($delete->execute()) {
    header("Location: admin_dashboard.php?msg=hapus_sukses");
    exit;
} else {
    die("Gagal menghapus user.");
}
?>
