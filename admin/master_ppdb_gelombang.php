<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data Gelombang Pendaftaran </h3>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?view=ppdb_gelombang&act=tambah'>Tambahkan Data</a>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
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
          }
          ?>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Gelombang Pendaftaran</th>
                <th>Tahun Ajaran</th>
                <th>Biaya</th>
                <th>Mulai - Selesai</th>
                <th>Kuota</th>
                <th>Status</th> <!-- Tambahkan kolom Status -->
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Ambil tanggal hari ini
              $today = date('Y-m-d');

              $tampil = mysqli_query($conn, "SELECT * FROM ppdb_gelombang
      INNER JOIN tahun_ajaran ON ppdb_gelombang.idTahunAjaran=tahun_ajaran.idTahunAjaran
      ORDER BY ppdb_gelombang.idGlombang ASC");
              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
                // Tentukan status berdasarkan rentang tanggal
                $status = (strtotime($today) >= strtotime($r['mulai']) && strtotime($today) <= strtotime($r['selesai'])) ? 'Aktif' : 'Tidak Aktif';

                echo "<tr><td>$no</td>
              <td>$r[nmGelombang]</td>
              <td>$r[nmTahunAjaran]</td>
              <td>" . buatRp($r['biaya']) . "</td>
              <td>" . tgl_indo($r['mulai']) . " - " . tgl_indo($r['selesai']) . " </td>
               <td>$r[kuota]</td>
              <td><span class='label label-" . ($status == 'Aktif' ? 'success' : 'danger') . "'>$status</span></td> <!-- Kolom Status -->
              <td><center>
                <a class='btn btn-warning btn-xs' title='Edit Data' href='?view=ppdb_gelombang&act=edit&id=$r[idGlombang]'>
                  <span class='glyphicon glyphicon-edit'></span>
                </a>
                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=ppdb_gelombang&hapus&id=$r[idGlombang]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                  <span class='glyphicon glyphicon-remove'></span>
                </a>
              </center></td>";
                echo "</tr>";
                $no++;
              }

              if (isset($_GET['hapus'])) {
                mysqli_query($conn, "DELETE FROM ppdb_gelombang WHERE idGlombang='$_GET[id]'");
                echo "<script>document.location='index.php?view=ppdb_gelombang';</script>";
              }
              ?>
            </tbody>
          </table>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  <?php
} elseif ($_GET['act'] == 'edit') {
  if (isset($_POST['update'])) {

    $query = mysqli_query($conn, "UPDATE ppdb_gelombang SET idTahunAjaran='$_POST[idTahunAjaran]', nmGelombang='$_POST[nmGelombang]', biaya='$_POST[biaya]', mulai='$_POST[mulai]', selesai='$_POST[selesai]', kuota='$_POST[kuota]'  where idGlombang = '$_POST[id]'");
    if ($query) {
      echo "<script>document.location='index.php?view=ppdb_gelombang&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=ppdb_gelombang&gagal';</script>";
    }
  }
  $edit = mysqli_query($conn, "SELECT * FROM ppdb_gelombang where idGlombang='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Data Gelombang Pendaftaran</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="post" action="" class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo $record['idGlombang']; ?>">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Tahun Ajaran</label>
              <div class="col-sm-8">
                <select name="idTahunAjaran" class="form-control">
                  <?php
                  $sqk = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
                  while ($k = mysqli_fetch_array($sqk)) {
                    $selected = ($k['idTahunAjaran'] == $record['idTahunAjaran']) ? ' selected="selected"' : "";

                    echo '<option value="' . $k['idTahunAjaran'] . '" ' . $selected . '>' . $k['nmTahunAjaran'] . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Gelombang Pendaftaran</label>
              <div class="col-sm-8">
                <input type="text" name="nmGelombang" class="form-control" value="<?php echo $record['nmGelombang']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Biaya Pendaftaran</label>
              <div class="col-sm-8">
                <input type="text" name="biaya" class="form-control" value="<?php echo $record['biaya']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Mulai </label>
              <div class="col-sm-8">
                <input type="text" name="mulai" class="form-control datepicker" value="<?php echo $record['mulai']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Selesai </label>
              <div class="col-sm-8">
                <input type="text" name="selesai" class="form-control datepicker" value="<?php echo $record['selesai']; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Kuota Pendaftaran</label>
              <div class="col-sm-8">
                <input type="number" name="kuota" class="form-control" value="<?php echo $record['kuota']; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="update" value="Update" class="btn btn-success">
                <a href="index.php?view=ppdb_gelombang" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php
} elseif ($_GET['act'] == 'tambah') {
  if (isset($_POST[tambah])) {
    $query = mysqli_query($conn, "INSERT INTO ppdb_gelombang(nmGelombang, idTahunAjaran, biaya, mulai, selesai, kuota) VALUES('$_POST[nmGelombang]','$_POST[idTahunAjaran]','$_POST[biaya]','$_POST[mulai]','$_POST[selesai]','$_POST[kuota]')");
    if ($query) {
      echo "<script>document.location='index.php?view=ppdb_gelombang&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=ppdb_gelombang&gagal';</script>";
    }
  }
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Tambah Data Gelombang Pendaftaran </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Tahun Ajaran</label>
              <div class="col-sm-8">
                <select name="idTahunAjaran" class="form-control">
                  <?php
                  $sqk = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
                  while ($k = mysqli_fetch_array($sqk)) {
                    $selected = ($k['idTahunAjaran'] == $record['idTahunAjaran']) ? ' selected="selected"' : "";

                    echo '<option value="' . $k['idTahunAjaran'] . '" ' . $selected . '>' . $k['nmTahunAjaran'] . '</option>';
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Gelombang Pendaftaran</label>
              <div class="col-sm-8">
                <input type="text" name="nmGelombang" class="form-control" value="<?php echo $record['nmGelombang']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Biaya Pendaftaran</label>
              <div class="col-sm-8">
                <input type="text" name="biaya" class="form-control" value="<?php echo $record['biaya']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Mulai </label>
              <div class="col-sm-8">
                <input type="text" name="mulai" class="form-control datepicker" value="<?php echo $record['mulai']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Selesai </label>
              <div class="col-sm-8">
                <input type="text" name="selesai" class="form-control datepicker" value="<?php echo $record['selesai']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Kuota Pendaftaran</label>
              <div class="col-sm-8">
                <input type="number" name="kuota" class="form-control" value="<?php echo $record['kuota']; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                <a href="index.php?view=ppdb_gelombang" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
}
  ?>