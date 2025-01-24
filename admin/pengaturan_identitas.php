<?php
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_oy = $idt['link_qr'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['status'];
if (isset($_POST['update'])) {

	$lokasi_file_kiri = $_FILES['flogokiri']['tmp_name'];
	$nama_file_kiri   = $_FILES['flogokiri']['name'];

	$lokasi_file_kanan = $_FILES['flogokanan']['tmp_name'];
	$nama_file_kanan  = $_FILES['flogokanan']['name'];

	$biayaAdmin = str_replace(".", "", $_POST['biayaAdmin']);

	// Apabila ada gambar yang diupload
	if (!empty($lokasi_file_kiri)) {

		UploadLogoKiri($nama_file_kiri);
		if (!empty($lokasi_file_kanan)) {
			UploadLogoKanan($nama_file_kanan);
			$query = mysqli_query($conn, "UPDATE identitas SET nmSekolah='$_POST[nmSekolah]',
												alamat='$_POST[alamat]',
												kabupaten='$_POST[kabupaten]',
												propinsi='$_POST[propinsi]',
												nmKepsek='$_POST[nmKepsek]',
												nipKepsek='$_POST[nipKepsek]',
												serverKey='$_POST[serverKey]',
												tema='$_POST[tema]',
												clientKey='$_POST[clientKey]',
												nmBendahara='$_POST[nmBendahara]',
												nipBendahara='$_POST[nuptk]',
												link='$_POST[link]',
												biaya_admin='$biayaAdmin',
												link_one_sender='$_POST[link_one_sender]',
												link_qr='$_POST[link_qr]',
												token='$_POST[token]',
												wa='$_POST[wa]',
												idnya='$_POST[idnya]',
												logo_kiri='$nama_file_kiri',
												logo_kanan='$nama_file_kanan'
									WHERE npsn = '$_POST[npsn]'");
		} else {
			$query = mysqli_query($conn, "UPDATE identitas SET nmSekolah='$_POST[nmSekolah]',
												alamat='$_POST[alamat]',
												kabupaten='$_POST[kabupaten]',
												propinsi='$_POST[propinsi]',
												nmKepsek='$_POST[nmKepsek]',
												nipKepsek='$_POST[nipKepsek]',
												serverKey='$_POST[serverKey]',
												tema='$_POST[tema]',
												clientKey='$_POST[clientKey]',
												link='$_POST[link]',
												biaya_admin='$biayaAdmin',
												link_one_sender='$_POST[link_one_sender]',
												link_qr='$_POST[link_qr]',
												token='$_POST[token]',
												wa='$_POST[wa]',
												idnya='$_POST[idnya]',
												nmBendahara='$_POST[nmBendahara]',
												nipBendahara='$_POST[nuptk]',
												logo_kiri='$nama_file_kiri'
									WHERE npsn = '$_POST[npsn]'");
		}
	} else {
		if (!empty($lokasi_file_kanan)) {

			UploadLogoKanan($nama_file_kanan);
			$query = mysqli_query($conn, "UPDATE identitas SET nmSekolah='$_POST[nmSekolah]',
												alamat='$_POST[alamat]',
												kabupaten='$_POST[kabupaten]',
												propinsi='$_POST[propinsi]',
												nmKepsek='$_POST[nmKepsek]',
												nipKepsek='$_POST[nipKepsek]',
												serverKey='$_POST[serverKey]',
												tema='$_POST[tema]',
												clientKey='$_POST[clientKey]',
												link='$_POST[link]',
												biaya_admin='$biayaAdmin',
												link_one_sender='$_POST[link_one_sender]',
												link_qr='$_POST[link_qr]',
												token='$_POST[token]',
												wa='$_POST[wa]',
												idnya='$_POST[idnya]',
												nmBendahara='$_POST[nmBendahara]',
													nipBendahara='$_POST[nuptk]',
												logo_kanan='$nama_file_kanan'
									WHERE npsn = '$_POST[npsn]'");
		} else {
			$query = mysqli_query($conn, "UPDATE identitas SET nmSekolah='$_POST[nmSekolah]',
												alamat='$_POST[alamat]',
												kabupaten='$_POST[kabupaten]',
												propinsi='$_POST[propinsi]',
												nmKepsek='$_POST[nmKepsek]',
												nipKepsek='$_POST[nipKepsek]',
												serverKey='$_POST[serverKey]',
												tema='$_POST[tema]',
												clientKey='$_POST[clientKey]',
												link='$_POST[link]',
												biaya_admin='$biayaAdmin',
												link_one_sender='$_POST[link_one_sender]',
												link_qr='$_POST[link_qr]',
												token='$_POST[token]',
												wa='$_POST[wa]',
												idnya='$_POST[idnya]',
												nmBendahara='$_POST[nmBendahara]',
													nipBendahara='$_POST[nuptk]'
									WHERE npsn = '$_POST[npsn]'");
		}
	}

	if ($query) {
		echo "<div class='col-md-12'><div class='alert alert-success alert-dismissible fade in' role='alert'> 
		  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		  <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Disimpan..
		  </div></div>";
	} else {
		echo "<div class='col-md-12'><div class='alert alert-danger alert-dismissible fade in' role='alert'> 
		  <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
		  <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data gagal disimpan...
		  </div></div>";
	}
} elseif ($_GET['act'] == 'onoff') {
	$a = $_GET['a'];
	$query = mysqli_query($conn, "UPDATE identitas SET statusWa='$a' where npsn = '$_GET[id]'");
	if ($query) {
		echo "<script>document.location='index.php?view=pengaturan';</script>";
	} else {
		echo "<script>document.location='index.php?view=pengaturan';</script>";
	}
} elseif (isset($_POST['qr'])) {

	$data = [
		'device' => $number_send,
		'api_key' => $token_send, //the number you want to connect, will be added to the database if it is not registered.

	];
	$curl = curl_init();

	curl_setopt_array($curl, array(
		CURLOPT_URL => $link_oy,
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
	echo $response;
}
$edit = mysqli_query($conn, "SELECT * FROM identitas where npsn='10700295'");
$record = mysqli_fetch_array($edit);
if ($record['statusWa'] == 'T') {
	$a = 'Y';
	$icon = "fa-close";
	$btn = "btn-danger";
	$alt = "Aktifkan";
	$onoff = "<a class='btn $btn btn-sm' title='$alt' href='?view=pengaturan&act=onoff&id=$record[npsn]&a=$a'><span class='fa $icon'></span> <b>Non Aktif</b></a>";
} else {
	$a = 'T';
	$icon = "fa-check";
	$btn = "btn-success";
	$onoff = "<a class='btn $btn btn-sm' href='?view=pengaturan&act=onoff&id=$record[npsn]&a=$a'><span class='fa $icon'></span> <b>Aktif</b></a>";
}
if ($_SESSION['notif'] == 'reset_sukses') {
	echo '<script>toastr["success"]("Database berhasil direset.","Selamat!")</script>';
} elseif ($_SESSION['notif'] == 'gagal') {
	echo '<script>toastr["error"]("Data gagal direset.","Gagal!")</script>';
}
unset($_SESSION['notif']);
if (isset($_POST['reset'])) {

	$reset = mysqli_query($conn, "TRUNCATE TABLE tagihan_bulanan_bayar");
	$reset = mysqli_query($conn, "TRUNCATE TABLE tagihan_bulanan");
	$reset = mysqli_query($conn, "TRUNCATE TABLE tagihan_bebas_bayar");
	$reset = mysqli_query($conn, "TRUNCATE TABLE tagihan_bebas");
	$reset = mysqli_query($conn, "TRUNCATE TABLE jenis_bayar");
	$reset = mysqli_query($conn, "TRUNCATE TABLE pos_bayar");
	$reset = mysqli_query($conn, "TRUNCATE TABLE tahun_ajaran");
	$reset = mysqli_query($conn, "TRUNCATE TABLE siswa");
	$reset = mysqli_query($conn, "TRUNCATE TABLE kelas_siswa");
	$reset = mysqli_query($conn, "TRUNCATE TABLE jurnal_umum");
	$reset = mysqli_query($conn, "TRUNCATE TABLE payment");
	$reset = mysqli_query($conn, "TRUNCATE TABLE transaksi");
	$reset = mysqli_query($conn, "TRUNCATE TABLE hutangtoko");
	$reset = mysqli_query($conn, "TRUNCATE TABLE angsurantoko");
	$reset = mysqli_query($conn, "TRUNCATE TABLE kwitansi");

	if ($reset) {
		$_SESSION['notif'] = 'reset_sukses';
		echo "<script>document.location='?view=$_GET[view]';</script>";
	} else {
		$_SESSION['notif'] = 'gagal';
		echo "<script>document.location='?view=$_GET[view]';</script>";
	}
}
$sqlPolygon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rb_absensi_setting WHERE type='polygonSetting'"));
$listPolygon = base64_encode(json_encode(isset($sqlPolygon) ? json_decode($sqlPolygon['value']) : []));

if (isset($_POST['type']) && $_POST['type'] == 'polygonSetting') {
	foreach ($_POST['lat'] as $i => $v) {
		$dataPolygon[] = [
			'lat' => $v,
			'lng' => $_POST['lng'][$i]
		];
	}
	mysqli_query($conn, "INSERT INTO rb_absensi_setting(type, value) VALUES('" . $_POST['type'] . "', '" . json_encode($dataPolygon) . "')");
	echo '<script>window.location="";</script>';
}

if (isset($_GET['act']) && $_GET['act'] == 'ulangpolygon') {
	mysqli_query($conn, "DELETE FROM rb_absensi_setting WHERE type='polygonSetting'");
	echo '<script>window.location="?view=pengaturan";</script>';
}

$sqlWaktu = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rb_absensi_setting WHERE type='waktuSetting'"));
$listWaktu = (isset($sqlWaktu) ? explode(',', $sqlWaktu['value']) : []);

if (isset($_POST['type']) && $_POST['type'] == 'waktuSetting') {
	mysqli_query($conn, "DELETE FROM rb_absensi_setting WHERE type='waktuSetting'");
	mysqli_query($conn, "INSERT INTO rb_absensi_setting(type, value) VALUES('" . $_POST['type'] . "', '" . $_POST['jam_masuk'] . ',' . $_POST['jam_pulang'] . "')");
	echo '<script>window.location="";</script>';
}
?>

<div class="col-md-8">
	<div class="box box-primary box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"> Pengaturan Sekolah</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
				<input type="hidden" name="npsn" value="<?php echo $record['npsn']; ?>">
				<div class="col-sm-6">
					<label for="" class=" control-label">Nama Sekolah</label>

					<input type="text" name="nmSekolah" class="form-control" value="<?php echo $record['nmSekolah']; ?>" required readonly>

				</div>
				<div class="col-sm-6">
					<label for="" class=" control-label">Alamat Sekolah</label>

					<input type="text" name="alamat" class="form-control" value="<?php echo $record['alamat']; ?>" required readonly>

				</div>
				<div class="col-sm-6">
					<label for="" class=" control-label">Kabupaten/Kota</label>

					<input type="text" name="kabupaten" class="form-control" value="<?php echo $record['kabupaten']; ?>" required readonly>

				</div>
				<div class="col-sm-6">
					<label for="" class=" control-label">Propinsi</label>

					<input type="text" name="propinsi" class="form-control" value="<?php echo $record['propinsi']; ?>" required readonly>

				</div>

				<div class="col-sm-6">
					<label for="" class=" control-label">Nama Kepsek</label>

					<input type="text" name="nmKepsek" class="form-control" value="<?php echo $record['nmKepsek']; ?>" required readonly>

				</div>
                <div class="col-sm-6">
					<label for="" class=" control-label">NRKS</label>

					<input type="text" name="nipKepsek" class="form-control" value="<?php echo $record['nipKepsek']; ?>" required readonly>

				</div>
				<div class="col-sm-6">
					<label for="" class="control-label">Nama Bendahara</label>

					<input type="text" name="nmBendahara" class="form-control" value="<?php echo $record['nmBendahara']; ?>" required readonly>

				</div>
            <div class="col-sm-6">
					<label for="" class=" control-label">NUPTK</label>

					<input type="text" name="nuptk" class="form-control" value="<?php echo $record['nipBendahara']; ?>" required readonly>

				</div>
				<div class="col-sm-6">
					<label for="" class=" control-label">Ganti Logo Kiri</label>

					<input type="file" name="flogokiri" class="form-control">

				</div>

				<div class="col-sm-6">
					<label for="" class="control-label">Ganti Logo Kanan</label>

					<input type="file" name="flogokanan" class="form-control">

				</div>

				<div class="col-sm-6">
					<label for="" class="control-label">Client Key Midtrans</label>

					<input type="text" name="clientKey" class="form-control" value="<?php echo $record['clientKey']; ?>"  >

				</div>

				<div class="col-sm-6">
					<label for="" class=" control-label">Server Key Midtrans</label>

					<input type="text" name="serverKey" class="form-control" value="<?php echo $record['serverKey']; ?>" >

				</div>

				<div class="col-sm-6">
					<label for="" class=" control-label">Link Snap JS</label>
					<select class="form-control" name="link">
						<option value="https://app.sandbox.midtrans.com/snap/snap.js" <?= $record['link'] == 'https://app.sandbox.midtrans.com/snap/snap.js' ? 'selected' : ''; ?>>Sandbox</option>
						<option value="https://app.midtrans.com/snap/snap.js" <?= $record['link'] == 'https://app.midtrans.com/snap/snap.js' ? 'selected' : '' ?> >Production</option>
					</select>

				</div>
				<div class="col-sm-6">
					<label for="" class=" control-label">Biaya Admin</label>
					<input type="text" name="biayaAdmin" id="biayaAdmin" class="form-control" value="<?php echo number_format($record['biaya_admin'], 0, ',', '.') ?>" required readonly>
				</div>

				<div class="col-sm-6">
					<label for="" class="control-label">Link WhatsApp </label>

					<input type="text" name="link_one_sender" class="form-control" value="<?php echo $record['link_one_sender']; ?>" readonly>
				</div>
				<div class="col-sm-6">
					<label for="" class="control-label">Link QR </label>

					<input type="text" name="link_qr" class="form-control" value="<?php echo $record['link_qr']; ?>" readonly>
				</div>
				<div class="col-sm-6">
					<label for="" class=" control-label">Token WhatsApp </label>
					<input type="text" name="token" class="form-control" value="<?php echo $record['token']; ?>" readonly>
				</div>

				<div class="col-sm-6">
					<label for="" class=" control-label">Nomor Pengirim </label>

					<input type="text" name="wa" class="form-control" value="<?php echo $record['wa']; ?>" placeholder="62..." >

				</div>

				<input type="hidden" name="idnya" class="form-control" value="<?php echo $record['idnya']; ?>" placeholder="..." readonly>


				<div class="col-sm-12">
					<label for="" class=" control-label">Tema Aplikasi</label>
					<select class="form-control" name="tema">
						<option value="<?php echo $record['tema']; ?>"><?php echo $record['tema']; ?></option>
						<option value="yellow">Yellow</option>
						<option value="yellow-light">Yellow Light</option>
						<option value="green">Green</option>
						<option value="green-light">Green Light</option>
						<option value="black">Black</option>
						<option value="black-light">Black Light</option>
						<option value="purple">Purple</option>
						<option value="purple-light">Purple Light</option>
						<option value="red">Red</option>
						<option value="red-light">Red Light</option>

					</select>

				</div>

				<div style='clear:both'></div>
				<div class='box-footer'>
					<input type="submit" name="update" value="Simpan Data" class="btn btn-success pull-left">
					<a href="index.php?view=pengaturan" class="btn btn-default pull-right">Cancel</a>
				</div>
			</form>
			<div style='clear:both'></div>



		</div>
	</div>
</div>
<div class="col-md-4">
	<div class="box box-primary box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"> Reset Database</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
				<input type="submit" name="reset" value="Reset Data" onclick="return confirm('Anda Yakin Ingin Mereset Database?')" class="btn btn-danger pull-left" disabled>
			</form>
		</div>
	</div>
</div>

<div class="col-md-4">
	<div class="box box-danger box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"> Sambungkan WhatsApp Gateway</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<h5>Tombol ini untuk menyambungkan whatsapp gateway anda</h5>
			<form method="post" action="?view=pengaturan_wa" class="form-horizontal" enctype="multipart/form-data">
				<div class='box-footer'>
					<table id="example" class="table table-bordered table-striped table-condensed">
						<thead>
							<tr>
								<th>Nomor</th>
								<th>Status</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							$tampil = mysqli_query($conn, "SELECT * FROM identitas ");
							while ($r = mysqli_fetch_array($tampil)) {
								echo "<tr>
								<td>" . $r['wa'] . "</td>
								";
								if ($idt['status'] == 'T') {
									echo '<td><a type="submit" value="" class="btn btn-danger btn-sm pull-left">  Disconnected</a></td><td>
									<button type="submit" name="qr" value="" class="btn btn-warning btn-l"> <span class="fa fa-qrcode"> </span> </button></td>';
								} else {
									echo '<td><a type="submit"  value="" class="btn btn-success btn-sm pull-left">  Connected</a></td><td>
									<button type="submit" name="qr" value="" class="btn btn-warning btn-l"> <span class="fa fa-qrcode"> </span>  </button></td>';
								}
								echo
								"</td>
								";
								echo "</tr>";
								$no++;
							}
							if (isset($_GET['hapus'])) {
								mysqli_query($conn, "DELETE FROM siswa where idSiswa='$_GET[id]'");
								echo "<script>document.location='index.php?view=siswa';</script>";
							}

							?>
						</tbody>
					</table>
					<div class="center">
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<style>
	.center {
		text-align: center !important;
	}
</style>
<script>
	function klik() {
		alert('Apakah anda yakin akan mereset data?');
	}
</script>
<div class="col-md-4">
	<div class="box box-warning box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"> Logo Kop Surat</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<table class="table table-bordered">
				<tr class="danger">
					<th class="text-center">Logo Kiri</th>
				</tr>
				<tr>
					<td class="text-center">
						<img src="./gambar/logo/<?php echo $record['logo_kiri']; ?>" width="160px">
					</td>
				</tr>
			</table>
			<hr>
			<table class="table table-bordered">
				<tr class="success">
					<th class="text-center">Logo Kanan</th>
				</tr>
				<tr>
					<td class="text-center">
						<img src="./gambar/logo/<?php echo $record['logo_kanan']; ?>" width="160px">
					</td>
				</tr>
			</table>
		</div>
	</div>
</div>

<div class="col-md-12">
	<div class="box box-primary box-solid">
		<div class="box-header">
			<h3 class="box-title">Atur Radius Absensi</h3>
		</div>
		<div class="box-body">

			<div id="map" style="width: 100%; height: 300px"></div>
			<form method="POST">
				<input type="text" name="type" value="polygonSetting" hidden>
				<span id="parent-polygon"></span>
				<div class="text-center" style="margin-top: 1rem;">
					<a href="?view=pengaturan&act=ulangpolygon" id="ulangPolygon" class="btn btn-warning" style="display: <?= isset($sqlPolygon) ? 'inline' : 'none' ?>;">Polygon Ulang</a>
					<button type="button" id="resetPolygon" class="btn btn-danger" style="display: none;">Reset</button>
					<input type="submit" id="simpanPolygon" class="btn btn-info" value="Simpan" style="display: none;">
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	var biayaAdmin = document.getElementById('biayaAdmin');
	biayaAdmin.addEventListener('keyup', function(e) {
		biayaAdmin.value = formatRupiah(this.value);
	});

	function formatRupiah(angka, prefix) {
		var number_string = angka.replace(/[^,\d]/g, '').toString(),
			split = number_string.split(','),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

		if (ribuan) {
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}

	// MAPS
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(function(position) {
			// var mapOptions = {
			// 	center: [position.coords.latitude, position.coords.longitude],
			// 	zoom: 6
			// }
			// Creating a map object
			// var map = new L.map('map', mapOptions);
			// // Creating a Layer object
			// var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

			// // Adding layer to the map
			// map.addLayer(layer);

			var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 18);
			var layer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoicml2YW5pIiwiYSI6ImNscTYzeWZyYzBneGYya252NWczemNkNjEifQ.m2iojpd1aoNA9sfsFDKQLw', {
				maxZoom: 18,
				attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
					'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
				id: 'mapbox/satellite-v9',
				tileSize: 512,
				zoomOffset: -1
			}).addTo(map);
			// var dataMarker = [];

			var listPolygon = [];
			JSON.parse(atob('<?= $listPolygon ?>')).forEach(item => {
				listPolygon.push([item.lat, item.lng]);
			});

			L.polygon(listPolygon).addTo(map);

			var initPolygon = null;
			var newPolygon = [];
			map.on('click', function(position) {
				var latlng = [position.latlng.lat, position.latlng.lng];
				// var newMarker = new L.Marker([position.latlng.lat, position.latlng.lng]);
				// dataMarker.push(newMarker);
				// newMarker.addTo(map)
				// console.log([position.latlng.lat, position.latlng.lng]);
				$("#parent-polygon").append(`<input type='text' value='${latlng[0]}' name='lat[]' hidden><input type='text' value='${latlng[1]}' name='lng[]' hidden>`)
				newPolygon.push(latlng);

				if (initPolygon != null) {
					map.removeLayer(initPolygon);
				}

				initPolygon = L.polygon(newPolygon);
				initPolygon.addTo(map);

				if (newPolygon.length > 0) {
					document.getElementById("resetPolygon").style = 'display: inline;';
				} else {
					document.getElementById("resetPolygon").style = 'display: none;';
				}

				if (newPolygon.length >= 3) {
					document.getElementById("simpanPolygon").style = 'display: inline;';
				} else {
					document.getElementById("simpanPolygon").style = 'display: none;';
				}
			});

			document.getElementById("resetPolygon").addEventListener('click', function(e) {
				e.preventDefault();
				if (initPolygon != null) map.removeLayer(initPolygon);
				initPolygon = null;
				newPolygon = [];
				$("#parent-polygon").html('');

				document.getElementById("simpanPolygon").style = 'display: none;';
				this.style = 'display: none';
			})

			var mymarker = new L.Marker([position.coords.latitude, position.coords.longitude]);
			mymarker.addTo(map);
			// map.setView([position.coords.latitude, position.coords.longitude], 5, {
			// 	animation: true
			// });
		})
	}
</script>