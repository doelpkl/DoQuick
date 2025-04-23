<?php
// Menghubungkan ke database
include('koneksi.php');

// Mengecek apakah request yang diterima adalah metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Mengambil data dari form yang dikirim melalui AJAX
    $id_task = $_POST['id_task']; // ID tugas yang akan diperbarui
    $task = $_POST['task']; // Nama atau judul tugas
    $priority = $_POST['priority']; // Prioritas tugas (misal: rendah, sedang, tinggi)
    $due_time = $_POST['due_time']; // Waktu jatuh tempo tugas
    $deskripsi = $_POST['deskripsi']; // Deskripsi tugas

    // Query untuk memperbarui data tugas berdasarkan id_task
    $query = "UPDATE tasks SET task='$task', priority='$priority', due_time='$due_time', deskripsi='$deskripsi' WHERE id_task='$id_task'";

    // Menjalankan query update
    if (mysqli_query($koneksi, $query)) {
        // Jika berhasil, kirimkan respons JSON dengan status sukses
        echo json_encode([
            "status" => "success",
            "message" => "Tugas berhasil diperbarui!"
        ]);
    } else {
        // Jika gagal, kirimkan respons JSON dengan status error
        echo json_encode([
            "status" => "error",
            "message" => "Gagal memperbarui tugas."
        ]);
    }
}
