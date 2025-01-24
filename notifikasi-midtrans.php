<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for sample HTTP notifications:
// https://docs.midtrans.com/en/after-payment/http-notification?id=sample-of-different-payment-channels

namespace Midtrans;

require_once(dirname(__FILE__) . '/vendor/autoload.php');

include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_payment.php";
include "config/fungsi_wa.php";

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];


if ($idt['link'] == 'https://app.sandbox.midtrans.com/snap/snap.js') {
    Config::$isProduction = false;
} else {
    Config::$isProduction = true;
}

Config::$serverKey = $idt['serverKey'];

try {
    $notif = new Notification();
} catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;
$transaction_time = $notif->transaction_time;

$datres = cek_payment($order_id, $idt['serverKey'], $idt['link']);
$type = $datres['payment_type'];
$tipe_transaksi = '';
if ($type == 'cstore') {
    $tipe_transaksi = $datres['store'];
    $kodebayar_transaksi = $datres['payment_code'];
    mysqli_query($conn, "UPDATE payment SET noPayment='$kodebayar_transaksi' WHERE paymentOrder='$order_id'");
} elseif ($type == 'bank_transfer') {
    $permata_va_number = isset($datres['permata_va_number']) ? $datres['permata_va_number'] : '';
    if ($permata_va_number <> '') {
        $tipe_transaksi = 'permata';
        $kodebayar_transaksi = $permata_va_number;
    } else {
        $tf = $datres['va_numbers'][0];
        $tipe_transaksi = $tf['bank'];
        $kodebayar_transaksi = $tf['va_number'];
    }
    mysqli_query($conn, "UPDATE payment SET noPayment='$kodebayar_transaksi' WHERE paymentOrder='$order_id'");
} elseif ($type == 'echannel') {
    $tipe_transaksi = 'mandiri';
    $kodebayar_transaksi = $datres['bill_key'];
    mysqli_query($conn, "UPDATE payment SET noPayment='$kodebayar_transaksi' WHERE paymentOrder='$order_id'");
} elseif ($type == 'gopay' || $type == 'qris' || $type == 'shopeepay' || $type == 'akulaku') {
    $tipe_transaksi = $type;
} elseif ($type == 'bca_klikpay') {
    $tipe_transaksi = 'BCA KlikPay';
} elseif ($type == 'bca_klikbca') {
    $tipe_transaksi = 'BCA KlikBCA';
} elseif ($type == 'cimb_clicks') {
    $tipe_transaksi = 'CIMB Clicks';
} elseif ($type == 'danamon_online') {
    $tipe_transaksi = 'Danamon Online';
} elseif ($type == 'bri_epay') {
    $tipe_transaksi = 'BRI Epay';
}
if ($tipe_transaksi <> '') {
    mysqli_query($conn, "UPDATE payment SET tipePayment='$tipe_transaksi' WHERE paymentOrder='$order_id'");
}


if ($transaction == 'capture') {
    // For credit card transaction, we need to check whether transaction is challenge by FDS or not
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            // TODO set payment status in merchant's database to 'Challenge by FDS'
            // TODO merchant should decide whether this transaction is authorized or not in MAP
            echo "Transaction order_id: " . $order_id . " is challenged by FDS";
        } else {
            // TODO set payment status in merchant's database to 'Success'
            echo "Transaction order_id: " . $order_id . " successfully captured using " . $type;
        }
    }
} else if ($transaction == 'settlement') {
    // TODO set payment status in merchant's database to 'Settlement'
    //echo "Transaction order_id: " . $order_id ." successfully transfered using " . $type;

    $qPayment = mysqli_query($conn, "SELECT * FROM payment WHERE paymentOrder='$order_id'");
    if (mysqli_num_rows($qPayment) <> 0) {
        while ($pyt = mysqli_fetch_array($qPayment)) {
            mysqli_query($conn, "UPDATE payment SET status='success' WHERE idPayment='$pyt[idPayment]'");

            if ($pyt['jenisTagihan'] == 'Bulanan') {

                mysqli_query($conn, "UPDATE tagihan_bulanan SET statusBayar='1', caraBayar='Transfer' WHERE idTagihanBulanan ='$pyt[idTagihan]'");
               mysqli_query($conn, "INSERT INTO tagihan_bulanan_bayar(idTagihanBulanan,tglBayar,jumlahBayar,ketBayar,caraBayar) VALUES ('$pyt[idTagihan]','$transaction_time','$pyt[nominal]','Lunas','Transfer')");

            } else if ($pyt['jenisTagihan'] == 'Bebas') {

                mysqli_query($conn, "INSERT INTO tagihan_bebas_bayar(idTagihanBebas,tglBayar,jumlahBayar,ketBayar,caraBayar) VALUES ('$pyt[idTagihan]','$transaction_time','$pyt[nominal]','Lunas','Transfer')");

                $tagBebas = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tagihan_bebas WHERE idTagihanBebas='$pyt[idTagihan]'"));
                $tagBebasBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayar FROM tagihan_bebas_bayar WHERE idTagihanBebas='$pyt[idTagihan]'"));
                if ($tagBebas['totalTagihan'] == $tagBebasBayar['totalBayar']) {
                    $statusByr = '1';
                } elseif ($tagBebas['totalTagihan'] > $tagBebasBayar['totalBayar']) {
                    $statusByr = '0';
                } else {
                    $statusByr = '0';
                }
                mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='$statusByr' WHERE idTagihanBebas ='$pyt[idTagihan]'");
            }
        }
    }

//     $qPaymentSukses = mysqli_query($conn, "SELECT payment.*, sum(nominal) as tot FROM payment WHERE paymentOrder='$order_id' GROUP BY paymentOrder LIMIT 1");
//     if (mysqli_num_rows($qPaymentSukses) <> 0) {

//         $pay = mysqli_fetch_array($qPaymentSukses);
//         $idsss = $pay['idSiswa'];
//         $siss = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idSiswa='$idsss'"));
//         $nams = $siss['nmSiswa'];
//         $klss = $siss['nmKelas'];
//         $jml = ($pay['tot'] + $idt['biaya_admin']);
//         $no = $siss['noHpOrtu'];
//         $nos = $siss['noHpSis'];
//         $tgl = $pay['tglPayment'];
//         $pos = $pay['jenisTagihan'];
//         $bank = $pay['tipePayment'];
//         $nobank = $pay['noPayment'];
//         $msg_wa = array();
//         $number_wa = array();

//         $number_wa[] = $nos;
//         $msg_wa[] = 'Terima Kasih, Pembayaran Sekolah siswa a/n:
                    
// Nama  : *' . $nams . '*
// Kelas : *' . $klss . '* 
// Jumlah: *' . str_replace(".", ",", buatRp($jml)) . '*
// Pada  : *' . $tgl . '* 
// VA/E-Money :' . $bank . '
// Nomor      : ' . $nobank . '

// *BERHASIL*';

//         $number_wa[] = $no;
//         $msg_wa[] = 'Terima Kasih, Pembayaran Sekolah Siswa : 
                    
// Nama  : *' . $nams . '*
// Kelas : *' . $klss . '* 
// Jumlah: *' . str_replace(".", ",", buatRp($jml)) . '*
// Pada  : *' . $tgl . '* 
// VA/E-Money :' . $bank . '
// Nomor      : ' . $nobank . '

// *BERHASIL*';

//         for ($i = 0; $i < count($number_wa); $i++) {

//             $curl = curl_init();
//             curl_setopt($curl, CURLOPT_URL, $link_send);
//             curl_setopt($curl, CURLOPT_HEADER, 0);
//             curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
//             curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
//             curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
//             curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
//             curl_setopt($curl, CURLOPT_TIMEOUT, 0); // batas waktu response
//             curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
//             curl_setopt($curl, CURLOPT_POST, 1);

//             $data_post = [
//                 'id_device' => $id,
//                 'api-key' => $token_send,
//                 'no_hp'   => $number_wa[$i],
//                 'pesan'   => $msg_wa[$i]
//             ];
//             curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
//             curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
//             $response = curl_exec($curl);
//             curl_close($curl);
//         }
//     }
} else if ($transaction == 'pending') {
    // TODO set payment status in merchant's database to 'Pending'
    //echo "Waiting customer to finish transaction order_id: " . $order_id . " using " . $type;

    $qPayment = mysqli_query($conn, "SELECT * FROM payment WHERE paymentOrder='$order_id'");
    if (mysqli_num_rows($qPayment) <> 0) {
        while ($pyt = mysqli_fetch_array($qPayment)) {
            mysqli_query($conn, "UPDATE payment SET status='pending' WHERE idPayment='$pyt[idPayment]'");

            if ($pyt['jenisTagihan'] == 'Bulanan') {
                mysqli_query($conn, "UPDATE tagihan_bulanan SET statusBayar='2' WHERE idTagihanBulanan ='$pyt[idTagihan]'");
            } else if ($pyt['jenisTagihan'] == 'Bebas') {
                mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='2' WHERE idTagihanBebas ='$pyt[idTagihan]'");
            }
        }
    }

    $qPaymentPending = mysqli_query($conn, "SELECT payment.*, sum(nominal) as tot FROM payment WHERE paymentOrder='$order_id' GROUP BY paymentOrder LIMIT 1");
    if (mysqli_num_rows($qPaymentPending) <> 0) {

        $pay = mysqli_fetch_array($qPaymentPending);
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
        $msg_wa[] = 'Silahkan *SELESAIKAN* Pembayaran Sekolah siswa a/n:
                
Nama  : *' . $nams . '*
Kelas : *' . $klss . '* 
Jumlah: *' . str_replace(".", ",", buatRp($jml)) . '*
Pada  : *' . $tgl . '* 
                
Silahkan transfer ke: 
VA/E-Money :*' . $bank . '*
Nomor      : *' . $nobank . '*';

        $number_wa[] = $no;
        $msg_wa[] = 'Silahkan *SELESAIKAN* Pembayaran Sekolah anak anda 
            
Nama  : *' . $nams . '*
Kelas : *' . $klss . '* 
Jumlah: *' . str_replace(".", ",", buatRp($jml)) . '*
Pada  : *' . $tgl . '* 
                
Silahkan transfer ke:
VA/E-Money :*' . $bank . '*
Nomor      : *' . $nobank . '*';

        for ($i = 0; $i < count($number_wa); $i++) {

            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $link_send);
            curl_setopt($curl, CURLOPT_HEADER, 0);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
            curl_setopt($curl, CURLOPT_TIMEOUT, 0); // batas waktu response
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($curl, CURLOPT_POST, 1);

            $data_post = [
                'id_device' => $id,
                'api-key' => $token_send,
                'no_hp'   => $number_wa[$i],
                'pesan'   => $msg_wa[$i]
            ];
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            $response = curl_exec($curl);
            curl_close($curl);
        }
    }
} else if ($transaction == 'deny') {
    // TODO set payment status in merchant's database to 'Denied'
    echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is denied.";
    $qPayment = mysqli_query($conn, "SELECT * FROM payment WHERE paymentOrder='$order_id'");
    if (mysqli_num_rows($qPayment) <> 0) {
        while ($pyt = mysqli_fetch_array($qPayment)) {
            mysqli_query($conn, "UPDATE payment SET status='deny' WHERE idPayment='$pyt[idPayment]'");

            if ($pyt['jenisTagihan'] == 'Bulanan') {
                mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='', inv='', statusBayar='0' WHERE idTagihanBulanan ='$pyt[idTagihan]'");
            } else if ($pyt['jenisTagihan'] == 'Bebas') {
                mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='0' WHERE idTagihanBebas ='$pyt[idTagihan]'");
            }
        }
    }
} else if ($transaction == 'expire') {
    // TODO set payment status in merchant's database to 'expire'
    //echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is expired.";
    $qPayment = mysqli_query($conn, "SELECT * FROM payment WHERE paymentOrder='$order_id'");
    if (mysqli_num_rows($qPayment) <> 0) {
        while ($pyt = mysqli_fetch_array($qPayment)) {
            mysqli_query($conn, "UPDATE payment SET status='expire' WHERE idPayment='$pyt[idPayment]'");

            if ($pyt['jenisTagihan'] == 'Bulanan') {
                mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='', inv='', statusBayar='0' WHERE idTagihanBulanan ='$pyt[idTagihan]'");
            } else if ($pyt['jenisTagihan'] == 'Bebas') {
                mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='0' WHERE idTagihanBebas ='$pyt[idTagihan]'");
            }
        }
    }
} else if ($transaction == 'cancel') {
    // TODO set payment status in merchant's database to 'Denied'
    //echo "Payment using " . $type . " for transaction order_id: " . $order_id . " is canceled.";
    $qPayment = mysqli_query($conn, "SELECT * FROM payment WHERE paymentOrder='$order_id'");
    if (mysqli_num_rows($qPayment) <> 0) {
        while ($pyt = mysqli_fetch_array($qPayment)) {
            mysqli_query($conn, "UPDATE payment SET status='cancel' WHERE idPayment='$pyt[idPayment]'");

            if ($pyt['jenisTagihan'] == 'Bulanan') {
                mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='', inv='', statusBayar='0' WHERE idTagihanBulanan ='$pyt[idTagihan]'");
            } else if ($pyt['jenisTagihan'] == 'Bebas') {
                mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='0' WHERE idTagihanBebas ='$pyt[idTagihan]'");
            }
        }
    }
}
