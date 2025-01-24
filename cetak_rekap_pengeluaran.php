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

	$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas"));
	$dBayar = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM jenis_bayar WHERE idJenisBayar='$_GET[jenisBayar]'"));
?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>Cetak - Rekapitulasi Pembayaran Siswa</title>
		<link rel="stylesheet" href="bootstrap/css/printer.css">
	</head>

	<body>
		<?php
		$tgl1 = $_GET['tgl1'];
		$tgl2 = $_GET['tgl2'];
		?>
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
			<h3 align="center">
				REKAPITULASI PENGELUARAN
			</h3>
			<hr />
			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">Tanggal : <?php echo tgl_raport($tgl1); ?> s/d <?php echo tgl_raport($tgl2); ?></h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th width="40px">No.</th>
								<th>Tanggal</th>
								<th>Uraian (Keterangan)</th>
								<th>Pengeluaran</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							$total = 0;
							$sqlJU = mysqli_query($conn,"SELECT * FROM jurnal_umum WHERE pengeluaran!=0 AND DATE(tgl) BETWEEN '$tgl1' AND '$tgl2' ORDER BY tgl ASC");
							while ($d = mysqli_fetch_array($sqlJU)) {
								echo "<tr>
							<td align='center'>$no</td>
							<td align='center'>" . tgl_raport($d['tgl']) . "</td>
							<td>$d[ket]</td>
							<td align='right'>" . buatRp($d['pengeluaran']) . "</td>
						</tr>";
								$no++;
								$total += $d['pengeluaran'];
							}
							?>
							<tr>
								<td colspan="3" align="center">Total Pengeluaran</td>
								<td align="right"><?php echo buatRp($total); ?></td>
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