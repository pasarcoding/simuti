<?php
include_once '../config/conn.php';
include_once '../config/library.php';
include_once '../config/koneksi.php';

// DB table to use
$table = 'tbl_users';

$primaryKey = 'id_users';

$columns = array(
    array('db' => 'id_users', 'dt' => 0),
    array('db' => 'nama', 'dt' => 1),
    array('db' => 'email', 'dt' => 2),
    array('db' => 'whatsapp', 'dt' => 3),
    array(
        'db' => 'registered',
        'dt' => '4',
        'formatter' => function ($registered) {
            // Assuming $waktu is in the format 'Y-m-d H:i:s'
            $timestamp = strtotime($registered);
            return date('d-m-Y H:i:s', $timestamp); // Format as desired (dd-mm-yyyy H:i:s)
        }
    ),
    array(
        'db' => 'id_users',
        'dt' => '5',
        'formatter' => function ($id_users, $row) {
            $email = $row['email']; // Ambil nilai email dari baris data saat ini

            // Start the loop to display each transaction
            $html = '<a class="btn btn-warning btn-xs" title="Detail Data" href="?view=users&act=detail&id=' . $id_users. '&email='. $email.'"><span class="fa fa-eye"></span> Detail Data</a></td>';
            return $html;
        }
    ),

);

require('../ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns)
);
