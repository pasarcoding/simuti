<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/fungsi_indotgl.php";
include "config/library.php";

$ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where idTahunAjaran='$_GET[tahun]'"));
$idTahun = $ta['idTahunAjaran'];
$tahun = $ta['nmTahunAjaran'];

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas where npsn='10700295'"));
$pos = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pos_bayar where idPosBayar='$_GET[pos]'"));

$idsiswa = $_GET['siswa'];
$dtsiswa = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM view_detil_siswa where idSiswa='$_GET[siswa]'"));
$nissiswa = $dtsiswa['nisSiswa'];
$namasiswa = $dtsiswa['nmSiswa'];
$namakelas = $dtsiswa['nmKelas'];
$tgllahir = $dtsiswa['tlgLahir'];
?>
<!DOCTYPE html>
<html>

<head>
	<title>Cetak - Laporan Tagihan Siswa</title>
	<link rel="stylesheet" href="bootstrap/css/printer.css">
	<style>
		* {
			box-sizing: border-box;
		}

		.row {
			display: flex;
			margin-left: -5px;
			margin-right: -5px;
		}

		.column {
			flex: 50%;
			padding: 5px;
		}

		.upper {
			text-transform: uppercase;
		}
	</style>
</head>

<body>

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
	<hr />
	<h3 align="center" class="upper"> <?php echo $namasiswa; ?></h3>
	<hr />
	<div class="row">
		<div class="column">
			<table width="100%">
				<tr>
					<td width="200px">NRP </td>
					<td width="10px">:</td>
					<td> <?php echo $nissiswa; ?></td>
				</tr>
				<tr>
					<td>Tanggal Lahir</td>
					<td>:</td>
					<td> <?php echo tgl_indo($dtsiswa['tglLahir']); ?></td>
				</tr>
				<tr>
					<td>Nama Wali</td>
					<td>:</td>
					<td> <?php echo $dtsiswa['nmOrtu']; ?></td>
				</tr>

			</table>
		</div>
		<div class="column">
			<table width="100%">
				<tr>
					<td width="200px">Status </td>
					<td width="10px">:</td>
					<td> <?php echo $dtsiswa['statusSiswa']; ?></td>
				</tr>
				<tr>
					<td>Kelas</td>
					<td>:</td>
					<td> <?php echo $dtsiswa['nmKelas']; ?></td>
				</tr>
				<tr>
					<td>Wali Kelas</td>
					<td>:</td>
					<td> <?php echo $dtsiswa['ketKelas']; ?></td>
				</tr>

			</table>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="column">
			<table border="1" align="left" class="table table-bordered table-striped">
				<thead>
					<tr>
						<th colspan="6">LAPORAN PEMBAYARAN</th>
					</tr>
					<tr>
						<th>No.</th>
						<th>Uraian</th>
						<th>%</th>
						<th>Tanggungan</th>
						<th>Terbayar</th>
						<th>Ket</th>

					</tr>

				</thead>
				<tbody>
					<?php
					$no = 1;
					$totDibayar = 0;
					$totTagihan = 0;
					$sqlJenisBayar = mysqli_query($conn, "SELECT jenis_bayar.*, pos_bayar.nmPosBayar 
				FROM jenis_bayar INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
 				WHERE jenis_bayar.idTahunAjaran='$idTahun'");
					while ($djb = mysqli_fetch_array($sqlJenisBayar)) {
						if ($djb['tipeBayar'] == 'bulanan') {
							//menghitung semua tagihan bulanan
							$tgbul 	=	mysqli_fetch_array(mysqli_query($conn, "SELECT
						jenis_bayar.idPosBayar,
						pos_bayar.nmPosBayar,
						tagihan_bulanan.idSiswa,
						Sum(tagihan_bulanan.jumlahBayar) AS TotalSemuaTagihanBulanan
						FROM
						tagihan_bulanan
						INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
						INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
						WHERE tagihan_bulanan.idJenisBayar='$djb[idJenisBayar]' AND tagihan_bulanan.idSiswa='$idsiswa'
						GROUP BY
						jenis_bayar.idPosBayar"));
							$semuaTagihan = $tgbul['TotalSemuaTagihanBulanan'];


							$dbayar = mysqli_fetch_array(mysqli_query($conn, "SELECT
						jenis_bayar.idPosBayar,
						pos_bayar.nmPosBayar,
						jenis_bayar.idTahunAjaran,
						tahun_ajaran.nmTahunAjaran,
						jenis_bayar.nmJenisBayar,
						Sum(tagihan_bulanan_bayar.jumlahBayar) AS TotalPembayaranPerJenis,
						tagihan_bulanan_bayar.ketBayar
						FROM
						tagihan_bulanan_bayar
						INNER JOIN tagihan_bulanan ON tagihan_bulanan_bayar.idTagihanBulanan = tagihan_bulanan.idTagihanBulanan
						INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
						INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
						INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
						WHERE tagihan_bulanan.idJenisBayar='$djb[idJenisBayar]' AND tagihan_bulanan.statusBayar='1' AND tagihan_bulanan.idSiswa='$idsiswa'
						GROUP BY
						tagihan_bulanan.idJenisBayar"));


							$jBayar 	= $dbayar['TotalPembayaranPerJenis'];
							$tagihan 	= $semuaTagihan;
						} else {
							//menghitung semua tagihan bebas
							$tgb 	= 	mysqli_fetch_array(mysqli_query($conn, "SELECT
							tagihan_bebas.idTagihanBebas,
							jenis_bayar.idPosBayar,
							pos_bayar.nmPosBayar,
							tagihan_bebas.idSiswa,
							SUM(tagihan_bebas.totalTagihan) As TotalSemuaTagihanBebas
							FROM
							tagihan_bebas
							INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
							INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
							WHERE tagihan_bebas.idJenisBayar='$djb[idJenisBayar]' AND tagihan_bebas.idSiswa='$idsiswa'
							GROUP BY
							jenis_bayar.idPosBayar"));
							$semuaTagihan = $tgb['TotalSemuaTagihanBebas'];

							$tgbs 	= 	mysqli_fetch_array(mysqli_query($conn, "SELECT
					tagihan_bebas.idTagihanBebas,
					jenis_bayar.idPosBayar,
					pos_bayar.nmPosBayar,
					tagihan_bebas.idSiswa,
					tagihan_bebas.totalTagihan
					FROM
					tagihan_bebas
					INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
					INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
					WHERE  tagihan_bebas.idSiswa='$idsiswa'
					GROUP BY
					jenis_bayar.idPosBayar"));


							$dbayar = mysqli_fetch_array(mysqli_query($conn, "SELECT
						tagihan_bebas.idJenisBayar,
						jenis_bayar.nmJenisBayar,
						jenis_bayar.idTahunAjaran,
						tahun_ajaran.nmTahunAjaran,
						tagihan_bebas_bayar.idTagihanBebas,
						Sum(tagihan_bebas_bayar.jumlahBayar) AS TotalPembayaranPerJenis,
						tagihan_bebas_bayar.ketBayar,
						tagihan_bebas_bayar.tglBayar
						FROM
						tagihan_bebas_bayar
						INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
						INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
						INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
						WHERE tagihan_bebas_bayar.idTagihanBebas='$tgb[idTagihanBebas]'
						GROUP BY
						tagihan_bebas_bayar.idTagihanBebas"));


							$jBayar 	= $dbayar['TotalPembayaranPerJenis'];
							$tagihan 	= $semuaTagihan;
						}
						echo "<tr>
				<td align='center'>$no</td>
				<td>$djb[nmJenisBayar]</td>
				<td></td>
				<td align='right'>" . buatRp($tagihan) . "</td>
				<td>" . buatRp($jBayar) . "</td>
				<td>$dbayar[ketBayar]</td>
				</tr>";

						$no++;
						$totDibayar += $jBayar;
						$totTagihan += $tagihan;
					}
					?>

					<tr>
						<td colspan="3"><b>Jumlah</b></td>
						<td align="right"><b><?php echo buatRp($totTagihan); ?></b></td>
						<td align="right"><b><?php echo buatRp($totDibayar); ?></b></td>
						<td align="right"></td>
					</tr>
					<tr>
						<td colspan="4">
							<p><i>Jumlah tanggungan yang belum terbayar dalam se-tahun</i></p>
						</td>
						<td align="right"><b><?php echo buatRp($totTagihan - $totDibayar); ?></b></td>
						<?php
						$siswa = $totTagihan - $totDibayar;
						if ($siswa == '0') {
							echo "<td align='right'>Lunas</td>";
						} else {
							echo "<td align='right'>Belum Lunas</td>";
						}
						?>
					</tr>
				</tbody>
			</table>
		</div>

		<div class="column">
			<table border="1" align="rigth" class="table table-bordered table-striped" width="50%">
				<thead>
					<tr>
						<th colspan="6">DATA SETORAN</th>
					</tr>
					<tr>
						<th>No</th>
						<th>Tgl Pembayaran</th>
						<th>Besar Pembayaran</th>
						<th>Per Untuk-an</th>
						<th>Ket</th>
					</tr>

				</thead>
				<tbody>
					<?php
					$no = 1;
					$totDibayar = 0;
					$totTagihan = 0;

					$dbayarss = mysqli_query($conn, "SELECT
					jenis_bayar.nmJenisBayar,
					tagihan_bulanan_bayar.idTagihanBulanan,
					tagihan_bulanan_bayar.jumlahBayar,
					tagihan_bulanan_bayar.ketBayar,
					tagihan_bulanan_bayar.caraBayar,
					tagihan_bulanan_bayar.tglBayar
					FROM
					tagihan_bulanan_bayar
					INNER JOIN tagihan_bulanan ON tagihan_bulanan_bayar.idTagihanBulanan = tagihan_bulanan.idTagihanBulanan
					INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
					WHERE tagihan_bulanan.idSiswa='$_GET[siswa]'");

					$dbayars = mysqli_query($conn, "SELECT
					tagihan_bebas_bayar.idTagihanBebas,
					jenis_bayar.nmJenisBayar,
				    tagihan_bebas_bayar.jumlahBayar,
					tagihan_bebas_bayar.ketBayar,
					tagihan_bebas_bayar.caraBayar,
					tagihan_bebas_bayar.tglBayar
					FROM
					tagihan_bebas_bayar
					INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
					INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
					WHERE tagihan_bebas.idSiswa='$_GET[siswa]'");

					while ($awo = mysqli_fetch_array($dbayars)) {
						echo "<tr>
						<td align='center'>$no</td>
						<td>$awo[tglBayar]</td>
						<td align='right'>" . buatRp($awo['jumlahBayar']) . "</td>
						<td>$awo[nmJenisBayar]</td>
						<td>$awo[caraBayar]</td>
						</tr>";
						$no++;
						$jBayars	+= $awo['jumlahBayar'];
					}
					while ($awo = mysqli_fetch_array($dbayarss)) {
						echo "<tr>
						<td align='center'>$no</td>
						<td>$awo[tglBayar]</td>
						<td align='right'>" . buatRp($awo['jumlahBayar']) . "</td>
						<td>$awo[nmJenisBayar]</td>
						<td>$awo[caraBayar]</td>
						</tr>";
						$no++;
						$jBayars	+= $awo['jumlahBayar'];
					}
					?>
					<tr>
						<td colspan="2"><b>Jumlah</b></td>
						<td align="center"><b><?php echo buatRp($jBayars); ?></b></td>
						<td align="center"></td>
						<td align="right"></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<br />
	<div class="row">
		<div class="column">
			<table width="100%">
				<tr>
					<td align="right" width="200px"></td>
					<td align="center">Orang Tua,
						<br /><br /><br /><br /><br /><br />
						<hr>
					</td>
			</table>
		</div>
		<div class="column">
			<table width="100%">
				<td align="center" width="300px">
					<?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
					<br />Bendahara,<br /><br /><br /><br /><br />
					<b><u><?php echo $idt['nmBendahara']; ?></u><br /><?php echo $idt['nipBendahara']; ?></b>
				</td>
				</tr>
			</table>
		</div>
	</div>
</body>
<script>
	window.print()
</script>

</html>