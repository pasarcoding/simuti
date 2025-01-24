<?php
//require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;

include 'config/rupiah.php';

$idTahunAjaran = $_GET[idTahunAjaran];
$jenis = $_GET[jenis];

if ($_GET[act] == '') {

	if ((isset($_GET['jenis']) and ($idTahunAjaran != 'all') and ($jenis != 'all'))) {
		$tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.jenis='$_GET[jenis]' AND siswa_afirmasi.idTahunAjaran='$_GET[idTahunAjaran]'  ORDER BY siswa.idSiswa ASC");
	} else if (($idTahunAjaran != 'all') and ($jenis == 'all')) {
		$tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.idTahunAjaran='$_GET[idTahunAjaran]'  ORDER BY siswa.idSiswa ASC");
	} else if (($idTahunAjaran == 'all') and ($jenis != 'all')) {
		$tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.jenis='$_GET[jenis]' ORDER BY siswa.idSiswa ASC");
	} else if (($idTahunAjaran == '') and ($jenis == '')) {
		$tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			ORDER BY siswa.idSiswa ASC");
	} else {
		$tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			ORDER BY siswa.idSiswa ASC");
	}


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
?>
	<div class="col-xs-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Data Siswa </h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<?php
				if (isset($_GET['sukses'])) {
					echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
					</div>";
				} elseif (isset($_GET['gagal'])) {
					echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
					</div>";
				}
				?>
				<form method="GET" action="" class="form-horizontal">
					<input type="hidden" name="view" value="siswa_afirmasi" />
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<select id="kelas" name="idTahunAjaran" class="form-control">
										<option value="all" selected> - Semua Tahun Ajaran - </option>
										<?php
										$sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
										while ($t = mysqli_fetch_array($sqltahun)) {
											$selected = ($t['idTahunAjaran'] == $idTahunAjaran) ? ' selected="selected"' : "";

											echo '<option value="' . $t['idTahunAjaran'] . '" ' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
										}
										?>
									</select>
								</td>
								<td>
									<select class="form-control" name="jenis">
										<option value="all">- Semua Jenis -</option>
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM jns_afirmasi ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $jenis) ? ' selected="selected"' : "";
											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['nmJenis'] . "</option>";
										}
										?>
									</select>
								</td>
								<td width="100">
									<input type="submit" name="tampil" value="Tampilkan" class="btn btn-success pull-right">
								</td>
								<td>
									<span class="pull-right">

										<a class='btn btn-primary' href='index.php?view=siswa_afirmasi&act=tambah'>Tambahkan Data</a>
									</span>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
				<div class="table-responsive">
					<table id="example1" class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>No</th>
								<th>NISN</th>
								<th>Nama Siswa</th>
								<th>Jenis Kelamin</th>
								<th>Kelas</th>
								<th>Nama Ortu</th>
								<th>No.Hp Ortu</th>
								<th>Alamat</th>
								<th>Keterangan</th>
								<th>Aksi</th>

							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							while ($r = mysqli_fetch_array($tampil)) {
								echo "<tr><td>$no</td>
							
								<td>" . $r['nisnSiswa'] . "</td>
								<td>" . $r['nmSiswa'] . "</td>
								<td>" . $r['jkSiswa'] . "</td>
								<td>" . $r['nmKelas'] . "</td>
								<td>" . $r['nama_ayah'] . "</td>
								<td>" . $r['noHpOrtu'] . "</td>
								<td>" . $r['alamatOrtu'] . "</td>
								<td>" . $r['keterangan'] . "</td>																
								<td><center>
								<a class='btn btn-success btn-xs' title='Edit Data' href='?view=siswa_afirmasi&act=edit&id=$r[idAfirmasi]'><span class='glyphicon glyphicon-edit'></span></a>
								<a class='btn btn-danger btn-xs' title='Delete Data' href='?view=siswa_afirmasi&hapus&id=$r[idAfirmasi]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini? (Menghapus siswa berarti juga akan menghapus tagihan dan pembayaran!)')\"><span class='glyphicon glyphicon-remove'></span></a>
								
								
								</center></td>
								";
								echo "</tr>";
								$no++;
							}
							if (isset($_GET[hapus])) {
								mysqli_query($conn, "DELETE FROM siswa_afirmasi where idAfirmasi='$_GET[id]'");
								echo "<script>document.location='index.php?view=siswa_afirmasi';</script>";
							}

							?>
						</tbody>
					</table>
				</div>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
<?php
} elseif ($_GET[act] == 'edit') {
	if (isset($_POST[update])) {

		$query = mysqli_query($conn, "UPDATE siswa_afirmasi SET
                idTahunAjaran = '$_POST[idTahunAjaran]',
                jenis = '$_POST[jenis]',
                keterangan = '$_POST[keterangan]'
            WHERE idAfirmasi = '$_POST[idAfirmasi]'");


		if ($query) {
			echo "<script>document.location='index.php?view=siswa_afirmasi&act=edit&id=$_POST[idAfirmasi]&sukses';</script>";
		} else {
			echo "<script>document.location='index.php?view=siswa_afirmasi&act=edit&id=$_POST[idAfirmasi]&gagal';</script>";
		}
	}
	$edit = mysqli_query($conn, "SELECT * FROM siswa_afirmasi	INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa  where siswa_afirmasi.idAfirmasi='$_GET[id]'");
	$record = mysqli_fetch_array($edit);


?>
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Edit Data Siswa </h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<?php
				if (isset($_GET['sukses'])) {
					echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
					</div>";
				} elseif (isset($_GET['gagal'])) {
					echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
					</div>";
				}
				?>
				<form method="post" action="" class="form-horizontal">
					<input type="hidden" name="idAfirmasi" value="<?php echo $record['idAfirmasi']; ?>">
					<center><b>
							<h3> <?= $record['nmSiswa'] ?></h3>
						</b></center>
					<hr>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tahun Ajaran</label>
								<div class="col-sm-8">
									<select name="idTahunAjaran" class="form-control">
										<?php
										$sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
										while ($t = mysqli_fetch_array($sqltahun)) {
											$selected = ($t['idTahunAjaran'] == $record['idTahunAjaran']) ? ' selected="selected"' : "";

											echo '<option value="' . $t['idTahunAjaran'] . '" ' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Afirmasi</label>
								<div class="col-sm-8">
									<select name="jenis" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM jns_afirmasi ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $record['jenis']) ? ' selected="selected"' : "";
											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['nmJenis'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="col-sm-6">

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Keterangan</label>
								<div class="col-sm-8">
									<input type="text" name="keterangan" class="form-control" value="<?= $record['keterangan'] ?>" placeholder="">
								</div>
							</div>

						</div>
					</div>

					<div class="form-group">
						<label for="" class="col-sm-3 control-label"></label>
						<div class="col-sm-8">
							<input type="submit" name="update" value="Update" class="btn btn-success">
							<a href="index.php?view=siswa_afirmasi" class="btn btn-default">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php
} elseif ($_GET[act] == 'tambah') {
	if (isset($_POST[tambah])) {
		$query = mysqli_query($conn, "INSERT INTO siswa_afirmasi(
                idSiswa, 
                idTahunAjaran, 
                jenis, 
                keterangan
            ) 
            VALUES(
                '$_POST[idSiswa]',
                '$_POST[idTahunAjaran]',
                '$_POST[jenis]',
                '$_POST[keterangan]'
            )");


		if ($query) {
			echo "<script>document.location='index.php?view=siswa_afirmasi&sukses';</script>";
		} else {
			echo "<script>document.location='index.php?view=siswa_afirmasi&gagal';</script>";
		}
	}
?>
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Tambah Data Siswa Afirmasi</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form method="POST" action="" class="form-horizontal">

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tahun Ajaran</label>
								<div class="col-sm-8">
									<select name="idTahunAjaran" class="form-control">
										<?php
										$sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
										while ($t = mysqli_fetch_array($sqltahun)) {
											$selected = ($t['idTahunAjaran'] == $record['idTahunAjaran']) ? ' selected="selected"' : "";

											echo '<option value="' . $t['idTahunAjaran'] . '" ' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Afirmasi</label>
								<div class="col-sm-8">
									<select name="jenis" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM jns_afirmasi ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $record['jenis']) ? ' selected="selected"' : "";
											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['nmJenis'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
						</div>

						<div class="col-sm-6">

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pilih Siswa</label>
								<div class="col-sm-8">
									<select name="idSiswa" data-live-search="true" class="form-control selectpicker">
										<option value="">- Pilih Siswa -</option>
										<?php
										$sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa");
										while ($s = mysqli_fetch_array($sqlSiswa)) {
											echo "<option value='$s[idSiswa]'>$s[nisSiswa] - $s[nmSiswa]</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Keterangan</label>
								<div class="col-sm-8">
									<input type="text" name="keterangan" class="form-control" placeholder="">
								</div>
							</div>

						</div>
					</div>


					<div class="form-group">
						<label for="" class="col-sm-3 control-label"></label>
						<div class="col-sm-8">
							<input type="submit" name="tambah" value="Simpan" class="btn btn-success">
							<a href="index.php?view=siswa_afirmasi" class="btn btn-default">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
<?php
} elseif ($_GET[act] == 'import') {
?>
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Import Data Siswa</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<?php
				if (isset($_GET['sukses'])) {
					echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
                          </div>";
				} elseif (isset($_GET['gagal'])) {
					echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
                          </div>";
				}
				?>
				<?php
				//jika tombol import ditekan
				if (isset($_POST['prosesimport'])) {
					$target = "temp/" . uniqid() . '.xlsx';
					move_uploaded_file($_FILES['fileSiswa']['tmp_name'], $target);
					$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
					$excel = $reader->load($target);
					$excelData = $excel->getActiveSheet()->toArray();
					unset($excelData[0]);
					foreach ($excelData as $item) {
						// setelah data dibaca, masukkan ke tabel pegawai sql
						$hasil = mysqli_query($conn, "INSERT INTO siswa(nisSiswa,nisnSiswa,nmSiswa,jkSiswa,agamaSiswa,idKelas,statusSiswa,username,password,level,nmOrtu,alamatOrtu,noHpOrtu,noHpSis,saldo) 
							VALUES('$item[0]','$item[1]','$item[2]','$item[3]','$item[4]','$item[5]','Aktif','$item[6]','$item[7]','siswa','$item[8]','$item[9]','$item[10]','$item[11]','0')");
					}
					if (!$hasil) {
						//          jika import gagal
						echo "<script>document.location='index.php?view=siswa_afirmasi&act=import&gagal';</script>";
					} else {
						//          jika impor berhasil
						echo "<script>document.location='index.php?view=siswa_afirmasi&act=import&sukses';</script>";
					}

					//    hapus file xls yang udah dibaca
					unlink($target);
				}
				?>

				<form method="POST" action="" class="form-horizontal" onSubmit="return validateForm()" enctype="multipart/form-data">
					<div class="form-group">
						<label for="" class="col-sm-8 control-label">Download Format Data Siswa</label>
						<div class="col-sm-8">
							<a href="./files/datasiswa.xlsx" class="btn btn-info">Datasiswa.xlsx</a>
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-8 control-label">Pilih File Excel (.xlsx)</label>
						<div class="col-sm-8">
							<input type="file" name="fileSiswa" class="form-control" id="fileSiswa" placeholder="">
						</div>
					</div>
					<div class="form-group">
						<label for="" class="col-sm-8 control-label"></label>
						<div class="col-sm-8">
							<input type="submit" name="prosesimport" value="Proses Import" class="btn btn-success">
							<a href="index.php?view=siswa_afirmasi" class="btn btn-default">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		//    validasi form (hanya file .xls yang diijinkan)
		function validateForm() {
			function hasExtension(inputID, exts) {
				var fileName = document.getElementById(inputID).value;
				return (new RegExp(' (' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
			}

		}
	</script>
<?php
}
?>
<script type="text/javascript">
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$('#target').attr('src', e.target.result);
			};

			reader.readAsDataURL(input.files[0]);
		}
	}

	$("#foto").change(function() {
		readURL(this);
	});
</script>