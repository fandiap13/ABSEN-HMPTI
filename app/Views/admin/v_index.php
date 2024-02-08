<?= $this->extend('templates/admin_templates'); ?>


<?= $this->section('main'); ?>

<div class="container-fluid">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <h5 class="card-title">User Login</h5>
      <div class="card-tools">

      </div>
    </div>
    <div class="card-body">
      <ul class="list-group">
        <li class="list-group-item"><strong>Nama Lengkap: </strong><?= $user['nama']; ?></li>
        <li class="list-group-item"><strong>NIM: </strong><?= $user['nim']; ?></li>
        <li class="list-group-item"><strong>Email: </strong><?= $user['email']; ?></li>
        <li class="list-group-item"><strong>Kelas: </strong><?= $user['kelas']; ?></li>
        <li class="list-group-item"><strong>Jabatan: </strong><?= $user['nama_jabatan']; ?></li>
        <li class="list-group-item"><strong>Waktu Login: </strong><?= date("d-m-Y H:i:s", strtotime(session('LoggedUserData')['waktu_login'])); ?></li>
      </ul>
    </div>
  </div><!-- /.card -->

</div><!-- /.container-fluid -->

<?= $this->endSection('main'); ?>