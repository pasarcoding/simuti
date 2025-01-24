<?php
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_oy = $idt['link_qr'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['status'];

if (isset($_POST['qr'])) {

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

}

?>
<div class="col-xs-6">
	<div class="box box-info box-solid">
		<div class="box-header with-border">
			<!-- tools box -->
			<div class="pull-right box-tools">
			</div>
			<!-- /. tools -->
			<h3 class="box-title">Refresh Halaman Setelah Menscan QR code</h3>
			<button type="button" class="btn btn-danger pull-right" onClick="document.location.reload(true)" ><i class="fa fa-refresh" > </i>Refresh</button>
		</div><!-- /.box-header -->
		<div class="box-body">
			<form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
				<div class="center">
					<?php
					if (json_decode($response)->message == processing) {
						$pesan = json_decode($response)->message;
						$gambar = json_decode($response)->qrcode;
						echo '<button type="submit" name="qr" value="" class="btn btn-danger ">Silahkan Klik tombol QR</button>';
					} elseif (json_decode($response)->status == false) {
						$query = mysqli_query($conn, "UPDATE identitas SET 
												status='Y'");
						$pesan = json_decode($response)->msg;
				// 		$gambar = json_decode($response)->msg;
					
						$pp = '<a type="" name="" value="" class="btn btn-warning ">Kembali</a>';
						echo '<button type="submit" name="qr" value="" class="btn btn-success "> ' . $pesan . '</button>';
					} elseif (json_decode($response)->status == true) {
						$query = mysqli_query($conn, "UPDATE identitas SET 
												status='Y'");
						$pesan = json_decode($response)->message;
						$a = json_decode($response)->imageee;
						echo '<button type="submit" name="qr" value="" class="btn btn-success "><span class="fa fa-whatsapp"> </span> WhatsApp ' . $pesan . '</button>';
					}
					?>
			</form>
		</div>
		<div class="center">
			<img src="<?php echo $gambar; ?>" />
			<img src="<?php echo $a; ?>" />
		</div>
		<div class="box-footer">
			<a href="index.php?view=pengaturan" class="btn btn-info pull-right"><span class="fa fa-reply"></span> Kembali</a>
		</div>
	</div>
</div>

<style>
	.center {
		text-align: center !important;
	}
</style>