<?php 
include "../config/koneksi.php"; // memanggil file koneksi.php untuk koneksi ke database
include "qrlib.php"; // memanggil system barcode
$nama = $_POST['nama'];
$telpon = $_POST['telpon'];
$barcode		= $nama.'.'.'png';
$namebarcode	= $telpon.'.'.'png';
$tempdir 		= "temp/"; // Nama Folder file QR Code kita nantinya akan disimpan
$isibarcode		= $nama;
$quality 		= 'H';
$Ukuran 		= 5;
$padding 		= 0;

QRCode::png($isibarcode,$tempdir.$namebarcode,$quality,$Ukuran,$padding);

 mysqli_query($koneksi, "INSERT INTO qrcode (nama, telpon, qrcode) values ('$nama', '$telpon', '$namebarcode')");
 echo "<script>window.alert('Data Berhasil di Tambahkan!');
                window.location='index.php'</script>";
?>
