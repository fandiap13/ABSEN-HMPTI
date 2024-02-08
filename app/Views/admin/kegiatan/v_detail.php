<?= $this->extend('templates/admin_templates'); ?>

<?= $this->section('main'); ?>

<link rel="stylesheet" href="<?= base_url(); ?>/template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<script src="<?= base_url(); ?>/template/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<style>
  #example1 tbody tr:hover {
    background-color: #007bff;
    color: white;
    cursor: pointer;
  }
</style>


<div class="container-fluid">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <div class="card-tools">
        <button type="button" id="tambah_kegiatan" class="btn btn-primary" onclick="window.location = '<?= base_url('admin/kegiatan'); ?>'"><i class="fas fa-arrow-left"></i> Kembali</button>
      </div>
    </div>
    <div class="card-body row">

      <div class="col-lg-4">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Kegiatan</h4>
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item"><b>Nama Kegiatan : </b> <?= $kegiatan['nama_kegiatan']; ?></li>
              <li class="list-group-item"><b>Tanggal Kegiatan : </b> <?= date("d F Y H:i:s", strtotime($kegiatan['tgl_kegiatan'])); ?></li>
              <li class="list-group-item"><b>Tanggal Selesai : </b> <?= $kegiatan['tgl_selesai'] !== null ? date("d F Y H:i:s", strtotime($kegiatan['tgl_selesai'])) : "Belum selesai kakak"; ?></li>
              <li class="list-group-item"><b>Sekertaris: </b> <?= $kegiatan['nama']; ?></li>
              <li class="list-group-item"><b>Status Kegiatan: </b> <?= $kegiatan['status_kegiatan'] == 1 ? '<nav class="badge badge-success">Aktif</nav>' : '<nav class="badge badge-danger">Tidak aktif</nav> '; ?></li>
              <li class="list-group-item"><b>Keterangan: </b> <br> <?= $kegiatan['keterangan']; ?></li>
            </ul>
          </div>
        </div>
      </div>

      <div class="col-lg-8">
        <div class="card">
          <div class="card-header">
            <h4 class="card-title">Absensi Anggota</h4>
            <div class="card-tools">
              <!-- jika status_kegiatan tidak aktif maka button tidak ditampilkan -->
              <?php if ($kegiatan['status_kegiatan'] == 1) : ?>
                <button class="btn btn-success aktifkan" onclick="aktifkanAksi();"><i class="fas fa-lock"></i> Aktifkan Aksi</button>
                <button class="btn btn-danger matikan" onclick="matikanAksi();"><i class="fas fa-lock-open"></i> Matikan Aksi</button>
              <?php endif; ?>
            </div>
          </div>
          <div class="card-body">
            <table id="example1" class="table table-bordered table-hover">
              <thead>
                <tr>
                  <th style="width: 3%;">No.</th>
                  <th>Anggota</th>
                  <th>Divisi</th>
                  <!-- <th>Status</th> -->
                  <th style="width: 20%;">Absensi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $i = 1;
                foreach ($member as $m) : ?>
                  <tr id_anggota="<?= $m['nim']; ?>">
                    <td><?= $i++; ?>.</td>
                    <td><?= $m['nama']; ?></td>
                    <td><?= $m['nama_divisi']; ?></td>
                    <!-- <td> -->
                    <?php
                    $absensi = $db->table('h_absensi')->getWhere(['id_anggota' => $m['nim'], 'id_kegiatan' => $kegiatan['id']])->getRowArray();
                    // echo $absensi ? "<span class='badge badge-success'>Sudah diabsen</span>" : "<span class='badge badge-danger'>Belum diabsen</span>";
                    ?>
                    <!-- </td> -->
                    <td>
                      <select name="absensi" class="absensi" id="absensi" id_kegiatan="<?= $kegiatan['id']; ?>" id_anggota="<?= $m['nim']; ?>">
                        <option value="">--Pilih--</option>
                        <option value="1" <?= $absensi && $absensi['status_absen'] == 1 ? "selected" : ""; ?>>Masuk</option>
                        <option value="2" <?= $absensi && $absensi['status_absen'] == 2 ? "selected" : ""; ?>>Izin</option>
                        <option value="3" <?= $absensi && $absensi['status_absen'] == 3 ? "selected" : ""; ?>>Alpha</option>
                      </select>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="viewmodal" style="display: none;"></div>

<script>
  function reset() {
    $('select[name=absensi]').attr('disabled', true);
    $('.matikan').hide();
    $('.aktifkan').show();
  }

  function aktifkanAksi() {
    $('.matikan').show();
    $('.aktifkan').hide();
    $('select[name=absensi]').removeAttr('disabled');
  }

  function matikanAksi() {
    reset();
  }

  $('select[name=absensi]').change(function(e) {
    e.preventDefault();
    Swal.fire({
      title: 'Absensi',
      text: "Apakah anda yakin mengubah status absen ?",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, ubah!',
      cancelButtonText: 'batal',
    }).then((result) => {
      if (result.isConfirmed) {
        let id_kegiatan = $(this).attr('id_kegiatan');
        let id_anggota = $(this).attr('id_anggota');
        let status_absen = $(this).val();
        $.ajax({
          type: "post",
          url: "<?= base_url('admin/absensi/absenManual'); ?>",
          data: {
            id_kegiatan,
            id_anggota,
            status_absen
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
            }
            if (response.error) {
              Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
            }
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(xhr.status + '\n' + thrownError);
          }
        });
      } else {
        window.location.reload();
      }
    });
  });

  $('#example1 tbody tr').click(function(e) {
    e.preventDefault();
    // if (e.target !== $('.absensi')[0]) {
    if (!$(e.target).hasClass('absensi')) {
      $.ajax({
        type: "post",
        url: "<?= base_url('admin/absensi/modalAnggota'); ?>",
        data: {
          id_anggota: $(this).attr('id_anggota')
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
    }
  });

  $(document).ready(function() {
    reset();

    $('#example1').DataTable({
      "paging": true,
      "lengthChange": true,
      "searching": true,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });
</script>

<?= $this->endSection('main'); ?>