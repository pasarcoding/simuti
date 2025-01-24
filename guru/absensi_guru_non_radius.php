<?php if ($_GET[act] == '') {
  if (isset($_GET[gettanggal])) {
    $filtertgl = $_GET[gettanggal];
  } else {
    if (isset($_POST[tgl])) {
      $tgl = $_POST[tgl];
    } else {
      $tgl = date("d");
    }
    if (isset($_POST[bln])) {
      $bln = $_POST[bln];
    } else {
      $bln = date("m");
    }
    if (isset($_POST[thn])) {
      $thn = $_POST[thn];
    } else {
      $thn = date("Y");
    }
    $lebartgl = strlen($tgl);
    $lebarbln = strlen($bln);

    switch ($lebartgl) {
      case 1: {
          $tglc = "0" . $tgl;
          break;
        }
      case 2: {
          $tglc = $tgl;
          break;
        }
    }

    switch ($lebarbln) {
      case 1: {
          $blnc = "0" . $bln;
          break;
        }
      case 2: {
          $blnc = $bln;
          break;
        }
    }
    $filtertgl = $thn . "-" . $blnc . "-" . $tglc;
  }
  $ex = explode('-', $filtertgl);
  $tahun = $ex[0];
  $bulane = $ex[1];
  $tanggal = $ex[2];
  if (substr($tanggal, 0, 1) == '0') {
    $tgle = substr($tanggal, 1, 1);
  } else {
    $tgle = substr($tanggal, 0, 2);
  }
  if (substr($bulane, 0, 1) == '0') {
    $blnee = substr($bulane, 1, 1);
  } else {
    $blnee = substr($bulane, 0, 2);
  }
  $waktus = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_setting_absen limit 1 "));
  $waktu_masuks = $waktus['waktu_masuk'];
  $waktu_pulangs = $waktus['waktu_pulang'];
  $waktu_absen = $waktus['waktu_absen'];
  $waktu_vatas = $waktus['waktu_batas_pulang'];
  if ($jam_sekarang < $waktu_masuks && $jam_sekarang > $waktu_absen && $jam_sekarang < $waktu_pulangs) {
    $statuss = 'Masuk';
  } elseif ($jam_sekarang > $waktu_masuks && $jam_sekarang > $waktu_pulangs && $jam_sekarang < $waktu_vatas) {
    $statuss = 'Pulang';
  } elseif ($jam_sekarang > $waktu_masuks && $jam_sekarang < $waktu_pulangs) {
    $statuss = 'Terlambat';
  } else {
    $statuss = 'Bukan Jam Absen';
  }
?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">

        <center>
          <h3 class="box-title">Pengajuan Izin Pada : <b style='color:red'><?php echo tgl_indo("$filtertgl") . "</b>"; ?> </h3>
          <br>

          <br>
          
        </center>
        <div class="box-body">
          <?php
          if (isset($_GET['sukses'])) {
            echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Anda sudah absen hari ini,..
					</div>";
          } elseif (isset($_GET['gagal'])) {
            echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Anda sudah absen hari ini..
					</div>";
          } elseif (isset($_GET['suksess'])) {
            echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
            <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
            <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Anda sudah absen pulang hari ini,..
            </div>";
          }
          ?>
          <div class="table-responsive">
            <table id="example" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style='width:20px'>No</th>
                  <th>Nama Guru</th>

                </tr>
              </thead>
              <tbody>
                   <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <?php
                  $tampil = mysqli_query($conn, "SELECT * FROM rb_guru where id='$_SESSION[id]'");
                

                $no = 1;
                while ($r = mysqli_fetch_array($tampil)) {
                  if (isset($_GET[gettanggal])) {
                    $sekarangabsen = $_GET[gettanggal];
                  } else {
                    if (isset($_POST[lihat])) {
                      $sekarangabsen = $thn . "-" . $blnc . "-" . $tglc;
                    } else {
                      $sekarangabsen = date("Y-m-d");
                    }
                  }
                                                  $a = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='$_SESSION[id]' AND tanggal='$sekarangabsen' "));

                 echo "<tr><td>$no</td>
                      <td>$r[nama_guru]</td>
                      <td>
                          <input type='hidden' name='tgla' value='$tglc'>
                          <input type='hidden' name='blna' value='$blnc'>
                          <input type='hidden' name='thna' value='$thn'>
                          <input type='hidden' value='$r[id]' name='nip[$no]'>
                          <select style='width:100px;' name='a[$no]' class='form-control'>";
                
                $kehadiran = mysqli_query($conn, "SELECT * FROM rb_kehadiran where kode_kehadiran IN ('I','S','SKD','C') ");
                while ($k = mysqli_fetch_array($kehadiran)) {
                  if ($a['kode_kehadiran'] == $k['kode_kehadiran']) {
                    echo "<option value='$k[kode_kehadiran]' selected>* $k[nama_kehadiran]</option>";
                  } else {
                    echo "<option value='$k[kode_kehadiran]'>$k[nama_kehadiran]</option>";
                  }
                }
                
                echo "</select>
                      </td>
                      </tr>";

                  $no++;
                }
                ?>

              </tbody>
            </table>

            <div class='col-md-12 row'>
              <div class='col-12 col-md-3' style='display: flex; flex-direction: column; justify-content: center; align-items: center;'>
                <div id='vid-cam'></div>
                <div style='display: flex;'>
                  <button type='button' class='btn btn-info' style='margin: 1rem;' id='take'>Ambil Gambar</button>
                  <button type='button' class='btn btn-info' style='margin: 1rem; display: none;' id='reCam'>Ambil Gambar Ulang</button>
                </div>
              </div>
              <div class='col-12 col-md-3'>
                <div style='margin: 2rem;'>
                  <div class='form-group'>
                    <input type='file' name='flogokiri' id='file-cam' style="display: none;" required>
                    <label>Latitude, Longitude</label>
                    <input type='text' name='latlng' id='latlng' class='form-control' readonly>
                  </div>
                  <div class='form-group'>
                    <label>Lampiran</label>
                    <input type="file" class="form-control form-control-sm" name="flogokanan" id="foto" accept=".png, .jpg, .jpeg" >
                    <p>Jika ada surat keterangan</p>
                  </div>
                
                </div>
              </div>
              <div class='col-12 col-md-6' style='height: 300px;'>
                <div id='map' style='height: 100%; width: 100%;'></div>
              </div>

            </div>

          </div><!-- /.box-body -->

          <div class='box-footer'>
            <?php
            echo "<input type='hidden' name='filtertgl' value='$filtertgl'>";
            ?>
            <button type='submit' name='simpann' class='btn btn-info pull-right'>Simpan Absensi</button>
          </div>

          </form>
        </div><!-- /.box -->
      </div>
      <?php
      if (isset($_POST[simpann])) {
        $jml_data = count($_POST[nip]);
        $nip = $_POST[nip];
        $a = $_POST[a];
        $e    = $_POST[thna];
        $f   = $_POST[blna];
        $g    = $_POST[tgla];

        for ($i = 1; $i <= $jml_data; $i++) {
          $cak = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where  nip='" . $nip[$i] . "' AND tanggal='" . $e . "-" . $f . "-" . $g . "'");
          $totals = mysqli_num_rows($cak);

          if ($totals == '1') {
            echo "<script>document.location='?view=absengurus&gagal';</script>";
          } else {
            $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where  nip='" . $nip[$i] . "' AND tanggal='" . $e . "-" . $f . "-" . $g . "'");
            $total = mysqli_num_rows($cek);

            if ($total >= 1) {
              mysqli_query($conn, "UPDATE rb_absensi_guru SET kode_kehadiran = '" . $a[$i] . "' where nip='" . $nip[$i] . "' AND tanggal='" . $e . "-" . $f . "-" . $g . "'");

              $lst_siswa = mysqli_query($conn, "SELECT * FROM rb_guru where id='" . $nip[$i] . "'");
              while ($cs = mysqli_fetch_array($lst_siswa)) {
                $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='" . $nip[$i] . "' AND tanggal='" . $e . "-" . $f . "-" . $g . "' and kode_kehadiran!='H'");
                while ($cik = mysqli_fetch_array($cek)) {

                  $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
                  $link = $idt['link_one_sender'];
                  $links = $idt['token'];
                  $wa = $idt['wa'];
                  $ortu = $cs['nmOrtu'];
                  $phone = $cs['noHpOrtu'];
                  $cak = $cik['kode_kehadiran'];
                  $waktu = tgl_indo($cik['tanggal']);
                  $waktus = hari_ini($cik['tanggal']);


                  if ($cak == 'A') {
                    $statush = 'Alpa';
                  } elseif ($cak == 'S') {
                    $statush = 'Sakit';
                  } elseif ($cak == 'I') {
                    $statush = 'Izin';
                  } elseif ($cak == 'T') {
                    $statush = 'Terlambat';
                  } else {
                    $statush = 'Hadir';
                  }
                  $hari  = date('d');
                  $bulan = date('m');
                  $tahun = date('Y');

                  $hadirs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and  id='$_SESSION[nips]' AND kode_kehadiran='H'"));
                  $sakits = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and id='$_SESSION[nips]' AND kode_kehadiran='S'"));
                  $izins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  month(tanggal)='$bulan' AND year(tanggal)='$tahun' and id='$_SESSION[nips]'  AND kode_kehadiran='I'"));
                  $alpas = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and  id='$_SESSION[nips]'  AND kode_kehadiran='A'"));
                  $telats = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  month(tanggal)='$bulan' AND year(tanggal)='$tahun' and id='$_SESSION[nips]' AND kode_kehadiran='T'"));
                  $totals = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and id='$_SESSION[nips]' "));

//                   $isi_pesan = "Kepada Yth:
// Diberitahukan kepada Yth Bpk/Ibk *$ortu*, selaku orang tua dari siswa:

// Nama Siswa : *$cs[nmSiswa]*
// kelas: *$cs[nmKelas]* 

// Menerangkan bahwa siswa tersebut Hari ini : $waktus, $waktu tidak hadir ke sekolah dengan keterangan : _*$statush*_

// Catatan ketidakhadiran bulan ini:
// Sakit: $sakits
// Ijin : $izins
// Terlambat: $telats
// Alpha: $alpas

// Sesuai dengan peraturan sekolah kami, dengan melihat catatan Siswa tersebut diatas sekarang dalam penangannan khusus (Wali Kelas/Guru BK)";

//                   $data = [
//                     "api_key" => $links,
//                     "sender" => $wa,
//                     "number" => $phone,
//                     "message" => $isi_pesan
//                   ];
//                   $curl = curl_init();
//                   curl_setopt_array($curl, array(
//                     CURLOPT_URL => $idt['link_one_sender'],
//                     CURLOPT_RETURNTRANSFER => true,
//                     CURLOPT_SSL_VERIFYHOST => false,
//                     CURLOPT_SSL_VERIFYPEER => false,
//                     CURLOPT_MAXREDIRS => 10,
//                     CURLOPT_FOLLOWLOCATION => true,
//                     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                     CURLOPT_CUSTOMREQUEST => 'POST',
//                     CURLOPT_POSTFIELDS => json_encode($data),
//                     CURLOPT_HTTPHEADER => array(
//                       'Content-Type: application/json',
//                     ),
//                   ));

//                   $response = curl_exec($curl);

//                   curl_close($curl);
                }
              }
            } else {
              $lokasi_file_kiri = $_FILES['flogokiri']['tmp_name'];
              $nama_file_kiri   = $_FILES['flogokiri']['name'];

              $lokasi_file_kanan = $_FILES['flogokanan']['tmp_name'];
              $nama_file_kanan  = $_FILES['flogokanan']['name'];
              $nama_file = $_FILES['gambar']['name'];
          $ukuran_file = $_FILES['gambar']['size'];
          $tipe_file = $_FILES['gambar']['type'];
          $tmp_file = $_FILES['gambar']['tmp_name'];

          $path = "./images/" . $nama_file;
          move_uploaded_file($tmp_file, $path);  
              UploadFotoSelfie($nama_file_kiri);
              if (!empty($lokasi_file_kanan)) {
                UploadFotoLampiran($nama_file_kanan);
                mysqli_query($conn, "INSERT INTO rb_absensi_guru VALUES('','" . $nip[$i] . "','" . $a[$i] . "','" . $e . "-" . $f . "-" . $g . "','" . date('Y-m-d H:i:s') . "','" . $nama_file_kiri . "', '" . $nama_file_kanan . "','" . $ukuran_file . "','" . $tipe_file . "', '" . $_POST['latlng'] . "', '', '" . $_POST['keterangan'] . "')");
              } else {
                mysqli_query($conn, "INSERT INTO rb_absensi_guru VALUES('','" . $nip[$i] . "','" . $a[$i] . "','" . $e . "-" . $f . "-" . $g . "','" . date('Y-m-d H:i:s') . "','" . $nama_file_kiri . "', '" . $nama_file_kanan . "','" . $ukuran_file . "','" . $tipe_file . "', '" . $_POST['latlng'] . "', '',  '" . $_POST['keterangan'] . "')");
              }


              $lst_siswa = mysqli_query($conn, "SELECT * FROM rb_guru where id='" . $nip[$i] . "'");
              while ($cs = mysqli_fetch_array($lst_siswa)) {
                $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where  nip='" . $nip[$i] . "' AND tanggal='" . $e . "-" . $f . "-" . $g . "'and kode_kehadiran!='H'");
                while ($cik = mysqli_fetch_array($cek)) {

                  $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
                  $link = $idt['link_one_sender'];
                  $links = $idt['token'];
                  $wa = $idt['wa'];

                  $phone = $idts['no_telp'];
                  $cak = $cik['kode_kehadiran'];
                  $waktu = tgl_indo($cik['tanggal']);
                  $waktus = hari_ini($cik['tanggal']);

                  if ($cak == 'A') {
                    $statush = 'Alpa';
                  } elseif ($cak == 'S') {
                    $statush = 'Sakit';
                  } elseif ($cak == 'I') {
                    $statush = 'Izin';
                  } elseif ($cak == 'T') {
                    $statush = 'Terlambat';
                  } else {
                    $statush = 'Hadir';
                  }

                  $hari  = date('d');
                  $bulan = date('m');
                  $tahun = date('Y');

                  $hadirs = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and  nip='$_SESSION[nips]' AND kode_kehadiran='H'"));
                  $sakits = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and nip='$_SESSION[nips]' AND kode_kehadiran='S'"));
                  $izins = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  month(tanggal)='$bulan' AND year(tanggal)='$tahun' and nip='$_SESSION[nips]'  AND kode_kehadiran='I'"));
                  $alpas = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and  nip='$_SESSION[nips]'  AND kode_kehadiran='A'"));
                  $telats = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where  month(tanggal)='$bulan' AND year(tanggal)='$tahun' and nip='$_SESSION[nips]' AND kode_kehadiran='T'"));
                  $totals = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM `rb_absensi_guru` where month(tanggal)='$bulan' AND year(tanggal)='$tahun' and nip='$_SESSION[nips]' "));

                  // $isi_pesan = 'Diberitahukan Bahwa Bpk/Ibu Guru dengan nama *' . $cs['nama_guru'] . '*, absensi Hari ini ' . $waktus . ', ' . $waktu . ' : _*' . $statush . '*_';

                  // $data = [
                  //   "api_key" => $links,
                  //   "sender" => $wa,
                  //   "number" => $phone,
                  //   "message" => $isi_pesan
                  // ];
                  // $curl = curl_init();
                  // curl_setopt_array($curl, array(
                  //   CURLOPT_URL => $idt['link_one_sender'],
                  //   CURLOPT_RETURNTRANSFER => true,
                  //   CURLOPT_SSL_VERIFYHOST => false,
                  //   CURLOPT_SSL_VERIFYPEER => false,
                  //   CURLOPT_MAXREDIRS => 10,
                  //   CURLOPT_FOLLOWLOCATION => true,
                  //   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                  //   CURLOPT_CUSTOMREQUEST => 'POST',
                  //   CURLOPT_POSTFIELDS => json_encode($data),
                  //   CURLOPT_HTTPHEADER => array(
                  //     'Content-Type: application/json',
                  //   ),
                  // ));

                  // $response = curl_exec($curl);

                  // curl_close($curl);
                }
              }
            }
          }
        }
        echo "<script>document.location='?view=absengurus&tahun=" . $_GET[tahun] . "';</script>";
      }
    } elseif ($_GET[act] == 'detailabsenguru') { ?>
      <div class="col-xs-12">
        <div class="box">
          <div class="box-header">
            <h3 class="box-title"><?php if (isset($_GET[tahun])) {
                                    echo "Absensi Siswa";
                                  } else {
                                    echo "Absensi Siswa Pada " . date('Y');
                                  } ?></h3>
            <form style='margin-right:5px; margin-top:0px' class='pull-right' action='' method='GET'>
              <input type="hidden" name='view' value='absensiswa'>
              <input type="hidden" name='act' value='detailabsenguru'>
              <select name='tahun' style='padding:4px'>
                <?php
                echo "<option value=''>- Pilih Tahun Akademik -</option>";
                $tahun = mysqli_query($conn, "SELECT * FROM rb_tahun_akademik");
                while ($k = mysqli_fetch_array($tahun)) {
                  if ($_GET[tahun] == $k[id_tahun_akademik]) {
                    echo "<option value='$k[id_tahun_akademik]' selected>$k[nama_tahun]</option>";
                  } else {
                    echo "<option value='$k[id_tahun_akademik]'>$k[nama_tahun]</option>";
                  }
                }
                ?>
              </select>
              <input type="submit" style='margin-top:-4px' class='btn btn-success btn-sm' value='Lihat'>
            </form>

          </div><!-- /.box-header -->
          <div class="table-responsive">
            <table id="example1" class="table table-bordered table-striped">
              <thead>
                <tr>
                  <th style='width:20px'>No</th>
                  <th>Jadwal Pelajaran</th>
                  <th>Kelas</th>
                  <th>Guru</th>
                  <th>Hari</th>
                  <th>Mulai</th>
                  <th>Semester</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (isset($_GET[tahun])) {
                  $tampil = mysqli_query($conn, "SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                
                                                  JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik='$_GET[tahun]' ORDER BY a.hari DESC");
                } else {
                  $tampil = mysqli_query($conn, "SELECT a.*, e.nama_kelas, b.namamatapelajaran, b.kode_pelajaran, c.nama_guru FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kode_pelajaran=b.kode_pelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                             
                                                JOIN rb_kelas e ON a.kode_kelas=e.kode_kelas 
                                                  where a.nip='$_SESSION[id]' AND a.id_tahun_akademik LIKE '" . date('Y') . "%' ORDER BY a.hari DESC");
                }
                $no = 1;
                while ($r = mysqli_fetch_array($tampil)) {
                  echo "<tr><td>$no</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_kelas]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_ke]</td>
                              <td>$r[id_tahun_akademik]</td>
                              <td><a class='btn btn-success btn-xs' title='Tampil List Absensi' href='?view=absengurus&act=tampilabsen&id=$r[kode_kelas]&kd=$r[kode_pelajaran]&jdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-th'></span> Tampilk Absensi</a></td>
                          </tr>";
                  $no++;
                }
                ?>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div>
      </div>
    </div>

  <?php
    } elseif ($_GET[act] == 'detailabsensiswa') {
      echo "<div class='col-xs-12'>  
              <div class='box'>
                <div class='box-header'>
                  <h3 class='box-title'>Data Absensi Siswa untuk Mata Pelajaran yang di Ampu</h3>
                </div>
                <div class='box-body'>
                <b class='semester'>SEMESTER 1</b>
                <table class='table table-bordered table-striped'>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Pelajaran</th>
                        <th>Nama Pelajaran</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Action</th>
                      </tr>";
      $tampil = mysqli_query($conn, "SELECT * FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kodepelajaran=b.kodepelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_kelas d ON a.kodekelas=d.kodekelas where a.kodekelas='$iden[kodekelas]' AND a.semester='1' ORDER BY a.hari DESC");
      $no = 1;
      while ($r = mysqli_fetch_array($tampil)) {
        echo "<tr><td>$no</td>
                              <td>$r[kodepelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[kelas]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai] WIB</td>
                              <td>$r[jam_selesai] WIB</td>
                              <td style='width:70px !important'><center>
                                <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='?view=absengurus&act=tampilabsen&id=$r[kodekelas]&kd=$r[kodepelajaran]&jdwl=$r[kodejdwl]'><span class='glyphicon glyphicon-th'></span> Tampilkan Absensi</a>
                              </center></td>";
        echo "</tr>";
        $no++;
      }

      echo "</table><br>
                  
                  <b class='semester'>SEMESTER 2</b>
                  <table class='table table-bordered table-striped'>
                      <tr>
                        <th style='width:40px'>No</th>
                        <th>Kode Pelajaran</th>
                        <th>Nama Pelajaran</th>
                        <th>Kelas</th>
                        <th>Hari</th>
                        <th>Jam Mulai</th>
                        <th>Jam Selesai</th>
                        <th>Action</th>
                      </tr>";
      $tampil = mysqli_query($conn, "SELECT * FROM rb_jadwal_pelajaran a 
                                            JOIN rb_mata_pelajaran b ON a.kodepelajaran=b.kodepelajaran
                                              JOIN rb_guru c ON a.nip=c.nip 
                                                JOIN rb_kelas d ON a.kodekelas=d.kodekelas where a.kodekelas='$iden[kodekelas]' AND a.semester='2' ORDER BY a.hari DESC");
      $no = 1;
      while ($r = mysqli_fetch_array($tampil)) {

        echo "<tr><td>$no</td>
                              <td>$r[kodepelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[kelas]</td>
                              <td>$r[hari]</td>
                              <td>$r[jam_mulai] WIB</td>
                              <td>$r[jam_selesai] WIB</td>
                              <td style='width:70px !important'><center>
                                <a class='btn btn-success btn-xs' title='Tampil List Absensi' href='?view=absengurus&act=tampilabsen&id=$r[kodekelas]&kd=$r[kodepelajaran]'><span class='glyphicon glyphicon-th'></span> Tampilkan Absensi</a>
                              </center></td>";
        echo "</tr>";
        $no++;
      }

      echo "</table>
                    </div>
                     </div>";
    }

  ?>