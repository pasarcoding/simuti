<?php
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";


$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas where npsn='10700295'"));
//$ta = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tahun_ajaran where idTahunAjaran='$_GET[tahun]'"));
//$kls = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM kelas_siswa where idKelas='$_GET[kelas]'"));
$dBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM jenis_bayar WHERE idJenisBayar='5'"));
$dsw = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idSiswa='$_GET[siswa]'"));
$poss = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pos_bayar "));
$taa = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where aktif='Y'"));
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));
$sqlJenisBayar = mysqli_query($conn, "SELECT * FROM jenis_bayar WHERE idTahunAjaran='$taa[idTahunAjaran]' ORDER BY tipeBayar DESC");

$tgl = $_GET['tgl'];
if ($_GET['tahun'] == '<b>Semua Tahun Ajaran<b>') {
	$tahun = 'Semua Tahun Ajaran';
} else {
	$tahun = $_GET['tahun'];
}
$tgl_jam = date("d-m-Y h:i:s a");
$no_kwt = strtotime($tgl_jam);
?>
<!DOCTYPE html>
<html>

<head>
	<title>Cetak - Bukti Pembayaran Siswa</title>
	<link rel="stylesheet" href="bootstrap/css/printer.css">
	<style type="text/css">
		@media print {
			footer {
				page-break-after: always;
			}
		}
	</style>
</head>

<body style="font-size:80%;">

	<div class="col-xs-12">
		<table width="100%">
			<tr>
				<td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="40px"></td>
				<td valign="top">
					<h3 align="center" style="margin-bottom:8px ">
						<?php echo $idt['nmSekolah']; ?>
					</h3>
					<center><?php echo $idt['alamat']; ?></center>
				</td>
				 <td width="100px" align="right"><!-- <img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="40px"> --> </td>
			</tr>
		</table>

		<hr style="margin:4px" />
		<div class="box box-info box-solid">
			<div class="box-header with-border">


				<table width="100%" style="margin-top:10px;margin-bottom:10px;">
					<tr>
						<td width="80px">Nama Siswa</td>
						<td width="8"> : </td>
						<td> <?php echo $dsw['nmSiswa']; ?></td>
						<td></td>

					</tr>
					<tr>
						<td>NIS/NISN</td>
						<td>: </td>
						<td> <?php echo $dsw['nisSiswa']; ?>/<?php echo $dsw['nisnSiswa']; ?></td>
						<td></td>
						<td style="float:right">No. KWT : <?php echo $_GET[kwt]; ?></td>
					</tr>
					<tr>
						<td>Kelas</td>
						<td>: </td>
						<td> <?php echo $dsw['nmKelas']; ?></td>
						<td></td>
						<td></td>
					</tr>
				</table>
			</div><!-- /.box-header -->

			<hr style="margin:4px" />
			<center><b>Bukti Pembayaran </b></center>
			<hr style="margin:4px" /><br>

			<table class="table table-bordered table-striped">
				<thead>
					<tr>
						<th>Jenis Pembayaran</th>
						<th>Tgl Bayar</th>
						<th>Opsi Bayar</th>
						<th>Petugas</th>
						<th>Tagihan/Status</th>

					</tr>
				</thead>
				<tbody>
					<?php
					//tagihan Lainnya
					while ($dj = mysqli_fetch_array($sqlJenisBayar)) {
						if ($dj['tipeBayar'] == 'bebas') {
							$sqlB = mysqli_query($conn, "SELECT
							tagihan_bebas_bayar.idTagihanBebasBayar,
							tagihan_bebas_bayar.idTagihanBebas,
							tagihan_bebas_bayar.tglBayar,
							tagihan_bebas_bayar.jumlahBayar,
							tagihan_bebas_bayar.ketBayar,
							tagihan_bebas_bayar.caraBayar,
							tagihan_bebas_bayar.user,
							tagihan_bebas.idJenisBayar,
							tagihan_bebas.idSiswa,
							tagihan_bebas.idKelas,
							tagihan_bebas.totalTagihan,
							tagihan_bebas.statusBayar
							FROM
								tagihan_bebas_bayar
							INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
							INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
							WHERE tagihan_bebas.idJenisBayar='$dj[idJenisBayar]' AND tagihan_bebas.statusBayar<>'0'  AND tagihan_bebas.idSiswa = '$_GET[siswa]' AND(DATE(tagihan_bebas_bayar.tglBayar) ='$tgl' )");

							while ($dtb = mysqli_fetch_array($sqlB)) {
								if ($dtb['statusBayar'] > 0) {
									$stTagihanLainya = buatRp($dtb['jumlahBayar']) . "/" . $dtb['ketBayar'];
								} else {
									$stTagihanLainya = buatRp($dtb['jumlahBayar']);
								}
								echo "<tr>
								<td align='left'>" . ucwords(strtolower($dj[nmJenisBayar])) . "</td>
								<td align='center'>$dtb[tglBayar]</td>
								<td align='center'>$dtb[caraBayar]</td>
								<td align='right'>$dtb[user]</td>
								<td align='right'>$stTagihanLainya</td>
								
							</tr>";
							}
						} else if ($dj['tipeBayar'] == 'bulanan') {

							//tagihan bulanan
							$sqlLap = mysqli_query($conn, "SELECT * FROM view_laporan_bayar_bulanan 
							WHERE idJenisBayar='$dj[idJenisBayar]' AND idSiswa='$_GET[siswa]' AND statusBayar<>'0' AND (DATE(tglBayar) ='$tgl' ) ORDER BY urutan ASC");
							while ($rt = mysqli_fetch_array($sqlLap)) { //while per page

								if ($rt['statusBayar'] == '1') {
									$stTagihan = buatRp($rt['diBayar']) . "/Lunas";
								} elseif ($rt['statusBayar'] == '2'){
									$stTagihan = buatRp($rt['diBayar'])."/Belum Lunas";
								}else{
									$stTagihan = buatRp($rt['diBayar']);
								}
								$date = date_create($rt[tglBayar]);
								echo "<tr>
								<td>" . ucwords(strtolower($dj[nmJenisBayar])) . "/$rt[nmBulan]</td>
								<td align='center'>" . date_format($date, 'Y-m-d') . "</td>
								<td align='center'>$rt[caraBayar]</td>
								<td align='right'>$rt[user]</td>
								<td align='right'>$stTagihan</td>
								
								</tr>";
							} //end while per page
						}
					}

					//total tagihan lainnya
					$totLainya = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totBayar
					FROM tagihan_bebas_bayar
					INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
					INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa 
					WHERE tagihan_bebas.statusBayar<>'0'  AND tagihan_bebas.idSiswa = '$_GET[siswa]' AND (DATE(tagihan_bebas_bayar.tglBayar) ='$tgl' )"));
					//total tagihan bulanan
					$totBulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(diBayar) AS totBayar FROM view_laporan_bayar_bulanan WHERE idSiswa='$_GET[siswa]' AND statusBayar<>'0' AND (DATE(tglBayar) ='$tgl')"));



					$tot = $totLainya['totBayar'] + $totBulanan['totBayar'];
					?>
					<tr>
						<td colspan="4">Jumlah Pembayaran</td>
						<td align="right"><b><?php echo buatRp($tot); ?></b></td>
					</tr>
				</tbody>
			</table>
		</div><!-- /.box-body -->
	</div><!-- /.box -->
	<br />
	<table width="100%">
		<td valign="top">
			<b>Terbilang :</b><br>
			<i><?php echo ucwords(strtolower(terbilang($tot))); ?> Rupiah</i>
		</td>
		<tr>
			<td align="center"></td>
			<td align="center" width="200px">
				<?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
				<br />Petugas,<br /> <br /><br /><br />
				<!-- <center>  <img src="./gambar/TTDKwintansi.png" height="80px">  </center> -->
				<b><u><?php echo $_SESSION['namalengkap']; ?></u>
			</td>
		</tr>
	</table>
	<!--<footer></footer>-->
	<hr style="margin:20px 0px; border-style: dashed;">

</body>
<script>
	window.print()
</script>

</html>