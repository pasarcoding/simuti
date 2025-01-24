<?php if ($_GET['act'] == '') { ?>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Barang</h3>
                <a class='pull-right btn btn-primary btn-sm' href='?view=inv_data_barang&act=tambah'>Tambahkan Data</a>
            </div><!-- /.box-header -->
            <div class="box-body">
                <!-- Filter Jenis Barang -->
                <form method="GET" action="" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="view" value="inv_data_barang">

                        <label for="filterJenisBarang">Filter Jenis Barang:</label>
                        <select name="filterJenisBarang" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Jenis Barang --</option>
                            <?php
                            // Ambil data jenis barang dari database
                            $jenisBarangQuery = mysqli_query($conn, "SELECT * FROM inv_jenis_barang");
                            while ($jenis = mysqli_fetch_array($jenisBarangQuery)) {
                                $selected = (isset($_GET['filterJenisBarang']) && $_GET['filterJenisBarang'] == $jenis['idJenisBarang']) ? 'selected' : '';
                                echo "<option value='{$jenis['idJenisBarang']}' $selected>{$jenis['nmJenisBarang']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <?php if (isset($_GET['filterJenisBarang']) && $_GET['filterJenisBarang'] != ''): ?>
                        <a href="cetak_barang.php?filterJenisBarang=<?php echo $_GET['filterJenisBarang']; ?>" class="btn btn-primary" target="_blank">Cetak</a>
                    <?php else: ?>
                        <a href="cetak_barang.php" class="btn btn-primary" target="_blank">Cetak Semua</a>
                    <?php endif; ?>
                </form>
                <br />

                <div class="table-responsive">
                    <?php
                    if (isset($_GET['sukses'])) {
                        echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses..</div>";
                    } elseif (isset($_GET['gagal'])) {
                        echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, terjadi kesalahan dengan data..</div>";
                    }
                    ?>

                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kategori Barang</th>
                                <th>Jenis Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Satuan</th>
                                <th>Status Barang</th>
                                <th>Harga Satuan</th>
                                <th>Harga Total</th>
                                <th>Sumber Dana</th>
                                <th>Penyedia</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Mengambil nilai filter jenis barang
                            $filterJenisBarang = isset($_GET['filterJenisBarang']) ? $_GET['filterJenisBarang'] : '';

                            // Membuat query berdasarkan filter jenis barang
                            $query = "SELECT * FROM inv_data_barang 
                                      INNER JOIN inv_jenis_barang ON inv_data_barang.jenisBarang = inv_jenis_barang.idJenisBarang
                                      INNER JOIN inv_sumber_dana ON inv_data_barang.sumberDana = inv_sumber_dana.idSumberDana";

                            if ($filterJenisBarang) {
                                $query .= " WHERE inv_data_barang.jenisBarang = '$filterJenisBarang'";
                            }

                            $query .= " ORDER BY inv_data_barang.idBarang ASC";

                            // Menjalankan query
                            $tampil = mysqli_query($conn, $query);
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                                echo "<tr>
                                      <td>$no</td>
                                      <td>$r[namaBarang]</td>
                                      <td>$r[kategoriBarang]</td>
                                      <td>$r[nmJenisBarang]</td>
                                      <td>$r[jumlahBarang]</td>
                                      <td>$r[satuan]</td>
                                      <td>$r[statusBarang]</td>
                                      <td>" . buatRp($r['hargaSatuan']) . "</td>
                                      <td>" . buatRp($r['hargaTotal']) . "</td>
                                      <td>$r[nmSumberDana]</td>
                                      <td>$r[penyedia]</td>
                                      <td><center>
                                        <a class='btn btn-success btn-xs' title='Edit Data' href='?view=inv_data_barang&act=edit&id=$r[idBarang]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=inv_data_barang&hapus&id=$r[idBarang]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>
                                  </tr>";
                                $no++;
                            }

                            if (isset($_GET['hapus'])) {
                                mysqli_query($conn, "DELETE FROM inv_data_barang WHERE idBarang='$_GET[id]'");
                                echo "<script>document.location='?view=inv_data_barang';</script>";
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
        $hargaTotal = $_POST['jumlahBarang'] * $_POST['hargaSatuan'];

        $query = mysqli_query($conn, "UPDATE inv_data_barang SET 
            namaBarang='$_POST[namaBarang]', 
            kategoriBarang='$_POST[kategoriBarang]', 
            jenisBarang='$_POST[jenisBarang]', 
            jumlahBarang='$_POST[jumlahBarang]', 
            satuan='$_POST[satuan]', 
            statusBarang='$_POST[statusBarang]', 
            hargaSatuan='$_POST[hargaSatuan]', 
            hargaTotal='$hargaTotal', 
            sumberDana='$_POST[sumberDana]', 
            penyedia='$_POST[penyedia]' 
            WHERE idBarang = '$_POST[id]'");
        if ($query) {
            echo "<script>document.location='?view=inv_data_barang&sukses';</script>";
        } else {
            echo "<script>document.location='?view=inv_data_barang&gagal';</script>";
        }
    }
    $edit = mysqli_query($conn, "SELECT * FROM inv_data_barang WHERE idBarang='$_GET[id]'");
    $record = mysqli_fetch_array($edit);

    // Ambil data untuk jenis barang dan sumber dana
    $jenisBarangResult = mysqli_query($conn, "SELECT * FROM inv_jenis_barang");
    $sumberDanaResult = mysqli_query($conn, "SELECT * FROM inv_sumber_dana");
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Edit Data Barang</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="post" action="" class="form-horizontal">
                        <input type="hidden" name="id" value="<?php echo $record['idBarang']; ?>">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <input type="text" name="namaBarang" class="form-control" value="<?php echo $record['namaBarang']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kategori Barang</label>
                            <div class="col-sm-4">
                                <select name="kategoriBarang" class="form-control" required>
                                    <option value="Sarana" <?php echo ($record['kategoriBarang'] == 'Sarana') ? 'selected' : ''; ?>>Sarana</option>
                                    <option value="Prasarana" <?php echo ($record['kategoriBarang'] == 'Prasarana') ? 'selected' : ''; ?>>Prasarana</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Jenis Barang</label>
                            <div class="col-sm-4">
                                <select name="jenisBarang" class="form-control" required>
                                    <?php
                                    while ($jenis = mysqli_fetch_array($jenisBarangResult)) {
                                        echo "<option value='{$jenis['idJenisBarang']}' " . ($record['jenisBarang'] == $jenis['idJenisBarang'] ? 'selected' : '') . ">{$jenis['nmJenisBarang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Jumlah Barang</label>
                            <div class="col-sm-4">
                                <input type="number" name="jumlahBarang" class="form-control" value="<?php echo $record['jumlahBarang']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Satuan</label>
                            <div class="col-sm-4">
                                <input type="text" name="satuan" class="form-control" value="<?php echo $record['satuan']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Status Barang</label>
                            <div class="col-sm-4">
                                <select name="statusBarang" class="form-control" required>
                                    <option value="BHP" <?php echo ($record['statusBarang'] == 'BHP') ? 'selected' : ''; ?>>BHP</option>
                                    <option value="Non BHP" <?php echo ($record['statusBarang'] == 'Non BHP') ? 'selected' : ''; ?>>Non BHP</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Harga Satuan</label>
                            <div class="col-sm-4">
                                <input type="number" name="hargaSatuan" class="form-control" value="<?php echo $record['hargaSatuan']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Harga Total</label>
                            <div class="col-sm-4">
                                <input type="number" name="hargaTotal" class="form-control" value="<?php echo $record['hargaTotal']; ?>" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Sumber Dana</label>
                            <div class="col-sm-4">
                                <select name="sumberDana" class="form-control" required>
                                    <?php
                                    while ($sumber = mysqli_fetch_array($sumberDanaResult)) {
                                        echo "<option value='{$sumber['idSumberDana']}' " . ($record['sumberDana'] == $sumber['idSumberDana'] ? 'selected' : '') . ">{$sumber['nmSumberDana']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Penyedia</label>
                            <div class="col-sm-4">
                                <input type="text" name="penyedia" class="form-control" value="<?php echo $record['penyedia']; ?>" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="update" value="Update" class="btn btn-success">
                                <a href="?view=inv_data_barang" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
} elseif ($_GET['act'] == 'tambah') {
    if (isset($_POST['tambah'])) {
        $hargaTotal = $_POST['jumlahBarang'] * $_POST['hargaSatuan'];

        $query = mysqli_query($conn, "INSERT INTO inv_data_barang(namaBarang, kategoriBarang, jenisBarang, jumlahBarang, satuan, statusBarang, hargaSatuan, hargaTotal, sumberDana, penyedia) 
        VALUES('$_POST[namaBarang]', '$_POST[kategoriBarang]', '$_POST[jenisBarang]', '$_POST[jumlahBarang]', '$_POST[satuan]', '$_POST[statusBarang]', '$_POST[hargaSatuan]', '$hargaTotal', '$_POST[sumberDana]', '$_POST[penyedia]')");
        if ($query) {
            echo "<script>document.location='?view=inv_data_barang&sukses';</script>";
        } else {
            echo "<script>document.location='?view=inv_data_barang&gagal';</script>";
        }
    }
    $jenisBarangResult = mysqli_query($conn, "SELECT * FROM inv_jenis_barang");
    $sumberDanaResult = mysqli_query($conn, "SELECT * FROM inv_sumber_dana");
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"> Tambah Data Barang </h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="" class="form-horizontal">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <input type="text" name="namaBarang" class="form-control" id="" placeholder="Nama Barang" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kategori Barang</label>
                            <div class="col-sm-4">
                                <select name="kategoriBarang" class="form-control" required>
                                    <option value="Sarana">Sarana</option>
                                    <option value="Prasarana">Prasarana</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Jenis Barang</label>
                            <div class="col-sm-4">
                                <select name="jenisBarang" class="form-control" required>
                                    <?php
                                    while ($jenis = mysqli_fetch_array($jenisBarangResult)) {
                                        echo "<option value='{$jenis['idJenisBarang']}'>{$jenis['nmJenisBarang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Jumlah Barang</label>
                            <div class="col-sm-4">
                                <input type="number" name="jumlahBarang" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Satuan</label>
                            <div class="col-sm-4">
                                <input type="text" name="satuan" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Status Barang</label>
                            <div class="col-sm-4">
                                <select name="statusBarang" class="form-control" required>
                                    <option value="BHP">BHP</option>
                                    <option value="Non BHP">Non BHP</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Harga Satuan</label>
                            <div class="col-sm-4">
                                <input type="number" name="hargaSatuan" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Harga Total</label>
                            <div class="col-sm-4">
                                <input type="number" name="hargaTotal" class="form-control" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Sumber Dana</label>
                            <div class="col-sm-4">
                                <select name="sumberDana" class="form-control" required>
                                    <?php
                                    while ($sumber = mysqli_fetch_array($sumberDanaResult)) {
                                        echo "<option value='{$sumber['idSumberDana']}'>{$sumber['nmSumberDana']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Penyedia</label>
                            <div class="col-sm-4">
                                <input type="text" name="penyedia" class="form-control" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                                <a href="?view=inv_data_barang" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php
}
    ?>