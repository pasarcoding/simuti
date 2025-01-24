<?php
    
    // This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
    // Please refer to this docs for snap popup:
    // https://docs.midtrans.com/en/snap/integration-guide?id=integration-steps-overview

    namespace Midtrans;

    require_once(dirname(__FILE__) . '/../../vendor/autoload.php');

    include "../../config/koneksi.php";
    include "../../config/library.php";

    $siswa = $_POST['sws'];
    $invoice = $_POST['invoice'];

    $idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas"));

    //Set Your server key
    Config::$serverKey = $idt['serverKey'];

    // Uncomment for production environment
    if ($idt['link'] == 'https://app.sandbox.midtrans.com/snap/snap.js'){
        Config::$isProduction = false;
    }else{
        Config::$isProduction = true;
    }
    
    // Enable sanitization
    Config::$isSanitized = true;

    // Enable 3D-Secure
    Config::$is3ds = true;


    $sws = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM siswa WHERE idSiswa='$siswa'"));

    $totalNominal = 0;
    $totalNominal += $idt['biaya_admin'];
    $payment = mysqli_query($conn,"SELECT * FROM payment WHERE idSiswa='$siswa' AND paymentOrder=''");
    while($pymt = mysqli_fetch_array($payment)){

        if ($pymt['jenisTagihan'] == 'Bulanan'){

            $sqlbulanan = mysqli_fetch_array(mysqli_query($conn,"SELECT tagihan_bulanan.*,
                                                              jenis_bayar.idPosBayar,
                                                              jenis_bayar.nmJenisBayar,
                                                              pos_bayar.nmPosBayar,
                                                              tahun_ajaran.nmTahunAjaran,
                                                              bulan.nmBulan, 
                                                              bulan.urutan
                                                              FROM tagihan_bulanan
                                                              LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
                                                              LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                                                              LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                                              LEFT JOIN bulan ON tagihan_bulanan.idBulan=bulan.idBulan
                                                              WHERE tagihan_bulanan.idTagihanBulanan='$pymt[idTagihan]'"));
            $pisah_TA = explode('/', $sqlbulanan['nmTahunAjaran']);
            if ($sqlbulanan['urutan'] <= 6) {
              $tahun = $pisah_TA[0];
            } else {
              $tahun = $pisah_TA[1];
            }
            $namaTagihanBulanan = 'Pembayaran '.$sqlbulanan['nmJenisBayar']." (".$sqlbulanan['nmBulan']." ".$tahun.") T.A ".$sqlbulanan['nmTahunAjaran'];

            $item[] = [
                'id'=>'BLN-'.$pymt['idTagihan'],
                'price'=>$pymt['nominal'],
                'quantity' => 1,
                'name' => $namaTagihanBulanan
            ];

        }else{

            $sqlbebas = mysqli_fetch_array(mysqli_query($conn,"SELECT
                                tagihan_bebas.*,
                                jenis_bayar.idPosBayar,
                                jenis_bayar.nmJenisBayar,
                                pos_bayar.nmPosBayar,
                                tahun_ajaran.nmTahunAjaran
                              FROM
                                tagihan_bebas
                              INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                              INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                              INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                              WHERE tagihan_bebas.idTagihanBebas='$pymt[idTagihan]'"));
            $namaTagihanBebas = 'Pembayaran '.$sqlbebas['nmJenisBayar']." T.A ".$sqlbebas['nmTahunAjaran'];

            $item[] = [
                'id'=>'BBS-'.$pymt['idTagihan'],
                'price'=>$pymt['nominal'],
                'quantity' => 1,
                'name' => $namaTagihanBebas
            ];

        }

        $totalNominal += $pymt['nominal'];

    }

    $item[] = [
        'id'=>'BA-1',
        'price'=>$idt['biaya_admin'],
        'quantity' => 1,
        'name' => 'Biaya Admin'
    ];

    // Required
    $transaction_details = array(
        'order_id' => $invoice,
        'gross_amount' => $totalNominal,
        
    );
        
    $item_details = $item;

    // Optional
    $billing_address = array(
        'first_name'    => $sws['nmSiswa'],
        'address'       => $sws['alamatWali'],
        'phone'         => $sws['noHpOrtu'],
    );
    
    /*
    // Optional
    $shipping_address = array(
        'first_name'    => "Obet",
        'last_name'     => "Supriadi",
        'address'       => "Manggis 90",
        'city'          => "Jakarta",
        'postal_code'   => "16601",
        'phone'         => "08113366345",
        'country_code'  => 'IDN'
    );
    */

    // Optional
    $customer_details = array(
        'first_name'    => $sws['nmSiswa'],
        'last_name'     => null,
        'email'         => null,
        'phone'         => $sws['noHpOrtu'],
    );
   
    // Fill transaction details
    $transaction = array(
       
        'transaction_details' => $transaction_details,
        'customer_details' => $customer_details,
        'item_details' => $item_details,
       
    );

       
    $snapToken = Snap::getSnapToken($transaction);
    echo json_encode($snapToken);
    

?>