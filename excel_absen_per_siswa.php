<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";

if (isset($_SESSION['id'])) {
    $tgl_mulai = $_GET['tgl_mulai'];
    $tgl_akhir = $_GET['tgl_akhir'];
    $date_range = generateDateRange($tgl_mulai, $tgl_akhir);

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_absen_guru_" . date('dmyHis') . ".xls");
    // Output the beginning of the HTML document
    echo '<html>
    <head>
    <style>
    table {
      border-collapse: collapse;
      width: 100%;
    }
    
    th, td {
      border: 1px solid #dddddd;
      text-align: center;
      padding: 8px;
    }
    
    th {
      background-color: #f2f2f2;
    }
    </style>
    </head>
<body>';

    echo '<h3 align="center">Rekap Absensi PTK (' . tgl_indo($tgl_mulai) . ' - ' . tgl_indo($tgl_akhir) . ')</h3>';

    // Start the HTML table
    echo '<table>
    <thead>
        <tr>
            <th rowspan="2" width="50">No</th>
            <th rowspan="2" width="370">Nama PTK</th>';

    foreach ($date_range as $date) {
        echo '<th colspan="2">' . tgl_indo($date) . '</th>';
    }

    echo '<th rowspan="2" width="50">S</th>
        <th rowspan="2" width="50">I</th>
        <th rowspan="2" width="50">A</th>
        <th rowspan="2" width="50">JML</th>
    </tr>
    <tr>';
    foreach ($date_range as $date) {
        echo '<th width="90">Masuk</th>';
        echo '<th width="90">Pulang</th>';
    }
    echo '</tr>
    </thead>

    <tbody>';

    $tampil = mysqli_query($conn, "SELECT * FROM rb_guru ORDER BY nama_guru");
    $no = 1;

    while ($r = mysqli_fetch_array($tampil)) {
        echo '<tr>
                <td>' . $no . '</td>
                <td>' . $r['nama_guru'] . '</td>';

        $total_S = 0;
        $total_I = 0;
        $total_A = 0;
        $total_H = 0;

        foreach ($date_range as $date) {
            $query = mysqli_query($conn, "SELECT waktu_input, kode_kehadiran FROM rb_absensi_guru WHERE DATE(tanggal) = '$date' AND nip='{$r['id']}' ORDER BY id_absensi_guru ASC");
            $data_kehadiran = mysqli_fetch_all($query, MYSQLI_ASSOC);

            $foundP = $foundH = false;

            foreach ($data_kehadiran as $kehadiran) {
                if ($kehadiran['kode_kehadiran'] == 'L') {
                    $foundH = true;
                    echo '<td style="background-color:red;"></td>';
                } elseif ($kehadiran['kode_kehadiran'] == 'DL') {
                    $foundP = $foundH = true; // Set both foundH and foundP to true
                    echo '<td>' . $kehadiran['kode_kehadiran'] . '</td>
                          <td>' . $kehadiran['kode_kehadiran'] . '</td>';
                    $total_H += 1;
                } elseif ($kehadiran['kode_kehadiran'] == 'H') {
                    $foundH = true;
                    date_default_timezone_set('Asia/Jakarta');
                    $kode_kehadiran = date('H:i', strtotime($kehadiran['waktu_input']));
                    echo '<td>' . $kode_kehadiran . '</td>';
                    $total_H += 1;
                } elseif ($kehadiran['kode_kehadiran'] == 'PJJ') {
                    $foundH = true;
                    date_default_timezone_set('Asia/Jakarta');
                    $kode_kehadiran = date('H:i', strtotime($kehadiran['waktu_input']));
                    echo '<td>' . $kode_kehadiran . '</td>';
                    $total_H += 1;
                } elseif ($kehadiran['kode_kehadiran'] == 'T') {
                    $foundH = true;
                    date_default_timezone_set('Asia/Jakarta');
                    $kode_kehadiran = date('H:i', strtotime($kehadiran['waktu_input']));
                    echo '<td style="background-color:red;">' . $kode_kehadiran . '</td>';
                    $total_H += 1;
                } elseif ($kehadiran['kode_kehadiran'] == 'S' or $kehadiran['kode_kehadiran'] == 'SKD') {
                    $foundH = true;
                    echo '<td>' . $kehadiran['kode_kehadiran'] . '</td>';
                    $total_S += 1;
                } elseif ($kehadiran['kode_kehadiran'] == 'A') {
                    $foundH = true;
                    echo '<td>' . $kehadiran['kode_kehadiran'] . '</td>';
                    $total_A += 1;
                } elseif ($kehadiran['kode_kehadiran'] == 'C' or $kehadiran['kode_kehadiran'] == 'I' or $kehadiran['kode_kehadiran'] == 'Off') {
                    $foundH = true;
                    echo '<td>' . $kehadiran['kode_kehadiran'] . '</td>';
                    $total_I += 1;
                } elseif ($kehadiran['kode_kehadiran'] == 'P') {
                    $foundP = true;
                    date_default_timezone_set('Asia/Jakarta');
                    $kode_kehadiran_P = date('H:i', strtotime($kehadiran['waktu_input']));
                    echo '<td>' . $kode_kehadiran_P . '</td>';
                } elseif ($kehadiran['kode_kehadiran'] == 'PC') {
                    $foundP = true;
                    date_default_timezone_set('Asia/Jakarta');
                    $kode_kehadiran_P = date('H:i', strtotime($kehadiran['waktu_input']));
                    echo '<td style="background-color:yellow;">' . $kode_kehadiran_P . '</td>';
                }
            }
            if (!$foundH) {
                echo '<td>-</td>';
            }
            if (!$foundP) {
                echo '<td>-</td>';
            }
        }

        echo '<td>' . $total_S . '</td>';
        echo '<td>' . $total_I . '</td>';
        echo '<td>' . $total_A . '</td>';
        echo '<td>' . $total_H . '</td></tr>';
        $no++;
    }

    echo '</tbody></table>';

    // Output the end of the HTML document
    echo '</body>
</html>';
} else {
    include "login.php";
}

function generateDateRange($start, $end)
{
    try {
        $start_date = new DateTime($start);
        $end_date = new DateTime($end);
        $date_range = array();

        while ($start_date <= $end_date) {
            $date_range[] = $start_date->format('Y-m-d');
            $start_date->modify('+1 day');
        }

        return $date_range;
    } catch (Exception $e) {
        // Handle the exception if DateTime creation fails
        error_log("Error in generateDateRange: " . $e->getMessage());
        return array('error' => $e->getMessage());
    }
}
?>
