<?php
// Menghubungkan ke database
include('koneksi.php');

// Memeriksa apakah request dikirim menggunakan metode POST dan id_task tersedia
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_task'])) {
    // Mengambil id_task dari data yang dikirim melalui POST
    $id_task = $_POST['id_task'];

    // Menjalankan query untuk menghapus tugas berdasarkan id_task
    $query = mysqli_query($koneksi, "DELETE FROM tasks WHERE id_task = '$id_task'");

    // Memeriksa apakah query berhasil dieksekusi
    if ($query) {
        // Jika berhasil, kirimkan respons dalam format JSON dengan status sukses
        echo json_encode([
            'status' => 'success',
            'message' => 'Tugas berhasil dihapus!'
        ]);
    } else {
        // Jika gagal, kirimkan respons dalam format JSON dengan status error
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal menghapus tugas!'
        ]);
    }
}
