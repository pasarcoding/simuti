<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
if (isset($_SESSION[id])) {
	if ($_SESSION['level'] == 'admin') {
		$iden = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users where username='$_SESSION[id]'"));
		$nama =  $iden['nama_lengkap'];
		$level = 'Administrator';
		$foto = 'dist/img/user.png';
	}

	$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));
	$ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where idTahunAjaran='$_GET[tahun]'"));
	$kls = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM kelas_siswa where idKelas='$_GET[kelas]'"));
	$dBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM jenis_bayar WHERE idJenisBayar='$_GET[jenisBayar]'"));
?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>Cetak - Laporan Pembayaran Siswa</title>
		<link rel="stylesheet" href="bootstrap/css/printer.css">
	</head>

	<body>
		<?php
		$tahun = $_GET['tahun'];
		$jenis = $_GET['jenisBayar'];
		$kelas = $_GET['kelas'];

		//tagihan bebas
		$sqlTagihanBebas = mysqli_query($conn, "SELECT
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
								WHERE tagihan_bebas.idJenisBayar='$jenis'  AND jenis_bayar.idTahunAjaran='$tahun' ORDER BY tagihan_bebas.idTagihanBebas ASC");
		?>
		<!-- Box Data -->
		<div class="col-xs-12">
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
			<h3 align="center">LAPORAN PIUTANG <?php echo strtoupper($dBayar['nmJenisBayar']); ?></h3>
			<div class="box box-info box-solid">

				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No.</th>
								<th>NIS</th>
								<th>Nama Siswa</th>
								<th>Tunggakan</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							while ($rtb = mysqli_fetch_array($sqlTagihanBebas)) {
								$qBayar = mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalDibayar FROM tagihan_bebas_bayar 
                        WHERE idTagihanBebas='$rtb[idTagihanBebas]'");
								$dtBayar = mysqli_fetch_array($qBayar);

								// Hitung selisih antara totalTagihan dan totalDibayar
								$selisih = $rtb['totalTagihan'] - $dtBayar['totalDibayar'];

								// Hanya tampilkan baris jika selisih tidak sama dengan 0
								if ($selisih != 0) {
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
										<td>" . buatRp($selisih) . "</td>
									</tr>";
									$totalTunggakan += $selisih;
									$no++;
								}
							}
							?>
							<tr>
								<td colspan="3" align="center"><b>Total Tunggakan:</b> </td>
								<td><b><?= buatRp($totalTunggakan) ?></b></td>
							</tr>
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
		<br />
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
	include "login.php";
}
?>