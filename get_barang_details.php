<?php
include 'config/koneksi.php'; // Pastikan koneksi sudah benar
$idBarang = $_GET['idBarang'];
$query = mysqli_query($conn, "SELECT * FROM inv_data_barang WHERE idBarang = '$idBarang'");
$data = mysqli_fetch_assoc($query);

// Mengirimkan data barang sebagai JSON
echo json_encode($data);
