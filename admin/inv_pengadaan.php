<?php if ($_GET['act'] == '') { ?>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Pengadaan Barang</h3>
                <a class='pull-right btn btn-primary btn-sm' href='index.php?view=inv_pengadaan&act=tambah'>Tambahkan Data</a>
            </div><!-- /.box-header -->
            <div class="box-body">
                <!-- Filter Jenis Barang -->
                <form method="GET" action="" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="view" value="inv_pengadaan">

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
                            // Ambil tahun yang tersedia dari data pengadaan
                            $tahunQuery = mysqli_query($conn, "SELECT DISTINCT YEAR(tanggalPengadaan) AS tahun FROM inv_pengadaan ORDER BY tahun DESC");
                            while ($tahun = mysqli_fetch_array($tahunQuery)) {
                                $selected = (isset($_GET['filterTahun']) && $_GET['filterTahun'] == $tahun['tahun']) ? 'selected' : '';
                                echo "<option value='{$tahun['tahun']}' $selected>{$tahun['tahun']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Tombol Cetak -->
                    <?php if (isset($_GET['filterBulan']) && $_GET['filterBulan'] != '' && isset($_GET['filterTahun']) && $_GET['filterTahun'] != ''): ?>
                        <a href="cetak_pengadaan.php?filterBulan=<?php echo $_GET['filterBulan']; ?>&filterTahun=<?php echo $_GET['filterTahun']; ?>" class="btn btn-primary" target="_blank">Cetak</a>
                    <?php else: ?>
                        <a href="cetak_pengadaan.php" class="btn btn-primary" target="_blank">Cetak Semua</a>
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
                                <th>Tanggal Pengadaan</th>
                                <th>Pengaju</th>
                                <th>Nama Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Harga Satuan</th>
                                <th>Harga Total</th>
                                <th>Sumber Dana</th>
                                <th>Penyedia</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $filterBulan = isset($_GET['filterBulan']) ? $_GET['filterBulan'] : '';
                            $filterTahun = isset($_GET['filterTahun']) ? $_GET['filterTahun'] : '';



                            // Membuat query berdasarkan filter jenis barang
                            $query = "SELECT * FROM inv_pengadaan 
                                      INNER JOIN inv_data_barang ON inv_pengadaan.namaBarang = inv_data_barang.idBarang
                                      INNER JOIN inv_sumber_dana ON inv_data_barang.sumberDana = inv_sumber_dana.idSumberDana WHERE 1";
                            if ($filterBulan) {
                                $query .= " AND MONTH(tanggalPengadaan) = '$filterBulan'";
                            }

                            if ($filterTahun) {
                                $query .= " AND YEAR(tanggalPengadaan) = '$filterTahun'";
                            }

                            $query .= " ORDER BY inv_pengadaan.idPengadaan ASC";

                            // Menjalankan query
                            $tampil = mysqli_query($conn, $query);
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                                echo "<tr>
                                      <td>$no</td>
                                      <td>" . tgl_miring($r['tanggalPengadaan']) . "</td>
                                        <td>$r[namaPengaju]</td>
                                      <td>$r[namaBarang]</td>
                                      <td>$r[jumlahBarang]</td>
                                      <td>" . buatRp($r['hargaSatuan']) . "</td>
                                      <td>" . buatRp($r['hargaTotal']) . "</td>
                                      <td>$r[nmSumberDana]</td>
                                      <td>$r[penyedia]</td>
                                      <td><center>
                                        <a class='btn btn-success btn-xs' title='Edit Data' href='?view=inv_pengadaan&act=edit&id=$r[idPengadaan]&barang=$r[idBarang]'><span class='glyphicon glyphicon-edit'></span></a>
                                        <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=inv_pengadaan&hapus&id=$r[idPengadaan]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                                      </center></td>
                                  </tr>";
                                $no++;
                            }

                            if (isset($_GET['hapus'])) {
                                mysqli_query($conn, "DELETE FROM inv_pengadaan WHERE idPengadaan='$_GET[id]'");
                                echo "<script>document.location='index.php?view=inv_pengadaan';</script>";
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
    // Ambil data pengadaan berdasarkan id yang dipilih untuk diedit
    if (isset($_GET['id'])) {
        $idPengadaan = $_GET['id'];

        // Query untuk mengambil data pengadaan berdasarkan idPengadaan
        $query = mysqli_query($conn, "SELECT * FROM inv_pengadaan WHERE idPengadaan = '$idPengadaan'");
        $record = mysqli_fetch_array($query);

        // Jika form di-submit, proses update data pengadaan
        if (isset($_POST['update'])) {
            $tanggalPengadaan = $_POST['tanggalPengadaan'];
            $idBarang = $_POST['namaBarang'];
            $namaPengaju = $_POST['namaPengaju'];

            // Query untuk update data pengadaan
            $updateQuery = mysqli_query($conn, "UPDATE inv_pengadaan SET
                tanggalPengadaan = '$tanggalPengadaan',
                namaBarang = '$idBarang',
                namaPengaju = '$namaPengaju'
                WHERE idPengadaan = '$idPengadaan'");

            if ($updateQuery) {
                echo "<script>document.location='index.php?view=inv_pengadaan&sukses';</script>";
            } else {
                echo "<script>document.location='index.php?view=inv_pengadaan&gagal';</script>";
            }
        }
    }

    // Ambil data barang untuk dropdown
    $barangResult = mysqli_query($conn, "SELECT * FROM inv_data_barang");
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Data Pengadaan</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form method="POST" action="" class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tanggal Pengadaan</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggalPengadaan" class="form-control" value="<?php echo $record['tanggalPengadaan']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Barang</label>
                        <div class="col-sm-4">
                            <select name="namaBarang" id="namaBarang" class="form-control" onchange="loadBarangDetails(this.value)" required>
                                <option value="">-- Pilih Nama Barang --</option>
                                <?php
                                // Ambil data barang dari inv_data_barang
                                while ($barang = mysqli_fetch_array($barangResult)) {
                                    echo "<option value='{$barang['idBarang']}'" . ($barang['idBarang'] == $record['namaBarang'] ? ' selected' : '') . ">{$barang['namaBarang']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Pengaju</label>
                        <div class="col-sm-4">
                            <input type="text" name="namaPengaju" class="form-control" value="<?php echo $record['namaPengaju']; ?>" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" name="update" value="Update" class="btn btn-success">
                            <a href="index.php?view=inv_pengadaan" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col-md-12 -->

    <script>
        // Fungsi untuk memuat detail barang (harga satuan, harga total, dsb) saat memilih nama barang
        function loadBarangDetails(idBarang) {
            if (idBarang == "") {
                document.getElementById("jumlahBarang").value = "";
                document.getElementById("hargaSatuan").value = "";
                document.getElementById("hargaTotal").value = "";
                document.getElementById("sumberDana").innerHTML = "";
                document.getElementById("penyedia").value = "";
                return;
            }

            // Mengambil data barang berdasarkan idBarang
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_barang_details.php?idBarang=" + idBarang, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);

                    // Memperbarui data form dengan data barang
                    document.getElementById("hargaSatuan").value = data.hargaSatuan;
                    document.getElementById("penyedia").value = data.penyedia;

                    // Mengupdate jumlah barang
                    document.getElementById("jumlahBarang").value = data.jumlahBarang;

                    // Mengupdate Sumber Dana
                    loadSumberDana(data.sumberDana);

                    // Menghitung harga total saat jumlah barang berubah
                    var jumlahBarang = document.getElementById("jumlahBarang").value;
                    var hargaTotal = data.hargaSatuan * jumlahBarang;
                    document.getElementById("hargaTotal").value = hargaTotal;
                }
            };
            xhr.send();
        }

        // Fungsi untuk memuat sumber dana berdasarkan idSumberDana
        function loadSumberDana(idSumberDana) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_sumber_dana.php?idSumberDana=" + idSumberDana, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);
                    document.getElementById("sumberDana").innerHTML = `<option value="${data.idSumberDana}">${data.nmSumberDana}</option>`;
                }
            };
            xhr.send();
        }

        // Update harga total ketika jumlah barang berubah
        document.getElementById("jumlahBarang").addEventListener("input", function() {
            var hargaSatuan = document.getElementById("hargaSatuan").value;
            var jumlahBarang = document.getElementById("jumlahBarang").value;
            var hargaTotal = hargaSatuan * jumlahBarang;
            document.getElementById("hargaTotal").value = hargaTotal;
        });
    </script>



<?php
} elseif ($_GET['act'] == 'tambah') {
    if (isset($_POST['tambah'])) {
        // Menangani proses input data pengadaan
        $tanggalPengadaan = $_POST['tanggalPengadaan'];
        $idBarang = $_POST['namaBarang'];

        $namaPengaju = $_POST['namaPengaju'];

        // Query untuk memasukkan data pengadaan ke database
        $query = mysqli_query($conn, "INSERT INTO inv_pengadaan(tanggalPengadaan, namaBarang, namaPengaju) 
        VALUES('$tanggalPengadaan', '$idBarang', '$namaPengaju')");

        if ($query) {
            echo "<script>document.location='index.php?view=inv_pengadaan&sukses';</script>";
        } else {
            echo "<script>document.location='index.php?view=inv_pengadaan&gagal';</script>";
        }
    }

    // Ambil data barang untuk dropdown
    $barangResult = mysqli_query($conn, "SELECT * FROM inv_data_barang");
    $sumberDanaResult = mysqli_query($conn, "SELECT * FROM inv_sumber_dana");
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Data Pengadaan</h3>
            </div><!-- /.box-header -->
            <div class="box-body">
                <form method="POST" action="" class="form-horizontal">
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tanggal Pengadaan</label>
                        <div class="col-sm-4">
                            <input type="date" name="tanggalPengadaan" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Barang</label>
                        <div class="col-sm-4">
                            <select name="namaBarang" id="namaBarang" class="form-control" onchange="loadBarangDetails(this.value)" required>
                                <option value="">-- Pilih Nama Barang --</option>
                                <?php
                                // Ambil data barang dari inv_data_barang
                                while ($barang = mysqli_fetch_array($barangResult)) {
                                    echo "<option value='{$barang['idBarang']}'>{$barang['namaBarang']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Jumlah Barang</label>
                        <div class="col-sm-4">
                            <input type="number" name="jumlahBarang" id="jumlahBarang" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Harga Satuan</label>
                        <div class="col-sm-4">
                            <input type="number" name="hargaSatuan" id="hargaSatuan" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Harga Total</label>
                        <div class="col-sm-4">
                            <input type="number" name="hargaTotal" id="hargaTotal" class="form-control" readonly>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Sumber Dana</label>
                        <div class="col-sm-4">
                            <select name="sumberDana" id="sumberDana" class="form-control" readonly>
                                <!-- Data Sumber Dana akan diisi oleh AJAX -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Penyedia</label>
                        <div class="col-sm-4">
                            <input type="text" name="penyedia" id="penyedia" class="form-control" readonly>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Pengaju</label>
                        <div class="col-sm-4">
                            <input type="text" name="namaPengaju" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                            <a href="index.php?view=inv_pengadaan" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div><!-- /.box-body -->
        </div><!-- /.box -->
    </div><!-- /.col-md-12 -->
    <script>
        // Fungsi untuk memuat detail barang (harga satuan, harga total, dsb) saat memilih nama barang
        function loadBarangDetails(idBarang) {
            if (idBarang == "") {
                document.getElementById("jumlahBarang").value = "";
                document.getElementById("hargaSatuan").value = "";
                document.getElementById("hargaTotal").value = "";
                document.getElementById("sumberDana").innerHTML = "";
                document.getElementById("penyedia").value = "";
                return;
            }

            // Mengambil data barang berdasarkan idBarang
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_barang_details.php?idBarang=" + idBarang, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);

                    // Memperbarui data form dengan data barang
                    document.getElementById("hargaSatuan").value = data.hargaSatuan;
                    document.getElementById("penyedia").value = data.penyedia;

                    // Mengupdate jumlah barang
                    document.getElementById("jumlahBarang").value = data.jumlahBarang;

                    // Mengupdate Sumber Dana
                    loadSumberDana(data.sumberDana);

                    // Menghitung harga total saat jumlah barang berubah
                    var jumlahBarang = document.getElementById("jumlahBarang").value;
                    var hargaTotal = data.hargaSatuan * jumlahBarang;
                    document.getElementById("hargaTotal").value = hargaTotal;
                }
            };
            xhr.send();
        }

        // Fungsi untuk memuat sumber dana berdasarkan idSumberDana
        function loadSumberDana(idSumberDana) {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "get_sumber_dana.php?idSumberDana=" + idSumberDana, true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    var data = JSON.parse(xhr.responseText);
                    document.getElementById("sumberDana").innerHTML = `<option value="${data.idSumberDana}">${data.nmSumberDana}</option>`;
                }
            };
            xhr.send();
        }

        // Update harga total ketika jumlah barang berubah
        document.getElementById("jumlahBarang").addEventListener("input", function() {
            var hargaSatuan = document.getElementById("hargaSatuan").value;
            var jumlahBarang = document.getElementById("jumlahBarang").value;
            var hargaTotal = hargaSatuan * jumlahBarang;
            document.getElementById("hargaTotal").value = hargaTotal;
        });
    </script>

<?php
}
?>