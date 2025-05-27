<?php
session_start();
if ($_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit;
}

require 'password_hash.php'; // untuk fungsi hashPassword
$mysqli = new mysqli("localhost", "root", "", "your_database_name");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = hashPassword($_POST['password']);
    $role = $_POST['role'];

    // upload foto
    $photoPath = "";
    if ($_FILES['photo']['name']) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $photoPath = $targetDir . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photoPath);
    }

    $stmt = $mysqli->prepare("INSERT INTO users (username, password, photo, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $username, $password, $photoPath, $role);
    $stmt->execute();

    header("Location: crud_admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head><title>Create User</title></head>
<body>
    <h2>Add New User</h2>
    <form method="post" enctype="multipart/form-data">
        Username: <input type="text" name="username" required><br><br>
        Password: <input type="password" name="password" required><br><br>
        Role:
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="customer">Customer</option>
        </select><br><br>
        Photo: <input type="file" name="photo" accept="image/*"><br><br>
        <button type="submit">Create</button>
    </form>
    <br><a href="crud_admin.php">Back</a>
</body>
</html>
