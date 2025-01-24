<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
if (isset($_SESSION[id])) {
  if ($_SESSION['level'] == 'admin') {
    $iden = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users where username='$_SESSION[id]'"));
    $nama =  $iden['nama_lengkap'];
    $level = 'Administrator';
    $foto = 'dist/img/user.png';
  }

  $tgl_mulai = $_GET['tgl_mulai'];
  $tgl_akhir = $_GET['tgl_akhir'];




  header("Content-type: application/vnd-ms-excel");
  header("Content-Disposition: attachment; filename=laporan_absen_guru_" . date('dmyHis') . ".xls");
?>
  <?php
  $tgl_mulai = $_GET['tgl_mulai'];
  $tgl_akhir = $_GET['tgl_akhir'];
  $ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$idTahunAjaran'"));
  ?>
  <table border="1">

    <h3 align='center'>Rekap Data Absensi PTK </b></h3>

    <h3></h3>

    <h4 class='box-title'>Dari Tanggal : <?php echo  tgl_indo($tgl_mulai); ?> Sampai <?php echo   tgl_indo($tgl_akhir); ?>

    </h4>

    <thead>
      <tr>
        <th>No</th>
        <th>Nama PTK</th>
        <th>Waktu Input</th>
        <th>Tempat Absen</th>
        <th>Alasan</th>
        <th>Lokasi</th>

      </tr>
    </thead>
    <tbody>
      <?php


      $tampil = mysqli_query($conn, "SELECT  nama_guru,waktu_input,nama,latlng,address,keterangan FROM rb_absensi_guru 
              INNER JOIN rb_guru ON rb_absensi_guru.nip = rb_guru.id ");

      $no = 1;
      while ($r = mysqli_fetch_array($tampil)) {

        $total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' GROUP BY tanggal"));

        $hadir = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='H'"));
        $sakit = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='S'"));
        $izin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='I'"));
        $alpa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='A'"));
        //hitung keseluruhan siswa


        $hadirs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='H'"));
        $sakits = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='S'"));
        $izins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='I'"));
        $alpas = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='A'"));
        $totals = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  "));

        $tambah = $hadir;
        $persen = $tambah / ($total) * 100;
        $data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'"));
        if (!file_exists("images/$r[nama]") or $r['nama'] == '') {
          $foto_user = "blank.png";
        } else {
          $foto_user = $r['nama'];
        }
        echo "<tr bgcolor=$warna>
                <td>$no</td>
              
                <td>$r[nama_guru]</td>

                <td>$r[waktu_input]</td>
                <td>$r[address]</td>
                <td>$r[keterangan]</td>
                
                <td id='map' >" . $r['latlng'] . "</td>
               
          
               ";
        echo " 
    </tr>
    ";
        $no++;
      }
      echo "</tbody></table> <tr>
<td colspan='1'><b>Keterangan</b></td>

</tr>
<table  border='1'>
<tr>
          <td colspan='1'>Guru Hadir</td>
          <td > $hadirs</td>
        </tr>
          <tr>
            <td colspan='1'>Guru Izin</td>
            <td > $izins</td>
          </tr>
          <tr>
            <td colspan='1'>Guru Sakit</td>
            <td > $sakits</td>
          </tr>
          <tr>
            <td colspan='1'>Guru Alpha</td>
            <td > $alpas</td>
          </tr>
          
          <tr>
          <td colspan='1'><b>Jumlah Total</b></td>
          <td >  $totals</td>
        </tr>
          ";
      echo " ";
      ?>

    </tbody>
  </table>
<?php
} else {
  include "login.php";
}
?>