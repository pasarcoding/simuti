<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/fungsi_indotgl.php";

// Function to sanitize output for file names
function sanitizeFileName($filename) {
    return preg_replace('/[^A-Za-z0-9\-]/', '', $filename); // Remove special characters
}
$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas "));

// Initialize variables for total counts
$totalLakiLaki = 0;
$totalPerempuan = 0;


    // Query to get student data and count genders
    $sql = "SELECT  nmKelas,
            SUM(CASE WHEN jkSiswa = 'L' THEN 1 ELSE 0 END) as jumlahLakiLaki,
            SUM(CASE WHEN jkSiswa = 'P' THEN 1 ELSE 0 END) as jumlahPerempuan,
            COUNT(*) as totalSiswa
            FROM view_detil_siswa
            WHERE statusSiswa = 'Aktif'
            GROUP BY idKelas
            ORDER BY nmKelas ASC";


// Execute query
$result = mysqli_query($conn, $sql);

// Excel headers

?>

<head>
	<title>Cetak - REKAP JUMLAH SISWA AKTIF </title>
	<link rel="stylesheet" href="bootstrap/css/printer.css">
</head>

<table width="100%">
		<tr>
			<td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
			<td valign="top">
				<h3 align="center" style="margin-bottom:8px ">
					<?php echo $idt['nmSekolah']; ?>
				</h3>
				<center><?php echo $idt['alamat']; ?></center>
			</td>
			<td width="100px" align="right"><img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="60px"></td>
		</tr>
	</table>
		<hr>
<h3  align="center"> REKAP JUMLAH SISWA AKTIF
</h3>
<h3  align="center"> SD MUHAMMADIYAH 3 BANDUNG</h3>

	<table border="1" class="table table-bordered table-striped" >
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Kelas</th>
            <th>Jumlah Laki-Laki</th>
            <th>Jumlah Perempuan</th>
            <th>Total Siswa</th>
        </tr>
    </thead>
   <tbody>
    <?php
    $no = 1;
    while ($row = mysqli_fetch_assoc($result)) {
        // Accumulate total counts
        $totalLakiLaki += $row['jumlahLakiLaki'];
        $totalPerempuan += $row['jumlahPerempuan'];

        // Output row for each class
        echo "<tr>
                <td style='text-align: center;'>$no</td>
                <td style='text-align: center;'>{$row['nmKelas']}</td>
                <td style='text-align: center;'>{$row['jumlahLakiLaki']}</td>
                <td style='text-align: center;'>{$row['jumlahPerempuan']}</td>
                <td style='text-align: center;'>{$row['totalSiswa']}</td>
              </tr>";
        $no++;
    }
    ?>
</tbody>

    <tfoot>
        <tr>
            <td style='text-align: center;' colspan="2"><b>Total</b></td>
            <td style='text-align: center;'><b><?php echo $totalLakiLaki; ?></b></td>
            <td style='text-align: center;'><b><?php echo $totalPerempuan; ?></b></td>
            <td style='text-align: center;'><b><?php echo ($totalLakiLaki + $totalPerempuan); ?></b></td>
        </tr>
    </tfoot>
</table>
<br />
<div class="row">
    
    <div class="column">
        <!-- Bagian User yang Login -->
        <table width="100%">
            <tr>
                <td align="center" width="300px">
                    <!-- Ganti ini dengan data sesuai dengan konteks -->
                    Mengetahui,
                    <br />Kepala Sekolah,<br /><br /><br /><br /><br />
                    <b><u> <?= $idt['nmKepsek']?></u></b>
                </td>
           
                <td align="center" width="300px">
                    <?= $idt['kabupaten']?>, <?php echo date("d F Y"); ?>
                    <br />Petugas,<br /><br /><br /><br /><br />
                    <b><u><?= $_SESSION['namalengkap']?></u></b>
                </td>
            </tr>
        </table>
    </div>
</div>

<script>
	window.print()
</script>

</html>