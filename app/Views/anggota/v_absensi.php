<?= $this->extend('templates/anggota_templates'); ?>

<?= $this->section('main'); ?>

<script src="<?= base_url(); ?>/html5-qrcode.min.js"></script>

<div class="row col-12 mb-3">
  <div class="col-lg-12 qr-container">
    <div id="reader"></div>
  </div>
</div>

<script>
  function onScanSuccess(decodedText, decodedResult) {
    // handle the scanned code as you like, for example:
    // console.log(`Code matched = ${decodedText}`, decodedResult);
    html5QrcodeScanner.clear(); // CLEAR SCANNER

    $.ajax({
      type: "post",
      url: "<?= base_url('anggota/scanAbsensi'); ?>",
      data: {
        kode_unik: decodedText,
        id_anggota: "<?= session("LoggedUserData")['nim']; ?>"
      },
      dataType: "json",
      success: function(response) {
        if (response.error) {
          Swal.fire('Error', response.error, 'error').then(() => window.location.reload());
        }
        if (response.success) {
          Swal.fire('Sukses', response.success, 'success').then(() => window.location = "<?= base_url('anggota'); ?>");
        }
      },
      error: function(xhr, ajaxOptions, thrownError) {
        alert(xhr.status + '\n' + thrownError);
      }
    });

  }

  function onScanFailure(error) {
    // handle scan failure, usually better to ignore and keep scanning.
    // for example:
    console.warn(`Code scan error = ${error}`);
  }

  // Square QR box with edge size = 70% of the smaller edge of the viewfinder.
  let qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
    let minEdgePercentage = 0.7; // 70%
    let minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
    let qrboxSize = Math.floor(minEdgeSize * minEdgePercentage);
    return {
      width: qrboxSize,
      height: qrboxSize
    };
  }

  let html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", {
      fps: 10,
      qrbox: qrboxFunction
    },
    /* verbose= */
    false);
  html5QrcodeScanner.render(onScanSuccess, onScanFailure);
</script>

<?= $this->endSection('main'); ?>