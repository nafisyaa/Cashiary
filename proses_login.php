<?php
session_start();
include 'db.php';

$email = $_POST['email'];
$password = $_POST['password'];

// Ambil user dari database
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();
    
    if (password_verify($password, $user['password'])) {
        // Simpan sesi login
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];

        header("Location: index.php");
        exit;
    } else {
        header("Location: login.php?error=Password salah!");
        exit;
    }
} else {
    header("Location: login.php?error=Email tidak ditemukan!");
    exit;
}
?>
