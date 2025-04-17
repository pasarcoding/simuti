<?php
include 'config/koneksi.php'; // Pastikan koneksi database

if (isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];

    $query = "UPDATE siswa SET statusInden = '$status' WHERE idSiswa = '$id'";
    if (mysqli_query($conn, $query)) {
        echo "success";
    } else {
        echo "error";
    }
}
