<?php $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$nmSekolah = $idt['nmSekolah'];
if ($_GET[act] == '') { ?>

  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Filter Data PTK</h3>
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
            <label for="" class="col-sm-2 control-label">Cari PTK</label>
            <div class="col-sm-4">
              <div class="input-group">
                <input type="text" class="form-control" autofocus="" name="id" id="student_nis" placeholder="NBM PTK" required="" value="<?= $_GET['nisn'] ?>">
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
                  <button type="button" class="btn btn-default" data-toggle="modal" data-target="#dataSantri"><b>Data PTK</b></button>
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
          <h4 class="modal-title">Cari Data PTK</h4>
        </div>
        <div class="modal-body">
          <div class="box-body table-responsive">
            <table id="example1" class="table table-hover dataTable no-footer">
              <thead>
                <tr>
                  <th>No</th>
                  <th>NBM</th>
                  <th>Nama PTK</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                <?php

                $tampil = mysqli_query($conn, "SELECT * FROM rb_guru ORDER BY id DESC");


                $no = 1;
                while ($r = mysqli_fetch_array($tampil)) {
                  echo "<tr>
                              <td>$no</td>
                              <td>$r[nbm]</td>
                              <td>$r[nama_guru]</td>
                              
                              <td>
                                <center><button type='button' data-dismiss='modal' class='btn btn-primary btn-xs' onclick=\"ambil_data('$r[id]')\">Pilih</button></center>
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
  function ambil_data(id) {
    var id = id;
    var thAjaran = $("#Ctahunajaran").val();

    window.location.href = 'index.php?view=<?= $_GET['view'] ?>&thn_ajar=' + thAjaran + '&id=' + id;
  }
</script>


<?php if (isset($_GET['thn_ajar']) && isset($_GET['id'])) {


  $siswa = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_guru  WHERE id='$_GET[id]'"));
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
        <h3 class="box-title">Informasi PTK</h3>
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
                <td>NBM</td>
                <td>:</td>
                <td><?= $siswa['nbm'] ?></td>
              </tr>
              <tr>
                <td>Nama PTK</td>
                <td>:</td>
                <td><?= $siswa['nama_guru'] ?></td>
              </tr>

            </tbody>
          </table>
        </div>

      </div>
    </div>

    <!-- List Tagihan Bulanan -->
    <div class="box box-success">
      <div class="box-header with-border">
        <h3 class="box-title">Prestasi PTK</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab_1" data-toggle="tab">Tambah Prestasi</a></li>
            <li><a href="#tab_2" data-toggle="tab">Laporan Prestasi PTK</a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab_1">
              <div class="box-body">
                <div class="row">
                  <?php
                  //simpan
                  if (isset($_POST['tambah'])) {
                    $query = mysqli_query($conn, "INSERT INTO rb_prestasi_ptk (id,tahunAjaran,semester,tgl,nmPrestasi,tingkatPrestasi,juaraPrestasi,stdel) VALUES ('$_POST[id]','$_POST[konseling_periode]','$_POST[semester]','$_POST[tgl]','$_POST[nmPrestasi]','$_POST[tingkatPrestasi]','$_POST[juaraPrestasi]','0')");


                    if ($query) {
                      $_SESSION['notif'] = 'csukses';
                      echo "<script>document.location='index.php?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&id=$_GET[id]';</script>";
                    } else {
                      $_SESSION['notif'] = 'gagal';
                      echo "<script>document.location='index.php?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&id=$_GET[id]';</script>";
                    }
                  }
                  ?>

                  <form action="index.php?view=<?= $_GET[view] ?>&thn_ajar=<?= $_GET[thn_ajar] ?>&id=<?= $_GET[id] ?>" method="post" accept-charset="utf-8">
                    <input type="hidden" name="konseling_periode" value="<?= $_GET['thn_ajar'] ?>">
                    <input type="hidden" name="id" value="<?= $siswa['id'] ?>">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Nama Prestasi</label>
                        <input type="text" class="form-control" required="" name="nmPrestasi" placeholder="Nama Prestasi">
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Semester </label>
                        <select class="form-control" name="semester">
                          <option value="Ganjil">Ganjil</option>
                          <option value="Genap">Genap</option>

                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label>Tanggal</label>
                        <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                          <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                          <input class="form-control md-3" required="" type="text" name="tgl" placeholder="Tanggal Prestasi" value="<?= $tanggal_sekarang ?>">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tingkat </label>
                        <select class="form-control" name="tingkatPrestasi">
                          <option value="Internasional">Internasional</option>
                          <option value="Nasional">Nasional</option>
                          <option value="Provinsi">Provinsi</option>
                          <option value="Kota">Kota</option>
                          <option value="Kecamatan">Kecamatan</option>
                          <option value="Zona">Zona</option>
                          <option value="Pusat (PP Muhammadiyah)">Pusat (PP Muhammadiyah)</option>
                          <option value="Wilayah (PW Muhammadiyah)">Wilayah (PW Muhammadiyah)</option>
                          <option value="Daerah (PD Muhammadiyah)">Daerah (PD Muhammadiyah)</option>
                          <option value="Cabang (PC Muhammadiyah)">Cabang (PC Muhammadiyah)</option>
                          <option value="Olympicad">Olympicad</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label for="" class="col-sm-2 control-label">Juara </label>
                      <select class="form-control" name="juaraPrestasi">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                      </select>
                    </div>
                </div>
              </div>
              <div class="col-md-6">
                <button type="submit" name="tambah" class="btn btn-success">Simpan</button>
                <button type="reset" class="btn btn-default">Kosongkan</button>
              </div>
              </form>
            </div>
            <div class="tab-pane" id="tab_2">
              <div class="box-body table-responsive">
                <?php
                //hapus
                if (isset($_POST['hapus'])) {
                  $query = mysqli_query($conn, " UPDATE rb_prestasi_ptk SET stdel='1' WHERE idPrestasi='$_POST[idPrestasi]'");
                  if ($query) {
                    $_SESSION['notif'] = 'dsukses';
                    echo "<script>document.location='index.php?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&id=$_GET[id]';</script>";
                  } else {
                    $_SESSION['notif'] = 'gagal';
                    echo "<script>document.location='index.php?view=$_GET[view]&thn_ajar=$_GET[thn_ajar]&id=$_GET[id]';</script>";
                  }
                }
                ?>
                <table class="table table-bordered" style="white-space: nowrap;">
                  <thead>
                    <tr class="info">
                      <th>No.</th>
                      <th>Tanggal</th>

                      <th>Semester</th>
                      <th>Prestasi</th>
                      <th>Tingkat</th>
                      <th>Juara</th>
                      <th>Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $tampil = mysqli_query($conn, "SELECT * 
                   
                    FROM rb_prestasi_ptk 
                    
                    WHERE id = '$siswa[id]' AND tahunAjaran='$_GET[thn_ajar]' AND stdel='0'");
                    $no = 1;
                    while ($r = mysqli_fetch_array($tampil)) {
                      echo '<tr>
                                    <td>' . $no++ . '</td>
                                    <td>' . $r['tgl'] . '</td>
                                   
                                    <td>' . $r['semester'] . '</td>
                                    <td>' . $r['nmPrestasi'] . '</td>
                                    <td>' . $r['tingkatPrestasi'] . '</td>
                                    <td>' . $r['juaraPrestasi'] . '</td>
                                    <td>
                                    <a href="#del' . $r['idPrestasi'] . '" data-toggle="modal" class="btn btn-xs btn-danger"><i class="fa fa-trash" data-toggle="tooltip" title="" data-original-title="Hapus"></i></a>
                                    </td>
                                  </tr>';

                      echo '<div class="modal modal-default fade" id="del' . $r['idPrestasi'] . '">
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
                                          <form action="index.php?view=' . $_GET['view'] . '&thn_ajar=' . $_GET['thn_ajar'] . '&id=' . $_GET['id'] . '" method="post" accept-charset="utf-8">
                                            <input type="hidden" name="idPrestasi" value="' . $r['idPrestasi'] . '"> 
                                            <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Batal</button>
                                            <button type="submit" name="hapus" class="btn btn-danger"><span class="fa fa-check"></span> Hapus</button>
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

          </div>
        </div>
      </div>
    </div>
  </div>


<?php } ?>