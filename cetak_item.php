<?php
error_reporting(0);
session_start();
include 'config/koneksi.php';
include 'config/rupiah.php';
include '../../config/library.php';
include "config/fungsi_indotgl.php";
include 'lib/function.php';
ob_start();

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));
$tahun = date('Y');
$filterLokasi = isset($_GET['filterLokasiBarang']) ? $_GET['filterLokasiBarang'] : '';

$lokasi = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM inv_lok_barang where idLokBarang='$filterLokasi' "));
$nmLokasi = $lokasi['lokasiBarang'];
// Handle the selected location filter

?>

<!DOCTYPE html>
<html>

<head>
    <title>Cetak Data Barang</title>
    <link rel="stylesheet" href="bootstrap/css/printer.css">
    <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
</head>

<body>
    <table width="100%">
        <tr>
            <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
            <td valign="top">
                <h3 align="center" style="margin-bottom:8px ">
                    DATA ITEM BARANG <br>
                    <?php echo $idt['nmSekolah']; ?>
                </h3>
            </td>
        </tr>
    </table>

    <!-- Filter Lokasi Barang -->
    <?php if ($nmLokasi != ''): ?>
        <h4>Lokasi Barang: <?php echo $nmLokasi; ?></h4>
    <?php else: ?>
        <h4>Semua Lokasi Barang</h4>
    <?php endif; ?>

    <table id="example1" class="table table-bordered table-striped" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kode INV</th>
                <th>Kategori Barang</th>
                <th>Sumber Dana</th>
                <th>Kondisi</th>
                <th>Lokasi Barang</th>
                <th>Foto Barang</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Modifikasi query berdasarkan filter lokasi barang
            $query = "SELECT * FROM inv_data_item 
                INNER JOIN inv_data_barang ON inv_data_item.namaBarang = inv_data_barang.idBarang
                INNER JOIN inv_sumber_dana ON inv_data_barang.sumberDana = inv_sumber_dana.idSumberDana
                INNER JOIN inv_lok_barang ON inv_data_item.lokasiBarang = inv_lok_barang.idLokBarang";

            if ($filterLokasi != '') {
                $query .= " WHERE inv_data_item.lokasiBarang = '$filterLokasi'";
            }

            $query .= " ORDER BY inv_data_item.idItem ASC";

            $tampil = mysqli_query($conn, $query);
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr>
              <td>$no</td>
              <td>$r[namaBarang]</td>
              <td>$r[kodeINV]</td>
              <td>$r[kategoriBarang]</td>
              <td>$r[nmSumberDana]</td>
              <td>$r[kondisi]</td>
              <td>$r[lokasiBarang]</td>
              <td>
                  <a href='$r[fotoBarang]' target='_blank'>
                      <img src='$r[fotoBarang]' alt='Foto Barang' style='width: 50px; height: auto;'>
                  </a>
              </td>
          </tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
    <br>
    <table width="100%">
        <tr>
            <!-- Left side -->
            <td align="center" width="50%">
                Mengetahui,<br />
                Kepala Sekolah <br /><br /><br /><br /><br /><br />

                <b><?php echo $idt['nmKepsek']; ?></b>
            </td>

            <!-- Right side -->
            <td align="center" width="50%">
                <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?><br />
                Petugas,
                <br /><br /><br /><br /><br /><br />
                <b><?php echo $_SESSION['namalengkap']; ?></b>
            </td>
        </tr>
    </table>
</body>

<script>
    window.print()
</script>

</html>