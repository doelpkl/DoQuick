<?php
session_start();
include('koneksi.php');

if (!isset($_SESSION['data_user']['id_user'])) {
    echo json_encode(["error" => "Unauthorized"]);
    exit();
}

// Ambil jumlah total semua tugas (tanpa filter user)
$queryTotal = mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM tasks");
$total = intval(mysqli_fetch_assoc($queryTotal)['total']);

// Cek apakah status berupa angka atau string
$queryCheckStatus = mysqli_query($koneksi, "SELECT status FROM tasks LIMIT 1");
$checkStatus = mysqli_fetch_assoc($queryCheckStatus)['status'];

if (is_numeric($checkStatus)) {
    // Status angka (1 = selesai, 0 = belum)
    $querySelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS selesai FROM tasks WHERE status = 1");
    $queryBelumSelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS belum_selesai FROM tasks WHERE status = 0");
} else {
    // Status string ('selesai' / 'belum selesai')
    $querySelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS selesai FROM tasks WHERE status = 'selesai'");
    $queryBelumSelesai = mysqli_query($koneksi, "SELECT COUNT(*) AS belum_selesai FROM tasks WHERE status != 'selesai'");
}

// Ambil hasilnya
$selesai = intval(mysqli_fetch_assoc($querySelesai)['selesai']);
$belum_selesai = intval(mysqli_fetch_assoc($queryBelumSelesai)['belum_selesai']);

// Kirim ke frontend dalam format JSON
echo json_encode([
    "total" => $total,
    "selesai" => $selesai,
    "belum_selesai" => $belum_selesai
]);

exit();
?>
