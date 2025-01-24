<?php
include 'config/koneksi.php'; // Pastikan koneksi sudah benar
$idSumberDana = $_GET['idSumberDana'];
$query = mysqli_query($conn, "SELECT * FROM inv_sumber_dana WHERE idSumberDana = '$idSumberDana'");
$data = mysqli_fetch_assoc($query);

// Mengirimkan data sumber dana sebagai JSON
echo json_encode($data);
