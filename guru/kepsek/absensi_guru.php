<?php
if ($_GET[act] == '') {
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
?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <div style="clear:both"></div>
        <div class="col-xs-12">
          <div class="row">
            <div class="col-xs-3">
              <h3 class="box-title">Data Absensi Guru Pada : <b style='color:red'><?php echo tgl_indo("$filtertgl") . "</b>"; ?> </h3>
            </div>
            <div class="col-xs-9 ">
              <form action="" method="POST" class="form-inline pull-right">
                <label for="tgl">Tanggal:</label>
                <select name="tgl" class="form-control">
                  <?php
                  for ($i = 1; $i <= 31; $i++) {
                    $selected = ($i == $tgle) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                  }
                  ?>
                </select>

                <label for="bln">Bulan:</label>
                <select name="bln" class="form-control">
                  <?php
                  for ($i = 1; $i <= 12; $i++) {
                    $selected = ($i == $blnee) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                  }
                  ?>
                </select>

                <label for="thn">Tahun:</label>
                <select name="thn" class="form-control">
                  <?php
                  $currentYear = date('Y');
                  for ($i = $currentYear - 5; $i <= $currentYear + 5; $i++) {
                    $selected = ($i == $tahun) ? 'selected' : '';
                    echo "<option value='$i' $selected>$i</option>";
                  }
                  ?>
                </select>

                <button type="submit" name="lihat" class="btn btn-primary">Lihat</button>
              </form>
            </div>
          </div>

        </div>

      </div><!-- /.box-header -->
      <form action='' method='POST'>
        <div class="box-body">
          <table id="example" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style='width:20px'>No</th>
                <th>Guru</th>
                <th>Foto</th>
                <th width='90px'>Kehadiran</th>
                 <th width='140px'>Pulang</th>

              </tr>
            </thead>
            <tbody>
              <?php
              $tampil = mysqli_query($conn, "SELECT * FROM rb_guru ORDER BY nama_guru ASC");

              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
                if (isset($_GET['gettanggal'])) {
                  $sekarangabsen = $_GET['gettanggal'];
                } else {
                  if (isset($_POST['lihat'])) {
                    $sekarangabsen = $thn . "-" . $blnc . "-" . $tglc;
                  } else {
                    $sekarangabsen = date("Y-m-d");
                  }
                }

                $a = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='$r[id]' AND tanggal='$sekarangabsen' "));
                $b = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='$r[id]' AND tanggal='$sekarangabsen' AND kode_kehadiran IN ('P','PC','DL')"));

                if (!file_exists("images/$a[nama]") or $a['nama'] == '') {
                  $foto_user = "blank.png";
                } else {
                  $foto_user = $a['nama'];
                }
                echo "<tr><td>$no</td>
                              
                              <td>$r[nama_guru]</td>
                              <td><img src=" . 'images/' . $foto_user . " id='target'  width='100' height='100' class='img-thumbnail img-responsive'></td>
                             
                              <input type='hidden' value='$r[id]' name='nip[$no]'>
                             
                              <td><select style='width:140px;' name='a[$no]' class='form-control'>";
                $kehadiran = mysqli_query($conn, "SELECT * FROM rb_kehadiran where kode_kehadiran!='P' and kode_kehadiran!='PC'");
                while ($k = mysqli_fetch_array($kehadiran)) {
                  if ($a['kode_kehadiran'] == $k['kode_kehadiran']) {
                    echo "<option value='$k[kode_kehadiran]' selected>* $k[nama_kehadiran]</option>";
                  } else {
                    echo "<option value='$k[kode_kehadiran]'>$k[nama_kehadiran]</option>";
                  }
                }
                echo "</select></td>
                                     
                      <td><select style='width:140px;' name='b[$no]' class='form-control'>";
                    echo "<option value='0' > -- Pilih --</option>";

                    $kehadiran = mysqli_query($conn, "SELECT * FROM rb_kehadiran WHERE kode_kehadiran IN ('P','PC','DL')");
                while ($k = mysqli_fetch_array($kehadiran)) {
                  if ($b['kode_kehadiran'] == $k['kode_kehadiran']) {
                    echo "<option value='$k[kode_kehadiran]' selected>* $k[nama_kehadiran]</option>";
                  } else {
                    echo "<option value='$k[kode_kehadiran]'>$k[nama_kehadiran]</option>";
                  }
                }
                echo "</select></td>        
                              </tr>";
                $no++;
              }
              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->

        

      </form>
    </div><!-- /.box -->
  </div>
<?php
  if (isset($_POST['simpan'])) {
    $jml_data = count($_POST['nip']);
    $nip = $_POST['nip'];
    $a = $_POST['a'];

    $tglabsen = $_POST['filtertgl'];
    for ($i = 1; $i <= $jml_data; $i++) {
      $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='" . $nip[$i] . "' AND tanggal='$tglabsen' ");
      $total = mysqli_num_rows($cek);
      if ($total >= 1) {
        mysqli_query($conn, "UPDATE rb_absensi_guru SET kode_kehadiran = '" . $a[$i] . "' where nip='" . $nip[$i] . "' AND tanggal='$tglabsen'");
        $lst_siswa = mysqli_query($conn, "SELECT * FROM rb_guru where id='" . $nip[$i] . "'");
        while ($cs = mysqli_fetch_array($lst_siswa)) {
          $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where   nip='" . $nip[$i] . "' AND tanggal='$tglabsen' and kode_kehadiran!='H'");
          while ($cik = mysqli_fetch_array($cek)) {

            $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
            $idts = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users where level='kepsek'"));
            $link_send = $idt['link_one_sender'];
            $token_send = $idt['token'];
            $number_send = $idt['wa'];
            $id = $idt['idnya'];
            $status = $idt['statusWa'];
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
            } else {
              $statush = 'Hadir';
            }

            echo "<script>document.location='?view=absensi_guru';</script>";
          }
        }
      } else {
        mysqli_query($conn, "INSERT INTO rb_absensi_guru VALUES ('','" . $nip[$i] . "',
                                                              '" . $a[$i] . "',
                                                              '$tglabsen',
                                                              '" . date('Y-m-d H:i:s') . "','','','','','','','')");
        $lst_siswa = mysqli_query($conn, "SELECT * FROM rb_guru where nip='" . $nip[$i] . "'");
        while ($cs = mysqli_fetch_array($lst_siswa)) {
          $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='" . $nip[$i] . "' AND tanggal='$tglabsen' and kode_kehadiran!='H'");
          while ($cik = mysqli_fetch_array($cek)) {

            $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
            $idts = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users where level='kepsek'"));

            //whatsapp api
            $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
            $link_send = $idt['link_one_sender'];
            $token_send = $idt['token'];
            $number_send = $idt['wa'];
            $id = $idt['idnya'];
            $status = $idt['statusWa'];

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
            } else {
              $statush = 'Hadir';
            }

            echo "<script>document.location='?view=absensi_guru';</script>";
          }
        }
      }
    }
  }
  
   if (isset($_POST['simpan_pulang'])) {
    $jml_data = count($_POST['nip']);
    $nip = $_POST['nip'];
    $a = $_POST['b'];

    $tglabsen = $_POST['filtertgl'];
    for ($i = 1; $i <= $jml_data; $i++) {
         $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='" . $nip[$i] . "' AND tanggal='$tglabsen' and kode_kehadiran IN ('P','PC','DL') ");
      $total = mysqli_num_rows($cek);
      if ($a[$i] == '0') {
       
            echo "<script>document.location='?view=absensi_guru';</script>";
      }else if ($total >= 1) { 
                  echo "<script>document.location='?view=absensi_guru';</script>";

      } else {
        mysqli_query($conn, "INSERT INTO rb_absensi_guru VALUES ('','" . $nip[$i] . "',
                                                              '" . $a[$i] . "',
                                                              '$tglabsen',
                                                              '" . date('Y-m-d H:i:s') . "','','','','','','','')");
        $lst_siswa = mysqli_query($conn, "SELECT * FROM rb_guru where nip='" . $nip[$i] . "'");
        while ($cs = mysqli_fetch_array($lst_siswa)) {
          $cek = mysqli_query($conn, "SELECT * FROM rb_absensi_guru where nip='" . $nip[$i] . "' AND tanggal='$tglabsen' and kode_kehadiran!='H'");
          while ($cik = mysqli_fetch_array($cek)) {

            $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
            $idts = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users where level='kepsek'"));

            //whatsapp api
            $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
            $link_send = $idt['link_one_sender'];
            $token_send = $idt['token'];
            $number_send = $idt['wa'];
            $id = $idt['idnya'];
            $status = $idt['statusWa'];

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
            } else {
              $statush = 'Hadir';
            }

            echo "<script>document.location='?view=absensi_guru';</script>";
          }
        }
      }
    }
  }
}
?>