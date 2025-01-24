<?php if ($_GET[act] == '') { ?>
	<div class="col-xs-12">
		<div class="box box-warning ">
			<div class="box-header with-border">
				<h3 class="box-title"> Pemasukan Kas</h3>
				<a class='pull-right btn btn-primary btn-sm' href='?view=jurnalumums&act=tambah'>Tambahkan Data</a>
			</div><!-- /.box-header -->
			<div class="box-body">
				<div class="table-responsive">

					<table id="example1" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Tanggal</th>
								<th>POS Bayar</th>
								<th>Penerimaan</th>
								<th>Keterangan</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$tampil = mysqli_query($conn, "SELECT
							jurnal_umum.*,
							pos_bayar.nmPosBayar,jurnal_umum.penerimaan,jurnal_umum.pengeluaran,jurnal_umum.ket,jurnal_umum.tgl
							FROM
								jurnal_umum
								INNER JOIN pos_bayar ON jurnal_umum.idPosBayar=pos_bayar.idPosBayar  where pengeluaran='0'
							 ORDER BY jurnal_umum.tgl DESC");
							$no = 1;
							while ($r = mysqli_fetch_array($tampil)) {
								echo "<tr><td>$no</td>
                              <td>" . tgl_indo($r['tgl']) . "</td>
							  <td>$r[nmPosBayar]</td>
                              <td>" . buatRp($r['penerimaan']) . "</td>
							  <td>$r[ket]</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=jurnalumums&act=edit&id=$r[id]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=jurnalumums&hapus&id=$r[id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
								echo "</tr>";
								$no++;
							}
							if (isset($_GET[hapus])) {
								mysqli_query($conn, "DELETE FROM jurnal_umum where id='$_GET[id]'");
								echo "<script>document.location='?view=jurnalumum';</script>";
							}

							?>
						</tbody>
					</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
	<?php
} elseif ($_GET[act] == 'edit') {
	if (isset($_POST[update])) {

		$query = mysqli_query($conn, "UPDATE jurnal_umum SET tgl='$_POST[tgl]',idPosBayar='$_POST[idPosBayar]', ket='$_POST[ket]',
							penerimaan='$_POST[penerimaan]' where id='$_POST[id]'");
		if ($query) {
			echo "<script>document.location='?view=jurnalumums&sukses';</script>";
		} else {
			echo "<script>document.location='?view=jurnalumums&gagal';</script>";
		}
	}
	$edit = mysqli_query($conn, "SELECT * FROM jurnal_umum where id='$_GET[id]'");
	$record = mysqli_fetch_array($edit);
	?>
		<div class="col-md-12">
			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"> Edit penerimaan Kas</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<form method="post" action="" class="form-horizontal">
						<input type="hidden" name="id" value="<?php echo $record['id']; ?>">
						<div class="box-header with-border">
							<div class="col-md-3 pull-left">
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
									<input type="text" name="tgl" class="form-control pull-right date-picker" value="<?php echo $record['tgl']; ?>" readonly>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" id="tab_logic">
								<thead>
									<tr>
										<th>No.</th>
										<th>POS Bayar</th>
										<th>penerimaan</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<tr id='addr0'>
										<td width="40px">
											1
										</td>
										<td>
											<select name='idPosBayar' class="form-control">
												<option value="" selected> - Pilih POS Bayar - </option>
												<?php
												$sqk = mysqli_query($conn, "SELECT * FROM pos_bayar ORDER BY idPosBayar ASC");
												while ($k = mysqli_fetch_array($sqk)) {
													$selected = ($k['idPosBayar'] == $record['idPosBayar']) ? ' selected="selected"' : "";
													echo '<option value="' . $k['idPosBayar'] . '" ' . $selected . '>' . $k['nmPosBayar'] . '</option>';
												}
												?>
											</select>
										</td>
										<td width="200px">
											<input type="text" name='penerimaan' value="<?php echo $record['penerimaan']; ?>" class="form-control" onkeypress="return isNumber(event)" required />
										</td>
										<td>
											<input type="text" name='ket' value="<?php echo $record['ket']; ?>" class="form-control" required />
										</td>
									</tr>
									<tr id='addr1'></tr>
								</tbody>
							</table>
						</div>
						<div class="box-footer">
							<div class="pull-right">
								<input type="submit" name="update" value="Update" class="btn btn-success">
								<a href="?view=jurnalumums" class="btn btn-default">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	<?php
} elseif ($_GET[act] == 'tambah') {
	if (isset($_POST['tambah'])) {

		$idPosBayar = $_POST['idPosBayar'];
		$penerimaan = $_POST['penerimaan'];
		$ket = $_POST['ket'];
		$tgl = $_POST['tgl'];

		for ($i = 0; $i < count($idPosBayar); $i++) {
			$query = mysqli_query($conn, "INSERT INTO jurnal_umum(tgl,idPosBayar,ket,penerimaan,pengeluaran) VALUES('$tgl','$idPosBayar[$i]','$ket[$i]','$penerimaan[$i]','0')");
		}

		if ($query) {
			echo "<script>document.location='?view=jurnalumums&sukses';</script>";
		} else {
			echo "<script>document.location='?view=jurnalumums&gagal';</script>";
		}
	}

	?>

		<div class="col-md-12">
			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<h3 class="box-title"> Tambah Data Penerimaan Kas </h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<form method="POST" action="" class="form-horizontal">
						<div class="box-header with-border">
							<div class="col-md-3 pull-right">
								<div class="input-group date">
									<input type="text" name="tgl" class="form-control pull-right date-picker" value="<?php echo date('Y-m-d'); ?>" readonly>
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
									</div>
								</div>
							</div>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-hover" id="tab_logic">
								<thead>
									<tr>
										<th>No.</th>
										<th>POS Bayar</th>
										<th>penerimaan</th>
										<th>Keterangan</th>
									</tr>
								</thead>
								<tbody>
									<tr id='addr0'>
										<td width="40px">
											1
										</td>
										<td>
											<select id="kelas" name='idPosBayar[]' class="form-control">
												<option value="" selected> - Pilih POS Bayar - </option>
												<?php
												$sqk = mysqli_query($conn, "SELECT * FROM pos_bayar ORDER BY idPosBayar ASC");
												while ($k = mysqli_fetch_array($sqk)) {
													echo "<option value=" . $k['idPosBayar'] . ">" . $k['nmPosBayar'] . "</option>";
												}
												?>
											</select>
										</td>
										<td width="200px">
											<input type="text" name='penerimaan[]' id="uang" placeholder='Jumlah penerimaan' class="form-control" onkeypress="return isNumber(event)" required />
										</td>
										<td>
											<input type="text" name='ket[]' placeholder='Keterangan' class="form-control" required />
										</td>
									</tr>
									<tr id='addr1'></tr>
								</tbody>
							</table>
						</div>
						<div class="box-footer">
							<!-- <div class="pull-left">
							<a id="add_row" class="btn btn-default">Tambah Baris</a> <a id='delete_row' class="btn btn-default">Hapus Baris</a>
						</div> -->
							<div class="pull-right">
								<input type="submit" name="tambah" value="Simpan" class="btn btn-success">
								<a href="?view=jurnalumums" class="btn btn-default">Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	<?php
}
	?>
	<script type="text/javascript">
		$(document).ready(function() {
			var i = 1;
			$("#add_row").click(function() {
				//$('#addr'+i).html("<td>"+ (i+1) +"</td><td><input name='ket"+i+"' type='text' placeholder='Keterangan' class='form-control input-md'  /> </td><td><input  name='penerimaan"+i+"' type='text' id='uang' placeholder='Jumlah Penerimaan'  class='form-control input-md'></td><td><input  name='penerimaan"+i+"' type='text' id='uang' placeholder='Jumlahpenerimaan'  class='form-control input-md'></td>");
				$('#addr' + i).html("<td>" + (i + 1) + "</td><td><select name='idPosBayar[]' class='form-control'><option value='' selected> - Pilih Jenis penerimaan - </option><?php $sqk = mysqli_query($conn, "SELECT * FROM jenis_penerimaan ORDER BY idPosBayar ASC");
																																													while ($k = mysqli_fetch_array($sqk)) {
																																														echo '<option value=' . $k['idPosBayar'] . '' . $selected . '>' . $k['nmPosBayar'] . '</option>';
																																													} ?></select></td><td><input type='text' name='penerimaan[]' placeholder='Jumlah penerimaan' class='form-control' onkeypress='return isNumber(event)' required /></td><td><input type='text' name='ket[]' placeholder='Keterangan' class='form-control' required /></td>");

				$('#tab_logic').append('<tr id="addr' + (i + 1) + '"></tr>');
				i++;
			});

			$("#delete_row").click(function() {
				if (i > 1) {
					$("#addr" + (i - 1)).html('');
					i--;
				}
			});
		});
	</script>