<?php if ($_GET['act'] == '') { ?>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Data Sumber Dana </h3>
                <a class='pull-right btn btn-primary btn-sm' href='?view=inv_sumber_dana&act=tambah'>Tambahkan Data</a>
            </div><!-- /.box-header -->
            <div class="box-body">
                <div class="table-responsive">
                    <?php
                    if (isset($_GET['sukses'])) {
                        echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses..
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
                                <th>Kode Sumber Dana</th>
                                <th>Nama Sumber Dana</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tampil = mysqli_query($conn, "SELECT * FROM inv_sumber_dana ORDER BY idSumberDana ASC");
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                                echo "<tr><td>$no</td>
                              <td>$r[kodeSumberDana]</td>
                              <td>$r[nmSumberDana]</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=inv_sumber_dana&act=edit&id=$r[idSumberDana]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=inv_sumber_dana&hapus&id=$r[idSumberDana]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                                echo "</tr>";
                                $no++;
                            }

                            if (isset($_GET['hapus'])) {
                                mysqli_query($conn, "DELETE FROM inv_sumber_dana WHERE idSumberDana='$_GET[id]'");
                                echo "<script>document.location='?view=inv_sumber_dana';</script>";
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

        $query = mysqli_query($conn, "UPDATE inv_sumber_dana SET kodeSumberDana='$_POST[kodeSumberDana]', 
                                            nmSumberDana='$_POST[nmSumberDana]' where idSumberDana = '$_POST[id]'");
        if ($query) {
            echo "<script>document.location='?view=inv_sumber_dana&sukses';</script>";
        } else {
            echo "<script>document.location='?view=inv_sumber_dana&gagal';</script>";
        }
    }
    $edit = mysqli_query($conn, "SELECT * FROM inv_sumber_dana where idSumberDana='$_GET[id]'");
    $record = mysqli_fetch_array($edit);
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Edit Data Sumber Dana</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="post" action="" class="form-horizontal">
                        <input type="hidden" name="id" value="<?php echo $record['idSumberDana']; ?>">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kode Sumber Dana</label>
                            <div class="col-sm-4">
                                <input type="text" name="kodeSumberDana" class="form-control" value="<?php echo $record['kodeSumberDana']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Sumber Dana</label>
                            <div class="col-sm-6">
                                <input type="text" name="nmSumberDana" class="form-control" value="<?php echo $record['nmSumberDana']; ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="update" value="Update" class="btn btn-success">
                                <a href="?view=inv_sumber_dana" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <?php
} elseif ($_GET['act'] == 'tambah') {
    if (isset($_POST['tambah'])) {
        $query = mysqli_query($conn, "INSERT INTO inv_sumber_dana(kodeSumberDana, nmSumberDana) VALUES('$_POST[kodeSumberDana]', '$_POST[nmSumberDana]')");
        if ($query) {
            echo "<script>document.location='?view=inv_sumber_dana&sukses';</script>";
        } else {
            echo "<script>document.location='?view=inv_sumber_dana&gagal';</script>";
        }
    }
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Tambah Data Sumber Dana </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="" class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kode Sumber Dana</label>
                            <div class="col-sm-4">
                                <input type="text" name="kodeSumberDana" class="form-control" id="" placeholder="Kode Sumber Dana" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Sumber Dana</label>
                            <div class="col-sm-6">
                                <input type="text" name="nmSumberDana" class="form-control" id="" placeholder="Nama Sumber Dana">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                                <a href="?view=inv_sumber_dana" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
}
?>
