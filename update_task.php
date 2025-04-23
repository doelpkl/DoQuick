<?php
// Menghubungkan ke database
include('koneksi.php');

// Mengecek apakah request menggunakan metode POST dan parameter id_task serta status tersedia
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_task']) && isset($_POST['status'])) {

    // Mengambil data dari request
    $id_task = $_POST['id_task'];
    $status = $_POST['status'];

    // Query untuk memperbarui status tugas berdasarkan id_task
    $query = "UPDATE tasks SET status = '$status' WHERE id_task = '$id_task'";
    $result = mysqli_query($koneksi, $query);

    // Menentukan respons berdasarkan hasil query
    if ($result) {
        echo json_encode(["status" => "success", "message" => "Task status updated successfully!"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to update task status: " . mysqli_error($koneksi)]);
    }

    // Menutup koneksi database
    mysqli_close($koneksi);
} else {
    // Jika request tidak valid, kirim respons error
    echo json_encode(["status" => "error", "message" => "Invalid request."]);
}
