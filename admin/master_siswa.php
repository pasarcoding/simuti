<?php
//require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;

include 'config/rupiah.php';

if ($_GET[act] == '') {
	if (isset($_GET['kelas']) && $_GET['kelas'] != "") {
		if (isset($_GET['status']) && $_GET['status'] != "") {
			$tampil = mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idKelas='$_GET[kelas]' AND statusSiswa='$_GET[status]' ORDER BY idKelas ASC");
		} else {
			$tampil = mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idKelas='$_GET[kelas]' ORDER BY idKelas ASC");
		}
		$kelas = $_GET['kelas'];
	} else {
		$tampil = mysqli_query($conn, "SELECT * FROM view_detil_siswa ORDER BY idKelas ASC");
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
					<input type="hidden" name="view" value="siswa" />
					<table class="table table-striped">
						<tbody>
							<tr>
								<td>
									<select id="kelas" name="kelas" class="form-control">
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
									<select class="form-control" name="status">
										<option value="">- Semua Status -</option>
										<option value="Aktif">Aktif</option>
										<option value="Non Aktif">Non Aktif</option>
										<option value="Drop Out">Drop Out</option>
										<option value="Pindah">Pindah</option>
										<option value="Lulus">Lulus</option>
										<option value="Calon Siswa">Calon Siswa</option>
									</select>
								</td>
								<td width="100">
									<input type="submit" name="tampil" value="Tampilkan" class="btn btn-success pull-right">
								</td>
								<td>
									<span class="pull-right">
										<a class="btn btn-danger" href="index.php?view=siswa&act=import">
											<i class="fa fa-file-excel-o"></i> Import Data Siswa
										</a>
										<a class='btn btn-primary' href='index.php?view=siswa&act=tambah'>Tambahkan Data</a>
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
								<th>No.Hp Siswa</th>
								<th>Saldo</th>
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
								<td>" . $r['nmOrtu'] . "</td>
								<td>" . $r['noHpOrtu'] . "</td>
								<td>" . $r['noHpSis'] . "</td>																
								<td>Rp." . rupiah($r['saldo']) . "</td>
								<td><center>
								<a class='btn btn-success btn-xs' title='Edit Data' href='?view=siswa&act=edit&id=$r[idSiswa]'><span class='glyphicon glyphicon-edit'></span></a>
								<a class='btn btn-danger btn-xs' title='Delete Data' href='?view=siswa&hapus&id=$r[idSiswa]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini? (Menghapus siswa berarti juga akan menghapus tagihan dan pembayaran!)')\"><span class='glyphicon glyphicon-remove'></span></a>
								
								
								</center></td>
								";
								echo "</tr>";
								$no++;
							}
							if (isset($_GET[hapus])) {
								mysqli_query($conn, "DELETE FROM siswa where idSiswa='$_GET[id]'");
								echo "<script>document.location='index.php?view=siswa';</script>";
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

		$query = mysqli_query($conn, "UPDATE siswa SET
                nisSiswa = '$_POST[nisSiswa]',
                nisnSiswa = '$_POST[nisnSiswa]',
                nmSiswa = '$_POST[nmSiswa]',
                jkSiswa = '$_POST[jkSiswa]',
                tglLahirSiswa = '$_POST[tglLahir]',
                level = '$_POST[level]',
                agama = '$_POST[agamaSiswa]',
                idKelas = '$_POST[idKelas]',
                statusSiswa='$_POST[statusSiswa]',
                alamatOrtu = '$_POST[alamat]',
                noHpOrtu = '$_POST[noHp]',
                noHpSis = '$_POST[noHpsis]',
                username = '$_POST[username]',
                password = '$_POST[password]',
                asal_sekolah = '$_POST[asal_sekolah]',
                nik_siswa = '$_POST[nik_siswa]',
                tempat_lahir = '$_POST[tempat_lahir]',
                jml_saudara = '$_POST[jml_saudara]',
                urutan_anak = '$_POST[urutan_anak]',
                no_kk = '$_POST[no_kk]',
                nik_ayah = '$_POST[nik_ayah]',
                nama_ayah = '$_POST[nama_ayah]',
                pendidikan_terakhir_ayah = '$_POST[pendidikan_terakhir_ayah]',
                pekerjaan_ayah = '$_POST[pekerjaan_ayah]',
                penghasilan_ayah = '$_POST[penghasilan_ayah]',
                nama_ibu = '$_POST[nama_ibu]',
                pendidikan_terakhir_ibu = '$_POST[pendidikan_terakhir_ibu]',
                pekerjaan_ibu = '$_POST[pekerjaan_ibu]',
                penghasilan_ibu = '$_POST[penghasilan_ibu]',
                rt_rw = '$_POST[rt_rw]',
                kelurahan = '$_POST[kelurahan]',
                kecamatan = '$_POST[kecamatan]',
                kab_kota = '$_POST[kab_kota]',
                provinsi = '$_POST[provinsi]'
            WHERE idSiswa = '$_POST[id]'");


		if ($query) {
			echo "<script>document.location='index.php?view=siswa&act=edit&id=$_POST[id]&sukses';</script>";
		} else {
			echo "<script>document.location='index.php?view=siswa&act=edit&id=$_POST[id]&gagal';</script>";
		}
	}
	$edit = mysqli_query($conn, "SELECT * FROM view_detil_siswa where idSiswa='$_GET[id]'");
	$record = mysqli_fetch_array($edit);


?>
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Edit Data Siswa</h3>
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
					<input type="hidden" name="id" value="<?php echo $record['idSiswa']; ?>">

					<center><b>
							<h3>Data Diri Siswa</h3>
						</b></center>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NIK Siswa</label>
								<div class="col-sm-8">
									<input type="text" name="nik_siswa" class="form-control" value="<?php echo $record['nik_siswa']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor KK</label>
								<div class="col-sm-8">
									<input type="text" name="no_kk" class="form-control" value="<?php echo $record['no_kk']; ?>" placeholder="" required>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NIS</label>
								<div class="col-sm-8">
									<input type="text" name="nisSiswa" class="form-control" value="<?php echo $record['nisSiswa']; ?>" placeholder="" required>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NISN</label>
								<div class="col-sm-8">
									<input type="text" name="nisnSiswa" class="form-control" value="<?php echo $record['nisnSiswa']; ?>" placeholder="" required>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Siswa</label>
								<div class="col-sm-8">
									<input type="text" name="nmSiswa" class="form-control" value="<?php echo $record['nmSiswa']; ?>" placeholder="" required>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Kelamin</label>
								<div class="col-sm-8">
									<select class="form-control" name="jkSiswa">
										<option value="<?php echo $record['jkSiswa']; ?>"><?php echo $record['jkSiswa']; ?></option>
										<option value="L">L</option>
										<option value="P">P</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tempat Lahir</label>
								<div class="col-sm-8">
									<input type="text" name="tempat_lahir" class="form-control" value="<?php echo $record['tempat_lahir']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Lahir </label>
								<div class="col-sm-8">
									<input type="text" name="tglLahir" class="form-control pull-right date-picker" value="<?php echo $record['tglLahirSiswa']; ?>" value="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Agama</label>
								<div class="col-sm-8">
									<select class="form-control" name="agamaSiswa">
										<option value="<?php echo $record['agama']; ?>"><?php echo $record['agama']; ?></option>
										<option value="Islam">Islam</option>
										<option value="Katolik">Katolik</option>
										<option value="Protestan">Protestan</option>
										<option value="Hindu">Hindu</option>
										<option value="Budha">Budha</option>
									</select>
								</div>
							</div>

						</div>


						<div class="col-sm-6">

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No.Hp Siswa</label>
								<div class="col-sm-8">
									<input type="number" name="noHpsis" class="form-control" value="<?php echo $record['noHpSis']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kelas</label>
								<div class="col-sm-8">
									<select name="idKelas" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM kelas_siswa ORDER BY idKelas ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['idKelas'] == $record['idKelas']) ? ' selected="selected"' : "";

											echo '<option value="' . $k['idKelas'] . '" ' . $selected . '>' . $k['nmKelas'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Level</label>
								<div class="col-sm-8">
									<select class="form-control" name="level">
										<option value="<?php echo $record['level']; ?>"><?php echo $record['level']; ?></option>
										<option value="siswa">Siswa</option>
										<option value="ketuakelas">Ketua Kelas</option>

									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Asal Sekolah</label>
								<div class="col-sm-8">
									<input type="text" name="asal_sekolah" class="form-control" value="<?php echo $record['asal_sekolah']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jumlah Saudara</label>
								<div class="col-sm-8">
									<input type="number" name="jml_saudara" class="form-control" value="<?php echo $record['jml_saudara']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Urutan Anak</label>
								<div class="col-sm-8">
									<input type="number" name="urutan_anak" class="form-control" value="<?php echo $record['urutan_anak']; ?>" placeholder="">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Username</label>
								<div class="col-sm-8">
									<input type="text" name="username" class="form-control" value="<?php echo $record['username']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Password</label>
								<div class="col-sm-8">
									<input type="text" name="password" class="form-control" value="<?php echo $record['password']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Status Siswa</label>
								<div class="col-sm-8">
									<select class="form-control" name="statusSiswa">
										<option value="<?= $record['statusSiswa'] ?>"> <?= $record['statusSiswa'] ?> </option>
										<option value="Aktif">Aktif</option>
										<option value="Non Aktif">Non Aktif</option>
										<option value="Drop Out">Drop Out</option>
										<option value="Pindah">Pindah</option>
										<option value="Lulus">Lulus</option>
										<option value="Calon Siswa">Calon Siswa</option>

									</select>
								</div>
							</div>


						</div>
					</div>
					<hr>
					<center>
						<h3>Data Orang Tua</h3>
					</center>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NIK Ayah</label>
								<div class="col-sm-8">
									<input type="text" name="nik_ayah" class="form-control" value="<?php echo $record['nik_ayah']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Ayah</label>
								<div class="col-sm-8">
									<input type="text" name="nama_ayah" class="form-control" value="<?php echo $record['nama_ayah']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No.Hp Ortu</label>
								<div class="col-sm-8">
									<input type="number" name="noHp" class="form-control" value="<?php echo $record['noHpOrtu']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Terakhir Ayah</label>
								<div class="col-sm-8">
									<select name="pendidikan_terakhir_ayah" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pendidikan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $record['pendidikan_terakhir_ayah']) ? ' selected="selected"' : "";
											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['tingkat_pendidikan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan Ayah</label>
								<div class="col-sm-8">
									<select name="pekerjaan_ayah" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pekerjaan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $record['pekerjaan_ayah']) ? ' selected="selected"' : "";
											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['pekerjaan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Penghasilan Ayah</label>
								<div class="col-sm-8">
									<select name="penghasilan_ayah" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_penghasilan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $record['penghasilan_ayah']) ? ' selected="selected"' : "";

											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['kategori'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>

							<hr>
							<center>
								<h3>Data Alamat</h3>
							</center>
							<hr>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Alamat</label>
								<div class="col-sm-8">
									<input type="text" name="alamat" class="form-control" value="<?php echo $record['alamatOrtu']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT/RW</label>
								<div class="col-sm-8">
									<input type="text" name="rt_rw" class="form-control" value="<?php echo $record['rt_rw']; ?>" placeholder="">
								</div>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Ibu</label>
								<div class="col-sm-8">
									<input type="text" name="nama_ibu" class="form-control" value="<?php echo $record['nama_ibu']; ?>" placeholder="">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Terakhir Ibu</label>
								<div class="col-sm-8">
									<select name="pendidikan_terakhir_ibu" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pendidikan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $record['pendidikan_terakhir_ibu']) ? ' selected="selected"' : "";
											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['tingkat_pendidikan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan Ibu</label>
								<div class="col-sm-8">
									<select name="pekerjaan_ibu" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pekerjaan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											$selected = ($k['id'] == $record['pekerjaan_ibu']) ? ' selected="selected"' : "";
											echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['pekerjaan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Penghasilan Ibu</label>
								<div class="col-sm-8">
									<select name="penghasilan_ibu" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_penghasilan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {

											$selected = ($k['id'] == $record['penghasilan_ibu']) ? ' selected="selected"' : "";

											echo '<option value="' . $k['id'] . '" ' . $selected . '>' . $k['kategori'] . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kelurahan</label>
								<div class="col-sm-8">
									<input type="text" name="kelurahan" class="form-control" value="<?php echo $record['kelurahan']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kecamatan</label>
								<div class="col-sm-8">
									<input type="text" name="kecamatan" class="form-control" value="<?php echo $record['kecamatan']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kab/Kota</label>
								<div class="col-sm-8">
									<input type="text" name="kab_kota" class="form-control" value="<?php echo $record['kab_kota']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Provinsi</label>
								<div class="col-sm-8">
									<input type="text" name="provinsi" class="form-control" value="<?php echo $record['provinsi']; ?>" placeholder="">
								</div>
							</div>

						</div>
					</div>




					<div class="form-group">
						<label for="" class="col-sm-3 control-label"></label>
						<div class="col-sm-8">
							<input type="submit" name="update" value="Update" class="btn btn-success">
							<a href="index.php?view=siswa" class="btn btn-default">Cancel</a>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

<?php
} elseif ($_GET[act] == 'tambah') {
	if (isset($_POST[tambah])) {
		$query = mysqli_query($conn, "INSERT INTO siswa(
                nisSiswa, 
                nisnSiswa, 
                nmSiswa, 
                jkSiswa, 
                tglLahirSiswa, 
                level, 
                agama, 
                idKelas, 
                statusSiswa,
                alamatOrtu, 
                noHpOrtu, 
                noHpSis, 
                username, 
                password, 
                saldo,
                asal_sekolah,
                nik_siswa,
                tempat_lahir,
                jml_saudara,
                urutan_anak,
                no_kk,
                nik_ayah,
                nama_ayah,
                pendidikan_terakhir_ayah,
                pekerjaan_ayah,
                penghasilan_ayah,
                nama_ibu,
                pendidikan_terakhir_ibu,
                pekerjaan_ibu,
                penghasilan_ibu,
                rt_rw,
                kelurahan,
                kecamatan,
                kab_kota,
                provinsi
            ) 
            VALUES(
                '$_POST[nisSiswa]',
                '$_POST[nisnSiswa]',
                '$_POST[nmSiswa]',
                '$_POST[jkSiswa]',
                '$_POST[tglLahir]',
                '$_POST[level]',
                '$_POST[agamaSiswa]',
                '$_POST[idKelas]',
                '$_POST[statusSiswa]',
                '$_POST[alamat]',
                '$_POST[noHp]',
                '$_POST[noHpsis]',
                '$_POST[username]',
                '$_POST[password]',
                '0',
                '$_POST[asal_sekolah]',
                '$_POST[nik_siswa]',
                '$_POST[tempat_lahir]',
                '$_POST[jml_saudara]',
                '$_POST[urutan_anak]',
                '$_POST[no_kk]',
                '$_POST[nik_ayah]',
                '$_POST[nama_ayah]',
                '$_POST[pendidikan_terakhir_ayah]',
                '$_POST[pekerjaan_ayah]',
                '$_POST[penghasilan_ayah]',
                '$_POST[nama_ibu]',
                '$_POST[pendidikan_terakhir_ibu]',
                '$_POST[pekerjaan_ibu]',
                '$_POST[penghasilan_ibu]',
                '$_POST[rt_rw]',
                '$_POST[kelurahan]',
                '$_POST[kecamatan]',
                '$_POST[kab_kota]',
                '$_POST[provinsi]'
            )");


		// $query = mysqli_query($conn,"INSERT INTO siswa (nisSiswa,nisnSiswa,nmSiswa,jkSiswa,level,agamaSiswa,idKelas,alamatOrtu,noHpOrtu,nmOrtu,username,password,saldo) 
		//                                   VALUES('90','90','ok','L','siswa','Islam','12','ind','628','opa','tes','tes','0')");

		if ($query) {
			echo "<script>document.location='index.php?view=siswa&sukses';</script>";
		} else {
			echo "<script>document.location='index.php?view=siswa&gagal';</script>";
		}
	}
?>
	<div class="col-md-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"> Tambah Data Siswa</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form method="POST" action="" class="form-horizontal">
					<center><b>
							<h3>Data Diri Siswa</h3>
						</b></center>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NIK Siswa</label>
								<div class="col-sm-8">
									<input type="text" name="nik_siswa" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nomor KK</label>
								<div class="col-sm-8">
									<input type="text" name="no_kk" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NIS</label>
								<div class="col-sm-8">
									<input type="text" name="nisSiswa" class="form-control" id="" placeholder="" required>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NISN</label>
								<div class="col-sm-8">
									<input type="text" name="nisnSiswa" class="form-control" id="" placeholder="Kolom ini otomatis akan menjadi  nomor  rekening di menu tabungan">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Siswa</label>
								<div class="col-sm-8">
									<input type="text" name="nmSiswa" class="form-control" id="" placeholder="" required>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jenis Kelamin</label>
								<div class="col-sm-8">
									<select class="form-control" name="jkSiswa">
										<option value="L">L</option>
										<option value="P">P</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tempat Lahir</label>
								<div class="col-sm-8">
									<input type="text" name="tempat_lahir" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal Lahir </label>
								<div class="col-sm-8">
									<input type="text" name="tglLahir" class="form-control pull-right date-picker" value="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Agama</label>
								<div class="col-sm-8">
									<select class="form-control" name="agamaSiswa">
										<option value="Islam">Islam</option>
										<option value="Katolik">Katolik</option>
										<option value="Protestan">Protestan</option>
										<option value="Hindu">Hindu</option>
										<option value="Budha">Budha</option>
									</select>
								</div>
							</div>

						</div>


						<div class="col-sm-6">

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No.Hp Siswa</label>
								<div class="col-sm-8">
									<input type="number" name="noHpsis" class="form-control" value="<?php echo $record['noHpSis']; ?>" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kelas</label>
								<div class="col-sm-8">
									<select name="idKelas" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM kelas_siswa ORDER BY idKelas ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											echo "<option value=" . $k['idKelas'] . ">" . $k['nmKelas'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Level</label>
								<div class="col-sm-8">
									<select class="form-control" name="level">
										<option value="siswa">Siswa</option>
										<option value="ketuakelas">Ketua Kelas</option>

									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Asal Sekolah</label>
								<div class="col-sm-8">
									<input type="text" name="asal_sekolah" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Jumlah Saudara</label>
								<div class="col-sm-8">
									<input type="number" name="jml_saudara" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Urutan Anak</label>
								<div class="col-sm-8">
									<input type="number" name="urutan_anak" class="form-control" placeholder="">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Username</label>
								<div class="col-sm-8">
									<input type="text" name="username" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Password</label>
								<div class="col-sm-8">
									<input type="text" name="password" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Status Siswa</label>
								<div class="col-sm-8">
									<select class="form-control" name="statusSiswa">
										<option value="<?= $record['statusSiswa'] ?>"> <?= $record['statusSiswa'] ?> </option>
										<option value="Aktif">Aktif</option>
										<option value="Non Aktif">Non Aktif</option>
										<option value="Drop Out">Drop Out</option>
										<option value="Pindah">Pindah</option>
										<option value="Lulus">Lulus</option>
										<option value="Calon Siswa">Calon Siswa</option>

									</select>
								</div>
							</div>

						</div>
					</div>
					<hr>
					<center>
						<h3>Data Orang Tua</h3>
					</center>
					<hr>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">NIK Ayah</label>
								<div class="col-sm-8">
									<input type="text" name="nik_ayah" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Ayah</label>
								<div class="col-sm-8">
									<input type="text" name="nama_ayah" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">No.Hp Ortu</label>
								<div class="col-sm-8">
									<input type="number" name="noHp" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Terakhir Ayah</label>
								<div class="col-sm-8">
									<select name="pendidikan_terakhir_ayah" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pendidikan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											echo "<option value=" . $k['id'] . ">" . $k['tingkat_pendidikan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan Ayah</label>
								<div class="col-sm-8">
									<select name="pekerjaan_ayah" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pekerjaan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											echo "<option value=" . $k['id'] . ">" . $k['pekerjaan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Penghasilan Ayah</label>
								<div class="col-sm-8">
									<select name="penghasilan_ayah" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_penghasilan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											echo "<option value=" . $k['id'] . ">" . $k['kategori'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>


							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Alamat</label>
								<div class="col-sm-8">
									<input type="text" name="alamat" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">RT/RW</label>
								<div class="col-sm-8">
									<input type="text" name="rt_rw" class="form-control" placeholder="">
								</div>
							</div>

						</div>
						<div class="col-sm-6">

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Nama Ibu</label>
								<div class="col-sm-8">
									<input type="text" name="nama_ibu" class="form-control" placeholder="">
								</div>
							</div>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pendidikan Terakhir Ibu</label>
								<div class="col-sm-8">
									<select name="pendidikan_terakhir_ibu" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pendidikan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											echo "<option value=" . $k['id'] . ">" . $k['tingkat_pendidikan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Pekerjaan Ibu</label>
								<div class="col-sm-8">
									<select name="pekerjaan_ibu" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_pekerjaan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											echo "<option value=" . $k['id'] . ">" . $k['pekerjaan'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Penghasilan Ibu</label>
								<div class="col-sm-8">
									<select name="penghasilan_ibu" class="form-control">
										<?php
										$sqk = mysqli_query($conn, "SELECT * FROM rb_penghasilan ORDER BY id ASC");
										while ($k = mysqli_fetch_array($sqk)) {
											echo "<option value=" . $k['id'] . ">" . $k['kategori'] . "</option>";
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kelurahan</label>
								<div class="col-sm-8">
									<input type="text" name="kelurahan" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kecamatan</label>
								<div class="col-sm-8">
									<input type="text" name="kecamatan" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kab/Kota</label>
								<div class="col-sm-8">
									<input type="text" name="kab_kota" class="form-control" placeholder="">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Provinsi</label>
								<div class="col-sm-8">
									<input type="text" name="provinsi" class="form-control" placeholder="">
								</div>
							</div>

						</div>
					</div>






					<div class="form-group">
						<label for="" class="col-sm-3 control-label"></label>
						<div class="col-sm-8">
							<input type="submit" name="tambah" value="Simpan" class="btn btn-success">
							<a href="index.php?view=siswa" class="btn btn-default">Cancel</a>
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
						echo "<script>document.location='index.php?view=siswa&act=import&gagal';</script>";
					} else {
						//          jika impor berhasil
						echo "<script>document.location='index.php?view=siswa&act=import&sukses';</script>";
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
							<a href="index.php?view=siswa" class="btn btn-default">Cancel</a>
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