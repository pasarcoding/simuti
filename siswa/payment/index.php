<?php
    include '../../config/koneksi.php';
    header('Content-type: application/json');

    $idSiswa = $_POST['siswa'];
    
    $cek_payment = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM payment WHERE idSiswa='$idSiswa' AND paymentOrder=''"));
    if ($cek_payment == 0){
        echo '0';
    }else{
        echo $cek_payment;
    }

?>
  