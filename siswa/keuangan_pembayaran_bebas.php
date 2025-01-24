<?php
$uri = str_replace('&id=' . $_GET['id'], NULL, $_SERVER['REQUEST_URI']);

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['statusWa'];
session_start();
$headers = array();
$headers[] = $token_send;
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
?>

<?php
if ($_GET[act] == '') { ?>
	<div class="col-xs-4">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Informasi Tagihan</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<?php
				$sqlInfoTagihanSiswa = mysqli_query($conn, "SELECT tagihan_bebas.*,
					siswa.nisSiswa,
					siswa.nisnSiswa,
					siswa.nmSiswa,
					kelas_siswa.nmKelas,
					jenis_bayar.idPosBayar,
					jenis_bayar.idTahunAjaran,
					jenis_bayar.nmJenisBayar,
					jenis_bayar.tipeBayar,
					pos_bayar.nmPosBayar,
					siswa.jkSiswa,
					tahun_ajaran.nmTahunAjaran
					FROM
					tagihan_bebas
					INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
					INNER JOIN kelas_siswa ON tagihan_bebas.idKelas = kelas_siswa.idKelas
					INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
					INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
					INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
					WHERE tagihan_bebas.idTagihanBebas='$_GET[tagihan]'");
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
						<td><b><?php echo buatRp($dtInfo['totalTagihan']); ?></b></td>
					</tr>
				</table>
			</div>
			<div class="box-footer">
				<a href="index-siswa.php?view=laptanggungan" class="btn btn-primary pull-right"><span class="fa fa-reply"></span> Kembali</a>
			</div>
		</div>
	</div>
	<div class="col-xs-8">
		<div class="box box-warning box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Pembayaran Tagihan</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<?php

				if (isset($_POST['simpanBayar'])) {
					$tagihan = $_POST['idTagihanBebas'];
					$hrg = $_POST['jumlahBayar'];
					$tg = date("Y-m-d");
					if ($_POST['jumlahBayar'] > $_POST['sisa']) {
						echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
						<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
						<span aria-hidden='true'>×</span></button> <strong>Gagal</strong> - Pembayaran yang anda masukkan melebihi total tagihan!!!
						</div>";
					} else {
						$qBayar = mysqli_query($conn, "INSERT INTO tagihan_bebas_bayar(idTagihanBebas,tglBayar,jumlahBayar,ketBayar,caraBayar,user)
							VALUES('$tagihan','$_POST[tglBayar]','$_POST[jumlahBayar]','$_POST[ketBayar]','$_POST[caraBayar]', '$_SESSION[namalengkap]')");


						$bb = mysqli_query($conn, "SELECT nmBulan as Bulan, nmJenisBayar as jenis, nmSiswa as nama, noHpOrtu as hpo, noHpSis as hps, totalTagihan as tagihan FROM siswa 
										INNER JOIN tagihan_bebas ON siswa.idSiswa = tagihan_bebas.idSiswa 
										INNER JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan                  
										INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar    
										WHERE idTagihanBebas = '$tagihan'              
										");
						$reb = mysqli_fetch_array($bb);

						$jenis = $reb['jenis'];
						$tagihanb = $reb['tagihan'];
						$siswab = $reb['nama'];
						$hpob = $reb['hpo'];
						$hpsb = $reb['hps'];

						$msg_wa = array();
						$number_wa = array();

						//pesan whatsapp ortu 
						$number_wa[] = $hpob;
						$msg_wa[] =  "Assalamualaikum, Terima Kasih pembayaran $jenis untuk bulan $bulan sebesar *Rp." . rupiah($_POST['jumlahBayar']) . "* anak anda yang bernama *$siswab*, *$_POST[ketBayar]* Terima kasih";

						//pesan whatsapp siswa 
						$number_wa[] = $hpsb;
						$msg_wa[] =  "Assalamualaikum, Terima Kasih pembayaran $jenis untuk bulan $bulan sebesar *Rp." . rupiah($_POST['jumlahBayar']) . "* untuk anda yang bernama *$siswab*, *$_POST[ketBayar]* Terima kasih";

						for ($i = 0; $i < count($number_wa); $i++) {
							send_wa($link_send, $token_send, $number_send, $number_wa[$i], $msg_wa[$i]);
						}

						if ($qbayar) {
							echo "<script>document.location='index.-siswa.php?view=angsuran&tagihan=$tagihan';</script>";
						} else {
							echo "<script>document.location='index.-siswa.php?view=angsuran&tagihan=$tagihan';</script>";
						}
					}
				}
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
				<table class="table table-bordered table-striped">
					<thead>
						<tr>
							<th>No</th>
							<th>Tanggal</th>
							<th>Jumlah Bayar</th>
							<th>Opsi</th>
							<th>Keterangan</th>
							<th>#</th>

						</tr>
					</thead>
					<tbody>
						<?php
						$sqlSiswa1 = mysqli_query($conn, "SELECT siswa.*,kelas_siswa.nmKelas FROM siswa
											INNER JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas
											WHERE siswa.idSiswa='$siswa'");
						$dts = mysqli_fetch_array($sqlSiswa1);
						$sqlBayarTB = mysqli_query($conn, "SELECT * FROM tagihan_bebas_bayar WHERE idTagihanBebas='$_GET[tagihan]' ORDER BY idTagihanBebasBayar ASC");
						$no = 1;
						while ($r = mysqli_fetch_array($sqlBayarTB)) {
							echo "<tr><td>$no</td>
							<td>" . tgl_indo($r['tglBayar']) . "</td>
							<td align=right>" . buatRp($r[jumlahBayar]) . "</td>
							<td>$r[caraBayar]</td>
							<td>$r[ketBayar]</td>
							<td  colspan=''><center>
							<a class='btn btn-success btn-xs' title='Cetak Slip' target='_blank' href='./slip_bebas_persiswa_perbayar.php?idtbb=$r[idTagihanBebasBayar]'><span class='glyphicon glyphicon-print'></span></a>
							</center></td>";
							echo "</tr>";
							$no++;
						}

						$sqlTotalBayar = mysqli_query($conn, "select sum(jumlahBayar) as totalDibayar from tagihan_bebas_bayar where idTagihanBebas='$_GET[tagihan]'");
						$totalDibayar = mysqli_fetch_array($sqlTotalBayar);

						$sisa = $dtInfo['totalTagihan'] - $totalDibayar['totalDibayar'];
						$sisaRp = buatRp($sisa);

						if ($totalDibayar['totalDibayar'] == 0) {
							mysqli_query($conn, "update tagihan_bebas set statusBayar='0' where idTagihanBebas='$_GET[tagihan]'");
							$statusBayar = "<b>Tunggakan : $sisaRp</b>";
							$notif = '';
							$notifs = '';
						} elseif ($totalDibayar['totalDibayar'] < $dtInfo['totalTagihan']) {
							mysqli_query($conn, "update tagihan_bebas set statusBayar='2' where idTagihanBebas='$_GET[tagihan]'");
							$statusBayar = "<b>Tunggakan : $sisaRp</b>";
							$notif = "<a class='btn btn-info btn-xs' title='Kirimkan Notifikasi Angsuran' href='index.-siswa.php?view=angsuran&tagihan=$_GET[tagihan]&siswa=$dtInfo[idSiswa]&ket=$dtInfo[nmJenisBayar]&sms'><span class='fa fa-comments-o'></span> SMS</a>";
							$notifs = "<a class='btn btn-info btn-xs' title='Kirimkan Notifikasi Lunas' href='https://api.whatsapp.com/send?phone=$dts[noHpOrtu]&text=Assalamualaikum, Terima Kasih pembayaran Tagihan $dtInfo[nmJenisBayar] sebesar *" . buatRp($totalDibayar[totalDibayar]) . "* anak anda yang bernama *$dtInfo[nmSiswa]* telah di Bayarkan Terima kasih (Keuangan Smk Aswaja).' target='_blank'><span class='fa fa-comments'></span> WA</a>";
						} else {
							mysqli_query($conn, "update tagihan_bebas set statusBayar='1' where idTagihanBebas='$_GET[tagihan]'");
							$statusBayar = "<span class='label label-success'>Lunas</span>";
							$notif = "<a class='btn btn-info btn-xs' title='Kirimkan Notifikasi Lunas' href='index.-siswa.php?view=angsuran&tagihan=$_GET[tagihan]&siswa=$dtInfo[idSiswa]&ket=$dtInfo[nmJenisBayar]&sms'><span class='fa fa-comments-o'></span> SMS</a>";
							$notifs = "<a class='btn btn-info btn-xs' title='Kirimkan Notifikasi Lunas' href='https://api.whatsapp.com/send?phone=$dts[noHpOrtu]&text=Assalamualaikum, Terima Kasih pembayaran Tagihan $dtInfo[nmJenisBayar] sebesar  *" . buatRp($totalDibayar[totalDibayar]) . "* anak anda yang bernama *$dtInfo[nmSiswa]* telah di Bayarkan  Terima kasih (Keuangan Smk Aswaja).' target='_blank'><span class='fa fa-comments'></span> WA</a>";
						}
						?>
						<tr class="success">
							<td colspan="2"><b>Total Bayar</b></td>
							<td align="right"><b><?php echo buatRp($totalDibayar['totalDibayar']); ?></b></td>
							<td colspan="5" class="text-center"><?php echo "$statusBaya"; ?></td>
						</tr>
						<?php
						if ($totalDibayar['totalDibayar'] < $dtInfo['totalTagihan']) {
						?>

							<script>
								var totalTagihanInput = document.getElementById("totalTagihan");
								var persentaseInput = document.getElementById("persentase");
								var hasilInput = document.getElementById("hasil");
								var selisihInput = document.getElementById("selisih");

								totalTagihanInput.addEventListener("input", hitungPersentase);
								persentaseInput.addEventListener("input", hitungPersentase);

								function hitungPersentase() {
									var totalTagihan = parseFloat(totalTagihanInput.value);
									var persentase = parseFloat(persentaseInput.value);

									var hasil = (persentase / 100) * totalTagihan;
									var selisih = totalTagihan - hasil;

									hasilInput.value = hasil.toFixed(2).replace(/\.?0+$/, '');
									selisihInput.value = selisih.toFixed(2).replace(/\.?0+$/, '');
								}
							</script>
						<?php
						}
						if (isset($_GET['sms'])) {
							$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM siswa where idSiswa='$_GET[siswa]'"));
							if ($row['noHpOrtu'] == '') {
								echo "<script>document.location='index.-siswa.php?view=angsuran&tagihan=$_GET[tagihan]&err';</script>";
							} else {
								$userkey = "vmezut";
								$passkey = "ceabd0a34bde5902f2c8a2da";
								$telepon = $row['noHpOrtu'];
								$message = "Assalamualaikum, Pembayaran Tagihan $_GET[ket] anak anda $row[nmSiswa] telah di Bayarkan, Terima kasih.";
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
								echo "<script>document.location='index.-siswa.php?view=angsuran&tagihan=$_GET[tagihan]&ok';</script>";
							}
						}
						?>
					</tbody>
				</table>

			</div><!-- /.box-body -->
			<div class="box-footer">

			</div>
		</div><!-- /.box -->
	</div>
<?php
} elseif ($_GET['act'] == 'hapus') {
	$tagihan = $_GET['tagihan'];
	$qHapus = mysqli_query($conn, "DELETE FROM tagihan_bebas_bayar WHERE idTagihanBebasBayar='$_GET[id]'");
	if ($qHapus) {
		echo "<script>document.location='index.-siswa.php?view=angsuran&tagihan=$tagihan';</script>";
	} else {
		echo "<script>document.location='index.-siswa.php?view=angsuran&tagihan=$tagihan';</script>";
	}
}
?>