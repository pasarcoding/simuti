<?php

	include '../../config/koneksi.php';
	include '../../config/variabel_default.php';

	$siswa = $_POST['siswa'];
	$invoice = $_POST['invoice'];
	$statusBayar = '1';//success
	$ketBayar = str_replace('"','',$_POST['status']);//success
    $caraBayar = 'Transfer Midtrans';
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
                mysqli_query($conn,"UPDATE payment SET paymentOrder='$invoice', status='success', noPayment='$noPayment' WHERE idSiswa='$siswa' AND idPayment='$pyt[idPayment]'");
            }else{
                mysqli_query($conn,"UPDATE payment SET paymentOrder='$invoice', status='success' WHERE idSiswa='$siswa' AND idPayment='$pyt[idPayment]'");
            }
            
    		if ($pyt['jenisTagihan'] == 'Bulanan'){
    			mysqli_query($conn,"UPDATE tagihan_bulanan SET tglBayar='$waktu_sekarang', statusBayar='$statusBayar', inv='$invoice', caraBayar='$caraBayar' WHERE idSiswa='$siswa' AND idTagihanBulanan ='$pyt[idTagihan]'");
    		} else if ($pyt['jenisTagihan'] == 'Bebas') {
                mysqli_query($conn,"INSERT INTO tagihan_bebas_bayar(idTagihanBebas,tglBayar,jumlahBayar,ketBayar,caraBayar) VALUES ('$pyt[idTagihan]','$waktu_sekarang','$pyt[nominal]','$ketBayar','$caraBayar')");

                $tagBebas = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tagihan_bebas WHERE idSiswa='$siswa' AND idTagihanBebas='$pyt[idTagihan]'"));
                $tagBebasBayar = mysqli_fetch_array(mysqli_query($conn,"SELECT SUM(jumlahBayar) as totalBayar FROM tagihan_bebas_bayar WHERE idTagihanBebas='$pyt[idTagihan]'"));
                if ($tagBebas['totalTagihan'] == $tagBebasBayar['totalBayar']){
                    $statusByr = '1';
                }elseif ($tagBebas['totalTagihan'] > $tagBebasBayar['totalBayar']){
                    $statusByr = '2';
                }else{
                    $statusByr = '0';
                }
                mysqli_query($conn,"UPDATE tagihan_bebas SET statusBayar='$statusByr', ref='$invoice' WHERE idSiswa='$siswa' AND idTagihanBulanan ='$pyt[idTagihan]'");
    		}
    		
    		 $qPaymentSukses = mysqli_query($conn, "SELECT payment.*, sum(nominal) as tot FROM payment WHERE paymentOrder='$invoice' GROUP BY paymentOrder LIMIT 1");
    if (mysqli_num_rows($qPaymentSukses) <> 0) {

        $pay = mysqli_fetch_array($qPaymentSukses);
        $idsss = $pay['idSiswa'];
        $siss = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idSiswa='$idsss'"));
        $nams = $siss['nmSiswa'];
        $klss = $siss['nmKelas'];
        $jml = ($pay['tot'] + $idt['biaya_admin']);
        $no = $siss['noHpOrtu'];
        $nos = $siss['noHpSis'];
        $tgl = $pay['tglPayment'];
        $pos = $pay['jenisTagihan'];
        $bank = $pay['tipePayment'];
        $nobank = $pay['noPayment'];
        $msg_wa = array();
        $number_wa = array();

        $number_wa[] = $nos;
        $msg_wa[] = 'Terima Kasih, Pembayaran Sekolah siswa a/n:
                    
Nama  : *' . $nams . '*
Kelas : *' . $klss . '* 
Jumlah: *' . str_replace(".", ",", buatRp($jml)) . '*
Pada  : *' . $tgl . '* 
VA/E-Money :' . $bank . '
Nomor      : ' . $nobank . '

*BERHASIL*';

        $number_wa[] = $no;
        $msg_wa[] = 'Terima Kasih, Pembayaran Sekolah anak anda 
                    
Nama  : *' . $nams . '*
Kelas : *' . $klss . '* 
Jumlah: *' . str_replace(".", ",", buatRp($jml)) . '*
Pada  : *' . $tgl . '* 
VA/E-Money :' . $bank . '
Nomor      : ' . $nobank . '

*BERHASIL*';

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
    }
    	}
        
    }
?>
