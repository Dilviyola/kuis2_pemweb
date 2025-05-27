<?php
// Menampilkan semua error (biar nggak blank screen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connection.php';

// Proses pendaftaran user baru
if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $photo = null;

    // Proses upload foto
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $filename = basename($_FILES['photo']['name']);
        $filename = preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $filename); // hapus karakter aneh
        $photo = 'uploads/' . time() . '_' . $filename;

        // Cek folder uploads, buat jika belum ada
        if (!is_dir('uploads')) {
            mkdir('uploads', 0777, true);
        }

        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    // Simpan ke database
    $query = "INSERT INTO users (username, password, photo) VALUES ('$username', '$password', '$photo')";
    if (mysqli_query($conn, $query)) {
        echo "<div class='alert alert-success'>User berhasil didaftarkan.</div>";
    } else {
        echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
    }
}

// Ambil data user
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
if (!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard CRUD</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">

    <h3>Tambah User Baru</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Upload Foto Profil</label>
            <input type="file" name="photo" id="photo" class="form-control">
        </div>

        <button type="submit" name="register" class="btn btn-primary">Daftar User</button>
    </form>

    <h3 class="mt-5">Daftar User</h3>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td>
                    <img src="<?= $row['photo'] ? $row['photo'] : 'default-profile.png' ?>" width="50" height="50" style="object-fit:cover; border-radius:50%;">
                </td>
                <td>
                    <a href="edit.php?id=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="delete.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm">Hapus</a>
                    <a href="login.php?username=<?= urlencode($row['username']) ?>" class="btn btn-success btn-sm">Login</a>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <a href="logout.php" class="btn btn-danger">Logout</a>
</div>
</body>
</html>
