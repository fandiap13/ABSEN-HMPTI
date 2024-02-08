<?php

$request = \Config\Services::request();
$url = $request->uri->getSegment(2);

?>

<!DOCTYPE html>

<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Panel | <?= $title; ?></title>

  <style>
    .loader-wrapper {
      position: absolute;
      background-color: #212121;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
    }

    .loading-wave {
      display: block;
      width: 300px;
      height: 100px;
      display: flex;
      justify-content: center;
      align-items: flex-end;
    }

    .loading-bar {
      width: 20px;
      height: 10px;
      margin: 0 5px;
      background-color: #3498db;
      border-radius: 5px;
      animation: loading-wave-animation 1s ease-in-out infinite;
    }

    .loading-bar:nth-child(2) {
      animation-delay: 0.1s;
    }

    .loading-bar:nth-child(3) {
      animation-delay: 0.2s;
    }

    .loading-bar:nth-child(4) {
      animation-delay: 0.3s;
    }

    @keyframes loading-wave-animation {
      0% {
        height: 10px;
      }

      50% {
        height: 50px;
      }

      100% {
        height: 10px;
      }
    }
  </style>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url(); ?>/template/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>/template/dist/css/adminlte.min.css">
  <!-- jQuery -->
  <script src="<?= base_url(); ?>/template/plugins/jquery/jquery.min.js"></script>
  <!-- sweetalert -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="hold-transition sidebar-mini">

  <div class="loader-wrapper">
    <div class="loading-wave">
      <div class="loading-bar"></div>
      <div class="loading-bar"></div>
      <div class="loading-bar"></div>
      <div class="loading-bar"></div>
    </div>
  </div>

  <div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="<?= base_url(); ?>/template/#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <button type="button" class="btn btn-danger keluar"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="#" class="brand-link">
        <img src="<?= base_url(); ?>/img/logo.png" alt="Logo HMPTI" class="brand-image img-circle">
        <span class="brand-text font-weight-light">HMPTI ABSEN</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">

          <?php

          if (@getimagesize("https://hmpti.udb.ac.id/assets/img/members/" . session('LoggedUserData')['image'])) {
            $image = "https://hmpti.udb.ac.id/assets/img/members/" . session('LoggedUserData')['image'];
          } else {
            $image = base_url('img/default.png');
          }

          ?>
          <!-- <div class="image text-white h4">
            <i class="fa fa-user nav-icon ml-1"></i>
          </div> -->
          <div class="image">
            <a href="<?= $image; ?>" target="_blank">
              <img src="<?= $image; ?>" class="img-circle" style="object-fit: cover; width: 45px; height: 45px; object-position: center;" alt="User Image">
            </a>
          </div>
          <div class="info">
            <a href="" class="d-block"><?= session('LoggedUserData')['nama']; ?></a>
          </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
          <div class="input-group" data-widget="sidebar-search">
            <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
              <button class="btn btn-sidebar">
                <i class="fas fa-search fa-fw"></i>
              </button>
            </div>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <li class="nav-item">
              <a href="<?= base_url('anggota'); ?>" class="nav-link">
                <i class="nav-icon fas fa-user-check"></i>
                <p>
                  Lakukan Absensi
                </p>
              </a>
            </li>

            <li class="nav-header">MENU ADMIN</li>

            <li class="nav-item">
              <a href="<?= base_url('admin/dashboard'); ?>" class="nav-link <?= $url === 'dashboard' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-tachometer-alt"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/kegiatan'); ?>" class="nav-link <?= $url === 'kegiatan' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-calendar"></i>
                <p>
                  Daftar Kegiatan
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('admin/absensi'); ?>" class="nav-link <?= $url === 'absensi' ? 'active' : ''; ?>">
                <i class="nav-icon fas fa-user-check"></i>
                <p>
                  Laporan Absensi
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link keluar">
                <i class="nav-icon fas fa-sign-out-alt"></i>
                <p>
                  Logout
                </p>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"><?= $subtitle ? $subtitle : $title; ?></h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item <?= $title && $subtitle == "" ? "active" : ""; ?>"><a href="#"><?= $title; ?></a></li>
                <?php if ($subtitle) { ?>
                  <li class="breadcrumb-item <?= $subtitle ? "active" : ""; ?>"><?= $subtitle; ?></li>
                <?php } ?>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <?= $this->renderSection('main'); ?>
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <!-- <div class="float-right d-none d-sm-inline">
        Anything you want
      </div> -->
      <!-- Default to the left -->
      <strong>Copyright &copy; <?= date('Y'); ?> <a href="https://hmpti.udb.ac.id/">HMPTI</a>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->
  <!-- Bootstrap 4 -->
  <script src="<?= base_url(); ?>/template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>/template/dist/js/adminlte.min.js"></script>

  <script>
    $(window).on("load", function() {
      $(".loader-wrapper").fadeOut("slow");
    });

    $(document).ready(function() {
      let msg = "<?= session()->getFlashData('msg'); ?>";
      if (msg) {
        let pesan = msg.split('#');
        Swal.fire({
          position: 'top-end',
          toast: true,
          icon: pesan[0],
          title: pesan[1],
          showConfirmButton: false,
          timer: 4000
        });
      }

      $('.keluar').click(function(e) {
        e.preventDefault();
        Swal.fire({
          title: 'Logout',
          text: "Apakah anda yakin keluar dari halaman ini ?",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, keluar!',
          cancelButtonText: 'batal'
        }).then((result) => {
          if (result.isConfirmed) {
            window.location = "<?= base_url('logout'); ?>";
          }
        });
      });
    });
  </script>
</body>

</html>