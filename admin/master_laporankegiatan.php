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
              <label>Kelas</label>
              <input type="hidden" id="idKelas" value="<?= $_GET[kelas] ?>">
              <input type="hidden" id="tipe_kelas" value="semuaKelas">
              <select id="Ckelas" name="kelas" class="form-control" required>
                <option disabled selected>- Pilih Kelas -</option>
              </select>
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

<?php if (isset($_GET['tgl_mulai']) && isset($_GET['tgl_akhir']) && isset($_GET['thn_ajar'])  && isset($_GET['kelas'])) {  ?>


  <?php
  $tgl_mulai = $_GET['tgl_mulai'];
  $tgl_akhir = $_GET['tgl_akhir'];
  $idTahunAjaran = $_GET['thn_ajar'];

  $idKelas = $_GET['kelas'];


  $ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$idTahunAjaran'"));
  if ($idKelas != 'all') {
    $kls = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM kelas_siswa WHERE idKelas='$idKelas'"));
    $kelas = 'Kelas ' . $kls['nmKelas'];
  } else {
    $kelas = 'Semua Kelas';
  }


  ?>

  <div class="col-md-12">
    <div class="box box-primary box-solid">
      <div class="box-header with-border">
        <h3 class="box-title"><span class="fa fa-file-text-o"></span> Rekapitulasi Prestasi dan Kegiatan SIswa <?= $kelas ?> T.A. <?= $ta['nmTahunAjaran'] ?> Tanggal <?= tgl_miring($tgl_mulai) ?> Sampai <?= tgl_miring($tgl_akhir) ?></h3>
      </div>
      <div class="box-body table-responsive">
        <table id="example1" class="table table-hover table-bordered dataTable no-footer text-center" style="white-space: nowrap;">
          <thead>
            <tr>
              <th>No</th>
              <th>NIS</th>
              <th>Nama Siswa</th>
              <th>Kelas</th>
              <th>Cetak</th>
            </tr>
          </thead>
          <tbody>
            <?php

            if ($idKelas == 'all') {
              $sql_konseling = mysqli_query($conn, "SELECT  * FROM view_detil_siswa ");
            } else {
              $sql_konseling = mysqli_query($conn, "SELECT  * FROM view_detil_siswa  WHERE view_detil_siswa.idKelas='$idKelas'");
            }
            $no = 1;
            while ($konseling = mysqli_fetch_array($sql_konseling)) {
              echo '<tr>
                          <td>' . $no++ . '</td>
                          <td>' . $konseling['nisSiswa'] . '</td>
                          <td>' . $konseling['nmSiswa'] . '</td>
                          <td>' . $konseling['nmKelas'] . '</td>'; ?>
              <td> <a class="btn btn-success" target="_blank" href="cetak_laporan_kesiswaan.php?tgl_mulai=<?= $_GET[tgl_mulai] ?>&tgl_akhir=<?= $_GET[tgl_akhir] ?>&thn_ajar=<?= $_GET[thn_ajar] ?>&siswa=<?= $konseling[idSiswa] ?>"><span class="fa fa-print"></span> Cetak </a>
              </td>
            <?php echo '</tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
      
    </div>
  </div>

<?php } ?>