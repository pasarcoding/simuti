<?php
include 'config/koneksi.php'; // Pastikan koneksi database sudah di-include
include 'config/library.php'; // Pastikan koneksi database sudah di-include
include "config/fungsi_indotgl.php";

$where = $_GET['unit'];

// Filter yang diterima dari GET
$whereQuery = "WHERE unit='$where'";  // Filter berdasarkan unit

if (!empty($_GET['idTahunAjaran'])) {
    $whereQuery .= " AND rb_rencana_kegiatan.idTahunAjaran = '" . $_GET['idTahunAjaran'] . "'";
}

if (!empty($_GET['bulan'])) {
    $whereQuery .= " AND MONTH(rb_rencana_kegiatan.waktu_mulai) = '" . $_GET['bulan'] . "'";
}

if (!empty($_GET['approval'])) {
    $whereQuery .= " AND rb_rencana_kegiatan.status = '" . $_GET['approval'] . "'";
}

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas where npsn='10700295'"));
$sqltahun = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$_GET[idTahunAjaran]'"));

$bulanNama = isset($_GET['bulan']) ? getBulanReport($_GET[bulan]) : 'Semua Bulan';

// Query untuk mengambil data yang difilter
$tampil = mysqli_query($conn, "SELECT * FROM rb_rencana_kegiatan
    INNER JOIN tahun_ajaran ON rb_rencana_kegiatan.idTahunAjaran = tahun_ajaran.idTahunAjaran
    $whereQuery
    ORDER BY rb_rencana_kegiatan.idTahunAjaran ASC");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=Rencana_Kegiatan_" . date('Y-m-d') . ".xls");
echo "<center>
    <h3>" . strtoupper($idt['nmSekolah']) . "</h3>
</center>";
echo "<center>
    <h3> RENCANA KEGIATAN " . strtoupper($where) . " BULAN " . strtoupper($bulanNama) . "</h3>
</center>";
echo "<center>
    <h3> TAHUN AJARAN " . strtoupper($sqltahun['nmTahunAjaran']) . "</h3>
</center>";

echo "<table border='1'>
        <tr>
            <th>No</th>
            <th>Tahun Ajaran</th>
            <th>Nama Kegiatan</th>
            <th>Uraian</th>
            <th>Penanggung Jawab</th>
            <th>Waktu Mulai</th>
            <th>Waktu Selesai</th>
            <th>Tempat</th>
            <th>Rencana Anggaran</th>
            <th>Status</th>
        </tr>";

$no = 1;
while ($r = mysqli_fetch_array($tampil)) {
    echo "<tr>
            <td>$no</td>
            <td>$r[nmTahunAjaran]</td>
            <td>$r[nmKegiatan]</td>
            <td>$r[uraian]</td>
            <td>$r[pj]</td>
            <td>$r[waktu_mulai]</td>
            <td>$r[waktu_selesai]</td>
            <td>$r[tempat]</td>
            <td>" . buatRp($r['anggaran']) . "</td>
            <td>$r[status]</td>
          </tr>";
    $no++;
}
echo "</table>";
?>
<table width="100%">
    <tr>
      <td align="right" colspan="9"></td>
      
      
    </tr>
  </table>
<table width="100%">
    <tr>
      <td align="right" colspan="8"></td>
      
      <td align="right" width="400px">
        <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
        <br><br><br><br><br><br>
        <b> <?php echo $idt['nmKepsek']; ?></b>

      </td>
    </tr>
  </table>
