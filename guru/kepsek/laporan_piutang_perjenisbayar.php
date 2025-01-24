<?php
if (isset($_GET['tampil'])) {
	$tahun = $_GET['tahun'];
	$jenis = $_GET['jenisBayar'];
	$kelas = $_GET['kelas'];
	$dBayar = mysqli_fetch_array(mysqli_query($conn, "select * from jenis_bayar where idJenisBayar='$jenis'"));
} else {
	$tahun = $ta['idTahunAjaran'];
	$jenis = '';
	$kelas = '';
}
?>
<div class="col-xs-12">
	<div class="box box-info box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"><span class="fa fa-file-text-o"></span> Filter Data</h3>
		</div><!-- /.box-header -->
		<div class="table-responsive">
			<form method="GET" action="" class="form-horizontal">
				<input type="hidden" name="view" value="lappiutang" />
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Tahun Ajaran</th>
							<th>Jenis Pembayaran</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
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
								<select id="jenisBayar" name="jenisBayar" class="form-control" required>
									<?php
									$sqlJB = mysqli_query($conn, "SELECT * FROM jenis_bayar WHERE idTahunAjaran='$tahun' ORDER BY idJenisBayar ASC");
									while ($jb = mysqli_fetch_array($sqlJB)) {
										$selected = ($jb['idJenisBayar'] == $jenis) ? ' selected="selected"' : "";
										echo "<option value=" . $jb['idJenisBayar'] . " " . $selected . ">" . $jb['nmJenisBayar'] . "</option>";
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
		if ($dBayar['tipeBayar'] == 'bulanan') {
			//tagihan bulanan
			$sqlLap = mysqli_query($conn, "SELECT * FROM siswa 
					WHERE idSiswa 
					IN (SELECT idSiswa FROM tagihan_bulanan WHERE idJenisBayar='$jenis') 
					 ORDER BY nmSiswa ASC");
	?>
			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Piutang <?php echo $dBayar['nmJenisBayar']; ?></h3>
				</div><!-- /.box-header -->
				<div class="table-responsive">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No.</th>
								<th>NIS</th>
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
								<td>$rt[nisSiswa]</td>
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
				<div class="box-footer">
					<a class="btn btn-success" target="_blank" href="./excel_laporan_piutang_bulanan_perjenis.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>&kelas=<?php echo $_GET['kelas']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
					<a class="pull-right btn btn-danger" target="_blank" href="./cetak_laporan_piutang_bulanan_perjenis.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>"><span class="glyphicon glyphicon-print"></span> Cetak Laporan </a>
				</div>
			</div><!-- /.box -->
		<?php
		} else {
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
								WHERE  tagihan_bebas.idJenisBayar='$jenis'  AND jenis_bayar.idTahunAjaran='$tahun' ORDER BY tagihan_bebas.idTagihanBebas ASC");
		?>
			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Pembayaran <?php echo $dBayar['nmJenisBayar']; ?></h3>
				</div><!-- /.box-header -->
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
				<div class="box-footer">
					<a class="btn btn-success" target="_blank" href="./excel_laporan_piutang_bebas_perjenis.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>&kelas=<?php echo $_GET['kelas']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
					<a class="pull-right btn btn-danger" target="_blank" href="./cetak_laporan_piutang_bebas_perjenis.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>&kelas=<?php echo $_GET['kelas']; ?>"><span class="glyphicon glyphicon-print"></span> Cetak Laporan </a>

				</div>
			</div><!-- /.box -->
	<?php
		}
	}
	?>
</div>