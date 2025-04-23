<?php
session_start();
include('koneksi.php');

// Jika user logout, set session logout_message
if (isset($_GET['logout'])) {
    $_SESSION['logout_message'] = "Anda telah berhasil logout.";
    header("Location: login.php"); // Redirect untuk mencegah notifikasi muncul saat refresh
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/icon.png" type="image/png">
    <title>Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f5f5f5;
            color: #1a2035;
        }

        .login-container {
            max-width: 500px;
            width: 100%;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 2rem;
            animation: fadeIn 0.8s ease-in-out;
        }

        .form-control {
            border-radius: 8px;
            background-color: #ffffff;
            color: #1a2035;
            border: 1px solid #1a2035;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a2035;
            box-shadow: 0 0 10px rgba(26, 32, 53, 0.5);
        }

        .btn-primary {
            background-color: #1a2035;
            color: #f5f7fd;
            border: none;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #161b2c;
            transform: scale(1.05);
        }

        .register-link {
            margin-top: 10px;
            text-align: center;
        }

        .register-link a {
            text-decoration: none;
        }

        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="login-container">
        <h3 class="text-center mb-4">Login</h3>
        <form action="login_proses.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Masukan Username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="pass" id="password" placeholder="Masukan Password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁️</button>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <p class="register-link">Belum punya akun? <a href="sign.php">Daftar di sini</a></p>
    </div>

    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        }
    </script>

    <!-- Notifikasi SweetAlert -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                title: "Registrasi Berhasil!",
                text: "<?= $_SESSION['success_message']; ?>",
                icon: "success",
                confirmButtonText: "OK"
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['logout_message'])): ?>
        <script>
            Swal.fire({
                title: "Logout Berhasil!",
                text: "<?= $_SESSION['logout_message']; ?>",
                icon: "success",
                confirmButtonText: "OK"
            });
        </script>
        <?php unset($_SESSION['logout_message']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <script>
            Swal.fire({
                title: "Login Gagal!",
                text: "<?= $_SESSION['error_message']; ?>",
                icon: "error",
                confirmButtonText: "Coba Lagi"
            });
        </script>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>
</body>

</html>
