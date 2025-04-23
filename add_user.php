<?php
// Mengimpor koneksi database
include('koneksi.php');

// Memeriksa apakah form telah dikirimkan
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    // Enkripsi password sebelum disimpan
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Menangani upload foto
    $foto = 'default.png'; // Default foto jika tidak ada yang diupload
    if ($_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto']['name'];
        $upload_dir = 'assets/img/';
        $upload_path = $upload_dir . basename($foto);

        // Cek apakah file berhasil diupload
        if (move_uploaded_file($_FILES['foto']['tmp_name'], $upload_path)) {
            // Berhasil upload foto
        } else {
            echo "Error uploading photo.";
        }
    }

    // Query untuk menambah user ke database
    $query = "INSERT INTO user (username, pass, foto, level) VALUES ('$username', '$hashed_password', '$foto', '$level')";
    
    if (mysqli_query($koneksi, $query)) {
        echo "<script>
            Swal.fire({
                icon: 'success',
                title: 'User Added Successfully!',
                confirmButtonColor: '#6C5DD3',
                timer: 2000
            }).then(function() {
                window.location.href = 'dashboard.php'; // Redirect ke halaman dashboard setelah berhasil
            });
        </script>";
    } else {
        echo "<script>
            Swal.fire({
                icon: 'error',
                title: 'Failed to Add User',
                text: 'Terjadi kesalahan saat menambahkan user.',
            });
        </script>";
    }
}
?>
