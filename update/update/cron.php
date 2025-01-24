<?php 
error_reporting(0);
include "config/koneksi.php";
include 'config/rupiah.php';
include 'config/library.php';

date_default_timezone_set('Asia/Jakarta');

$now = date('m');

//bulan
$b = mysql_query("SELECT nmBulan as bulan, urutan as urt, idBulan as id_bln FROM bulan WHERE idBulan = $now");
$bl = mysql_fetch_array($b);
$id_bln = $bl['id_bln'];
$bulan = $bl['bulan'];
$urut_bln = $bl['urt'];

//tahun ajaran
$t = mysql_query("SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif = 'Y'");
$ta = mysql_fetch_array($t);
$thn_ajar = $ta['ta'];

//url tagihan
$page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);

$headers = array();
$headers[] = 'Authorization: Bearer f0467a7082f3c0eb1c6b92ef1bf48c31871d4c86';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';


$token = 'f0467a7082f3c0eb1c6b92ef1bf48c31871d4c86';    
$now = date('j');
$range = [1,2,3,4,5,6,7,8,9,10,26,27];  

if(isset($_GET['api']) && $_GET['api'] == $token) 
{

        if(in_array($now,$range))
        {

        $lst_siswa = mysql_query("SELECT * FROM siswa WHERE statusSiswa='Aktif'"); 
        while ($sws = mysql_fetch_array($lst_siswa)) { 

            $link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . $uri_segments[1] . '/laporan_tagihan_siswa.php?tahun=' . $thn_ajar . '%26siswa=' . $sws['idSiswa'];
            $rincian_tagihan='';
            $total_tagihan=0;
            $no = 1;
            // tagihan bulan 
            $tag_bln = mysql_query("SELECT tagihan_bulanan.idSiswa, tagihan_bulanan.jumlahBayar,
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
                                WHERE tagihan_bulanan.idSiswa='$sws[idSiswa]' AND jenis_bayar.idTahunAjaran<='$thn_ajar' AND tagihan_bulanan.statusBayar='0' AND tagihan_bulanan.idBulan<='$id_bln'
                            ");
            while ($tBln = mysql_fetch_array($tag_bln)) {
                if ($tBln['jumlahBayar'] <> 0){
                    $pisah_TA = explode('/', $tBln['nmTahunAjaran']);
                    if ($tBln['urutan'] <= 6){
                        $nmBulan = $tBln['nmBulan'].' '.$pisah_TA[0];
                    }else{
                        $nmBulan = $tBln['nmBulan'].' '.$pisah_TA[1];
                    }
                    $rincian_tagihan = $rincian_tagihan.$no++.". ".$tBln['nmJenisBayar']." - T.A".$tBln['nmTahunAjaran']." - (".$nmBulan.") => *".str_replace('.',',',buatRp($tBln['jumlahBayar']))."* %0A";
                    $total_tagihan += $tBln['jumlahBayar'];
                }
            }

            $tag_bebas = mysql_query("SELECT tagihan_bebas.*, 
                                        SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, 
                                        jenis_bayar.idPosBayar, 
                                        jenis_bayar.nmJenisBayar, 
                                        tahun_ajaran.nmTahunAjaran,
                                        pos_bayar.nmPosBayar
                                FROM tagihan_bebas 
                                LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                                LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                                WHERE tagihan_bebas.idSiswa='$sws[idSiswa]' AND jenis_bayar.idTahunAjaran<='$thn_ajar' AND tagihan_bebas.statusBayar!='1' AND tagihan_bebas.idBulan<='$id_bln'
                                GROUP BY tagihan_bebas.idJenisBayar");

            while ($tBbs = mysql_fetch_array($tag_bebas)) {
                $bayar_bebas = mysql_fetch_array(mysql_query("SELECT SUM(jumlahBayar) as totalBayarBebas FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tBbs[idTagihanBebas]'"));
                $sisa_tag_bebas = $tBbs['totalTagihanBebas'] - $bayar_bebas['totalBayarBebas'];
                if ($sisa_tag_bebas <> 0){
                    $rincian_tagihan = $rincian_tagihan.$no++.". ".$tBbs['nmJenisBayar']." - T.A".$tBbs['nmTahunAjaran']." => *".str_replace('.',',',buatRp($sisa_tag_bebas))."* %0A";
                    $total_tagihan += $sisa_tag_bebas;
                }
            }

            $msg_sws = 'Assalamualaikum, Harap menyelesaikan pembayaran Tagihan sebesar *'.str_replace(".",",",buatRp($total_tagihan)).'* untuk Anda yang bernama *'.$sws['nmSiswa'].'*, dengan rincian di bawah ini:%0A %0A'.$rincian_tagihan.'%0ADownload Tagihan : '.$link_url_tagihan.' %0A %0Akapan akan dibayarkan ?. Terima kasih';
            $msg_ortu = 'Assalamualaikum, Harap menyelesaikan pembayaran Tagihan sebesar *'.str_replace(".",",",buatRp($total_tagihan)).'* anak Anda yang bernama *'.$sws['nmSiswa'].'*, dengan rincian di bawah ini:%0A %0A'.$rincian_tagihan.'%0ADownload Tagihan : '.$link_url_tagihan.' %0A %0Akapan akan dibayarkan ?. Terima kasih';
            
            if ($total_tagihan <> 0){
                
                // siswa
                $phone = $sws['noHpSis'];        
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, 'http://lyra.api-wa.my.id/api/message');
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);      
                curl_setopt($ch, CURLOPT_POSTFIELDS, "phone=$phone&message=$msg_sws&type=text");
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    
                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close($ch);
                
                echo $msg_sws.'<br>';
                var_dump($result);
                echo '<br>';    
    
    
                // orang tua
                $phone1 = $sws['noHpOrtu'];        
                $ch1 = curl_init();
                curl_setopt($ch1, CURLOPT_URL, 'http://lyra.api-wa.my.id/api/message');
                curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch1, CURLOPT_POST, 1);      
                curl_setopt($ch1, CURLOPT_POSTFIELDS, "phone=$phone1&message=$msg_ortu&type=text");
                curl_setopt($ch1, CURLOPT_HTTPHEADER, $headers);
    
                $result1 = curl_exec($ch1);
                if (curl_errno($ch1)) {
                    echo 'Error:' . curl_error($ch1);
                }
                curl_close($ch1);
                
                echo $msg_ortu.'<br>';
                var_dump($result1);
                echo '<br>';

            }
            
            $re = json_encode($result);
            mysql_query("INSERT INTO cron(con) VALUES('$re')");
            
            $re1 = json_encode($result1);
            mysql_query("INSERT INTO cron(con) VALUES('$re1')");
        }
    }
    else
    {            

        mysql_query("INSERT INTO cron(con) VALUES('false')");
    }    
  
}


?>