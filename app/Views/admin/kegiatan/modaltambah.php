<div class="modal fade" id="modaltambah">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"><?= $title; ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?= form_open(base_url('admin/kegiatan/simpanKegiatan'), ['class' => 'form_tambah']); ?>
      <div class="modal-body">
        <div class="form-group">
          <label for="nama_kegiatan">Nama Kegiatan</label>
          <input type="text" name="nama_kegiatan" id="nama_kegiatan" class="form-control">
          <div class="invalid-feedback error_nama_kegiatan"></div>
        </div>
        <div class="form-group">
          <label for="tgl_kegiatan">Tanggal Kegiatan</label>
          <input type="datetime-local" name="tgl_kegiatan" id="tgl_kegiatan" class="form-control">
          <div class="invalid-feedback error_tgl_kegiatan"></div>
        </div>
        <div class="form-group">
          <label for="keterangan">Keterangan</label>
          <textarea name="keterangan" id="keterangan" class="form-control" rows="3"></textarea>
          <div class="invalid-feedback error_keterangan"></div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary btnSimpan"><i class="fa fa-save"></i> Simpan</button>
      </div>
      <?= form_close(); ?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script>
  $('#modaltambah').on('shown.bs.modal', function() {
    $('#nama_kegiatan').trigger('focus')
  });

  $('.form_tambah').submit(function(e) {
    e.preventDefault();
    $.ajax({
      type: "post",
      url: $(this).attr('action'),
      data: $(this).serialize(),
      dataType: "json",
      beforeSend: function() {
        $('.btnSimpan').html('<i class="fa fa-spinner fa-spin"></i>');
        $('.btnSimpan').attr('disabled', true);
      },
      complete: function() {
        $('.btnSimpan').html('<i class="fa fa-save"></i> Simpan');
        $('.btnSimpan').removeAttr('disabled');
      },
      success: function(response) {
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success').then(() => window.location.reload());
        }
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
        }
        if (response.errors) {
          let errors = response.errors;
          if (errors.nama_kegiatan) {
            $('#nama_kegiatan').addClass('is-invalid');
            $('.error_nama_kegiatan').html(errors.nama_kegiatan);
          } else {
            $('#tgl_kegiatan').removeClass('is-invalid');
            $('.error_tgl_kegiatan').html("");
          }
          if (errors.tgl_kegiatan) {
            $('#tgl_kegiatan').addClass('is-invalid');
            $('.error_tgl_kegiatan').html(errors.tgl_kegiatan);
          } else {
            $('#tgl_kegiatan').removeClass('is-invalid');
            $('.error_tgl_kegiatan').html("");
          }
          if (errors.keterangan) {
            $('#keterangan').addClass('is-invalid');
            $('.error_keterangan').html(errors.keterangan);
          } else {
            $('#keterangan').removeClass('is-invalid');
            $('.error_keterangan').html("");
          }
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });
  });
</script>