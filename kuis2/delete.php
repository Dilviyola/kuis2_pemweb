<?php
session_start();
include 'connection.php';

// Cek apakah parameter id ada dan valid (harus angka)
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    // Gunakan prepared statement untuk keamanan
    $query = "DELETE FROM users WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id);

        if (mysqli_stmt_execute($stmt)) {
            // Redirect kembali ke index dengan pesan sukses
            header("Location: index.php?message=deleted");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Gagal menghapus user. Error: " . mysqli_stmt_error($stmt) . "</div>";
        }

        mysqli_stmt_close($stmt);
    } else {
        echo "<div class='alert alert-danger'>Query tidak dapat diproses. Error: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div class='alert alert-warning'>ID tidak valid atau tidak ditemukan.</div>";
}
?>
