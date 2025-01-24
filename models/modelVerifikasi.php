<?php
include_once '../config/conn.php';
include_once '../config/koneksi.php';
include_once '../config/library.php';

// DB table to use
$table = 'tbl_verifikasi';

$primaryKey = 'idVerif';

// Update this line with the correct column names
$columns = array(
    array('db' => 'idVerif', 'dt' => 'no'),
    array(
        'db' => 'email',
        'dt' => 'email',
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
        'db' => 'email',
        'dt' => 'status',
        'formatter' => function ($id) use ($conn) {
            $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_users WHERE email='$id'"));
            if ($tampil['verifikasi'] == 'T') {
                $a = $tampil['id_user'];
                $icon = "fa-user-times";
                $btn = "btn-danger";
                $alt = "Validasi";
                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='?view=verifikasi&act=onoff&id=$id'><span class='fa $icon'></span> Belum Terverifikasi</a>";
            } else {
                $a = 'Y';
                $icon = "fa-user";
                $btn = "btn-success";
                $alt = "Validasi";
                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='#'><span class='fa $icon'></span>  Terverifikasi</a>";
            }

            return "<td>$onoff  </td>";
        }
    ),

    array(
        'db' => 'idVerif',
        'dt' => 'aksi',
        'formatter' => function ($id) {
            global $conn; // Assuming $conn is the database connection

            // Start the loop to display each transaction
            $html = '<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#detailModal' . $id . '"><span class="fa fa-eye"></span> Detail</button>
                <div class="modal fade" id="detailModal' . $id . '" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel' . $id . '" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="detailModalLabel' . $id . '">Detail Dokumen</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">';

            $querys = "SELECT * FROM tbl_verifikasi  WHERE idVerif = '$id'";
            $results = mysqli_query($conn, $querys);

            if (!$results) {
                die("Query failed: " . mysqli_error($conn));
            }
            $html .= '<div class="container-fluid">';
            $total = 0;

            if (mysqli_num_rows($results) > 0) {
                while ($transaction = mysqli_fetch_assoc($results)) {
                    // Use $transaction['id'], $transaction['waktu'], $transaction['jumlah'], etc.
                    $html .= '<div class="row mb-3">
                        <div class="col-md-4 mb-2 text-center">
                            <img src="' . $transaction['url'] . '" alt="Foto KTP" class="img-fluid" width="300">
                        </div>
                    </div>
                   ';
                }
            } else {
                $html .= '<p>Not found</p>';
            }

            $html .= '<div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>';


            return $html;
        }
    ),

);
require('../ssp.class.php');

// Modify the following line to pass the MySQLi connection object, not an array
echo json_encode(
    SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns, NULL, NULL)
);
