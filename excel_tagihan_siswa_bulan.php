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

$lst_siswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa where idKelas='$_GET[kelas]'");
//while per page

$kelasss = mysqli_query($conn, "SELECT * FROM kelas_siswa WHERE idKelas = '$_GET[kelas]'");
$kelas = mysqli_fetch_array($kelasss);
$kelass = $kelas['nmKelas'];

$bulan = mysqli_query($conn, "SELECT * FROM bulan WHERE idBulan = '$_GET[bulan]'");
$bulans = mysqli_fetch_array($bulan);
$bulann = $bulans['nmBulan'];

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_tagihan_bulan" . str_replace(" ", "_", $pos['nmPosBayar']) . "_" . date('dmyHis') . ".xls");
?>
<table width="100%">
	<tr>
		<td valign="top">
			<h3 align="center" style="margin-bottom:8px ">
				<?php echo $idt['nmSekolah']; ?>
			</h3>
			<center><?php echo $idt['alamat']; ?></center>
		</td>
	</tr>
</table>
<hr>

<center>
	<h4>Tagihan Siswa Kelas : <?php echo $kelass; ?> - Bulan : <?php echo $bulann; ?>
</center>
<table border="1" class="table table-bordered table-striped">
	<thead>
		<tr>

			<th>Nama Siswa</th>
			<th>Nama Bulan</th>
			<th >Total Tagihan</th>
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
					$rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBln['nmJenisBayar'] . "  - " . str_replace('.', ',', buatRp($tBln['jumlahBayar'])) . "<br>
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
						$rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBbs['nmJenisBayar'] . "  - " . str_replace('.', ',', buatRp($sisa_tag_bebas)) . "<br> 
					";
						$total_tagihan += $sisa_tag_bebas;
					}
				}
			}



			echo "<tr class='header1 expand'>
							
				<td>$sws[nmSiswa]</td>
				<td>$nmBulan</td>
				
				<td >" . buatRp($total_tagihan) . "</td>
				
			</tr>";
			echo "
			<tr>										
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