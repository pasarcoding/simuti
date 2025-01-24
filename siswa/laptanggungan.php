<style>
  .notification .badge-danger {
    position: absolute;
    top: 78px;
    left: 74px;
    padding: 3px 5px;
    border-radius: 50%;
    background-color: red;
    color: white;
  }
</style>

<?php
$minimal_nominal_bayar = '10000';
?>
<?php

if (isset($_GET['tampil'])) {
  $tahun = $_GET['tahun'];
  $kelas = $_GET['kelas'];
} else {
  $tahun = $ta['idTahunAjaran'];
  $kelas = '';
}
$dtsiswa = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM view_detil_siswa where idSiswa='$_SESSION[idsa]'"));
$nissiswa = $dtsiswa['nisSiswa'];
$namasiswa = $dtsiswa['nmSiswa'];
$namakelas = $dtsiswa['nmKelas'];
$dsiswa = mysqli_fetch_array(mysqli_query($conn, "SELECT siswa.*,  kelas_siswa.nmKelas FROM siswa  LEFT JOIN kelas_siswa ON siswa.kelasSiswa=kelas_siswa.idKelas  WHERE idSiswa='$siswa'"));
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
?>

<div class="box-lg-12">

  <?php if (!isset($_GET['act'])) : ?>
    <?php
    //tagihan bebas
    $sqlTagihanBebas = mysqli_query($conn, "SELECT
                      tagihan_bebas.*,
                      jenis_bayar.idPosBayar,
                      pos_bayar.nmPosBayar,
                      jenis_bayar.idTahunAjaran,
                      jenis_bayar.nmJenisBayar,
                      jenis_bayar.tipeBayar,
                      siswa.nisSiswa,
                      siswa.nisnSiswa,
                      siswa.nmSiswa,
                      siswa.jkSiswa,
                      siswa.agamaSiswa,
                      siswa.idKelas,
                      siswa.statusSiswa,
                      tahun_ajaran.nmTahunAjaran,
                      kelas_siswa.nmKelas
                    FROM
                      tagihan_bebas
                    INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                    INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
                    INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                    INNER JOIN kelas_siswa ON tagihan_bebas.idKelas = kelas_siswa.idKelas
                    INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                    WHERE siswa.idSiswa='$_SESSION[idsa]' ORDER BY tagihan_bebas.idTagihanBebas ASC");
    //AND jenis_bayar.idTahunAjaran='$_GET[idTahunAjaran]' 
    ?>
    <div class="col-xs-12">
      <div class="box box-success box-solid">
        <div class="box-header with-border">
          <!-- tools box -->
          <div class="pull-right box-tools">

            <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
              <i class="fa fa-minus"></i></button>
          </div>
          <!-- /. tools -->
          <h3 class="box-title">Informasi Siswa</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          $ta = "<b>Semua Tahun Ajaran<b>";
          $thnAjaran = "Semua Tahun Ajaran";

          $tgl = date('Y-m-d');

          $sqlSiswa1 = mysqli_query($conn, "SELECT siswa.*,kelas_siswa.nmKelas FROM siswa
                          INNER JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas
                          WHERE siswa.idSiswa='$_SESSION[idsa]'");
          $dts = mysqli_fetch_array($sqlSiswa1);
          ?>
          <table class="table table-striped">
            <tr>
              <td width="200">Tahun Ajaran</td>
              <td width="4">:</td>
              <td><b><?php echo $ta; ?></b></td>
            </tr>
            <tr>
              <td>NIS</td>
              <td>:</td>
              <td><?php echo $dts['nisSiswa']; ?></td>
            </tr>
            <tr>
              <td>NISN</td>
              <td>:</td>
              <td><?php echo $dts['nisnSiswa']; ?></td>
            </tr>
            <tr>
              <td>Nama Siswa</td>
              <td>:</td>
              <td><?php echo $dts['nmSiswa']; ?></td>
            </tr>
            <tr>
              <td>Kelas</td>
              <td>:</td>
              <td><?php echo $dts['nmKelas']; ?></td>
            </tr>
          </table>
        </div>
      </div>


      <div class="box box-info box-solid">
        <div class="box-header with-border">
          <!-- tools box -->
          <div class="pull-right box-tools">
            <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
              <i class="fa fa-minus"></i></button>
          </div>
          <!-- /. tools -->
          <h3 class="box-title">Fitur Kilat</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <h5> Fitur ini digunakan untuk mempermudah transaksi</h5>

          <button type="button" class="btn btn-info btn-xs notification" data-toggle="modal" data-target="#ModalTransaksi">
            <span class="fa fa-shopping-cart"></span> Transaksi
            <span class="badge badge-danger">
              <div id="div-total-transaksi"></div>
            </span>
          </button>

          <div id="ModalTransaksi" class="modal fade" role="dialog">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Transaksi</h4>
                </div>
                <div class="modal-body table-responsive">
                  <div id="detailTransaksi"></div>
                </div>
                <div class="modal-footer">
                  <input type="hidden" id="totTransaksi">
                  <form id="frm-payment" action="siswa/payment/proses.php" method="POST">
                    <?php
                    $date = date("YmdHis");
                    $inv = 'INV' . $date;
                    ?>
                    <input type="hidden" name="sws" id="sws" value="<?= $_SESSION[idsa] ?>">
                    <input type="hidden" name="invoice" id="invoice" value="<?= $inv ?>">
                    <button type="submit" class="btn col-12 btn-sm btn-success" id="btn-bayar">Bayar Sekarang</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
          &nbsp;&nbsp;&nbsp;

          <button type="button" class="btn btn-primary btn-xs" data-toggle="modal" data-target="#ModalRiwayat"><i class="fa fa-file-text-o"></i> Riwayat Transaksi</button>

          <div id="ModalRiwayat" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">Riwayat Transaksi</h4>
                </div>
                <div class="modal-body table-responsive">
                  <table width="100%" cellpadding="2" class="table table-striped table-hover table-bordered" id="example2">
                    <thead>
                      <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Order ID</th>
                        <th>No Rekening</th>
                        <th>Rincian</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $no = 1;
                      $riwayatPayment = mysqli_query($conn, "SELECT * FROM payment WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder <> '' GROUP BY paymentOrder ORDER BY idPayment DESC");
                      while ($rp = mysqli_fetch_array($riwayatPayment)) {
                      ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?= tgl_miring($rp['tglPayment']) ?></td>
                          <td><?= $rp['paymentOrder'] ?></td>
                          <td>
                            <?php
                            if ($rp['tipePayment'] == 'gopay' || $rp['tipePayment'] == 'qris') {
                              echo '<center>' . ucfirst($rp['tipePayment']) . ' <br> <img src="' . $rp['noPayment'] . '" width="50px" height="50px"></center>';
                            } else {
                              echo ucfirst($rp['tipePayment']) . ' - ' . $rp['noPayment'];
                            }
                            ?>
                          </td>
                          <td>
                            <table width="100%" cellpadding="2">
                              <tbody>
                                <?php
                                $riwayatPaymentDetail = mysqli_query($conn, "SELECT * FROM payment WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder='$rp[paymentOrder]'");
                                while ($pymt = mysqli_fetch_array($riwayatPaymentDetail)) {
                                  if ($pymt['jenisTagihan'] == 'Bulanan') {

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
                          <td><a class="btn btn-primary btn-xs" href="?view=<?= $_GET['view'] ?>&act=lihat&inv=<?= $rp['paymentOrder'] ?>">Lihat</a></td>
                        </tr>
                      <?php
                      }
                      ?>
                    </tbody>
                  </table>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                </div>
              </div>
            </div>
          </div>

          <button type="button" class="btn btn-danger btn-xs" data-toggle="modal" data-target="#ModalCetakSemuaSlip"><span class="fa fa-print"></span> Cetak Semua Slip Pertanggal</button>

          <a class="btn btn-success btn-xs" target="_blank" title="Cetak Slip" href="./kwitansi.php?tahun=<?php echo $ta; ?>&tgl=<?php echo $tgl; ?>&kelas=<?php echo $dts['idKelas']; ?>&siswa=<?php echo $_SESSION[idsa]; ?>"><span class="fa fa-print"></span> Cetak Semua Slip Hari Ini</a>

          <a href="./cetak_tagihan_persiswa.php?siswa=<?php echo $_SESSION['idsa'] ?>&tahun=<?php echo $tahun ?>" class="btn btn-danger btn-xs" target="_blank"><span class="fa fa-print"></span> Cetak Tagihan</a>


          <?php
          $TAG_BULAN = array();
          while ($dj = mysqli_fetch_array($sqlJenisBayar)) {
            if ($dj['tipeBayar'] == 'bebas') {
              $sqlB = mysqli_query($conn, "SELECT
                    tagihan_bebas_bayar.idTagihanBebasBayar,
                    tagihan_bebas_bayar.idTagihanBebas,
                    tagihan_bebas_bayar.tglBayar,
                    tagihan_bebas_bayar.jumlahBayar,
                    tagihan_bebas_bayar.ketBayar,
                    tagihan_bebas_bayar.caraBayar,
                    tagihan_bebas.idJenisBayar,
                    tagihan_bebas.idSiswa,
                    tagihan_bebas.idKelas,
                    tagihan_bebas.totalTagihan,
                    tagihan_bebas.statusBayar
                    FROM
                      tagihan_bebas_bayar
                    INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
                    INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
                    WHERE tagihan_bebas.idJenisBayar='$dj[idJenisBayar]' AND tagihan_bebas.statusBayar<>'0' AND tagihan_bebas.idSiswa = '$_SESSION[idsa]' AND (DATE(tagihan_bebas_bayar.tglBayar)) ='$tgl'");

              while ($dtb = mysqli_fetch_array($sqlB)) {
                $TAG_BULAN[] = "*" . ucwords(strtolower($dj[nmJenisBayar])) . "* sebesar *" . buatRp($dtb[jumlahBayar]) . "*";
              }
            } else if ($dj['tipeBayar'] == 'bulanan') {
              $sqlLap = mysqli_query($conn, "SELECT * FROM view_laporan_bayar_bulanan 
                    WHERE idJenisBayar='$dj[idJenisBayar]' AND idTahunAjaran='$tahunaktif[idTahunAjaran]' AND idSiswa='$_SESSION[idsa]' AND statusBayar='1' AND (DATE(tglBayar)) = '$tgl' ORDER BY urutan ASC");
              while ($rt = mysqli_fetch_array($sqlLap)) {
                $TAG_BULAN[] = "*" . ucwords(strtolower($dj[nmJenisBayar])) . "/" . $rt[nmBulan] . "* sebesar *" . buatRp($rt[jumlahBayar]) . "*";
              }
            }
            //total tagihan lainnya
            $totLainya = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totBayar
                  FROM tagihan_bebas_bayar
                  INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
                  INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa 
                  WHERE tagihan_bebas.statusBayar<>'0' AND tagihan_bebas.idSiswa = '$_SESSION[idsa]' AND (DATE(tagihan_bebas_bayar.tglBayar)) ='$tgl'"));
            //total tagihan bulanan
            $totBulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totBayar FROM tagihan_bulanan WHERE idSiswa='$_SESSION[idsa]' AND statusBayar='1' AND (DATE(tglBayar)) = '$tgl'"));
            $total_pembayaran = buatRp($totLainya['totBayar'] + $totBulanan['totBayar']);
          }

          for ($i = 0; $i < count($TAG_BULAN); $i++) {
            $textPembayaran = $textPembayaran . ' ' . $TAG_BULAN[$i] . ',';
          }

          $page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
          $uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
          $uri_segments = explode('/', $uri_path);
          $link_url = "$page_URL$_SERVER[HTTP_HOST]/" . $uri_segments[1] . '/slip_bulanan_persiswa_peritem_sekarang.php?tahun=' . $thnAjaran . '%26tgl=' . $tgl . '%26kelas=' . $dts[idKelas] . '%26siswa=' . $siswa;

          $format_tgl = date('d-m-Y', strtotime($tgl));
          $wa_sekolah = 'http://wa.me/%2B6281548557174';
          $artb = mysqli_fetch_array($sqlTagihanBebas);

          echo "";
          ?>

          <div id="ModalCetakSemuaSlip" class="modal fade" role="dialog">
            <form method="GET" action="./slip_bulanan_persiswa_peritem.php" class="form-horizontal" target="_blank" title="Cetak Slip">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Filter Data</h4>
                  </div>
                  <div class="modal-body">

                    <input type="hidden" name="tahun" value="<?php echo $ta; ?>">
                    <input type="hidden" name="siswa" value="<?php echo $_SESSION[idsa]; ?>">
                    <input type="hidden" name="kelas" value="<?php echo $dts['idKelas']; ?>">

                    <table class="table table-responsive">
                      <thead>
                        <tr>
                          <th>Mulai</th>
                          <th>Sampai</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" name="tgl1" id="tgl1" class="form-control pull-right date-picker" required="" value">
                            </div>
                            <!-- /.input group -->
                          </td>
                          <td>
                            <div class="input-group date">
                              <div class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                              </div>
                              <input type="text" name="tgl2" id="tgl2" class="form-control pull-right date-picker" required="">
                            </div>
                            <!-- /.input group -->
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="modal-footer">
                    <input type="submit" value="Cetak" class="btn btn-success" onclick="checkTanggal('#tgl1','#tgl2');">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <div class="col-xs-12">
      <!-- List Tagihan Bulanan -->
      <div class="box box-warning box-solid">
        <div class="box-header backg with-border">
          <h3 class="box-title">Tagihan Bulanan</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-striped table-hover" style="cursor: pointer;">
            <thead>
              <tr>
                <th>No.</th>
                <th colspan="2">Nama Pembayaran</th>
                <th>Total</th>
                <th>Sudah Dibayar</th>
                <th>Kekurangan</th>
                <th>Status</th>
              </tr>
            </thead>
            <?php
            $no = 1;
            $sqlListTGB = mysqli_query($conn, "SELECT
                    jenis_bayar.idJenisBayar,
                    jenis_bayar.nmJenisBayar,
                    jenis_bayar.tipeBayar,
                    jenis_bayar.idTahunAjaran,
                    tahun_ajaran.nmTahunAjaran,
                    Sum(tagihan_bulanan.jumlahBayar) AS jmlTagihanBulanan,
                    kelas_siswa.nmKelas,
                    siswa.idSiswa,
                    siswa.nisSiswa,
                    siswa.nisnSiswa,
                    siswa.nmSiswa,
                    jenis_bayar.idPosBayar,
                    pos_bayar.nmPosBayar,
                    pos_bayar.ketPosBayar
                    FROM
                    jenis_bayar
                    INNER JOIN tagihan_bulanan ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
                    INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                    INNER JOIN siswa ON tagihan_bulanan.idSiswa = siswa.idSiswa
                    INNER JOIN kelas_siswa ON tagihan_bulanan.idKelas = kelas_siswa.idKelas
                    INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                    WHERE siswa.idSiswa='$_SESSION[idsa]' 
                    GROUP BY
                    jenis_bayar.idJenisBayar");
            while ($rtgb = mysqli_fetch_array($sqlListTGB)) {
              $dtgb = mysqli_fetch_array(mysqli_query($conn, "SELECT sum(jumlahBayar) as jmlDibayar FROM tagihan_bulanan WHERE idJenisBayar=$rtgb[idJenisBayar] AND idSiswa=$_SESSION[idsa] AND statusBayar='1'"));
              $no = 1;
              if ($dtgb['jmlDibayar'] == 0) {
                $status = "<label class='label label-danger'>Belum Bayar</label>";
                $icon = "fa-plus";
                $btn = "btn-danger";
                $color = "red";
                $alt = "Bayar";
              } elseif ($dtgb['jmlDibayar'] < $rtgb['jmlTagihanBulanan']) {
                $status = "<label class='label label-warning'>Belum Lengkap</label>";
                $icon = "fa-plus";
                $btn = "btn-warning";
                $color = "red";
                $alt = "Bayar";
              } else {
                $status = "<label class='label label-success'>Lunas</label>";
                $icon = "fa-search";
                $btn = "btn-success";
                $color = "green";
                $alt = "Detil";
              }
              echo "<tbody><tr style='color:$color'  data-toggle='collapse' data-target='#demo" . $rtgb['idJenisBayar'] . "' >
                                  <td>" . $no++ . "</td>
                                  <td colspan='2'>" . $rtgb['nmJenisBayar'] . " T.A. " . $rtgb['nmTahunAjaran'] . "</td>
                                  <td>" . buatRp($rtgb['jmlTagihanBulanan']) . "</td>
                                  <td>" . buatRp($dtgb['jmlDibayar']) . "</td>
                                  <td>" . buatRp($rtgb['jmlTagihanBulanan'] - $dtgb['jmlDibayar']) . "</td>
                                  <td>$status</td>
                            </tr></tbody>";

              echo '<tbody  id="demo' . $rtgb['idJenisBayar'] . '" class="collapse">
                                        <tr>
                                          <td colspan="9" align="center" class="info">
                                              <h4>' . $rtgb[nmJenisBayar] . ' - T.A ' . $rtgb[nmTahunAjaran] . '</h4>
                                          </td>
                                        </tr>
                                        <tr>
                                          <th>No.</th> 
                                          <th>Bulan</th>
                                          <th>Tahun</th>
                                          <th>Tagihan</th>
                                          <th style="text-align: center;">Status</th>
                                          <th style="text-align: center;">Cara Bayar</th>
                                          <th>Action</th>
                                        </tr>';

              $no = 1;
              $sqltbDetail = mysqli_query($conn, "SELECT tagihan_bulanan.*, bulan.nmBulan, bulan.urutan FROM tagihan_bulanan LEFT JOIN bulan ON tagihan_bulanan.idBulan=bulan.idBulan WHERE idJenisBayar='$rtgb[idJenisBayar]' AND idSiswa='$_SESSION[idsa]' ORDER BY bulan.urutan ASC");


              while ($tb = mysqli_fetch_array($sqltbDetail)) {
                $pisah_TA = explode('/', $rtgb['nmTahunAjaran']);
                if ($tb['urutan'] <= 6) {
                  $tahun = $pisah_TA[0];
                } else {
                  $tahun = $pisah_TA[1];
                }
                if ($tb['statusBayar'] == '1') {
                  $color = "success";
                  $status = 'Lunas';
                  $pay = null;
                  $cara = $tb['caraBayar'];
                } else if ($tb['statusBayar'] == '2') {
                  $color = "warning";
                  $status = 'Pending';
                  $pay = null;
                  $cara = "";
                } else {
                  $color = "danger";
                  $status = 'Belum Lunas';
                  $cara = "";
                  $pay = '<input type="button" class="btn btn-success btn-xs text-center button-pembayaran" value="Bayar" onclick="addCart(\'Bulanan\',' . $tb['idTagihanBulanan'] . ',' . $tb['jumlahBayar'] . ')">';
                }
                echo '<tr class="' . $color . '">
                              <td>' . $no++ . '</td>
                              <td>' . $tb['nmBulan'] . '</td>
                              <td>' . $tahun . '</td>
                              <td>' . buatRp($tb['jumlahBayar']) . '</td>
                              <td align="center">' . $status . ' </td>
                              <td align="center"> ' . $cara . '</td>
                             <td>' . $pay . '</td>
                            </tr>';
              }
              echo '</tbody>';
            }
            ?>

          </table>
        </div>
      </div>

      <div class="box box-danger box-solid">
        <div class="box-header backg with-border">
          <h3 class="box-title">Tagihan Lainnya</h3>
        </div><!-- /.box-header -->
        <div class="box-body table-responsive">
          <table class="table table-striped table-hover">
            <thead>
              <tr>
                <th>No.</th>
                <th>Jenis Pembayaran</th>
                <th>Total</th>
                <th>Dibayar</th>
                <th>Kekurangan</th>
                <th>Status</th>
               <th>Action</th> 
              </tr>
            </thead>
            <tbody>
              <?php
              $sqlTagihanBebas = mysqli_query($conn, "SELECT
                    tagihan_bebas.*,
                    jenis_bayar.idPosBayar,
                    pos_bayar.nmPosBayar,
                    jenis_bayar.idTahunAjaran,
                    jenis_bayar.nmJenisBayar,
                    jenis_bayar.tipeBayar,
                    siswa.nisSiswa,
                    siswa.nisnSiswa,
                    siswa.nmSiswa,
                    siswa.jkSiswa,
                    siswa.agamaSiswa,
                    siswa.idKelas,
                    siswa.statusSiswa,
                    tahun_ajaran.nmTahunAjaran,
                    kelas_siswa.nmKelas
                  FROM
                    tagihan_bebas
                  INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                  INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
                  INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                  INNER JOIN kelas_siswa ON tagihan_bebas.idKelas = kelas_siswa.idKelas
                  INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                  WHERE siswa.idSiswa='$_SESSION[idsa]' ORDER BY tagihan_bebas.idTagihanBebas ASC");

              $no = 1;
              while ($rtb = mysqli_fetch_array($sqlTagihanBebas)) {
                $dtBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT sum(jumlahBayar) as totalDibayar FROM tagihan_bebas_bayar WHERE idTagihanBebas='$rtb[idTagihanBebas]'"));

                $sisa = $rtb['totalTagihan'] - $dtBayar['totalDibayar'];
                $sisaRp = buatRp($sisa);

                if ($rtb['statusBayar'] == '0') {
                  $status = "<label class='label label-danger'>Belum Bayar</label>";
                  $icon = "fa-plus";
                  $btn = "btn-danger";
                  $color = "red";
                  $alt = "Bayar";
                  $btncetak = "disabled";
                  $pa = '<input type="button" class="btn btn-success btn-xs text-center button-pembayaran" value="Bayar" data-toggle="modal" data-target="#modalBayarBebas' . $rtb['idTagihanBebas'] . '">';
                } elseif ($rtb['statusBayar'] == '2') {
                  $status = "<label class='label label-warning'>Pending</label>";
                  $icon = "fa-plus";
                  $btn = "btn-warning";
                  $color = "red";
                  $alt = "Bayar";
                  $btncetak = "";
                  $pa = '<a class="btn '.$btn.' btn-xs" title="'.$alt.'" href="?view=angsuran&tagihan='.$rtb['idTagihanBebas'].'"><span class="fa fa-eye"></span> Detail</a>
                        <input type="button" class="btn btn-success btn-xs text-center button-pembayaran" value="Bayar" data-toggle="modal" data-target="#modalBayarBebas' . $rtb['idTagihanBebas'] . '">';
              } elseif ($rtb['statusBayar'] == '1') {
                  $status = "<label class='label label-success'>Lunas</label>";
                  $icon = "fa-search";
                  $btn = "btn-success";
                  $color = "green";
                  $alt = "Detil";
                  $btncetak = "";
                  $pa = "<a class='btn $btn btn-xs' title='$alt' href='?view=angsuran&tagihan=$rtb[idTagihanBebas]' ><span class='fa $icon'></span> $alt</a>
                  ";
                }
                echo "<tr style='color:$color' >
                              <td>" . $no++ . "</td>
                              <td>" . $rtb['nmJenisBayar'] . " T.A. " . $rtb['nmTahunAjaran'] . "</td>
                              <td>" . buatRp($rtb['totalTagihan']) . "</td>
                              <td>" . buatRp($dtBayar['totalDibayar']) . "</td>
                              <td>" . buatRp($rtb['totalTagihan'] - $dtBayar['totalDibayar']) . "</td>
                              <td>" . $status . "
                              </td>      
                              <td>" . $pa . "</td>  
                            </tr>";
                echo '<div class="modal fade in" id="modalBayarBebas' . $rtb['idTagihanBebas'] . '" role="dialog">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h4 class="modal-title">Informasi</h4>
                                  </div>
                                    <div class="modal-body">
                                      <div class="form-group">
                                        <label>Nama Pembayaran</label><br>
                                        <label style="background-color:#bab5b5; padding:3px; width:100%">' . $rtb['nmJenisBayar'] . " T.A. " . $rtb['nmTahunAjaran'] . '</label>
                                      </div>
                                      <div class="form-group">
                                        <label>Masukan Jumlah Yang Akan Dibayar</label>
                                        <input type="text" name="jumlah_bayar" class="form-control" id="nominal_bayar' . $rtb['idTagihanBebas'] . '" onkeyup="convertToRupiah(this);">
                                        <small style="color:red">Jumlah minimal pembayaran ' . buatRp($minimal_nominal_bayar) . '</small>
                                        <input type="hidden" class="form-control" id="max_nominal' . $rtb['idTagihanBebas'] . '" value="' . ($rtb['totalTagihan'] - $dtBayar['totalDibayar']) . '">
                                      </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="submit" class="btn btn-sm btn-success col-12" onclick="cekBebas(' . $rtb['idTagihanBebas'] . ')" data-dismiss="modal">Bayar</button>
                                      <button type="button" class="btn btn-sm btn-danger col-12" data-dismiss="modal">Batal</button>
                                    </div>
                                </div>
                              </div>
                            </div>';
              }
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  <?php else : ?>

    <?php
    $datres = cek_payment($_GET['inv'], $idt['serverKey'], $idt['link']);
    $type = $datres['payment_type'];
    $tipe_transaksi = '';
    if ($type == 'cstore') {
      $tipe_transaksi = $datres['store'];
      $kodebayar_transaksi = $datres['payment_code'];
      mysqli_query($conn, "UPDATE payment SET noPayment='$kodebayar_transaksi' WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder='$_GET[inv]'");
    } elseif ($type == 'bank_transfer') {
      $permata_va_number = isset($datres['permata_va_number']) ? $datres['permata_va_number'] : '';
      if ($permata_va_number <> '') {
        $tipe_transaksi = 'permata';
        $kodebayar_transaksi = $permata_va_number;
      } else {
        $tf = $datres['va_numbers'][0];
        $tipe_transaksi = $tf['bank'];
        $kodebayar_transaksi = $tf['va_number'];
      }
      mysqli_query($conn, "UPDATE payment SET noPayment='$kodebayar_transaksi' WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder='$_GET[inv]'");
    } elseif ($type == 'echannel') {
      $tipe_transaksi = 'mandiri';
      $kodebayar_transaksi = $datres['bill_key'];
      mysqli_query($conn, "UPDATE payment SET noPayment='$kodebayar_transaksi' WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder='$_GET[inv]'");
    } elseif ($type == 'gopay' || $type == 'qris' || $type == 'shopeepay' || $type == 'akulaku') {
      $tipe_transaksi = $type;
    } elseif ($type == 'bca_klikpay') {
      $tipe_transaksi = 'BCA KlikPay';
    } elseif ($type == 'bca_klikbca') {
      $tipe_transaksi = 'BCA KlikBCA';
    } elseif ($type == 'cimb_clicks') {
      $tipe_transaksi = 'CIMB Clicks';
    } elseif ($type == 'danamon_online') {
      $tipe_transaksi = 'Danamon Online';
    } elseif ($type == 'bri_epay') {
      $tipe_transaksi = 'BRI Epay';
    }
    if ($tipe_transaksi <> '') {
      mysqli_query($conn, "UPDATE payment SET tipePayment='$tipe_transaksi' WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder='$_GET[inv]'");
    }

    $payment = mysqli_fetch_array(mysqli_query($conn, "SELECT payment.*, sum(payment.nominal) as totalBayar FROM payment WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder='$_GET[inv]' GROUP BY paymentOrder"));
    ?>
    <div class="box box-success box-solid">
      <div class="box-header with-border">
        <!-- tools box -->
        <div class="pull-right box-tools">

          <button type="button" class="btn btn-primary btn-sm pull-right" data-widget="collapse" data-toggle="tooltip" title="Collapse" style="margin-right: 5px;">
            <i class="fa fa-minus"></i></button>
        </div>
        <!-- /. tools -->
        <h3 class="box-title">Pembayaran</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-6">
            <table class="table" border="0">
              <tr style="border-bottom: 1px solid black">
                <th>Waktu Transaksi</th>
                <td>:</td>
                <th><?= tgl_indo($payment['tglPayment']) . ' ' . date('H:i:s', strtotime($payment['tglPayment'])); ?></th>
              </tr>
              <tr>
                <td>Order ID</td>
                <td>:</td>
                <td><?= $payment['paymentOrder'] ?></td>
              </tr>
              <tr>
                <td>Batas Pembayaran</td>
                <td>:</td>
                <td><?php
                    $batasBayar = date('Y-m-d', strtotime('+1 days', strtotime($payment['tglPayment'])));
                    echo tgl_indo($batasBayar) . ' ' . date('H:i', strtotime($payment['tglPayment'])); ?>
                </td>
              </tr>
              <tr>
                <td>Total Pembayaran</td>
                <td>:</td>
                <td><?= buatRp($payment['totalBayar'] + $idt['biaya_admin']) ?></td>
              </tr>
              <tr>
                <td>No Rekening</td>
                <td>:</td>
                <td><?php
                    if ($payment['tipePayment'] == 'gopay' || $payment['tipePayment'] == 'qris') {
                      echo '<center>' . ucfirst($payment['tipePayment']) . ' <br> <img src="' . $payment['noPayment'] . '" width="200px" height="200px"></center>';
                    } else {
                      echo ucfirst($payment['tipePayment']) . ' - ' . $payment['noPayment'];
                    }
                    ?>
                </td>
              </tr>
              <tr>
                <td>Status</td>
                <td>:</td>
                <td>
                  <?php
                  if ($payment['status'] == 'success') {
                    echo '<span class="btn-xs btn-success">Sukses</span>';
                  } elseif ($payment['status'] == 'pending') {
                    echo '<span class="btn-xs btn-warning">Pending</span>';
                  } elseif ($payment['status'] == 'settlement') {
                    echo '<span class="btn-xs btn-success">Sukses</span>';
                  } elseif ($payment['status'] == 'deny' or $payment['status'] == 'expire' or $payment['status'] == 'cancel') {
                    echo '<span class="btn-xs btn-danger">Gagal</span>';
                  }
                  ?>
                </td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table" border="0">
              <tr style="border-bottom: 1px solid black">
                <th colspan="3">Rincian Pembayaran</th>
              </tr>
              <tr>
                <td colspan="3">
                  <table class="table" border="0">
                    <?php
                    $riwayatPaymentDetail = mysqli_query($conn, "SELECT * FROM payment WHERE idSiswa='$_SESSION[idsa]' AND paymentOrder='$payment[paymentOrder]'");
                    while ($pymt = mysqli_fetch_array($riwayatPaymentDetail)) {
                      if ($pymt['jenisTagihan'] == 'Bulanan') {

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
                    <?php } ?>
                    <tr>
                      <td>Biaya Admin</td>
                      <td><?= buatRp($idt['biaya_admin']) ?></td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </div>

          <div class="box-footer text-right">
            <a class="btn btn-danger btn-sm" href="?view=<?= $_GET['view'] ?>">Kembali</a>
          </div>
        </div>

      <?php endif; ?>
      </div>

      <div id="result-json"></div>
      <script type="text/javascript">
        $(document).ready(function() {
          loadTransaksi();
          loadDetailTransaksi();
          btnBayar()
        });

        function loadTransaksi() {
          var siswa = "<?= $_SESSION['idsa'] ?>";
          $.ajax({
            type: 'POST',
            url: "siswa/payment/index.php",
            data: {
              siswa: siswa
            },
            cache: false,
            success: function(msg) {
              $("#div-total-transaksi").html(msg);
              $("#totTransaksi").val(msg);
              btnBayar()
            }
          });
        }

        function cekBebas(idTagihan) {
          var nominal = $('#nominal_bayar' + idTagihan).val();
          var nominal_baru = parseInt(nominal.replace(/[^\w\s]/gi, ''));
          var min_nominal = parseInt('<?= $minimal_nominal_bayar ?>');
          var max_nominal = parseInt($('#max_nominal' + idTagihan).val());
          if (nominal_baru < min_nominal) {
            toastr["error"]("Maaf, nominal tidak boleh kurang dari " + min_nominal + ".", "Gagal!");
          } else if (nominal_baru > max_nominal) {
            toastr["error"]("Maaf, nominal tidak boleh lebih dari " + max_nominal + ".", "Gagal!");
          } else {
            addCart('Bebas', idTagihan, nominal_baru);
          }
        }

        function addCart(jenis, tagihan, nominal) {
          var siswa = "<?= $_SESSION['idsa'] ?>";
          $.ajax({
            type: 'POST',
            url: "siswa/payment/add.php",
            data: {
              jenis: jenis,
              tagihan: tagihan,
              siswa: siswa,
              nominal: nominal
            },
            cache: false,
            success: function(msg) {
              if (msg.notif == 'success') {
                toastr["success"]("Tagihan berhasil ditambahkan.", "Sukses!");
              } else if (msg.notif == 'errorTagihan') {
                toastr["error"]("Tagihan gagal ditambahkan.", "Gagal!");
              } else if (msg.notif == 'errorAdd') {
                toastr["error"]("Maaf, tagihan ini telah ada di dalam transaksi.", "Gagal!");
              } else {
                toastr["error"]("Terjadi kesalahan.", "Gagal!");
              }
              loadTransaksi();
              loadDetailTransaksi();
              btnBayar()
            }
          });
        }

        function deleteCart(payment) {
          $.ajax({
            type: 'POST',
            url: "siswa/payment/delete.php",
            data: {
              payment: payment
            },
            cache: false,
            success: function(msg) {
              if (msg.notif == 'success') {
                toastr["success"]("Tagihan berhasil dihapus.", "Sukses!");
              } else {
                toastr["error"]("Tagihan gagal dihapus.", "Gagal!");
              }
              loadDetailTransaksi();
              loadTransaksi();
              btnBayar();
            }
          });
        }

        function loadDetailTransaksi() {
          var siswa = "<?= $_SESSION['idsa'] ?>";
          var biayaAdmin = "<?= isset($idt['biaya_admin']) ? $idt['biaya_admin'] : '0' ?>";
          $.ajax({
            type: 'POST',
            url: "siswa/payment/detail.php",
            data: {
              siswa: siswa,
              biayaAdmin: biayaAdmin
            },
            cache: false,
            success: function(msg) {
              $("#detailTransaksi").html(msg);
              btnBayar()
            }
          });
        }

        function btnBayar() {
          var tot = parseInt($("#totTransaksi").val());
          if (tot == 0) {
            document.getElementById("btn-bayar").disabled = true;
          } else {
            document.getElementById("btn-bayar").disabled = false;
          }
        }
      </script>

      <?php
      //query status pembayaran pending
      $qPembayaranPending = mysqli_query($conn, "SELECT * FROM payment WHERE idSiswa='$_SESSION[idsa]' AND status='pending'");
      ?>

      <script src="<?= $idt['link'] ?>" data-client-key="<?= $idt['clientKey'] ?>"></script>
      <script type="text/javascript">
        $('#frm-payment').submit(function(e) {
          e.preventDefault();
          var formData = new FormData($("#frm-payment")[0]);

          if ('<?= mysqli_num_rows($qPembayaranPending) ?>' > 0) {
            toastr["error"]("Maaf, harap selesaikan pembayaran sebelumnya.", "Gagal!");
          } else {
            $.ajax({
              url: $("#frm-payment").attr('action'),
              type: 'post',
              data: formData,
              dataType: 'json',
              contentType: false,
              processData: false,
              success: function(data) {
                snap.pay(data, {
                  onSuccess: function(result) {
                    var siswa = $('#sws').val();
                    var invoice = $('#invoice').val();
                    var status = JSON.stringify(result.transaction_status, null, 2);
                    var idtransaksi = result.transaction_id;
                    var tipe = result.payment_type;
                    $.ajax({
                      type: 'POST',
                      url: "siswa/payment/success.php",
                      data: {
                        siswa: siswa,
                        invoice: invoice,
                        status: status,
                        idtransaksi: idtransaksi,
                        tipe: tipe
                      },
                      cache: false,
                      success: function(msg) {
                        document.location = 'index-siswa.php?view=laptanggungan&sukses';
                        //document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                      }
                    });
                  },
                  onPending: function(result) {
                    var siswa = $('#sws').val();
                    var invoice = $('#invoice').val();
                    var status = JSON.stringify(result.transaction_status, null, 2);
                    var idtransaksi = result.transaction_id;
                    var tipe = result.payment_type;
                    $.ajax({
                      type: 'POST',
                      url: "siswa/payment/pending.php",
                      data: {
                        siswa: siswa,
                        invoice: invoice,
                        status: status,
                        idtransaksi: idtransaksi,
                        tipe: tipe
                      },
                      cache: false,
                      success: function(msg) {
                        document.location = 'index-siswa.php?view=laptanggungan&act=lihat&inv=' + invoice;
                        //document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                      }
                    });
                  },
                  onError: function(result) {
                    document.location = 'index-siswa.php?view=laptanggungan&error';
                    //document.getElementById('result-json').innerHTML += JSON.stringify(result, null, 2);
                  }
                });
              }
            });
          }
        });
      </script>