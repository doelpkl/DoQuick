<?php
session_start(); // Memulai sesi untuk mengakses data sesi yang tersimpan.

$_SESSION['logout_message'] = "Anda telah berhasil logout.";
// Menyimpan pesan logout dalam sesi agar bisa ditampilkan di halaman login.

session_destroy();
// Menghapus semua data sesi yang tersimpan, sehingga user benar-benar logout.

header("Location: login.php?logout=1");
// Mengarahkan user kembali ke halaman login dengan parameter `logout=1` di URL.
// Parameter ini bisa digunakan di halaman login untuk menampilkan pesan logout.

exit();
// Menghentikan eksekusi script untuk memastikan redirect berjalan tanpa gangguan.
