<?php
include_once '../config/conn.php';
include_once '../config/koneksi.php';
include_once '../config/library.php';

// DB table to use
$table = 'tbl_order';

$primaryKey = 'id';

// Update this line with the correct column names
$columns = array(
    array('db' => 'id', 'dt' => 'no'),
    array('db' => 'invoice', 'dt' => 'invoice'),
    array(
        'db' => 'id_user',
        'dt' => 'id_user',
        'formatter' => function ($id_user) {
            global $conn;

            // Fetch user name from tbl_users based on id_user
            $query = "SELECT nama FROM tbl_users WHERE email = '$id_user'";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            $user = mysqli_fetch_assoc($result);
            return $user['nama'];
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
        'db' => 'id',
        'dt' => 'verif',
        'formatter' => function ($id) use ($conn) {
            $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tbl_order WHERE id='$id'"));

            $userLevel = $_SESSION['level']; // replace 'level' with the actual session variable name
            $isUser = ($userLevel == 'user');

            if ($tampil['verif'] == 'T') {
                $a = 'Y';
                $icon = "fa-times";
                $iconkirim = "fa-paper-plane";
                $btn = "btn-warning";
                $alt = "Validasi";
                $cek = "<a class='btn btn-primary btn-xs' title='$alt' href='../cekTransaksiBukuBank.php'><span class='fa fa-refresh'></span> Check Transaksi</a>";


                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='?view=payment&act=onoff&id=$tampil[invoice]&a=$a'><span class='fa $icon'></span> Belum Bayar</a>";
            } else if ($tampil['verif'] == 'Y') {
                $a = 'Y';
                $icon = "fa-check";
                $iconkirim = "fa-paper-plane";
                $btn = "btn-primary";
                $alt = "Validasi";
                $cek = "<a class='btn btn-success btn-xs' title='$alt' href='?view=payment&act=kirim&id=$tampil[invoice]'><span class='fa fa-plane'></span> Kirim ulang Qr</a>";

                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='#'><span class='fa $icon'></span>  Sukses</a>";
            } else if ($tampil['verif'] == 'P') {
                $a = 'Y';
                $icon = "fa-exclamation";
                $iconkirim = "fa-paper-plane";
                $btn = "btn-warning";
                $alt = "Validasi";
                $cek = "<a class='btn btn-primary btn-xs' title='$alt' href='../cekTransaksiBukuBank.php'><span class='fa fa-refresh'></span> Check Transaksi</a>";

                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='?view=payment&act=onoff&id=$tampil[invoice]&a=$a'><span class='fa $icon'></span> Pending</a>";
            } else {
                $a = 'Y';
                $icon = "fa-close";
                $iconkirim = "fa-paper-plane";
                $btn = "btn-danger";
                $alt = "Validasi";
                $cek = "";

                $onoff =   "<a class='btn $btn btn-xs' title='$alt' href='#'><span class='fa $icon'></span>  Gagal</a>";
            }

            return "<td>$onoff $cek </td>";
        }
    ),
    array(
        'db' => 'id',
        'dt' => 'total',
        'formatter' => function ($id) {
            global $conn; // Assuming $conn is the database connection


            $query_order = "SELECT invoice FROM tbl_order WHERE id = '$id'";
            $result_order = mysqli_query($conn, $query_order);

            if (!$result_order) {
                die("Query failed: " . mysqli_error($conn));
            }

            $order = mysqli_fetch_assoc($result_order);
            $invoice = $order['invoice'];

            // Start the loop to display each transaction

            $querys = "SELECT *,tbl_order.id AS Id FROM tbl_order 
                            INNER JOIN tbl_tiket_harga ON tbl_order.jenis=tbl_tiket_harga.id

                            WHERE tbl_order.invoice = '$invoice'";
            $results = mysqli_query($conn, $querys);

            if (!$results) {
                die("Query failed: " . mysqli_error($conn));
            }

            $total = 0;
            while ($transaction = mysqli_fetch_assoc($results)) {

                $subtotal = $transaction['harga'] * $transaction['jumlah'];

                $total += $subtotal + $transaction['kodeunik'];
            }




            return buatRp($total);
        }
    ),
    array(
    'db' => 'bukti',
    'dt' => 'bukti',
    'formatter' => function ($gambarBukti) {
        if ($gambarBukti) {
            $imagePath = BASEURL_WEB . "/assets/bukti/" . $gambarBukti;

            // Tambahkan atribut data-toggle dan data-target untuk memicu modal Bootstrap
            return '<button data-toggle="modal" class="btn btn-warning btn-xs" data-target="#imageModal"><i class="fa fa-eye"></i> Lihat Bukti</button>
                    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body text-center"> <!-- Tambahkan kelas text-center untuk menengahkan konten -->
                                    <img src="' . $imagePath . '" alt="Bukti" class="mx-auto" style="max-width: 50%;"> <!-- Tambahkan kelas mx-auto -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>';
        } else {
            return 'Tidak Ada Bukti';
        }
    }
),


    array(
        'db' => 'id',
        'dt' => 'aksi',
        'formatter' => function ($id) {
            global $conn; // Assuming $conn is the database connection

            // Fetch user information based on id_user
            $query = "SELECT nama FROM tbl_users WHERE email = (SELECT id_user FROM tbl_order WHERE id = $id)";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            $user = mysqli_fetch_assoc($result);
            $userName = $user['nama'];

            $query_order = "SELECT invoice FROM tbl_order WHERE id = '$id'";
            $result_order = mysqli_query($conn, $query_order);

            if (!$result_order) {
                die("Query failed: " . mysqli_error($conn));
            }

            $order = mysqli_fetch_assoc($result_order);
            $invoice = $order['invoice'];

            // Start the loop to display each transaction
            $html = '<button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#detailModal' . $id . '"><span class="fa fa-eye"></span> Detail</button>
                <div class="modal fade" id="detailModal' . $id . '" tabindex="-1" role="dialog" aria-labelledby="detailModalLabel' . $id . '" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h3 class="modal-title" id="detailModalLabel' . $id . '">Detail Transaksi</h3>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body table-responsive">';

            $querys = "SELECT *,tbl_order.id AS Id FROM tbl_order 
                            INNER JOIN tbl_tiket_harga ON tbl_order.jenis=tbl_tiket_harga.id
                                                        INNER JOIN tbl_tiket ON tbl_tiket_harga.id_event=tbl_tiket.id

                            WHERE tbl_order.invoice = '$invoice'";
            $results = mysqli_query($conn, $querys);

            if (!$results) {
                die("Query failed: " . mysqli_error($conn));
            }
            $html .= '<table class="table table-bordered">
                            <thead>
                                <tr>
                                <th>Judul</th>
                                    <th>Nama Item</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>';
            $total = 0;
            while ($transaction = mysqli_fetch_assoc($results)) {


                $subtotal = $transaction['harga'] * $transaction['jumlah'];

                // Use $transaction['id'], $transaction['waktu'], $transaction['jumlah'], etc.
                $html .= '<tr>
                 <td>' . $transaction['judul'] . '</td>
                        <td>' . $transaction['nama_tiket'] . '</td>
                        <td>' . buatRp($transaction['harga']) . '</td>
                        <td>' . $transaction['jumlah'] . '</td>
                        <td>' . buatRp($transaction['harga'] * $transaction['jumlah']) . '</td>
                        <td><a class="btn btn-warning btn-xs" title="Edit Data" href="?view=payment&act=edit&id=' . $transaction['Id'] . '"><span class="fa fa-edit"></span> Edit Data</a></td>
                    </tr>';

                $total += $subtotal + $transaction['kodeunik'];
                $kodeunik = $transaction['kodeunik'];
                $subs += $subtotal;
            }


            $html .= '<tr>
                                <td colspan="4" ><strong>Sub Total:</strong></td>
                                <td colspan="2"><strong>' . buatRp($subs) . '</strong></td>
                            </tr><tr>
                                <td colspan="4" ><strong>Kode Unik:</strong></td>
                                <td colspan="2"><strong>' . buatRp($kodeunik) . '</strong></td>
                            </tr><tr>
                                <td colspan="4" ><strong>Total:</strong></td>
                                <td colspan="2"><strong>' . buatRp($total) . '</strong></td>
                            </tr>
                            
                        </tbody>
                    </table>'; // Add a horizontal line to separate transactions

            $html .= '</div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>';

            return $html;
        }
    ),

);
$groupby = 'invoice';
require('../ssp.class.php');

// Modify the following line to pass the MySQLi connection object, not an array
echo json_encode(
    SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns, NULL, NULL, $groupby)
);
