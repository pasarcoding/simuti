<?php
if (isset($_GET['tampil'])) {
	$kelas = $_GET['idKelas'];
} else {
	$kelas = '';
}
?>
<div class="col-xs-12">
	<div class="box box-primary box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Data Siswa</h3>
							        <a class='pull-right btn btn-success btn-sm' target="_blank" href='cetak_rekap_siswa_perkelas.php'><i class="fa fa-print"></i> Rekap Seluruh Siswa Akif</a>

		</div><!-- /.box-header -->
		<div class="box-body">
			<form method="GET" action="" class="form-horizontal">
				<div class="form-group">
					<input type="hidden" name="view" value="lapsiswa">
					<label for="" class="col-sm-2 control-label">Kelas</label>
					<div class="col-sm-2">
						<select name="idKelas" class="form-control">
						    <option value="all" >Semua Siswa</option>
							<?php
							$sqk = mysqli_query($conn,"SELECT * FROM kelas_siswa ORDER BY idKelas ASC");
							while ($k = mysqli_fetch_array($sqk)) {
								$selected = ($k['idKelas'] == $kelas) ? ' selected="selected"' : "";
								echo "<option value=" . $k['idKelas'] . " " . $selected . ">" . $k['nmKelas'] . "</option>";
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
		// Mengambil data siswa dari database
		if ($_GET['idKelas'] == 'all') {
	    	$sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE statusSiswa='Aktif' ORDER BY nmSiswa ASC");
	    		$sqlGender = mysqli_query($conn, "SELECT jkSiswa, COUNT(*) as total FROM view_detil_siswa WHERE  statusSiswa='Aktif' GROUP BY jkSiswa");
        		$totalLakiLaki = 0;
        		$totalPerempuan = 0;
        
        		while ($gender = mysqli_fetch_array($sqlGender)) {
        			if ($gender['jkSiswa'] == 'L') {
        				$totalLakiLaki = $gender['total'];
        			} elseif ($gender['jkSiswa'] == 'P') {
        				$totalPerempuan = $gender['total'];
        			}
        		}
		}else{
		    	$sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idKelas='$_GET[idKelas]' AND statusSiswa='Aktif' ORDER BY nmSiswa ASC");
		    		$sqlGender = mysqli_query($conn, "SELECT jkSiswa, COUNT(*) as total FROM view_detil_siswa WHERE idKelas='$_GET[idKelas]' AND statusSiswa='Aktif' GROUP BY jkSiswa");
        		$totalLakiLaki = 0;
        		$totalPerempuan = 0;
        
        		while ($gender = mysqli_fetch_array($sqlGender)) {
        			if ($gender['jkSiswa'] == 'L') {
        				$totalLakiLaki = $gender['total'];
        			} elseif ($gender['jkSiswa'] == 'P') {
        				$totalPerempuan = $gender['total'];
        			}
        		}
		}
		// Menghitung jumlah laki-laki dan perempuan
	
	?>
		<div class="box box-primary">
			<div class="box-body">
				<table id="example1" class="table table-striped">
					<thead>
						<tr>
							<th>No.</th>
						<!--	<th>NIS</th> -->
							<th>NISN</th>
							<th>Nama Siswa</th>
							<th>Jenis Kelamin</th>
							<th>Kelas</th>
							<th>Nama Orang Tua</th>
							<th>No. Hp</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						while ($ds = mysqli_fetch_array($sqlSiswa)) {
						    if ($gender['jkSiswa'] == 'L') {
                                $totalLakiLaki = $gender['total'];
                            } elseif ($gender['jkSiswa'] == 'P') {
                                $totalPerempuan = $gender['total'];
                            }
							echo "<tr>
								<td>$no</td>
							<!--	<td>$ds[nisSiswa]</td> -->
								<td>$ds[nisnSiswa]</td>
								<td>$ds[nmSiswa]</td>
								<td>$ds[jkSiswa]</td>
								<td>$ds[nmKelas]</td>
								<td>$ds[nmOrtu]</td>
								<td align='center'>$ds[noHpOrtu]</td>
								</tr>";
							$no++;
						}
						?>
					</tbody>
					<tr>
					    <td colspan="4"><b>Jumlah Siswa Laki - Laki</b></td>
					     <td colspan="4" align="center"><b><?php echo $totalLakiLaki; ?></b></td>
					</tr>
					<tr>
					    <td colspan="4"><b>Jumlah Siswa Perempuan</b></td>
					     <td colspan="4"  align="center"><b><?php echo $totalPerempuan; ?></b></td>
					</tr>
				</table>
			</div><!-- /.box-body -->
			<div class="box-footer">
				<a class="btn btn-success" target="_blank" href="./excel_laporan_siswa_perkelas.php?kelas=<?php echo $_GET['idKelas']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
				<!--<a class="btn btn-warning" target="_blank" href="./pdf_laporan_siswa_perkelas.php?kelas=<?php //echo $_GET['idKelas']; 
																											?>"><span class="fa fa-file-pdf-o"></span>  Export ke Pdf</a>-->
				<a class="pull-right btn btn-danger" target="_blank" href="./cetak_laporan_siswa_perkelas.php?kelas=<?php echo $_GET['idKelas']; ?>"><span class="glyphicon glyphicon-print"></span> Cetak Laporan</a>
			</div>
		</div><!-- /.box -->
	<?php
	}
	?>
</div>