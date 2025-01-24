<?php
    include '../../config/koneksi.php';
    include '../../config/variabel_default.php';
    header('Content-type: application/json');

    $jenisTagihan = $_POST['jenis'];
    $idTagihan = $_POST['tagihan'];
    $idSiswa = $_POST['siswa'];
    $nominal = $_POST['nominal'];
    
    $cek_payment = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM payment WHERE jenisTagihan='$jenisTagihan' AND idTagihan='$idTagihan' AND idSiswa='$idSiswa' AND paymentOrder='' "));
    if ($cek_payment == 0){

        $add = mysqli_query($conn,"INSERT INTO payment(tglPayment,paymentOrder,idSiswa,jenisTagihan,idTagihan,nominal) VALUES ('$waktu_sekarang','','$idSiswa','$jenisTagihan','$idTagihan','$nominal')");
        if ($add){
            $response['notif'] = 'success'; 
        }else{
            $response['notif'] = 'errorTagihan'; 
        }
    }else{
        $response['notif'] = 'errorAdd'; 
    }
    
    echo json_encode($response);
