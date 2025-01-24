<?php if ($_GET[act] == '') { ?>

  <div class="col-md-12">
    <div class="box box-success">
      <div class="box-header">
        <form method="GET">

          <input type="hidden" name="view" value="<?= $_GET[view] ?>">
          <div class="row">
            <div class="col-md-2">
              <label>Dari tgl</label>
              <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input class="form-control" type="text" name="tgl_mulai" readonly="readonly" placeholder="tgl Awal" value="<?= $_GET['tgl_mulai'] ?>">
              </div>
            </div>
            <div class="col-md-2">
              <label>Sampai tgl</label>
              <div class="input-group date date-picker" data-date="" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
                <input class="form-control" type="text" name="tgl_akhir" readonly="readonly" placeholder="Sampai tgl" value="<?= $_GET['tgl_akhir'] ?>">
              </div>
            </div>

            <div class="col-md-2">
              <label>Tahun Ajaran</label>
              <input type="hidden" id="idTahunAjaran" value="<?= $_GET[thn_ajar] ?>">
              <select class="form-control" name="thn_ajar" id="Ctahunajaran" required=""></select>
            </div>


            <div class="col-md-2">
              <div style="margin-top:25px;">
                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i>Cari</button>
              </div>
            </div>
          </div>
          <br>

        </form>
      </div>
    </div>
  </div>
<?php } ?>

<?php if (isset($_GET['tgl_mulai']) && isset($_GET['tgl_akhir']) && isset($_GET['thn_ajar'])) {  ?>


  <?php
  $tgl_mulai = $_GET['tgl_mulai'];
  $tgl_akhir = $_GET['tgl_akhir'];
  $idTahunAjaran = $_GET['thn_ajar'];



  $ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$idTahunAjaran'"));



  ?>

  <div class="col-md-12">
    <div class="box box-primary box-solid">
      <div class="box-header with-border">
        <h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Prestasi PTK T.A. <?= $ta['nmTahunAjaran'] ?> tgl <?= tgl_miring($tgl_mulai) ?> Sampai <?= tgl_miring($tgl_akhir) ?></h3>
      </div>
      <div class="box-body table-responsive">
        <table id="example1" class="table table-hover table-bordered dataTable no-footer text-center" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>NBM</th>
              <th>Nama PTK</th>
              <th>Semester</th>
              <th>Nama Prestasi</th>
              <th>Tingkat</th>
              <th>Juara</th>
            </tr>
          </thead>
          <tbody>
            <?php

            $sql_konseling = mysqli_query($conn, "SELECT rb_prestasi_ptk.*, rb_guru.* FROM rb_prestasi_ptk LEFT JOIN rb_guru ON rb_prestasi_ptk.id=rb_guru.id  WHERE DATE(rb_prestasi_ptk.tgl) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND rb_prestasi_ptk.tahunAjaran='$idTahunAjaran' AND rb_prestasi_ptk.stdel='0'   ORDER BY rb_prestasi_ptk.tgl ASC");

            $no = 1;
            while ($konseling = mysqli_fetch_array($sql_konseling)) {
              echo '<tr>
                          <td>' . $no++ . '</td>
                          <td>' . tgl_miring($konseling['tgl']) . '</td>
                          <td>' . $konseling['nbm'] . '</td>
                          <td>' . $konseling['nama_guru'] . '</td>
                         
                          <td>' . $konseling['semester'] . '</td>
                          <td>' . $konseling['nmPrestasi'] . '</td>
                          <td>' . $konseling['tingkatPrestasi'] . '</td>
                          <td>' . $konseling['juaraPrestasi'] . '</td>
                         
                        </tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer">
        <div class="pull-right">
          <a class="btn btn-success" target="_blank" href="admin/excel/export_laporan_prestasi_ptk.php?tgl_mulai=<?= $_GET[tgl_mulai] ?>&tgl_akhir=<?= $_GET[tgl_akhir] ?>&thn_ajar=<?= $_GET[thn_ajar] ?>"><span class="fa fa-file-excel-o"></span> Cetak Excel</a>

        </div>
      </div>
    </div>
  </div>

<?php } ?>