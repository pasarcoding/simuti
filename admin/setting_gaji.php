<?php if ($_GET['act'] == '') {
  if (isset($_GET['tahun']) && $_GET['tahun'] != '') {
    $tampil = mysqli_query($conn, "SELECT * FROM jenis_gaji WHERE idTahunAjaran='$_GET[tahun]' ORDER BY id ASC");
    $tahun = $_GET['tahun'];
  } elseif (isset($_GET['tahun']) && $_GET['tahun'] == '') {
    $tampil = mysqli_query($conn, "SELECT * FROM jenis_gaji ");
  } else {
    $sqlTahunAktif = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE aktif='Y'");
    $tahunaktif = mysqli_fetch_array($sqlTahunAktif);
    $tahun = $tahunaktif['idTahunAjaran'];
    $tampil = mysqli_query($conn, "SELECT * FROM jenis_gaji 
    LEFT JOIN tahun_ajaran ON jenis_gaji.idTahunAjaran=tahun_ajaran.idTahunAjaran
   ORDER BY id ASC");
  }
?>

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

  <div class="col-xs-8">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Setting Gaji </h3>
      </div><!-- /.box-header -->
      <div class="box-body">

        <table class="table table-striped">
          <tbody>
            <tr>
              <td>
                <span class="pull-right">
                  <a class='pull-right btn btn-primary' href='index.php?view=setting_gaji&act=tambah'>Tambahkan Data</a>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Gaji</th>
                <th>Tahun</th>
                <th>Jenis</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr><td>$no</td>
                <td>$r[nmGaji]</td>
                <td>$r[nmTahunAjaran]</td>
                <td>$r[jenis]</td>";
              ?>
                <td>
                  <?php if ($r['jenis'] == 'pokok') { ?>
                    <a data-toggle="tooltip" data-placement="top" title="Ubah" style="margin-right:5px" class="btn btn-primary btn-xs" href="index.php?view=set_tarif_gaji&jenis=<?php echo $r['id'] . "&tipe=" . $r['jenis']; ?>">
                      Setting Gaji
                    </a>
                  <?php } ?>
                <?php echo "
                      <a class='btn btn-success btn-xs' title='Edit Data' href='?view=setting_gaji&act=edit&id=$r[id]'><span class='glyphicon glyphicon-edit'></span></a>
                      <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=setting_gaji&hapus&id=$r[id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                    </td>";
                echo "</tr>";
                $no++;
              }
              if (isset($_GET['hapus'])) {
                $query = mysqli_query($conn, "DELETE FROM jenis_gaji where id='$_GET[id]'");
                if ($query) {
                  echo "<script>document.location='index.php?view=setting_gaji&sukseshapus';</script>";
                } else {
                  echo "<script>document.location='index.php?view=setting_gaji&gagalhapus';</script>";
                }
              }

                ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  </div>

  <div class="col-xs-4">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Setting Potongan </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <table class="table table-striped">
          <tbody>
            <tr>
              <td>
                <span class="pull-right">
                  <a class='pull-right btn btn-primary' href='index.php?view=setting_gaji&act=tambah_potongan'>Tambahkan Data</a>
                </span>
              </td>
            </tr>
          </tbody>
        </table>
        <div class="table-responsive">
          <table id="example2" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Gaji</th>
                <th>Tahun</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $sqlTahunAktif = mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE aktif='Y'");
              $tahunaktif = mysqli_fetch_array($sqlTahunAktif);
              $tahun = $tahunaktif['idTahunAjaran'];
              $tampil = mysqli_query($conn, "SELECT * FROM jenis_potongan 
              INNER JOIN tahun_ajaran ON jenis_potongan.idTahunAjaran=tahun_ajaran.idTahunAjaran
               ORDER BY id ASC");
              while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr><td>$no</td>
                <td>$r[nmPotongan]</td>
                <td>$r[nmTahunAjaran]</td>";
              ?>
                <td>
                <?php echo "
                      <a class='btn btn-success btn-xs' title='Edit Data' href='?view=setting_gaji&act=edit_potongan&id=$r[id]'><span class='glyphicon glyphicon-edit'></span></a>
                      <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=setting_gaji&hapus_potongan&id=$r[id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                    </td>";
                echo "</tr>";
                $no++;
              }
              if (isset($_GET['hapus_potongan'])) {
                $query = mysqli_query($conn, "DELETE FROM jenis_potongan where id='$_GET[id]'");
                if ($query) {
                  echo "<script>document.location='index.php?view=setting_gaji&sukseshapus';</script>";
                } else {
                  echo "<script>document.location='index.php?view=setting_gaji&gagalhapus';</script>";
                }
              }

                ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  </div>
<?php
} elseif ($_GET['act'] == 'edit') {
  if (isset($_POST['update'])) {

    $query = mysqli_query($conn, "UPDATE jenis_gaji SET idTahunAjaran='$_POST[idTahunAjaran]', 
     nmGaji='$_POST[nmGaji]',jenis='$_POST[jenis]',jumlah='$_POST[jumlah]' where id = '$_POST[id]'");
    if ($query) {
      echo "<script>document.location='index.php?view=setting_gaji&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=setting_gaji&gagal';</script>";
    }
  }
  $edit = mysqli_query($conn, "SELECT * FROM jenis_gaji where id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
?>
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Edit Data Jenis Pembayaran</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="post" action="" class="form-horizontal">
          <input type="hidden" name="id" value="<?php echo $record['id']; ?>" readonly>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Gaji</label>
            <div class="col-sm-6">
              <input type="text" name="nmGaji" class="form-control" value="<?php echo $record['nmGaji']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Tahun</label>
            <div class="col-sm-4">
              <select name="idTahunAjaran" class="form-control">
                <?php
                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
                while ($t = mysqli_fetch_array($sqltahun)) {
                  $selected = ($t['aktif'] == 'Y') ? ' selected="selected"' : "";
                  echo "<option value=" . $t['idTahunAjaran'] . " " . $selected . ">" . $t['nmTahunAjaran'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Jenis Gaji</label>
            <div class="col-sm-4">
              <select class="form-control" name="jenis">
                <option value="<?= $record['jenis']; ?>" selected><?= $record['jenis']; ?></option>
                <option value="pokok" >Pokok</option>
                <option value="tunjangan">Tunjangan</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">jumlah</label>
            <div class="col-sm-6">
              <input type="text" name="jumlah" class="form-control" value="<?php echo $record['jumlah']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="update" value="Update" class="btn btn-success">
              <a href="index.php?view=setting_gaji" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
} elseif ($_GET['act'] == 'edit_potongan') {
  if (isset($_POST['update'])) {

    $query = mysqli_query($conn, "UPDATE jenis_potongan SET idTahunAjaran='$_POST[idTahunAjaran]', 
     nmPotongan='$_POST[nmPotongan]',jumlah='$_POST[jumlah]' where id = '$_POST[id]'");
    if ($query) {
      echo "<script>document.location='index.php?view=setting_gaji&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=setting_gaji&gagal';</script>";
    }
  }
  $edit = mysqli_query($conn, "SELECT * FROM jenis_potongan where id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
?>
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Edit Data Jenis Potongan</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="post" action="" class="form-horizontal">
          <input type="hidden" name="id" value="<?php echo $record['id']; ?>" readonly>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Potongan</label>
            <div class="col-sm-6">
              <input type="text" name="nmPotongan" class="form-control" value="<?php echo $record['nmPotongan']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Tahun</label>
            <div class="col-sm-4">
              <select name="idTahunAjaran" class="form-control">
                <?php
                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
                while ($t = mysqli_fetch_array($sqltahun)) {
                  $selected = ($t['aktif'] == 'Y') ? ' selected="selected"' : "";
                  echo "<option value=" . $t['idTahunAjaran'] . " " . $selected . ">" . $t['nmTahunAjaran'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">jumlah</label>
            <div class="col-sm-6">
              <input type="text" name="jumlah" class="form-control" value="<?php echo $record['jumlah']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="update" value="Update" class="btn btn-success">
              <a href="index.php?view=setting_gaji" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
} elseif ($_GET['act'] == 'tambah') {
  if (isset($_POST['tambah'])) {
    $query = mysqli_query($conn, "INSERT INTO jenis_gaji(idTahunAjaran,nmGaji,jenis,jumlah) VALUES('$_POST[idTahunAjaran]','$_POST[nmGaji]','$_POST[jenis]','$_POST[nomial]')");
    if ($query) {
      echo "<script>document.location='index.php?view=setting_gaji&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=setting_gaji&gagal';</script>";
    }
  }
?>
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Tambah Data Gaji </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="POST" action="" class="form-horizontal">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Gaji</label>
            <div class="col-sm-6">
              <input type="text" name="nmGaji" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Tahun</label>
            <div class="col-sm-4">
              <select name="idTahunAjaran" class="form-control">
                <?php
                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
                while ($t = mysqli_fetch_array($sqltahun)) {
                  $selected = ($t['aktif'] == 'Y') ? ' selected="selected"' : "";
                  echo "<option value=" . $t['idTahunAjaran'] . " " . $selected . ">" . $t['nmTahunAjaran'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Jenis Gaji</label>
            <div class="col-sm-4">
              <select class="form-control" name="jenis">
                <option value="pokok" selected>Pokok</option>
                <option value="tunjangan">Tunjangan</option>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">jumlah</label>
            <div class="col-sm-6">
              <input type="text" name="jumlah" class="form-control" value="">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
              <a href="index.php?view=setting_gaji" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
} elseif ($_GET['act'] == 'tambah_potongan') {
  if (isset($_POST['tambah'])) {
    $query = mysqli_query($conn, "INSERT INTO jenis_potongan(idTahunAjaran,nmPotongan,jumlah) VALUES('$_POST[idTahunAjaran]','$_POST[nmPotongan]','$_POST[jumlah]')");
    if ($query) {
      echo "<script>document.location='index.php?view=setting_gaji&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=setting_gaji&gagal';</script>";
    }
  }
?>
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Tambah Data Jenis Potongan </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="POST" action="" class="form-horizontal">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Potongan</label>
            <div class="col-sm-6">
              <input type="text" name="nmPotongan" class="form-control">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Tahun</label>
            <div class="col-sm-4">
              <select name="idTahunAjaran" class="form-control">
                <?php
                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran ASC");
                while ($t = mysqli_fetch_array($sqltahun)) {
                  $selected = ($t['aktif'] == 'Y') ? ' selected="selected"' : "";
                  echo "<option value=" . $t['idTahunAjaran'] . " " . $selected . ">" . $t['nmTahunAjaran'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">jumlah</label>
            <div class="col-sm-6">
              <input type="text" name="jumlah" class="form-control" value="<?php echo $record['jumlah']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
              <a href="index.php?view=setting_gaji" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
}
?>