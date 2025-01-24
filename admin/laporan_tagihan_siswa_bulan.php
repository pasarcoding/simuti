<?php
if (isset($_GET['tampil'])) {
	$tahun = $_GET['tahun'];
	$kelas = $_GET['kelas'];
	$bulan = $_GET['bulan'];
} else {
	$tahun = $ta['idTahunAjaran'];
	$kelas = '';
	$bulan = '';
}

//tahun ajaran
$t = mysqli_query($conn, "SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif = 'Y'");
$ta = mysqli_fetch_array($t);
$thn_ajar = $ta['ta'];

$bulan = mysqli_query($conn, "SELECT * FROM bulan WHERE idBulan = '$_GET[bulan]'");
$bulans = mysqli_fetch_array($bulan);
$bulann = $bulans['nmBulan'];
$bulanid = $bulans['idBulan'];
?>
<div class="col-xs-12">
	<div class="box box-primary box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Tagihan Siswa Bulan Ini</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<form method="GET" action="" class="form-horizontal">
				<input type="hidden" name="view" value="laptagihansiswabulan">
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Kelas</th>
							<th>Tahun Ajaran</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<select id="kelas" name="kelas" class="form-control" required>
									<option value="" selected> - Pilih Kelas - </option>
									<?php
									$sqk = mysqli_query($conn, "SELECT * FROM kelas_siswa ORDER BY idKelas ASC");
									while ($k = mysqli_fetch_array($sqk)) {
										$selected = ($k['idKelas'] == $kelas) ? ' selected="selected"' : "";
										echo "<option value=" . $k['idKelas'] . " " . $selected . ">" . $k['nmKelas'] . "</option>";
									}
									?>
								</select>
							</td>
							<td>
								<select id="tahun" name="tahun" class="form-control" required>
									<?php
									$sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
									while ($t = mysqli_fetch_array($sqltahun)) {
										$selected = ($t['idTahunAjaran'] == $tahun) ? ' selected="selected"' : "";
										echo "<option value=" . $t['idTahunAjaran'] . " " . $selected . ">" . $t['nmTahunAjaran'] . "</option>";
									}
									?>
								</select>
							</td>
							<td>
								<select id="bulan" name="bulan" class="form-control" required>
									
										<?php
										$sqlBulan = mysqli_query($conn, "SELECT * FROM bulan ORDER BY urutan ASC");
										while ($b = mysqli_fetch_array($sqlBulan)) {
											echo "<option value=" . $b['idBulan'] . ">" . $b['nmBulan'] . "</option>";
										}
										?>
								</select>
							</td>
							<td width="100">
								<input type="submit" name="tampil" value="Tampilkan" class="btn btn-success pull-right">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div><!-- /.box-body -->
	</div><!-- /.box -->
	<?php
	if (isset($_GET['tampil'])) {

		$lst_siswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa where idKelas='$_GET[kelas]'");

	?>
		<div class="box box-primary">
			<div class="box-body">
				<div class="table-responsive">
					<table id="example1" class="table table-striped table-bordered">
						<thead>
							<tr>
								<th>#</th>
								<th>Nama Siswa</th>
								<th>Nama Bulan</th>
								<th colspan='2'>Total Tagihan</th>
							
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
								<td><span class='btn btn-danger btn-xs sign'></span></td>
				<td>$sws[nmSiswa]</td>
				<td>$nmBulan</td>
				<td colspan='2'>" . buatRp($total_tagihan) . "</td>
				
				</tr>";
								echo "
			<tr>										
										<td colspan='5'>
											<div class='panel panel-info'>
												<div class='panel-body'>
													<table class='table table-bordered table-striped'>
													  <thead>
														<tr>
														
															<th>Bulan</th>
															<th>Rincian Tagihan</th>
														
														</tr>
													  </thead>
													  <tbody>
													  <tr>
													 		
															  <td>$nmBulan</td>
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
				</div><!-- /.box-body -->
				<div class="box-footer">
					<a class="btn btn-danger" target="_blank" href="./cetak_tagihan_siswa_bulan.php?kelas=<?php echo $_GET['kelas']; ?>&tahun=<?php echo $_GET['tahun']; ?>&bulan=<?php echo $_GET['bulan']; ?>"><span class="glyphicon glyphicon-print"></span> Cetak Semua Tagihan</a>
					<a class="btn btn-success" target="_blank" href="./excel_tagihan_siswa_bulan.php?kelas=<?php echo $_GET['kelas']; ?>&tahun=<?php echo $_GET['tahun']; ?>&bulan=<?php echo $_GET['bulan']; ?>"><span class="glyphicon glyphicon-file"></span> Export ke Excel</a>
				</div>
			</div><!-- /.box -->
		<?php
	}
		?>
		</div>