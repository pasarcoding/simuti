<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";

if (isset($_SESSION['id'])) {
    $tgl_mulai = $_GET['tgl_mulai'];
    $tgl_akhir = $_GET['tgl_akhir'];
    $date_range = generateDateRange($tgl_mulai, $tgl_akhir);

    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=laporan_absen_" . date('dmyHis') . ".xls");
?>
    <table border="1">
        <h3 align='center'>Rekap Data Absensi Guru </h3>
        <h4 class='box-title'>Dari Tanggal : <?php echo tgl_indo($tgl_mulai); ?> Sampai <?php echo tgl_indo($tgl_akhir); ?>
        </h4>
        <tbody>
        </tbody>
        <thead>
            <tr>
                <th rowspan='2'>No</th>
                <th rowspan='2'>Nama </th>
                <?php foreach ($date_range as $date) : ?>
                    <th rowspan='2'><?php echo tgl_indo($date); ?></th>
                <?php endforeach; ?>
                <th colspan='3'>Keterangan</th>
                <th rowspan='2'>Jml</th>
                <th rowspan='2'>
                    <center>% Kehadiran</center>
                </th>
            </tr>
            <tr>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Alpa</th>
            </tr>
        </thead>
        <tbody>
            <?php

            $tampil = mysqli_query($conn, "SELECT * FROM rb_guru ORDER BY nama_guru");
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr>";
                echo "<td align='center'>$no</td>";
                echo "<td>{$r['nama_guru']}</td>";

                foreach ($date_range as $date) {
                    $tampils = mysqli_fetch_array(mysqli_query($conn, "SELECT *, TIME(waktu_input) AS jam FROM rb_absensi_guru WHERE DATE(tanggal) = '$date' AND nip='{$r['id']}'"));

                    if ($tampils) {
                        // Absence data exists for the date
                        if ($tampils['kode_kehadiran'] == 'H') {
                            // Display the time if kode_kehadiran is 'H'
                            $jam = date("H:i:s", strtotime($tampils['jam']));
                            echo "<td align='center'>$jam</td>";
                        } else {
                            // Display 'Izin' for 'I', 'Sakit' for 'S', 'Alpha' for 'A'
                            $keterangan = ($tampils['kode_kehadiran'] == 'I') ? 'Izin' : (($tampils['kode_kehadiran'] == 'S') ? 'Sakit' : 'Alpha');
                            echo "<td align='center'>$keterangan</td>";
                        }
                    } else {
                        // No absence data for the date
                        echo "<td align='center'>-</td>";
                    }
                }

                $total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' GROUP BY tanggal "));

                $hadir = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' and nip='{$r['id']}' AND kode_kehadiran='H'"));
                $sakit = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' and nip='{$r['id']}' AND kode_kehadiran='S'"));
                $izin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' and nip='{$r['id']}' AND kode_kehadiran='I'"));
                $alpa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' and nip='{$r['id']}' AND kode_kehadiran='A'"));

                echo "<td align=center>$sakit</td>";
                echo "<td align=center>$izin</td>";
                echo "<td align=center>$alpa</td>";
                echo "<td align=center>$hadir</td>";

                $tambah = $hadir;
                $persen =  $tambah / ($total) * 100;
                echo "<td align=right>" . number_format($persen, 2) . " %</td>";
                echo "</tr>";
                $no++;
            }
            ?>
        </tbody>
    </table>
<?php
} else {
    include "login.php";
}

function generateDateRange($start, $end)
{
    $start_date = new DateTime($start);
    $end_date = new DateTime($end);
    $date_range = array();

    while ($start_date <= $end_date) {
        $date_range[] = $start_date->format('Y-m-d');
        $start_date->modify('+1 day');
    }

    return $date_range;
}
?>