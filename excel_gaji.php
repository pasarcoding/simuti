<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
$jenis = $_GET['posBayar'];
$posBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * from pos_bayar where idPosBayar='$jenis'"));
$dBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * from jenis_bayar where idPosBayar='$jenis'"));
$bulan = $_GET['bulan'] ?? ''; // Menggunakan null coalescing operator untuk menghindari error jika parameter tidak ada
$tahun_ini = date("Y");
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * from identitas "));

// Mendefinisikan array dengan nama-nama bulan
$nama_bulan = [
	'01' => 'Januari',
	'02' => 'Februari',
	'03' => 'Maret',
	'04' => 'April',
	'05' => 'Mei',
	'06' => 'Juni',
	'07' => 'Juli',
	'08' => 'Agustus',
	'09' => 'September',
	'10' => 'Oktober',
	'11' => 'November',
	'12' => 'Desember',
];

// Mengambil nama bulan sesuai dengan nilai bulan dari parameter GET
$nama_bulan_terpilih = isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : ''; // Memeriksa apakah nilai bulan valid

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_gaji_guru_" . str_replace(" ", "_", $nama_bulan_terpilih) . ".xls");
?>
<style>
	th {
		background-color: #4CAF50;
		/* Warna latar belakang hijau */
		color: white;
		/* Warna teks putih */
	}
</style>
<table border="1">
    <h2>REKAPITULASI GAJI GURU DAN KARYAWAN</h2>
    <h3><?php echo $idt['nmSekolah']; ?></h3>
    <h3>Periode 1 s.d 30/31 <?php echo $nama_bulan_terpilih . ' ' . $_GET['tahun']; ?></h3>
    <tr>
    <td colspan="100%"></td>
</tr>
    <thead>
        <tr>
            <th width="300">Nama Guru</th>
            <th width="300">Jabatan</th>
            <th>Honor / Gaji</th>

            <?php
            // Display jenis gaji values in the thead
            $jenisGajiQuery = mysqli_query($conn, "SELECT * FROM jenis_gaji WHERE jenis='tunjangan'");
            while ($jenisGaji = mysqli_fetch_array($jenisGajiQuery)) {
                $nmGaji = $jenisGaji['nmGaji'];
                echo "<th width='150'>$nmGaji</th>";
            }
            ?>

            <th width="200" style='background-color: #f2f2f2;'>Total Penerimaan</th>

            <?php
            // Display jenis potongan values in the thead
            $jenisPotonganQuery = mysqli_query($conn, "SELECT * FROM jenis_potongan");
            while ($jenisPotongan = mysqli_fetch_array($jenisPotonganQuery)) {
                $nmPotongan = $jenisPotongan['nmPotongan'];
                echo "<th width='150'>$nmPotongan</th>";
            }
            ?>

            <th width="200" style='background-color: #f2f2f2;'>Total Potongan</th>
            <th width="200">Jumlah yang diterima bersih</th>
        </tr>
    </thead>

    <tbody>
        <?php
        $id_bulan = $_GET['bulan'];
        $idTahunAjaran = $_GET['idTahunAjaran'];

        $sqlSiswa1 = mysqli_query($conn, "SELECT * FROM rb_guru 
            INNER JOIN rb_jenis_ptk ON rb_guru.id_jenis_ptk=rb_jenis_ptk.id_jenis_ptk
            LEFT JOIN kelas_siswa ON rb_guru.idKelas=kelas_siswa.idKelas
            WHERE rb_guru.id IN (
                SELECT DISTINCT id_guru FROM bayar_gaji
                WHERE id_bulan='$id_bulan' AND idTahunAjaran='$idTahunAjaran'
            )");
        $no = 1;

        while ($r = mysqli_fetch_array($sqlSiswa1)) {
            $bayarGaji = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM bayar_gaji 
                WHERE id_bulan='$_GET[bulan]' AND idTahunAjaran='$_GET[idTahunAjaran]' AND id_guru='$r[id]'"));

            $gj = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM gaji_pokok 
                INNER JOIN jenis_gaji ON gaji_pokok.id_jenis=jenis_gaji.id
                WHERE jenis_gaji.jenis='pokok' and gaji_pokok.id_bulan='$_GET[bulan]'"));

            echo "<tr class='header1 expand'>
                    <td><span class='btn btn-danger btn-xs sign' style='margin-right:30px; '></span> <b> $r[nama_guru] </b></td>
                    <td>$r[jenis_ptk] $r[nmKelas]</td>";

            $jenisGajiQuery = mysqli_query($conn, "SELECT * FROM bayar_gaji WHERE id_guru='$r[id]' AND id_bulan='$_GET[bulan]' AND idTahunAjaran='$_GET[idTahunAjaran]'");
            while ($jenisGaji = mysqli_fetch_array($jenisGajiQuery)) {
                echo "<td>" . buatRp($jenisGaji['gaji_pokok'] * $jenisGaji['jumlah_jam']) . "</td>";

                // Fetch details from jenis_gaji for tunjangan columns
                $jenisTunjanganQuery = mysqli_query($conn, "SELECT * FROM jenis_gaji WHERE jenis='tunjangan'");
                while ($jenisTunjangan = mysqli_fetch_array($jenisTunjanganQuery)) {
                    $idJenisTunjangan = $jenisTunjangan['id'];
                    $columnName = "id_jenis_tunjangan$idJenisTunjangan";

                    echo "<td>" . buatRp($jenisGaji[$columnName]) . "</td>";
                }

                $tot = 0; // Initialize $tot to zero
                // Check if both gaji_pokok and jumlah_jam are not empty or not equal to zero
                if (!empty($bayarGaji['gaji_pokok']) && !empty($bayarGaji['jumlah_jam']) && $bayarGaji['gaji_pokok'] != 0 && $bayarGaji['jumlah_jam'] != 0) {
                    $tot = $bayarGaji['total_gaji'];
                } else {
                    // Data is not available, display a message or perform alternative actions
                    $tot = 0;
                }
                echo "<td style='background-color: #f2f2f2;'>" . buatRp($tot) . "</td>";

                // Fetch details from jenis_potongan for potongan columns
                $jenisPotonganQuery = mysqli_query($conn, "SELECT * FROM jenis_potongan");
                while ($jenisPotongan = mysqli_fetch_array($jenisPotonganQuery)) {
                    $idJenisPotongan = $jenisPotongan['id'];
                    $columnName = "id_jenis_potongan$idJenisPotongan";

                    echo "<td>" . buatRp($jenisGaji[$columnName]) . "</td>";
                }

                if (!empty($bayarGaji['gaji_pokok']) && !empty($bayarGaji['jumlah_jam']) && $bayarGaji['gaji_pokok'] != 0 && $bayarGaji['jumlah_jam'] != 0) {
                    $totPotongan = $bayarGaji['total_potongan'];
                } else {
                    // Data is not available, display a message or perform alternative actions
                    $totPotongan = 0;
                }
                echo "<td style='background-color: #f2f2f2;'>" . buatRp($totPotongan) . "</td>";
                echo "<td>" . buatRp($jenisGaji['total_gaji'] - $jenisGaji['total_potongan']) . "</td>";
            }

            echo "</tr>";
        }
        ?>
    </tbody>
</table>
