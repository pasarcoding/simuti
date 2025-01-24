<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">

        <form method="GET">
          <input type="hidden" name="view" value="<?= $_GET[view] ?>">
          <div class="row">
            <div class="col-md-2">
              <label>Dari Tanggal</label>
              <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input class="form-control" type="text" name="tgl_mulai" readonly="readonly" placeholder="Tanggal Awal" value="<?= $_GET['tgl_mulai'] ?>">
              </div>
            </div>
            <div class="col-md-2">
              <label>Sampai Tanggal</label>
              <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input class="form-control" type="text" name="tgl_akhir" readonly="readonly" placeholder="Sampai Tanggal" value="<?= $_GET['tgl_akhir'] ?>">
              </div>
            </div>
            <div class="col-md-2">
              <div style="margin-top:25px;">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>Cari</button>
              </div>
            </div>
          </div>
          <br>

        </form>
      <?php } ?>
      <?php if (isset($_GET['tgl_mulai']) && isset($_GET['tgl_akhir'])) {  ?>

        <?php
        $tgl_mulai = $_GET['tgl_mulai'];
        $tgl_akhir = $_GET['tgl_akhir'];
        $ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$idTahunAjaran'"));
        ?>

      </div><!-- /.box-header -->
      <div class="box-body">
      </tbody>
          <h3 class='box-title'></h3> <a class='pull-right btn btn-success btn-md' style='margin-right:5px' target='_blank' href='excel_absen_per_siswa.php?tgl_mulai=<?=$_GET['tgl_mulai']?>&tgl_akhir=<?=$_GET['tgl_akhir']?>'><i class='fa fa-file-excel-o'></i> Excel Rekap Absensi </a>
          <br><br> 
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama PTK</th>
                <th>Waktu Input</th>
                <th>Status</th>
                <th>Lokasi</th>
                <th>Foto</th>
              </tr>
            </thead>
            <tbody>
              <?php


              $tampil = mysqli_query($conn, "SELECT  nama_guru,waktu_input,nama,latlng,address,kode_kehadiran,selfie FROM rb_absensi_guru 
              INNER JOIN rb_guru ON rb_absensi_guru.nip = rb_guru.id where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' ");

              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {

                $total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' GROUP BY tanggal"));

                $hadir = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='H'"));
                $sakit = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='S'"));
                $izin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='I'"));
                $alpa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='A'"));
                $sakitdokter = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='SKD'"));
                $libur = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='L'"));
                $pulang = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='P'"));
                $off = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='O'"));
                $cuti = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='C'"));
                $pulangcepat = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='PC'"));
                $dinasluar = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='DL'"));
                $PJJ = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='PJJ'"));
                //hitung keseluruhan siswa


                $hadirs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='H'"));
                $sakits = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='S'"));
                $izins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='I'"));
                $alpas = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='A'"));
                $sakitdokters = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='SKD'"));
                $liburs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='L'"));
                $pulangs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='P'"));
                $offs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='O'"));
                $cutis = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='C'"));
                $pulangcepats = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  AND kode_kehadiran='PC'"));
                $dinasluars = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='DL'"));
                $PJJs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND kode_kehadiran='PJJ'"));

                $totals = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'  "));

                $tambah = $hadir + $telat;
                $persen = $tambah / ($total) * 100;
                $data = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where DATE(tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir'"));
                if (!file_exists("./foto_absen/$r[nama]") or $r['nama'] == '') {
                  $foto_user = "blank.png";
                } else {
                  $foto_user = $r['nama'];
                }
                 
                $kode = $r['kode_kehadiran'];
                if ($kode == 'A') {
                    $statush = 'Alpa';
                } elseif ($kode == 'S' || $kode == 'I' || $kode == 'SD' || $kode == 'C') {
                    if (!file_exists("./foto_absen/$r[selfie]") or $r['selfie'] == '') {
                    $tombol = "";
                    } else {
                    $tombol = "<a class='btn btn-primary btn-xs' target='_blank' href='./foto_absen/$foto_izin'>Tampilkan Lampiran</a>";
                    }
                    $statush = ($kode == 'S') ? 'Sakit' : (($kode == 'I') ? 'Izin' : (($kode == 'SD') ? 'Sakit Ket Dokter' : 'Cuti'));
                } elseif ($kode == 'H') {
                    $statush = 'Hadir';
                } elseif ($kode == 'L') {
                    $statush = 'Libur';
                } elseif ($kode == 'O') {
                    $statush = 'Off';
                } elseif ($kode == 'PC') {
                    $statush = 'Pulang Cepat';
                } elseif ($kode == 'PJJ') {
                    $statush = 'PJJ/BDR';
                } else {
                    $statush = 'Pulang';
                }
                echo "<tr bgcolor=$warna>
                <td>$no</td>
              
                <td>$r[nama_guru]</td>

                <td>$r[waktu_input]</td>
                <td>$statush <br>$tombol</td>
                
                <td>
                <a href='https://maps.google.com/?q=$r[latlng]' target='_blank'>
                    View on Google Maps
                </a>
            </td>
                           
                <td align=center><img src=" . './foto_absen/' . $foto_user . " id='target'  width='100' height='100' class='img-thumbnail img-responsive'>
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
          <td colspan='1'>Guru Sakit Ket DOkter</td>
          <td > $sakitdokters</td>
        </tr>
        <tr>
          <td colspan='1'>Guru Libur</td>
          <td > $liburs</td>
        </tr>
        <tr>
          <td colspan='1'>Guru Dinas Luar</td>
          <td > $dinasluars</td>
        </tr>
        <tr>
          <td colspan='1'>PJJ/BDR</td>
          <td > $PJJs</td>
        </tr>
        <tr>
          <td colspan='1'>Guru Off</td>
          <td > $offs</td>
        </tr>
         <tr>
          <td colspan='1'>Guru Cuti</td>
          <td > $cutis</td>
        </tr>
        <tr>
          <td colspan='1'>Guru Pulang</td>
          <td > $pulangs</td>
        </tr>
        <tr>
          <td colspan='1'>Guru Pulang Cepat</td>
          <td > $pulangcepats</td>
        </tr>
          <tr>
          <td colspan='1'><b>Jumlah Total</b></td>
          <td >  $totals</td>
        </tr>
          ";

              ?>

            </tbody>
          </table>
        </div><!-- /.box-body -->
        <?php

        echo $sw;

        ?>
      </div>
    </div>
    
  <?php
      } elseif ($_GET[act] == 'tampilabsen') {
        $d = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM kelas_siswa where idKelas='$_GET[id]'"));
        $m = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[kd]'"));
        $n = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_jadwal_pelajaran where "));
        echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Rekap Data Absensi Siswa </b></h3> <a class='pull-right btn btn-success btn-sm' style='margin-right:5px' target='_blank' href='cetak_absen.php?id=$_GET[id]&kd=$_GET[kd]&jdwl=$_GET[jdwl]&tahun=$_GET[tahun]'><i class='fa fa-print'></i> Cetak Rekap Absen</a>
                </div>
              <div class='table-responsive'>

              <div class='col-md-12'>
              <table class='table table-condensed table-hover'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[idKelas]'>
                   
                    <tr><th width='120px' scope='row'>Nama Kelas</th> <td>$d[nmKelas]</td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>           <td>$m[namamatapelajaran]</td></tr>
                  </tbody>
              </table>
              </div>

              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered table-striped'>
                      <thead>
                      <tr>
                        <th>No</th>
                        <th>NIPD</th>
                        <th>Nama Siswa</th>
                        <th>Pertemuan</th>
                        <th>Hadir</th>
                        <th>Sakit</th>
                        <th>Izin</th>
                        <th>Alpa</th>
                        <th><center>% Kehadiran</center></th>
                      </tr>
                    </thead>
                    <tbody>";

        $no = 1;
        $tampil = mysqli_query($conn, "SELECT * FROM siswa a where a.idKelas='$_GET[id]' ORDER BY a.idSiswa");
        while ($r = mysqli_fetch_array($tampil)) {
          $total = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  GROUP BY tanggal"));
          $hadir = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  AND nisn='$r[nisnSiswa]' AND kode_kehadiran='H'"));
          $sakit = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  AND nisn='$r[nisnSiswa]' AND kode_kehadiran='S'"));
          $izin = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  AND nisn='$r[nisnSiswa]' AND kode_kehadiran='I'"));
          $alpa = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  AND nisn='$r[nisnSiswa]' AND kode_kehadiran='A'"));
          $persen = $hadir / ($total) * 100;
          echo "<tr bgcolor=$warna>
                            <td>$no</td>
                            <td>$r[nisnSiswa]</td>
                            <td>$r[nmSiswa]</td>
                            <td align=center>$total</td>
                            <td align=center>$hadir</td>
                            <td align=center>$sakit</td>
                            <td align=center>$izin</td>
                            <td align=center>$alpa</td>
                            <td align=right>" . number_format($persen, 2) . " %</td>";
          echo "</tr>";
          $no++;
        }

        echo "</tbody>
                  </table>
                </div>
              </div>
            </div>";
      }
  ?>