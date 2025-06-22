<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
$error = $_GET['error'] ?? null;
$success = $_GET['success'] ?? null;
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Cashiary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    
    <style>
        body {
            background-image: url('background_cashiary.jpeg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
        }

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
                    <h4 class="text-dark fw-bold"><i class="bi bi-box-arrow-in-right me-2"></i>Login User</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><i class="bi bi-exclamation-triangle-fill me-2"></i><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><i class="bi bi-check-circle-fill me-2"></i><?= htmlspecialchars($success) ?></div>
                    <?php endif; ?>

                    <form action="proses_login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label text-dark">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="email" id="email" class="form-control" required autofocus>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="form-label text-dark">Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password" id="password" class="form-control" required>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-door-open me-2"></i>Login</button>
                    </form>
                    <div class="text-center mt-3">
                        <small class="text-dark">Belum punya akun? <a href="register.php" class="fw-bold">Daftar di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
