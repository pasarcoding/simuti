<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
if (isset($_SESSION[id])) {
	if ($_SESSION['level'] == 'admin') {
		$iden = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users where username='$_SESSION[id]'"));
		$nama =  $iden['nama_lengkap'];
		$level = 'Administrator';
		$foto = 'dist/img/user.png';
	}

	$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas where npsn='10700295'"));
	$ta = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tahun_ajaran where idTahunAjaran='$_GET[tahun]'"));
	$kls = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM kelas_siswa where idKelas='$_GET[kelas]'"));
	$dBayar = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM jenis_bayar WHERE idJenisBayar='$_GET[jenisBayar]'"));

	$tahun = $_GET['tahun'];
	$jenis = $_GET['jenisBayar'];
	$kelas = $_GET['kelas'];

	//tagihan bebas
	$sqlTagihanBebas = mysqli_query($conn,"SELECT
			tagihan_bebas.*,
			jenis_bayar.idPosBayar,
			jenis_bayar.idTahunAjaran,
			jenis_bayar.nmJenisBayar,
			jenis_bayar.tipeBayar,
			siswa.nisSiswa,
			siswa.nisnSiswa,
			siswa.nmSiswa,
			siswa.jkSiswa,
			siswa.agamaSiswa,
			siswa.idKelas,
			siswa.statusSiswa,
			tahun_ajaran.nmTahunAjaran,
			kelas_siswa.nmKelas
		FROM
			tagihan_bebas
		INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
		INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
		INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
		INNER JOIN kelas_siswa ON tagihan_bebas.idKelas = kelas_siswa.idKelas
		INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
		WHERE tagihan_bebas.idJenisBayar='$jenis' AND siswa.idKelas='$kelas' AND jenis_bayar.idTahunAjaran='$tahun' ORDER BY tagihan_bebas.idTagihanBebas ASC");

	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=laporan_kelas_" . str_replace(" ", "_", $kls['nmKelas']) . "_" . date('dmyHis') . ".xls");
?>
	<table border="1">
		<thead>
			<tr>
				<th>No.</th>
				<th>NIS</th>
				<th>Nama Siswa</th>
				<th>Total Tagihan</th>
				<th>Total Bayar</th>
				<th>Tunggakan</th>
				<th>Status</th>
				
			</tr>
		</thead>
		<?php
		$no = 1;
		while ($rtb = mysqli_fetch_array($sqlTagihanBebas)) {
			$qBayar = mysqli_query($conn,"select sum(jumlahBayar) as totalDibayar from tagihan_bebas_bayar 
								where idTagihanBebas='$rtb[idTagihanBebas]'");
			$dtBayar = mysqli_fetch_array($qBayar);
			if ($rtb['statusBayar'] == '0') {
				$status = "Belum Bayar";
			} elseif ($rtb['statusBayar'] == '1') {
				$status = "Lunas";
			} elseif ($rtb['statusBayar'] == '2') {
				$status = "Belum Lunas";
			}
			echo "<tr>
				<td>$no</td>
				<td>$rtb[nisSiswa]</td>
				<td>$rtb[nmSiswa]</td>
				<td>" . buatRp($rtb['totalTagihan']) . "</td>
				<td>" . buatRp($dtBayar['totalDibayar']) . "</td>
				<td>" . buatRp($rtb['totalTagihan'] - $dtBayar['totalDibayar']) . "</td>
				<td>$status</td>
			</tr>";
			$no++;
		}
		?>
	</table>
<?php
} else {
	include "login.php";
}
?>