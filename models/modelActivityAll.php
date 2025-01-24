<?php
include_once '../config/conn.php';
include_once '../config/koneksi.php';

session_start();

// DB table to use
$table = 'tbl_log';

$primaryKey = 'log_id';

$columns = array(
    array('db' => 'log_id', 'dt' => 0),
    array(
        'db' => 'id_user',
        'dt' => 1,
        'formatter' => function ($id_user) {
             global $conn;

            // Fetch user name from tbl_users based on id_user
            $query = "SELECT id_users FROM tbl_users WHERE email = '$id_user'";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            $user = mysqli_fetch_assoc($result);
            $id_users = $user['id_users'];
            return '<a href="?view=users&act=detail&id=' . $id_users . '&email=' . $id_user . '">' . $id_user . '</a>';
        }
    ),
    array('db' => 'aksi', 'dt' => 2),
    array('db' => 'os', 'dt' => 3),
    array('db' => 'bwoser', 'dt' => 4),
    array('db' => 'ip', 'dt' => 5),
    array('db' => 'latitude', 'dt' => 6),
    array('db' => 'longitude', 'dt' => 7),
    array(
        'db' => 'waktu',
        'dt' => 8,
        'formatter' => function ($registered) {
            // Assuming $waktu is in the format 'Y-m-d H:i:s'
            $timestamp = strtotime($registered);
            return date('d-m-Y H:i:s', $timestamp); // Format as desired (dd-mm-yyyy H:i:s)
        }
    ),
);

require('../ssp.class.php');

$records = SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns, NULL, NULL);
$output = array();



echo json_encode($records);
?>
