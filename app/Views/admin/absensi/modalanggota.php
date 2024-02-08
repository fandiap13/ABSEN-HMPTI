<div class="modal fade" id="modalanggota">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <ul class="list-group">
          <li class="list-group-item"><b>Nama Anggota :</b> <?= $anggota['nama']; ?></li>
          <li class="list-group-item"><b>NIM :</b> <?= $anggota['nim']; ?></li>
          <li class="list-group-item"><b>E-mail :</b> <?= $anggota['email']; ?></li>
          <li class="list-group-item"><b>Divisi :</b> <?= $anggota['nama_divisi']; ?></li>
          <li class="list-group-item"><b>Jabatan :</b> <?= $anggota['nama_jabatan']; ?></li>
        </ul>

        <h5 class="font-weight-bold mt-3">History Absen</h5>
        <?php if ($kegiatan) : ?>
          <table class="table table-bordered table-hover mt-3">
            <thead>
              <tr>
                <th style="width: 5%;">No.</th>
                <th>Nama Kegiatan</th>
                <th>Tanggal Kegiatan</th>
                <th>Tanggal Absensi</th>
                <th>Oleh</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              foreach ($kegiatan as $k) : ?>
                <tr>
                  <td><?= $i++; ?>.</td>
                  <td><?= $k['nama_kegiatan']; ?></td>
                  <td><?= date('d-m-Y H:i:s', strtotime($k['tgl_kegiatan'])); ?></td>
                  <td><?= date('d-m-Y H:i:s', strtotime($k['tanggal_absen'])); ?></td>
                  <td><?= $k['oleh']; ?></td>
                  <td>
                    <?php if ($k['status_absen'] == 1) : ?>
                      <nav class="badge badge-success">Masuk</nav>
                    <?php elseif ($k['status_absen'] == 2) : ?>
                      <nav class="badge badge-warning">Izin</nav>
                    <?php else : ?>
                      <nav class="badge badge-danger">Alpha</nav>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php else : ?>
          <p>Belum mengikuti kegiatan apapaun !</p>
        <?php endif; ?>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->