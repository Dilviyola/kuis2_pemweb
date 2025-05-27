<?php
session_start();
include 'connection.php';


$id = $_GET['id'];
$query = "SELECT * FROM users WHERE id = $id";
$result = mysqli_query($conn, $query);
$user = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {
    $username = $_POST['username'];
    
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $photo = $user['photo']; 

    if (isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
        $photo = 'uploads/' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], $photo);
    }

    mysqli_query($conn, "UPDATE users SET username='$username', password='$password', photo='$photo' WHERE id=$id");
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-custom {
            background-color: #007bff;
            color: white;
            border-radius: 25px;
            padding: 10px 20px;
        }
        .btn-custom:hover {
            background-color: #0056b3;
        }
        .img-profile {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 600px;">
            <div class="card-body">
                <h2 class="text-center text-primary mb-4">Edit User</h2>

                <div class="text-center mb-4">
                    <!-- Menampilkan foto profil jika ada -->
                    <?php if ($user['photo']): ?>
                        <img src="<?php echo $user['photo']; ?>" class="img-fluid img-profile" alt="Foto Profil">
                    <?php else: ?>
                        <img src="default-profile.png" class="img-fluid img-profile" alt="Default Foto Profil">
                    <?php endif; ?>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" name="username" id="username" class="form-control" value="<?= $user['username'] ?>" required>
                    </div>


                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Upload Photo (optional)</label>
                        <input type="file" name="photo" id="photo" class="form-control">
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" name="update" class="btn btn-custom">Update</button>
                    </div>
                </form>

                <div class="mt-3 text-center">
                    <a href="index.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
