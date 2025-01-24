<?php
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['status'];

if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data Payment </h3>

      </div><!-- /.box-header -->
      <div class="box-body">
        <?php
        if (isset($_GET['sukses'])) {
          echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
                          </div>";
        } elseif (isset($_GET['gagal'])) {
          echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..
                          </div>";
        } elseif (isset($_GET['sukseshapus'])) {
          echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Berhasil!</strong> - Data Berhasil dihapus.....
                          </div>";
        } elseif (isset($_GET['gagalhapus'])) {
          echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data ini telah digunakan oleh data lain, sehingga tidak bida dihapus!!
                          </div>";
        }
        ?>
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Siswa</th>
              <th>Kelas Siswa</th>
              <th>Jenis Pembayaran</th>
              <th>Payment Order</th>
              <th>Waktu</th>
              <th>Total Pembayaran</th>
              <th>Rincian</th>
              <th>Bukti Payment</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($conn, "SELECT  SUM(payment_manual.nominal) as Total,payment_manual.tglPayment,payment_manual.foto,
            payment_manual.paymentOrder,payment_manual.status,payment_manual.idSiswa,payment_manual.idPayment,payment_manual.jenisBayar,payment_manual.idBank,siswa.nmSiswa,kelas_siswa.nmKelas
            FROM payment_manual 
            LEFT JOIN siswa ON payment_manual.idSiswa=siswa.idSiswa
            LEFT JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas
            where  payment_manual.status='2'  group by payment_manual.paymentOrder");
            $gg = mysqli_fetch_array($qPayment);
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              if ($r['status'] == '2') {
                $a = 'success';
                $icon = "fa-check";
                $btn = "btn-info";
                $alt = "Proses";
                $onoff = "<a class='btn $btn btn-xs' title='$alt' onclick=\"return confirm('Apa anda yakin untuk memproses Data ini? ')\" href='?view=payment&act=onoff&id=$r[paymentOrder]&siswa=$r[idSiswa]&a=$a'><span class='fa $icon'></span> Proses</a>";
                $tolak = "<a class='btn btn-danger btn-xs' title='$alt' onclick=\"return confirm('Apa anda yakin untuk menolak Data ini? ')\" href='?view=payment&act=tolak&id=$r[paymentOrder]&siswa=$r[idSiswa]&a=$a'><span class='fa fa-close'></span> Tolak</a>";
              } else if ($r['status'] == '1') {
                $icon = "fa-check";
                $btn = "btn-success";
                $onoff = "<a class='btn $btn btn-xs' href='#'><span class='fa $icon'></span> Selesai </a> ";
                $tolak = "";
              } else if ($r['status'] == '0') {
                $icon = "fa-check";
                $btn = "btn-danger";
                $onoff = "<a class='btn $btn btn-xs' href='#'><span class='fa $icon'></span> Belum Transfer </a> ";
                $tolak = "";
              } else {
                $icon = "fa-close";
                $btn = "btn-danger";
                $onoff = "<a class='btn $btn btn-xs' href='#'><span class='fa $icon'></span> Ditolak </a> ";
                $tolak = "";
              }
              echo "<tr><td>$no</td>
                          <td>$r[nmSiswa]</td>
                          <td>$r[nmKelas]</td>
                          <td>$r[jenisBayar]</td>
                              <td>$r[paymentOrder]</td>
                              <td>$r[tglPayment]</td>
                              <td>" . buatRp($r['Total']) . "</td>"; ?>
              <td>
                <table width="100%" cellpadding="2">
                  <tbody>
                    <?php
                    $riwayatPaymentDetail = mysqli_query($conn, "SELECT * FROM payment_manual WHERE paymentOrder='$r[paymentOrder]'");
                    while ($pymt = mysqli_fetch_array($riwayatPaymentDetail)) {
                      if ($pymt['jenisBayar'] == 'Bulanan') {

                        $sqlbulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT tagihan_bulanan.*,
                                                                                  jenis_bayar.idPosBayar,
                                                                                  jenis_bayar.nmJenisBayar,
                                                                                  pos_bayar.nmPosBayar,
                                                                                  tahun_ajaran.nmTahunAjaran,
                                                                                  bulan.nmBulan, 
                                                                                  bulan.urutan
                                                                                  FROM tagihan_bulanan
                                                                                  LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
                                                                                  LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                                                                                  LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                                                                  LEFT JOIN bulan ON tagihan_bulanan.idBulan=bulan.idBulan
                                                                                  WHERE tagihan_bulanan.idTagihanBulanan='$pymt[idTagihan]'"));
                        $pisah_TA = explode('/', $sqlbulanan['nmTahunAjaran']);
                        if ($sqlbulanan['urutan'] <= 6) {
                          $tahun = $pisah_TA[0];
                        } else {
                          $tahun = $pisah_TA[1];
                        }
                        $namaTagihan = 'Pembayaran ' . $sqlbulanan['nmJenisBayar'] . " (" . $sqlbulanan['nmBulan'] . " " . $tahun . ") T.A " . $sqlbulanan['nmTahunAjaran'];
                      } else {

                        $sqlbebas = mysqli_fetch_array(mysqli_query($conn, "SELECT
                                                    tagihan_bebas.*,
                                                    jenis_bayar.idPosBayar,
                                                    jenis_bayar.nmJenisBayar,
                                                    pos_bayar.nmPosBayar,
                                                    tahun_ajaran.nmTahunAjaran
                                                  FROM
                                                    tagihan_bebas
                                                  INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                                                  INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                                                  INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                                  WHERE tagihan_bebas.idTagihanBebas='$pymt[idTagihan]'"));
                        $namaTagihan = 'Pembayaran ' . $sqlbebas['nmJenisBayar'] . " T.A " . $sqlbebas['nmTahunAjaran'];
                      }
                    ?>
                      <tr>
                        <td><?= $namaTagihan ?></td>
                        <td><?= buatRp($pymt['nominal']) ?></td>
                      </tr>
                    <?php
                    }
                    ?>
                    <tr>
                      <td>Biaya Admin</td>
                      <td><?= buatRp($idt['biaya_admin']) ?></td>
                    </tr>
                  </tbody>
                </table>

              </td>
              <td>
                <a href='#'>
                  <a href="#" onclick="window.open('foto_bukti/<?php echo $r['foto']; ?>','popuppage','width=600,toolbar=0,resizable=0,scrollbars=no,height=600,top=100,left=300');" data-toggle='tooltip' title="Lihat Bukti Upload" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Lihat</a>

                  <!-- <img src='foto_bukti/" . $r[foto] . "' id='target'  class='img-thumbnail img-responsive' width='150' height='300'> -->
                </a>
              </td>
            <?php echo "<td>$onoff $tolak</td>";
              echo "</tr>";
              $no++;
            }
            if (isset($_GET['hapus'])) {
              $query = mysqli_query($conn, "DELETE FROM pos_bayar where idPosBayar='$_GET[id]'");
              if ($query) {
                echo "<script>document.location='index.php?view=posbayar&sukseshapus';</script>";
              } else {
                echo "<script>document.location='index.php?view=posbayar&gagalhapus';</script>";
              }
            }

            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
<?php
} elseif ($_GET[act] == 'tolak') {
  $qPayment = mysqli_query($conn, "SELECT * FROM payment_manual WHERE paymentOrder='$_GET[id]' ");
  while ($pyt = mysqli_fetch_array($qPayment)) {
    $query = mysqli_query($conn, "UPDATE payment_manual SET status='3' where paymentOrder = '$_GET[id]'");
    $sqlSiswa = mysqli_query($conn, "SELECT * FROM siswa WHERE idSiswa='$pyt[idSiswa]'");
    $siswa = mysqli_fetch_array($sqlSiswa);

    if ($query) {
      echo "<script>document.location='index.php?view=payment';</script>";
    } else {
      echo "<script>document.location='index.php?view=payment';</script>";
    }
  }
} elseif ($_GET[act] == 'onoff') {

  $sqlPayment = mysqli_query($conn, "SELECT siswa.*,bank.*, SUM(nominal) as TotalBayar,payment_manual.paymentOrder FROM payment_manual 
  
  INNER JOIN siswa ON payment_manual.idSiswa=siswa.idSiswa
  INNER JOIN bank ON payment_manual.idBank=bank.id
  
  WHERE payment_manual.paymentOrder='$_GET[id]'");
  $pay = mysqli_fetch_array($sqlPayment);

  // $a = mysqli_query($conn, "SELECT * FROM siswa 
  // INNER JOIN tagihan_bulanan ON siswa.idSiswa = tagihan_bulanan.idSiswa 
  // INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan         
  // WHERE idTagihanBulanan='$pay[idTagihan]' ");
  // $re = mysqli_fetch_array($a);

  $tgl = date('Y-m-d');


  // $tagihan = $re['tagihan'];
  $nmSiswa = $pay['nmSiswa'];
  $idSiswa = $pay['idSiswa'];
  $hpo = $pay['noHpOrtu'];
  $hps = $pay['noHpSiswa'];
  $tagihan = $pay['TotalBayar'];
  $paymentOrder = $pay['paymentOrder'];
  $bank = $pay['nmBank'];
  $noRek = $pay['noRek'];
  $atasnama = $pay['atasNama'];
  $msg_wa = array();
  $number_wa = array();
  // $sis = $siswa['idSiswa'];
  $link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . $uri_segments[1] . '/kwitansi.php?tahun=' . $thn_ajar . '&tgl=' . $tgl . '&siswa=' . $idSiswa;

  $number_wa[] = $hpo;
  $number_wa[] = $hps;
  $msg_wa[] = 'Assalamualaikum, Terima Kasih atas pembayaran

Payment Order: *' . $paymentOrder . '*
Nama: *' . $nmSiswa . '*
Jumlah: *' . str_replace(".", ",", buatRp($tagihan)) . '* 
BankNomor Rekening: *' . $bank . '*: *' . $noRek . '*
Atas Nama : *' . $atasnama . '*

*Sukses*
Terima kasih. 

Download kwitansi : ' . $link_url_tagihan . '';
  $msg_wa[] = 'Assalamualaikum,Terima Kasih atas pembayaran

Payment Order: *' . $paymentOrder . '*
Nama: *' . $nmSiswa . '*
Jumlah: *' . str_replace(".", ",", buatRp($tagihan)) . '* 
Bank/Nomor Rekening: *' . $bank . '*/ *' . $noRek . '*
Atas Nama : *' . $atasnama . '*
  
*Sukses*
Terima kasih. 
  
Download kwitansi : ' . $link_url_tagihan . '';

  for ($i = 0; $i < count($number_wa); $i++) {
    send_wa($link_send, $token_send, $number_send, $number_wa[$i], $msg_wa[$i]);
  }

  $qPayment = mysqli_query($conn, "SELECT * FROM payment_manual WHERE idSiswa='$_GET[siswa]' AND paymentOrder='$_GET[id]'");
  while ($pyt = mysqli_fetch_array($qPayment)) {
    $query = mysqli_query($conn, "UPDATE payment_manual SET status='1' where paymentOrder = '$_GET[id]'");

    if ($pyt['jenisBayar'] == 'Bulanan') {

      $sqlTotalBayar = mysqli_query($conn, "SELECT sum(totalTagihan) as TotalTagihan from tagihan_bulanan WHERE idTagihanBulanan='$pyt[idTagihan]'");
      $totalDibayar = mysqli_fetch_array($sqlTotalBayar);
      if ($pyt['nominal'] < $totalDibayar['TotalTagihan']) {
        mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$pyt[tglPayment]',statusBayar='2',inv='$pyt[paymentOrder]',caraBayar='Transfer' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBulanan ='$pyt[idTagihan]'");
      } else {
        mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$pyt[tglPayment]',statusBayar='1',inv='$pyt[paymentOrder]',caraBayar='Transfer' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBulanan ='$pyt[idTagihan]'");
      }
    } else {
      mysqli_query($conn, "INSERT INTO tagihan_bebas_bayar (idTagihanBebas,tglBayar,jumlahBayar,caraBayar) VALUES ('$pyt[idTagihan]','$pyt[tglPayment]','$pyt[nominal]','Transfer') ");

      $sqlTotalBayar = mysqli_query($conn, "SELECT sum(totalTagihan) as TotalTagihan from tagihan_bebas WHERE idTagihanBebas='$pyt[idTagihan]'");
      $totalDibayar = mysqli_fetch_array($sqlTotalBayar);

      if ($pyt['nominal'] < $totalDibayar['TotalTagihan']) {
        mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='2' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBebas ='$pyt[idTagihan]'");
      } else {
        mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='1' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBebas ='$pyt[idTagihan]'");
      }
    }
    if ($query) {
      echo "<script>document.location='index.php?view=payment';</script>";
    } else {
      echo "<script>document.location='index.php?view=payment';</script>";
    }
  }

?>


<?php
}
?>