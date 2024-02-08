<?= $this->extend('templates/anggota_templates'); ?>

<?= $this->section('main'); ?>
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-body d-flex">

        <?php
        // jika file tidak ditemukan maka akan menampilkan gambar default
        if (@getimagesize("https://hmpti.udb.ac.id/assets/img/members/" . session('LoggedUserData')['image'])) {
          $image = "https://hmpti.udb.ac.id/assets/img/members/" . session('LoggedUserData')['image'];
        } else {
          $image = base_url('img/default.png');
        }
        ?>

        <img src="<?= $image; ?>" alt="Profil User" style="width: 150px; height: 150px; object-fit: cover; object-position: center; border-radius: 5px;" class="mr-3">

        <div class="d-flex flex-column">
          <h5 class="card-title text-bold"><?= session('LoggedUserData')['nama']; ?></h5>

          <p class="card-text" style="width: 100%;">
          <ul class="list-unstyled">
            <li><i class="fas fa-id-card fa-sm"></i> <b>NIM :</b> <?= session('LoggedUserData')['nim']; ?></li>
            <li><i class="fas fa-envelope fa-sm"></i> <b>E-mail :</b> <?= session('LoggedUserData')['email']; ?></li>
            <li><i class="fas fa-university fa-sm"></i> <b>Divisi :</b> <?= session('LoggedUserData')['divisi']; ?></li>
            <li><i class="fas fa-chess fa-sm"></i> <b>Jabatan :</b> <?= session('LoggedUserData')['jabatan']; ?></li>
          </ul>
          </p>
        </div>

      </div>
    </div>
  </div>
  <div class="col-lg-4">
    <div class="card">
      <div class="card-body d-flex flex-column align-items-center">
        <h5 class="card-title text-bold">Klik untuk absen</h5>
        <p class="card-text">
          <a href="<?= base_url('anggota/absensi'); ?>" class="text-secondary">
            <i class="d-block fas fa-qrcode fa-9x"></i>
          </a>
        </p>
      </div>
    </div>
  </div>
</div>

<hr>

<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title m-0"><i class="fas fa-calendar-check"></i> History Absensi</h5>
      </div>
      <div class="card-body">

        <?php
        if ($history) {
          foreach ($history as $h) : ?>

            <div class="card">
              <div class="card-header">
                <h5 class="card-title m-0"><b>Kegiatan :</b> <?= $h['nama_kegiatan']; ?></h5>
              </div>
              <div class="card-body text-gray">
                <p class="card-text">
                  <?= $h['keterangan']; ?>

                  <hr style="border-top: 1px dashed gray;">

                <div class="d-flex justify-content-between">
                  <ul class="list-unstyled" style="color: black;">
                    <li>Sekertaris : <?= $h['nama_sekertaris']; ?></li>
                    <li>Tanggal Kegiatan : <?= date("d-m-Y H:i:s", strtotime($h['tgl_kegiatan'])); ?></li>
                    <li>Kegiatan Selesai : <?= $h['tgl_selesai'] === null ? "Belum selesai" : date("d-m-Y H:i:s", strtotime($h['tgl_selesai'])); ?></li>
                    <li>Absen Oleh : <?= $h['oleh']; ?></li>
                    <li>Waktu Absen : <?= date("d-m-Y H:i:s", strtotime($h['tanggal_absen'])); ?></li>
                  </ul>

                  <div class="status-absen">
                    <?php if ($h['status_absen'] == 1) : ?>
                      <nav class="badge badge-success">Masuk</nav>
                    <?php elseif ($h['status_absen'] == 2) : ?>
                      <nav class="badge badge-warning">Izin</nav>
                    <?php else : ?>
                      <nav class="badge badge-danger">Alpha</nav>
                    <?php endif; ?>
                  </div>
                </div>

                </p>
              </div>
            </div>

          <?php
          endforeach;
        } else { ?>
          <p class="card-text">History Masih Kosong</p>
        <?php } ?>

      </div>
    </div>

    <?= $page_count > 1 ? $pager_links : "" ?>

  </div>
</div>
<?= $this->endSection('main'); ?>