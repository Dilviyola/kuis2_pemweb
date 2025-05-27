<?php
session_start();
$mysqli = new mysqli("localhost", "root", "", "cobaa");

// 🔒 Pastikan user login dan memiliki role customer
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'customer') {
    die("Akses ditolak. Hanya customer yang dapat mengedit profil ini.");
}

$id = $_SESSION['id']; // Ambil ID dari session user
$success = '';
$error = '';

// Ambil data user dari database
$stmt = $mysqli->prepare("SELECT photo, username FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan.");
}

// Jika form dikirim
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $newPhoto = 'uploads/' . uniqid() . '.' . $ext;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        move_uploaded_file($_FILES['photo']['tmp_name'], $newPhoto);

        // Hapus foto lama jika ada
        if (!empty($user['photo']) && file_exists($user['photo'])) {
            unlink($user['photo']);
        }

        // Update foto di database
        $update = $mysqli->prepare("UPDATE users SET photo = ? WHERE id = ?");
        $update->bind_param("si", $newPhoto, $id);

        if ($update->execute()) {
            $success = "✅ Foto profil berhasil diperbarui.";
            $user['photo'] = $newPhoto; // update tampilan
        } else {
            $error = "❌ Gagal memperbarui foto profil.";
        }
    } else {
        $error = "❌ Foto tidak valid atau belum dipilih.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profil Customer</title>
</head>
<body>
    <h2>Edit Foto Profil - <?= htmlspecialchars($user['username']) ?></h2>

    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php elseif ($error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Foto Saat Ini:</label><br>
        <?php if ($user['photo']): ?>
            <img src="<?= htmlspecialchars($user['photo']) ?>" width="120"><br>
        <?php else: ?>
            <em>Belum ada foto</em><br>
        <?php endif; ?>

        <label>Ganti Foto Baru:</label><br>
        <input type="file" name="photo" accept="image/*" required><br><br>

        <button type="submit">Simpan</button>
    </form>

    <p><a href="customer_dashboard.php">← Kembali ke Dashboard</a></p>
</body>
</html>
