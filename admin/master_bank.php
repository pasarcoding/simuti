<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data Bank </h3>
        <a class='pull-right btn btn-primary btn-sm' href='index.php?view=bank&act=tambah'>Tambahkan Data</a>
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
                <th>Nama Bank</th>
                <th>Atas Nama</th>
                <th>Nomor Rekening</th>
                <th>Logo</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $tampil = mysqli_query($conn, "SELECT * FROM bank ORDER BY id ASC");
              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr><td>$no</td>
                              <td>$r[nmBank]</td>
                              <td>$r[atasNama]</td>
                              <td>$r[noRek]</td>
                              <td>"; ?>
                <a href="#" onclick="window.open('./gambar/<?php echo $r['gambar']; ?>','popuppage','width=600,toolbar=0,resizable=0,scrollbars=no,height=600,top=100,left=300');" data-toggle='tooltip' title="Lihat Bukti Upload" class="btn btn-warning btn-xs"><i class="fa fa-eye"></i> Lihat</a>
              <?php echo " <td><center>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=bank&act=edit&id=$r[id]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=bank&hapus&id=$r[id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                echo "</tr>";
                $no++;
              }
              if (isset($_GET[hapus])) {
                mysqli_query($conn, "DELETE FROM bank where id='$_GET[id]'");
                echo "<script>document.location='index.php?view=bank';</script>";
              }

              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  <?php
} elseif ($_GET[act] == 'edit') {
  if (isset($_POST[update])) {
    $lokasi_file_kanan = $_FILES['flogobank']['tmp_name'];
    $foto_kartanu = $_FILES['flogobank']['name'];
    if (!empty($lokasi_file_kanan)) {
      UploadLogoBank($foto_kartanu);
      $query = mysqli_query($conn, "UPDATE bank SET nmBank='$_POST[nmBank]', 
      atasNama='$_POST[atasNama]', 
      noRek='$_POST[noRek]',gambar ='$foto_kartanu' where id = '$_POST[id]'");
    } else {
      $query = mysqli_query($conn, "UPDATE bank SET nmBank='$_POST[nmBank]', 
      atasNama='$_POST[atasNama]', 
      noRek='$_POST[noRek]' where id = '$_POST[id]'");
    }


    if ($query) {
      echo "<script>document.location='index.php?view=bank&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=bank&gagal';</script>";
    }
  }
  $edit = mysqli_query($conn, "SELECT * FROM bank where id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Data bank</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nama Bank</label>
              <div class="col-sm-4">
                <input type="text" name="nmBank" class="form-control" value="<?php echo $record['nmBank']; ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Atas Nama</label>
              <div class="col-sm-6">
                <input type="text" name="atasNama" class="form-control" value="<?php echo $record['atasNama']; ?>">
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nomor Rekening</label>
              <div class="col-sm-6">
                <input type="number" name="noRek" class="form-control" value="<?php echo $record['noRek']; ?>">
              </div>
            </div>
            <div class="form-group" id="imageUpload" style="<?= $listBank['idBank'] === '0' ? 'display: none;' : '' ?>">
              <label for="" class="col-sm-2 control-label">Gambar</label>
              <div class="col-sm-4">
                <input type="file" class="upload-file bg-warning shadow-s rounded-s " id="bukti" name="flogobank" accept="image/*">
                <img src='./gambar/<?= $record['gambar'] ?>' alt='image' class='gambar' width="150">
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="update" value="Update" class="btn btn-success">
                <a href="index.php?view=bank" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

  <?php
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    $lokasi_file_kanan = $_FILES['flogobank']['tmp_name'];
    $foto_kartanu = $_FILES['flogobank']['name'];
    
    if (!empty($lokasi_file_kanan)) {
      UploadLogoBank($foto_kartanu);
      $query = mysqli_query($conn, "INSERT INTO bank(nmBank,atasNama,noRek,gambar) VALUES('$_POST[nmBank]','$_POST[atasNama]','$_POST[noRek]','$foto_kartanu')");
    } else {
      $query = mysqli_query($conn, "INSERT INTO bank(nmBank,atasNama,noRek) VALUES('$_POST[nmBank]','$_POST[atasNama]','$_POST[noRek]')");
    }



    if ($query) {
      echo "<script>document.location='index.php?view=bank&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=bank&gagal';</script>";
    }
  }
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Tambah Data bank </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal" enctype="multipart/form-data">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nama Bank</label>
              <div class="col-sm-4">
                <input type="text" name="nmBank" class="form-control" placeholder="Nama Bank" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Atas Nama</label>
              <div class="col-sm-6">
                <input type="text" name="atasNama" class="form-control" placeholder="Atas Nama">
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nomor Rekening</label>
              <div class="col-sm-6">
                <input type="number" name="noRek" class="form-control" placeholder="Nomor Rekening">
              </div>
            </div>
            <div class="form-group" id="imageUpload" style="<?= $listBank['idBank'] === '0' ? 'display: none;' : '' ?>">
              <label for="" class="col-sm-2 control-label">Gambar</label>
              <div class="col-sm-4">
                <input type="file" class="upload-file bg-warning shadow-s rounded-s " id="bukti" name="flogobank" accept="image/*">
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
                <a href="index.php?view=bank" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
}
  ?>