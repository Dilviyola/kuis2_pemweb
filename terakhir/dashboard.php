<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$mysqli = new mysqli("localhost", "root", "", "cobaa");

$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'];

$stmt = $mysqli->prepare("SELECT username, photo FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($username, $photo);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - <?= ucfirst($role) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background: linear-gradient(135deg, #d0e7ff, #f0faff);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .dashboard-container {
            background: white;
            padding: 30px 40px;
            border-radius: 15px;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            text-align: center;
            width: 350px;
        }

        h2 {
            margin-bottom: 10px;
            color: #333;
        }

        img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 15px;
            border: 2px solid #007bff;
        }

        a {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.3s ease;
        }

        a:hover {
            background-color: #0056b3;
        }

        .logout {
            background-color: #dc3545;
        }

        .logout:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h2>Selamat Datang, <?= htmlspecialchars($username) ?> (<?= htmlspecialchars($role) ?>)</h2>
        <img src="<?= $photo ? htmlspecialchars($photo) : 'default.png' ?>" alt="Profile Photo"><br>

        <?php if ($role === 'admin'): ?>
            <a href="crud_admin.php">Kelola Pengguna</a><br>
        <?php else: ?>
            <a href="edit_profile_customer.php">Edit Foto Profil</a><br>
        <?php endif; ?>

        <a class="logout" href="logout.php">Logout</a>
    </div>
</body>
</html>
