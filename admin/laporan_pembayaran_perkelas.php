<?php
if (isset($_GET['tampil'])) {
	$tahun = $_GET['tahun'];
	$jenis = $_GET['jenisBayar'];
	$kelas = $_GET['kelas'];
	$dBayar = mysqli_fetch_array(mysqli_query($conn,"select * from jenis_bayar where idJenisBayar='$jenis'"));
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
				<input type="hidden" name="view" value="lappembayaran" />
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Tahun Ajaran</th>
							<th>Jenis Pembayaran</th>
							<th>Kelas</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<select id="tahun" name="tahun" class="form-control" required>
									<?php
									$sqltahun = mysqli_query($conn,"SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
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
									$sqlJB = mysqli_query($conn,"SELECT * FROM jenis_bayar WHERE idTahunAjaran='$tahun' ORDER BY idJenisBayar ASC");
									while ($jb = mysqli_fetch_array($sqlJB)) {
										$selected = ($jb['idJenisBayar'] == $jenis) ? ' selected="selected"' : "";
										echo "<option value=" . $jb['idJenisBayar'] . " " . $selected . ">" . $jb['nmJenisBayar'] . "</option>";
									}
									?>
								</select>
							</td>
							<td>
								<select id="kelas" name="kelas" class="form-control" required>
									<option value="" selected> - Pilih Kelas - </option>
									<?php
									$sqk = mysqli_query($conn,"SELECT * FROM kelas_siswa ORDER BY idKelas ASC");
									while ($k = mysqli_fetch_array($sqk)) {
										$selected = ($k['idKelas'] == $kelas) ? ' selected="selected"' : "";
										echo "<option value=" . $k['idKelas'] . " " . $selected . ">" . $k['nmKelas'] . "</option>";
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
			$sqlLap = mysqli_query($conn,"SELECT * FROM siswa 
					WHERE idSiswa 
					IN (SELECT idSiswa FROM tagihan_bulanan WHERE idJenisBayar='$jenis' AND idKelas='$kelas') 
					AND idKelas='$kelas' ORDER BY nmSiswa ASC");
	?>
			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Pembayaran <?php echo $dBayar['nmJenisBayar']; ?></h3>
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
								$sqlTB = mysqli_query($conn,"SELECT tagihan_bulanan.*, bulan.nmBulan, bulan.urutan
											FROM tagihan_bulanan
											INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
											WHERE tagihan_bulanan.idSiswa = '$rt[idSiswa]' AND tagihan_bulanan.idJenisBayar = '$jenis' ORDER BY bulan.urutan ASC");
								while ($t = mysqli_fetch_array($sqlTB)) {
									if ($t['statusBayar'] == '1') {
										$date = strtotime($t['tglBayar']);
										$tgl = date('d/m/y', $date);
										$jmlbayar = buatRp($t['jumlahBayar']);
									} else {
										$tgl = '-';
										$jmlbayar = '-';
									}
									echo "<td align='center'>$tgl<br/>$jmlbayar</td>";
								}
								echo "</tr>";
								$no++;
							}
							?>
						</tbody>
					</table>
				</div><!-- /.box-body -->
				<div class="box-footer">
					<a class="btn btn-success" target="_blank" href="./excel_laporan_pembayaran_bulanan_perkelas.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>&kelas=<?php echo $_GET['kelas']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
					<a class="pull-right btn btn-danger" target="_blank" href="./cetak_laporan_pembayaran_bulanan_perkelas.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>&kelas=<?php echo $_GET['kelas']; ?>"><span class="glyphicon glyphicon-print"></span> Cetak Laporan </a>
				</div>
			</div><!-- /.box -->
		<?php
		} else {
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
								<th>Total Tagihan</th>
								<th>Total Bayar</th>
								<th>Tunggakan</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>
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
						</tbody>
					</table>
				</div><!-- /.box-body -->
				<div class="box-footer">
					<a class="btn btn-success" target="_blank" href="./excel_laporan_pembayaran_bebas_perkelas.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>&kelas=<?php echo $_GET['kelas']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
					<a class="pull-right btn btn-danger" target="_blank" href="./cetak_laporan_pembayaran_bebas_perkelas.php?tahun=<?php echo $_GET['tahun']; ?>&jenisBayar=<?php echo $_GET['jenisBayar']; ?>&kelas=<?php echo $_GET['kelas']; ?>"><span class="glyphicon glyphicon-print"></span> Cetak Laporan </a>

				</div>
			</div><!-- /.box -->
	<?php
		}
	}
	?>
</div>