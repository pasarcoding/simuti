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
$filterJenisBarang = isset($_GET['filterJenisBarang']) ? $_GET['filterJenisBarang'] : '';

// Ambil data jenis barang jika ada filter
$jenisBarang = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM inv_jenis_barang WHERE idJenisBarang='$filterJenisBarang'"));
$nmJenisBarang = $jenisBarang['nmJenisBarang'];

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
                    DATA BARANG INVENTARIS <br>
                    <?php echo $idt['nmSekolah']; ?>
                </h3>
            </td>
        </tr>
    </table>

    <!-- Filter Jenis Barang -->
    <?php if ($nmJenisBarang != ''): ?>
        <h4>Jenis Barang: <?php echo $nmJenisBarang; ?></h4>
    <?php else: ?>
        <h4>Semua Jenis Barang</h4>
    <?php endif; ?>

    <table id="example1" class="table table-bordered table-striped" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori Barang</th>
                <th>Jenis Barang</th>
                <th>Jumlah Barang</th>
                <th>Satuan</th>
                <th>Status Barang</th>
                <th>Harga Satuan</th>
                <th>Harga Total</th>
                <th>Sumber Dana</th>
                <th>Penyedia</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Modifikasi query berdasarkan filter jenis barang
            $query = "SELECT * FROM inv_data_barang 
                      INNER JOIN inv_jenis_barang ON inv_data_barang.jenisBarang = inv_jenis_barang.idJenisBarang
                      INNER JOIN inv_sumber_dana ON inv_data_barang.sumberDana = inv_sumber_dana.idSumberDana";

            if ($filterJenisBarang) {
                $query .= " WHERE inv_data_barang.jenisBarang = '$filterJenisBarang'";
            }

            $query .= " ORDER BY inv_data_barang.idBarang ASC";

            // Menjalankan query
            $tampil = mysqli_query($conn, $query);
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr>
                    <td>$no</td>
                    <td>$r[namaBarang]</td>
                    <td>$r[kategoriBarang]</td>
                    <td>$r[nmJenisBarang]</td>
                    <td>$r[jumlahBarang]</td>
                    <td>$r[satuan]</td>
                    <td>$r[statusBarang]</td>
                    <td>" . buatRp($r['hargaSatuan']) . "</td>
                    <td>" . buatRp($r['hargaTotal']) . "</td>
                    <td>$r[nmSumberDana]</td>
                    <td>$r[penyedia]</td>
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