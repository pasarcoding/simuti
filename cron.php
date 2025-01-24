<?php
error_reporting(0);
include "config/koneksi.php";
include 'config/rupiah.php';
include 'config/library.php';

date_default_timezone_set('Asia/Jakarta');

$nows = date('m');

//bulan
$b = mysqli_query($conn, "SELECT nmBulan as bulan, urutan as urt, idBulan as id_bln FROM bulan WHERE idBulan='$nows'");
$bl = mysqli_fetch_array($b);
$id_bln = $bl['id_bln'];
$bulan = $bl['bulan'];
$urut_bln = $bl['urt'];

//tahun ajaran
$t = mysqli_query($conn, "SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif='Y'");
$ta = mysqli_fetch_array($t);
$thn_ajar = $ta['ta'];

//url tagihan
$page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('');

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
// $headers = array();
// $headers[] = $idt[token];
// $headers[] = 'Content-Type: application/x-www-form-urlencoded';

// $token = 'dk_8c49232e159a473e85c958db93b110fa';    
$now = date('j');
$range = [10, 11, 12, 13, 14, 15, 16, 17];

if (in_array($now, $range)) {

    $lst_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE statusSiswa='Aktif'");
    while ($sws = mysqli_fetch_array($lst_siswa)) {

        $link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . 'laporan_tagihan_siswa.php?tahun=' . $thn_ajar . '&siswa=' . $sws['idSiswa'];
        $rincian_tagihan = '';
        $total_tagihan = 0;
        $no = 1;
        $now = date('m');

        // tagihan bulan 
        $tag_bln = mysqli_query($conn, "SELECT tagihan_bulanan.idSiswa, tagihan_bulanan.jumlahBayar,
                jenis_bayar.idPosBayar, 
                jenis_bayar.nmJenisBayar, 
                tahun_ajaran.nmTahunAjaran,
                pos_bayar.nmPosBayar,
                bulan.nmBulan,
                bulan.urutan
            FROM tagihan_bulanan 
            LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
            LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
            LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
            LEFT JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
            WHERE tagihan_bulanan.idSiswa='$sws[idSiswa]' AND jenis_bayar.idTahunAjaran='$thn_ajar' AND tagihan_bulanan.statusBayar='0' AND tagihan_bulanan.idBulan='$id_bln'
            order by bulan.urutan asc ");
        while ($tBln = mysqli_fetch_array($tag_bln)) {
            if ($tBln['jumlahBayar'] <> 0) {
                $pisah_TA = explode('/', $tBln['nmTahunAjaran']);
                if ($tBln['urutan'] <= 6) {
                    $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[0];
                } else {
                    $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[1];
                }
                $bayar_bulan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayarBulanan FROM tagihan_bulanan_bayar WHERE idTagihanBulanan='$tBln[idTagihanBulanan]'"));
                $sisa_tag_bulan = $tBln['jumlahBayar'] - $bayar_bulan['totalBayarBulanan'];
                $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBln['nmJenisBayar'] . "  - " . str_replace('.', ',', buatRp($sisa_tag_bulan)) . "
                    ";
                $total_tagihan += $tBln['jumlahBayar'];
            }
        }

        // tagihan bebas
        $tag_bebas = mysqli_query($conn, "SELECT tagihan_bebas.*, 
        SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, 
        jenis_bayar.idPosBayar, 
        jenis_bayar.nmJenisBayar, 
        tahun_ajaran.nmTahunAjaran,
        pos_bayar.nmPosBayar,
        bulan.nmBulan,
        bulan.urutan
    FROM tagihan_bebas 
    LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
    LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
    LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
    LEFT JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan
    WHERE tagihan_bebas.idSiswa='$sws[idSiswa]' AND jenis_bayar.idTahunAjaran='$thn_ajar' AND tagihan_bebas.statusBayar!='1' AND tagihan_bebas.idBulan='$id_bln'
    GROUP BY tagihan_bebas.idJenisBayar order by bulan.urutan asc");

        while ($tBbs = mysqli_fetch_array($tag_bebas)) {
            if ($tBbs['totalTagihanBebas'] <> 0) {
                $pisah_TA = explode('/', $tBbs['nmTahunAjaran']);
                if ($tBbs['urutan'] <= 6) {
                    $nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[0];
                } else {
                    $nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[1];
                }
                $bayar_bebas = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayarBebas FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tBbs[idTagihanBebas]'"));
                $sisa_tag_bebas = $tBbs['totalTagihanBebas'] - $bayar_bebas['totalBayarBebas'];
                if ($sisa_tag_bebas <> 0) {
                    $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBbs['nmJenisBayar'] . "  - " . str_replace('.', ',', buatRp($sisa_tag_bebas)) . " 
    ";
                    $total_tagihan += $sisa_tag_bebas;
                }
            }
        }

        if ($total_tagihan <> 0) {
            //pesan whatsapp siswa 
            $msg_wa = array();
            $number_wa = array();

            $number_wa[]  = $sws['noHpSis'];
            $msg_wa[] = 'Assalamualaikum, Harap menyelesaikan pembayaran Tagihan:
    
Nama : *' . $sws['nmSiswa'] . '*            
Bulan: *' . $nmBulan . '* 
Total: *' . str_replace(".", ",", buatRp($total_tagihan)) . '*  
    
dengan rincian di bawah ini:
                
' . $rincian_tagihan . '
Download Tagihan : ' . $link_url_tagihan . '
    
kapan akan dibayarkan ?. Terima kasih';

            //pesan whatsapp ortu 
            $number_wa[]  = $sws['noHpOrtu'];
            $msg_wa[] = 'Assalamualaikum, Harap menyelesaikan pembayaran Tagihan : 
        
Nama : *' . $sws['nmSiswa'] . '*            
Bulan: *' . $nmBulan . '* 
Total: *' . str_replace(".", ",", buatRp($total_tagihan)) . '*
    
dengan rincian di bawah ini:
    
' . $rincian_tagihan . '
Download Tagihan : ' . $link_url_tagihan . '
kapan akan dibayarkan ?. Terima kasih';

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

        $re = json_encode($response);
        mysqli_query($conn, "INSERT INTO cron(con) VALUES('$re')");
    }
} else {

    mysqli_query($conn, "INSERT INTO cron(con) VALUES('false')");
}
