<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data SK Tahunan </h3>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?view=sktahunan&act=tambah'>Tambahkan Data</a>
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
            <th>Tahun Ajaran</th>
            <th>Nama Guru</th>
            <th>Keterangan</th></th>
            <th>File</th> <!-- Kolom baru untuk tombol lihat file -->
            <th>Aksi</th>
            
        </tr>
    </thead>
    <tbody>
        <?php
        $tampil = mysqli_query($conn, "SELECT *,rb_sk_tahunan.id as Id FROM rb_sk_tahunan 
        INNER JOIN rb_guru ON rb_sk_tahunan.nik=rb_guru.id
        INNER JOIN tahun_ajaran ON rb_sk_tahunan.idTahunAjaran=tahun_ajaran.idTahunAjaran
        ORDER BY rb_sk_tahunan.idTahunAjaran ASC");
        $no = 1;
        while ($r = mysqli_fetch_array($tampil)) {
            echo "<tr>
                    <td>$no</td>
                    <td>$r[nmTahunAjaran]</td>
                    <td>$r[nama_guru]</td>
                    <td>$r[judul_file]</td>
                     <td>
                       
                            <a class='btn btn-info btn-xs' title='Lihat File' href='$r[file]' target='_blank'>
                                <span class='glyphicon glyphicon-eye-open'></span> Lihat File
                            </a>
                    </td>
                    <td>
                        <center>
                            <a class='btn btn-success btn-xs' title='Edit Data' href='?view=sktahunan&act=edit&id=$r[Id]'>
                                <span class='glyphicon glyphicon-edit'></span>
                            </a>
                            <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=sktahunan&hapus&id=$r[Id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                                <span class='glyphicon glyphicon-remove'></span>
                            </a>
                        </center>
                    </td>
                   
                  </tr>";
            $no++;
        }
        if (isset($_GET['hapus'])) {
            mysqli_query($conn, "DELETE FROM rb_sk_tahunan WHERE id='$_GET[id]'");
            echo "<script>document.location='index.php?view=sktahunan';</script>";
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

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // Cek ukuran file (maks 40MB)
        if ($_FILES['file']['size'] <= 40 * 1024 * 1024) { // 40 MB
            $target_dir = "file_sk/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            
            // Move the uploaded file to the desired directory
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            
            // Update query with file path
            $query = mysqli_query($conn, "UPDATE rb_sk_tahunan SET nik='$nik', judul_file='$judul_file', idTahunAjaran='$idTahunAjaran', file='$target_file' WHERE id='$_POST[id]'");
        } else {
            echo "<script>alert('Ukuran file terlalu besar. Maksimal 40MB.');</script>";
        }
    } else {
        // Update without changing the file
        $query = mysqli_query($conn, "UPDATE rb_sk_tahunan SET nik='$nik', judul_file='$judul_file' WHERE id='$_POST[id]'");
    }

    if ($query) {
        echo "<script>document.location='index.php?view=sktahunan&sukses';</script>";
    } else {
        echo "<script>document.location='index.php?view=sktahunan&gagal';</script>";
    }
  }
  
  // Remainder of the edit form...

  $edit = mysqli_query($conn, "SELECT * FROM rb_sk_tahunan where id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
     <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Data SK</h3>
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
							</select>              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Guru</label>
              <div class="col-sm-4">
                	<select name="guru" data-live-search="true" class="form-control selectpicker">
								<option value="">- Pilih guru -</option>
								<?php
								$sqlSiswa = mysqli_query($conn, "SELECT * FROM rb_guru order by id");
								while ($s = mysqli_fetch_array($sqlSiswa)) {
								    	$selected = ($s['id'] == $record['nik']) ? ' selected="selected"' : "";

									echo '<option value="'. $s['id']. ' " ' . $selected . '>'.$s[nama_guru].' </option>';
								}
								?>
							</select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-4">

                <input type="text" name="judul_file" class="form-control" value="<?php echo $record['judul_file']; ?>" required>
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
                <a href="index.php?view=sktahunan" class="btn btn-default">Cancel</a>
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

    // Handle file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] == 0) {
        // Cek ukuran file (maks 40MB)
        if ($_FILES['file']['size'] <= 40 * 1024 * 1024) { // 40 MB
            $target_dir = "file_sk/";
            $target_file = $target_dir . basename($_FILES["file"]["name"]);
            
            // Move the uploaded file to the desired directory
            move_uploaded_file($_FILES["file"]["tmp_name"], $target_file);
            
            // Insert query with file path
            $query = mysqli_query($conn, "INSERT INTO rb_sk_tahunan (nik, idTahunAjaran, judul_file, file) VALUES ('$nik','$idTahunAjaran', '$judul_file', '$target_file')");
        } else {
            echo "<script>alert('Ukuran file terlalu besar. Maksimal 40MB.');</script>";
        }
    }

    if ($query) {
        echo "<script>document.location='index.php?view=sktahunan&sukses';</script>";
    } else {
        echo "<script>document.location='index.php?view=sktahunan&gagal';</script>";
    }
  }
  ?>
   <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Tambah Data SK </h3>
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
									$selected = ($t['idTahunAjaran'] == $thn_ajar) ? ' selected="selected"' : "";
									echo '<option value="' . $t['idTahunAjaran'] . '" ' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
								}
								?>
							</select>              
							</div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Guru</label>
              <div class="col-sm-4">
                	<select name="guru" data-live-search="true" class="form-control selectpicker">
								<option value="">- Pilih guru -</option>
								<?php
								$sqlSiswa = mysqli_query($conn, "SELECT * FROM rb_guru order by id");
								while ($s = mysqli_fetch_array($sqlSiswa)) {
									echo "<option value='$s[id]'>$s[nama_guru] </option>";
								}
								?>
							</select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Keterangan</label>
                            <div class="col-sm-4">

                <input type="text" name="judul_file" class="form-control" value="<?php echo $record['judul_file']; ?>" required>
              </div> </div>
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
                <a href="index.php?view=sktahunan" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
}
  ?>