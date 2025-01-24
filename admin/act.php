<?php
date_default_timezone_set('Asia/Jakarta');
include "../config/koneksi.php";
include "../config/rupiah.php";
include "../config/library.php";
include "../config/fungsi_wa.php";
session_start();
//url tagihan
$page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
//identitas
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['status'];
//tahun ajaran
$t = mysqli_query($conn, "SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif = 'Y'");
$ta = mysqli_fetch_array($t);
$thn_ajar = $ta['ta'];


if (isset($_POST['simpan_bulanan'])) {
	$tglBayar = date("Y-m-d H:i:s");
	$tgl = date('Y-m-d');

	$query = mysqli_query($conn, "INSERT INTO tagihan_bulanan_bayar (idTagihanBulanan,tglBayar,jumlahBayar,caraBayar,ketBayar,user)
		VALUES ('$_POST[id_tagihan_bulanan]','$_POST[tanggal_bayar]', '$_POST[jumlah_bayar]','$_POST[caraBayar]','$_POST[ketBayar]', '$_SESSION[namalengkap]')");
	$totals = mysqli_fetch_array(mysqli_query($conn, "SELECT sum(jumlahBayar) as terbayar from tagihan_bulanan_bayar WHERE idTagihanBulanan='$_POST[id_tagihan_bulanan]'"));
	$sqlTotalBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * from tagihan_bulanan WHERE idTagihanBulanan='$_POST[id_tagihan_bulanan]'"));

	$bayars = $bayar + $totals['terbayar'];
	if ($bayars < $sqlTotalBayar['jumlahBayar']) {
		mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$tglBayar', tglUpdate='$_POST[tanggal_bayar]', statusBayar='2', caraBayar='$_POST[caraBayar]' , user='$_SESSION[namalengkap]' WHERE  idTagihanBulanan ='$_POST[id_tagihan_bulanan]'");
	} else {
		mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$tglBayar', tglUpdate='$_POST[tanggal_bayar]', statusBayar='1', caraBayar='$_POST[caraBayar]' , user='$_SESSION[namalengkap]' WHERE  idTagihanBulanan ='$_POST[id_tagihan_bulanan]'");
	}


	$a = mysqli_query($conn, "SELECT nmBulan as Bulan, jenis_bayar.nmJenisBayar as jenis, tagihan_bulanan.idSiswa as ids, nmSiswa as nama, noHpOrtu as hpo, noHpSis as hps, jumlahBayar as tagihan FROM siswa 
						 INNER JOIN tagihan_bulanan ON siswa.idSiswa = tagihan_bulanan.idSiswa 
						 INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan 
						 INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
						 WHERE idTagihanBulanan='$_POST[id_tagihan_bulanan]'         
                   		");

	$re = mysqli_fetch_array($a);
	$sis = $re['ids'];
	$link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . $uri_segments[1] . '/kwitansi.php?tahun=' . $thn_ajar . '%26tgl=' . $tgl . '%26siswa=' . $sis;

	// $tagihan = $re['tagihan'];
	$siswa = $re['nama'];
	$hpo = $re['hpo'];
	$hps = $re['hps'];
	$bulan = $re['Bulan'];
//	$jenisbayar = $re['jenis'];
	$tagihan = $_POST['jumlah_bayar'];
    $jenisbayar = $re['jenis'];

	$msg_wa = array();
	$number_wa = array();

	$number_wa[] = $hpo;
//	$number_wa[] = $hps;
	$msg_wa[] = 'Assalamualaikum Wr Wb, Pembayaran Tagihan ' . $jenisbayar .'   untuk

Bulan : ' . $bulan . ' 
Jumlah: *' . str_replace(".", ",", buatRp($tagihan)) . '* 
a/n   : *' . $siswa . '*

Sudah kami terima.	*Lunas* 
Terima kasih. 
(Keuangan SD Muhammadiyah 3 Bandung)
		
';

	$msg_wa[] = 'Assalamualaikum, Terima Kasih pembayaran Tagihan ' . $jenisbayar .' untuk 
		
Bulan: ' . $bulan . ' 
Jumlah: *' . str_replace(".", ",", buatRp($tagihan)) . '* 
Nama: *' . $siswa . '*
	
*Lunas* 
Terima kasih. 
		
Download kwitansi : ' . $link_url_tagihan . '';

	for ($i = 0; $i < count($number_wa); $i++) {
		send_wa($link_send, $token_send, $number_send, $number_wa[$i], $msg_wa[$i]);
	}
	header('Location: ' . $_POST['uri']);
} else {
	header('Location: ../index.php?view=pembayaran');
}
