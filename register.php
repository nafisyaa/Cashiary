<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$error = $_GET['error'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - Cashiary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        /* Mengatur gambar latar belakang untuk seluruh halaman */
        body {
            background-image: url('background_cashiary.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* Efek Kaca Buram (Glassmorphism) untuk kartu */
        .card-glass {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="d-flex align-items-center">

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow card-glass">
                <div class="card-header text-center border-0 pt-4 bg-transparent">
                    <h4 class="text-dark fw-bold"><i class="bi bi-person-plus-fill me-2"></i>Buat Akun Baru</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form action="proses_register.php" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label text-dark">Username</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label text-dark">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="confirm_password" class="form-label text-dark">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="confirm_password" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" name="terms" class="form-check-input" id="termsCheck" required>
                            <label class="form-check-label text-dark" for="termsCheck">
                                Saya setuju untuk membuat akun.
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-bold">
                            <i class="bi bi-person-check-fill me-2"></i>Daftar
                        </button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <small class="text-dark">Sudah punya akun? <a href="login.php" class="fw-bold">Login di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>