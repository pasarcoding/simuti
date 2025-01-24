<?php
    include '../../config/koneksi.php';
    include '../../config/variabel_default.php';
    header('Content-type: application/json');

    $idPayment = $_POST['payment'];
    
    $delete = mysqli_query($conn,"DELETE FROM payment WHERE idPayment='$idPayment'");
    if ($delete){
        $response['notif'] = 'success'; 
    }else{
        $response['notif'] = 'error'; 
    }
    
    echo json_encode($response);
?>
  