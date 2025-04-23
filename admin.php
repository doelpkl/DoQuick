<?php
// Mengimpor file "template.php" yang kemungkinan berisi struktur tampilan halaman
include('template.php');

// Memeriksa apakah sesi "data_user" sudah ada. Jika tidak, user akan diarahkan ke halaman login
if (!isset($_SESSION['data_user'])) {
    header("Location: login.php"); // Redirect ke login.php jika belum login
    exit(); // Hentikan eksekusi kode setelah redirect
}

// Mengambil ID user yang sedang login dari sesi
$id_user = $_SESSION['data_user']['id_user'];


// Koneksi ke database
$koneksi = mysqli_connect('localhost', 'root', '', 'ujikom');

// Menghitung jumlah user yang ada di database
$query_user_count = "SELECT COUNT(*) as total_user FROM user WHERE level != 'admin'";
$result_user_count = mysqli_query($koneksi, $query_user_count);
$row_user_count = mysqli_fetch_assoc($result_user_count);
$total_user = $row_user_count['total_user'];
?>
?>



<div class="container">
    <div class="page-inner">
        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
            <div>
                <h3 class="fw-bold mb-3">Admin Dashboard</h3>
            </div>
            <div class="ms-md-auto py-2 py-md-0">
    <button class="btn btn-primary btn-round" data-bs-toggle="modal" data-bs-target="#addUserModal">Add User</button>
</div>

        </div>

        <div class="row">
            <!-- Card User -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-primary bubble-shadow-small">
                                    <i class="fas fa-users"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3">
                                <div class="numbers">
                                    <p class="card-category">User</p>
                                    <h4 class="card-title" id="totalUser"><?php echo $total_user; ?></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Task -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-info bubble-shadow-small">
                                    <i class="fas fa-tasks"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3">
                                <div class="numbers">
                                    <p class="card-category">Task</p>
                                    <h4 class="card-title" id="totalTask">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Task Selesai -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-success bubble-shadow-small">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-3">
                                <div class="numbers">
                                    <p class="card-category">Task Completed</p>
                                    <h4 class="card-title" id="taskSelesai">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Task Belum Selesai -->
            <div class="col-sm-6 col-md-3">
                <div class="card card-stats card-round">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col-icon">
                                <div class="icon-big text-center icon-secondary bubble-shadow-small">
                                    <i class="fas fa-times-circle"></i>
                                </div>
                            </div>
                            <div class="col col-stats ms-6">
                                <div class="numbers">
                                    <p class="card-category">Task Incomplete</p>
                                    <h4 class="card-title" id="taskBelumSelesai">0</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- User List -->
        <div class="mt-3 w-100">
            <div class="row">
                <?php
                $query = "SELECT * FROM user WHERE level != 'admin'";
                $result = mysqli_query($koneksi, $query);

                while ($row = mysqli_fetch_assoc($result)) :
                ?>
                <div class="col-md-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img src="assets/img/<?php echo !empty($row['foto']) ? $row['foto'] : 'default.png'; ?>" class="rounded-circle me-3" width="50" height="50" alt="User Image">
                                <span class="fw-semibold"><?php echo htmlspecialchars($row['username']); ?></span>
                            </div>
                            <div>
                                <a href="#" class="text-primary me-2 view-user" data-id="<?php echo $row['id_user']; ?>" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="#" class="text-warning me-2 edit-user" data-id="<?php echo $row['id_user']; ?>" title="Edit">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="#" class="text-danger btn-hapus" data-id="<?php echo $row['id_user']; ?>" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal Add User -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addUserForm" action="add_user.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Username -->
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <!-- Foto Profil -->
                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Profil</label>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                    </div>

                    <!-- Level -->
                    <div class="mb-3">
                        <label for="level" class="form-label">Level</label>
                        <select class="form-select" id="level" name="level" required>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Modal Detail -->
<div class="modal fade" id="userModal" tabindex="-1" aria-labelledby="userModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="userModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Konten modal akan dimuat di sini -->
            </div>
        </div>
    </div>
</div>


<!-- Memuat jQuery dan SweetAlert -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>

$("#addUserForm").on("submit", function(e) {
        e.preventDefault(); // Menghindari refresh halaman

        var formData = new FormData(this);

        $.ajax({
            url: 'add_user.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                // Menampilkan respons dari PHP
                Swal.fire({
                    icon: 'success',
                    title: 'User Added Successfully!',
                    confirmButtonColor: '#6C5DD3',
                    timer: 2000
                }).then(function() {
                    location.reload(); // Reload halaman untuk memperbarui daftar user
                });
            },
            error: function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Failed to Add User',
                    text: 'Terjadi kesalahan saat menambahkan user.',
                });
            }
        });
    });

    // Ambil data task melalui AJAX dari get_task_data.php
    $(document).ready(function() {
        fetch("all_task.php")
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                console.error("Error:", data.error);
                return;
            }
            // Update angka di dashboard
            $("#totalTask").text(data.total);
            $("#taskSelesai").text(data.selesai);
            $("#taskBelumSelesai").text(data.belum_selesai);
        })
        .catch(error => console.error("Error fetching task data:", error));
    });

    // Event handler untuk tombol lihat detail user
    $(document).on('click', '.view-user', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'detail_user.php',
            type: 'POST',
            data: {
                id: userId
            },
            success: function(response) {
                $('#userModalLabel').text('Detail User');
                $('#modalContent').html(response);
                $('#userModal').modal('show');
            }
        });
    });

    // Event handler untuk tombol edit user
    $(document).on('click', '.edit-user', function() {
        var userId = $(this).data('id');
        $.ajax({
            url: 'edit_user.php',
            type: 'POST',
            data: {
                id: userId
            },
            success: function(response) {
                $('#userModalLabel').text('Edit User');
                $('#modalContent').html(response);
                $('#userModal').modal('show');
            }
        });
    });

    // Event handler untuk tombol hapus user
$(document).on('click', '.btn-hapus', function() {
    var userId = $(this).data('id'); // Ambil id user dari atribut data-id
    Swal.fire({
        title: 'Yakin ingin menghapus user ini?',
        text: "Tindakan ini tidak bisa dibatalkan!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Kirim permintaan AJAX untuk menghapus user
            $.ajax({
                url: 'hapus_user.php', // Pastikan URL ini mengarah ke file yang benar
                type: 'POST',
                data: {
                    id_user: userId // Kirim id user yang akan dihapus
                },
                success: function(response) {
                    if (response === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'User berhasil dihapus',
                            showConfirmButton: false,
                            timer: 1500
                        });
                        // Hapus elemen user dari tampilan setelah berhasil dihapus
                        $('a[data-id="' + userId + '"]').closest('.col-md-4').remove();
                    } else if (response === 'not_found') {
                        Swal.fire({
                            icon: 'error',
                            title: 'User tidak ditemukan',
                            text: 'User yang akan dihapus tidak ditemukan di database.',
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal menghapus user',
                            text: 'Terjadi kesalahan pada server.',
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal menghubungi server',
                        text: 'Terjadi kesalahan saat mengirim permintaan.',
                    });
                }
            });
        }
    });
});

</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php
if (isset($_SESSION['success_message'])) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'Login Berhasil!',
            text: '" . $_SESSION['success_message'] . "',
            confirmButtonColor: '#6C5DD3'
        });
    </script>";
    unset($_SESSION['success_message']); // Hapus setelah ditampilkan
}
?>

<?php
// Menghubungkan file footer.php
include('footer.php');
include('footer.php');
?>
