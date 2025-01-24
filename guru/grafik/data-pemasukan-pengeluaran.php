<?php 
    include "../../config/koneksi.php";
    include "../../config/fungsi_indotgl.php";
    header('Content-Type: application/json');

    $idTahunAjaran = $_GET['thnAjaran'];
    $ta = mysql_fetch_array(mysql_query("SELECT * FROM tahun_ajaran where idTahunAjaran='$idTahunAjaran'"));

    $data = array();
    $sqlBulan = mysql_query("SELECT * FROM bulan ORDER BY urutan ASC");
    while($bln=mysql_fetch_array($sqlBulan)) {
        $data1 = array();
        
        $bulan = $bln['idBulan'];
        $ta_pisah = explode("/", $ta['nmTahunAjaran']);
        if ($bln['urutan'] <= 6){
            $tahun = $ta_pisah[0];
        }else{
            $tahun = $ta_pisah[1];
        }

        // Hitung Pemasukan
        $totalMasuk = 0;
        $dBulananMasuk = mysql_fetch_array(mysql_query("SELECT SUM(jumlahBayar) AS totalMasuk FROM tagihan_bulanan 
                                                          WHERE statusBayar='1' AND month(tglBayar) = '$bulan' AND year(tglBayar)='$tahun'"));
        $totalMasuk += $dBulananMasuk['totalMasuk'];
        $dBebasMasuk = mysql_fetch_array(mysql_query("SELECT SUM(jumlahBayar) AS totalMasuk FROM tagihan_bebas_bayar 
                                                      WHERE month(tglBayar) = '$bulan' AND year(tglBayar)='$tahun'"));
        $totalMasuk += $dBebasMasuk['totalMasuk'];
        

        // Hitung Pengeluaran
        $totalKeluar = 0;
        $dJurnalKeluar = mysql_fetch_array(mysql_query("SELECT SUM(pengeluaran) AS totalKeluar FROM jurnal_umum WHERE month(tgl)='$bulan' AND year(tgl)='$tahun'"));
        $totalKeluar += $dJurnalKeluar['totalKeluar'];

        $data1['y']= getBulan($bln['idBulan']).' '.$tahun;
        $data1['a'] = $totalMasuk;
        $data1['b'] = $totalKeluar;
        $data[] = $data1;
    }

    echo json_encode($data);
?>