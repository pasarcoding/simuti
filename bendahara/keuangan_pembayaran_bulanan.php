<?php
$uri = str_replace('&id=' . $_GET['id'], NULL, $_SERVER['REQUEST_URI']);
include "./config/rupiah.php";
include "../config/koneksi.php";
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['statusWa'];
session_start();
//$headers = array();
//$headers[] = $token_send;
//$headers[] = 'Content-Type: application/x-www-form-urlencoded';
?>

<script type="text/javascript">
	function checkTanggal(tgl1, tgl2) {
		var tanggal1 = document.getElementById("tgl1").value;
		var tanggal2 = document.getElementById("tgl2").value;
		var tglTotime1 = Date.parse(tanggal1);
		var tglTotime2 = Date.parse(tanggal2);
		if (tgl2 == '') {
			document.querySelector(tgl2).setCustomValidity("Sampai Tanggal Belum Dimasukkan");
		} else if (tglTotime1 > tglTotime2) {
			document.querySelector(tgl2).setCustomValidity("Sampai Tanggal Tidak Boleh Kurang Dari Mulai Tanggal, Silahkan Pilih Tanggal Lain");
		} else {
			document.querySelector(tgl2).setCustomValidity("");
		}
	}
</script>
<?php if ($_GET[act] == '') { ?>


	<?php

	$siswa = $_GET['siswa'];
	$sqlInfoTagihanSiswa = mysqli_query($conn, "SELECT jenis_bayar.idJenisBayar,
									jenis_bayar.nmJenisBayar,
									jenis_bayar.tipeBayar,
									jenis_bayar.idTahunAjaran,
									tahun_ajaran.nmTahunAjaran,
									Sum(tagihan_bulanan.jumlahBayar) AS jmlTagihanBulanan,
									kelas_siswa.idKelas,
									kelas_siswa.nmKelas,
									siswa.nisSiswa,
									siswa.nisnSiswa,
									siswa.nmSiswa
									FROM
									jenis_bayar
									INNER JOIN tagihan_bulanan ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
									INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
									INNER JOIN siswa ON tagihan_bulanan.idSiswa = siswa.idSiswa
									INNER JOIN kelas_siswa ON tagihan_bulanan.idKelas = kelas_siswa.idKelas
									WHERE jenis_bayar.idJenisBayar='$_GET[jenis]' AND jenis_bayar.idTahunAjaran='$_GET[tahun]' AND siswa.idSiswa='$siswa'
									GROUP BY
									jenis_bayar.idJenisBayar");
	$dtInfo = mysqli_fetch_array($sqlInfoTagihanSiswa);

	?>
	<div class="col-xs-4">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Informasi Tagihan</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<?php

				$siswa = $_GET['siswa'];
				$sqlInfoTagihanSiswa = mysqli_query($conn, "SELECT jenis_bayar.idJenisBayar,
									jenis_bayar.nmJenisBayar,
									jenis_bayar.tipeBayar,
									jenis_bayar.idTahunAjaran,
									tahun_ajaran.nmTahunAjaran,
									Sum(tagihan_bulanan.jumlahBayar) AS jmlTagihanBulanan,
									kelas_siswa.idKelas,
									kelas_siswa.nmKelas,
									siswa.nisSiswa,
									siswa.nisnSiswa,
									siswa.nmSiswa
									FROM
									jenis_bayar
									INNER JOIN tagihan_bulanan ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
									INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
									INNER JOIN siswa ON tagihan_bulanan.idSiswa = siswa.idSiswa
									INNER JOIN kelas_siswa ON tagihan_bulanan.idKelas = kelas_siswa.idKelas
									WHERE jenis_bayar.idJenisBayar='$_GET[jenis]' AND jenis_bayar.idTahunAjaran='$_GET[tahun]' AND siswa.idSiswa='$siswa'
									GROUP BY
									jenis_bayar.idJenisBayar");
				$dtInfo = mysqli_fetch_array($sqlInfoTagihanSiswa);

				?>
				<table class="table table-striped">
					<tr>
						<td>Jenis</td>
						<td>:</td>
						<td><?php echo $dtInfo['nmJenisBayar']; ?></td>
					</tr>
					<tr>
						<td>Tahun Ajaran</td>
						<td>:</td>
						<td><?php echo $dtInfo['nmTahunAjaran']; ?></td>
					</tr>
					<tr>
						<td>NIS</td>
						<td>:</td>
						<td><?php echo $dtInfo['nisSiswa']; ?></td>
					</tr>
					<tr>
						<td>NISN</td>
						<td>:</td>
						<td><?php echo $dtInfo['nisnSiswa']; ?></td>
					</tr>
					<tr>
						<td>Nama Siswa</td>
						<td>:</td>
						<td><?php echo $dtInfo['nmSiswa']; ?></td>
					</tr>
					<tr>
						<td>Kelas</td>
						<td>:</td>
						<td><?php echo $dtInfo['nmKelas']; ?></td>
					</tr>
					<tr class="warning">
						<td>Total Tagihan</td>
						<td>:</td>
						<td><b><?php echo buatRp($dtInfo['jmlTagihanBulanan']); ?></b></td>
					</tr>
				</table>
			</div>
			<div class="box-footer">
				<a href="index-bendahara.php?view=pembayaran&siswa=<?php echo $siswa; ?>&cari=Cari+Siswa" class="btn btn-primary pull-right"><span class="fa fa-reply"></span> Kembali</a>
			</div>
		</div>
	</div>
	<div class="col-xs-8">
		<div class="box box-warning box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Pembayaran Tagihan Bulanan</h3>
				<span class="pull-right">

					<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#ModalCetakSemuaSlip"><span class="fa fa-print"></span> Cetak Semua Slip</button>

					<a class="btn btn-success btn-xs" target="_blank" title="Cetak Slip" href="./slip_bulanan_persiswa.php?tahun=<?php echo $_GET['tahun']; ?>&jenis=<?php echo $dtInfo['idJenisBayar']; ?>&siswa=<?php echo $siswa; ?>"><span class="fa fa-print"></span> Cetak Semua</a>
				</span>
			</div><!-- /.box-header -->

			<!-- modal filter tanggal cetak semua slip -->
			<div id="ModalCetakSemuaSlip" class="modal fade" role="dialog">
				<form method="GET" action="./slip_bulanan_persiswa_peritem.php" class="form-horizontal" target="_blank" title="Cetak Slip">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal">&times;</button>
								<h4 class="modal-title">Filter Data</h4>
							</div>
							<div class="modal-body">
								<input type="hidden" name="tahun" value="<?php echo $_GET['tahun']; ?>">
								<input type="hidden" name="siswa" value="<?php echo $siswa; ?>">
								<input type="hidden" name="kelas" value="<?php echo $dtInfo['idKelas']; ?>">

								<table class="table table-striped">
									<thead>
										<tr>
											<th>Mulai</th>
											<th>Sampai</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td>
												<div class="input-group date">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input type="text" name="tgl1" id="tgl1" class="form-control pull-right date-picker" required="">
												</div>
												<!-- /.input group -->
											</td>
											<td>
												<div class="input-group date">
													<div class="input-group-addon">
														<i class="fa fa-calendar"></i>
													</div>
													<input type="text" name="tgl2" id="tgl2" class="form-control pull-right date-picker" required="">
												</div>
												<!-- /.input group -->
											</td>
										</tr>
									</tbody>
								</table>
							</div>
							<div class="modal-footer">
								<input type="submit" value="Cetak" class="btn btn-success" onclick="checkTanggal('#tgl1','#tgl2');">
								<button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
							</div>
						</div>
					</div>
				</form>
			</div>
			<!-- /.modal filter tanggal cetak semua slip -->




			<div class="box-body">
				<?php
				if (isset($_GET['ok'])) {
					$url_sms = 'https://gsm.zenziva.net/api/balance/?userkey=vmezut&passkey=ceabd0a34bde5902f2c8a2da';
					$get_content_sms = file_get_contents($url_sms);
					$json = json_decode($get_content_sms, TRUE);
					$credit_sms = $json['credit'];
					echo "<div class='alert alert-success'><b>SUKSES</b> - SMS ke Orang Tua Berhasil dikirimkan! <b style='float:right'>(Sisa Kuota $credit_sms SMS)</b></div>";
				} elseif (isset($_GET['err'])) {
					echo "<div class='alert alert-danger'><b>GAGAL</b> - Notifikasi SMS Gagal Terkirim, Cek Lagi No Tujuan!</div>";
				}
				?>
				<table class="table table-striped table-hover table-condensed">
					<thead>
						<tr>
							<th width="120px">Bulan</th>
							<th width="210px">Jumlah Bayar</th>
							<th width="110px">Tgl. Bayar</th>
							<th width="80px">Opsi</th>
							<th width="100px">Ket Bayar</th>
							<th>Bayar</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$sqlSiswa1 = mysqli_query($conn, "SELECT siswa.*,kelas_siswa.nmKelas FROM siswa
											INNER JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas
											WHERE siswa.idSiswa='$siswa'");
						$dts = mysqli_fetch_array($sqlSiswa1);
						$sqlTagihanBulanan = mysqli_query($conn, "SELECT
										tagihan_bulanan.idTagihanBulanan,
										tagihan_bulanan.idJenisBayar,
										tagihan_bulanan.idSiswa,
										tagihan_bulanan.idKelas,
										tagihan_bulanan.idBulan,
										tagihan_bulanan.jumlahBayar,
										tagihan_bulanan.tglBayar,
										tagihan_bulanan.tglUpdate,
										tagihan_bulanan.statusBayar,
										tagihan_bulanan.caraBayar,
										
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
										kelas_siswa.nmKelas,
										bulan.nmBulan,
										bulan.urutan
									FROM
										tagihan_bulanan
									INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
									INNER JOIN siswa ON tagihan_bulanan.idSiswa = siswa.idSiswa
								
									INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
									INNER JOIN kelas_siswa ON tagihan_bulanan.idKelas = kelas_siswa.idKelas
									INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
									INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
									WHERE jenis_bayar.idJenisBayar='$_GET[jenis]' AND jenis_bayar.idTahunAjaran='$_GET[tahun]' AND siswa.idSiswa='$siswa' ORDER BY bulan.urutan ASC");
						$no = 1;
						while ($rt = mysqli_fetch_array($sqlTagihanBulanan)) {
							$sqlTotalBayar = mysqli_fetch_array(mysqli_query($conn, "select sum(jumlahBayar) as jumlah from tagihan_bulanan_bayar WHERE idTagihanBulanan='$rt[idTagihanBulanan]'"));
							$bayar = $sqlTotalBayar['jumlah'];
							$bayars = $rt['jumlahBayar'] - $bayar;
							if ($rt['statusBayar'] == 0) {
								$status = 1;
								$icon = "fa-check";
								$btn = "btn-danger";
								$statusBayar = "Belum Bayar";
								$color = "red";
								$btnBayar = "disabled";
								$alt = "Bayar Sekarang";
								$onClick = "return confirm('Akan Melakukan Pembayaran bulan $rt[nmBulan] ?')";
								$opsi = " Bayar";
								$tombolBayar = "<input type='submit' class='btn btn-success btn-xs' value='Bayar Tagihan'>";
								$inputTanggal = "<input type='text' class='form-control datepicker text-center' name='tglBayar' value='" . date('Y-m-d') . "'>";
								$comboCaraBayar = "<select style='width: 90px;' name='caraBayar' class='form-control select-sm'>
														<option value='Tunai'>Tunai</option>
														<option value='Transfer'>Transfer</option>
													</select>";
								$inputbayar = "<input type='text' name='bayar' value='$bayars ' >";
							} elseif ($rt['statusBayar'] == 2) {
								$status = 1;
								$icon = "fa-check";
								$btn = "btn-danger";
								$statusBayar = "Belum Bayar";
								$color = "red";
								$btnBayar = "disabled";
								$alt = "Bayar Sekarang";
								$onClick = "return confirm('Akan Melakukan Pembayaran bulan $rt[nmBulan] ?')";
								$opsi = " Bayar";
								$tombolBayar = "<input type='submit' class='btn btn-success btn-xs' value='Bayar Tagihan'>";
								$inputTanggal = "<input type='text' class='form-control datepicker text-center' name='tglBayar' value='" . date('Y-m-d') . "'>";
								$comboCaraBayar = "<select style='width: 90px;' name='caraBayar' class='form-control select-sm'>
																				<option value='Tunai'>Tunai</option>
																				<option value='Transfer'>Transfer</option>
																			</select>";
								$inputbayar = "<input type='text' name='bayar' value='$bayars ' >";
							} else {
								$status = 0;
								$icon = "fa-close";
								$btn = "btn-danger";
								$statusBayar = "Lunas";
								$color = "green";
								$btnBayar = "";
								$alt = "Hapus Pembayaran";
								$onClick = "return confirm('Akan Menghapus Pembayaran bulan $rt[nmBulan] ?')";
								$opsi = "";
								$inputTanggal = "<input type='text' class='form-control datepicker text-center' name='tglBayar' value='$rt[tglBayar]' disabled>";
								$comboCaraBayar = $rt['caraBayar'];
								$tombolBayar = "<a class='btn $btn btn-xs' title='$alt' href='?view=bayarbulanan&act=hapuss&tahun=$rt[idTahunAjaran]&tipe=bulanan&siswa=$rt[idSiswa]&idt=$rt[idTagihanBulanan]&idjenis=$rt[idJenisBayar]&status=$status&bln=$rt[nmBulan]' 
												onclick=\"$onClick\"><span class='fa $icon'>$opsi</span></a>";
								$inputbayar = "<input type='text' name='bayar' value='' disabled>";
							}

							echo "<tr style='color:$color'>								
								
								<td >$rt[nmBulan]</td>
								
								<form method='get' action='index-bendahara.php'>
								<td>
								$inputbayar
								</td>

								<td>
									
									<input type='hidden' name='view' value='bayarbulanan'>
									<input type='hidden' name='act' value='bayar'>
									<input type='hidden' name='tahun' value='$rt[idTahunAjaran]'>
									<input type='hidden' name='tipe' value='bulanan'>
									<input type='hidden' name='siswa' value='$rt[idSiswa]'>
									<input type='hidden' name='idt' value='$rt[idTagihanBulanan]'>
									<input type='hidden' name='idjenis' value='$rt[idJenisBayar]'>
									
									<input type='hidden' name='status' value='$status'>
									$inputTanggal
								</td>
								
								<td class='text-center'>
									$comboCaraBayar
								</td>
								<td>
										<select class='form-control' name='ketBayar' required>
											<option value='Lunas'>Lunas</option>
											<option value='Angsuran 1'>Angsuran 1</option>
											<option value='Angsuran 2'>Angsuran 2</option>
											<option value='Angsuran 3'>Angsuran 3</option>
											<option value='Angsuran 4'>Angsuran 4</option>
											<option value='Angsuran 5'>Angsuran 5</option>
										</select>
									</td>
								<td >									
									$tombolBayar
								</form></td>
								</tr>";

							$sqlBayarTB = mysqli_query($conn, "SELECT * FROM tagihan_bulanan_bayar WHERE idTagihanBulanan='$rt[idTagihanBulanan]' ORDER BY idTagihanBulanan ASC");
							$no = 1;
							while ($r = mysqli_fetch_array($sqlBayarTB)) {
								echo "<tr class='success'>
							 	<td  class='text-center'> $no</td>
								<td><b>" . buatRp($r[jumlahBayar]) . "</b></td>
								<td>" . tgl_indo($r[tglBayar]) . "</td>
								<td class='text-center'>$r[caraBayar]</td>
								<td class='text-center'>$r[ketBayar]</td>
								<td width='15px' colspan='2'>
								<a class='btn btn-danger btn-xs' title='Delete Data' href='?view=bayarbulanan&act=hapus&id=$r[idTagihanBulananBayar]&tahun=$rt[idTahunAjaran]&ids=$rt[idTagihanBulanan]&idjenis=$rt[idJenisBayar]&siswa=$rt[idSiswa]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
								<a class='btn btn-primary btn-xs ' target='_blank' title='Cetak Slip' href='./slip_bulanan_persiswa_perbulan_bayar.php?tagihan=$r[idTagihanBulananBayar]'> Cetak <span class='fa fa-print'></span> </a>
									</td>";
								$no++;
							}

							echo "</tr>";
						}

						if (isset($_GET['sms'])) {
							$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM siswa where idSiswa='$_GET[siswa]'"));
							if ($row['noHpOrtu'] == '') {
								echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[jenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]&err';</script>";
							} else {
								$userkey = "vmezut";
								$passkey = "ceabd0a34bde5902f2c8a2da";
								$telepon = $row['noHpOrtu'];
								$message = "Assalamualaikum, Harap menyelesaikan pembayaran Tagihan bulanan anak anda $row[nmSiswa] untuk bulan $_GET[bln], Terima kasih.";
								$url = "https://gsm.zenziva.net/api/sendsms/";
								$curlHandle = curl_init();
								curl_setopt($curlHandle, CURLOPT_URL, $url);
								curl_setopt($curlHandle, CURLOPT_HEADER, 0);
								curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
								curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
								curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
								curl_setopt($curlHandle, CURLOPT_POST, 1);
								curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
									'userkey' => $userkey,
									'passkey' => $passkey,
									'nohp' => $telepon,
									'pesan' => $message
								));
								$results = json_decode(curl_exec($curlHandle), true);
								curl_close($curlHandle);
								echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[jenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]&ok';</script>";
							}
						} elseif (isset($_GET['lunas'])) {
							$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM siswa where idSiswa='$_GET[siswa]'"));
							if ($row['noHpOrtu'] == '') {
								echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[jenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]&err';</script>";
							} else {
								$userkey = "vmezut";
								$passkey = "ceabd0a34bde5902f2c8a2da";
								$telepon = $row['noHpOrtu'];
								$message = "Assalamualaikum, Pembayaran Tagihan bulanan anak anda $row[nmSiswa] untuk bulan $_GET[bln] telah Lunas, Terima kasih.";
								$url = "https://gsm.zenziva.net/api/sendsms/";
								$curlHandle = curl_init();
								curl_setopt($curlHandle, CURLOPT_URL, $url);
								curl_setopt($curlHandle, CURLOPT_HEADER, 0);
								curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
								curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
								curl_setopt($curlHandle, CURLOPT_TIMEOUT, 30);
								curl_setopt($curlHandle, CURLOPT_POST, 1);
								curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
									'userkey' => $userkey,
									'passkey' => $passkey,
									'nohp' => $telepon,
									'pesan' => $message
								));
								$results = json_decode(curl_exec($curlHandle), true);
								curl_close($curlHandle);
								echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[jenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]&ok';</script>";
							}
						}
						?>

					</tbody>
				</table>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>
<?php
} elseif ($_GET['act'] == 'hapus') {
	$tagihan = $_GET['tagihan'];
	$qHapus = mysqli_query($conn, "DELETE FROM tagihan_bulanan_bayar WHERE idTagihanBulananBayar='$_GET[id]'");
	$totals = mysqli_fetch_array(mysqli_query($conn, "select sum(jumlahBayar) as terbayar from tagihan_bulanan_bayar WHERE idTagihanBulanan='$_GET[ids]'"));
	$sqlTotalBayar = mysqli_fetch_array(mysqli_query($conn, "select * from tagihan_bulanan WHERE idTagihanBulanan='$_GET[ids]'"));

	if ($totals['terbayar'] == '') {
		$qHapus =  mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar=null, tglUpdate=null ,statusBayar='0', user='$_SESSION[namalengkap]' WHERE  idTagihanBulanan ='$_GET[ids]'");
	} else {
		$qHapus =  mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar=null,tglUpdate=null ,statusBayar='2', user='$_SESSION[namalengkap]' WHERE  idTagihanBulanan ='$_GET[ids]'");
	}


	if ($qHapus) {
		echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[idjenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]';</script>";
	} else {
		echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[idjenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]';</script>";
	}
} elseif ($_GET['act'] == 'bayar') {
	if ($_GET['tipe'] == 'bulanan') {
		//$tglBayar = date("Y-m-d H:i:s");
		$tglBayar = $_GET['tglBayar'];
		$caraBayar = $_GET['caraBayar'];
		$bayar = $_GET['bayar'];
		$totals = mysqli_fetch_array(mysqli_query($conn, "select sum(jumlahBayar) as terbayar from tagihan_bulanan_bayar WHERE idTagihanBulanan='$_GET[idt]'"));
		$query = mysqli_query($conn, "INSERT INTO tagihan_bulanan_bayar (idTagihanBulanan,tglBayar,jumlahBayar,ketBayar,caraBayar,user)
		VALUES ('$_GET[idt]','$tglBayar', '$_GET[bayar]', '$_GET[ketBayar]','$caraBayar','$_SESSION[namalengkap]')");

		$sqlTotalBayar = mysqli_fetch_array(mysqli_query($conn, "select * from tagihan_bulanan WHERE idTagihanBulanan='$_GET[idt]'"));

		$bayars = $bayar + $totals['terbayar'];
		if ($bayars < $sqlTotalBayar['jumlahBayar']) {
			mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$tglBayar', tglUpdate='$tglBayar', statusBayar='2', caraBayar='$caraBayar' , user='$_SESSION[namalengkap]' WHERE  idTagihanBulanan ='$_GET[idt]'");
		} else {
			mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$tglBayar', tglUpdate='$tglBayar', statusBayar='1', caraBayar='$caraBayar' , user='$_SESSION[namalengkap]' WHERE  idTagihanBulanan ='$_GET[idt]'");
		}

		$a = mysqli_query($conn, "SELECT nmBulan as Bulan, nmSiswa as nama, noHpOrtu as hpo, noHpSis as hps, jumlahBayar as tagihan FROM siswa 
		INNER JOIN tagihan_bulanan ON siswa.idSiswa = tagihan_bulanan.idSiswa 
		INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan         
		WHERE idTagihanBulanan='$_GET[idt]'    
		  ");

		$re = mysqli_fetch_array($a);

		$tagihan = $re['tagihan'];
		$siswa = $re['nama'];
		$hpo = $re['hpo'];
		$hps = $re['hps'];
		$bulan = $re['Bulan'];

		$tagihan_bulan = [$hpo, $hps];

		$msg_wa = array();
		$number_wa = array();

		//pesan whatsapp ortu
		$number_wa[] = $hpo;
		$msg_wa[] =  "Assalamualaikum, Terima Kasih pembayaran Tagihan bulanan untuk bulan $bulan sebesar *Rp." . rupiah($_GET[bayar]) . "* anak anda yang bernama *$siswa*, *Lunas* Terima kasih";
		//pesan whatsapp ortu
		$number_wa[] = $hps;
		$msg_wa[] =  "Assalamualaikum, Terima Kasih pembayaran Tagihan bulanan untuk bulan $bulan sebesar *Rp." . rupiah($_GET[bayar]) . "*  anda yang bernama *$siswa*, *Lunas* Terima kasih";

		for ($i = 0; $i < count($number_wa); $i++) {
			$data = [
				'api_key' => $token_send,
				'sender' => $number_send,
				'number' => $number_wa[$i],
				'message' => $msg_wa[$i]
			];
			$curl = curl_init();
			curl_setopt_array($curl, array(
				CURLOPT_URL => $link_send,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_ENCODING => '',
				CURLOPT_MAXREDIRS => 10,
				CURLOPT_TIMEOUT => 0,
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS => json_encode($data),
				CURLOPT_HTTPHEADER => array(
					'Content-Type: application/json'
				),
			));
			$response = curl_exec($curl);
			curl_close($curl);
		}
		echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[idjenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]';</script>";
	}

?>
<?php
} elseif ($_GET['act'] == 'hapuss') {
	if ($_GET['tipe'] == 'bulanan') {
		//$tglBayar = date("Y-m-d H:i:s");
		$tglBayar = $_GET['tglBayar'];
		$caraBayar = $_GET['caraBayar'];
		$query = mysqli_query($conn, "DELETE FROM tagihan_bulanan_bayar WHERE idTagihanBulanan='$_GET[idt]'");
		$query = mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar=null, statusBayar='0', caraBayar=null WHERE idTagihanBulanan='$_GET[idt]'");

		echo "<script>document.location='index-bendahara.php?view=bayarbulanan&jenis=$_GET[idjenis]&tahun=$_GET[tahun]&siswa=$_GET[siswa]';</script>";
	}
}
?>