<?php
include('koneksi.php');

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $query = "SELECT * FROM user WHERE id_user = '$id'";
    $result = mysqli_query($koneksi, $query);
    $user = mysqli_fetch_assoc($result);

    if ($user) {
        $query_tasks = "SELECT COUNT(*) as total_task FROM tasks WHERE id_user = '$id'";
        $result_tasks = mysqli_query($koneksi, $query_tasks);
        $total_task = mysqli_fetch_assoc($result_tasks)['total_task'];

        $query_completed_tasks = "SELECT COUNT(*) as completed_task FROM tasks WHERE id_user = '$id' AND status = 'completed'";
        $completed_task = mysqli_fetch_assoc(mysqli_query($koneksi, $query_completed_tasks))['completed_task'];

        $query_incomplete_tasks = "SELECT COUNT(*) as incomplete_task FROM tasks WHERE id_user = '$id' AND status = 'incomplete'";
        $incomplete_task = mysqli_fetch_assoc(mysqli_query($koneksi, $query_incomplete_tasks))['incomplete_task'];

        // Versi desain rapi saja, yang baru
        echo "
        <div class='text-center mb-4'>
            <img src='assets/img/" . (!empty($user['foto']) ? $user['foto'] : 'default.png') . "' 
                 class='rounded-circle shadow-sm mb-2' 
                 width='100' height='100' 
                 style='object-fit: cover;'>
            <h5 class='fw-bold mb-0'>" . htmlspecialchars($user['username']) . "</h5>
        </div>

        <div class='row text-center'>
            <div class='col-4'>
                <div class='p-2 border rounded shadow-sm'>
                    <i class='fas fa-tasks text-primary mb-1' style='font-size: 24px;'></i>
                    <div class='fw-semibold'>Total Task</div>
                    <div class='text-muted'>$total_task</div>
                </div>
            </div>
            <div class='col-4'>
                <div class='p-2 border rounded shadow-sm'>
                    <i class='fas fa-check-circle text-success mb-1' style='font-size: 24px;'></i>
                    <div class='fw-semibold'>Completed</div>
                    <div class='text-muted'>$completed_task</div>
                </div>
            </div>
            <div class='col-4'>
                <div class='p-2 border rounded shadow-sm'>
                    <i class='fas fa-times-circle text-danger mb-1' style='font-size: 24px;'></i>
                    <div class='fw-semibold'>Incomplete</div>
                    <div class='text-muted'>$incomplete_task</div>
                </div>
            </div>
        </div>
        ";
    } else {
        echo "<p class='text-center text-danger'>User tidak ditemukan.</p>";
    }
}
?>
