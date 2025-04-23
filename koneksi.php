<?php
// Konfigurasi koneksi database
// Variabel berikut digunakan untuk menyimpan informasi koneksi ke database

$host = "localhost"; // Alamat server database, biasanya "localhost" jika database berjalan di server yang sama
$user = "root"; // Username database, default untuk MySQL di localhost adalah "root"
$password = ""; // Password database, default untuk MySQL di localhost sering kali kosong
$db = "ujikom"; // Nama database yang akan digunakan

// Membuat koneksi ke database menggunakan fungsi mysqli_connect
$koneksi = mysqli_connect($host, $user, $password, $db);

// Periksa apakah koneksi berhasil
if (!$koneksi) {
	// Jika koneksi gagal, hentikan eksekusi script dan tampilkan pesan error
	die("Koneksi gagal: " . mysqli_connect_error());
} else {
}
