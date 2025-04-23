<?php
session_start();
include('koneksi.php');

if (!isset($_SESSION['data_user']['id_user'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

$id_user = $_SESSION['data_user']['id_user']; // Ambil ID user dari session

// Ambil jumlah total tugas
$queryTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tasks WHERE id_user = '$id_user'");
$total = intval(mysqli_fetch_assoc($queryTotal)['total']);

// Cek apakah status di database berupa angka (1/0) atau string ('selesai')
$queryCheckStatus = mysqli_query($koneksi, "SELECT status FROM tasks LIMIT 1");
$checkStatus = mysqli_fetch_assoc($queryCheckStatus)['status'];

if (is_numeric($checkStatus)) {
    // Jika status berupa angka (1 = selesai, 0 = belum selesai)
    $querySelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS selesai FROM tasks WHERE id_user = '$id_user' AND status = 1");
    $queryBelumSelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS belum_selesai FROM tasks WHERE id_user = '$id_user' AND status = 0");
} else {
    // Jika status berupa string ('selesai' atau 'belum selesai')
    $querySelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS selesai FROM tasks WHERE id_user = '$id_user' AND status = 'selesai'");
    $queryBelumSelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS belum_selesai FROM tasks WHERE id_user = '$id_user' AND status != 'selesai'");
}

// Konversi hasil query ke integer
$selesai = intval(mysqli_fetch_assoc($querySelesai)['selesai']);
$belum_selesai = intval(mysqli_fetch_assoc($queryBelumSelesai)['belum_selesai']);

// Kirim data dalam format JSON
echo json_encode([
    "total" => $total,
    "selesai" => $selesai,
    "belum_selesai" => $belum_selesai
]);

exit();
?>
