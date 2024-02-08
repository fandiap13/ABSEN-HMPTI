<?= $this->extend('templates/admin_templates'); ?>

<?= $this->section('main'); ?>

<?php $db = \Config\Database::connect(); ?>

<style>
  table tbody tr.daftarAnggota:hover {
    color: white;
    background-color: #007bff;
    cursor: pointer;
  }
</style>

<div class="container-fluid">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <div class="card-tools">
        <button type="button" class="btn btn-warning" onclick="window.location.href = '<?= base_url('admin/absensi'); ?>';"><i class="fas fa-sync"></i> Refresh</button>
      </div>
    </div>
    <div class="card-body">
      <?= form_open("", ['class' => 'row col-12']); ?>
      <div class="form-group col-lg-3">
        <label for="tgl_mulai">Tanggal mulai</label>
        <input type="date" class="form-control" name="tgl_mulai" id="tgl_mulai" value="<?= !empty($_POST['tgl_mulai']) ? $_POST['tgl_mulai'] : ""; ?>" required>
      </div>
      <div class="form-group col-lg-3">
        <label for="tgl_selesai">Tanggal selesai</label>
        <input type="date" class="form-control" name="tgl_selesai" id="tgl_selesai" value="<?= !empty($_POST['tgl_selesai']) ? $_POST['tgl_selesai'] : ""; ?>" required>
      </div>
      <div class="form-group col-lg-6">
        <label for="">#</label>
        <div class="row col-12">
          <div class="col-lg-6">
            <button type="button" class="btn btn-success btn-block" onclick="cetak()"><i class="fas fa-print"></i> Cetak Laporan</button>
          </div>
          <div class="col-lg-6">
            <button type="submit" class="btn btn-block btn-primary"><i class="fa fa-search"></i> Tampilkan</button>
          </div>
        </div>
      </div>
      <?= form_close(); ?>

      <div class="table-responsive">
        <?php if ($kegiatan->getNumRows() > 0) { ?>
          <?php if (isset($_POST['tgl_mulai']) || isset($_POST['tgl_selesai'])) { ?>
            <p class="text-center">Laporan absensi anggota dari tanggal <b><?= date('d-m-Y', strtotime($_POST['tgl_mulai'])); ?></b> sampai <b><?= date('d-m-Y', strtotime($_POST['tgl_selesai'])); ?></b></p>
          <?php } ?>
          <table class="table table-sm table-bordered">
            <thead>
              <tr>
                <th rowspan="2" class="align-middle text-center" style="width: 10px;">No</th>
                <th rowspan="2" class="align-middle text-center" style="width: 180px;">Nama</th>
                <th rowspan="2" class="align-middle text-center" style="width: 90px;">NIM</th>
                <?php $colspan = $kegiatan->getNumRows(); ?>
                <th class="text-center" colspan="<?= $colspan; ?>">Kegiatan</th>
              </tr>
              <tr>
                <?php foreach ($kegiatan->getResultArray() as $k) : ?>
                  <th class="text-center">
                    <?= $k['nama_kegiatan']; ?> <br>
                    (<?= date('d-m-Y', strtotime($k['tgl_kegiatan'])); ?>)
                  </th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php
              $i = 1;
              foreach ($anggota as $a) :
              ?>
                <tr class="daftarAnggota">
                  <td><?= $i++; ?></td>
                  <td><?= $a['nama']; ?></td>
                  <td><?= $a['nim']; ?></td>
                  <?php
                  foreach ($kegiatan->getResultArray() as $k2) :
                    $cekAbsen = $db->table('h_absensi')->select('status_absen')->getWhere([
                      'id_kegiatan' => $k2['id'],
                      'id_anggota' => $a['nim']
                    ])->getRowArray();
                  ?>
                    <td class="text-center">
                      <?php if (!$cekAbsen) : ?>
                        Belum diabsen
                      <?php else : ?>
                        <?php if ($cekAbsen['status_absen'] == 1) : ?>
                          <nav class="badge badge-success">Masuk</nav>
                        <?php elseif ($cekAbsen['status_absen'] == 2) : ?>
                          <nav class="badge badge-warning">Izin</nav>
                        <?php else : ?>
                          <nav class="badge badge-danger">Alpha</nav>
                        <?php endif; ?>
                      <?php endif; ?>
                    </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php } else { ?>
          <?php if (isset($_POST['tgl_mulai']) || isset($_POST['tgl_selesai'])) { ?>
            <p>Tidak ada kegiatan dari tanggal <b><?= date('d-m-Y', strtotime($_POST['tgl_mulai'])); ?></b> sampai <b><?= date('d-m-Y', strtotime($_POST['tgl_selesai'])); ?></b></p>
          <?php } else { ?>
            <p>Tidak ada kegiatan yang tercatat pada sistem</p>
          <?php } ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div>

<div class="viewmodal" style="display: none;"></div>

<script>
  function cetak() {
    let tgl_mulai = $('input[name=tgl_mulai]').val();
    let tgl_selesai = $('input[name=tgl_selesai]').val();
    if (tgl_mulai && tgl_selesai) {
      window.open(`<?= base_url('admin/absensi/cetak_laporan'); ?>/${tgl_mulai}/${tgl_selesai}`, "_blank");
    } else {
      window.open("<?= base_url('admin/absensi/cetak_laporan'); ?>", "_blank");
    }
  }

  $('.daftarAnggota').click(function(e) {
    e.preventDefault();
    let nim = $(this).children('td:nth-child(3)').html();

    $.ajax({
      type: "post",
      url: "<?= base_url('admin/absensi/modalAnggota'); ?>",
      data: {
        id_anggota: nim
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data).show();
          $('#modalanggota').modal('show');
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });
</script>

<?= $this->endSection('main'); ?>