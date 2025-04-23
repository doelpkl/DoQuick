<?php
session_start();
include('koneksi.php');
if (!isset($_SESSION['data_user'])) {
  header("Location: login.php");
  exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <!-- Favicon -->
<link rel="icon" href="assets/img/icon.png" type="image/png">

  <title>DoQuick | Manage Your Time, Easily</title>
  <meta
    content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
    name="viewport" />
  <!-- Fonts and icons -->
  <script src="assets/js/plugin/webfont/webfont.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

  <script>
    WebFont.load({
      google: {
        families: ["Public Sans:300,400,500,600,700"]
      },
      custom: {
        families: [
          "Font Awesome 5 Solid",
          "Font Awesome 5 Regular",
          "Font Awesome 5 Brands",
          "simple-line-icons",
        ],
        urls: ["assets/css/fonts.min.css"],
      },
      active: function() {
        sessionStorage.fonts = true;
      },
    });
  </script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      function fetchTaskData() {
        fetch("get_task_data_user.php")
          .then(response => response.json())
          .then(data => {
            if (data.error) {
              console.error("Error:", data.error);
              return;
            }
            document.getElementById("totalTugas").textContent = data.total;
            document.getElementById("tugasSelesai").textContent = data.selesai;
            document.getElementById("tugasBelumSelesai").textContent = data.belum_selesai;
          })
          .catch(error => console.error("Error fetching task data:", error));
      }
      fetchTaskData();
    });
  </script>


  <!-- CSS Files -->
  <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
  <link rel="stylesheet" href="assets/css/plugins.min.css" />
  <link rel="stylesheet" href="assets/css/kaiadmin.min.css" />

  <!-- CSS Just for demo purpose, don't include it in your project -->
  <link rel="stylesheet" href="assets/css/demo.css" />

  <style>
    @media (max-width: 768px) {

      /* Sembunyikan kolom selain Task dan Action */
      #taskTable th:nth-child(1),
      #taskTable th:nth-child(3),
      #taskTable th:nth-child(4),
      #taskTable th:nth-child(5),
      #taskTable td:nth-child(1),
      #taskTable td:nth-child(3),
      #taskTable td:nth-child(4),
      #taskTable td:nth-child(5) {
        display: none;
      }
    }

    .small-toast {
    font-size: 14px !important;
    padding: 10px 15px !important;
    width: auto !important;
    max-width: 300px;
}
  </style>
</head>

<!-- Sebelumnya sudah ada session_start() dan pengecekan login -->

<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar" data-background-color="dark">
      <div class="sidebar-logo">
        <!-- Logo Header -->
        <!-- <div class="logo-header" data-background-color="dark">
          <a href="index.php" class="logo">
            <img
              src="assets/img/d.jpg"
              alt="navbar brand"
              class="navbar-brand"
              height="20"
            />
          </a>
          <div class="nav-toggle">
            <button class="btn btn-toggle toggle-sidebar">
              <i class="gg-menu-right"></i>
            </button>
            <button class="btn btn-toggle sidenav-toggler">
              <i class="gg-menu-left"></i>
            </button>
          </div>
          <button class="topbar-toggler more">
            <i class="gg-more-vertical-alt"></i>
          </button>
        </div> -->
        <!-- End Logo Header -->
      </div>

      <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
          <ul class="nav nav-secondary">
            <?php
            $current_page = basename($_SERVER['PHP_SELF']); // Ambil nama file yang sedang diakses
            ?>

            <?php if ($_SESSION['data_user']['level'] == 'admin') : ?>
              <li class="nav-item <?= ($current_page == 'admin.php') ? 'active' : ''; ?>">
                <a href="admin.php">
                  <i class="fas fa-users-cog"></i>
                  <p>Admin Dashboard</p>
                </a>
              </li>
            <?php endif; ?>

            <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Menu</h4>
              </li>

            <li class="nav-item <?= ($current_page == 'index.php') ? 'active' : ''; ?>">
              <a href="index.php">
                <i class="fas fa-list"></i>
                <p>To Do List</p>
              </a>
            </li>

          </ul>
        </div>
      </div>
    </div>
    <!-- End Sidebar -->

    <!-- Main Panel -->
    <div class="main-panel">
      <div class="main-header">
        <div class="main-header-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>

        <!-- Navbar Header -->
        <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
          <div class="container-fluid">
            <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
              <li class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false" aria-haspopup="true">
                  <i class="fa fa-search"></i>
                </a>
                <ul class="dropdown-menu dropdown-search animated fadeIn">
                  <form class="navbar-left navbar-form nav-search">
                    <div class="input-group">
                      <input type="text" placeholder="Search ..." class="form-control" />
                    </div>
                  </form>
                </ul>
              </li>

              <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                  <div class="avatar-sm">
                    <img
                      src="<?php echo !empty($_SESSION['data_user']['foto']) ? 'assets/img/' . $_SESSION['data_user']['foto'] : 'assets/img/default.png'; ?>"
                      alt="Profile Picture"
                      class="avatar-img rounded-circle" />
                  </div>
                  <span class="profile-username">
                    <span class="fw-bold"><?php echo $_SESSION['data_user']['username']; ?></span>
                  </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                  <div class="dropdown-user-scroll scrollbar-outer">
                    <li>
                      <div class="user-box text-center">
                        <div class="avatar-lg">
                          <img
                            src="<?php echo !empty($_SESSION['data_user']['foto']) ? 'assets/img/' . $_SESSION['data_user']['foto'] : 'assets/img/default.png'; ?>"
                            alt="Profile Image"
                            class="avatar-img rounded" />
                        </div>
                        <div class="u-text mt-2">
                          <h4><?php echo $_SESSION['data_user']['username']; ?></h4>
                          <p class="text-muted"><?php echo $_SESSION['data_user']['level']; ?></p>
                        </div>
                      </div>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item">
                        <i class="fas fa-tasks me-2 text-primary"></i> Total Tugas:
                        <span id="totalTugas">0</span>
                      </a>
                      <a class="dropdown-item">
                        <i class="fas fa-check-circle me-2 text-success"></i> Tugas Selesai:
                        <span id="tugasSelesai">0</span>
                      </a>
                      <a class="dropdown-item">
                        <i class="fas fa-times-circle me-2 text-danger"></i> Belum Selesai:
                        <span id="tugasBelumSelesai">0</span>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="acc.php">
                        <i class="fas fa-cog me-2"></i> Account Setting
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item text-danger" href="logout.php">
                        <i class="fas fa-sign-out-alt me-2"></i> Logout
                      </a>
                    </li>
                  </div>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
