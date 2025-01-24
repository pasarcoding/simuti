<?php
$uri = str_replace('&id=' . $_GET['id'], NULL, $_SERVER['REQUEST_URI']);
include "./config/rupiah.php";
include "../config/koneksi.php";
date_default_timezone_set('Asia/Jakarta');
include "../config/library.php";
session_start();
error_reporting(1);
//url tagihan
$page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
//identitas
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
//tahun ajaran
$t = mysqli_query($conn, "SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif = 'Y'");
$ta = mysqli_fetch_array($t);
$thn_ajar = $ta['ta'];

// $headers = array();
// $headers[] = $token_send;
// $headers[] = 'Content-Type: application/x-www-form-urlencoded';

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
<?php if ($_GET[act] == '') {
	$sqlTahunAktif = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE aktif='Y'");
	$tahunaktif = mysqli_fetch_array($sqlTahunAktif);
	$sqlJenisBayar = mysqli_query($conn, "SELECT * FROM jenis_bayar WHERE idTahunAjaran='$tahunaktif[idTahunAjaran]' ORDER BY tipeBayar DESC");

	if ($_SESSION['notif'] == 'csukses_bulan') {
		echo '<script>toastr["success"]("Data Pembayaran Bulanan berhasil ditambahkan.","Sukses!")</script>';
	} elseif ($_SESSION['notif'] == 'dsukses_bulan') {
		echo '<script>toastr["success"]("Data Pembayaran Bulanan berhasil dihapus.","Sukses!")</script>';
	} elseif ($_SESSION['notif'] == 'csukses_bebas') {
		echo '<script>toastr["success"]("Data Pembayaran Bebas berhasil ditambahkan.","Sukses!")</script>';
	} elseif ($_SESSION['notif'] == 'dsukses_bebas') {
		echo '<script>toastr["success"]("Data Pembayaran Bebas berhasil dihapus.","Sukses!")</script>';
	} elseif ($_SESSION['notif'] == 'gagal') {
		echo '<script>toastr["error"]("Data gagal diproses.","Gagal!")</script>';
	} elseif ($_SESSION['notif'] == 'gagal_nominal_transaksi') {
		echo '<script>toastr["error"]("Nominal Pembayaran melebihi Tagihan.","Gagal!")</script>';
	} elseif ($_SESSION['notif'] == 'wa_sukses') {
		echo '<script>toastr["success"]("Berhasil mengirimkan Tagihan.","Sukses!")</script>';
	} elseif ($_SESSION['notif'] == 'wa_gagal') {
		echo '<script>toastr["error"]("Gagal mengirimkan Tagihan.","Gagal!")</script>';
	} elseif ($_SESSION['notif'] == 'sukses_transaksi') {
		echo '<script> toastr["success"]("Berhasil menyimpan transaksi ke kas.","Sukses!"); </script>';
	}
	unset($_SESSION['notif']);

	$tglBayar = date("Y-m-d H:i:s");
?>

	<div class="col-xs-12">
		<div class="box box-warning box-solid">
			<div class="box-header with-border">
				<!-- tools box -->
				<div class="pull-right box-tools">
					<button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
						<i class="fa fa-minus"></i></button>
				</div>
				<!-- /. tools -->
				<h3 class="box-title">Filter Data Pembayaran Siswa</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form method="GET" action="" class="form-horizontal">
					<input type="hidden" name="view" value="pembayaran">
					<div class="form-group">
						<!--<label for="" class="col-sm-1 control-label">Tahun</label>
					<div class="col-sm-2">
					  <select name="idTahunAjaran" class="form-control">
						<?php
						$sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
						while ($t = mysqli_fetch_array($sqltahun)) {
							$selected = ($t['idTahunAjaran'] == $tahunaktif['idTahunAjaran']) ? ' selected="selected"' : "";

							echo '<option value="' . $t['idTahunAjaran'] . '" ' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
						}
						?>
					  </select>
					</div>-->
						<label for="" class="col-sm-2 control-label">NIS/NISN/Nama</label>
						<div class="col-sm-8">
							<select name="siswa" data-live-search="true" class="form-control selectpicker">
								<option value="">- Cari Siswa -</option>
								<?php
								$sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa");
								while ($s = mysqli_fetch_array($sqlSiswa)) {
									echo "<option value='$s[idSiswa]'>$s[nisSiswa] - $s[nmSiswa]</option>";
								}
								?>
							</select>
						</div>
						<div class="col-sm-2">
							<input type="submit" name="cari" value="Cari Siswa" class="btn btn-success">
						</div>
					</div>
				</form>
			</div><!-- /.box-body -->
		</div><!-- /.box -->
	</div>

	<?php
	if (isset($_GET['cari'])) {
		$siswa = $_GET['siswa'];
		//tagihan bebas
		$sqlTagihanBebas = mysqli_query($conn, "SELECT
									tagihan_bebas.*,
									jenis_bayar.idPosBayar,
									pos_bayar.nmPosBayar,
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
								WHERE siswa.idSiswa='$siswa' ORDER BY tagihan_bebas.idTagihanBebas ASC");
		//AND jenis_bayar.idTahunAjaran='$_GET[idTahunAjaran]' 
	?>





		<div class="col-xs-12">
			<div class="box box-success box-solid">
				<div class="box-header with-border">
					<!-- tools box -->
					<div class="pull-right box-tools">

						<button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
							<i class="fa fa-minus"></i></button>
					</div>
					<!-- /. tools -->
					<h3 class="box-title">Informasi Siswa</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<?php
					$ta = "<b>Semua Tahun Ajaran<b>";
					$thnAjaran = "Semua Tahun Ajaran";

					$tgl = date('Y-m-d');

					$sqlSiswa1 = mysqli_query($conn, "SELECT siswa.*,kelas_siswa.nmKelas FROM siswa
											INNER JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas
											WHERE siswa.idSiswa='$siswa'");
					$dts = mysqli_fetch_array($sqlSiswa1);
					?>
					<table class="table table-striped">
						<tr>
							<td width="200">Tahun Ajaran</td>
							<td width="4">:</td>
							<td><b><?php echo $ta; ?></b></td>
						</tr>
						<tr>
							<td>NIS</td>
							<td>:</td>
							<td><?php echo $dts['nisSiswa']; ?></td>
						</tr>
						<tr>
							<td>NISN</td>
							<td>:</td>
							<td><?php echo $dts['nisnSiswa']; ?></td>
						</tr>
						<tr>
							<td>Nama Siswa</td>
							<td>:</td>
							<td><?php echo $dts['nmSiswa']; ?></td>
						</tr>
						<tr>
							<td>Kelas</td>
							<td>:</td>
							<td><?php echo $dts['nmKelas']; ?></td>
						</tr>
					</table>
				</div>
			</div>


			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<!-- tools box -->
					<div class="pull-right box-tools">
						<button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
							<i class="fa fa-minus"></i></button>
					</div>
					<!-- /. tools -->
					<h3 class="box-title">Fitur Kilat</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<h5> Fitur ini digunakan untuk mempermudah transaksi</h5>
					<button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#ModalCetakSemuaSlip"><span class="fa fa-print"></span> Cetak Semua Slip Pertanggal</button>

					<a class="btn btn-success btn-xs" target="_blank" title="Cetak Slip" href="./kwitansi.php?tahun=<?php echo $ta; ?>&tgl=<?php echo $tgl; ?>&kelas=<?php echo $dts['idKelas']; ?>&siswa=<?php echo $siswa; ?>"><span class="fa fa-print"></span> Cetak Semua Slip Hari Ini</a>

					<?php
					$TAG_BULAN = array();
					while ($dj = mysqli_fetch_array($sqlJenisBayar)) {
						if ($dj['tipeBayar'] == 'bebas') {
							$sqlB = mysqli_query($conn, "SELECT
							tagihan_bebas_bayar.idTagihanBebasBayar,
							tagihan_bebas_bayar.idTagihanBebas,
							tagihan_bebas_bayar.tglBayar,
							tagihan_bebas_bayar.jumlahBayar,
							tagihan_bebas_bayar.ketBayar,
							tagihan_bebas_bayar.caraBayar,
							tagihan_bebas.idJenisBayar,
							tagihan_bebas.idSiswa,
							tagihan_bebas.idKelas,
							tagihan_bebas.totalTagihan,
							tagihan_bebas.statusBayar
							FROM
								tagihan_bebas_bayar
							INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
							INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
							WHERE tagihan_bebas.idJenisBayar='$dj[idJenisBayar]' AND tagihan_bebas.statusBayar<>'0' AND tagihan_bebas.idSiswa = '$_GET[siswa]' AND (DATE(tagihan_bebas_bayar.tglBayar)) ='$tgl'");

							while ($dtb = mysqli_fetch_array($sqlB)) {
								$TAG_BULAN[] = "*" . ucwords(strtolower($dj[nmJenisBayar])) . "* sebesar *" . buatRp($dtb[jumlahBayar]) . "*";
							}
						} else if ($dj['tipeBayar'] == 'bulanan') {
							$sqlLap = mysqli_query($conn, "SELECT * FROM tagihan_bulanan 
							INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
							WHERE tagihan_bulanan.idJenisBayar='$dj[idJenisBayar]' AND siswa.idSiswa='$_GET[siswa]' AND tagihan_bulanan.statusBayar='1' AND (DATE(tagihan_bulanan.tglBayar)) = '$tgl' ORDER BY urutan ASC");
							while ($rt = mysqli_fetch_array($sqlLap)) {
								$TAG_BULAN[] = "*" . ucwords(strtolower($dj[nmJenisBayar])) . "/" . $rt[nmBulan] . "* sebesar *" . buatRp($rt[jumlahBayar]) . "*";
							}
						}
						//total tagihan lainnya
						$totLainya = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totBayar
					FROM tagihan_bebas_bayar
					INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
					INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa 
					WHERE tagihan_bebas.statusBayar<>'0' AND tagihan_bebas.idSiswa = '$_GET[siswa]' AND (DATE(tagihan_bebas_bayar.tglBayar)) ='$tgl'"));
						//total tagihan bulanan
						$totBulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totBayar FROM tagihan_bulanan WHERE idSiswa='$_GET[siswa]' AND statusBayar='1' AND (DATE(tglBayar)) = '$tgl'"));
						$total_pembayaran = buatRp($totLainya['totBayar'] + $totBulanan['totBayar']);
					}

					for ($i = 0; $i < count($TAG_BULAN); $i++) {
						$textPembayaran = $textPembayaran . ' ' . $TAG_BULAN[$i] . ',';
					}

					$page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
					$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
					$uri_segments = explode('/', $uri_path);
					$link_url = "$page_URL$_SERVER[HTTP_HOST]/" . $uri_segments[1] . '/slip_bulanan_persiswa_peritem_sekarang.php?tahun=' . $thnAjaran . '%26tgl=' . $tgl . '%26kelas=' . $dts[idKelas] . '%26siswa=' . $siswa;

					$format_tgl = date('d-m-Y', strtotime($tgl));
					$wa_sekolah = 'http://wa.me/%2B6282358733455';
					$artb = mysqli_fetch_array($sqlTagihanBebas);

					echo "<a class='btn btn-info btn-xs' title='Kirimkan Notifikasi Pembayaran' href='https://api.whatsapp.com/send?phone=$dts[noHpOrtu]&text=Terima kasih, Pembayaran Sekolah $artb[nmPosBayar] a/n $dts[nmSiswa], kelas $dts[nmKelas], telah kami terima tanggal $format_tgl sejumlah $total_pembayaran. %0A Download Kwitansi : $link_url %0A Nomor WA Sekolah : $wa_sekolah' target='_blank'><span class='fa fa-whatsapp'></span> Kirim Pembayaran Hari Ini</a>";
					?>

					<div id="ModalCetakSemuaSlip" class="modal fade" role="dialog">
						<form method="GET" action="./slip_bulanan_persiswa_peritem.php" class="form-horizontal" target="_blank" title="Cetak Slip">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal">&times;</button>
										<h4 class="modal-title">Filter Data</h4>
									</div>
									<div class="modal-body">

										<input type="hidden" name="tahun" value="<?php echo $ta; ?>">
										<input type="hidden" name="siswa" value="<?php echo $siswa; ?>">
										<input type="hidden" name="kelas" value="<?php echo $dts['idKelas']; ?>">

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
															<input type="text" name="tgl1" id="tgl1" class="form-control pull-right date-picker" required="" value">
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
				</div>
				<?php

				// if (isset($_POST['simpan_bulanan'])) {

				// 	// set wa					
				// 	// $query = mysqli_query($conn,"UPDATE tagihan_bulanan SET  tglBayar='$tglBayar', tglUpdate='$_POST[tanggal_bayar]', statusBayar='0'  WHERE idTagihanBulanan='$_POST[id_tagihan_bulanan]'");-                    			

				// 	// if ($query) {					
				// 	// 	// echo "<script>document.location='='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
				// 	// } else {
				// 	// 	$_SESSION['notif'] = 'gagal';
				// 	// 	echo "<script>document.location='='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
				// 	// }
				// }

				if (isset($_POST['hapus_bulanan'])) {
					$query = mysqli_query($conn, "UPDATE tagihan_bulanan  SET tglBayar=null, tglUpdate=null, inv=null,   statusBayar='0', caraBayar='Tunai' WHERE idTagihanBulanan='$_POST[id_tagihan_bulanan]'");
					$query = mysqli_query($conn, "DELETE FROM tagihan_bulanan_bayar  WHERE idTagihanBulanan='$_POST[id_tagihan_bulanan]'");

					if ($query) {
						$_SESSION['notif'] = 'dsukses_bulan';
						echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
					} else {
						$_SESSION['notif'] = 'gagal';
						echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
					}
				}


				if (isset($_POST['simpan_bebas'])) {

					if ($_POST['sisa_tagihan'] == $_POST['jumlah_bayar']) {
						$statusBayar = 1;
					} else {
						$statusBayar = 2;
					}
					if ($_POST['sisa_tagihan'] < $_POST['jumlah_bayar']) {
						$_SESSION['notif'] = 'gagal_nominal_transaksi';
						echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
					} else {
						$query = mysqli_query($conn, "INSERT INTO tagihan_bebas_bayar(idTagihanBebas,tglBayar,jumlahBayar,ketBayar,caraBayar,user) VALUES ('$_POST[id_tagihan_bebas]','$tglBayar','$_POST[jumlah_bayar]','$_POST[ketBayar]','Tunai', '$_SESSION[namalengkap]')");

						$query1 = mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='$statusBayar' WHERE idTagihanBebas='$_POST[id_tagihan_bebas]'");


						$bb = mysqli_query($conn, "SELECT nmBulan as Bulan,tagihan_bebas.idSiswa as ids, nmJenisBayar as jenis, nmSiswa as nama, noHpOrtu as hpo, noHpSis as hps, totalTagihan as tagihan FROM siswa 
						INNER JOIN tagihan_bebas ON siswa.idSiswa = tagihan_bebas.idSiswa 
						INNER JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan                  
						INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar    
						WHERE idTagihanBebas = '$_POST[id_tagihan_bebas]'
						");
						$reb = mysqli_fetch_array($bb);
						$sis = $reb['ids'];
						$jenis = $reb['jenis'];
						$tagihanb = $reb['tagihan'];
						$siswab = $reb['nama'];
						$hpob = $reb['hpo'];
						$hpsb = $reb['hps'];
						$curb = $reb['Bulan'];
						$tgl = date('Y-m-d');
						$link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . '/kwitansi.php?tahun=' . $thn_ajar . '%26tgl=' . $tgl . '%26siswa=' . $sis;
						$tagihan = $_POST['jumlah_bayar'];
						$ket = $_POST['ketBayar'];

						$msg_wa = array();
						$number_wa = array();

						//pesan whatsapp ortu 
						$number_wa[] = $hpob;
						$msg_wa[] =  "Assalamualaikum, Terima Kasih pembayaran $jenis untuk bulan $bulan sebesar *Rp." . rupiah($_POST['jumlah_bayar']) . "* anak anda yang bernama *$siswab*, *$_POST[ketBayar]* Terima kasih";

						//pesan whatsapp siswa s
						$number_wa[] = $hpsb;
						$msg_wa[] =  "Assalamualaikum, Terima Kasih pembayaran $jenis untuk bulan $bulan sebesar *Rp." . rupiah($_POST['jumlah_bayar']) . "* untuk anda yang bernama *$siswab*, *$_POST[ketBayar]* Terima kasih";

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

						if ($query && $query1) {
							$_SESSION['notif'] = 'csukses_bebas';
							echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
						} else {
							$_SESSION['notif'] = 'gagal';
							echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
						}
					}
				}

				if (isset($_POST['simpan_banyak_bayar_bebas'])) {

					$id_tagihan_bebas = $_POST['id_tagihan_bebas'];
					$nama_pos_bayar = $_POST['nama_pos_bayar'];
					$nominal_bayar = $_POST['nominal_bayar'];
					$ketBayar = $_POST['ketBayar'];
					$keterangan_bayar = $_POST['keterangan_bayar'];

					for ($i = 0; $i < count($id_tagihan_bebas); $i++) {
						$cek_tagihan = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tagihan_bebas WHERE idTagihanBebas='$id_tagihan_bebas[$i]'"));
						$cek_bayar = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayar FROM tagihan_bebas WHERE idTagihanBebas='$id_tagihan_bebas[$i]'"));
						$cek_bayars = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayar FROM tagihan_bebas_bayar WHERE idTagihanBebas='$id_tagihan_bebas[$i]'"));
						$sisa_tagihan = $cek_tagihan['totalTagihan'] - $cek_bayars['totalBayar'];
						if ($nominal_bayar[$i] > $sisa_tagihan) {
							$_SESSION['notif'] = 'gagal_nominal_transaksi';
							echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
						} else {
							if ($nominal_bayar[$i] == $sisa_tagihan) {
								$statusBayar = 1;
							} else {
								$statusBayar = 2;
							}
							$query = mysqli_query($conn, "INSERT INTO tagihan_bebas_bayar(idTagihanBebas,tglBayar,jumlahBayar,ketBayar,caraBayar,user) VALUES ('$id_tagihan_bebas[$i]','$_POST[tanggal_bayar]','$nominal_bayar[$i]','$ketBayar[$i]','Tunai', '$_SESSION[namalengkap]')");
							$query1 = mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='$statusBayar' WHERE idTagihanBebas='$id_tagihan_bebas[$i]'");

							$bb = mysqli_query($conn, "SELECT nmBulan as Bulan,tagihan_bebas.idSiswa as ids, nmJenisBayar as jenis, nmSiswa as nama, noHpOrtu as hpo, noHpSis as hps, totalTagihan as tagihan FROM siswa 
							INNER JOIN tagihan_bebas ON siswa.idSiswa = tagihan_bebas.idSiswa 
							INNER JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan                  
							INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar    
							WHERE idTagihanBebas='$id_tagihan_bebas[$i]'
							");

							$reb = mysqli_fetch_array($bb);
							$bulan = $reb['Bulan'];
							$sis = $reb['ids'];
							$jenis = $reb['jenis'];
							$tagihanb = $reb['tagihan'];
							$siswab = $reb['nama'];
							$hpob = $reb['hpo'];
							$hpsb = $reb['hps'];
							$tgl = date('Y-m-d');
							$link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . '/kwitansi.php?tahun=' . $thn_ajar . '%26tgl=' . $tgl . '%26siswa=' . $sis;

							$msg_wa = array();
							$number_wa = array();

							//pesan whatsapp ortu
							$number_wa[] = $hpob;
							$msg_wa[] =  'Assalamualaikum, Terima Kasih pembayaran $jenis untuk bulan $bulan sebesar *Rp.".rupiah($nominal_bayar[$i])."* anak anda yang bernama *$siswab*, *$ketBayar[$i]* Terima kasih %0A%0ADownload kwitansi ' . $link_url_tagihan . '';

							//pesan whatsapp siswa
							$number_wa[] = $hpsb;
							$msg_wa[] =  'Assalamualaikum, Terima Kasih pembayaran $jenis untuk bulan $bulan sebesar *Rp.".rupiah($nominal_bayar[$i])."* untuk anda yang bernama *$siswab*, *$ketBayar[$i]* Terima kasih %0A%0ADownload kwitansi ' . $link_url_tagihan . '';

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

							if ($query && $query1) {
								$_SESSION['notif'] = 'csukses_bebas';
								echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
							} else {
								$_SESSION['notif'] = 'gagal';
								echo "<script>document.location='?view=$_GET[view]&siswa=$_GET[siswa]&cari=Cari Siswa';</script>";
							}
						}
					}
				}

				?>
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab">Bulanan</a></li>
						<li><a href="#tab_2" data-toggle="tab">Lainnya</a></li>
					</ul>

					<div class="tab-content">

						<?php $uri = str_replace('&id=' . $_GET['id'], NULL, $_SERVER['REQUEST_URI']); ?>

						<!-- List Tagihan Bulanan -->
						<div class="tab-pane active" id="tab_1">
							<div class="box-body table-responsive">
								<table class="table table-bordered" style="white-space: nowrap;">
									<thead>
										<tr class="info">
											<th>No.</th>
											<th>Nama Pembayaran</th>
											<th>Sisa Tagihan</th>
											<?php
											$bulan = mysqli_query($conn, "SELECT * FROM bulan ORDER BY urutan ASC");
											while ($bln = mysqli_fetch_array($bulan)) {
												echo '<th>' . $bln['nmBulan'] . '</th>';
											}
											?>
										</tr>
									</thead>
									<tbody>
										<?php
										$no = 1;
										$grupJenisBayar = mysqli_query($conn, "SELECT
								jenis_bayar.idJenisBayar,
								jenis_bayar.nmJenisBayar,
								jenis_bayar.tipeBayar,
								jenis_bayar.idTahunAjaran,
								tahun_ajaran.nmTahunAjaran,
								Sum(tagihan_bulanan.jumlahBayar) AS jmlTagihanBulanan,
								kelas_siswa.nmKelas,
								siswa.idSiswa,
								siswa.nisSiswa,
								siswa.nisnSiswa,
								siswa.nmSiswa,
								jenis_bayar.idPosBayar,
								pos_bayar.nmPosBayar,
								pos_bayar.ketPosBayar
								FROM
								jenis_bayar
								INNER JOIN tagihan_bulanan ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
								INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
								INNER JOIN siswa ON tagihan_bulanan.idSiswa = siswa.idSiswa
								INNER JOIN kelas_siswa ON tagihan_bulanan.idKelas = kelas_siswa.idKelas
								INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
								WHERE siswa.idSiswa='$siswa'
								GROUP BY
								jenis_bayar.idJenisBayar");

										while ($gjb = mysqli_fetch_array($grupJenisBayar)) {
											$jumlahBayarBulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(diBayar) as totalBayarBulanan FROM view_laporan_bayar_bulanan WHERE idSiswa='$gjb[idSiswa]' AND idJenisBayar='$gjb[idJenisBayar]' GROUP BY idSiswa"));
											echo '<tr>';
											echo '<td>' . $no . '</td>';
											echo '<td>' . $gjb['nmJenisBayar'] . ' - T.A ' . $gjb['nmTahunAjaran'] . '</td>';
											echo '<td>' . buatRp($gjb['jmlTagihanBulanan'] - $jumlahBayarBulanan['totalBayarBulanan']) . '</td>';
											$sqlSiswa1 = mysqli_query($conn, "SELECT siswa.*,kelas_siswa.nmKelas FROM siswa
											INNER JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas
											WHERE siswa.idSiswa='$siswa'");
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
									WHERE  siswa.idSiswa='$siswa' AND tagihan_bulanan.idJenisBayar='$gjb[idJenisBayar]' ORDER BY bulan.urutan ASC");

											while ($TagihanBulanan = mysqli_fetch_array($sqlTagihanBulanan)) {
												$tag = mysqli_fetch_array(mysqli_query($conn, "select * from tagihan_bulanan WHERE idTagihanBulanan='$TagihanBulanan[idTagihanBulanan]'"));
												$bayar = mysqli_query($conn, "select  sum(jumlahBayar) as TotalTagihan from tagihan_bulanan_bayar WHERE idTagihanBulanan='$TagihanBulanan[idTagihanBulanan]'");
												$bayars = mysqli_fetch_array($bayar);
												$sisaaja = $tag['jumlahBayar'] - $bayars['TotalTagihan'];

												$sisaTagihanBebas = $tb['totalTagihanBebas'] - $totalBayarBebas['totalBayarBebas'];
												if ($TagihanBulanan['statusBayar'] == '1') {
													echo '<td class="success">
                                        <a data-toggle="modal" data-target="#del' . $TagihanBulanan[nmBulan] . $TagihanBulanan[idTagihanBulanan] . '">' . buatRp($TagihanBulanan['jumlahBayar']) . '<br>(' . date('d/m/y', strtotime($TagihanBulanan['tglBayar'])) . ')<br>' . $TagihanBulanan['caraBayar'] . '</a>
                                        
                                      </td>
									  
									  
									  ';
													echo '<div class="modal fade" id="del' . $TagihanBulanan[nmBulan] . $TagihanBulanan[idTagihanBulanan] . '" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">×</button>
                                                <h4 class="modal-title">Pembayaran Bulan ' . $TagihanBulanan[nmBulan] . '</h4>
                                              </div>
                                              <form action="" method="post" accept-charset="utf-8">
                                                <div class="modal-body">
                                                  <input class="form-control" required="" type="hidden" name="id_tagihan_bulanan" value="' . $TagihanBulanan[idTagihanBulanan] . '">
                                                                                               
                                                  
                                                
                                                  <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                      <input class="form-control" required="" type="text" name="tanggal_bayar" placeholder="Tanggal Bayar" value="' . date('Y-m-d H:i:s', strtotime($TagihanBulanan['tglUpdate'])) . '" readonly>
                                                    </div>
                                                  </div>
                                                  <div class="form-group">
                                                      <label>Jumlah Bayar</label>
                                                      <input class="form-control"  type="text" name="jumlah_bayar" placeholder="Jumlah Bayar" value="' . $TagihanBulanan['jumlahBayar'] . '" readonly>
                                                  </div>
												 
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="submit" name="hapus_bulanan" class="btn btn-danger">Hapus</button>
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                </div>
                                              </form>                           
                                            </div>
                                          </div>
                                        </div>';
												} elseif ($TagihanBulanan['statusBayar'] == '2') {

													echo '<td class="danger"> 
                                        <a data-toggle="modal" data-target="#add' . $TagihanBulanan[nmBulan] . $TagihanBulanan[idTagihanBulanan] . '" onclick="change_kas_account(' . $no . ')">' . buatRp($sisaaja) . ' <br>Belum Lengkap</a>
                                      </td>';
													echo '<div class="modal fade" id="add' . $TagihanBulanan[nmBulan] . $TagihanBulanan[idTagihanBulanan] . '" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">×</button>
                                                <h4 class="modal-title">Pembayaran Bulan ' . $TagihanBulanan[nmBulan] . '</h4>
                                              </div>
                                              <form action="admin/act.php" method="post" accept-charset="utf-8">
                                                <div class="modal-body">
                                                  <input class="form-control" required="" type="hidden" name="id_tagihan_bulanan" value="' . $TagihanBulanan[idTagihanBulanan] . '">
                                            
                                                  <input class="form-control" required="" type="hidden" name="nama_bulan" value="' . $TagihanBulanan['nmBulan'] . '">
                                                  <input class="form-control" required="" type="hidden" name="nama_pos_bayar" value="' . $gjb['nmPosBayar'] . '">
                                                  <input class="form-control" required="" type="hidden" name="nama_tahun_ajaran" value="' . $gjb['nmTahunAjaran'] . '">
												  <input class="form-control" required="" type="hidden" name="sisa_tagihan" value="' . $sisaTagihanBebas . '">
												  <input type="hidden" name="uri" value="' . $uri . '">
                                                  <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                      <input class="form-control " required="" type="text" name="tanggal_bayar" placeholder="Tanggal Bayar" value="' . date('Y-m-d H:i:s') . '">
                                                    </div>
                                                  </div>
												  <div class="form-group">
												  <label>Sisa Bayar</label>
												  <input class="form-control "  type="text"  placeholder="Jumlah Bayar" value="' . $sisaaja . '" readonly>
												  
											  </div>
                                                  <div class="form-group">
                                                      <label>Jumlah Bayar</label>
		
                                                      <input class="form-control"  type="text" name="jumlah_bayar" placeholder="Jumlah Bayar" value="">
                                                  </div>
												  <select style=""width: 90px;" name="caraBayar" class="form-control select-sm">
														<option value="Tunai">Tunai</option>
														<option value="Transfer">Transfer</option>
													</select>
													<label>Keterangan Bayar</label>
													<select class="form-control" name="ketBayar" required>
											<option value="Lunas">Lunas</option>
											<option value="Angsuran 1">Angsuran 1</option>
											<option value="Angsuran 2">Angsuran 2</option>
											<option value="Angsuran 3">Angsuran 3</option>
											<option value="Angsuran 4">Angsuran 4</option>
											<option value="Angsuran 5">Angsuran 5</option>
										</select>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="submit" name="simpan_bulanan" class="btn btn-success">Simpan</button>
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                </div>
                                              </form>                           
                                            </div>
                                          </div>
                                        </div>';
												} else {
													echo '<td class="danger"> 
                                        <a data-toggle="modal" data-target="#add' . $TagihanBulanan[nmBulan] . $TagihanBulanan[idTagihanBulanan] . '" onclick="change_kas_account(' . $no . ')">' . buatRp($TagihanBulanan['jumlahBayar']) . '</a>
                                      </td>';
													echo '<div class="modal fade" id="add' . $TagihanBulanan[nmBulan] . $TagihanBulanan[idTagihanBulanan] . '" role="dialog">
                                        <div class="modal-dialog modal-sm">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal">×</button>
                                                <h4 class="modal-title">Pembayaran Bulan ' . $TagihanBulanan[nmBulan] . '</h4>
                                              </div>
                                              <form action="admin/act.php" method="post" accept-charset="utf-8">
                                                <div class="modal-body">
                                                  <input class="form-control" required="" type="hidden" name="id_tagihan_bulanan" value="' . $TagihanBulanan[idTagihanBulanan] . '">
                                            
                                                  <input class="form-control" required="" type="hidden" name="nama_bulan" value="' . $TagihanBulanan['nmBulan'] . '">
                                                  <input class="form-control" required="" type="hidden" name="nama_pos_bayar" value="' . $gjb['nmPosBayar'] . '">
                                                  <input class="form-control" required="" type="hidden" name="nama_tahun_ajaran" value="' . $gjb['nmTahunAjaran'] . '">
												  <input class="form-control" required="" type="hidden" name="sisa_tagihan" value="' . $sisaTagihanBebas . '">
												  <input type="hidden" name="uri" value="' . $uri . '">
                                                  <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                      <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                      <input class="form-control " required="" type="text" name="tanggal_bayar" placeholder="Tanggal Bayar" value="' . date('Y-m-d H:i:s') . '">
                                                    </div>
                                                  </div>
												  <div class="form-group">
												  <label>Sisa Bayar</label>
												  <input class="form-control "  type="text"  placeholder="Jumlah Bayar" value="' . $sisaaja . '" readonly>
												  
											  </div>
                                                  <div class="form-group">
                                                      <label>Jumlah Bayar</label>
                                                      <input class="form-control"  type="text" name="jumlah_bayar" placeholder="Jumlah Bayar" value="">
                                                  </div>
												  <select style=""width: 90px;" name="caraBayar" class="form-control select-sm">
														<option value="Tunai">Tunai</option>
														<option value="Transfer">Transfer</option>
													</select>
													<label>Keterangan Bayar</label>
													<select class="form-control" name="ketBayar" required>
											<option value="Lunas">Lunas</option>
											<option value="Angsuran 1">Angsuran 1</option>
											<option value="Angsuran 2">Angsuran 2</option>
											<option value="Angsuran 3">Angsuran 3</option>
											<option value="Angsuran 4">Angsuran 4</option>
											<option value="Angsuran 5">Angsuran 5</option>
										</select>
                                                </div>
                                                <div class="modal-footer">
                                                  <button type="submit" name="simpan_bulanan" class="btn btn-success">Simpan</button>
                                                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                                </div>
                                              </form>                           
                                            </div>
                                          </div>
                                        </div>';
													$no++;
												}
											}

											echo '</tr>';
										}
										?>
									</tbody>
								</table>
							</div>
						</div>
						<!-- End List Tagihan Bulanan -->
						<div class="tab-pane" id="tab_2">


							<!-- List Tagihan Lainnya (Bebas) -->

							<div class="box-body">
								<a data-toggle="modal" class="btn btn-success btn-xs" title="Bayar Banyak" href="#bayarBanyak" onclick="get_form(); change_kas_account(1)"><span class="fa fa-money"></span> Bayar Banyak</a>
								<a href="" class="btn btn-info btn-xs"><i class="fa fa-refresh"></i> Refresh</a>
								<div class="box-body table-responsive">

									<div class="modal fade" id="bayarBanyak" role="dialog">
										<div class="modal-dialog modal-md">
											<div class="modal-content">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal">×</button>
													<h4 class="modal-title">Bayar Banyak</h4>
												</div>
												<form action="" method="post" accept-charset="utf-8">
													<div class="modal-body">
														<div class="form-group">
															<label>Tanggal *</label>
															<div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
																<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
																<input class="form-control" required="" type="text" name="tanggal_bayar" id="bebas_pay_date" placeholder="Tanggal Bayar" value="<?= date('Y-m-d H:i:s') ?>">
															</div>

														</div>
														<div id="fbatch"></div>
													</div>
													<div class="modal-footer">
														<button type="submit" name="simpan_banyak_bayar_bebas" class="btn btn-success">Simpan</button>
														<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
													</div>
												</form>
											</div>
										</div>
									</div>


									<table class="table table-bordered" style="white-space: nowrap;">
										<thead>
											<tr class="info">
												<th>
													<input type="checkbox" id="selectall" value="msg" name="msg">
												</th>
												<th>No.</th>
												<th>Jenis Pembayaran</th>
												<th>Sisa Tagihan</th>
												<th>Dibayar</th>
												<th>Status</th>
												<th>Bayar</th>
											</tr>
										</thead>
										<tbody>
											<?php
											$TagihanBebas = mysqli_query($conn, "SELECT 
                                                                      tagihan_bebas.*, 
                                                                      SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, 
                                                                      jenis_bayar.idPosBayar, 
																	  jenis_bayar.nmJenisBayar,
                                                                      tahun_ajaran.nmTahunAjaran,
                                                                      pos_bayar.nmPosBayar
                                                                    FROM tagihan_bebas 
                                                                    LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                                                                    LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                                                    LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                                                                    WHERE tagihan_bebas.idSiswa='$siswa'  
                                                                    GROUP BY tagihan_bebas.idJenisBayar");
											$no = 1;
											while ($tb = mysqli_fetch_array($TagihanBebas)) {
												$totalBayarBebas = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayarBebas FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tb[idTagihanBebas]'"));
												$sisaTagihanBebas = $tb['totalTagihanBebas'] - $totalBayarBebas['totalBayarBebas'];

												if ($tb['statusBayar'] == '1') {
													echo '<tr class="success">
                                      <td style="background-color: #fff !important;">
                                        <center><input type="checkbox" disabled="disabled"></center>
                                      </td>
                                      <td style="background-color: #fff !important;">' . $no . '</td>
                                      <td style="background-color: #fff !important;">' . $tb['nmJenisBayar'] . ' - T.A ' . $tb['nmTahunAjaran'] . '</td>
                                      <td>' . buatRp($sisaTagihanBebas) . '</td>
                                      <td>' . buatRp($totalBayarBebas['totalBayarBebas']) . '</td>
                                      <td><a data-toggle="modal" title="Lihat" href="#lihat' . $tb['idTagihanBebas'] . '" class="view-cicilan label label-success">Lunas</a></td>
                                      <td width="40" style="text-align:center">
                                        <a data-toggle="modal" class="btn btn-success btn-xs disabled" title="Bayar" href="#addCicilan' . $tb['idTagihanBebas'] . '" ><span class="fa fa-money"></span> Bayar</a>
                                      </td>
                                    </tr>';
												} else {
													echo '<tr class="danger">
                                      <td style="background-color: #fff !important;">
                                        <center><input type="checkbox" class="checkbox" name="msg[]" id="msg" value="' . $tb['idTagihanBebas'] . '"></center>
                                      </td>
                                      <td style="background-color: #fff !important;">' . $no . '</td>
                                      <td style="background-color: #fff !important;">' . $tb['nmJenisBayar'] . ' - T.A ' . $tb['nmTahunAjaran'] . '</td>
                                      <td>' . buatRp($sisaTagihanBebas) . '</td>
                                      <td>' . buatRp($totalBayarBebas['totalBayarBebas']) . '</td>
                                      <td><a data-toggle="modal" title="Lihat" href="#lihat' . $tb['idTagihanBebas'] . '" class="view-cicilan label label-warning">Belum Lunas</a></td>
                                      <td width="40" style="text-align:center">
                                        <a data-toggle="modal" class="btn btn-success btn-xs " title="Bayar" href="#addCicilan' . $tb['idTagihanBebas'] . '" ><span class="fa fa-money"></span> Bayar</a>
                                      </td>
                                    </tr>';

													echo '<div class="modal fade" id="addCicilan' . $tb['idTagihanBebas'] . '" role="dialog">
                                      <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                            <h4 class="modal-title">Tambah Pembayaran/Cicilan</h4>
                                          </div>
                                          <form action="" method="post" accept-charset="utf-8">
                                            <div class="modal-body">
                                              <input class="form-control" required="" type="hidden" name="id_tagihan_bebas" value="' . $tb[idTagihanBebas] . '">
                                              <input class="form-control" required="" type="hidden" name="sisa_tagihan" value="' . $sisaTagihanBebas . '">
                                              
                                              <div class="form-group">
                                                <label>Tanggal *</label>
                                                <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                                                  <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                                                  <input class="form-control" required="" type="text" name="tanggal_bayar" id="bebas_pay_date" placeholder="Tanggal Bayar" value="' . date('Y-m-d  H:i:s') . '">
                                                </div>
                                              </div>
											  
                                              <div class="row">
                                                <div class="col-md-6">
                                                  <label>Jumlah Bayar *</label>
                                                  <input type="text" required="" name="jumlah_bayar" class="form-control" placeholder="Jumlah Bayar">
                                                </div>
                                               <div class="col-md-6">
                                                  <label>Keterangan *</label>
                                                  <input type="text" required="" name="ketBayar" class="form-control" placeholder="Lunas/Angsuran">
                                                </div>
                                              </div>
											   
                                            </div>
											
                                            <div class="modal-footer">
                                              <button type="submit" name="simpan_bebas" class="btn btn-success">Simpan</button>
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>';
												}


												echo '<div class="modal fade" id="lihat' . $tb['idTagihanBebas'] . '" role="dialog">
                                      <div class="modal-dialog modal-md">
                                        <div class="modal-content">
                                          <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal">×</button>
                                            <h4 class="modal-title">Lihat Pembayaran/Cicilan</h4>
                                          </div>
                                          <form action="" method="post" accept-charset="utf-8">
                                            <div class="modal-body">
                                            <div id="object_detail_bebas">
                                              <object data="admin/pembayaran_tagihan_bebas.php?id=' . $tb['idTagihanBebas'] . '&view=' . $_GET['view'] . '&thn_ajar=' . $_GET['thn_ajar'] . '&nis=' . $_GET['nis'] . '&posbayar=' . $tb['nmPosBayar'] . ' - T.A ' . $tb['nmTahunAjaran'] . '" width="100%" height="400px"></object>
                                            </div>
                                            </div>
                                            <div class="modal-footer">
                                              <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                                            </div>
                                          </form>
                                        </div>
                                      </div>
                                    </div>';
												$no++;
											}
											?>



										</tbody>
									</table>

								</div>

							</div>

						</div>
						<br><br>
					</div>


				</div>
			</div>
		</div>
		<div class="col-xs-12">
			<!-- List Tagihan Bulanan -->
			<div class="box box-warning box-solid">
				<div class="box-header with-border">
					<!-- tools box -->
					<div class="pull-right box-tools">
						<button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
							<i class="fa fa-minus"></i></button>
					</div>
					<h3 class="box-title">Tagihan Bulanan</h3>
				</div><!-- /.box-header -->
				<div class="box-body">
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>No.</th>
								<th>Tahun Ajaran</th>
								<th>Pos Bayar</th>
								<th>Jenis Pembayaran</th>
								<th>Total Tagihan</th>
								<th>DiBayar</th>
								<th>Status Bayar</th>
								<th>Bayar</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sqlListTGB = mysqli_query($conn, "SELECT
							
								*
								FROM
								tagihan_bulanan
								INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
								INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
								INNER JOIN siswa ON tagihan_bulanan.idSiswa = siswa.idSiswa
								INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
								INNER JOIN kelas_siswa ON tagihan_bulanan.idKelas = kelas_siswa.idKelas
								INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
								WHERE siswa.idSiswa='$siswa'  GROUP BY tagihan_bulanan.idJenisBayar 
								");
							//jenis_bayar.idTahunAjaran='$_GET[idTahunAjaran]' AND 
							$no = 1;
							while ($rtgb = mysqli_fetch_array($sqlListTGB)) {

								$dtgb = mysqli_fetch_array(mysqli_query($conn, "select sum(jumlahBayar) as jmlDibayar from tagihan_bulanan_bayar 
								where idTagihanBulanan='$rtb[idTagihanBulanan]'"));

								// $dtgb = mysqli_fetch_array(mysqli_query($conn, "select sum(diBayar) as jmlDibayar from view_laporan_bayar_bulanan where idJenisBayar='$rtgb[idJenisBayar]' AND idSiswa='$rtgb[idSiswa]' "));

								if ($dtgb['jmlDibayar'] == 0) {
									$status = "<label class='label label-danger'>Belum Bayar</label>";
									$icon = "fa-plus";
									$btn = "btn-danger";
									$color = "red";
									$alt = "Bayar";
									$btncetak = "disabled";
								} elseif ($dtgb['jmlDibayar'] < $rtgb['jmlTagihanBulanan']) {
									$status = "<label class='label label-warning'>Belum Lengkap</label>";
									$icon = "fa-plus";
									$btn = "btn-warning";
									$color = "red";
									$alt = "Bayar";
									$btncetak = "";
								} else {
									$status = "<label class='label label-success'>Lunas</label>";
									$icon = "fa-search";
									$btn = "btn-success";
									$color = "green";
									$alt = "Detil";
									$btncetak = "";
								}
								echo "<tr style='color:$color'>
								<td>$no</td>
								<td>$rtgb[nmTahunAjaran]</td>
								<td>$rtgb[nmPosBayar]</td>
								<td>$rtgb[nmJenisBayar]</td>
								<td>" . buatRp($rtgb['jumlahBayar']) . "</td>
								<td>" . buatRp($dtgb['jmlDibayar']) . "</td>
								<td>$status</td>
								<td width='40' style='text-align:center'>
									<a class='btn $btn btn-xs' title='$alt' href='?view=bayarbulanan&jenis=$rtgb[idJenisBayar]&tahun=$rtgb[idTahunAjaran]&siswa=$rtgb[idSiswa]'><span class='fa $icon'></span> $alt</a>
								</td>
								
							</tr>";
								$no++;
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
			<!-- End List Tagihan Bulanan -->

			<br>

			<!-- List Tagihan Lainnya (Bebas) -->
			<div class="box box-danger box-solid">
				<div class="box-header with-border">
					<!-- tools box -->
					<div class="pull-right box-tools">
						<button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
							<i class="fa fa-minus"></i></button>
						<!--<a class="btn btn-success btn-sm pull-right" style="margin-right: 5px;" target="_blank" title="Cetak Slip" href="./slip_bebas_persiswa.php?tahun=<?php //echo $_GET['idTahunAjaran']; 
																																												?>&siswa=<?php //echo $_GET['nisSiswa']; 
																																															?>"><span class="fa fa-print"></span> Cetak Semua Status Pembayaran</a>-->
					</div>
					<!-- /. tools -->
					<h3 class="box-title">Tagihan Lainnya</h3>
				</div><!-- /.box-header -->

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
					<table class="table table-striped table-hover">
						<thead>
							<tr>
								<th>No.</th>
								<th>Tahun Ajaran</th>
								<th>Pos Bayar</th>
								<th>Jenis Pembayaran</th>
								<th>Total Tagihan</th>
								<th>Dibayar</th>
								<th>Status</th>
								<th>Bayar</th>
								<th class='text-center'>Cetak</th>
								<th>Kirim WhatsApp Tagihan</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sqlTagihanBebas = mysqli_query($conn, "SELECT
									tagihan_bebas.*,
									
									jenis_bayar.idPosBayar,
									pos_bayar.nmPosBayar,
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
								WHERE siswa.idSiswa='$siswa' ORDER BY tagihan_bebas.idTagihanBebas ASC");
							$no = 1;

							while ($rtb = mysqli_fetch_array($sqlTagihanBebas)) {
								$qBayar = mysqli_query($conn, "select sum(jumlahBayar) as totalDibayar from tagihan_bebas_bayar 
												where idTagihanBebas='$rtb[idTagihanBebas]'");
								$dtBayar = mysqli_fetch_array($qBayar);
								$sisaTagihanBebas = $rtb['totalTagihan'] - $dtBayar['totalDibayar'];

								if ($rtb['statusBayar'] == '0') {
									$status = "<label class='label label-danger'>Belum Bayar</label>";
									$icon = "fa-plus";
									$btn = "btn-danger";
									$color = "red";
									$alt = "Bayar";
									$btncetak = "disabled";
									$btnwa = "";
								} elseif ($rtb['statusBayar'] == '2') {
									$status = "<label class='label label-warning'>Belum Lunas</label>";
									$icon = "fa-plus";
									$btn = "btn-warning";
									$color = "red";
									$alt = "Bayar";
									$btncetak = "";
									$btnwa = "";
								} elseif ($rtb['statusBayar'] == '1') {
									$status = "<label class='label label-success'>Lunas</label>";
									$icon = "fa-search";
									$btn = "btn-success";
									$color = "green";
									$alt = "Detil";
									$btncetak = "";
									$btnwa = "disabled";
								}
								echo "<tr style='color:$color'>
							<td>$no</td>
							<td>$rtb[nmTahunAjaran]</td>
							<td>$rtb[nmPosBayar]</td>
							<td>$rtb[nmJenisBayar]</td>
							<td>" . buatRp($rtb['totalTagihan']) . "</td>
							<td>" . buatRp($dtBayar['totalDibayar']) . "</td>
							<td>$status</td>
							<td width='40' style='text-align:center'>
								<a class='btn $btn btn-xs' title='$alt' href='?view=angsuran&tagihan=$rtb[idTagihanBebas]' ><span class='fa $icon'></span> $alt</a>
							</td>
							<td class='text-center'>
								<a class='btn btn-success btn-xs $btncetak' target='_blank' title='Cetak Pembayaran' href='./slip_bebas_persiswa.php?tagihan=$rtb[idTagihanBebas]&siswa=$siswa'><span class='fa fa-print'></span> Cetak Pembayaran</a>
								
							</td>
							
						<td class='text-center'>
							<a class='btn btn-info btn-xs $btnwa' title='Kirimkan Notifikasi Tagihan' href='https://api.whatsapp.com/send?phone=$dts[noHpOrtu]&text=Assalamualaikum, Harap menyelesaikan pembayaran Tagihan $rtb[nmJenisBayar] anak anda *$dts[nmSiswa]* sebesar  *" . buatRp($sisaTagihanBebas) . "* , Terima kasih. ' target='_blank'><span class='fa fa-commenting'></span>Kirim Tagihan</a>
						</td>
						</tr>";
								$no++;
							}

							if (isset($_GET['sms'])) {
								$row = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM siswa where idSiswa='$_GET[siswa]'"));
								if ($row['noHpOrtu'] == '') {
									echo "<script>document.location='?view=pembayaran&siswa=$_GET[siswa]&cari=$_GET[cari]&err';</script>";
								} else {
									$userkey = "vmezut";
									$passkey = "ceabd0a34bde5902f2c8a2da";
									$telepon = $row['noHpOrtu'];
									$message = "Assalamualaikum, Harap menyelesaikan pembayaran Tagihan $_GET[ket] anak anda $row[nmSiswa], Terima kasih.";
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
									echo "<script>document.location='?view=pembayaran&siswa=$_GET[siswa]&cari=$_GET[cari]&ok';</script>";
								}
							}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php
	}
	?>
<?php
}
?>

<script type="text/javascript">
	function startCalculate() {
		interval = setInterval("Calculate()", 10);
	}

	function Calculate() {
		var numberHarga = $('#harga').val(); // a string
		numberHarga = numberHarga.replace(/\D/g, '');
		numberHarga = parseInt(numberHarga, 10);

		var numberBayar = $('#bayar').val(); // a string
		numberBayar = numberBayar.replace(/\D/g, '');
		numberBayar = parseInt(numberBayar, 10);

		var total = numberBayar - numberHarga;
		$('#kembalian').val(total);
	}

	function stopCalc() {
		clearInterval(interval);
	}

	function cari_noref() {
		var tgl_transaksi = $("#tgl_transaksi").val();
		var idSiswa = $("#idSiswa").val();
		$.ajax({
			type: 'POST',
			url: "admin/combobox/pilihan_norefrensi_pembayaran.php",
			data: {
				tgl_transaksi: tgl_transaksi,
				idSiswa: idSiswa
			},
			cache: false,
			success: function(msg) {
				$("#Cnorefrensi").html(msg);
			}
		});
	}

	function copy_data() {
		var tanggal = $("#tgl_transaksi").val();
		var noref = $("#Cnorefrensi").val();

		$("#thermal_tanggal").val(tanggal);
		$("#thermal_noref").val(noref);
	}
</script>

<script>
	$(".loader").hide();

	function change_kas_account(no) {
		var kas = $("#Cakunkas").val();

		$("#Akun_Kas_Bulanan" + no).val(kas);
		$("#Akun_Kas_Bebas" + no).val(kas);
		$("#Akun_Kas_Bebas_batch").val(kas);
	}

	function get_form() {
		var bebas_id = $('#msg:checked');
		if (bebas_id.length > 0) {
			var bebas_id_value = [];
			$(bebas_id).each(function() {
				bebas_id_value.push($(this).val());
			});

			$.ajax({
				url: 'admin/form/form_add_bebas.php',
				method: "POST",
				data: {
					bebas_id: bebas_id_value,
				},
				success: function(msg) {
					$("#fbatch").html(msg);
				},
				error: function(msg) {
					toastr["error"]("msg", "Gagal!");
				}
			});
		} else {
			$("#fbatch").html('');
			toastr["error"]("Belum ada pembayaran yang dipilih", "Gagal!");
		}
	}

	function trxFinish() {
		var view = $("#view").val();
		var nis = $("#nis_siswa").val();
		var period = $("#Ctahunajaran").val();
		var Cakunkas = $("#Cakunkas").val();
		var kas_noref = $("#kas_noref").val();

		if (kas_noref != '' && Cakunkas != '') {
			$.ajax({
				url: 'admin/simpan_transaksi_bayar.php',
				type: 'POST',
				data: {
					'Cakunkas': Cakunkas,
					'kas_noref': kas_noref,
					'nis_siswa': nis,
					'period': period,
				},
				beforeSend: function() {
					$(".loader").fadeIn("slow");
					$(".payment").fadeOut("slow");
				},
				success: function(msg) {
					//   var set = setInterval(function(){ window.location.href = '?view='+view+'&thn_ajar='+period+'&nis='+nis; }, 5000);
				},
				error: function(msg) {
					toastr["error"]("msg", "Gagal!");
				}
			});
		} else {
			toastr["error"]("Akun Kas Belum di Pilih", "Gagal!");
		}
	}
</script>