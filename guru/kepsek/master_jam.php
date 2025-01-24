<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-warning">
      <div class="box-header with-border">


      </div><!-- /.box-header -->
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

              <th>Nama Jam</th>
              <th>Dari</th>
              <th>Sampai</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($conn, "SELECT * FROM jam ORDER BY idJam ASC");
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              echo "<tr><td>$no</td>
                           
                              <td>$r[nmJam]</td>
                              <td>$r[dariJam]</td>
                              <td>$r[sampaiJam]</td>"; ?>
              <!-- <td>
                  <a data-toggle="tooltip" data-placement="top" title="Ubah" style="margin-right:5px" class="btn btn-primary btn-xs" href="index.php?view=inv&kelas=<?php echo $r['idJam']; ?>">
                    Setting Inventaris Kelas
                  </a>
                  
                  <a class=" btn btn-info  btn-xs" target="_blank" href="./cetak_laporan_barang_kelas.php?kelas=<?php echo $r['idJam']; ?>"><span class="fa fa-print"></span> Cetak Data Inventaris Kelas</a>
            
                  <a class=" btn btn-success  btn-xs" target="_blank" href="./excel_laporan_barang_kelas.php?kelas=<?php echo $r['idJam']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
            
                </td> -->
            <?php echo "
                             ";
              echo "</tr>";
              $no++;
            }
            if (isset($_GET[hapus])) {
              mysqli_query($conn, "DELETE FROM jam where idJam='$_GET[id]'");
              echo "<script>document.location='index.php?view=jam';</script>";
            }

            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
<?php
} elseif ($_GET[act] == 'edit') {
  if (isset($_POST[update])) {

    $query = mysqli_query($conn, "UPDATE jam SET nmJam='$_POST[nmJam]', 
										 dariJam='$_POST[dariJam]', sampaiJam='$_POST[sampaiJam]'  where idJam = '$_POST[id]'");
    if ($query) {
      echo "<script>document.location='index.php?view=jam&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=jam&gagal';</script>";
    }
  }
  $edit = mysqli_query($conn, "SELECT * FROM jam where idJam='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
?>
  <div class="col-md-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"> Edit Data Jam</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="post" action="" class="form-horizontal">
          <input type="hidden" name="id" value="<?php echo $record['idJam']; ?>">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Jam</label>
            <div class="col-sm-4">
              <input type="text" name="nmJam" class="form-control" value="<?php echo $record['nmJam']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Dari Jam</label>
            <div class="col-sm-6">
              <input type="time" name="dariJam" class="form-control" value="<?php echo $record['dariJam']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Sampai Jam</label>
            <div class="col-sm-6">
              <input type="time" name="sampaiJam" class="form-control" value="<?php echo $record['sampaiJam']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="update" value="Update" class="btn btn-success">
              <a href="index.php?view=jam" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    $query = mysqli_query($conn, "INSERT INTO jam(nmJam,dariJam,sampaiJam) VALUES('$_POST[nmJam]','$_POST[dariJam]','$_POST[sampaiJam]')");
    if ($query) {
      echo "<script>document.location='index.php?view=jam&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=jam&gagal';</script>";
    }
  }
?>
  <div class="col-md-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"> Tambah Data Jam </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="POST" action="" class="form-horizontal">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Jam</label>
            <div class="col-sm-4">
              <input type="text" name="nmJam" class="form-control" id="" placeholder="Nama Jam" required>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Dari Jam</label>
            <div class="col-sm-6">
              <input type="time" name="dariJam" class="form-control" id="">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Sampai Jam</label>
            <div class="col-sm-6">
              <input type="time" name="sampaiJam" class="form-control" id="">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
              <a href="index.php?view=jam" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
}
?>