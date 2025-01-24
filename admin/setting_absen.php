<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-warning">
    
      <div class="table-responsive">
       
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
        }
        ?>
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Waktu Masuk</th>
                <th>Terlambat</th>
                <th>Waktu Pulang</th>
                <th>Batas Waktu Pulang</th>

                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $tampil = mysqli_query($conn, "SELECT * FROM rb_setting_absen ORDER BY id ASC");
              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr><td>$no</td>
                                              <td>$r[waktu_terlambat]</td>

                              <td>$r[waktu_masuk]</td>
                               <td>$r[waktu_pulang]</td>
                                <td>$r[waktu_batas_pulang]</td>
                              "; ?>
                <!-- <td>
                  <a data-toggle="tooltip" data-placement="top" title="Ubah" style="margin-right:5px" class="btn btn-primary btn-xs" href="index.php?view=inv&kelas=<?php echo $r['id']; ?>">
                    Setting Inventaris Kelas
                  </a>
                  
                  <a class=" btn btn-info  btn-xs" target="_blank" href="./cetak_laporan_barang_kelas.php?kelas=<?php echo $r['id']; ?>"><span class="fa fa-print"></span> Cetak Data Inventaris Kelas</a>
            
                  <a class=" btn btn-success  btn-xs" target="_blank" href="./excel_laporan_barang_kelas.php?kelas=<?php echo $r['id']; ?>"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
            
                </td> -->
              <?php echo "
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=setting_absen&act=edit&id=$r[id]'><span class='glyphicon glyphicon-edit'></span></a>
                              </center></td>";
                echo "</tr>";
                $no++;
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

    $query = mysqli_query($conn, "UPDATE rb_setting_absen SET waktu_masuk='$_POST[waktu_masuk]', waktu_terlambat='$_POST[waktu_terlambat]',waktu_pulang='$_POST[waktu_pulang]',waktu_batas_pulang='$_POST[waktu_batas_pulang]'
										 where id = '$_POST[id]'");
    if ($query) {
      echo "<script>document.location='index.php?view=setting_absen&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=setting_absen&gagal';</script>";
    }
  }
  $edit = mysqli_query($conn, "SELECT * FROM rb_setting_absen where id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Data Waktu</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="post" action="" class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"> Waktu Masuk</label>
              <div class="col-sm-4">
                  <input type="text" name="waktu_terlambat" class="form-control" value="<?php echo $record['waktu_terlambat']; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Terlambat</label>
              <div class="col-sm-4">
                                <input type="text" name="waktu_masuk" class="form-control" value="<?php echo $record['waktu_masuk']; ?>" required>

              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"> Waktu Pulang</label>
              <div class="col-sm-4">
                <input type="text" name="waktu_pulang" class="form-control" value="<?php echo $record['waktu_pulang']; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Waktu Batas Pulang</label>
              <div class="col-sm-4">
                <input type="text" name="waktu_batas_pulang" class="form-control" value="<?php echo $record['waktu_batas_pulang']; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="update" value="Update" class="btn btn-success">
                <a href="index.php?view=setting_absen" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    $query = mysqli_query($conn, "INSERT INTO rb_setting_absen(waktu_masuk,waktu_absen) VALUES('$_POST[waktu_masuk]','$_POST[waktu_absen]')");
    if ($query) {
      echo "<script>document.location='index.php?view=setting_absen&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=setting_absen&gagal';</script>";
    }
  }
  ?>
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"> Tambah Data Waktu </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Waktu Masuk</label>
              <div class="col-sm-4">
                <input type="text" name="waktu_absen" class="form-control" id="" placeholder="Batas Waktu Masuk" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Terlambat</label>
              <div class="col-sm-4">
                <input type="text" name="waktu_masuk" class="form-control" id="" placeholder="Terlambat" required>
              </div>
            </div>
            
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                <a href="index.php?view=setting_absen" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
}
  ?>