<?php
session_start(); // Memulai session
include('koneksi.php'); // Menghubungkan ke database

// Cek apakah user sudah login, jika tidak, redirect ke halaman login
if (!isset($_SESSION['data_user'])) {
    header("Location: login.php");
    exit;
}

// Mengambil data user yang sedang login
$id_user = $_SESSION['data_user']['id_user'];
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id_user");
$data = mysqli_fetch_assoc($query);

// Menentukan foto profil, jika belum ada gunakan default
$fotoProfil = !empty($data['foto']) ? 'assets/img/' . $data['foto'] : 'assets/img/default.png';

// Jika form update dikirim
if (isset($_POST['update'])) {
    // Mengamankan input username dari karakter khusus
    $new_username = mysqli_real_escape_string($koneksi, $_POST['new_username']);

    // Jika password baru diisi, hash password, jika tidak gunakan password lama
    $new_password = !empty($_POST['new_password']) ? password_hash($_POST['new_password'], PASSWORD_DEFAULT) : $data['pass'];

    // Cek apakah ada file gambar yang diupload
    if (!empty($_FILES['profile_picture']['name'])) {
        $targetDir = "assets/img/"; // Folder tujuan penyimpanan gambar
        $fileName = time() . "_" . basename($_FILES["profile_picture"]["name"]); // Buat nama unik untuk gambar
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION); // Ambil ekstensi file

        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif']; // Ekstensi yang diperbolehkan
        if (in_array(strtolower($fileType), $allowedTypes)) {
            // Pindahkan file ke folder penyimpanan
            if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $targetFilePath)) {
                // Hapus foto lama jika ada
                if (!empty($data['foto']) && file_exists("assets/img/" . $data['foto'])) {
                    unlink("assets/img/" . $data['foto']);
                }
                // Update foto profil di database
                mysqli_query($koneksi, "UPDATE user SET foto = '$fileName' WHERE id_user = $id_user");
                $_SESSION['data_user']['foto'] = $fileName; // Perbarui session foto
                $fotoProfil = $targetFilePath; // Perbarui tampilan foto
            }
        }
    }

    // Update username dan password di database
    $query = mysqli_query($koneksi, "UPDATE user SET username = '$new_username', pass = '$new_password' WHERE id_user = $id_user");
    $_SESSION['data_user']['username'] = $new_username; // Perbarui session username

    // Beri notifikasi sukses atau error
    if ($query) {
        $_SESSION['success_message'] = "Perubahan akun berhasil disimpan.";
        header("Location: acc.php"); // Redirect ke halaman akun setelah update berhasil
        exit;
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat menyimpan perubahan.";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background-color: #f5f5f5;
            color: #1a2035;
        }

        .account-container {
            max-width: 500px;
            width: 100%;
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
            padding: 2rem;
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

        .btn-secondary {
            background-color: #6c757d;
            /* Abu-abu kebiruan */
            color: #f5f7fd;
            border: none;
            border-radius: 8px;
            width: 100%;
            transition: all 0.3s ease-in-out;
        }

        .btn-secondary:hover {
            background-color: #545b62;
            /* Warna lebih gelap saat hover */
            transform: scale(1.05);
        }

        .profile-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>

<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="account-container">
        <h3 class="text-center mb-4">Account Settings</h3>
        <div class="text-center mb-3">
            <img src="<?= $fotoProfil ?>" alt="Profile Picture" class="profile-img">
        </div>
        <form action="" method="POST" enctype="multipart/form-data">
            <!-- Input untuk mengganti foto profil -->
            <div class="mb-3">
                <label class="form-label">Upload Foto Profil</label>
                <input type="file" name="profile_picture" class="form-control">
            </div>
            <!-- Input untuk mengganti username -->
            <div class="mb-3">
                <label class="form-label">Username Baru</label>
                <input type="text" name="new_username" value="<?= htmlspecialchars($data['username']); ?>" class="form-control" required>
            </div>
            <!-- Input untuk mengganti password (opsional) -->
            <div class="mb-3">
                <label class="form-label">Password Baru (Opsional)</label>
                <input type="password" name="new_password" id="password" class="form-control">
                <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">👁️</button>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
        </form>
        <div class="mt-3 text-center">
            <a href="index.php" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>
    </div>

    <script>
        // Fungsi untuk menampilkan atau menyembunyikan password
        function togglePassword() {
            var passwordInput = document.getElementById("password");
            passwordInput.type = passwordInput.type === "password" ? "text" : "password";
        }
    </script>

    <!-- Menampilkan notifikasi sukses jika ada -->
    <?php if (isset($_SESSION['success_message'])): ?>
        <script>
            Swal.fire({
                title: "Sukses!",
                text: "<?= $_SESSION['success_message']; ?>",
                icon: "success",
                confirmButtonText: "OK"
            });
        </script>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <!-- Menampilkan notifikasi error jika ada -->
    <?php if (isset($_SESSION['error_message'])): ?>
        <script>
            Swal.fire({
                title: "Error!",
                text: "<?= $_SESSION['error_message']; ?>",
                icon: "error",
                confirmButtonText: "OK"
            });
        </script>
        <?php unset($_SESSION['error_message']); ?>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>