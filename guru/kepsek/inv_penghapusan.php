<?php if ($_GET['act'] == '') { ?>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Penghapusan Barang</h3>
                <!--<a class='pull-right btn btn-primary btn-sm' href='?view=inv_penghapusan&act=tambah'>Tambahkan Data</a>-->
            </div><!-- /.box-header -->
            <div class="box-body">
                <!-- Filter Jenis Barang -->
                <form method="GET" action="" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="view" value="inv_penghapusan">

                        <!-- Filter Bulan -->
                        <label for="filterBulan">Filter Bulan:</label>
                        <select name="filterBulan" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Bulan --</option>
                            <?php
                            $bulan = [
                                '01' => 'Januari',
                                '02' => 'Februari',
                                '03' => 'Maret',
                                '04' => 'April',
                                '05' => 'Mei',
                                '06' => 'Juni',
                                '07' => 'Juli',
                                '08' => 'Agustus',
                                '09' => 'September',
                                '10' => 'Oktober',
                                '11' => 'November',
                                '12' => 'Desember'
                            ];

                            foreach ($bulan as $key => $value) {
                                $selected = (isset($_GET['filterBulan']) && $_GET['filterBulan'] == $key) ? 'selected' : '';
                                echo "<option value='$key' $selected>$value</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <!-- Filter Tahun -->
                        <label for="filterTahun">Filter Tahun:</label>
                        <select name="filterTahun" class="form-control" onchange="this.form.submit()">
                            <?php
                            // Ambil tahun yang tersedia dari data penghapusan
                            $tahunQuery = mysqli_query($conn, "SELECT DISTINCT YEAR(tanggalPenghapusan) AS tahun FROM inv_penghapusan ORDER BY tahun DESC");
                            while ($tahun = mysqli_fetch_array($tahunQuery)) {
                                $selected = (isset($_GET['filterTahun']) && $_GET['filterTahun'] == $tahun['tahun']) ? 'selected' : '';
                                echo "<option value='{$tahun['tahun']}' $selected>{$tahun['tahun']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Tombol Cetak -->
                    <?php if (isset($_GET['filterBulan']) && $_GET['filterBulan'] != '' && isset($_GET['filterTahun']) && $_GET['filterTahun'] != ''): ?>
                        <a href="cetak_penghapusan.php?filterBulan=<?php echo $_GET['filterBulan']; ?>&filterTahun=<?php echo $_GET['filterTahun']; ?>" class="btn btn-primary" target="_blank">Cetak</a>
                    <?php else: ?>
                        <a href="cetak_penghapusan.php" class="btn btn-primary" target="_blank">Cetak Semua</a>
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
                                <th>Kode INV</th>
                                <th>Barang</th>
                                <th>Tanggal Penghapusan</th>
                                <th>Keterangan</th>
                                <th>Bukti Fisik</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $filterBulan = isset($_GET['filterBulan']) ? $_GET['filterBulan'] : '';
                            $filterTahun = isset($_GET['filterTahun']) ? $_GET['filterTahun'] : '';

                            // Membuat query berdasarkan filter bulan dan tahun
                            $query = "SELECT * , inv_data_barang.namaBarang  as NamaBarang FROM inv_penghapusan 
                                      INNER JOIN inv_data_barang ON inv_penghapusan.idItem = inv_data_barang.idBarang
                                      INNER JOIN inv_data_item ON inv_data_barang.idBarang = inv_data_item.namaBarang

                                      WHERE 1";
                            if ($filterBulan) {
                                $query .= " AND MONTH(tanggalPenghapusan) = '$filterBulan'";
                            }

                            if ($filterTahun) {
                                $query .= " AND YEAR(tanggalPenghapusan) = '$filterTahun'";
                            }

                            $query .= " ORDER BY inv_penghapusan.idPenghapusan ASC";

                            // Menjalankan query
                            $tampil = mysqli_query($conn, $query);
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                                echo "<tr>
                                      <td>$no</td>
                                       <td>$r[kodeINV]</td>
                                     
                                      <td>$r[NamaBarang]</td>
                                       <td>" . tgl_miring($r['tanggalPenghapusan']) . "</td>
                                      <td>$r[keterangan]</td>
                                      <td><a href='{$r['buktiFisik']}' target='_blank'>Lihat Gambar</a></td>
                                      
                                  </tr>";
                                $no++;
                            }

                            if (isset($_GET['hapus'])) {
                                mysqli_query($conn, "DELETE FROM inv_penghapusan WHERE idPenghapusan='$_GET[id]'");
                                echo "<script>document.location='?view=inv_penghapusan';</script>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div><!-- /.col-xs-12 -->
    </div><!-- /.row -->


<?php
} elseif ($_GET['act'] == 'edit') {
    // Ambil data penghapusan berdasarkan id yang dipilih untuk diedit
    if (isset($_GET['id'])) {
        $idPenghapusan = $_GET['id'];

        // Query untuk mengambil data penghapusan berdasarkan idPenghapusan
        $query = mysqli_query($conn, "SELECT * FROM inv_penghapusan WHERE idPenghapusan = '$idPenghapusan'");
        $record = mysqli_fetch_array($query);

        // Jika form di-submit, proses update data penghapusan
        if (isset($_POST['update'])) {
            $tanggalPenghapusan = $_POST['tanggalPenghapusan'];
            $idBarang = $_POST['namaItem']; // Menggunakan 'namaItem' untuk penghapusan
            $keterangan = $_POST['keterangan'];
            $buktiFisik = $_FILES['buktiFisik']['name'];

            // Jika file bukti fisik diupload, proses upload file
            if ($buktiFisik) {
                $target_dir = "foto_barang_rusak/"; // Folder untuk menyimpan bukti fisik
                $target_file = $target_dir . basename($_FILES["buktiFisik"]["name"]);
                move_uploaded_file($_FILES["buktiFisik"]["tmp_name"], $target_file);
            } else {
                $target_file = $record['buktiFisik']; // Menjaga bukti fisik lama jika tidak ada file baru
            }

            // Query untuk update data penghapusan
            $updateQuery = mysqli_query($conn, "UPDATE inv_penghapusan SET
                tanggalPenghapusan = '$tanggalPenghapusan',
                idItem = '$idBarang',
                keterangan = '$keterangan',
                buktiFisik = '$target_file'
                WHERE idPenghapusan = '$idPenghapusan'");

            if ($updateQuery) {
                echo "<script>document.location='?view=inv_penghapusan&sukses';</script>";
            } else {
                echo "<script>document.location='?view=inv_penghapusan&gagal';</script>";
            }
        }
    }

    // Ambil data barang untuk dropdown
    $barangResult = mysqli_query($conn, "SELECT * FROM inv_data_barang");
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Data Penghapusan</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tanggal Penghapusan</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggalPenghapusan" class="form-control" value="<?php echo $record['tanggalPenghapusan']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Item</label>
                        <div class="col-sm-4">
                            <select name="namaItem" id="namaItem" class="form-control" required>
                                <option value="">-- Pilih Nama Item --</option>
                                <?php
                                // Ambil data barang dari inv_data_barang
                                while ($barang = mysqli_fetch_array($barangResult)) {
                                    echo "<option value='{$barang['idBarang']}'" . ($barang['idBarang'] == $record['idItem'] ? ' selected' : '') . ">{$barang['namaBarang']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Keterangan</label>
                        <div class="col-sm-4">
                            <input type="text" name="keterangan" class="form-control" value="<?php echo $record['keterangan']; ?>" required>
                        </div>
                    </div>

                    <!-- Bukti Fisik -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Bukti Fisik</label>
                        <div class="col-sm-4">
                            <input type="file" name="buktiFisik" class="form-control" accept="image/jpeg">
                            <small>File harus berupa gambar JPG. (Opsional)</small><br>
                            <?php
                            if ($record['buktiFisik']) {
                                echo "Bukti fisik lama: <a href='{$record['buktiFisik']}' target='_blank'>Lihat Gambar</a>";
                            }
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" name="update" value="Update" class="btn btn-success">
                            <a href="?view=inv_penghapusan" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col-md-12 -->


<?php
} elseif ($_GET['act'] == 'tambah') {
    if (isset($_POST['tambah'])) {
        // Menangani proses input data penghapusan
        $tanggalPenghapusan = $_POST['tanggalPenghapusan'];
        $idBarang = $_POST['namaItem'];
        $keterangan = $_POST['keterangan'];

        // Proses upload file bukti fisik (gambar JPG)
        if (isset($_FILES['buktiFisik'])) {
            $target_dir = "foto_barang_rusak/";
            $target_file = $target_dir . basename($_FILES["buktiFisik"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Mengecek apakah file adalah gambar JPG
            if ($imageFileType != "jpg" && $imageFileType != "jpeg") {
                echo "<script>alert('Hanya file JPG yang diperbolehkan.');</script>";
            } else {
                if (move_uploaded_file($_FILES["buktiFisik"]["tmp_name"], $target_file)) {
                    $buktiFisik = $target_file; // Menyimpan path file gambar

                    // Query untuk memasukkan data penghapusan ke database
                    $query = mysqli_query($conn, "INSERT INTO inv_penghapusan(tanggalPenghapusan, idItem, keterangan, buktiFisik) 
                    VALUES('$tanggalPenghapusan', '$idBarang', '$keterangan', '$buktiFisik')");

                    if ($query) {
                        echo "<script>document.location='?view=inv_penghapusan&sukses';</script>";
                    } else {
                        echo "<script>document.location='?view=inv_penghapusan&gagal';</script>";
                    }
                } else {
                    echo "<script>alert('Maaf, terjadi kesalahan saat mengupload file.');</script>";
                }
            }
        }
    }

    // Ambil data barang untuk dropdown
    $barangResult = mysqli_query($conn, "SELECT inv_data_barang.idBarang, inv_data_barang.namaBarang 
    FROM inv_data_item 
    INNER JOIN inv_data_barang ON inv_data_item.namaBarang = inv_data_barang.idBarang
    ");
?>

    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Data Penghapusan</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tanggal Penghapusan</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggalPenghapusan" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Item</label>
                        <div class="col-sm-4">
                            <select name="namaItem" id="namaItem" class="form-control" required>
                                <option value="">-- Pilih Nama Item --</option>
                                <?php
                                // Menampilkan nama barang yang diambil dari query
                                while ($barang = mysqli_fetch_array($barangResult)) {
                                    echo "<option value='{$barang['idBarang']}'>{$barang['namaBarang']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Keterangan</label>
                        <div class="col-sm-4">
                            <input type="text" name="keterangan" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Bukti Fisik</label>
                        <div class="col-sm-4">
                            <input type="file" name="buktiFisik" class="form-control" accept="image/jpeg" required>
                            <small>File harus berupa gambar JPG</small>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                            <a href="?view=inv_penghapusan" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col-md-12 -->
<?php
}
?>