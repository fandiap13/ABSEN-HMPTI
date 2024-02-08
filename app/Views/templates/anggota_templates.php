<?php

$request = \Config\Services::request();

try {
  $uri = empty($request->uri->getSegment(1)) ? null : $request->uri->getSegment(1);
  $uri2 = empty($request->uri->getSegment(2)) ? null : $request->uri->getSegment(2);
} catch (\Throwable $th) {
  $uri = null;
  $uri2 = null;
}


?>


<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>HMPTI ABSEN | <?= $title; ?></title>

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

<body class="hold-transition layout-top-nav">
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
    <nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
      <div class="container">
        <a href="<?= base_url("anggota"); ?>" class="navbar-brand">
          <img src="<?= base_url(); ?>/img/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
          <span class="brand-text font-weight-light">HMPTI ABSEN</span>
        </a>

        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
          <!-- Left navbar links -->
          <ul class="navbar-nav">
            <li class="nav-item">

              <?php

              if ($uri2 === null && $uri == "anggota" || $uri === null) {
                $active = "active";
              } else {
                $active = "";
              }

              ?>

              <a href="<?= base_url('anggota'); ?>" class="nav-link <?= $active; ?>">Beranda</a>
            </li>
            <li class="nav-item dropdown">
              <a id="dropdownSubMenu1" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="nav-link dropdown-toggle"><?= session('LoggedUserData')['nama']; ?></a>
              <ul aria-labelledby="dropdownSubMenu1" class="dropdown-menu border-0 shadow">
                <?php if (session('LoggedUserData')['role'] == 'admin') : ?>
                  <li><a href="<?= base_url('admin/dashboard'); ?>" class="dropdown-item"><i class="fas fa-tachometer-alt"></i> Kembali ke dashboard </a></li>
                <?php endif; ?>
                <li><a href="<?= base_url('anggota/absensi'); ?>" class="dropdown-item"><i class="fas fa-user-check"></i> Absensi</a></li>
                <li><a href="#" class="dropdown-item logoutUser"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>

        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
          <li class="nav-item">
            <button type="button" class="btn btn-danger logoutUser"><i class="fas fa-sign-out-alt"></i> Logout</button>
          </li>
        </ul>
      </div>
    </nav>
    <!-- /.navbar -->

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0"> <?= $title; ?></h1>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container">

          <?= $this->renderSection('main'); ?>

        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <footer class="main-footer">
      <!-- To the right -->
      <!-- <div class="float-right d-none d-sm-inline">
        Anything you want
      </div> -->
      <!-- Default to the left -->
      <strong>Copyright &copy; <?= date('Y'); ?> <a href="https://hmpti.udb.ac.id" target="_blank">HMPTI UDB</a>.</strong> All rights reserved.
    </footer>
  </div>
  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- Bootstrap 4 -->
  <script src="<?= base_url(); ?>/template/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="<?= base_url(); ?>/template/dist/js/adminlte.min.js"></script>
  <!-- AdminLTE for demo purposes -->
  <script src="<?= base_url(); ?>/template/dist/js/demo.js"></script>

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

      $('.logoutUser').click(function(e) {
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