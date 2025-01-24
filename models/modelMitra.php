<?php
include_once '../config/conn.php';
include_once '../config/koneksi.php';
include_once '../config/rupiah.php';

// DB table to use
$table = 'transaksi';

$primaryKey = 'id_transaksi';
$count = 2; // Initialize $count outside the DataTables configuration

// Update this line with the correct column names
$columns = array(
    array(
        'db' => 'id_transaksi',
        'dt' => 'tipe',
        'formatter' => function ($id_transaksi) {
            global $conn;

            // Fetch user name from tbl_users based on id_user
            $query = "SELECT * FROM transaksi WHERE id_transaksi = '$id_transaksi'";
            $result = mysqli_query($conn, $query);
            $user = mysqli_fetch_assoc($result);

            if ($user['kredit'] == 0) {
                return "<a class='btn btn-success btn-xs'><i class='glyphicon glyphicon-save-file'></i></a> ";
            } else {
                return "<a class='btn btn-danger btn-xs'><i class='glyphicon glyphicon-open-file'></i></a> ";
            }
        }
    ),
    array('db' => 'tanggal', 'dt' => 'tanggal'),
    array('db' => 'id_transaksi', 'dt' => 'id_transaksi'),
    array(
        'db' => 'nisnSiswa',
        'dt' => 'nmSiswa',
        'formatter' => function ($nisnSiswa) {
            global $conn;

            // Fetch user name from tbl_users based on id_user
            $query = "SELECT nmSiswa FROM siswa WHERE nisnSiswa = '$nisnSiswa'";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            $user = mysqli_fetch_assoc($result);
            return $user['nmSiswa'];
        }
    ),
    array('db' => 'debit', 'dt' => 'debit', 'formatter' => function ($debit) {
        return "Rp." . rupiah($debit);
    }),
    array('db' => 'kredit', 'dt' => 'kredit', 'formatter' => function ($kredit) {
        return "Rp." . rupiah($kredit);
    }),
    
);

$count++; // Increment $count outside the else block

require('../ssp.class.php');

// Modify the following line to pass the MySQLi connection object, not an array
echo json_encode(
    SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns, NULL, NULL)
);
