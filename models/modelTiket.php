<?php
include_once '../config/conn.php';

// DB table to use
$table = 'tbl_tiket';

$primaryKey = 'id';

// Update this line with the correct column names
$columns = array(
    array('db' => 'id', 'dt' => 'no'),
    array('db' => 'judul', 'dt' => 'judul'),
    array('db' => 'views', 'dt' => 'views'),
    array(
        'db' => 'id',
        'dt' => 'aksi',
        'formatter' => function ($id) {
            return '<a class="btn btn-info btn-sm" title="Item Data" href="?view=tiket&act=item&id=' . $id . '&id_event=' . $id . '"><span class="fa fa-list"></span> List Item Tiket</a>
                    <a class="btn btn-success btn-sm" title="Edit Data" href="?view=tiket&act=edit&id=' . $id . '&id_event=' . $id . '"><span class="fa fa-edit"></span> Edit</a>
                    <a class="btn btn-danger btn-sm" title="Delete Data" href="?view=tiket&act=hapus_tiket&id=' . $id . '" onclick="return confirm(\'Apa anda yakin untuk hapus Data ini?\')"><span class="fa fa-remove"></span> Hapus</a>';
        }
    ),
);

require('../ssp.class.php');

echo json_encode(
    SSP::simple($_GET, $koneksi, $table, $primaryKey, $columns)
);
