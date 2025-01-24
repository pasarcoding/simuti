<?php if ($_GET['act'] == '') { ?>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Item Barang</h3>
                <!--<a class='pull-right btn btn-primary btn-sm' href='?view=inv_data_item&act=tambah_item'>Tambah Data Item</a>-->
            </div><!-- /.box-header -->
            <div class="box-body">
                <!-- Filter Lokasi Barang -->
                <form method="GET" action="" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="view" value="inv_data_item">
                        <label for="filterLokasiBarang">Filter Lokasi Barang:</label>
                        <select name="filterLokasiBarang" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Lokasi --</option>
                            <?php
                            // Ambil semua lokasi barang
                            $lokasiBarangQuery = mysqli_query($conn, "SELECT * FROM inv_lok_barang");
                            while ($lokasi = mysqli_fetch_array($lokasiBarangQuery)) {
                                $selected = (isset($_GET['filterLokasiBarang']) && $_GET['filterLokasiBarang'] == $lokasi['idLokBarang']) ? 'selected' : '';
                                echo "<option value='{$lokasi['idLokBarang']}' $selected>{$lokasi['lokasiBarang']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Tambahkan tombol Cetak -->
                    <?php if (isset($_GET['filterLokasiBarang']) && $_GET['filterLokasiBarang'] != ''): ?>
                        <a href="cetak_item.php?filterLokasiBarang=<?php echo $_GET['filterLokasiBarang']; ?>" class="btn btn-primary" target="_blank">Cetak</a>
                    <?php else: ?>
                        <a href="cetak_item.php" class="btn btn-primary" target="_blank">Cetak Semua</a>
                    <?php endif; ?>
                </form>

                </form>
                <br>
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

                <div class="table-responsive">
                    <table id="example1" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Kode INV</th>
                                <th>Kategori Barang</th>
                                <th>Sumber Dana</th>
                                <th>Kondisi</th>
                                <th>Lokasi Barang</th>
                                <th>Foto Barang</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Menentukan filter lokasi barang
                            $filterLokasi = isset($_GET['filterLokasiBarang']) ? $_GET['filterLokasiBarang'] : '';

                            // Modifikasi query berdasarkan filter lokasi barang
                            $query = "SELECT * FROM inv_data_item 
                                INNER JOIN inv_data_barang ON inv_data_item.namaBarang = inv_data_barang.idBarang
                                INNER JOIN inv_sumber_dana ON inv_data_barang.sumberDana = inv_sumber_dana.idSumberDana
                                INNER JOIN inv_lok_barang ON inv_data_item.lokasiBarang = inv_lok_barang.idLokBarang";

                            if ($filterLokasi != '') {
                                $query .= " WHERE inv_data_item.lokasiBarang = '$filterLokasi'";
                            }

                            $query .= " ORDER BY inv_data_item.idItem ASC";

                            $tampil = mysqli_query($conn, $query);
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                                echo "<tr>
                                    <td>$no</td>
                                    <td>$r[namaBarang]</td>
                                    <td>$r[kodeINV]</td>
                                    <td>$r[kategoriBarang]</td>
                                    <td>$r[nmSumberDana]</td>
                                    <td>$r[kondisi]</td>
                                    <td>$r[lokasiBarang]</td>
                                    <td>
                                        <a href='$r[fotoBarang]' target='_blank'>
                                            <img src='$r[fotoBarang]' alt='Foto Barang' style='width: 50px; height: auto;'>
                                        </a>
                                    </td>
                                    
                                </tr>";
                                $no++;
                            }

                            if (isset($_GET['hapus'])) {
                                mysqli_query($conn, "DELETE FROM inv_data_item WHERE idItem='$_GET[id]'");
                                echo "<script>document.location='?view=inv_data_item';</script>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>


    <?php
} else if ($_GET['act'] == 'tambah_item') {
    // Ambil data barang dan lokasi barang
    $dataBarang = mysqli_query($conn, "SELECT * FROM inv_data_barang");
    $lokasiBarangResult = mysqli_query($conn, "SELECT * FROM inv_lok_barang");

    if (isset($_POST['tambah_item'])) {
        // Membuat Kode INV berdasarkan kategori dan sumber dana
        $idBarang = $_POST['namaBarang'];

        // Ambil kategori dan sumber dana berdasarkan idBarang yang dipilih
        $barangQuery = mysqli_query($conn, "SELECT kategoriBarang, kodeSumberDana FROM inv_data_barang 
        INNER JOIN inv_sumber_dana ON inv_data_barang.sumberDana = inv_sumber_dana.idSumberDana

        WHERE idBarang = '$idBarang'");
        $barangData = mysqli_fetch_array($barangQuery);

        // Menyimpan kategoriBarang dan sumberDana dari data yang diambil
        $kategoriBarang = $barangData['kategoriBarang'];
        $sumberDana = $barangData['kodeSumberDana'];

        // Tentukan nilai kategoriBarang
        if ($kategoriBarang == 'Sarana') {
            $kategoriBarang = '01';
        } else {
            $kategoriBarang = '02';
        }

        // Ambil nomor urut terakhir dan hitung kode INV
        $lastItem = mysqli_query($conn, "SELECT MAX(idItem) as last_id FROM inv_data_item");
        $lastId = mysqli_fetch_array($lastItem)['last_id'];
        $newId = str_pad($lastId + 1, 5, "0", STR_PAD_LEFT);

        // Format kode INV
        $kodeINV = "INV.$kategoriBarang.$sumberDana.$newId";



        // Menyimpan data item baru
        $fotoBarang = ""; // Jika menggunakan upload file foto, simpan path file di sini.
        if (isset($_FILES['fotoBarang']) && $_FILES['fotoBarang']['name']) {
            // Proses upload foto
            $fotoBarang = "foto_barang/" . $_FILES['fotoBarang']['name'];
            move_uploaded_file($_FILES['fotoBarang']['tmp_name'], $fotoBarang);
        }

        // Insert data item ke dalam tabel inv_data_item
        $query = mysqli_query($conn, "INSERT INTO inv_data_item (namaBarang, kondisi, lokasiBarang, fotoBarang, kodeINV) 
            VALUES ('$_POST[namaBarang]', '$_POST[kondisi]', '$_POST[lokasiBarang]', '$fotoBarang', '$kodeINV')");

        if ($query) {
            echo "<script>document.location='?view=inv_data_item&sukses';</script>";
        } else {
            echo "<script>document.location='?view=inv_data_item&gagal';</script>";
        }
    }
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Data Item Barang</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <select name="namaBarang" id="namaBarang" class="form-control" required>
                                    <option value="">Pilih Nama Barang</option>
                                    <?php
                                    while ($barang = mysqli_fetch_array($dataBarang)) {
                                        echo "<option value='{$barang['idBarang']}'>{$barang['namaBarang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kondisi</label>
                            <div class="col-sm-4">
                                <select name="kondisi" class="form-control" required>
                                    <option value="Baik">Baik</option>
                                    <option value="Rusak Ringan">Rusak Ringan</option>
                                    <option value="Rusak Berat">Rusak Berat</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Lokasi Barang</label>
                            <div class="col-sm-4">
                                <select name="lokasiBarang" class="form-control" required>
                                    <?php
                                    while ($lokasi = mysqli_fetch_array($lokasiBarangResult)) {
                                        echo "<option value='{$lokasi['idLokBarang']}'>{$lokasi['lokasiBarang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Foto Barang</label>
                            <div class="col-sm-4">
                                <input type="file" name="fotoBarang" class="form-control" accept="image/jpeg">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="tambah_item" value="Simpan" class="btn btn-success">
                                <a href="?view=inv_data_item" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>


    <?php
} else if ($_GET['act'] == 'edit') {
    $idItem = $_GET['id']; // Ambil ID item yang akan diedit

    // Ambil data barang yang akan diedit
    $query = mysqli_query($conn, "SELECT * FROM inv_data_item WHERE idItem = '$idItem'");
    $dataItem = mysqli_fetch_array($query);

    // Ambil data barang dan lokasi barang untuk pilihan dropdown
    $dataBarang = mysqli_query($conn, "SELECT * FROM inv_data_barang");
    $lokasiBarangResult = mysqli_query($conn, "SELECT * FROM inv_lok_barang");

    if (isset($_POST['edit_item'])) {
        // Menangani foto barang jika ada perubahan
        $fotoBarang = $dataItem['fotoBarang']; // Ambil foto barang yang lama

        if (isset($_FILES['fotoBarang']) && $_FILES['fotoBarang']['name']) {
            // Proses upload foto baru
            $fotoBarang = "foto_barang/" . $_FILES['fotoBarang']['name'];
            move_uploaded_file($_FILES['fotoBarang']['tmp_name'], $fotoBarang);
        }

        // Update data item ke dalam database
        $queryUpdate = mysqli_query($conn, "UPDATE inv_data_item SET
            namaBarang = '$_POST[namaBarang]',
            kondisi = '$_POST[kondisi]',
            lokasiBarang = '$_POST[lokasiBarang]',
            fotoBarang = '$fotoBarang'
            WHERE idItem = '$idItem'");

        if ($queryUpdate) {
            echo "<script>document.location='?view=inv_data_item&sukses';</script>";
        } else {
            echo "<script>document.location='?view=inv_data_item&gagal';</script>";
        }
    }
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Data Item Barang</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Barang</label>
                            <div class="col-sm-4">
                                <select name="namaBarang" id="namaBarang" class="form-control" required>
                                    <option value="">Pilih Nama Barang</option>
                                    <?php
                                    while ($barang = mysqli_fetch_array($dataBarang)) {
                                        $selected = ($barang['idBarang'] == $dataItem['namaBarang']) ? 'selected' : '';
                                        echo "<option value='{$barang['idBarang']}' $selected>{$barang['namaBarang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kondisi</label>
                            <div class="col-sm-4">
                                <select name="kondisi" class="form-control" required>
                                    <option value="Baik" <?= ($dataItem['kondisi'] == 'Baik') ? 'selected' : ''; ?>>Baik</option>
                                    <option value="Rusak Ringan" <?= ($dataItem['kondisi'] == 'Rusak Ringan') ? 'selected' : ''; ?>>Rusak Ringan</option>
                                    <option value="Rusak Berat" <?= ($dataItem['kondisi'] == 'Rusak Berat') ? 'selected' : ''; ?>>Rusak Berat</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Lokasi Barang</label>
                            <div class="col-sm-4">
                                <select name="lokasiBarang" class="form-control" required>
                                    <?php
                                    while ($lokasi = mysqli_fetch_array($lokasiBarangResult)) {
                                        $selected = ($lokasi['idLokBarang'] == $dataItem['lokasiBarang']) ? 'selected' : '';
                                        echo "<option value='{$lokasi['idLokBarang']}' $selected>{$lokasi['lokasiBarang']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Foto Barang</label>
                            <div class="col-sm-4">
                                <input type="file" name="fotoBarang" class="form-control" accept="image/jpeg">
                                <br />
                                <?php if ($dataItem['fotoBarang']) { ?>
                                    <img src="<?= $dataItem['fotoBarang'] ?>" alt="Foto Barang" style="width: 100px; height: auto;">
                                <?php } ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="edit_item" value="Simpan Perubahan" class="btn btn-success">
                                <a href="?view=inv_data_item" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    <?php
}
    ?>