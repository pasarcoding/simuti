<?php if ($_GET[act] == '') { ?>

  <div class="col-md-12">
    <div class="box box-success">
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
        <h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Pembinaan PTK T.A. <?= $ta['nmTahunAjaran'] ?> Tanggal <?= tgl_miring($tgl_mulai) ?> Sampai <?= tgl_miring($tgl_akhir) ?></h3>
      </div>
      <div class="box-body table-responsive">
        <table id="example1" class="table table-hover table-bordered dataTable no-footer text-center" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>NBM</th>
              <th>Nama PTK</th>
              <th>Pembinaan</th>
              <th>Tindakan</th>
              <th>Poin</th>
              <th>Catatan</th>
            </tr>
          </thead>
          <tbody>
            <?php


            $sql_konseling = mysqli_query($conn, "SELECT guru_konseling.*, rb_guru.* FROM guru_konseling LEFT JOIN rb_guru ON guru_konseling.guru=rb_guru.id  WHERE DATE(guru_konseling.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND guru_konseling.tahunAjaran='$idTahunAjaran' AND guru_konseling.stdel='0'  ORDER BY guru_konseling.tanggal ASC");

            $no = 1;
            while ($konseling = mysqli_fetch_array($sql_konseling)) {
              echo '<tr>
                          <td>' . $no++ . '</td>
                          <td>' . tgl_miring($konseling['tanggal']) . '</td>
                          <td>' . $konseling['nbm'] . '</td>
                          <td>' . $konseling['nama_guru'] . '</td>
                         
                          <td>' . $konseling['pelanggaran'] . '</td>
                          <td>' . $konseling['tindakan'] . '</td>
                          <td>' . $konseling['poin'] . ' Poin</td>
                          <td>' . $konseling['catatan'] . '</td>
                        </tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="box-footer">
        <div class="pull-right">
          <a class="btn btn-success" target="_blank" href="admin/excel/export_laporan_pelanggaran_ptk.php?tgl_mulai=<?= $_GET[tgl_mulai] ?>&tgl_akhir=<?= $_GET[tgl_akhir] ?>&thn_ajar=<?= $_GET[thn_ajar] ?>"><span class="fa fa-file-excel-o"></span> Cetak Excel</a>

        </div>
      </div>
    </div>
  </div>

<?php } ?>