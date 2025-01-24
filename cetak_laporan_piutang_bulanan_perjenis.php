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
		<title>Cetak - Laporan Piutang Siswa</title>
		<link rel="stylesheet" href="bootstrap/css/printer.css">
	</head>

	<body>
		<?php
		$tahun = $_GET['tahun'];
		$jenis = $_GET['jenisBayar'];
		$kelas = $_GET['kelas'];

		$sqlLap = mysqli_query($conn, "SELECT * FROM siswa 
						WHERE idSiswa 
					IN (SELECT idSiswa FROM tagihan_bulanan WHERE idJenisBayar='$jenis') 
					 ORDER BY nmSiswa ASC");
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
								<th>Nama Siswa</th>
								<th>Juli</th>
								<th>Agustus</th>
								<th>September</th>
								<th>Oktober</th>
								<th>November</th>
								<th>Desember</th>
								<th>Januari</th>
								<th>Februari</th>
								<th>Maret</th>
								<th>April</th>
								<th>Mei</th>
								<th>Juni</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							while ($rt = mysqli_fetch_array($sqlLap)) {
								echo "<tr>
								<td>$no</td>
								<td>$rt[nmSiswa]</td>";
								$sqlTB = mysqli_query($conn, "SELECT tagihan_bulanan.*, bulan.nmBulan, bulan.urutan
											FROM tagihan_bulanan
											INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
											WHERE tagihan_bulanan.idSiswa = '$rt[idSiswa]' AND tagihan_bulanan.idJenisBayar = '$jenis' ORDER BY bulan.urutan ASC");
								while ($t = mysqli_fetch_array($sqlTB)) {
									$qBayar = mysqli_query($conn, "SELECT sum(jumlahBayar) as totalDibayar from tagihan_bulanan_bayar 
												where idTagihanBebas='$t[idTagihanBulanan]'");
									$dtBayar = mysqli_fetch_array($qBayar);
									$bayar = $t['jumlahBayar'] - $dtBayar['totalDibayar'];
									if ($t['statusBayar'] == '1') {
										$date = "";
										$tgl = "";
										$jmlbayar = "";
									} elseif ($t['statusBayar'] == '2') {
										$jmlbayar = buatRp($t['jumlahBayar'] - $dtBayar['totalDibayar']);
									} else {
										$tgl = '';
										$jmlbayar = buatRp($t['jumlahBayar']);
									}
									echo "<td align='center'>$tgl<br/>$jmlbayar</td>";
								}
								echo "</tr>";
								$totalTunggakan += $bayar;

								$no++;
							}
							?>
							<tr>
								<td colspan="3" align="center"><b>Total Tunggakan:</b> </td>
								<td colspan="12" align="center"><b><?= buatRp($totalTunggakan) ?></b></td>
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