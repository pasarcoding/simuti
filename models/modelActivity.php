<?php
include_once '../config/conn.php';
session_start();

// DB table to use
$table = 'tbl_log';

$primaryKey = 'log_id';

$columns = array(
    array('db' => 'log_id', 'dt' => 0),
    array('db' => 'aksi', 'dt' => 1),
    array('db' => 'os', 'dt' => 2),
    array('db' => 'bwoser', 'dt' => 3),
        array('db' => 'ip', 'dt' => 4),

    array('db' => 'latitude', 'dt' => 5),
    array('db' => 'longitude', 'dt' => 6),
    array(
        'db' => 'waktu',
        'dt' => 7,
        'formatter' => function ($registered) {
            // Assuming $waktu is in the format 'Y-m-d H:i:s'
            $timestamp = strtotime($registered);
            return date('d-m-Y H:i:s', $timestamp); // Format as desired (dd-mm-yyyy H:i:s)
        }
    ),
);

$id_user = isset($_GET['email']) ? $_GET['email'] : null;

// Menambahkan kondisi WHERE berdasarkan id_user
$where = "id_user = '" . $id_user . "'";
require('../ssp.class.php');

$records = SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns, NULL, $where);
$output = array();



echo json_encode($records);
?>
