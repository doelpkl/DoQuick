<?php
include('koneksi.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_task = filter_input(INPUT_POST, 'id_task', FILTER_VALIDATE_INT);
    $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

    if ($id_task === false || $status === false) {
        echo json_encode([
            "status" => "error",
            "message" => "ID atau status tidak valid."
        ]);
        exit;
    }

    $query = "UPDATE tasks SET status = ? WHERE id_task = ?";
    $stmt = mysqli_prepare($koneksi, $query);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ii", $status, $id_task);
        $exec = mysqli_stmt_execute($stmt);

        if ($exec) {
            echo json_encode([
                "status" => "success",
                "message" => "Status tugas berhasil diperbarui."
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Gagal memperbarui status tugas."
            ]);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menyiapkan perintah."
        ]);
    }

    mysqli_close($koneksi);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Permintaan tidak valid."
    ]);
}
