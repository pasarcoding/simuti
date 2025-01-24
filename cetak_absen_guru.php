<html>

<head>
  <title>Cetak - Laporan Absensi Guru</title>
  <link rel="stylesheet" href="bootstrap/css/printer.css">
</head>

<body>
  <?php
  session_start();
  error_reporting(0);
  include "config/koneksi.php";
  include "config/fungsi_indotgl.php";


  $tgl_mulai = $_GET['tgl_mulai'];
  $tgl_akhir = $_GET['tgl_akhir'];


  $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));

  ?>
  <div class='col-md-12'>
    <div class='box box-info'>
      <div class='box-header with-border'>
        <h3 align='center'>Rekap Data Absensi Guru </b></h3>
      </div>
      <div class='table-responsive'>

        <div class='col-md-12'>
          <input type='hidden' name='kelas' value='$s[idKelas]'>
          <h4 class='box-title'>Dari Tanggal : <?php echo  tgl_indo($tgl_mulai); ?> Sampai <?php echo   tgl_indo($tgl_akhir); ?>
          </h4>
        </div>

        <?php
        $tgl_mulai = $_GET['tgl_mulai'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$idTahunAjaran'"));
        ?>

      </div><!-- /.box-header -->
      <div class="table-responsive">
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>

              <th>Nama Guru</th>

              <th>Waktu Input</th>
              <th>Tempat Absen</th>
              <th>Alasan</th>

              <th>Lokasi</th>
              <th>Foto</th>

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
              $telat = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='T'"));
              //hitung keseluruhan siswa


              $hadirs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='H'"));
              $sakits = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='S'"));
              $izins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='I'"));
              $alpas = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='A'"));
              $telats = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='T'"));
              $totals = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  "));

              $tambah = $hadir + $telat;
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
               
                <td align=center><img src=" . './images/' . $foto_user . " id='target'  width='100' height='100' class='img-thumbnail img-responsive'>
                </td>
               ";
              echo " 
    </tr>
    ";
              $no++;
            }
            echo "</tbody></table><tr>
            <td colspan='1'><b>Keterangan</b></td>
            
          </tr>
          <table id='example' class='table table-bordered table-striped' width='40%'>
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
          <td colspan='1'>Guru Terlambat</td>
          <td > $telats</td>
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
      </div><!-- /.box-body -->
      <?php

      echo $sw;

      ?>
    </div>
  </div>
  <br>
  <table width="100%">
    <tr>
      <td align="center"></td>
      <td align="center" width="400px">
        <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
        <br><br><br><br><br><br>
        <b> <?php echo $idt['nmKepsek']; ?></b>

      </td>
    </tr>
  </table>
</body>
<script>
  window.print()
</script>

</html>