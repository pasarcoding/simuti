<?php
include '../../config/koneksi.php';
include '../../config/fungsi_indotgl.php';
include 'config/rupiah.php';
include "../../config/library.php";
date_default_timezone_set('Asia/Jakarta');
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
// $headers = array();
// $headers[] = $token_send;
// $headers[] = 'Content-Type: application/x-www-form-urlencoded';

if ($_GET['aksi'] == '') {
  $query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi");
  $row_saldo = mysqli_fetch_array($query_saldo);
  $saldo_keseluruhan = $row_saldo['jumlah_debit'] - $row_saldo['jumlah_kredit'];
?>

  <div class="clearfix"></div>
  </div>
  <div class="col-md-9 col-sm-12 col-xs-12">
    <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#modalAdd "><i class="glyphicon glyphicon-save-file"></i> Setoran Tunai</a>
    <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#tarikAdd"><i class="glyphicon glyphicon-open-file"></i> Penarikan Tunai</a>
  </div>
  <div class="col-md-3 col-sm-12 col-xs-12" style="margin-left: 0px;">
    <h4><small>Saldo : </small>Rp. <?php echo rupiah($saldo_keseluruhan); ?></h4>
  </div>
  <div class="form-group">
    <div class="col-md-12">
      <div class="box box-solid box-primary">
        <div class="box-header">
          <h3 class="box-title"> Data Riwayat Transaksi </h3>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table table-responsive no-padding table-striped">
              <thead>
                <tr>
                  <th width="20">Tipe</th>
                  <th>Tanggal</th>
                  <th>No Transaksi</th>
                  <th>Siswa</th>
                  <th>Debit</th>
                  <th>Kredit</th>
                  <th width="110">Saldo</th>
                </tr>
              </thead>
              <tbody>
                <?php
                $no = 0;
                $query = mysqli_query($conn, "SELECT * FROM transaksi JOIN siswa ON transaksi.nisnSiswa=siswa.nisnSiswa order by id_transaksi asc ");
                $count = 2;
                while ($row = mysqli_fetch_array($query)) {
                  $no++;
                ?>
                  <tr style="background: <?php if ($row['kredit'] == 0) { ?>
                          #defff1;
                          <?php } else { ?>
                            #feeeea;
                            <?php } ?>">
                    <td><?php if ($row['kredit'] == 0) { ?><a class="btn btn-success btn-xs"><i class="glyphicon glyphicon-save-file"></i></a> <?php } else { ?> <a class="btn btn-danger btn-xs"><i class="glyphicon glyphicon-open-file"></i></a><?php } ?></td>
                    <td><?php echo $row['tanggal']; ?></td>
                    <td><?php echo $row['id_transaksi']; ?></td>
                    <td><?php echo $row['nmSiswa']; ?></td>
                    <?php if ($count == 1) { ?>
                      <td><?php echo "Rp." . rupiah($row['debit']); ?></td>
                      <td><?php echo "Rp." . rupiah($row['kredit']); ?></td>
                      <td>
                        <?php
                        $debit = $row['debit'];
                        $saldo = $row['debit'];
                        echo "Rp." . rupiah($saldo);
                        ?>
                      </td>
                      <?php } else {
                      if ($row['debit'] != 0) {
                      ?>
                        <td><?php echo "Rp." . rupiah($row['debit']); ?></td>
                        <td><?php echo "Rp." . rupiah($row['kredit']); ?></td>
                        <td>
                          <?php
                          $debit = $denit + $row['debit'];
                          $saldo = $saldo + $row['debit'];
                          echo "Rp." . rupiah($saldo);
                          ?>
                        <?php } else { ?>
                        <td><?php echo "Rp." . rupiah($row['debit']); ?></td>
                        <td><?php echo "Rp." . rupiah($row['kredit']); ?></td>
                        <td>
                      <?php
                        $kredit = $kredit + $row['kredit'];
                        $saldo = $saldo - $row['kredit'];
                        echo "Rp." . rupiah($saldo);
                      }
                    }
                    $count++
                      ?>
                  </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <!-- /page content --><br>
      <?php } elseif ($_GET['aksi'] == 'setoran_tunai') {

      if (isset($_POST['tunai'])) {

        if (empty($_POST['kredit'])) {
          mysqli_query($conn, "INSERT INTO transaksi(id_transaksi,
                                  nisnSiswa,
                                  tanggal,
                                  debit,
                                  kredit,
                                  keterangan) VALUES('$_POST[id]',
                                                 '$_POST[nisnSiswa]',
                                                 '$_POST[tanggal]',
                                                 '$_POST[debit]',
                                                 '0',
                                                 '$_POST[keterangan]')");
          $bb = mysqli_query($conn, "SELECT transaksi.debit as Debit, nmSiswa as nama, tanggal as tgl, noHpOrtu as hpo, siswa.saldo as Saldo, noHpSis as hps FROM siswa 
INNER JOIN transaksi ON siswa.nisnSiswa = transaksi.nisnSiswa  
WHERE id_transaksi='$_POST[id]'
");
          $reb = mysqli_fetch_array($bb);
          $bbs = mysqli_query($conn, "SELECT * FROM siswa where nisnSiswa='$_POST[nisnSiswa]'");
          $rebs = mysqli_fetch_array($bbs);
          $tagihan = $reb['Debit'];
          $siswab = $reb['nama'];
          $tgl = tgl_indo($reb['tgl']);
          $hpo = $reb['hpo'];
          $hps = $reb['hps'];
          $saldo = $rebs['saldo'] + $tagihan;

          $msg_wa = array();
          $number_wa = array();

          //pesan whatsapp ortu 
          $number_wa[] = $hpo;
          $msg_wa[] =  'Assalamualaikum, Terima Kasih setoran tabungan sebesar *' . str_replace(".", ",", buatRp($tagihan)) . '* 
        
Pada : ' . $tgl . ' 
Nama : *' . $siswab . '*. 
        
Saldo saat ini *' . str_replace(".", ",", buatRp($saldo)) . '* Terima kasih';

          //pesan whatsapp siswa
          $number_wa[] = $hps;
          $msg_wa[] =  'Assalamualaikum, Terima Kasih setoran tabungan sebesar *' . str_replace(".", ",", buatRp($tagihan)) . '* 
        
Pada  : ' . $tgl . ' 
Nama  : *' . $siswab . '*. 
        
Saldo saat ini *' . str_replace(".", ",", buatRp($saldo)) . '* Terima kasih';

          for ($i = 0; $i < count($number_wa); $i++) {
            $data = [
              'api_key' => $token_send,
              'sender' => $number_send,
              'number' => $number_wa[$i],
              'message' => $msg_wa[$i]
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $link_send,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => json_encode($data),
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
          }

          $query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE nisnSiswa ='$_POST[nisnSiswa]'   ");
          $saldo = mysqli_fetch_array($query_saldo);
          $saldoo = $saldo['jumlah_debit'] - $saldo['jumlah_kredit'];
          mysqli_query($conn, "UPDATE siswa SET saldo = '$saldoo'
                                    WHERE nisnSiswa = '$_POST[nisnSiswa]'  ");
        } else {
          mysqli_query($conn, "INSERT INTO transaksi(id_transaksi,
                                  nisnSiswa,
                                  tanggal,
                                  debit,
                                  kredit,
                                  keterangan) VALUES('$_POST[id]',
                                                 '$_POST[nisnSiswa]',
                                                 '$_POST[tanggal]',
                                                 '0',
                                                 '$_POST[kredit]',
                                                 '$_POST[keterangan]')");

          $query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE nisnSiswa ='$_POST[nisnSiswa]'  ");
          $saldo = mysqli_fetch_array($query_saldo);
          $saldoo = $saldo['jumlah_debit'] - $saldo['jumlah_kredit'];

          mysqli_query($conn, "UPDATE siswa SET saldo = '$saldoo'
                                    WHERE nisnSiswa = '$_POST[nisnSiswa]'  ");
        }


        echo "<script language='javascript'>document.location='?view=transaksi';</script>";
      } else {

        $id = $_POST['idSiswa'];
        $query = mysqli_query($conn, "SELECT * FROM siswa WHERE idSiswa='$id'");
        $r = mysqli_fetch_array($query);
        $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM siswa WHERE idSiswa='$_POST[idSiswa]'"));
        if ($cek == 0) {
          echo "<script>window.alert('Nomor Nisn Tidak ada !')
    window.location='?view=transaksi'</script>";
        } else {
      ?>

          <div class="col-xs-12">
            <div class="box box-info box-solid">
              <div class="box-header with-border">
                <h3 class="box-title"> </h3>
                <h2>Setoran Tunai</h2>

                <div class="form-group"></div>
              </div>
              <div class="x_content">
                <form action="?view=transaksi&aksi=setoran_tunai" enctype="multipart/form-data" method="POST">
                  <div class="form-group">
                    <div class="col-md-6">
                      <?php
                      $query = mysqli_query($conn, "SELECT max(id_transaksi) as maxID FROM transaksi ");
                      $data = @mysqli_fetch_array($query);
                      $idMax = $data['maxID'];

                      $noUrut = (int) substr($idMax, 1, 9);
                      $noUrut++;
                      $char = "T";
                      $newID = $char . sprintf("%04s", $noUrut);
                      ?>
                      <label for="id">ID Transaksi :</label>
                      <input type="text" class="form-control" disabled value="<?php echo $newID; ?>" />
                      <input type="hidden" class="form-control" name="id" value="<?php echo $newID; ?>" />


                      <label for="nama">Nomor Rekening :</label>
                      <input type="hidden" class="form-control" name="nisnSiswa" value="<?php echo $r['nisnSiswa']; ?>" />
                      <input type="text" disabled class="form-control" value="<?php echo $r['nisnSiswa']; ?>" />

                      <label for="alamat">Nama :</label>
                      <input class="form-control" disabled value="<?php echo $r['nmSiswa']; ?>">

                      <label for="alamat">Agama :</label>
                      <input class="form-control" disabled value="<?php echo $r['agamaSiswa']; ?>">

                      <label for="username">Alamat :</label>
                      <input type="text" disabled class="form-control" value="<?php echo $r['alamatOrtu']; ?>" disabled />

                      <label for="password">Orang Tua :</label>
                      <input type="text" class="form-control" value="<?php echo $r['nmOrtu']; ?>" disabled />

                    </div>
                    <div class="col-md-6">

                      <label for="password">Total Saldo :</label>
                      <?php
                      $query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE nisnSiswa ='" . $r['nisnSiswa'] . "'");
                      $saldo = mysqli_fetch_array($query_saldo);
                      $saldoo = $saldo['jumlah_debit'] - $saldo['jumlah_kredit'];
                      ?>
                      <h3>Rp. <?php echo rupiah($saldoo); ?></h3>

                      <label for="password">Saldo Bulan ini :</label>
                      <?php
                      $bulan = date('m');
                      $query_bulan = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE DATE_FORMAT((tanggal),'%m') like '%$bulan%' AND nisnSiswa ='" . $r['nisnSiswa'] . "'");
                      $saldo_bulan = mysqli_fetch_array($query_bulan);
                      $saldo_b = $saldo_bulan['jumlah_debit'] - $saldo_bulan['jumlah_kredit'];
                      ?>
                      <h3>Rp. <?php echo rupiah($saldo_b); ?></h3>

                      <label for="password">Jumlah Setoran :</label>
                      <input type="hidden" class="form-control" name="kredit" />
                      <input type="text" class="form-control" name="debit" autofocus=”autofocus” autocomplete="off" />

                      <label for="alamat">Keterangan :</label>
                      <textarea class="form-control" name="keterangan"></textarea>

                      <label for="tanggal">Tanggal Transaksi :</label>
                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="tanggal" class="form-control pull-right date-picker" value="<?php echo date('Y-m-d'); ?>">
                      </div>
                      <br>
                    </div>


                    <div class="col-md-6"></div>
                    <div class="form-group">
                      <button type="button" class="btn btn-default btn-sm" onclick=self.history.back()>Batal</button>
                      <button type="submit" name="tunai" class="btn btn-success btn-sm">Simpan</button>

                    </div>
                </form>
              </div>
            </div>
          </div>
          <!-- /page content -->
        <?php }
      }
    } elseif ($_GET['aksi'] == 'penarikan_tunai') {

      if (isset($_POST['tarik'])) {

        if (empty($_POST['kredit'])) {
          mysqli_query($conn, "INSERT INTO transaksi(id_transaksi,
                                  nisnSiswa,
                                  tanggal,
                                  debit,
                                  kredit,
                                  keterangan
								 ) VALUES('$_POST[id]',
                                                 '$_POST[nisnSiswa]',
                                                 '$_POST[tanggal]',
                                                 '0',
                                                 '$_POST[kredit]',
                                                 '$_POST[keterangan]'
												 )");


          $query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE nisnSiswa ='$_POST[nisnSiswa]' ");
          $saldo = mysqli_fetch_array($query_saldo);
          $saldoo = $saldo['jumlah_debit'] - $saldo['jumlah_kredit'];
          mysqli_query($conn, "UPDATE siswa SET saldo = '$saldoo'
                                    WHERE nisnSiswa = '$_POST[nisnSiswa]'");
          $bb = mysqli_query($conn, "SELECT  transaksi.kredit as Kredit, nmSiswa as nama, tanggal as tgl, noHpOrtu as hpo, siswa.saldo as Saldo, noHpSis as hps FROM siswa 
 INNER JOIN transaksi ON siswa.nisnSiswa = transaksi.nisnSiswa  
 WHERE id_transaksi='$_POST[id]'
 ");
          $reb = mysqli_fetch_array($bb);
          $bbs = mysqli_query($conn, "SELECT * FROM siswa where nisnSiswa='$_POST[nisnSiswa]'");
          $rebs = mysqli_fetch_array($bbs);
          $tagihan = $reb['Kredit'];
          $siswab = $reb['nama'];
          $tgl = tgl_indo($reb['tgl']);
          $hpo = $reb['hpo'];
          $hps = $reb['hps'];
          $saldo = $rebs['Saldo'];

          $msg_wa = array();
          $number_wa = array();

          //pesan whatsapp ortu 
          $number_wa[] = $hpo;
          $msg_wa[] =  'Assalamualaikum, Terima Kasih penarikan tabungan sebesar *' . str_replace(".", ",", buatRp($tagihan)) . '* 
         
 Pada : ' . $tgl . ' 
 Nama : *' . $siswab . '*. 
         
 Saldo saat ini *' . str_replace(".", ",", buatRp($saldo)) . '* Terima kasih';

          //pesan whatsapp siswa
          $number_wa[] = $hps;
          $msg_wa[] =  'Assalamualaikum, Terima Kasih penarikan tabungan sebesar *' . str_replace(".", ",", buatRp($tagihan)) . '* 
         
 Pada  : ' . $tgl . ' 
 Nama  : *' . $siswab . '*. 
         
 Saldo saat ini *' . str_replace(".", ",", buatRp($saldo)) . '* Terima kasih';

          for ($i = 0; $i < count($number_wa); $i++) {
            $data = [
              'api_key' => $token_send,
              'sender' => $number_send,
              'number' => $number_wa[$i],
              'message' => $msg_wa[$i]
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $link_send,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => json_encode($data),
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
          }
        } else {
          mysqli_query($conn, "INSERT INTO transaksi(id_transaksi,
                                  nisnSiswa,
                                  tanggal,
                                  debit,
                                  kredit,
                                  keterangan) VALUES('$_POST[id]',
                                                 '$_POST[nisnSiswa]',
                                                 '$_POST[tanggal]',
                                                 '0',
                                                 '$_POST[kredit]',
                                                 '$_POST[keterangan]')");

          $query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE nisnSiswa ='$_POST[nisnSiswa]' ");
          $saldo = mysqli_fetch_array($query_saldo);
          $saldoo = $saldo['jumlah_debit'] - $saldo['jumlah_kredit'];

          mysqli_query($conn, "UPDATE siswa SET saldo = '$saldoo'
                                    WHERE nisnSiswa = '$_POST[nisnSiswa]'");
          $bb = mysqli_query($conn, "SELECT  transaksi.kredit as Kredit, nmSiswa as nama, tanggal as tgl, noHpOrtu as hpo, siswa.saldo as Saldo, noHpSis as hps FROM siswa 
                                    INNER JOIN transaksi ON siswa.nisnSiswa = transaksi.nisnSiswa  
                                    WHERE id_transaksi='$_POST[id]'
                                    ");
          $reb = mysqli_fetch_array($bb);
          $bbs = mysqli_query($conn, "SELECT * FROM siswa where nisnSiswa='$_POST[nisnSiswa]'");
          $rebs = mysqli_fetch_array($bbs);
          $tagihan = $reb['Kredit'];
          $siswab = $reb['nama'];
          $tgl = tgl_indo($reb['tgl']);
          $hpo = $reb['hpo'];
          $hps = $reb['hps'];
          $saldo = $rebs['saldo'];

          $msg_wa = array();
          $number_wa = array();

          //pesan whatsapp ortu 
          $number_wa[] = $hpo;
          $msg_wa[] =  'Assalamualaikum, Terima Kasih penarikan tabungan sebesar *' . str_replace(".", ",", buatRp($tagihan)) . '* 
                                            
Pada : ' . $tgl . ' 
Nama : *' . $siswab . '*. 
                                            
Saldo saat ini *' . str_replace(".", ",", buatRp($saldo)) . '* Terima kasih';

          //pesan whatsapp siswa
          $number_wa[] = $hps;
          $msg_wa[] =  'Assalamualaikum, Terima Kasih penarikan tabungan sebesar *' . str_replace(".", ",", buatRp($tagihan)) . '* 
                                            
Pada  : ' . $tgl . ' 
Nama  : *' . $siswab . '*. 
                                            
Saldo saat ini *' . str_replace(".", ",", buatRp($saldo)) . '* Terima kasih';

          for ($i = 0; $i < count($number_wa); $i++) {
            $data = [
              'api_key' => $token_send,
              'sender' => $number_send,
              'number' => $number_wa[$i],
              'message' => $msg_wa[$i]
            ];
            $curl = curl_init();
            curl_setopt_array($curl, array(
              CURLOPT_URL => $link_send,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS => json_encode($data),
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));
            $response = curl_exec($curl);
            curl_close($curl);
          }
        }


        echo "<script language='javascript'>document.location='?view=transaksi';</script>";
      } else {


        $id = $_POST['idSiswa'];
        $query = mysqli_query($conn, "SELECT * FROM siswa WHERE idSiswa='$id'");
        $r = mysqli_fetch_array($query);

        $cek = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM siswa WHERE idSiswa='$_POST[idSiswa]'"));
        if ($cek == 0) {
          echo "<script>window.alert('Nomor Rekening Tidak ada !')
    window.location='?view=transaksi'</script>";
        } else {

        ?>

          <!-- page content -->

          <div class="col-xs-12">
            <div class="box box-info box-solid">
              <div class="box-header with-border">
                <h3 class="box-title"> </h3>
                <h2>Penarikan Tunai</h2>

                <div class="form-group"></div>
              </div>
              <div class="x_content">
                <form action="?view=transaksi&aksi=penarikan_tunai" enctype="multipart/form-data" method="POST">
                  <div class="form-group">
                    <div class="col-md-6">
                      <?php
                      $query = mysqli_query($conn, "SELECT max(id_transaksi) as maxID FROM transaksi ");
                      $data = @mysqli_fetch_array($query);
                      $idMax = $data['maxID'];

                      $noUrut = (int) substr($idMax, 1, 9);
                      $noUrut++;
                      $char = "T";
                      $newID = $char . sprintf("%04s", $noUrut);
                      ?>
                      <label for="id">ID Transaksi :</label>
                      <input type="text" class="form-control" disabled value="<?php echo $newID; ?>" />
                      <input type="hidden" class="form-control" name="id" value="<?php echo $newID; ?>" />


                      <label for="nama">Nomor Rekening :</label>
                      <input type="hidden" class="form-control" name="nisnSiswa" value="<?php echo $r['nisnSiswa']; ?>" />

                      <input type="text" disabled class="form-control" value="<?php echo $r['nisnSiswa']; ?>" />

                      <label for="alamat">Nama :</label>
                      <input class="form-control" disabled value="<?php echo $r['nmSiswa']; ?>">

                      <label for="alamat">Agama :</label>
                      <input class="form-control" disabled value="<?php echo $r['agamaSiswa']; ?>">


                      <label for="username">Alamat :</label>
                      <input type="text" disabled class="form-control" value="<?php echo $r['alamatOrtu']; ?>" disabled />

                      <label for="password">Orang Tua :</label>
                      <input type="text" class="form-control" value="<?php echo $r['nmOrtu']; ?>" disabled />

                    </div>
                    <div class="col-md-6">

                      <label for="password">Saldo :</label>
                      <?php
                      $query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE nisnSiswa ='" . $r['nisnSiswa'] . "'");
                      $saldo = mysqli_fetch_array($query_saldo);
                      $saldoo = $saldo['jumlah_debit'] - $saldo['jumlah_kredit'];
                      ?>
                      <h3>Rp. <?php echo rupiah($saldoo); ?></h3>

                      <label for="password">Saldo Bulan ini :</label>
                      <?php
                      $bulan = date('m');
                      $query_bulan = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE DATE_FORMAT((tanggal),'%m') like '%$bulan%' AND nisnSiswa ='" . $r['nisnSiswa'] . "'");
                      $saldo_bulan = mysqli_fetch_array($query_bulan);
                      $saldo_b = $saldo_bulan['jumlah_debit'] - $saldo_bulan['jumlah_kredit'];
                      ?>
                      <h3>Rp. <?php echo rupiah($saldo_b); ?></h3>

                      <label for="password">Jumlah Penarikan :</label>

                      <input type="hidden" class="form-control" name="debit" />
                      <input type="text" class="form-control" name="kredit" autofocus=”autofocus” autocomplete="off" />

                      <label for="alamat">Keterangan :</label>
                      <textarea class="form-control" name="keterangan"></textarea>

                      <label for="tanggal">Tanggal Transaksi :</label>

                      <div class="input-group date">
                        <div class="input-group-addon">
                          <i class="fa fa-calendar"></i>
                        </div>
                        <input type="text" name="tanggal" class="form-control pull-right date-picker" value="<?php echo date('Y-m-d'); ?>">
                      </div>
                      <br>
                    </div>





                    <div class="col-md-6"></div>
                    <div class="form-group">
                      <button type="button" class="btn btn-default btn-sm" onclick=self.history.back()>Batal</button>
                      <button type="submit" name="tarik" class="btn btn-success btn-sm">Simpan</button>

                    </div>

                </form>
              </div>
            </div>
          </div>

    
  <!-- /page content -->


<?php }
      }
    } ?>

<!-- Modal -->
<div class="modal fade" id="modalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Setoran Tunai</h4>
      </div>
      <div class="modal-body">

        <form action="?view=transaksi&aksi=setoran_tunai" class="form-horizontal form-label-left" method="POST">

          <div class="form-group">
            <div class="col-sm-12 col-sm-12 col-xs-12 ">
              <label for="" class="col-sm-2 control-label">NIS/NISN/Nama</label>
              <div class="col-sm-12">
                <select name="idSiswa" data-live-search="true" class="form-control selectpicker">
                  <option value="">- Cari Siswa -</option>
                  <?php
                  $sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa");
                  while ($s = mysqli_fetch_array($sqlSiswa)) {
                    echo "<option value='$s[idSiswa]'>$s[nisSiswa] - $s[nmSiswa]</option>";
                  }
                  ?>
                </select>
                <br><br>
                <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Cari</button>
                </span>

              </div>

            </div>
          </div>
        </form>

      </div>

      <!-- end form for validations -->
    </div>
  </div>
</div>
<!-- /modal -->


<!-- Modal -->
<div class="modal fade" id="tarikAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Penarikan Tunai</h4>
      </div>
      <div class="modal-body">

        <form action="?view=transaksi&aksi=penarikan_tunai" class="form-horizontal form-label-left" method="POST">

          <div class="form-group">
            <div class="col-sm-12 col-sm-12 col-xs-12 ">
              <label for="" class="col-sm-2 control-label">NIS/NISN/Nama</label>
              <div class="col-sm-12">
                <select name="idSiswa" data-live-search="true" class="form-control selectpicker">
                  <option value="">- Cari Siswa -</option>
                  <?php
                  $sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa");
                  while ($s = mysqli_fetch_array($sqlSiswa)) {
                    echo "<option value='$s[idSiswa]'>$s[nisSiswa] - $s[nmSiswa]</option>";
                  }
                  ?>
                </select>
                <br><br>
                <button type="submit" class="btn btn-danger"><i class="fa fa-search"></i> Cari</button>
                </span>

              </div>

            </div>
          </div>
        </form>

      </div>

      <!-- end form for validations -->
    </div>
  </div>
</div>
<!-- /modal -->