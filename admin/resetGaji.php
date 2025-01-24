<?php
include '../config/koneksi.php';

$no = $_GET['id'];
$id_bulan = $_GET['bulan'];
$idTahunAjaran = $_GET['idTahunAjaran'];

// Assuming you have a column for each type of tunjangan
mysqli_query($conn, "DELETE FROM bayar_gaji WHERE id_guru = $no and id_bulan = $id_bulan  and idTahunAjaran=$idTahunAjaran ");

// Redirect or perform additional actions after saving
header("Location: ../?view=bayar_gaji&idTahunAjaran=$idTahunAjaran&bulan=$id_bulan&cari=Set+Gaji+Guru");
exit();
