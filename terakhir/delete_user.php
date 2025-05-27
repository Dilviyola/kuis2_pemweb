<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "cobaa");

// 🔒 Cek role admin (pastikan session role sudah diset saat login)
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    die("Akses ditolak. Hanya admin yang dapat menghapus user.");
}

$id = $_GET['id'] ?? null;

if (!$id || !is_numeric($id)) {
    die("ID user tidak valid.");
}

// Ambil data user untuk hapus fotonya nanti
$stmt = $mysqli->prepare("SELECT photo FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan.");
}

// Hapus user dari database
$delete = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$delete->bind_param("i", $id);

if ($delete->execute()) {
    // Hapus file foto jika ada
    if (!empty($user['photo']) && file_exists($user['photo'])) {
        unlink($user['photo']);
    }

    // Redirect kembali ke dashboard atau tampilkan pesan
    header("Location: admin_dashboard.php?msg=deleted");
    exit;
} else {
    die("Gagal menghapus user.");
}
?>
