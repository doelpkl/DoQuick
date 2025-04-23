<?php
// Menghubungkan ke database
include('koneksi.php');

// Mengecek apakah request yang diterima adalah POST dan memiliki ID
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    // Mengamankan input ID agar terhindar dari SQL Injection
    $id = mysqli_real_escape_string($koneksi, $_POST['id']);

    // Query untuk mengambil data user berdasarkan ID
    $query = "SELECT * FROM user WHERE id_user = '$id'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result); // Mengambil data dalam bentuk array asosiatif

    // Jika user tidak ditemukan, tampilkan pesan error
    if (!$user) {
        echo "<p>User tidak ditemukan.</p>";
        exit; // Hentikan eksekusi script
    }
}

// Jika form dikirim untuk update data user
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_user'])) {
    // Mengamankan input user untuk mencegah SQL Injection
    $id_user = mysqli_real_escape_string($koneksi, $_POST['id_user']);
    $username = mysqli_real_escape_string($koneksi, $_POST['username']);
    $level = mysqli_real_escape_string($koneksi, $_POST['level']);

    // Jika password diisi, hash password baru
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Membuat query untuk update user
    $updateQuery = "UPDATE user SET username = '$username', level = '$level'";

    // Jika ada perubahan password, tambahkan ke query update
    if ($password) {
        $updateQuery .= ", pass = '$password'";
    }

    // Jika user mengupload foto baru
    if (!empty($_FILES['foto']['name']) && $_FILES['foto']['error'] == 0) {
        $foto = time() . "_" . basename($_FILES['foto']['name']); // Nama file unik berdasarkan timestamp
        $tmp_foto = $_FILES['foto']['tmp_name']; // Path file sementara
        $folder = 'assets/img/'; // Direktori penyimpanan foto
        $path_foto = $folder . $foto; // Path lengkap penyimpanan foto

        // Jika upload berhasil, tambahkan ke query update
        if (move_uploaded_file($tmp_foto, $path_foto)) {
            $updateQuery .= ", foto = '$foto'";
        }
    }

    // Menyelesaikan query update dengan kondisi WHERE
    $updateQuery .= " WHERE id_user = '$id_user'";

    // Menjalankan query update dan memberikan respon
    if (mysqli_query($koneksi, $updateQuery)) {
        echo "success"; // Jika berhasil
    } else {
        echo "error"; // Jika terjadi kesalahan
    }
    exit; // Menghentikan eksekusi lebih lanjut
}
?>

<!-- FORM EDIT USER -->
<form id="editUserForm" enctype="multipart/form-data">
    <!-- Input tersembunyi untuk menyimpan ID user -->
    <input type="hidden" name="id_user" value="<?= $user['id_user'] ?>">

    <!-- Tampilan foto profil -->
    <div class="text-center">
        <img src="assets/img/<?= !empty($user['foto']) ? $user['foto'] : 'default.png' ?>"
            width="100" height="100"
            class="rounded-circle mb-2"
            style="object-fit: cover; border-radius: 50%;"
            id="previewImage">
    </div>

    <!-- Input untuk upload foto profil -->
    <div class="mb-3">
        <label class="form-label">Foto Profil</label>
        <input type="file" class="form-control" name="foto" id="fotoInput" accept="image/*">
    </div>

    <!-- Input untuk username -->
    <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
    </div>

    <!-- Input untuk password (opsional, hanya diisi jika ingin diubah) -->
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" class="form-control" name="password" placeholder="Masukkan password baru jika ingin mengubah">
    </div>

    <!-- Pilihan untuk level pengguna -->
    <div class="mb-3">
        <label class="form-label">Level</label>
        <select class="form-control" name="level" required>
            <option value="premium" <?= ($user['level'] == 'premium' ? 'selected' : '') ?>>Premium</option>
            <option value="user" <?= ($user['level'] == 'user' ? 'selected' : '') ?>>User</option>
        </select>
    </div>

    <!-- Tombol submit untuk menyimpan perubahan -->
    <button type="submit" class="btn btn-primary w-100">Simpan Perubahan</button>
</form>

<!-- Tambahkan SweetAlert untuk notifikasi -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Menampilkan preview foto saat user memilih file baru
    document.getElementById('fotoInput').addEventListener('change', function(event) {
        var reader = new FileReader(); // Membuat objek FileReader
        reader.onload = function() {
            document.getElementById('previewImage').src = reader.result; // Menampilkan preview
        };
        reader.readAsDataURL(event.target.files[0]); // Membaca file sebagai URL data
    });

    // AJAX untuk submit form dengan SweetAlert konfirmasi
    document.getElementById('editUserForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Mencegah reload halaman

        var formData = new FormData(this); // Mengambil data form

        Swal.fire({
            title: "Konfirmasi Perubahan",
            text: "Apakah Anda yakin ingin menyimpan perubahan ini?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Ya, Simpan!"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('edit_user.php', { // Mengirim data ke edit_user.php
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.text()) // Mengubah respons menjadi teks
                    .then(data => {
                        if (data.trim() === 'success') { // Jika update sukses
                            Swal.fire({
                                title: "Berhasil!",
                                text: "Data berhasil diperbarui.",
                                icon: "success",
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload(); // Reload halaman setelah sukses
                            });
                        } else {
                            Swal.fire("Gagal", "Terjadi kesalahan saat mengupdate user.", "error"); // Jika gagal update
                        }
                    })
                    .catch(error => {
                        Swal.fire("Error", "Terjadi kesalahan pada sistem.", "error"); // Jika ada error dalam fetch
                    });
            }
        });
    });
</script>