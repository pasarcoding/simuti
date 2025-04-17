<?php if ($_GET['act'] == '') { ?>
    <div class="col-xs-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Data Arsip</h3>
                <!-- <a class='pull-right btn btn-primary btn-sm' href='?view=arsip_data&act=tambah_item'>Tambah Data Arsip</a> -->
            </div><!-- /.box-header -->
            <div class="box-body">
                <!-- Filter Lokasi Barang -->
                <form method="GET" action="" class="form-inline">
                    <div class="form-group">
                        <input type="hidden" name="view" value="arsip_data">
                        <label for="filternmKategori">Filter Kategori Arsip:</label>
                        <select name="filternmKategori" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Kategori --</option>
                            <?php
                            // Ambil semua lokasi barang
                            $nmKategoriQuery = mysqli_query($conn, "SELECT * FROM arsip_kategori");
                            while ($lokasi = mysqli_fetch_array($nmKategoriQuery)) {
                                $selected = (isset($_GET['filternmKategori']) && $_GET['filternmKategori'] == $lokasi['idKategori']) ? 'selected' : '';
                                echo "<option value='{$lokasi['idKategori']}' $selected>{$lokasi['nmKategori']}</option>";
                            }
                            ?>
                        </select>
                    </div>

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
                                <th>Kategori Arsip</th>
                                <th>Nama Arsip</th>
                                <th>File</th>
                                <!-- <th>Aksi</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Menentukan filter lokasi barang
                            $filterLokasi = isset($_GET['filternmKategori']) ? $_GET['filternmKategori'] : '';

                            // Modifikasi query berdasarkan filter lokasi barang
                            $query = "SELECT * FROM arsip_data 
                                INNER JOIN arsip_kategori ON arsip_data.idKategori = arsip_kategori.idKategori";

                            if ($filterLokasi != '') {
                                $query .= " WHERE arsip_data.idKategori = '$filterLokasi'";
                            }

                            $query .= " ORDER BY arsip_data.idArsip ASC";

                            $tampil = mysqli_query($conn, $query);
                            $no = 1;
                            while ($r = mysqli_fetch_array($tampil)) {
                                echo "<tr>
                                    <td>$no</td>
                                    <td>$r[nmKategori]</td>
                                    <td>$r[nmArsip]</td>
                                   
                                    <td>
                                        <a href='$r[fileArsip]' class='btn btn-primary btn-sm' target='_blank'>Lihat
                                        </a>
                                    </td>
                                  
                                </tr>";
                                $no++;
                            }

                            if (isset($_GET['hapus'])) {
                                mysqli_query($conn, "DELETE FROM arsip_data WHERE idArsip='$_GET[id]'");
                                echo "<script>document.location='?view=arsip_data';</script>";
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
    $nmKategoriResult = mysqli_query($conn, "SELECT * FROM arsip_kategori");

    if (isset($_POST['tambah_item'])) {
        // Membuat Kode INV berdasarkan kategori dan sumber dana
        $idKategori = $_POST['idKategori'];


        // Menyimpan data item baru
        $fileArsip = ""; // Jika menggunakan upload file foto, simpan path file di sini.
        if (isset($_FILES['fileArsip']) && $_FILES['fileArsip']['name']) {
            // Mendapatkan ekstensi file
            $ext = pathinfo($_FILES['fileArsip']['name'], PATHINFO_EXTENSION);

            // Membuat nama file acak dengan menggunakan uniqid dan ekstensi file
            $randomName = uniqid() . '.' . $ext;

            // Menyimpan file dengan nama acak di folder "file_arsip"
            $fileArsip = "file_arsip/" . $randomName;
            move_uploaded_file($_FILES['fileArsip']['tmp_name'], $fileArsip);
        }


        // Insert data item ke dalam tabel arsip_data
        $query = mysqli_query($conn, "INSERT INTO arsip_data (idKategori,nmArsip, fileArsip) 
            VALUES ('$idKategori', '$_POST[nmArsip]', '$fileArsip')");

        if ($query) {
            echo "<script>document.location='?view=arsip_data&sukses';</script>";
        } else {
            echo "<script>document.location='?view=arsip_data&gagal';</script>";
        }
    }
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Tambah Data Arsip</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kategori Arsip</label>
                            <div class="col-sm-4">
                                <select name="idKategori" id="idKategori" class="form-control" required>
                                    <option value="">Pilih Kategori Arsip</option>
                                    <?php
                                    while ($arsip = mysqli_fetch_array($nmKategoriResult)) {
                                        echo "<option value='{$arsip['idKategori']}'>{$arsip['nmKategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Arsip</label>
                            <div class="col-sm-4">
                                <input type="text" name="nmArsip" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">File Arsip</label>
                            <div class="col-sm-4">
                                <input type="file" name="fileArsip" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="tambah_item" value="Simpan" class="btn btn-success">
                                <a href="?view=arsip_data" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>

    <?php
} else if ($_GET['act'] == 'edit') {
    // Ambil data arsip berdasarkan ID yang akan diedit
    $idArsip = $_GET['id'];
    $arsipResult = mysqli_query($conn, "SELECT * FROM arsip_data WHERE idArsip = '$idArsip'");
    $arsipData = mysqli_fetch_array($arsipResult);

    $nmKategoriResult = mysqli_query($conn, "SELECT * FROM arsip_kategori");

    if (isset($_POST['edit_item'])) {
        // Ambil data yang akan di-update
        $idKategori = $_POST['idKategori'];
        $nmArsip = $_POST['nmArsip'];
        $fileArsip = $arsipData['fileArsip']; // Jika tidak ada file baru, tetap menggunakan file lama

        // Jika ada file baru yang di-upload
        if (isset($_FILES['fileArsip']) && $_FILES['fileArsip']['name']) {
            // Menghapus file lama jika ada file baru
            if (file_exists($arsipData['fileArsip'])) {
                unlink($arsipData['fileArsip']);
            }

            // Mendapatkan ekstensi file
            $ext = pathinfo($_FILES['fileArsip']['name'], PATHINFO_EXTENSION);

            // Membuat nama file acak dengan menggunakan uniqid dan ekstensi file
            $randomName = uniqid() . '.' . $ext;

            // Menyimpan file baru dengan nama acak di folder "file_arsip"
            $fileArsip = "file_arsip/" . $randomName;
            move_uploaded_file($_FILES['fileArsip']['tmp_name'], $fileArsip);
        }

        // Update data arsip
        $query = mysqli_query($conn, "UPDATE arsip_data SET idKategori = '$idKategori', nmArsip = '$nmArsip', fileArsip = '$fileArsip' WHERE idArsip = '$idArsip'");

        if ($query) {
            echo "<script>document.location='?view=arsip_data&sukses';</script>";
        } else {
            echo "<script>document.location='?view=arsip_data&gagal';</script>";
        }
    }
    ?>
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Edit Data Arsip</h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Kategori Arsip</label>
                            <div class="col-sm-4">
                                <select name="idKategori" id="idKategori" class="form-control" required>
                                    <option value="">Pilih Kategori Arsip</option>
                                    <?php
                                    while ($arsip = mysqli_fetch_array($nmKategoriResult)) {
                                        $selected = ($arsip['idKategori'] == $arsipData['idKategori']) ? 'selected' : '';
                                        echo "<option value='{$arsip['idKategori']}' $selected>{$arsip['nmKategori']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">Nama Arsip</label>
                            <div class="col-sm-4">
                                <input type="text" name="nmArsip" class="form-control" value="<?php echo $arsipData['nmArsip']; ?>">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label">File Arsip</label>
                            <div class="col-sm-4">
                                <input type="file" name="fileArsip" class="form-control">
                                <br>
                                <label>File lama:</label> <a href="<?php echo $arsipData['fileArsip']; ?>" target="_blank"><?php echo basename($arsipData['fileArsip']); ?></a>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-sm-2 control-label"></label>
                            <div class="col-sm-10">
                                <input type="submit" name="edit_item" value="Update" class="btn btn-success">
                                <a href="?view=arsip_data" class="btn btn-default">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div><!-- /.box-body -->
            </div><!-- /.box -->
        </div>
    <?php
}
    ?>