<?php
include_once '../config/conn.php';
include_once '../config/koneksi.php';
include_once '../config/library.php';

// DB table to use
$table = 'tbl_refund';

$primaryKey = 'idRefund';

// Update this line with the correct column names
$columns = array(
    array('db' => 'idRefund', 'dt' => 'no'),
    array('db' => 'invoice', 'dt' => 'invoice'),
    array('db' => 'nmBank', 'dt' => 'nmBank'),
    array('db' => 'noRek', 'dt' => 'noRek'),
    array('db' => 'jumlah', 'dt' => 'jumlah'),
    array('db' => 'alasan', 'dt' => 'alasan'),
    array('db' => 'atasNama', 'dt' => 'atasNama'),
    array(
        'db' => 'id_user',
        'dt' => 'nama',
        'formatter' => function ($id_user) {
            global $conn;

            // Fetch user name from tbl_users based on id_user
            $query = "SELECT id_users,email,nama FROM tbl_users WHERE email = '$id_user'";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            $user = mysqli_fetch_assoc($result);
            $id_users = $user['id_users'];
            $nama = $user['nama'];

            return '<a href="?view=users&act=detail&id=' . $id_users . '&email=' . $id_user . '">' . $nama . '</a>';
        }
    ),
    array(
        'db' => 'waktu',
        'dt' => 'waktu',
        'formatter' => function ($waktu) {
            // Assuming $waktu is in the format 'Y-m-d H:i:s'
            $timestamp = strtotime($waktu);
            return date('d-m-Y H:i:s', $timestamp); // Format as desired (dd-mm-yyyy H:i:s)
        }
    ),
    array(
        'db' => 'idRefund',
        'dt' => 'statusRefund',
        'formatter' => function ($id) use ($conn) {
            $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_refund WHERE idRefund='$id'"));
            if ($tampil['statusRefund'] == 'T') {
                $icon = "fa-user-times";
                $btn = "btn-warning";
                $alt = "Validasi";
                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='?view=refund&act=onoff&id=$id&invoice=$tampil[invoice]'><span class='fa $icon'></span> Belum Diproses</a>";
                $tolak =   "<a class='btn $btn btn-xs' title='$alt' href='?view=refund&act=tolak&id=$id&invoice=$tampil[invoice]'><span class='fa $icon'></span> Tolak</a>";
            } else if ($tampil['statusRefund'] == 'Y') {
                $a = 'Y';
                $icon = "fa-user";
                $btn = "btn-success";
                $alt = "Validasi";
                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='#'><span class='fa $icon'></span>  Terproses</a>";
            } else {
                $a = 'Y';
                $icon = "fa-close";
                $btn = "btn-danger";
                $alt = "Validasi";
                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='#'><span class='fa $icon'></span>  Ditolak</a>";
            }

            return "<td>$onoff $tolak </td>";
        }
    ),



);
require('../ssp.class.php');

// Modify the following line to pass the MySQLi connection object, not an array
echo json_encode(
    SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns, NULL, NULL)
);
