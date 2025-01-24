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
    <title>Cetak Pengadaan</title>
    <link rel="stylesheet" href="bootstrap/css/printer.css">
    <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
</head>

<body>
    <table width="100%">
        <tr>
            <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
            <td valign="top">
                <h3 align="center" style="margin-bottom:8px ">
                    DATA PENGADAAN BARANG <br>
                    <?php echo $idt['nmSekolah']; ?>
                </h3>
            </td>
        </tr>
    </table>

    <!-- Filter Bulan dan Tahun -->
    <?php if ($filterBulan != '' && $filterTahun != ''): ?>
        <h4>Pengadaan Barang untuk Bulan: <?php echo $bulanName . " " . $filterTahun; ?></h4>
    <?php else: ?>
        <h4>Semua Pengadaan Barang</h4>
    <?php endif; ?>

    <table id="example1" class="table table-bordered table-striped" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Pengadaan</th>
                <th>Pengaju</th>
                <th>Nama Barang</th>
                <th>Jumlah Barang</th>
                <th>Harga Satuan</th>
                <th>Harga Total</th>
                <th>Sumber Dana</th>
                <th>Penyedia</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $filterBulan = isset($_GET['filterBulan']) ? $_GET['filterBulan'] : '';
            $filterTahun = isset($_GET['filterTahun']) ? $_GET['filterTahun'] : '';



            // Membuat query berdasarkan filter jenis barang
            $query = "SELECT * FROM inv_pengadaan 
                                      INNER JOIN inv_data_barang ON inv_pengadaan.namaBarang = inv_data_barang.idBarang
                                      INNER JOIN inv_sumber_dana ON inv_data_barang.sumberDana = inv_sumber_dana.idSumberDana WHERE 1";
            if ($filterBulan) {
                $query .= " AND MONTH(tanggalPengadaan) = '$filterBulan'";
            }

            if ($filterTahun) {
                $query .= " AND YEAR(tanggalPengadaan) = '$filterTahun'";
            }

            $query .= " ORDER BY inv_pengadaan.idPengadaan ASC";

            // Menjalankan query
            $tampil = mysqli_query($conn, $query);
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr>
                                      <td>$no</td>
                                      <td>" . tgl_miring($r['tanggalPengadaan']) . "</td>
                                    <td>$r[namaPengaju]</td>
                                      <td>$r[namaBarang]</td>
                                      <td>$r[jumlahBarang]</td>
                                      <td>" . buatRp($r['hargaSatuan']) . "</td>
                                      <td>" . buatRp($r['hargaTotal']) . "</td>
                                      <td>$r[nmSumberDana]</td>
                                      <td>$r[penyedia]</td>
                                     
                                  </tr>";
                $no++;
            }

            if (isset($_GET['hapus'])) {
                mysqli_query($conn, "DELETE FROM inv_pengadaan WHERE idPengadaan='$_GET[id]'");
                echo "<script>document.location='index.php?view=inv_pengadaan';</script>";
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