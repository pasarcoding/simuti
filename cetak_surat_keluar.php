<?php
error_reporting(0);
session_start();
include 'config/koneksi.php';
include 'config/rupiah.php';
include 'config/library.php';
include 'config/fungsi_indotgl.php';
include 'lib/function.php';
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));

// Get the year and month from the URL (default to current year/month if not set)
$year = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$month = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$bulanNama = isset($_GET['bulan']) ? strtoupper(getBulanReport($_GET['bulan'])) : 'SEMUA BULAN';

// Start the HTML report generation
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cetak Laporan Surat Keluar</title>
    <link rel="stylesheet" href="bootstrap/css/printer.css">
    <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
</head>

<body>
    <table width="100%">
        <tr>
            <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
            <td valign="top">
                <h3 align="center" style="margin-bottom:8px ">
                    <center>DATA SURAT KELUAR <br>
                       BULAN <?= $bulanNama ?> <?= $year ?>  <br>                 
                       <?php echo $idt['nmSekolah']; ?>
</center>
                </h3>
            </td>
            <td width="100px" align="right"><img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="60px"></td>
        </tr>
    </table>
    <hr>
    
  
		<table border="1" class="table table-bordered table-striped">
        <tr>
            <th bgcolor="silver" width="50">No</th>
            <th bgcolor="silver">Nomor Surat</th>
            <th bgcolor="silver">Tanggal Surat</th>
            <th bgcolor="silver">Asal Surat</th>
            <th bgcolor="silver">Sifat Surat</th>
            <th bgcolor="silver">Perihal</th>
            <th bgcolor="silver">Status</th>
        </tr>

        <?php
        $no = 0;

        // Modify the query to filter by year and month
        $query = mysqli_query($conn, "
            SELECT * FROM surat_keluar 
            WHERE YEAR(tgl) = '$year' AND MONTH(tgl) = '$month'
            ORDER BY tgl DESC
        ");

        while ($row = mysqli_fetch_array($query)) {
            $no++;
            ?>
            <tr>
                <td><?php echo $no; ?></td>
                <td><?php echo $row['nomor_surat']; ?></td>
                <td><?php echo tgl_indo($row['tgl']); ?></td>
                <td><?php echo $row['asal']; ?></td>
                <td><?php echo $row['sifat']; ?></td>
                <td><?php echo $row['jenis']; ?></td>
                <td><?php echo ($row['status'] == 'Belum Diterima') ? 'Belum Diterima' : 'Diterima'; ?></td>
            </tr>
        <?php } ?>
    </table>

    <br>
   <table width="100%">
    <tr>
        <!-- Left side -->
        <td align="center" width="50%">
            Mengetahui,<br />
            Kepala Sekolah  <br /><br /><br /><br /><br /><br />
            
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
