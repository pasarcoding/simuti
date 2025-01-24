<?php if ($_GET['act'] == '') {
	$sqlEdit = mysqli_query($conn, "SELECT
									jenis_gaji.*,
									tahun_ajaran.nmTahunAjaran
								FROM
									jenis_gaji
								INNER JOIN tahun_ajaran ON jenis_gaji.idTahunAjaran = tahun_ajaran.idTahunAjaran
								WHERE jenis_gaji.id='$_GET[jenis]'");
	$record = mysqli_fetch_array($sqlEdit);
?>
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Tarif - <?php echo $record['nmGaji']; ?></h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form method="post" action="" class="form-horizontal">
					<input type="hidden" name="idTahunAjaran" class="form-control" value="<?php echo $record['idTahunAjaran']; ?>">
					<input type="hidden" name="tipe" class="form-control" value="<?php echo $record['jenis']; ?>">

					<div class="form-group">
						<label for="" class="col-sm-2 control-label">Tahun</label>
						<div class="col-sm-4">
							<input type="text" name="nmTahunAjaran" class="form-control" value="<?php echo $record['nmTahunAjaran']; ?>" readonly>
						</div>

						<div class="col-sm-2">
							<input type="submit" name="cari" value="Cari / Tampilkan" class="btn btn-success">
						</div>
					</div>
				</form>
				<hr>
				<label for="" class="col-sm-2">Aksi</label>
				<div class="col-sm-10">
					<a class="btn btn-success" href="?view=set_tarif_gaji&jenis=<?php echo $record['id']; ?>&tahun=<?php echo $record['idTahunAjaran']; ?>&tipe=<?php echo $record['jenis']; ?>&act=tambah_<?php echo $record['jenis']; ?>"><span class='glyphicon glyphicon-plus'></span> Tambah Data</a>
					<a class="btn btn-warning" href="?view=set_tarif_gaji&jenis=<?php echo $record['id']; ?>&tipe=<?php echo $record['jenis']; ?>"><span class='glyphicon glyphicon-refresh'></span> Refresh</a>
					<a class="btn btn-default" href="?view=setting_gaji"><span class='glyphicon glyphicon-repeat'></span> Kembali</a>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
		<?php
		if (isset($_POST['cari'])) {
			if (isset($_POST['tipe']) && $_POST['tipe'] == 'pokok') {
				$sqlCariBulanan = mysqli_query($conn, "SELECT  gaji_pokok.*,bulan.*
											FROM
											gaji_pokok
											INNER JOIN bulan ON gaji_pokok.id_bulan=bulan.idBulan
											WHERE gaji_pokok.id_jenis='$_GET[jenis]'");
			} else {
				$sqlCariBulanan = mysqli_query($conn, "SELECT  gaji_tunjangan.*,bulan.*
											FROM
											gaji_tunjangan
											INNER JOIN bulan ON gaji_tunjangan.id_bulan=bulan.idBulan
											WHERE gaji_tunjangan.id_jenis='$_GET[jenis]'");
			}

		?>
			<div class="box box-primary">
				<div class="table-responsive">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>No.</th>
								<th>Bulan</th>
								<th>Nominal</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							while ($rt = mysqli_fetch_array($sqlCariBulanan)) {

								echo "<tr>
										<td>$no</td>
										<td>$rt[nmBulan]</td>
										<td>" . buatRp($rt['nominal']) . "</td>
										<td style='text-align:center'>
											<a class='btn btn-success btn-xs' href='?view=set_tarif_gaji&jenis=$rt[id_jenis]&tipe=pokok&act=edit&id=$rt[id_gaji_pokok]'><span class='glyphicon glyphicon-edit'></span></a>
											<a class='btn btn-danger btn-xs' href='?view=set_tarif_gaji&jenis=$rt[id_jenis]&tipe=pokok&id=$rt[id_gaji_pokok]&act=hapus' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
										</td>
									</tr>";
								$no++;
							}
							?>
						</tbody>
					</table>
				</div><!-- /.box-body -->

			</div><!-- /.box -->
		<?php
		}
		?>
	</div>
<?php
} elseif ($_GET['act'] == 'edit') {
	if (isset($_POST['update'])) {
		$query = mysqli_query($conn, "UPDATE gaji_pokok SET nominal='$_POST[nominal]'
										WHERE id_gaji_pokok='$_POST[id]'");
		if ($query) {
			echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$_POST[id]&tipe=pokok&sukses';</script>";
		} else {
			echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$_POST[id]&tipe=pokok&gagal';</script>";
		}
	}

	$sqlEdit = mysqli_query($conn, "SELECT  *
							
						FROM
						gaji_pokok
						INNER JOIN jenis_gaji ON gaji_pokok.id_jenis=jenis_gaji.id
						WHERE id_jenis='$_GET[jenis]' AND id_gaji_pokok='$_GET[id]'");
	$record = mysqli_fetch_array($sqlEdit);
?>
	<div class="col-md-6">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Edit Tagihan Siswa</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form method="post" action="" class="form-horizontal">
					<div class="col-sm-12">
						<div class="box box-solid box-danger">
							<div class="box-header">
								<h3 class="box-title">Informasi Gaji</h3>
							</div>
							<div class="box-body">
								<input type="hidden" name="id" value="<?php echo $record['id']; ?>">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Jenis Gaji</label>
									<div class="col-sm-8">
										<input type="text" name="nmJenisBayar" class="form-control" value="<?php echo $record['nmGaji']; ?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Bulan</label>
									<div class="col-sm-8">
										<input type="text" name="idBulan" class="form-control" value="<?php echo $record['id_bulan']; ?>" readonly>
									</div>
								</div>

								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Total Gaji</label>
									<div class="col-sm-8">
										<input type="text" name="nominal" class="form-control" value="<?php echo $record['nominal']; ?>">
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label"></label>
									<div class="col-sm-8">
										<input type="submit" name="update" value="Update Gaji" class="btn btn-primary">
									</div>
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php
} elseif ($_GET['act'] == 'tambah_tunjangan') {
	if (isset($_POST['simpantagihanbebas'])) {
		//$nData = $_POST['jmldata']; // membaca jumlah data

		$siswa = $_POST['pilih'];
		$nData = count($siswa);

		$kelas = $_POST['idKelas'];
		$bulan = $_POST['idBulan'];
		$tagihan = $_POST['nTagihan'];

		$id = $_POST['id'];
		// looping
		for ($i = 0; $i < $nData; $i++) {
			$idSiswa = $siswa[$i];
			$idKelas = $kelas[$i];
			$idBulan = $bulan[$i];
			$nTagihan = $tagihan[$i];

			$query = mysqli_query($conn, "INSERT INTO tagihan_bebas(id,idSiswa,idKelas,idBulan,totalTagihan)
									VALUES('$id',
											'$idSiswa',
											'$idKelas',
											'$idBulan',
											'$nTagihan')");
		}

		if ($query) {
			echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$id&tipe=pokok&sukses';</script>";
		} else {
			echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$id&tipe=pokok&gagal';</script>";
		}
	}

	$sqlEdit = mysqli_query($conn, "SELECT
							jenis_gaji.*,
							tahun_ajaran.nmTahunAjaran
						FROM
							jenis_gaji
						INNER JOIN tahun_ajaran ON jenis_gaji.idTahunAjaran = tahun_ajaran.idTahunAjaran
						WHERE jenis_gaji.id='$_GET[jenis]'");
	$record = mysqli_fetch_array($sqlEdit);
?>
	<div class="col-md-12">
		<div class="box box-solid box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Setting Gaji TUnjangan</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<div class="col-sm-5">
					<div class="box box-solid box-danger">
						<div class="box-header">
							<h3 class="box-title">Data Gaji</h3>
						</div>
						<div class="box-body">
							<form method="GET" action="" class="form-horizontal">
								<input type="hidden" name="view" value="set_tarif_gaji">
								<input type="hidden" name="tahun" value="<?php echo $record['idTahunAjaran']; ?>">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Nama Gaji</label>
									<div class="col-sm-8">
										<input type="text" name="" class="form-control" value="<?php echo $record['nmGaji']; ?>" readonly>
									</div>
								</div>
								<input type="hidden" name="act" value="tambah_tunjangan">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Jenis Gaji</label>
									<div class="col-sm-8">
										<input type="text" name="jenis" class="form-control" value="<?php echo $record['jenis']; ?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Tahun</label>
									<div class="col-sm-8">
										<input type="text" name="" class="form-control" value="<?php echo $record['nmTahunAjaran']; ?>" readonly>
									</div>
								</div>

								<div class="form-group">

									<label for="" class="col-sm-4 control-label ">Bulan</label>
									<div class="col-sm-4">
										<select name="id_bulan" class="form-control">
											<?php
											$sqks = mysqli_query($conn, "SELECT * FROM bulan ");
											while ($ks = mysqli_fetch_array($sqks)) {
												echo "<option value=" . $ks['idBulan'] . ">" . $ks['nmBulan'] . "</option>";
											}
											?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label"></label>
									<div class="col-sm-8">
										<input type="submit" name="tampil" class="btn btn-warning btn-sm" value="Tampilkan">
									</div>
								</div>
							</form>
						</div>
					</div>
					<div class="box box-solid box-warning">
						<div class="box-header">
							<h3 class="box-title">Tarif Setiap Siswa Sama</h3>
						</div>
						<div class="box-body">
							<div class="form-group">
								<label for="" class="col-sm-4 control-label">Tarif (Rp.)</label>
								<div class="col-sm-4">
									<input type="text" id="allTarifBebas" name="allTarif" class="form-control harusAngka">
								</div>
								<div class="col-sm-4">
									<i>Masukkan Nilai dan Tekan Enter</i>
								</div>
							</div>


						</div>
					</div>
				</div>
				<div class="col-sm-7">
					<?php
					if (isset($_GET['tampil'])) {

						$sqlSiswaTagihan = mysqli_query($conn, "SELECT siswa.*, kelas_siswa.nmKelas
									FROM
										siswa
									INNER JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas 
									WHERE siswa.idSiswa NOT IN (SELECT idSiswa FROM tagihan_bebas WHERE id='$_GET[jenis]' AND idKelas='$_GET[idKelas]') AND siswa.idKelas='$_GET[idKelas]' AND siswa.statusSiswa='Aktif'");
						//$n = mysqli_num_rows($sqlSiswaTagihan); // membaca jumlah data
					?>
						<div class="box box-solid box-success">
							<div class="box-header">
								<h3 class="box-title">Tentukan Tagihan Setiap Siswa</h3>
							</div>
							<form method="POST" action="" class="form-horizontal">
								<input type="hidden" name="id" value="<?php echo $_GET['jenis']; ?>">
								<div class="table-responsive">
									<table class="table table-striped">
										<tr>
											<th>No.</th>
											<th><input type="checkbox" id="parent"></th>
											<th>NIS</th>
											<th>Nama Siswa</th>
											<th>Kelas</th>
											<th>Besar Tagihan</th>
										</tr>
										<?php
										$no = 1;
										while ($ft = mysqli_fetch_array($sqlSiswaTagihan)) {
										?>
											<tr>
												<td><?php echo $no; ?></td>
												<td><input type="checkbox" name="pilih[]" value="<?php echo $ft['idSiswa']; ?>" class="child"></td>
												<td><?php echo $ft['nisSiswa']; ?></td>
												<td><?php echo $ft['nmSiswa']; ?></td>
												<td><?php echo $ft['nmKelas']; ?><br><br>Bulan</td>
												<td>
													<input type="hidden" name="idKelas[]" value="<?php echo $ft['idKelas']; ?>">
													<input type="text" id="nTagihan" name="nTagihan[]" class="form-control harusAngka nTagihan" required>
													<select name="idBulan[]" class="form-control">
														<?php
														$sqks = mysqli_query($conn, "SELECT * FROM bulan ");
														while ($ks = mysqli_fetch_array($sqks)) {
															$selected = ($ks['idBulan'] == $_GET['idBulan']) ? ' selected="selected"' : "";

															echo "<option value=" . $_GET['idBulan'] . " " . $selected . ">" . $ks['nmBulan'] . "</option>";
														}
														?>
													</select>
												</td>

											</tr>
										<?php $no++;
										} ?>
									</table>
								</div>
								<div class="box-footer">
									<!--<input type="hidden" name="jmldata" value="<?php echo $n; ?>">-->
									<input type="submit" name="simpantagihanbebas" value="Simpan Tagihan" class="btn btn-success">
									<a href="index.php?view=jenisbayar" class="btn btn-default">Cancel</a>
								</div>
							</form>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
	</div>
<?php
} elseif ($_GET[act] == 'tambah_pokok') {
	if (isset($_POST['simpan'])) {
		$idJenisBayar = $_POST['id'];

		//nilai tarif
		$dt1 = $_POST['n1'];
		$dt2 = $_POST['n2'];
		$dt3 = $_POST['n3'];
		$dt4 = $_POST['n4'];
		$dt5 = $_POST['n5'];
		$dt6 = $_POST['n6'];
		$dt7 = $_POST['n7'];
		$dt8 = $_POST['n8'];
		$dt9 = $_POST['n9'];
		$dt10 = $_POST['n10'];
		$dt11 = $_POST['n11'];
		$dt12 = $_POST['n12'];

		$jmlbulan = 12;
		for ($j = 1; $j <= $jmlbulan; $j++) {
			switch ($j) {
				case 1:
					$dt = $dt1;
					break;
				case 2:
					$dt = $dt2;
					break;
				case 3:
					$dt = $dt3;
					break;
				case 4:
					$dt = $dt4;
					break;
				case 5:
					$dt = $dt5;
					break;
				case 6:
					$dt = $dt6;
					break;
				case 7:
					$dt = $dt7;
					break;
				case 8:
					$dt = $dt8;
					break;
				case 9:
					$dt = $dt9;
					break;
				case 10:
					$dt = $dt10;
					break;
				case 11:
					$dt = $dt11;
					break;
				case 12:
					$dt = $dt12;
					break;
				default:
					$dt = "";
			}
			$query = mysqli_query($conn, "INSERT INTO gaji_pokok(id_jenis,id_bulan,nominal)
									VALUES('$idJenisBayar',
										'$j',
										'$dt')");
		}


		if ($query) {
			echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$idJenisBayar&tipe=pokok&sukses';</script>";
		} else {
			echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$idJenisBayar&tipe=pokok&gagal';</script>";
		}
	}

	$sqlEdit = mysqli_query($conn, "SELECT
							jenis_gaji.*,
							tahun_ajaran.nmTahunAjaran
						FROM
							jenis_gaji
						INNER JOIN tahun_ajaran ON jenis_gaji.idTahunAjaran = tahun_ajaran.idTahunAjaran
						WHERE jenis_gaji.id='$_GET[jenis]'");
	$record = mysqli_fetch_array($sqlEdit);
?>
	<div class="col-md-12">
		<div class="box box-solid box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Menambah Tarif / Gaji Pokok</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form method="post" action="" class="form-horizontal">
					<div class="col-sm-5">
						<div class="box box-solid box-danger">
							<div class="box-header">
								<h3 class="box-title">Data Gaji</h3>
							</div>
							<div class="box-body">
								<input type="hidden" name="id" value="<?php echo $record['id']; ?>">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Nam Gaji</label>
									<div class="col-sm-8">
										<input type="text" name="nmGaji" class="form-control" value="<?php echo $record['nmGaji']; ?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Tahun</label>
									<div class="col-sm-8">
										<input type="text" name="nmTahunAjaran" class="form-control" value="<?php echo $record['nmTahunAjaran']; ?>" readonly>
									</div>
								</div>
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Tipe Gaji</label>
									<div class="col-sm-8">
										<input type="text" name="tipeGaji" class="form-control" value="<?php echo $record['jenis']; ?>" readonly>
									</div>
								</div>

							</div>
						</div>
					</div>
					<div class="col-sm-7">
						<div class="box box-solid box-warning">
							<div class="box-header">
								<h3 class="box-title">Tarif Setiap Bulan Sama</h3>
							</div>
							<div class="box-body">
								<div class="form-group">
									<label for="" class="col-sm-4 control-label">Tarif Bulanan (Rp.)</label>
									<div class="col-sm-4">
										<input type="text" id="allTarif" name="allTarif" class="form-control harusAngka">
									</div>
									<div class="col-sm-4">
										<i>Masukkan Nilai dan Tekan Enter</i>
									</div>
								</div>
							</div>
						</div>
						<div class="box box-solid box-success">
							<div class="box-header">
								<h3 class="box-title">Tarif Setiap Bulan Tidak Sama</h3>
							</div>
							<div class="table-responsive">
								<table class="table-responsive">
									<tr>
										<td>Juli</td>
										<td><input type="text" id="n7" name="n7" class="form-control harusAngka"></td>
										<td>Januari</td>
										<td><input type="text" id="n1" name="n1" class="form-control harusAngka"></td>
									</tr>
									<tr>
										<td>Agustus</td>
										<td><input type="text" id="n8" name="n8" class="form-control harusAngka"></td>
										<td>Februari</td>
										<td><input type="text" id="n2" name="n2" class="form-control harusAngka"></td>
									</tr>
									<tr>
										<td>September</td>
										<td><input type="text" id="n9" name="n9" class="form-control harusAngka"></td>
										<td>Maret</td>
										<td><input type="text" id="n3" name="n3" class="form-control harusAngka"></td>
									</tr>
									<tr>
										<td>Oktober</td>
										<td><input type="text" id="n10" name="n10" class="form-control harusAngka"></td>
										<td>April</td>
										<td><input type="text" id="n4" name="n4" class="form-control harusAngka"></td>
									</tr>
									<tr>
										<td>November</td>
										<td><input type="text" id="n11" name="n11" class="form-control harusAngka"></td>
										<td>Mei</td>
										<td><input type="text" id="n5" name="n5" class="form-control harusAngka"></td>
									</tr>
									<tr>
										<td>Desember</td>
										<td><input type="text" id="n12" name="n12" class="form-control harusAngka"></td>
										<td>Juni</td>
										<td><input type="text" id="n6" name="n6" class="form-control harusAngka"></td>
									</tr>
								</table>
							</div>
							<div class="box-footer">
								<input type="submit" name="simpan" value="Simpan Tarif" class="btn btn-success">
								<a href="index.php?view=set_tarif_gaji" class="btn btn-default">Cancel</a>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php
} elseif ($_GET['act'] == 'hapus') {
	$idjenis = $_GET['jenis'];
	$hapus = mysqli_query($conn, "DELETE FROM gaji_pokok WHERE id_gaji_pokok='$_GET[id]'");
	if ($hapus) {
		echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$idjenis&tipe=pokok&sukseshapus';</script>";
	} else {
		echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$idjenis&tipe=pokok&gagalhapus';</script>";
	}
} elseif ($_GET['act'] == 'hapussekelas') {
	$idjenis = $_GET['jenis'];
	$hapus = mysqli_query($conn, "DELETE FROM tagihan_bebas WHERE id='$idjenis' AND idKelas='$_GET[kelas]' AND statusBayar='0'");
	if ($hapus) {
		echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$idjenis&tipe=pokok&sukseshapus';</script>";
	} else {
		echo "<script>document.location='index.php?view=set_tarif_gaji&jenis=$idjenis&tipe=pokok&gagalhapus';</script>";
	}
}
?>