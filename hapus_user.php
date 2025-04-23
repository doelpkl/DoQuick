<?php
// Menghubungkan ke database
include('koneksi.php');

// Memeriksa apakah request menggunakan metode POST dan id_user dikirim
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_user'])) {
    // Mengamankan input dari SQL Injection
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);

    // Query untuk mengambil nama file foto dari user berdasarkan id_user
    $query = "SELECT foto FROM user WHERE id_user = '$id_user'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    // Jika user ditemukan
    if ($user) {
        // Cek apakah user memiliki foto yang bukan default
        if (!empty($user['foto']) && $user['foto'] !== 'default.png') {
            unlink("assets/img/" . $user['foto']); // Hapus file foto dari folder
        }

        // Query untuk menghapus user dari database
        $query_hapus = "DELETE FROM user WHERE id_user = '$id_user'";

        // Eksekusi query penghapusan
        if (mysqli_query($koneksi, $query_hapus)) {
            echo "success"; // Beri respon jika berhasil
        } else {
            echo "error"; // Beri respon jika gagal
        }
    } else {
        echo "not_found"; // Beri respon jika user tidak ditemukan
    }
}
