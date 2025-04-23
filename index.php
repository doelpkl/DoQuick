<?php
// Mengimpor file "template.php" yang kemungkinan berisi struktur tampilan halaman
include('template.php');

// Mengimpor file "koneksi.php" untuk menghubungkan ke database
include('koneksi.php');

// Memeriksa apakah sesi "data_user" sudah ada. Jika tidak, user akan diarahkan ke halaman login
if (!isset($_SESSION['data_user'])) {
    header("Location: login.php"); // Redirect ke login.php jika belum login
    exit(); // Hentikan eksekusi kode setelah redirect
}

// Mengambil ID user yang sedang login dari sesi
$id_user = $_SESSION['data_user']['id_user'];
?>

<!-- Membuat card untuk menampilkan daftar tugas -->
<div class="card w-100 mt-4">
    <h2 class="mt-5"></h2>
    <div class="card-body">
        <!-- Tombol untuk menambah tugas baru -->
        <button id="addTaskBtn" class="btn btn-success mb-3">Add Task</button>
        <div class="table-responsive" style="height: 80vh; overflow: auto;">
            <table id="taskTable" class="table table-bordered table-hover text-center w-100">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Task</th>
                        <th>Priority</th>
                        <th>Due Time</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Inisialisasi nomor urut
                    $no = 1;
                    // Query untuk mengambil daftar tugas user yang sedang login, diurutkan berdasarkan status, prioritas, dan waktu tenggat
                    $query = mysqli_query($koneksi, "SELECT * FROM tasks WHERE id_user = '$id_user' ORDER BY status ASC, priority ASC, due_time ASC");

                    // Loop untuk menampilkan setiap tugas dalam tabel
                    while ($data = mysqli_fetch_assoc($query)) :
                    ?>
                        <tr data-task-id="<?= $data['id_task']; ?>" data-status="<?= $data['status']; ?>">
                            <td><?= $no++; ?></td>
                            <td class="text-start <?= ($data['status'] == 1) ? 'text-decoration-line-through' : ''; ?>">
                                <?= $data['task']; ?>
                            </td>
                            <td>
                                <?php
                                $priority = ["Low", "Medium", "High"];
                                echo "<span class='badge bg-" . ($data['priority'] == 3 ? "danger" : ($data['priority'] == 2 ? "warning" : "success")) . "'>" . $priority[$data['priority'] - 1] . "</span>";
                                ?>
                            </td>
                            <td class="<?= ($data['status'] == 1) ? 'text-decoration-line-through' : ''; ?>">
                                <?= date("H:i", strtotime($data['due_time'])); ?>
                            </td>
                            <td>
                                <?= ($data['status'] == 1) ? "<span class='badge bg-success'>Completed</span>" : "<span class='badge bg-danger'>Not Completed</span>"; ?>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <li>
                                            <a class="dropdown-item text-warning toggle-status" href="#" data-id="<?= $data['id_task']; ?>" data-status="<?= $data['status']; ?>">
                                                <i class="fas <?= ($data['status'] == 1) ? 'fa-undo' : 'fa-check'; ?> me-2"></i> <?= ($data['status'] == 1) ? 'Undo' : 'Complete'; ?>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-info info-task" href="#" data-id="<?= $data['id_task']; ?>">
                                                <i class="fas fa-info-circle me-2"></i> Info
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-primary edit-task"
                                                href="#"
                                                data-id="<?= $data['id_task']; ?>"
                                                data-task="<?= htmlspecialchars($data['task']); ?>"
                                                data-priority="<?= $data['priority']; ?>"
                                                data-due_time="<?= $data['due_time']; ?>"
                                                data-deskripsi="<?= htmlspecialchars($data['deskripsi']); ?>">
                                                <i class="fas fa-edit me-2"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger delete-task" href="#" data-id="<?= $data['id_task']; ?>">
                                                <i class="fas fa-trash me-2"></i> Delete
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>

                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include('footer.php'); ?>



<!-- Modal for Adding Task -->
<div id="taskModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <div class="mb-3">
                        <label class="form-label">Task</label>
                        <input type="text" class="form-control" name="task" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" name="priority" required>
                            <option value="">Select Priority</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Time</label>
                        <input type="time" class="form-control" name="due_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary">Save Task</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Editing Task -->
<div id="editTaskModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editTaskForm">
                    <input type="hidden" name="id_task">
                    <div class="mb-3">
                        <label class="form-label">Task</label>
                        <input type="text" class="form-control" name="task" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Priority</label>
                        <select class="form-select" name="priority" required>
                            <option value="">Select Priority</option>
                            <option value="1">Low</option>
                            <option value="2">Medium</option>
                            <option value="3">High</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due Time</label>
                        <input type="time" class="form-control" name="due_time" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi"></textarea>
                    </div>


                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Info -->
<div id="infoTaskModal" class="modal fade" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h5 class="modal-title">Task Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Task</label>
                    <div class="form-control" id="taskDetail" readonly></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Priority</label>
                    <div class="form-control" id="priorityDetail" readonly></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Due Time</label>
                    <div class="form-control" id="dueTimeDetail" readonly></div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <div class="form-control" id="descDetail" style="height: auto; min-height: 80px;" readonly></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>




<?php include('footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable untuk tabel tugas
        $('#taskTable').DataTable();

        // Menampilkan modal untuk menambah tugas ketika tombol "Add Task" diklik
        $('#addTaskBtn').click(function() {
            $('#taskModal').modal('show'); // Menampilkan modal
        });

        // Menangani pengiriman formulir untuk menambahkan tugas baru
        $('#taskForm').submit(function(e) {
            e.preventDefault(); // Mencegah pengiriman form biasa
            $.ajax({
                url: 'tambah_task.php', // URL tujuan pengiriman data
                type: 'POST', // Menggunakan metode POST
                data: $(this).serialize(), // Mengirimkan data form yang telah diserialisasi
                dataType: 'json', // Menentukan format respons yang diterima (JSON)
                success: function(response) {
    if (response.status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: response.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'small-toast'
            }
        });
        $('#taskModal').modal('hide');
        setTimeout(function () {
            location.reload();
        }, 2000);
    } else {
        // Tangani error dari PHP (misalnya duplikat due_time)
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: response.message,
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000,
            customClass: {
                popup: 'small-toast'
            }
        });
    }
}
            });
        });

        // Menangani klik untuk toggle status tugas (selesai/belum selesai)
        $(document).on('click', '.toggle-status', function() {
            let taskId = $(this).data('id'); // Mendapatkan ID tugas
            let currentStatus = $(this).data('status'); // Mendapatkan status tugas saat ini
            let newStatus = currentStatus == 1 ? 0 : 1; // Menentukan status baru
            let statusText = newStatus == 1 ? 'Task diselesaikan!' : 'Task dikembalikan!'; // Pesan status
            let iconType = newStatus == 1 ? 'success' : 'info'; // Jenis ikon notifikasi

            $.ajax({
                url: 'selesai_task.php', // URL untuk memperbarui status tugas
                type: 'POST',
                data: { id_task: taskId, status: newStatus }, // Mengirim ID dan status baru
                dataType: 'json',
                success: function(response) {
                    // Jika berhasil, tampilkan notifikasi dengan SweetAlert
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: iconType,
                            title: 'Diperbarui!',
                            text: statusText,
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            customClass: {
                                popup: 'small-toast'
                            }
                        });
                        setTimeout(function() {
                            location.reload(); // Merefresh halaman setelah 2 detik
                        }, 2000);
                    }
                }
            });
        });

        // Menangani klik untuk menampilkan detail tugas dalam modal
        $(document).on('click', '.info-task', function() {
            let id_task = $(this).data('id'); // Mendapatkan ID tugas

            $.ajax({
                url: 'detail_task.php', // URL untuk mendapatkan detail tugas
                type: 'POST',
                data: { id_task: id_task }, // Mengirimkan ID tugas
                dataType: 'json',
                success: function(response) {
                    // Menampilkan detail tugas di modal jika berhasil
                    if (response.status === 'success') {
                        $('#taskDetail').text(response.task);
                        $('#priorityDetail').text(response.priority);
                        $('#dueTimeDetail').text(response.due_time);
                        $('#descDetail').text(response.deskripsi);
                        $('#infoTaskModal').modal('show'); // Menampilkan modal detail
                    }
                }
            });
        });

        // Menangani klik untuk membuka modal edit tugas
        $(document).on('click', '.edit-task', function() {
            let id_task = $(this).data('id'); // Mendapatkan ID tugas
            let task = $(this).data('task'); // Mendapatkan data tugas
            let priority = $(this).data('priority'); // Mendapatkan prioritas tugas
            let due_time = $(this).data('due_time'); // Mendapatkan waktu jatuh tempo tugas
            let deskripsi = $(this).data('deskripsi'); // Mendapatkan deskripsi tugas

            // Memasukkan data ke dalam formulir edit
            $('#editTaskForm input[name="id_task"]').val(id_task);
            $('#editTaskForm input[name="task"]').val(task);
            $('#editTaskForm select[name="priority"]').val(priority);
            $('#editTaskForm input[name="due_time"]').val(due_time);
            $('#editTaskForm textarea[name="deskripsi"]').val(deskripsi);

            $('#editTaskModal').modal('show'); // Menampilkan modal edit
        });

        // Menangani pengiriman formulir untuk mengedit tugas
        $('#editTaskForm').submit(function(e) {
            e.preventDefault(); // Mencegah pengiriman form biasa
            $.ajax({
                url: 'edit_task.php', // URL untuk mengedit tugas
                type: 'POST',
                data: $(this).serialize(), // Mengirimkan data formulir yang telah diserialisasi
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Jika berhasil, tampilkan notifikasi dengan SweetAlert
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Tugas berhasil diperbarui!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            customClass: {
                                popup: 'small-toast'
                            }
                        });
                        $('#editTaskModal').modal('hide'); // Menutup modal
                        setTimeout(function() {
                            location.reload(); // Merefresh halaman setelah 2 detik
                        }, 2000);
                    } else {
                        // Jika gagal, tampilkan notifikasi error
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: 'Terjadi kesalahan saat memperbarui tugas.',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000,
                            customClass: {
                                popup: 'small-toast'
                            }
                        });
                    }
                },
                error: function() {
                    // Menampilkan error jika terjadi kesalahan di server
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan pada server. Coba lagi nanti.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        customClass: {
                            popup: 'small-toast'
                        }
                    });
                }
            });
        });

        // Menangani penghapusan tugas dengan peringatan konfirmasi
        $(document).on('click', '.delete-task', function() {
            let taskId = $(this).data('id'); // Mendapatkan ID tugas

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Tugas ini akan dihapus secara permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Mengirimkan permintaan untuk menghapus tugas
                    $.ajax({
                        url: 'hapus_task.php',
                        type: 'POST',
                        data: { id_task: taskId },
                        dataType: 'json',
                        success: function(response) {
                            if (response.status === 'success') {
                                // Menampilkan notifikasi sukses jika berhasil dihapus
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    customClass: {
                                        popup: 'small-toast'
                                    }
                                });
                                setTimeout(function() {
                                    location.reload(); // Merefresh halaman setelah 2 detik
                                }, 2000);
                            } else {
                                // Menampilkan notifikasi error jika gagal dihapus
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: response.message,
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    customClass: {
                                        popup: 'small-toast'
                                    }
                                });
                            }
                        }
                    });
                }
            });
        });
    });

    // Fungsi untuk menampilkan notifikasi ketika tugas hampir jatuh tempo
    $(document).ready(function () {
        let lastShown = {}; // Untuk menyimpan status kapan notifikasi terakhir ditampilkan

        // Cek setiap 10 detik untuk melihat apakah tugas hampir jatuh tempo
        setInterval(function () {
            let currentTime = new Date();

            $('#taskTable tbody tr').each(function () {
                let $row = $(this);
                let taskId = $row.data('task-id'); // Mendapatkan ID tugas
                let taskName = $row.find('td:nth-child(2)').text().trim(); // Nama tugas
                let dueTime = $row.find('td:nth-child(4)').text().trim(); // Waktu jatuh tempo
                let status = parseInt($row.data('status')); // Status tugas (selesai atau belum)

                // Lewati jika tugas sudah selesai atau tidak ada waktu jatuh tempo
                if (status === 1 || !dueTime) return;

                // Menghitung selisih waktu jatuh tempo
                let timeParts = dueTime.split(":");
                if (timeParts.length < 2) return;

                let dueDate = new Date(currentTime);
                dueDate.setHours(parseInt(timeParts[0]), parseInt(timeParts[1]), 0, 0);

                let diffInSeconds = (dueDate - currentTime) / 1000;

                // Hanya tampilkan notifikasi jika tugas hampir jatuh tempo (5 menit atau kurang)
                if (diffInSeconds <= 300 && diffInSeconds > 0) {
                    let now = Date.now();
                    let last = lastShown[taskId] || 0;

                    // Tampilkan notifikasi jika sudah lebih dari 10 detik sejak terakhir tampil
                    if (now - last >= 10000) { // 10 detik
                        lastShown[taskId] = now;

                        Swal.fire({
                            icon: 'warning',
                            title: 'Tugas Hampir Jatuh Tempo!',
                            text: 'Tugas "' + taskName + '" akan segera jatuh tempo.',
                            position: 'top-end',
                            toast: true,
                            showConfirmButton: false,
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showCloseButton: true, // Menambahkan tombol close (X)
                        }).then((result) => {
                            if (result.dismiss === Swal.DismissReason.close) {
                                lastShown[taskId] = Date.now(); // Reset jika notifikasi ditutup
                            }
                        });
                    }
                }
            });
        }, 10000); // Cek setiap 10 detik
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