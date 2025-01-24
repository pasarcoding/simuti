<?php
error_reporting(0);
session_start();
include 'config/koneksi.php';
include 'config/rupiah.php';
include '../../config/library.php';
include "config/fungsi_indotgl.php";
include 'lib/function.php';
ob_start();

// Fetch school identity (this part might remain the same, for branding and school information)
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));

// Optionally fetch the class filter if needed (for a guestbook based on the class or group, if any)
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';
?>

<!DOCTYPE html>
<html>

<head>
  <title>Cetak Buku Tamu</title>
  <link rel="stylesheet" href="bootstrap/css/printer.css">
  <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
</head>

<body>
  <table width="100%">
    <tr>
      <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
      <td valign="top">
        <h3 align="center" style="margin-bottom:8px">
          BUKU TAMU<br>
          <?php echo $idt['nmSekolah']; ?>
        </h3>
      </td>
    </tr>
  </table>


  <table border="1" class="table table-bordered table-striped">
    <thead>
    <tr>
      <th>No</th>
      <th>Nama</th>
      <th>No Telp</th>
       <th>Alamat</th>
      <th>Instansi</th>
      <th>Jenis Kelamin</th>
      <th>Bertemu </th>
       <th>Tanggal</th>
      <th>Jam Masuk</th> <!-- Menambahkan kolom Jam Masuk -->
      <th>Keperluan</th>
      <th>Foto</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Memeriksa apakah bulan dan tahun telah dipilih
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

    // Menyusun query berdasarkan filter bulan dan tahun
    $where = "";
    if ($bulan && $tahun) {
      // Menambahkan filter untuk bulan dan tahun
      $where = "WHERE MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun'";
    } elseif ($bulan) {
      // Jika hanya bulan yang dipilih
      $where = "WHERE MONTH(tanggal) = '$bulan'";
    } elseif ($tahun) {
      // Jika hanya tahun yang dipilih
      $where = "WHERE YEAR(tanggal) = '$tahun'";
    }

    // Query untuk mengambil data buku tamu dengan filter
    $tampil = mysqli_query($conn, "SELECT *, DATE_FORMAT(tanggal, '%H:%i') AS jam_masuk FROM tamu $where ORDER BY id ASC");
    $no = 1;
    while ($r = mysqli_fetch_array($tampil)) {
      // Menampilkan data buku tamu beserta foto dan jam masuk
      echo "<tr>
              <td>$no</td>
              <td>$r[nama]</td>
              <td>$r[no_telp]</td>
              <td>$r[alamat]</td>
              <td>$r[instansi]</td>
              <td>$r[jenis_kelamin]</td>
              <td>$r[bertemu]</td>
              <td>". tgl_indo($r[tanggal])."</td>
              <td>$r[jam_masuk]</td>
              <td>$r[keperluan]</td>";

      // Menampilkan foto, jika ada foto yang disimpan di kolom 'foto'
      echo "<td><img src='bukutamu/$r[foto]' alt='Foto' width='100' height='75'></td>";

      // Menampilkan jam masuk

      // Aksi edit dan delete
      echo "</tr>";
      $no++;
    }

    // Proses penghapusan data
    if (isset($_GET['hapus'])) {
      mysqli_query($conn, "DELETE FROM buku_tamu WHERE idBukuTamu='$_GET[id]'");
      echo "<script>document.location='index.php?view=bukutamu';</script>";
    }
    ?>
  </tbody>
  </table>
  
   <table width='100%' style='font-size:12px; margin-top: 10px; float:right;'>
    <tr>
      <td align="left">
        <br />Kepala Sekolah,<br /><br /><br /><br />
        <b><u><?php echo $idt['nmKepsek']; ?></u></b><br>
        <?php echo $idt['nipKepsek']; ?>
      </td>
        <td align="right">
        <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date('Y-m-d')); ?>
        <br />Petugas,<br /><br /><br /><br />
       <b> <u><?php echo $_SESSION['namalengkap']; ?></u><br>
      <!--  <b><u><?php echo $idt['nmBendahara']; ?></u></b><br>
        <p><?php echo $idt['nipBendahara']; ?></p> -->
      </td>
    </tr>

  </table>

</body>

<script>
  window.print()
</script>

</html>
