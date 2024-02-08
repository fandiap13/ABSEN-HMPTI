<?php $db = \Config\Database::connect(); ?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $title; ?></title>
</head>

<body onload="print()">
  <h1 style="text-align: center;">Laporan Absensi Anggota</h1>
  <?php if ($kegiatan->getNumRows() > 0) { ?>
    <?php if ($tgl_mulai !== null && $tgl_selesai !== null) { ?>
      <p style="text-align: center; font-size: 1.4rem;">Pada tanggal <b><?= date('d-m-Y', strtotime($tgl_mulai)); ?></b> sampai tanggal <b><?= date('d-m-Y', strtotime($tgl_selesai)); ?></b></p>
    <?php } ?>
    <table style="width: 100%;" border="1" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th rowspan="2" style="width: 10px;">No</th>
          <th rowspan="2" style="width: 180px;">Nama</th>
          <th rowspan="2" style="width: 90px;">NIM</th>
          <?php $colspan = $kegiatan->getNumRows(); ?>
          <th colspan="<?= $colspan; ?>">Kegiatan</th>
        </tr>
        <tr>
          <?php foreach ($kegiatan->getResultArray() as $k) : ?>
            <th>
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
          <tr>
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
              <td>
                <?php if (!$cekAbsen) : ?>
                  Belum diabsen
                <?php else : ?>
                  <?php if ($cekAbsen['status_absen'] == 1) : ?>
                    <nav>Masuk</nav>
                  <?php elseif ($cekAbsen['status_absen'] == 2) : ?>
                    <nav>Izin</nav>
                  <?php else : ?>
                    <nav>Alpha</nav>
                  <?php endif; ?>
                <?php endif; ?>
              </td>
            <?php endforeach; ?>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php } else { ?>
    <?php if ($tgl_mulai !== null && $tgl_selesai !== null) { ?>
      <p style="text-align: center;">Tidak ada kegiatan dari tanggal <b><?= date('d-m-Y', strtotime($tgl_mulai)); ?></b> sampai <b><?= date('d-m-Y', strtotime($tgl_selesai)); ?></b></p>
    <?php } else { ?>
      <p style="text-align: center;">Tidak ada kegiatan yang tercatat pada sistem</p>
    <?php } ?>
  <?php } ?>
</body>

</html>