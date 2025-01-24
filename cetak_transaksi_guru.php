<?php
error_reporting(0);
session_start();
include 'config/koneksi.php';
include 'config/rupiah.php';
include 'config/library.php';
include 'config/fungsi_indotgl.php';
include 'lib/function.php';
ob_start();
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));
$dBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM jenis_bayar WHERE idJenisBayar='$_GET[jenisBayar]'"));

if (isset($_GET['pdf'])) {
?>
  <!DOCTYPE html>
  <html>

  <head>
    <title>Cetak Rekap Data Transaksi Guru</title>
    <link rel="stylesheet" href="bootstrap/css/printer.css">
    <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
  </head>

  <body>
    <table width="100%">
      <tr>
        <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
        <td valign="top">
          <h3 align="center" style="margin-bottom:8px ">
            <?php echo $idt['nmSekolah']; ?>
            <center>Laporan Rekap Data Transaksi Guru <br>
              <?php echo $idt['alamat']; ?></center>
          </h3>
        </td>
        <td width="100px" align="right"><img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="60px"></td>
      </tr>
    </table>
    <hr>
    <center>
      <h3>RIWAYAT BAYAR DAPEN GURU</h3>
    </center>
    <table border="1" class="table table-bordered table-striped">
      <tr>
        <th bgcolor="silver" width="50">No</th>
        <th bgcolor="silver">Nama Guru</th>
        <th bgcolor="silver">Debit</th>
        <th bgcolor="silver">Kredit</th>
        <th bgcolor="silver">Tanggal</th>
        <th bgcolor="silver">Keterangan</th>
        <th bgcolor="silver">Saldo</th>
      </tr>
      <?php
      $no = 0;
      $saldo = [];
      if (isset($_GET['guru']) && $_GET['guru'] == 'all') {
        $query = mysqli_query($conn, "SELECT * FROM transaksi_guru INNER JOIN rb_guru ON transaksi_guru.id_guru = rb_guru.id ORDER BY id_transaksi ASC");
      } else {
        $guru = mysqli_real_escape_string($conn, $_GET['guru']);
        $query = mysqli_query($conn, "SELECT * FROM transaksi_guru INNER JOIN rb_guru ON transaksi_guru.id_guru = rb_guru.id WHERE rb_guru.id = '$guru' ORDER BY id_transaksi ASC");
      }

      while ($row = mysqli_fetch_array($query)) {
        $no++;
        if (!isset($saldo[$row['id_guru']])) {
          $saldo[$row['id_guru']] = 0;
        }
        $saldo[$row['id_guru']] += $row['debit'];
        $saldo[$row['id_guru']] -= $row['kredit'];
      ?>
        <tr>
          <td><?php echo $no; ?></td>
          <td><?php echo $row['nama_guru']; ?></td>
          <td>Rp. <?php echo buatRp($row['debit']); ?></td>
          <td>Rp. <?php echo buatRp($row['kredit']); ?></td>
          <td><?php echo $row['tanggal']; ?></td>
          <td><?php echo $row['keterangan']; ?></td>
          <td>Rp. <?php echo buatRp($saldo[$row['id_guru']]); ?></td>
        </tr>
      <?php } ?>
      <?php
      // Hitung total saldo dari array $saldo
      $total_saldo = array_sum($saldo);
      ?>

      <tr>
        <td colspan="6"> Total Saldo: </td>
        <td>
          <?php echo buatRp($total_saldo); ?>
        </td>
      </tr>
    </table>

    <br>
    <table width="100%">
      <tr>
        <td align="center"></td>
        <td align="center" width="400px">
          <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
          <br />Bendahara,<br /><br /><br /><br />
          <b><u><?php echo $idt['nmBendahara']; ?></u><br /><?php echo $idt['nipBendahara']; ?></b>
        </td>
      </tr>
    </table>
  </body>
  <script>
    window.print()
  </script>

  </html>
<?php
} else {
  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=laporan_transaksi_guru_" . date('dmyHis') . ".xls");
?><center>
    <h3>RIWAYAT BAYAR DAPEN GURU</h3>
  </center>
  <table border="1" class="table table-bordered table-striped">
    <tr>
      <th bgcolor="silver" width="50">No</th>
      <th bgcolor="silver">Nama Guru</th>
      <th bgcolor="silver">Debit</th>
      <th bgcolor="silver">Kredit</th>
      <th bgcolor="silver">Tanggal</th>
      <th bgcolor="silver">Keterangan</th>
      <th bgcolor="silver">Saldo</th>
    </tr>
    <?php
    $no = 0;
    $saldo = [];
    if (isset($_GET['guru']) && $_GET['guru'] == 'all') {
      $query = mysqli_query($conn, "SELECT * FROM transaksi_guru INNER JOIN rb_guru ON transaksi_guru.id_guru = rb_guru.id ORDER BY id_transaksi ASC");
    } else {
      $guru = mysqli_real_escape_string($conn, $_GET['guru']);
      $query = mysqli_query($conn, "SELECT * FROM transaksi_guru INNER JOIN rb_guru ON transaksi_guru.id_guru = rb_guru.id WHERE rb_guru.id = '$guru' ORDER BY id_transaksi ASC");
    }

    while ($row = mysqli_fetch_array($query)) {
      $no++;
      if (!isset($saldo[$row['id_guru']])) {
        $saldo[$row['id_guru']] = 0;
      }
      $saldo[$row['id_guru']] += $row['debit'];
      $saldo[$row['id_guru']] -= $row['kredit'];
    ?>
      <tr>
        <td><?php echo $no; ?></td>
        <td><?php echo $row['nama_guru']; ?></td>
        <td>Rp. <?php echo buatRp($row['debit']); ?></td>
        <td>Rp. <?php echo buatRp($row['kredit']); ?></td>
        <td><?php echo $row['tanggal']; ?></td>
        <td><?php echo $row['keterangan']; ?></td>
        <td>Rp. <?php echo buatRp($saldo[$row['id_guru']]); ?></td>
      </tr>
    <?php } ?>
    <?php
    // Hitung total saldo dari array $saldo
    $total_saldo = array_sum($saldo);
    ?>
    <tr>
      <td colspan="6"> Total Saldo: </td>
      <td>
        <?php echo buatRp($total_saldo); ?>
      </td>
    </tr>
  </table>
  <br>
  <table width="100%">
    <tr>
      <td align="center"></td>
      <td align="center" width="400px">
        <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
        <br />Bendahara,<br /><br /><br /><br />
        <b><u><?php echo $idt['nmBendahara']; ?></u><br /><?php echo $idt['nipBendahara']; ?></b>
      </td>
    </tr>
  </table>
<?php } ?>