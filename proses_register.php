<?php
include 'db.php'; 

// Ambil data dari form
$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

if (!isset($_POST['terms'])) {
    header("Location: register.php?error=Anda harus menyetujui Syarat dan Ketentuan!");
    exit;
}

if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
    header("Location: register.php?error=Semua kolom wajib diisi!");
    exit;
}

if ($password !== $confirm_password) {
    header("Location: register.php?error=Konfirmasi password tidak cocok!");
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: register.php?error=Format email tidak valid!");
    exit;
}

// Cek apakah username atau email sudah ada di database
$sql_check = "SELECT id FROM users WHERE username = ? OR email = ?";
$stmt_check = $conn->prepare($sql_check);
$stmt_check->bind_param("ss", $username, $email);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows > 0) {
    header("Location: register.php?error=Username atau email sudah terdaftar!");
    exit;
}

// Hash password sebelum disimpan
$password_hashed = password_hash($password, PASSWORD_DEFAULT);

// Simpan user baru ke database
$sql_insert = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
$stmt_insert = $conn->prepare($sql_insert);
$stmt_insert->bind_param("sss", $username, $email, $password_hashed);

if ($stmt_insert->execute()) {
    // Jika berhasil, redirect ke halaman login dengan pesan sukses
    header("Location: login.php?success=Registrasi berhasil! Silakan login.");
    exit;
} else {
    // Jika gagal, redirect kembali dengan pesan error
    header("Location: register.php?error=Terjadi kesalahan saat registrasi. Silakan coba lagi.");
    exit;
}
?>