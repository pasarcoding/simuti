<?php
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$nmSekolah = $idt['nmSekolah'];
if ($_GET[act] == '') { ?>

  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Filter Data Pelanggaran Siswa</h3>
      </div>
      <div class="box-body">
        <form action="" class="form-horizontal" method="get" accept-charset="utf-8">
          <input type="hidden" name="view" value="<?= $_GET['view'] ?>">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
            <div class="col-sm-2">
              <input type="hidden" id="idTahunAjaran" value="<?= $_GET[thn_ajar] ?>">
              <select class="form-control" name="thn_ajar" id="Ctahunajaran">

              </select>
            </div>
            <label for="" class="col-sm-2 control-label">Cari Siswa</label>
            <div class="col-sm-4">
              <div class="input-group">
                <input type="text" class="form-control" autofocus="" name="nis" id="student_nis" placeholder="NISN Siswa" required="" value="<?= $_GET['nisn'] ?>">
                <span class="input-group-btn">
                  <button class="btn btn-success" type="submit">Cari</button>
                </span>
                <span class="input-group-btn">
                </span>
                <span class="input-group-btn">
                </span>
                <span class="input-group-btn">
                </span>
                <span class="input-group-btn">
                  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#dataSantri"><b>Data Siswa</b></button>
                </span>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div><!-- /.box -->
  </div>


  <div class="modal fade in" id="dataSantri" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">×</button>
          <h4 class="modal-title">Cari Data Siswa</h4>
        </div>
        <div class="modal-body">
          <div class="box-body table-responsive">
            <table id="example1" class="table table-hover dataTable no-footer">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Nis</th>
                  <th>Nama Siswa</th>
                  <th>Kelas</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if ($idUnitUsers != '0') {
                  $tampil = mysqli_query($conn, "SELECT view_detil_siswa.* FROM view_detil_siswa  LEFT JOIN kelas_siswa ON view_detil_siswa.idKelas = kelas_siswa.idKelas    ORDER BY view_detil_siswa.idSiswa DESC");
                } else {
                  $tampil = mysqli_query($conn, "SELECT view_detil_siswa.* FROM view_detil_siswa  LEFT JOIN kelas_siswa ON view_detil_siswa.idKelas = kelas_siswa.idKelas   ORDER BY view_detil_siswa.idSiswa DESC");
                }

                $no = 1;
                while ($r = mysqli_fetch_array($tampil)) {
                  echo "<tr>
                              <td>$no</td>
                              <td>$r[nisSiswa]</td>
                              <td>$r[nmSiswa]</td>
                              
                              <td>$r[nmKelas]</td>
                              <td>
                                <center><button type='button' data-dismiss='modal' class='btn btn-primary btn-xs' onclick=\"ambil_data('$r[nisnSiswa]')\">Pilih</button></center>
                              </td>
                            </tr>";
                  $no++;
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
  </div>
<?php } ?>

<script>
  function ambil_data(nisSiswa) {
    var nisSiswa = nisSiswa;
    var thAjaran = $("#Ctahunajaran").val();

    window.location.href = '?view=<?= $_GET[view] ?>&thn_ajar=' + thAjaran + '&nis=' + nisSiswa;
  }
</script>


<?php if (isset($_GET['thn_ajar']) && isset($_GET['nis'])) {


  $siswa = mysqli_fetch_array(mysqli_query($conn, "SELECT view_detil_siswa.* FROM view_detil_siswa  LEFT JOIN kelas_siswa ON view_detil_siswa.idKelas = kelas_siswa.idKelas WHERE view_detil_siswa.nisnSiswa='$_GET[nis]'"));
  $thn = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$_GET[thn_ajar]'"));



  //notif
  if ($_SESSION['notif'] == 'csukses') {
    echo '<script>toastr["success"]("Data berhasil disimpan.","Sukses!")</script>';
  } elseif ($_SESSION['notif'] == 'dsukses') {
    echo '<script>toastr["success"]("Data berhasil dihapus.","Sukses!")</script>';
  } elseif ($_SESSION['notif'] == 'gagal') {
    echo '<script>toastr["error"]("Data gagal diproses.","Gagal!")</script>';
  }
  unset($_SESSION['notif']);

?>
  <div class="col-md-12">

    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Informasi Siswa</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="col-md-9">
          <table class="table table-striped">
            <tbody>
              <tr>
                <td width="200">Tahun Ajaran</td>
                <td width="4">:</td>
                <td><strong><?= $thn['nmTahunAjaran'] ?><strong></strong></strong></td>
              </tr>
              <tr>
                <td>NIS</td>
                <td>:</td>
                <td><?= $siswa['nisSiswa'] ?></td>
              </tr>
              <tr>
                <td>Nama Siswa</td>
                <td>:</td>
                <td><?= $siswa['nmSiswa'] ?></td>
              </tr>

              <tr>
                <td>Kelas</td>
                <td>:</td>
                <td><?= $siswa['nmKelas'] ?></td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
    </div>

    <!-- List Tagihan Bulanan -->
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Pelanggaran Siswa</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Tambah Pelanggaran</a></li>
            <li><a href="#tab_2" data-toggle="tab">Laporan Pelanggaran Siswa</a></li>
            <li><a href="#tab_3" data-toggle="tab">Rekap Laporan Pelanggaran Siswa</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="box-body">
                <div class="row">
                  <?php
                  //simpan
                  if (isset($_POST['tambah'])) {

                    $query = mysqli_query($conn, "INSERT INTO siswa_konseling(siswa,tahunAjaran,tanggal,pelanggaran,tindakan,poin,catatan,stdel) VALUES ('$_POST[konseling_id_siswa]','$_POST[konseling_periode]','$_POST[konseling_tanggal]','$_POST[konseling_pelanggaran]','$_POST[konseling_tindakan]','$_POST[konseling_poin]','$_POST[konseling_catatan]','0')");
//                     $a = mysqli_query($conn, "SELECT * from siswa
//                     WHERE idSiswa='$_POST[konseling_id_siswa]'         
//                               ");
//                     $re = mysqli_fetch_array($a);
//                     $hpo = $re['noHpOrtu'];
//                     $hps = $re['noHpSis'];
//                     $nama = $re['nmSiswa'];
//                     $msg_wa = array();
//                     $number_wa = array();

//                     //pesan whatsapp ortu
//                     $number_wa[] = $hpo;
//                     $msg_wa[] =  "Assalamualaikum, siswa atas nama $nama melakukan pelanggaran sebagai berikut:
		
// Tanggal: *$_POST[konseling_tanggal]*		
// Nama Pelanggaran: *$_POST[konseling_pelanggaran]*
// Tindakan: *$_POST[konseling_tindakan]* 
// Poin: *$_POST[konseling_poin]* 

// KONSELING $nmSekolah";
//                     //pesan whatsapp ortu
//                     $number_wa[] = $hps;
//                     $msg_wa[] =  "Assalamualaikum, siswa atas nama $nama melakukan pelanggaran sebagai berikut:
		
// Tanggal: *$_POST[konseling_tanggal]*		
// Nama Pelanggaran: *$_POST[konseling_pelanggaran]*
// Tindakan: *$_POST[konseling_tindakan]* 
// Poin: *$_POST[konseling_poin]* 
                    
// KONSELING $nmSekolah";

//                     for ($i = 0; $i < count($number_wa); $i++) {

//                       send_wa($link_send, $token_send, $number_send, $number_wa[$i], $msg_wa[$i]);
//                       header('Location: ' . $_POST['uri']);
//                     }
                    if ($query) {
                      $_SESSION['notif'] = 'csukses';
                      echo "<script>document.location='?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&nis=$_GET[nis]';</script>";
                    } else {
                      $_SESSION['notif'] = 'gagal';
                      echo "<script>document.location='?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&nis=$_GET[nis]';</script>";
                    }
                  }
                  ?>

                  <form action="?view=<?= $_GET[view] ?>&thn_ajar=<?= $_GET[thn_ajar] ?>&nis=<?= $_GET[nis] ?>" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                    <input type="hidden" name="konseling_periode" value="<?= $_GET['thn_ajar'] ?>">
                    <input type="hidden" name="konseling_id_siswa" value="<?= $siswa['idSiswa'] ?>">

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tanggal</label>
                        <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                          <input class="form-control md-3" required="" type="text" name="konseling_tanggal" placeholder="Tanggal Pelanggaran" value="<?= $tanggal_sekarang ?>">
                        </div>
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Pelanggaran</label>
                        <input type="text" class="form-control" required="" name="konseling_pelanggaran" placeholder="Pelanggaran">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tindakan</label>
                        <input type="text" class="form-control" required="" name="konseling_tindakan" placeholder="Tindakan">
                      </div>
                    </div>

                    <div class="col-md-3">
                      <div class="form-group">
                        <label>Poin</label>
                        <input type="number" class="form-control" required="" name="konseling_poin" placeholder="Poin">
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Catatan</label>
                        <input type="text" class="form-control" required="" name="konseling_catatan" placeholder="Catatan">
                      </div>
                    </div>

                    <!-- <div class="col-md-4">
                      <div class="form-group">
                        <label>File Surat Pernyataan</label>
                        <input type="file" name="nama_file" >
                      </div>
                    </div> -->


                    <div class="col-md-6">
                      <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                      <button type="reset" class="btn btn-default">Kosongkan</button>
                    </div>
                  </form>
                </div>
              </div>
            </div>


            <div class="tab-pane" id="tab_2">
              <div class="box-body table-responsive">
                <?php
                //hapus
                if (isset($_POST['hapus'])) {
                  $query = mysqli_query($conn, " UPDATE siswa_konseling SET stdel='1' WHERE idKonseling='$_POST[idKonseling]'");
                  if ($query) {
                    $_SESSION['notif'] = 'dsukses';
                    echo "<script>document.location='?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&nis=$_GET[nis]';</script>";
                  } else {
                    $_SESSION['notif'] = 'gagal';
                    echo "<script>document.location='?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&nis=$_GET[nis]';</script>";
                  }
                }
                ?>
                <table class="table table-bordered" style="white-space: nowrap;">
                  <thead>
                    <tr class="info">
                      <th>No.</th>
                      <th>Tanggal</th>
                      <th>Pelanggaran</th>
                      <th>Tindakan</th>
                      <th>Poin</th>
                      <th>Catatan</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $tampil = mysqli_query($conn, "SELECT * FROM siswa_konseling WHERE siswa = '$siswa[idSiswa]' AND tahunAjaran='$_GET[thn_ajar]' AND stdel='0'");
                    $no = 1;
                    while ($r = mysqli_fetch_array($tampil)) {
                      echo '<tr>
                                    <td>' . $no++ . '</td>
                                    <td>' . $r['tanggal'] . '</td>
                                    <td>' . $r['pelanggaran'] . '</td>
                                    <td>' . $r['tindakan'] . '</td>
                                    <td>' . $r['poin'] . '</td>
                                    <td>' . $r['catatan'] . '</td>
                                    <td>
                                    <a href="#del' . $r['idKonseling'] . '" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="" data-original-title="Hapus"></i></a>
                                    
                                    </td>
                                  </tr>';

                      echo '<div class="modal modal-default fade" id="del' . $r['idKonseling'] . '">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span></button>
                                          <h3 class="modal-title"><span class="fa fa-warning"></span> Konfirmasi penghapusan</h3>
                                        </div>
                                        <div class="modal-body">
                                          <p>Apakah anda yakin akan menghapus data ini?</p>
                                        </div>
                                        <div class="modal-footer">
                                          <form action="?view=' . $_GET['view'] . '&thn_ajar=' . $_GET['thn_ajar'] . '&nis=' . $_GET['nis'] . '" method="post" accept-charset="utf-8">
                                            <input type="hidden" name="idKonseling" value="' . $r['idKonseling'] . '"> 
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                            <button type="submit" name="hapus" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
                                          </form>
                                        </div>
                                      </div>
                                    </div>
                                  </div>';
                      echo '<div class="modal modal-default fade" id="lihat' . $r['idKonseling'] . '">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">×</span></button>
                                          <h3 class="modal-title">Surat Pernyataan Siswa</h3>
                                        </div>
                                        <div class="modal-body">
                                        <embed src="./gambar/' . $r['file'] . '" type="application/pdf" width="560" height="400">
                                        </div>
                                        <div class="modal-footer">
                                          <form action="?view=' . $_GET['view'] . '&thn_ajar=' . $_GET['thn_ajar'] . '&nis=' . $_GET['nis'] . '" method="post" accept-charset="utf-8">
                                            <input type="hidden" name="idKonseling" value="' . $r['idKonseling'] . '"> 
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Tutup</button>

                                          </form>
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

            <div class="tab-pane" id="tab_3">
              <div class="box-body table-responsive">
                <table class="table table-bordered" style="white-space: nowrap;">
                  <thead>
                    <tr class="info">
                      <th>No.</th>
                      <th>NIS</th>
                      <th>Nama Siswa</th>
                      <th>Kelas</th>
                      <th>Total Poin</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $tampil = mysqli_query($conn, "SELECT sum(siswa_konseling.poin) as total_poin ,
                    view_detil_siswa.nmSiswa,view_detil_siswa.nmKelas,view_detil_siswa.nisSiswa
                            FROM siswa_konseling
                             INNER JOIN view_detil_siswa ON siswa_konseling.siswa = view_detil_siswa.idSiswa 
                            WHERE siswa_konseling.siswa = '$siswa[idSiswa]' AND siswa_konseling.tahunAjaran='$_GET[thn_ajar]' AND siswa_konseling.stdel='0' GROUP BY siswa_konseling.siswa ");
                    $no = 1;
                    while ($r = mysqli_fetch_array($tampil)) {
                      echo '<tr>
                                    <td>' . $no++ . '</td>
                                    <td>' . $r['nisSiswa'] . '</td>
                                    <td>' . $r['nmSiswa'] . '</td>
                                   
                                    <td>' . $r['nmKelas'] . '</td>
                                    <td>' . $r['total_poin'] . ' Poin</td>
                                  </tr>';
                    }
                    ?>

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


<?php } ?>