<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/fungsi_indotgl.php";
include "config/library.php";


//tahun ajaran
$t = mysqli_query($conn, "SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif = 'Y'");
$ta = mysqli_fetch_array($t);
$thn_ajar = $ta['ta'];

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas where npsn='10700295'"));
$pos = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM pos_bayar where idPosBayar='$_GET[pos]'"));

$kelasss = mysqli_query($conn, "SELECT * FROM kelas_siswa WHERE idKelas = '$_GET[kelas]'");
$kelas = mysqli_fetch_array($kelasss);
$kelass = $kelas['nmKelas'];

$bulan = mysqli_query($conn, "SELECT * FROM bulan WHERE idBulan = '$_GET[bulan]'");
$bulans = mysqli_fetch_array($bulan);
$bulann = $bulans['nmBulan'];
?>
<!DOCTYPE html>
<html>

<head>
	<title>Cetak - Laporan Tagihan Siswa</title>
	<link rel="stylesheet" href="bootstrap/css/printer.css">
	<style type="text/css">
		@media print {
			footer {
				page-break-after: always;
			}
		}
	</style>
</head>

<body>

	<?php
	$lst_siswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa where idKelas='$_GET[kelas]' and idSiswa='$_GET[siswa]'");
	$swa = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM view_detil_siswa where idKelas='$_GET[kelas]' and idSiswa='$_GET[siswa]'"));
	?>
	<style type="text/css">
		.upper {
			text-transform: uppercase;
		}

		.lower {
			text-transform: lowercase;
		}

		.cap {
			text-transform: capitalize;
		}

		.small {
			font-variant: small-caps;
		}
	</style>
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
	<center>
		<h3 class="upper"> <?php echo $swa['nmSiswa']; ?>
	</center>
	<table border="1" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th colspan="6">Laporan Pembayaran</th>
			</tr>
			<tr>
				<th>No</th>
				<th>Uraian</th>
				<th>%</th>
				<th>Tanggungan
				<th>
				<th>Terbayar</th>
				<th>Ket</th>
			</tr>
		</thead>
		<tbody>
			<?php
			while ($sws = mysqli_fetch_array($lst_siswa)) {
				$no = 1;
				$rincian_tagihan = '';
				$total_tagihan = 0;
				// tagihan bulan 
				$tag_bln = mysqli_query($conn, "SELECT tagihan_bulanan.idSiswa, tagihan_bulanan.jumlahBayar,
													  jenis_bayar.idPosBayar, 
													  jenis_bayar.nmJenisBayar, 
													  tahun_ajaran.nmTahunAjaran,
													  pos_bayar.nmPosBayar,
													  bulan.nmBulan,
													  bulan.urutan
											  FROM tagihan_bulanan 
											  LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
											  LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
											  LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
											  LEFT JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
											  WHERE tagihan_bulanan.idSiswa='$sws[idSiswa]' AND jenis_bayar.idTahunAjaran='$_GET[tahun]' AND tagihan_bulanan.statusBayar='0' AND tagihan_bulanan.idBulan='$_GET[bulan]'
											  order by bulan.urutan asc ");
				while ($tBln = mysqli_fetch_array($tag_bln)) {
					if ($tBln['jumlahBayar'] <> 0) {
						$pisah_TA = explode('/', $tBln['nmTahunAjaran']);
						if ($tBln['urutan'] <= 6) {
							$nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[0];
						} else {
							$nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[1];
						}
						$rincian_tagihan = $rincian_tagihan . "" . $tBln['nmJenisBayar'] . "  - " . str_replace('.', ',', buatRp($tBln['jumlahBayar'])) . "<br>
					";
						$total_tagihan += $tBln['jumlahBayar'];
					}
				}

				// tagihan bebas
				$tag_bebas = mysqli_query($conn, "SELECT tagihan_bebas.*, 
													  SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, 
													  jenis_bayar.idPosBayar, 
													  jenis_bayar.nmJenisBayar, 
													  tahun_ajaran.nmTahunAjaran,
													  pos_bayar.nmPosBayar,
													  bulan.nmBulan,
													  bulan.urutan
											  FROM tagihan_bebas 
											  LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
											  LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
											  LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
											  LEFT JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan
											  WHERE tagihan_bebas.idSiswa='$sws[idSiswa]' AND jenis_bayar.idTahunAjaran='$_GET[tahun]' AND tagihan_bebas.statusBayar!='1' AND tagihan_bebas.idBulan='$_GET[bulan]'
											  GROUP BY tagihan_bebas.idJenisBayar order by bulan.urutan asc");

				while ($tBbs = mysqli_fetch_array($tag_bebas)) {
					if ($tBbs['totalTagihanBebas'] <> 0) {
						$pisah_TA = explode('/', $tBbs['nmTahunAjaran']);
						if ($tBbs['urutan'] <= 6) {
							$nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[0];
						} else {
							$nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[1];
						}
						$bayar_bebas = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayarBebas FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tBbs[idTagihanBebas]'"));
						$sisa_tag_bebas = $tBbs['totalTagihanBebas'] - $bayar_bebas['totalBayarBebas'];
						if ($sisa_tag_bebas <> 0) {
							$rincian_tagihan = $rincian_tagihan .  " " . $tBbs['nmJenisBayar'] . "  - " . str_replace('.', ',', buatRp($sisa_tag_bebas)) . "<br> 
					";
							$total_tagihan += $sisa_tag_bebas;
						}
					}
				}
				echo "<tr class='header1 expand'>
										<td>$no</td>
										<td >$rincian_tagihan</td>
										<td></td>
										<td>$nmBulan</td>
										<td>$nmBulan</td>
										<td colspan='2'>" . buatRp($total_tagihan) . "</td>
									</tr>";
				echo "<tr>										
										<td colspan='5'>
											<div class='panel panel-info'>
												<div class='panel-body'>
													<table class='table table-bordered table-striped'>
													  <thead>
														<tr>
															<th>Rincian Tagihan</th>
														</tr>
													  </thead>
													  <tbody>
													  <tr>
															  <td >$rincian_tagihan</td>
															</tr>";
				echo "</tbody>
															</table>
														</div>
													</div>
												</td>
											</tr>";
				$no++;
				$totDibayar += $jBayar;
				$totTagihan += $tagihan;
			}
			?>
		</tbody>
	</table>

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
	<footer></footer>


</body>
<script>
	window.print()
</script>

</html>