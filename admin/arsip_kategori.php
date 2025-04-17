<?php if ($_GET['act'] == '') { ?>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Kategori Arsip </h3>
                <a class='pull-right btn btn-primary btn-sm' href='index.php?view=arsip_kategori&act=tambah'>Tambahkan Data</a>
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
                                <th>Nama Kategori</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tampil = mysqli_query($conn, "SELECT * FROM arsip_kategori ORDER BY idKategori ASC");
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                                echo "<tr><td>$no</td>
                              <td>$r[nmKategori]</td>
                              <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=arsip_kategori&act=edit&id=$r[idKategori]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=arsip_kategori&hapus&id=$r[idKategori]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                                echo "</tr>";
                                $no++;
                            }

                            if (isset($_GET['hapus'])) {
                                mysqli_query($conn, "DELETE FROM arsip_kategori WHERE idKategori='$_GET[id]'");
                                echo "<script>document.location='index.php?view=arsip_kategori';</script>";
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

        $query = mysqli_query($conn, "UPDATE arsip_kategori SET 
                                            nmKategori='$_POST[nmKategori]' where idKategori = '$_POST[id]'");
        if ($query) {
            echo "<script>document.location='index.php?view=arsip_kategori&sukses';</script>";
        } else {
            echo "<script>document.location='index.php?view=arsip_kategori&gagal';</script>";
        }
    }
    $edit = mysqli_query($conn, "SELECT * FROM arsip_kategori where idKategori='$_GET[id]'");
    $record = mysqli_fetch_array($edit);
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Edit Data Kategori Arsip</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="post" action="" class="form-horizontal">
                        <input type="hidden" name="id" value="<?php echo $record['idKategori']; ?>">

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Kategori</label>
                                <div class="col-sm-6">
                                    <input type="text" name="nmKategori" class="form-control" value="<?php echo $record['nmKategori']; ?>">
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="update" value="Update" class="btn btn-success">
                                <a href="index.php?view=arsip_kategori" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
} elseif ($_GET['act'] == 'tambah') {
    if (isset($_POST['tambah'])) {
        $query = mysqli_query($conn, "INSERT INTO arsip_kategori(nmKategori) VALUES('$_POST[nmKategori]')");
        if ($query) {
            echo "<script>document.location='index.php?view=arsip_kategori&sukses';</script>";
        } else {
            echo "<script>document.location='index.php?view=arsip_kategori&gagal';</script>";
        }
    }
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Tambah Data Kategori Arsip </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="" class="form-horizontal">

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Kategori</label>
                            <div class="col-sm-6">
                                <input type="text" name="nmKategori" class="form-control" id="" placeholder="Nama Kategori Arsip">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                                <a href="index.php?view=arsip_kategori" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
}
    ?>