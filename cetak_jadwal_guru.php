<?php
error_reporting(0);
session_start();
include 'config/koneksi.php';
include 'config/rupiah.php';
include '../../config/library.php';
include "config/fungsi_indotgl.php";
include 'lib/function.php';
ob_start();
$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas "));
$dBayar = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM kelas_siswa WHERE idKelas='$_GET[class]'")); 
$classFilter = isset($_GET['class']) ? $_GET['class'] : '';?>

<!DOCTYPE html>
<html>

<head>
  <title>Cetak Rekap Data Nasabah</title>
  <link rel="stylesheet" href="bootstrap/css/printer.css">
          <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">

</head>

<body>
  <table width="100%">
    <tr>
      <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
      <td valign="top">
        <h3 align="center" style="margin-bottom:8px ">
          <?php echo $idt['nmSekolah']; ?>
          <center>Laporan Rekap Data Nasabah <br>
            <?php echo $idt['alamat']; ?></center>
        </h3>
      </td>
      <td width="100px" align="right"><img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="60px"></td>
    </tr>
  </table>
<h3>Kelas : <?=$dBayar['nmKelas']?></h3>
  <table border="1" class="table table-bordered table-striped">

   <thead>
            <tr>
              <th>HARI</th>
              <th> JAM</th>
               <th>MATA PELAJARAN</th>
               <th>KELAS</th> 
            </tr>
          </thead>
          <tbody>
            <?php
            
            $sqk = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where aktif='Y'"));
            $tampil = mysqli_query($conn, "SELECT *
            FROM hari ");
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              echo "<tr>
                              <td >$r[nmHari]</td>
                            </tr>";

              $query = "SELECT rb_jadwal_pelajaran.*, hari.nmHari, rb_mata_pelajaran.namamatapelajaran,
                      jam.idJam, jam.nmJam, jam.dariJam, jam.sampaiJam, kelas_siswa.nmKelas, rb_jadwal_pelajaran.kodejdwl
                      FROM rb_jadwal_pelajaran
                      INNER JOIN hari ON rb_jadwal_pelajaran.hari = hari.nmHari
                      INNER JOIN rb_mata_pelajaran ON rb_jadwal_pelajaran.kode_pelajaran = rb_mata_pelajaran.kode_pelajaran
                    INNER JOIN rb_guru ON rb_mata_pelajaran.nip = rb_guru.nbm

                      INNER JOIN kelas_siswa ON rb_jadwal_pelajaran.idKelas = kelas_siswa.idKelas
                      INNER JOIN jam ON rb_jadwal_pelajaran.jam_ke = jam.idJam
                      WHERE hari.id = '$r[id]' and rb_guru.nbm='$_SESSION[nbm]'";


            if ($classFilter != '') {
                $query .= " AND rb_jadwal_pelajaran.idKelas = '$classFilter'";
            }

            $query .= " ORDER BY jam.idJam ASC";
            $tampils = mysqli_query($conn, $query);


              while ($row = mysqli_fetch_array($tampils)) {
                $tgl = date('Y-m-d');
              

                echo "<tr><td></td>
            <td>$row[nmJam] ($row[dariJam]-$row[sampaiJam])</td>
            
           
            <td>$row[namamatapelajaran] </td>
            <td>$row[nmKelas] </td>
            
            </tr>";
                $no++;
              }
            }
            if (isset($_GET[hapus])) {
              mysqli_query($conn, "DELETE FROM rb_jadwal_pelajaran where kodejdwl='$_GET[hapus]'");
              echo "<script>document.location='?view=matapelajaran';</script>";
            }

            ?>
          </tbody>
        </table>
</body>

<script>
  window.print()
</script>

</html>
