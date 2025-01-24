<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data Program Kerja </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
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
            <th>Nama Program Kerja</th>
            <th>File</th> <!-- Kolom untuk tombol lihat file -->
        </tr>
    </thead>
    <tbody>
        <?php
        // Mengubah query untuk mengambil data program kerja
        $tampil = mysqli_query($conn, "SELECT *, rb_program_kerja.id as Id FROM rb_program_kerja 
        INNER JOIN tahun_ajaran ON rb_program_kerja.idTahunAjaran = tahun_ajaran.idTahunAjaran where unit='kepsek'
        ORDER BY rb_program_kerja.idTahunAjaran ASC");
        $no = 1;
        while ($r = mysqli_fetch_array($tampil)) {
            echo "<tr>
                    <td>$no</td>
                    <td>$r[nmTahunAjaran]</td>
                    <td>$r[nama]</td>
                    <td>
                        <a class='btn btn-info btn-xs' title='Lihat File' href='$r[file]' target='_blank'>
                            <span class='glyphicon glyphicon-eye-open'></span> Lihat File
                        </a>
                    </td>
                   
                  </tr>";
            $no++;
        }
        // Menghapus data jika ada perintah untuk menghapus
        if (isset($_GET['hapus'])) {
            mysqli_query($conn, "DELETE FROM rb_program_kerja WHERE id='$_GET[id]'");
            echo "<script>document.location='index.php?view=program_kerja';</script>";
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
    $nik = $_POST['guru'];
    $judul_file = $_POST['judul_file'];
    $idTahunAjaran = $_POST['idTahunAjaran'];
    $unit = $_POST['unit'];

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // Cek ukuran file (maks 40MB)
        if ($_FILES['file']['size'] <= 70 * 1024 * 1024) { // 40 MB
            $target_dir = "file_program_kerja/";  // Ganti nama folder sesuai dengan konteks Program Kerja
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            
            // Move the uploaded file to the desired directory
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            
            // Update query with file path
            $query = mysqli_query($conn, "UPDATE rb_program_kerja SET  nama='$judul_file', idTahunAjaran='$idTahunAjaran', file='$target_file' WHERE id='$_POST[id]'");
        } else {
            echo "<script>alert('Ukuran file terlalu besar. Maksimal 70MB.');</script>";
        }
    } else {
        // Update without changing the file
        $query = mysqli_query($conn, "UPDATE rb_program_kerja SET nama='$judul_file' WHERE id='$_POST[id]'");
    }

    if ($query) {
        echo "<script>document.location='index.php?view=program_kerja&sukses';</script>";
    } else {
        echo "<script>document.location='index.php?view=program_kerja&gagal';</script>";
    }
  }

  // Fetch the record to edit
  $edit = mysqli_query($conn, "SELECT * FROM rb_program_kerja where id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
     <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Data Program Kerja</h3>
        </div>
        <div class="box-body">
          <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
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
           
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nama Program Kerja</label>
              <div class="col-sm-4">
                <input type="text" name="judul_file" class="form-control" value="<?php echo $record['nama']; ?>" required>
              </div>
            </div>
            
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">File</label>
              <div class="col-sm-4">
                <input type="file" name="file" class="form-control" accept=".pdf">
                <?php if (!empty($record['file'])): ?>
                  <p>File yang diupload: <a href="<?= $record['file'] ?>" target="_blank"><?= $record['file'] ?></a></p>
                <?php endif; ?>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="update" value="Update" class="btn btn-success">
                <a href="index.php?view=program_kerja" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

<?php
} elseif ($_GET['act'] == 'tambah') {
  if (isset($_POST['tambah'])) {
    $nik = $_POST['guru'];
    $judul_file = $_POST['judul_file'];
    $idTahunAjaran = $_POST['idTahunAjaran'];
    $unit = $_POST['unit'];

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // Cek ukuran file (maks 40MB)
        if ($_FILES['file']['size'] <= 70 * 1024 * 1024) { // 40 MB
            $target_dir = "file_program_kerja/";  // Ganti nama folder sesuai dengan konteks Program Kerja
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            
            // Move the uploaded file to the desired directory
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            
            // Insert query with file path
            $query = mysqli_query($conn, "INSERT INTO rb_program_kerja (idTahunAjaran, nama, unit, file) VALUES ('$idTahunAjaran', '$judul_file', 'kepsek', '$target_file')");
        } else {
            echo "<script>alert('Ukuran file terlalu besar. Maksimal 40MB.');</script>";
        }
    }

    if ($query) {
        echo "<script>document.location='index.php?view=program_kerja&sukses';</script>";
    } else {
        echo "<script>document.location='index.php?view=program_kerja&gagal';</script>";
    }
  }
  ?>
   <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Tambah Data Program Kerja </h3>
        </div>
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
              <div class="col-sm-4">
                <select name="idTahunAjaran" class="form-control">
                    <?php
                    $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
                    while ($t = mysqli_fetch_array($sqltahun)) {
                        echo '<option value="' . $t['idTahunAjaran'] . '">' . $t['nmTahunAjaran'] . '</option>';
                    }
                    ?>
                </select>
              </div>
            </div>
            
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nama Program Kerja</label>
              <div class="col-sm-4">
                <input type="text" name="judul_file" class="form-control" required>
              </div>
            </div>
          
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">File</label>
              <div class="col-sm-4">
                <input type="file" name="file" class="form-control" accept=".pdf">
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                <a href="index.php?view=program_kerja" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
}
?>
