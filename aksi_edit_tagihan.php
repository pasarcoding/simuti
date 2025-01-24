<?php
include "config/koneksi.php";
// ...

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $idTagihanBebas = $_POST['idKelas'];
  $editTotalTagihan = $_POST['editTotalTagihan'];
  $jenisBayar = $_POST['jenisBayar'];
  // Lakukan validasi data jika diperlukan
  // ...

  // Lakukan aksi edit ke database
  // ...

  // Contoh: Update totalTagihan ke database
  $query = "UPDATE tagihan_bebas SET totalTagihan = '$editTotalTagihan' WHERE idKelas = '$idTagihanBebas' and idJenisBayar  = '$jenisBayar' ";
  // Jalankan query dan periksa hasilnya
  if (mysqli_query($conn, $query)) {
    // Proses edit berhasil
   
   json_encode(200);
  } else {
    // Proses edit gagal
    echo "Edit gagal";
  }
}
