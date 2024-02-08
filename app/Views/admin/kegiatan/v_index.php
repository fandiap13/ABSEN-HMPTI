<?= $this->extend('templates/admin_templates'); ?>

<?= $this->section('main'); ?>

<link rel="stylesheet" href="<?= base_url(); ?>/template/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/template/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="<?= base_url(); ?>/template/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
<script src="<?= base_url(); ?>/template/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url(); ?>/template/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url(); ?>/template/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="<?= base_url(); ?>/template/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>

<!-- switch -->
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>


<div class="container-fluid">
  <div class="card card-primary card-outline">
    <div class="card-header">
      <div class="card-tools">
        <button type="button" class="btn btn-warning" onclick="window.location.href = '<?= base_url('admin/kegiatan'); ?>';"><i class="fas fa-sync"></i> Refresh</button>
        <button type="button" id="tambah_kegiatan" class="btn btn-primary"><i class="fas fa-plus"></i> Tambah Kegiatan</button>
      </div>
    </div>
    <div class="card-body">
      <?= form_open(); ?>
      <div class="row col-12">
        <div class="col-lg-4 form-group">
          <label for="tgl_mulai">Tanggal mulai</label>
          <input type="date" class="form-control" name="tgl_mulai" id="tgl_mulai" value="<?= !empty($_POST['tgl_mulai']) ? $_POST['tgl_mulai'] : ""; ?>" required>
        </div>
        <div class="col-lg-4 form-group">
          <label for="tgl_selesai">Tanggal selesai</label>
          <input type="date" class="form-control" name="tgl_selesai" id="tgl_selesai" value="<?= !empty($_POST['tgl_selesai']) ? $_POST['tgl_selesai'] : ""; ?>" required>
        </div>
        <div class="col-lg-4 form-group">
          <label for="">#</label>
          <button type="submit" class="btn btn-primary btn-block"><i class="fas fa-search"></i> Tampilkan Data</button>
        </div>
      </div>
      <?= form_close(); ?>
      <table id="example1" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th style="width: 5%;">No.</th>
            <th>Nama Kegiatan</th>
            <th>Tanggal Kegiatan</th>
            <th>Keterangan</th>
            <th>Sekertaris</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $i = 1;
          foreach ($kegiatan as $k) :
          ?>
            <tr>
              <td><?= $i++; ?>.</td>
              <td><?= $k['nama_kegiatan']; ?></td>
              <td><?= date('d F Y H:i:s', strtotime($k['tgl_kegiatan'])); ?></td>
              <td><?= $k['keterangan']; ?></td>
              <td><?= $k['nama']; ?></td>
              <td>
                <input type="checkbox" name="status_kegiatan" id_kegiatan="<?= $k['id']; ?>" data-toggle="toggle" data-size="sm" data-onstyle="primary" <?= $k['status_kegiatan'] == 1 ? 'checked' : ''; ?>>
              </td>
              <td class="text-center">
                <?php if ($k['status_kegiatan'] == 1) : ?>
                  <button class="btn btn-sm btn-info" title="Absen" onclick="modalAbsen('<?= $k['id']; ?>')"><i class="fas fa-qrcode"></i></button>
                <?php endif; ?>
                <button class="btn btn-sm btn-success" title="Detail" onclick="detail('<?= $k['id']; ?>')"><i class="fa fa-eye"></i></button>
                <button class="btn btn-sm btn-primary" title="Edit" onclick="edit('<?= $k['id']; ?>')"><i class="fas fa-edit"></i></button>
                <button class="btn btn-sm btn-danger" title="Hapus" onclick="hapus('<?= $k['id']; ?>')"><i class="fas fa-trash-alt"></i></button>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th style="width: 5%;">No.</th>
            <th>Nama Kegiatan</th>
            <th>Tanggal Kegiatan</th>
            <th>Keterangan</th>
            <th>Sekertaris</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>
</div>

<div class="viewmodal" style="display: none;"></div>

<div class="modal fade" id="modalabsen">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">QRCode Absensi Kegiatan</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id_kegiatan" class="id_kegiatan">
        <div id="qrcode_result" style="max-width: 100%;" class="d-flex justify-content-center"></div>
        <div class="text-bold text-center text-gray-dark mt-2">Waktu : <span id="timer"></span> detik</div>
        <p class="text-center">Silahkan scan Qrcode di atas untuk melakukan absensi, Qrcode akan berubah setiap 10 detik</p>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="close()">Tutup</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  // mengenerate qrcode
  let qrcodeNya;
  // menginisialisasikan interval
  var interval;
  var hitungMundurTimer;

  // generate qrcode
  function generateQrCode(qrContent, target) {
    return new QRCode(target, {
      text: qrContent,
      width: 350,
      height: 350,
      colorDark: "#000000",
      colorLight: "#ffffff",
      correctLevel: QRCode.CorrectLevel.H,
    });
  }

  function modalAbsen(id) {
    $('#modalabsen').modal('show');
    $('#modalabsen .id_kegiatan').val(id);
  }

  // hitung mundur untuk generate qrcode
  function hitungMundur() {
    var timeleft = 0;
    document.getElementById("timer").textContent = 0;
    hitungMundurTimer = setInterval(function() {
      timeleft++;
      document.getElementById("timer").textContent = timeleft;
      if (timeleft >= 10)
        clearInterval(hitungMundurTimer);
    }, 1000);
  }

  // ubah kode unik pada qrcode
  function ubahKodeUnik() {
    let id_kegiatan = $('#modalabsen .id_kegiatan').val();
    $.ajax({
      type: "post",
      url: "<?= base_url('admin/kegiatan/ubahKodeUnik'); ?>",
      data: {
        id_kegiatan
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function ambilQRCode() {
    let id_kegiatan = $('#modalabsen .id_kegiatan').val();
    $.ajax({
      type: "post",
      url: "<?= base_url('admin/kegiatan/ambil_kode_unik'); ?>",
      data: {
        id_kegiatan
      },
      dataType: "json",
      success: function(response) {
        if (response.kode_unik) {
          if (qrcodeNya == null || qrcodeNya == "" || qrcodeNya == undefined) {
            qrcodeNya = generateQrCode(response.kode_unik, 'qrcode_result');
          } else {
            qrcodeNya.makeCode(response.kode_unik, 'qrcode_result');
          }
        }
        // console.log(response);

        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  }

  function hapus(id) {
    Swal.fire({
      title: 'Hapus !',
      text: "Apakah anda yakin menghapus kegiatan ini!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, Hapus!',
      cancelButtonText: 'batal'
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "<?= base_url('admin/kegiatan/hapusKegiatan'); ?>",
          data: {
            id
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
      }
    });
  }

  function edit(id) {
    $.ajax({
      type: "get",
      url: "<?= base_url('admin/kegiatan/modalEdit'); ?>",
      data: {
        id
      },
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data).show();
          $('#modaledit').modal('show');
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

  function detail(id) {
    window.location.href = `<?= base_url('admin/kegiatan/detail/'); ?>${id}`;
  }

  $(function() {
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

  $('#tambah_kegiatan').click(function(e) {
    e.preventDefault();
    $.ajax({
      url: "<?= base_url('admin/kegiatan/modalKegiatan'); ?>",
      dataType: "json",
      success: function(response) {
        if (response.data) {
          $('.viewmodal').html(response.data).show();
          $('#modaltambah').modal('show');
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });

  // program yang berjalan ketika modalabsen dimunculkan
  $('#modalabsen').on('shown.bs.modal', function() {
    ambilQRCode();
    hitungMundur();

    // setting interval 10 detik
    interval = setInterval(() => {
      // panggil fungsi setelah 10 detik
      ubahKodeUnik();
      ambilQRCode();
      hitungMundur();
    }, 11000);
  });

  // mematikan setinterval ketika modalabsen di close
  $('#modalabsen').on('hidden.bs.modal', function() {
    // menghapus semua interval
    clearInterval(interval);
    clearInterval(hitungMundurTimer);
  });

  $("input[name=status_kegiatan]").change(function(e) {
    e.preventDefault();
    // mengatur status switch pada status kegiatan
    if ($(".toggle.btn").hasClass("btn-light")) {
      $(".toggle.btn").removeClass("btn-light").removeClass("off");
      $(".toggle.btn").addClass("btn-primary");
    } else {
      $(".toggle.btn").removeClass("btn-primary");
      $(".toggle.btn").addClass("btn-light").addClass("off");
    }

    let status_kegiatan;
    if ($(this).is(':checked')) {
      status_kegiatan = 1;
    } else {
      status_kegiatan = 2;
    }
    Swal.fire({
      title: 'Edit status kegiatan',
      text: `Apakah anda yakin ingin ${status_kegiatan == 1 ? 'mengaktifkan' : 'menonaktifkan'} status kegiatan ?`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Ya, lakukan!',
      cancelButtonText: 'batal',
    }).then((result) => {
      if (result.isConfirmed) {
        $.ajax({
          type: "post",
          url: "<?= base_url('admin/kegiatan/status_kegiatan'); ?>",
          data: {
            id_kegiatan: $(this).attr('id_kegiatan'),
            status_kegiatan: status_kegiatan
          },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              if ($(".toggle.btn").hasClass("btn-light")) {
                $(".toggle.btn").removeClass("btn-light").removeClass("off");
                $(".toggle.btn").addClass("btn-primary");
              } else {
                $(".toggle.btn").removeClass("btn-primary");
                $(".toggle.btn").addClass("btn-light").addClass("off");
              }

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
</script>

<?= $this->endSection('main'); ?>