<?php
$mysqli = new mysqli("localhost", "root", "", "cobaa");

$id = $_GET['id'] ?? null;
$errors = [];
$success = '';

if (!$id) {
    die("ID user tidak ditemukan.");
}

// Ambil data user
$stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User tidak ditemukan.");
}

// Proses update jika form dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $photo = $user['photo']; // default ke foto lama

    // Hash password hanya jika diisi
    if (!empty($password)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    } else {
        $hashedPassword = $user['password'];
    }

    // Cek dan proses foto baru
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = 'uploads/' . uniqid() . '.' . $ext;

        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    // Update ke database
    $update = $mysqli->prepare("UPDATE users SET username = ?, password = ?, photo = ? WHERE id = ?");
    $update->bind_param("sssi", $username, $hashedPassword, $photo, $id);

    if ($update->execute()) {
        $success = "✅ Data berhasil diupdate.";
        // Refresh user data
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    } else {
        $errors[] = "Gagal update user.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit User - Admin</title>
</head>
<body>
    <h2>Edit User (ID: <?= $user['id'] ?>)</h2>

    <?php foreach ($errors as $error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label>Username:</label><br>
        <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br><br>

        <label>Password (isi jika ingin diubah):</label><br>
        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah"><br><br>

        <label>Foto Sekarang:</label><br>
        <?php if ($user['photo']): ?>
            <img src="<?= $user['photo'] ?>" width="100"><br>
        <?php endif; ?>

        <label>Ganti Foto:</label><br>
        <input type="file" name="photo"><br><br>

        <button type="submit">Simpan Perubahan</button>
    </form>

    <p><a href="admin_dashboard.php">← Kembali ke Dashboard</a></p>
</body>
</html>
