<?php
// Menghubungkan ke database
include('koneksi.php');

// Memeriksa apakah ada data 'id_task' yang dikirim melalui metode POST
if (isset($_POST['id_task'])) {
    $id_task = $_POST['id_task']; // Mengambil ID tugas yang dikirim dari frontend

    // Query untuk mengambil data tugas berdasarkan id_task
    $query = mysqli_query($koneksi, "SELECT * FROM tasks WHERE id_task = '$id_task'");
    $data = mysqli_fetch_assoc($query); // Mengambil hasil query dalam bentuk array asosiatif

    // Jika data tugas ditemukan
    if ($data) {
        // Array untuk mengonversi nilai priority dari angka ke teks
        $priority = ["Low", "Medium", "High"];

        // Mengembalikan data dalam format JSON
        echo json_encode([
            'status' => 'success', // Status sukses jika data ditemukan
            'task' => $data['task'], // Nama tugas
            'priority' => $priority[$data['priority'] - 1], // Mengubah angka prioritas menjadi teks
            'due_time' => date("H:i", strtotime($data['due_time'])), // Format ulang waktu jatuh tempo ke format HH:MM
            'deskripsi' => $data['deskripsi'] // Deskripsi tugas
        ]);
    } else {
        // Jika data tidak ditemukan, kirim respons error dalam format JSON
        echo json_encode(['status' => 'error']);
    }
}
