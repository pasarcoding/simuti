<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data Slider </h3>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?view=ppdb_slider&act=tambah'>Tambahkan Data</a>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <?php
          if (isset($_GET['sukses'])) {
            echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
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
                <th>Judul</th>
                <th>Sub Judul</th>
                <th>Gambar</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Ambil tanggal hari ini
              $today = date('Y-m-d');

              $tampil = mysqli_query($conn, "SELECT * FROM ppdb_slider
      ORDER BY id ASC");
              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
                // Tentukan status berdasarkan rentang tanggal

                echo "<tr><td>$no</td>
              <td>$r[judul]</td>
              <td>$r[sub_judul]</td>
               <td>$r[gambar]</td>
             <td><center>
                <a class='btn btn-warning btn-xs' title='Edit Data' href='?view=ppdb_slider&act=edit&id=$r[id]'>
                  <span class='glyphicon glyphicon-edit'></span>
                </a>
                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=ppdb_slider&hapus&id=$r[id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                  <span class='glyphicon glyphicon-remove'></span>
                </a>
              </center></td> ";
                echo "</tr>";
                $no++;
              }

              if (isset($_GET['hapus'])) {
                mysqli_query($conn, "DELETE FROM ppd_slider WHERE id='$_GET[id]'");
                echo "<script>document.location='index.php?view=ppdb_slider';</script>";
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

    $old_gambar = $_POST['old_gambar']; // Assuming 'old_gambar' holds the current image filename from the database

    // Check if a new image is uploaded
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] == 0) {
      // If an image is uploaded, handle the upload and generate a random filename
      $gambar = $_FILES['gambar']['name'];
      $target_dir = "foto_slider/"; // Specify your upload directory
      $random_name = uniqid() . '.' . pathinfo($gambar, PATHINFO_EXTENSION); // Generate a random filename
      $target_file = $target_dir . $random_name;

      // Move the uploaded file to the target directory
      if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
        $gambar = $random_name; // Assign the new random name to the variable
      } else {
        // Handle error if the file is not uploaded
        echo "Sorry, there was an error uploading your file.";
        exit; // Stop further processing
      }
    } else {
      // If no file is uploaded, retain the old image filename (do not change)
      $gambar = $old_gambar;
    }

    // Update the database with the new data (only updating gambar if a new file was uploaded)
    $query = mysqli_query($conn, "UPDATE ppdb_slider SET judul='$_POST[judul]', sub_judul='$_POST[sub_judul]', gambar='$gambar' WHERE id = '$_POST[id]'");

    if ($query) {
      echo "<script>document.location='index.php?view=ppdb_slider&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=ppdb_slider&gagal';</script>";
    }
  }

  $edit = mysqli_query($conn, "SELECT * FROM ppdb_slider WHERE id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Edit Slider</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Judul</label>
              <div class="col-sm-8">
                <input type="text" name="judul" class="form-control" value="<?php echo $record['judul']; ?>" required>
              </div>
            </div>
            <input type="hidden" name="old_gambar" value="<?php echo $record['gambar']; ?>">

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Sub Judul</label>
              <div class="col-sm-8">
                <input type="text" name="sub_judul" class="form-control" value="<?php echo $record['sub_judul']; ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Gambar</label>
              <div class="col-sm-8">
                <input type="file" name="gambar" class="form-control">
                <small>Current Image: <img src="foto_slider/<?php echo $record['gambar']; ?>" width="100" alt="Current Image"></small>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="update" value="Update" class="btn btn-success">
                <a href="index.php?view=ppdb_slider" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
} elseif ($_GET['act'] == 'tambah') {
  if (isset($_POST['tambah'])) {
    // Handle image upload
    $gambar = $_FILES['gambar']['name'];
    $target_dir = "foto_slider/"; // Specify your upload directory

    // Generate a unique random filename
    $random_name = uniqid() . '.' . pathinfo($gambar, PATHINFO_EXTENSION);

    // Define the target file path
    $target_file = $target_dir . $random_name;

    // Move the uploaded file to the target directory with the random name
    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $target_file)) {
      // File successfully uploaded
      echo "The file " . basename($gambar) . " has been uploaded as " . $random_name;
    } else {
      // Failed to upload file
      echo "Sorry, there was an error uploading your file.";
    }

    // Proceed with database insertion
    $query = mysqli_query($conn, "INSERT INTO ppdb_slider(judul, sub_judul, gambar) VALUES('$_POST[judul]', '$_POST[sub_judul]', '$random_name')");
    if ($query) {
      echo "<script>document.location='index.php?view=ppdb_slider&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=ppdb_slider&gagal';</script>";
    }
  }
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah Data Slider </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Judul </label>
              <div class="col-sm-8">
                <input type="text" name="judul" class="form-control" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Sub Judul </label>
              <div class="col-sm-8">
                <input type="text" name="sub_judul" class="form-control" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-3 control-label">Gambar</label>
              <div class="col-sm-8">
                <input type="file" name="gambar" class="form-control" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                <a href="index.php?view=ppdb_slider" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
}
  ?>