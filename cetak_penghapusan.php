<?php
error_reporting(0);
session_start();
include 'config/koneksi.php';
include 'config/rupiah.php';
include 'config/library.php';
include "config/fungsi_indotgl.php";
include 'lib/function.php';
ob_start();

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));
$tahun = date('Y');

// Filter Bulan dan Tahun
$filterBulan = isset($_GET['filterBulan']) ? $_GET['filterBulan'] : '';
$filterTahun = isset($_GET['filterTahun']) ? $_GET['filterTahun'] : '';

// Ambil data bulan dan tahun yang diterapkan untuk filter
$bulanName = $filterBulan ? date("F", mktime(0, 0, 0, $filterBulan, 10)) : '';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cetak Penghapusan</title>
    <link rel="stylesheet" href="bootstrap/css/printer.css">
    <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
</head>

<body>
    <table width="100%">
        <tr>
            <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
            <td valign="top">
                <h3 align="center" style="margin-bottom:8px ">
                    DATA PENGHAPUSAN BARANG <br>
                    <?php echo $idt['nmSekolah']; ?>
                </h3>
            </td>
        </tr>
    </table>

    <!-- Filter Bulan dan Tahun -->
    <?php if ($filterBulan != '' && $filterTahun != ''): ?>
        <h4>Penghapusan Barang untuk Bulan: <?php echo $bulanName . " " . $filterTahun; ?></h4>
    <?php else: ?>
        <h4>Semua Penghapusan Barang</h4>
    <?php endif; ?>

    <table id="example1" class="table table-bordered table-striped" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode INV</th>
                <th>Barang</th>
                <th>Tanggal Penghapusan</th>
                <th>Keterangan</th>
                <th>Bukti Fisik</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Membuat query berdasarkan filter bulan dan tahun
            $query = "SELECT * , inv_data_barang.namaBarang  as NamaBarang FROM inv_penghapusan 
                                      INNER JOIN inv_data_barang ON inv_penghapusan.idItem = inv_data_barang.idBarang
                                      INNER JOIN inv_data_item ON inv_data_barang.idBarang = inv_data_item.namaBarang
                                      WHERE 1";

            if ($filterBulan) {
                $query .= " AND MONTH(tanggalPenghapusan) = '$filterBulan'";
            }

            if ($filterTahun) {
                $query .= " AND YEAR(tanggalPenghapusan) = '$filterTahun'";
            }

            $query .= " ORDER BY inv_penghapusan.idPenghapusan ASC";

            // Menjalankan query
            $tampil = mysqli_query($conn, $query);
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr>
                                      <td>$no</td>
                                       <td>$r[kodeINV]</td>
                                      <td>$r[NamaBarang]</td>
                                       <td>" . tgl_miring($r['tanggalPenghapusan']) . "</td>
                                      <td>$r[keterangan]</td>
                                       <td>
                  <a href='$r[buktiFisik]' target='_blank'>
                      <img src='$r[buktiFisik]' alt='Foto Barang' style='width: 50px; height: auto;'>
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