<?php
$mysqli = new mysqli("localhost", "root", "", "cobaa");

$role = $_GET['role'] ?? 'customer';
$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $photo = '';

    // Cek apakah username sudah digunakan
    $checkStmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $checkStmt->bind_param("s", $username);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        $errors[] = "Username '$username' sudah digunakan. Silakan pilih yang lain.";
    } else {
        // Upload photo jika tersedia
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photo = 'uploads/' . uniqid() . '.' . $ext;

            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }

            move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
        }

        $stmt = $mysqli->prepare("INSERT INTO users (username, password, photo, role) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $hashedPassword, $photo, $role);

        if ($stmt->execute()) {
            $success = "✅ Registrasi berhasil! <a href='login.php?role=$role'>Klik di sini untuk login</a>.";
        } else {
            $errors[] = "Gagal mendaftar karena error database.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - <?= htmlspecialchars($role) ?></title>
</head>
<body>
    <h2>Register as <?= htmlspecialchars(ucfirst($role)) ?></h2>

    <?php foreach ($errors as $error): ?>
        <p style="color:red;"><?= $error ?></p>
    <?php endforeach; ?>

    <?php if ($success): ?>
        <p style="color:green;"><?= $success ?></p>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <input type="text" name="username" required placeholder="Username"><br><br>
        <input type="password" name="password" required placeholder="Password"><br><br>
        <input type="file" name="photo" accept="image/*"><br><br>
        <button type="submit">Register</button>
    </form>
</body>
</html>
