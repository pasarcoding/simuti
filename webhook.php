<?php
require 'config/fungsi_wa.php';
require 'config/koneksi.php';
require 'config/library.php';
$t = mysqli_query($conn, "SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif = 'Y'");
$ta = mysqli_fetch_array($t);
$thn_ajar = $ta['ta'];
// this is simple php webhook for mpwa, not recommended using thi procedural pattern if you have a lot of keywrds!
header('content-type: application/json; charset=utf-8');
$data = json_decode(file_get_contents('php://input'), true);
if (!$data) {
    die('this url is for webhook.');
}
// file_put_contents('whatsapp.txt','[' . date('Y-m-d H:i:s') . "]\n" . json_encode($data) . "\n\n",FILE_APPEND);                                             
$message = strtolower($data['message']);
$from = strtolower($data['from']);
$bufferimage = isset($data['bufferImage']) ? $data['bufferImage'] : null;
$respon = false;

// Get saldo from database based on message 'saldo'
if ($message == 'saldo') {
    $result = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM siswa where noHpOrtu='$from' OR noHpSis='$from'"));
    $results = mysqli_query($conn,"SELECT * FROM siswa where noHpOrtu='$from'");
    if ($result && mysqli_num_rows($results) > 0) {
        $saldo = $result['saldo'];      
        $nama = $result['nmSiswa'];

    $respon = FormatMessage::text("Hallo $nama, sisa saldo tabungan kamu saat ini adalah " . buatRp($saldo) ."
    
Terimakasih", true);
    } else {
        $respon = FormatMessage::text("No saldo found for your number.", true);
    }
}


if ($message == 'tagihan') {

    $id_tahun_ajaran = $thn_ajar;
    $results = mysqli_query($conn,"SELECT * FROM siswa where noHpOrtu='$from' OR noHpSis='$from' ");

    $now = date('m');

    //bulan
    $b = mysqli_query($conn,"SELECT nmBulan as bulan, urutan as urt, idBulan as id_bln FROM bulan WHERE idBulan = $now");
    $bl = mysqli_fetch_array($b);
    $id_bln = $bl['id_bln'];
    $bulan = $bl['bulan'];
    $urut_bln = $bl['urt'];
        $total_tagihan_bulanan_bebas = 0;
        $siswa = mysqli_fetch_array(mysqli_query($conn, "SELECT siswa.*, kelas_siswa.nmKelas FROM siswa LEFT JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas WHERE siswa.noHpOrtu='$from'  "));
        $link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . 'laporan_tagihan_siswa.php?tahun=' . $id_tahun_ajaran . '&siswa=' . $siswa['idSiswa'];
        $rincian_tagihan = '';
        $total_tagihan = 0;
        $no = 1;
        //semua bulan
       
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
    WHERE tagihan_bulanan.idSiswa='$siswa[idSiswa]' AND jenis_bayar.idTahunAjaran<='$id_tahun_ajaran' AND tagihan_bulanan.statusBayar='0' AND bulan.idBulan='$id_bln'
    order by bulan.urutan asc ");
            while ($tBln = mysqli_fetch_array($tag_bln)) {
                if ($tBln['jumlahBayar'] <> 0) {
                    $pisah_TA = explode('/', $tBln['nmTahunAjaran']);
                    if ($tBln['urutan'] <= 6) {
                        $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[0];
                    } else {
                        $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[1];
                    }
                    $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBln['nmJenisBayar'] . " - T.A" . $tBln['nmTahunAjaran'] . " - (" . $nmBulan . ") => *" . str_replace('.', ',', buatRp($tBln['jumlahBayar'])) . "*
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
    WHERE tagihan_bebas.idSiswa='$siswa[idSiswa]' AND jenis_bayar.idTahunAjaran<='$id_tahun_ajaran' AND tagihan_bebas.statusBayar!='1' AND  bulan.idBulan='$id_bln'
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
                        $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBbs['nmJenisBayar'] . " - T.A" . $tBbs['nmTahunAjaran'] . " - (" . $nmBulan . ") => *" . str_replace('.', ',', buatRp($sisa_tag_bebas)) . "*
";
                        $total_tagihan += $sisa_tag_bebas;
                    }
                }
            }
            //Perbulan
       

        // kirim Tagihan WAu
        $noHp_ortu = $siswa['noHpOrtu'];

        $text_wa = 'Assalamualaikum, Harap menyelesaikan pembayaran Tagihan sebesar *' . str_replace(".", ",", buatRp($total_tagihan)) . '* anak Anda yang bernama *' . $siswa['nmSiswa'] . '*, dengan rincian di bawah ini:
    
' . $rincian_tagihan . '
Download Tagihan : ' . $link_url_tagihan . '
    
kapan akan dibayarkan ?. Terima kasih';
       
    
    if ($siswa && mysqli_num_rows($results) > 0) {
        $saldo = $result['saldo'];      
        $nama = $result['nmSiswa'];

  $respon = FormatMessage::text("$text_wa. Terimakasih", true);

    } else {
        $respon = FormatMessage::text("Maaf data tidak ditemukan.", true);
    }
}
// For other message types (media, button, template, list), keep the existing code as it is

// ...
// Existing code for media, button, template, list
// ...

// get image
if ($bufferimage) {
    $base64str = 'data:image/png;base64,' . $bufferimage;
    list(,$base64str) = explode(';', $base64str);
    list(,$base64str) = explode(',', $base64str);
    $imagedata = base64_decode($base64str);
    $filename = 'images/' . time() . '.png';
    $file = file_put_contents($filename, $imagedata);
    fwrite($file, $imagedata);
    fclose($file);
}

// Close the database connection
mysqli_close($connection);

echo $respon;
