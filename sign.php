<?php
session_start();
include 'koneksi.php';

if (isset($_POST['daftar'])) {
    $username = $_POST['username'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT);
    $level = 'user';

    // Validasi username
    if (!preg_match('/^(?=.*[a-zA-Z])[a-zA-Z0-9]{3,}$/', $username)) {
        $_SESSION['error_message'] = "Username harus minimal 3 huruf dan jika mengandung angka, harus juga mengandung huruf.";
    } else {
        $cek_username = mysqli_query($koneksi, "SELECT username FROM user WHERE username = '$username'");

        if (mysqli_num_rows($cek_username) > 0) {
            $_SESSION['error_message'] = "Username sudah digunakan, pilih yang lain.";
        } else {
            $query = mysqli_query($koneksi, "INSERT INTO user (username, pass, level) VALUES ('$username', '$pass', '$level')");

            if ($query) {
                $_SESSION['success_message'] = "Registrasi berhasil! Silakan login.";
                header('Location: login.php');
                exit();
            } else {
                $_SESSION['error_message'] = "Registrasi gagal, silakan coba lagi.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="assets/img/icon.png" type="image/png">
    <title>Registrasi</title>

    <!-- Menggunakan Bootstrap untuk styling -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert untuk notifikasi -->

    <style>
        /* Background body polos */
        body {
            background-color: #f5f5f5;
            /* Warna polos */
            color: #1a2035;
            /* Warna teks utama */
        }

        /* Container dengan background putih dan efek shadow */
        .login-container {
            max-width: 500px;
            width: 100%;
            background: #ffffff;
            /* Background putih */
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            /* Efek shadow */
            padding: 2rem;
            animation: fadeIn 0.8s ease-in-out;
        }

        /* Form Control */
        .form-control {
            border-radius: 8px;
            background-color: #ffffff;
            /* Background input tetap putih */
            color: #1a2035;
            /* Warna teks utama */
            border: 1px solid #1a2035;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #1a2035;
            box-shadow: 0 0 10px rgba(26, 32, 53, 0.5);
        }

        /* Button tetap menggunakan warna background sebelumnya */
        .btn-primary {
            background-color: #1a2035;
            /* Warna lama */
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

        /* Link Login */
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
        <h3 class="text-center mb-4">Registrasi</h3>

        <!-- Form Registrasi -->
        <form action="" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" name="username" id="username" placeholder="Masukkan username" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" name="pass" id="password" placeholder="Masukkan password" required>
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁️</button>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" name="daftar">Daftar</button>
        </form>

        <p class="register-link">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>

    <!-- Script untuk menampilkan/menghilangkan password -->
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }

        document.getElementById("username").focus(); // Fokus ke input username saat halaman dimuat.
    </script>

    <!-- Memuat script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Menampilkan notifikasi jika ada pesan sukses atau error -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            setTimeout(() => {
                Swal.fire({
                    title: "Registrasi Berhasil!",
                    text: "<?= $_SESSION['success_message']; ?>",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            }, 500);
        </script>
        <?php unset($_SESSION['success_message']); ?> <!-- Menghapus pesan dari session setelah ditampilkan -->
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <script>
            Swal.fire({
                title: "Registrasi Gagal!",
                text: "<?= $_SESSION['error_message']; ?>",
                icon: "error",
                confirmButtonText: "Coba Lagi"
            });
        </script>
        <?php unset($_SESSION['error_message']); ?> <!-- Menghapus pesan dari session setelah ditampilkan -->
    <?php endif; ?>
</body>

</html>