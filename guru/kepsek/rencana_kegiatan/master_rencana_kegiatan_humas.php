<?php 
            $where = "humas";


if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
          <div class="row">
    <!-- Filter Form -->
    <form method="GET" action="">
        <input class="hidden" name="view" value="rencana_kegiatan_<?= $where ?>"></input>
        <input class="hidden" name=""></input>

        <div class="col-md-3">
            <select name="idTahunAjaran" class="form-control">
                <option value="">-- Pilih Tahun Ajaran --</option>
                <?php
                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
                while ($t = mysqli_fetch_array($sqltahun)) {
                    $selected = ($_GET['idTahunAjaran'] == $t['idTahunAjaran']) ? ' selected' : '';
                    echo '<option value="' . $t['idTahunAjaran'] . '"' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="col-md-3">
            <select name="bulan" class="form-control">
                <option value="">-- Pilih Bulan --</option>
                <?php
                $bulan = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($bulan as $key => $value) {
                    $selected = ($_GET['bulan'] == $key) ? ' selected' : '';
                    echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
                }
                ?>
            </select>
        </div>
        
        <div class="col-md-3">
            <select name="approval" class="form-control">
                <option value="">-- Pilih Approval --</option>
                <option value="pending" <?php echo ($_GET['approval'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                <option value="setuju" <?php echo ($_GET['approval'] == 'setuju') ? 'selected' : ''; ?>>Setuju</option>
                <option value="tolak" <?php echo ($_GET['approval'] == 'tolak') ? 'selected' : ''; ?>>Tolak</option>
            </select>
        </div>
        <input class="hidden" value="<?= $where ?>" name="unit"></input>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary" name="filter">Tampilkan</button>
            <a href="excel_rencana_kegiatan_admin.php?unit=<?= $where ?>&idTahunAjaran=<?= $_GET['idTahunAjaran'] ?>&bulan=<?= $_GET['bulan'] ?>&approval=<?= $_GET['approval'] ?>" class="btn btn-success">Cetak Excel</a>

        </div>
    </form>
    </div>
      </div><!-- /.box-header -->
      
        
      <div class="box-body">
          

<br>
          <?php
          $whereQuery = "WHERE unit='$where'";
          if (!empty($_GET['idTahunAjaran'])) {
                    $whereQuery .= " AND idTahunAjaran = '" . $_GET['idTahunAjaran'] . "'";
                }
                
                // Filter berdasarkan Bulan (jika dipilih)
                if (!empty($_GET['bulan'])) {
                    $whereQuery .= " AND MONTH(waktu_mulai) = '" . $_GET['bulan'] . "'";
                }
                
                // Filter berdasarkan Approval
                if (!empty($_GET['approval'])) {
                    $whereQuery .= " AND status = '" . $_GET['approval'] . "'";
                }
            // Menghitung jumlah kegiatan dan total anggaran pada unit tertentu
            $queryCount = mysqli_query($conn, "SELECT COUNT(*) as jumlah_kegiatan, SUM(anggaran) as total_anggaran 
                                               FROM rb_rencana_kegiatan 
                                                $whereQuery");
            
            $countData = mysqli_fetch_array($queryCount);
            $jumlahKegiatan = $countData['jumlah_kegiatan'];
            $totalAnggaran = $countData['total_anggaran'];
            
            // Format total anggaran agar lebih mudah dibaca (misalnya format angka Indonesia)
            $totalAnggaranFormatted = number_format($totalAnggaran, 0, ',', '.');
            ?>
            
            <div class="row">
                <div class="col-md-3">
                    <h4><strong class="text-danger">Jumlah Kegiatan: </strong><span class="text-danger"><?php echo $jumlahKegiatan; ?> Kegiatan</span></h4>
                </div>
                <div class="col-md-3">
                    <h4><strong class="text-danger">Total Anggaran: </strong><span class="text-danger">Rp <?php echo $totalAnggaranFormatted; ?></span></h4>
                </div>
            </div>

            
        <div class="table-responsive">
          <?php
          if (isset($_GET['sukses'])) {
            echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..</div>";
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
                <th>Tahun Ajaran</th>
                <th>Nama Kegiatan</th>
                <th>Uraian</th>
                <th>Penanggung Jawab</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Tempat</th>
                <th>Rencana Anggaran</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Menangani query filter
                $whereQuery = "WHERE unit='$where'";  // Filter berdasarkan unit
                
                // Filter berdasarkan Tahun Ajaran
                if (!empty($_GET['idTahunAjaran'])) {
                    $whereQuery .= " AND rb_rencana_kegiatan.idTahunAjaran = '" . $_GET['idTahunAjaran'] . "'";
                }
                
                // Filter berdasarkan Bulan (jika dipilih)
                if (!empty($_GET['bulan'])) {
                    $whereQuery .= " AND MONTH(rb_rencana_kegiatan.waktu_mulai) = '" . $_GET['bulan'] . "'";
                }
                
                // Filter berdasarkan Approval
                if (!empty($_GET['approval'])) {
                    $whereQuery .= " AND rb_rencana_kegiatan.status = '" . $_GET['approval'] . "'";
                }
                
                // Query untuk mengambil data sesuai filter
                $tampil = mysqli_query($conn, "SELECT *, rb_rencana_kegiatan.idRk as Id FROM rb_rencana_kegiatan 
                    INNER JOIN tahun_ajaran ON rb_rencana_kegiatan.idTahunAjaran = tahun_ajaran.idTahunAjaran
                    $whereQuery
                    ORDER BY rb_rencana_kegiatan.waktu_mulai ASC");
                
                $no = 1;
                while ($r = mysqli_fetch_array($tampil)) {
                    // Menentukan tombol berdasarkan status
                    if ($r['status'] == 'pending') {
                            $tombol =  "<a class='btn btn-warning btn-xs' title='Pending'>$r[status]</a>";
                            $editDelete = "<center><a class='btn btn-primary btn-xs' href='file_rencana/{$r['file_rencana']}' target='_blank'><i class='fa fa-eye'></i></a>
                                            <a class='btn btn-success btn-xs' title='Edit Data' href='?view=rencana_kegiatan&act=edit&id=$r[idRk]'>
                                                <span class='glyphicon glyphicon-edit'></span>
                                            </a>
                                            <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=rencana_kegiatan&hapus&id=$r[idRk]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                                                <span class='glyphicon glyphicon-remove'></span>
                                            </a>
                                          </center>";
                        } elseif ($r['status'] == 'tolak') {
                            $tombol =  "<a class='btn btn-danger btn-xs' title='Tolak'>$r[status]</a>";
                            $editDelete = "<center><a class='btn btn-primary btn-xs' href='file_rencana/{$r['file_rencana']}' target='_blank'><i class='fa fa-eye'></i></a>
                                            <a class='btn btn-danger btn-xs' title='Tidak dapat diubah, Status: Ditolak'>
                                                <span class='glyphicon glyphicon-lock'></span> Tidak dapat diedit
                                            </a>
                                          </center>";
                        } elseif ($r['status'] == 'setuju') {
                            $tombol =  "<a class='btn btn-success btn-xs' title='Setuju'>$r[status]</a>";
                            $editDelete = "<center><a class='btn btn-primary btn-xs' href='file_rencana/{$r['file_rencana']}' target='_blank'><i class='fa fa-eye'></i></a>
                                            <a class='btn btn-danger btn-xs' title='Tidak dapat diubah, Status: Disetujui'>
                                                <span class='glyphicon glyphicon-lock'></span> Tidak dapat diedit
                                            </a>
                                          </center>";
                        } else {
                            // Untuk status lain yang tidak terduga
                            $tombol =  "<a class='btn btn-success btn-xs' title='Status Tidak Diketahui'>$r[status]</a>";
                            $editDelete = "<center><a class='btn btn-primary btn-xs' href='file_rencana/{$r['file_rencana']}' target='_blank'><i class='fa fa-eye'></i></a>
                                            <a class='btn btn-danger btn-xs' title='Status tidak diketahui, Tidak dapat diedit'>
                                                <span class='glyphicon glyphicon-lock'></span> Tidak dapat diedit
                                            </a>
                                          </center>";
                        }
                
                    echo "<tr>
                            <td>$no</td>
                            <td>$r[nmTahunAjaran]</td>
                            <td>$r[nmKegiatan]</td>
                            <td>$r[uraian]</td>
                            <td>$r[pj]</td>
                            <td>$r[waktu_mulai]</td>
                            <td>$r[waktu_selesai]</td>
                            <td>$r[tempat]</td>
                            <td>" . buatRp($r['anggaran']) . "</td> <!-- Format Anggaran -->
                            <td>$tombol</td>
                          </tr>";
                    $no++;
                }
                
                // Menghapus data jika ada perintah untuk menghapus
                if (isset($_GET['hapus'])) {
                    mysqli_query($conn, "DELETE FROM rb_rencana_kegiatan WHERE idRk='$_GET[id]'");
                    echo "<script>document.location='?view=rencana_kegiatan_$where';</script>";
                }
?>

    </tbody>
</table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>


<?php
}else if ($_GET['act'] == 'edit') {
    if (isset($_POST['update'])) {
        $id = $_POST['idRk'];  // id yang diedit
        $idTahunAjaran = $_POST['idTahunAjaran'];
        $nmKegiatan = $_POST['nmKegiatan'];
        $uraian = $_POST['uraian'];
        $pj = $_POST['pj'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];
        $tempat = $_POST['tempat'];
        $anggaran = $_POST['anggaran'];

        // Get current file_rencana value from the database to retain if no new file is uploaded
        $file_rencana = $record['file_rencana'];

        // Handle file upload only if a new file is provided
        if (isset($_FILES['file_rencana']) && $_FILES['file_rencana']['error'] == 0) {
            $file_tmp = $_FILES['file_rencana']['tmp_name'];
            $file_name = $_FILES['file_rencana']['name'];
            $file_size = $_FILES['file_rencana']['size'];
            $file_type = $_FILES['file_rencana']['type'];

            // Specify the folder to upload the file
            $upload_dir = 'file_rencana/';
            // Ensure the directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate a unique file name to avoid overwriting
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_name_new = uniqid('file_') . '.' . $file_ext;
            $file_path = $upload_dir . $file_name_new;

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($file_tmp, $file_path)) {
                $file_rencana = $file_name_new; // Update with the new file name
            }
        }

        // Update query with or without the file_rencana depending on the file upload
        $query = mysqli_query($conn, "UPDATE rb_rencana_kegiatan SET 
            idTahunAjaran='$idTahunAjaran', 
            nmKegiatan='$nmKegiatan', 
            uraian='$uraian',
            pj='$pj', 
            waktu_mulai='$waktu_mulai',
            waktu_selesai='$waktu_selesai',
            tempat='$tempat',
            anggaran='$anggaran'");

        // Only update the file_rencana column if a new file is uploaded
        if ($file_rencana != $record['file_rencana']) {
            $query = mysqli_query($conn, "UPDATE rb_rencana_kegiatan SET file_rencana='$file_rencana' WHERE idRk='$id'");
        }


        // Redirect setelah update
        if ($query) {
            echo "<script>document.location='?view=rencana_kegiatan_$where&sukses';</script>";
        } else {
            echo "<script>document.location='?view=rencana_kegiatan_$where&gagal';</script>";
        }
    }

    // Ambil data untuk diedit
    $edit = mysqli_query($conn, "SELECT * FROM rb_rencana_kegiatan WHERE idRk='$_GET[id]'");
    $record = mysqli_fetch_array($edit);
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Data Rencana Kegiatan</h3>
            </div>
            <div class="box-body">
            <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="idRk" value="<?php echo $record['idRk']; ?>">

                    <!-- Tahun Ajaran -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
                        <div class="col-sm-4">
                            <select name="idTahunAjaran" class="form-control">
                                <?php
                                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
                                while ($t = mysqli_fetch_array($sqltahun)) {
                                    $selected = ($t['idTahunAjaran'] == $record['idTahunAjaran']) ? ' selected="selected"' : "";
                                    echo '<option value="' . $t['idTahunAjaran'] . '" ' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>

                    <!-- Nama Kegiatan -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Kegiatan</label>
                        <div class="col-sm-4">
                            <input type="text" name="nmKegiatan" class="form-control" value="<?php echo $record['nmKegiatan']; ?>" required>
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Uraian</label>
                        <div class="col-sm-4">
                            <textarea name="uraian" class="form-control" rows="3" required><?php echo $record['uraian']; ?></textarea>
                        </div>
                    </div>

                    <!-- Penanggung Jawab -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Penanggung Jawab</label>
                        <div class="col-sm-4">
                            <input type="text" name="pj" class="form-control" value="<?php echo $record['pj']; ?>" required>
                        </div>
                    </div>

                    <!-- Waktu Mulai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Mulai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_mulai" class="form-control" value="<?php echo $record['waktu_mulai']; ?>" required>
                        </div>
                    </div>

                    <!-- Waktu Selesai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Selesai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_selesai" class="form-control" value="<?php echo $record['waktu_selesai']; ?>" required>
                        </div>
                    </div>

                    <!-- Tempat -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tempat</label>
                        <div class="col-sm-4">
                            <input type="text" name="tempat" class="form-control" value="<?php echo $record['tempat']; ?>" required>
                        </div>
                    </div>

                    <!-- Anggaran -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Anggaran</label>
                        <div class="col-sm-4">
                            <input type="number" name="anggaran" class="form-control" value="<?php echo $record['anggaran']; ?>" required>
                        </div>
                    </div>
                    <!-- File Upload (Dokumen Pendukung) -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Dokumen Pendukung</label>
                        <div class="col-sm-4">
                            <input type="file" name="file_rencana" class="form-control">
                            <small>Biarkan kosong jika tidak mengubah file</small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" name="update" value="Update" class="btn btn-success">
                            <a href="?view=rencana_kegiatan_<?= $where ?>" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

<?php
}else if ($_GET['act'] == 'tambah') {
    if (isset($_POST['tambah'])) {
        $idTahunAjaran = $_POST['idTahunAjaran'];
        $nmKegiatan = $_POST['nmKegiatan'];
        $uraian = $_POST['uraian'];
        $pj = $_POST['pj'];
        $waktu_mulai = $_POST['waktu_mulai'];
        $waktu_selesai = $_POST['waktu_selesai'];
        $tempat = $_POST['tempat'];
        $anggaran = $_POST['anggaran'];
        $unit = $where;

        // Handle file upload
        $file_rencana = '';
        if (isset($_FILES['file_rencana']) && $_FILES['file_rencana']['error'] == 0) {
            $file_tmp = $_FILES['file_rencana']['tmp_name'];
            $file_name = $_FILES['file_rencana']['name'];
            $file_size = $_FILES['file_rencana']['size'];
            $file_type = $_FILES['file_rencana']['type'];

            // Specify the folder to upload the file
            $upload_dir = 'file_rencana/';
            // Ensure the directory exists
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            // Generate a unique file name to avoid overwriting
            $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
            $file_name_new = uniqid('file_') . '.' . $file_ext;
            $file_path = $upload_dir . $file_name_new;

            // Move the uploaded file to the destination folder
            if (move_uploaded_file($file_tmp, $file_path)) {
                $file_rencana = $file_name_new; // Store the file name to save in the database
            }
        }

        // Insert query with file_rencana
        $query = mysqli_query($conn, "INSERT INTO rb_rencana_kegiatan (idTahunAjaran, nmKegiatan, uraian, pj, waktu_mulai, waktu_selesai, tempat, anggaran, status, unit, file_rencana) 
                                      VALUES ('$idTahunAjaran', '$nmKegiatan', '$uraian', '$pj', '$waktu_mulai', '$waktu_selesai', '$tempat', '$anggaran', 'pending', '$unit', '$file_rencana')");

        if ($query) {
            echo "<script>document.location='?view=rencana_kegiatan_$where&sukses';</script>";
        } else {
            echo "<script>document.location='?view=rencana_kegiatan_$where&gagal';</script>";
        }
    }
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Tambah Data Rencana Kegiatan</h3>
            </div>
            <div class="box-body">
            <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                    <!-- Tahun Ajaran -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
                        <div class="col-sm-4">
                            <select name="idTahunAjaran" class="form-control" required>
                                <?php
                                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
                                while ($t = mysqli_fetch_array($sqltahun)) {
                                    echo '<option value="' . $t['idTahunAjaran'] . '">' . $t['nmTahunAjaran'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Nama Kegiatan -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Kegiatan</label>
                        <div class="col-sm-4">
                            <input type="text" name="nmKegiatan" class="form-control" required>
                        </div>
                    </div>
                    
                    <!-- Uraian -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Uraian</label>
                        <div class="col-sm-4">
                            <textarea name="uraian" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    
                    <!-- Penanggung Jawab -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Penanggung Jawab</label>
                        <div class="col-sm-4">
                            <input type="text" name="pj" class="form-control" required>
                        </div>
                    </div>
                    
                    <!-- Waktu Mulai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Mulai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_mulai" class="form-control" required>
                        </div>
                    </div>
                    
                    <!-- Waktu Selesai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Selesai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_selesai" class="form-control" required>
                        </div>
                    </div>
                    
                    <!-- Tempat -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tempat</label>
                        <div class="col-sm-4">
                            <input type="text" name="tempat" class="form-control" required>
                        </div>
                    </div>
                    
                    <!-- Anggaran -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Anggaran</label>
                        <div class="col-sm-4">
                            <input type="number" name="anggaran" class="form-control" required>
                        </div>
                    </div>
                      <!-- File Upload (Dokumen Pendukung) -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Dokumen Pendukung</label>
                        <div class="col-sm-4">
                            <input type="file" name="file_rencana" class="form-control">
                        </div>
                    </div>
                    
                    
                    <!-- Submit Button -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label"></label>
                        <div class="col-sm-10">
                            <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                            <a href="?view=rencana_kegiatan_<?= $where ?>" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php
}
?>
