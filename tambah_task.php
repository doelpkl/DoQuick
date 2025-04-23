<?php
session_start();
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Mengecek apakah request yang diterima adalah metode POST
    $task = $_POST['task']; // Mengambil data task dari form
    $priority = $_POST['priority']; // Mengambil data priority dari form
    $due_time = $_POST['due_time']; // Mengambil data due_time dari form
    $deskripsi = $_POST['deskripsi']; // Mengambil data deskripsi dari form
    $id_user = $_SESSION['data_user']['id_user']; // Mengambil ID user dari session

    // Mengecek apakah task dengan due_time yang sama sudah ada di database untuk user yang sama
    $checkQuery = "SELECT * FROM tasks WHERE due_time='$due_time' AND id_user='$id_user'";
    $result = mysqli_query($koneksi, $checkQuery);

    if (mysqli_num_rows($result) == 0) { // Jika tidak ada task yang sama
        // Menyimpan data task ke database
        $query = "INSERT INTO tasks (task, priority, due_time, deskripsi, id_user) VALUES ('$task', '$priority', '$due_time', '$deskripsi', '$id_user')";
        if (mysqli_query($koneksi, $query)) { // Jika query berhasil dieksekusi
            echo json_encode(["status" => "success", "message" => "Task berhasil ditambahkan!"]); // Mengirim respon JSON sukses
        } else { // Jika query gagal
            echo json_encode(["status" => "error", "message" => "Gagal menambahkan task."]); // Mengirim respon JSON error
        }
    } else { // Jika task sudah ada
        echo json_encode(["status" => "error", "message" => "Tidak dapat mengisi due time yang sama"]); // Mengirim respon JSON bahwa task sudah ada
    }
}
