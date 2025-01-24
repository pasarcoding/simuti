<?php
$idTahunAjaran = $_GET[idTahunAjaran];
        $jenis = $_GET[jenis];
?>
<div class="col-xs-12">
	<div class="box box-primary box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Data Siswa Afirmasi</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<form method="GET" action="" class="form-horizontal">
				<div class="form-group">
					<input type="hidden" name="view" value="lapsiswaafirmasi">
					<label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
					<div class="col-sm-2">
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
					</div>
						<label for="" class="col-sm-2 control-label">Jenis</label>
					<div class="col-sm-2">
							<select class="form-control" name="jenis">
									    	<option value="all">- Semua Jenis -</option>
									<?php
								$sqk = mysqli_query($conn, "SELECT * FROM jns_afirmasi ORDER BY id ASC");
								while ($k = mysqli_fetch_array($sqk)) {
									 $selected = ($k['id'] ==$jenis) ? ' selected="selected"' : "";
									echo "<option value=" . $k['id'] . " ' . $selected . '>" . $k['nmJenis'] . "</option>";
								}
								?>
									</select>
					</div>
					<div class="col-sm-2">
						<input type="submit" name="tampil" value="Tampilkan" class="btn btn-success">
					</div>
				</div>
			</form>
		</div><!-- /.box-body -->
	</div><!-- /.box -->
	<?php
	if (isset($_GET['tampil'])) {
	    
        if(($idTahunAjaran != 'all') and ($jenis != 'all')) {
            $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.jenis='$_GET[jenis]' AND siswa_afirmasi.idTahunAjaran='$_GET[idTahunAjaran]'  ORDER BY siswa.idSiswa ASC");
        }else if (($idTahunAjaran != 'all') and ($jenis == 'all') ){
            $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.idTahunAjaran='$_GET[idTahunAjaran]'  ORDER BY siswa.idSiswa ASC");
        }else if (($idTahunAjaran == 'all') and ($jenis != 'all') ){   
             $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.jenis='$_GET[jenis]' ORDER BY siswa.idSiswa ASC");
        }else {
            $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			ORDER BY siswa.idSiswa ASC");
        }   


	
	?>
		<div class="box box-primary">
			<div class="box-body">
				<table id="example1" class="table table-striped">
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
								
								";
								echo "</tr>";
								$no++;
							}
							

							?>
						</tbody>
					</table>
			</div><!-- /.box-body -->
			<div class="box-footer">
				<a class="btn btn-success" target="_blank" href="./excel_laporan_siswa_afirmasi.php?idTahunAjaran=<?php echo $_GET['idTahunAjaran']; ?>&jenis=<?php echo $_GET['jenis']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
				<!--<a class="btn btn-warning" target="_blank" href="./pdf_laporan_siswa_perkelas.php?kelas=<?php //echo $_GET['idKelas']; 
																											?>"><span class="fa fa-file-pdf-o"></span>  Export ke Pdf</a>-->
				<a class="pull-right btn btn-danger" target="_blank" href="./cetak_laporan_siswa_afirmasi.php?idTahunAjaran=<?php echo $_GET['idTahunAjaran']; ?>&jenis=<?php echo $_GET['jenis']; ?>"><span class="glyphicon glyphicon-print"></span> Cetak Laporan</a>
			</div>
		</div><!-- /.box -->
	<?php
	}
	?>
</div>