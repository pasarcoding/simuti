<?php

	include '../../config/koneksi.php';
	include '../../config/variabel_default.php';

	$siswa = $_POST['siswa'];
	$invoice = $_POST['invoice'];
	$statusBayar = '2';//pending
	$ketBayar = str_replace('"','',$_POST['status']);
	$caraBayar = 'Transfer';
	$idtransaksi = $_POST['idtransaksi'];
	$tipe = $_POST['tipe'];

	$noPayment = '';
	$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas LIMIT 1"));
	 $link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
	if ($tipe == 'gopay'){
		if ($idt['link'] == 'https://app.sandbox.midtrans.com/snap/snap.js'){
			$noPayment = 'https://api.sandbox.midtrans.com/v2/gopay/'.$idtransaksi.'/qr-code';
		}else{
			$noPayment = 'https://api.midtrans.com/v2/gopay/'.$idtransaksi.'/qr-code';
		}
	}elseif ($tipe == 'qris'){
		//shoppepay
		if ($idt['link'] == 'https://app.sandbox.midtrans.com/snap/snap.js'){
			$noPayment = 'https://api.sandbox.midtrans.com/v2/qris/shopeepay/sppq_'.$idtransaksi.'/qr-code';
		}else{
			$noPayment = 'https://api.midtrans.com/v2/qris/shopeepay/sppq_'.$idtransaksi.'/qr-code';
		}
	}
    
	$qPayment = mysqli_query($conn,"SELECT * FROM payment WHERE idSiswa='$siswa' AND paymentOrder=''");
    if (mysqli_num_rows($qPayment) <> 0){
    	while($pyt=mysqli_fetch_array($qPayment)) {
    		if ($noPayment <> ''){
    			mysqli_query($conn,"UPDATE payment SET paymentOrder='$invoice', status='pending', noPayment='$noPayment' WHERE idSiswa='$siswa' AND idPayment='$pyt[idPayment]'");
    		}else{
    			mysqli_query($conn,"UPDATE payment SET paymentOrder='$invoice', status='pending' WHERE idSiswa='$siswa' AND idPayment='$pyt[idPayment]'");
    		}

    		if ($pyt['jenisTagihan'] == 'Bulanan'){
    			mysqli_query($conn,"UPDATE tagihan_bulanan SET tglBayar='$waktu_sekarang', statusBayar='2', inv='$invoice', caraBayar='$caraBayar'  WHERE idSiswa='$siswa' AND idTagihanBulanan ='$pyt[idTagihan]'");
    		} else if ($pyt['jenisTagihan'] == 'Bebas') {
                mysqli_query($conn,"UPDATE tagihan_bebas SET statusBayar='2', ref='$invoice' WHERE idSiswa='$siswa' AND idTagihanBebas ='$pyt[idTagihan]'");
    		}
    		

    	}
    }
 
?>
