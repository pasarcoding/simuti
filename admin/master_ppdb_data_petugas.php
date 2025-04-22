<?php if ($_GET['act'] == '') { ?>

    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Data Petugas </h3>
                <a class='pull-right btn btn-primary btn-sm' href='index.php?view=data petugas&act=tambah'>Tambahkan Data</a>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <?php
                        if (isset($_SESSION['notif'])) {
                            if ($_SESSION['notif'] == 'success-create'){
                                echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah berhasil ditambahkan.
                                    </div>";
                            }elseif ($_SESSION['notif'] == 'success-update'){
                                echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah berhasil diperbarui.
                                    </div>";
                            }elseif ($_SESSION['notif'] == 'success-delete'){
                                echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah berhasil di hapus.
                                    </div>";
                            }elseif ($_SESSION['notif'] == 'error'){
                                echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak dapat diproses, terjadi kesalahan data.
                                    </div>";
                            }elseif ($_SESSION['notif'] == 'error-not-found'){
                                echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                        <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak ditemukan.
                                    </div>";
                            }
                            unset($_SESSION['notif']);  
                        } 
                    ?>
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>No. Hp</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $tampil = mysqli_query($conn, "SELECT * FROM ppdb_petugas ORDER BY id ASC");
                                $no = 1;
                                while ($r = mysqli_fetch_assoc($tampil)) {
                                    $encodeID = base64_encode($r['id']);
                                    echo "<tr>
                                            <td>".$no++."</td>
                                            <td>".$r['nama']."</td>
                                            <td>".$r['no_hp']."</td>
                                            <td>
                                                <center>
                                                    <a class='btn btn-warning btn-xs' title='Edit Data' href='?view=data petugas&act=edit&id=".$encodeID."'>
                                                        <span class='glyphicon glyphicon-edit'></span>
                                                    </a>
                                                    <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=data petugas&hapus&id=".$encodeID."' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                                                        <span class='glyphicon glyphicon-remove'></span>
                                                    </a>
                                                </center>
                                            </td>
                                        </tr>";
                                }

                                if (isset($_GET['hapus'])) {
                                    $decodeID = base64_decode($_GET['id']);
                                    $qPetugas = mysqli_query($conn, "SELECT * FROM ppdb_petugas WHERE id='$decodeID'");
                                    if (mysqli_num_rows($qPetugas) == 0){
                                        $_SESSION['notif'] = 'error-not-found';
                                    }else{
                                        mysqli_query($conn, "DELETE FROM ppdb_petugas WHERE id='$decodeID'");
                                        $_SESSION['notif'] = 'success-delete';
                                    }
                                    echo "<script>document.location='index.php?view=data petugas';</script>";
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php } elseif ($_GET['act'] == 'edit') { ?>

    <?php 
        if (isset($_POST['update'])) {
            $decodeID = base64_decode($_POST['id']);
            $query = mysqli_query($conn, "UPDATE ppdb_petugas SET nama='$_POST[nama]', no_hp='$_POST[noHp]' where id = '$decodeID'");
            if ($query) {
                $_SESSION['notif'] = 'success-update';
            } else {
                $_SESSION['notif'] = 'error';
            }
            echo "<script>document.location='index.php?view=data petugas';</script>";
        }

        $decodeID = base64_decode($_GET['id']);
        $edit = mysqli_query($conn, "SELECT * FROM ppdb_petugas where id='$decodeID'");
        if (mysqli_num_rows($edit) == 0){
            $_SESSION['notif'] = 'error-not-found';
            echo "<script>document.location='index.php?view=data petugas';</script>";
        }
        $record = mysqli_fetch_assoc($edit);
    ?>
  
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Edit Data Petugas</h3>
            </div>
            <div class="box-body">
                <form method="post" action="" class="form-horizontal">
                    <input type="hidden" name="id" value="<?php echo base64_encode($record['id']); ?>">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama" class="form-control" placeholder="Nama" value="<?php echo $record['nama']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">No. Hp</label>
                        <div class="col-sm-8">
                            <input type="text" name="noHp" class="form-control" placeholder="Contoh: 6281234567890" value="<?php echo $record['no_hp']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" name="update" value="Update" class="btn btn-success">
                            <a href="index.php?view=data petugas" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php } elseif ($_GET['act'] == 'tambah') { ?>
    
    <?php 
        if (isset($_POST['tambah'])) {
            $query = mysqli_query($conn, "INSERT INTO ppdb_petugas(nama, no_hp) VALUES('$_POST[nama]','$_POST[noHp]')");
            if ($query) {
                $_SESSION['notif'] = 'success-create';
            } else {
                $_SESSION['notif'] = 'error';
            }
            echo "<script>document.location='index.php?view=data petugas';</script>";
        }
    ?>

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"> Tambah Data Petugas </h3>
            </div>
            <div class="box-body">
                <form method="POST" action="" class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">Nama</label>
                        <div class="col-sm-8">
                            <input type="text" name="nama" class="form-control" placeholder="Nama" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-3 control-label">No. Hp</label>
                        <div class="col-sm-8">
                            <input type="text" name="noHp" class="form-control" placeholder="Contoh: 6281234567890" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                            <a href="index.php?view=data petugas" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php } ?>