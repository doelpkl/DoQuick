<?php
include "koneksi.php";
session_start();

$username = isset($_POST['username']) ? mysqli_real_escape_string($koneksi, $_POST['username']) : '';
$pass = isset($_POST['pass']) ? $_POST['pass'] : '';

if (!empty($username) && !empty($pass)) {
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE username = '$username'");

    if ($query && mysqli_num_rows($query) == 1) {
        $data = mysqli_fetch_assoc($query);

        if (password_verify($pass, $data['pass'])) {
            $_SESSION['data_user'] = $data;
            $_SESSION['success_message'] = "Selamat datang, " . $data['username'] . "!";

            // Cek level user dan arahkan ke halaman sesuai
            if ($data['level'] == 'admin') {
                header('Location: admin.php');
            } else {
                header('Location: index.php');
            }
            exit();
        } else {
            $_SESSION['error_message'] = "Password salah!";
        }
    } else {
        $_SESSION['error_message'] = "User tidak ditemukan!";
    }
} else {
    $_SESSION['error_message'] = "Username dan password harus diisi!";
}

header("Location: login.php");
exit();
