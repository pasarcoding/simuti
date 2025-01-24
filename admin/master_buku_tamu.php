<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <!-- Filter Bulan dan Tahun -->
        <form method="GET" class="form-inline">
  <input name="view" type="hidden" value="buku_tamu">

  <label for="bulan" class="mr-2">Bulan:</label>
  <select name="bulan" id="bulan" class="form-control mr-2">
    <option value="">Pilih Bulan</option>
    <?php
    // Membuat opsi bulan
    for ($i = 1; $i <= 12; $i++) {
      $bulan = str_pad($i, 2, "0", STR_PAD_LEFT);  // Menambahkan 0 di depan angka bulan
      echo "<option value='$bulan' " . (isset($_GET['bulan']) && $_GET['bulan'] == $bulan ? 'selected' : '') . ">" . date("F", mktime(0, 0, 0, $bulan, 10)) . "</option>";
    }
    ?>
  </select>

  <label for="tahun" class="mr-2">Tahun:</label>
  <select name="tahun" id="tahun" class="form-control mr-2">
    <option value="">Pilih Tahun</option>
    <?php
    // Menampilkan daftar tahun (misalnya dari tahun 2020 sampai tahun sekarang)
    $currentYear = date("Y");
    for ($y = 2024; $y <= $currentYear; $y++) {
      echo "<option value='$y' " . (isset($_GET['tahun']) && $_GET['tahun'] == $y ? 'selected' : '') . ">$y</option>";
    }
    ?>
  </select>

  <button type="submit" class="btn btn-primary">Filter</button>
  <!-- Tombol Print -->
<a href="cetak_buku_tamu.php?bulan=<?php echo $_GET['bulan']; ?>&tahun=<?php echo $_GET['tahun']; ?>" class="btn btn-success mt-3" target="_blank">
  <span class="glyphicon glyphicon-print"></span> Print
</a>
</form>



      </div><!-- /.box-header -->
      <div class="table-responsive">
        <?php
        // Menampilkan pesan sukses atau gagal jika ada
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
      <th>Nama</th>
      <th>No Telp</th>
       <th>Alamat</th>
      <th>Instansi</th>
      <th>Jenis Kelamin</th>
      <th>Bertemu </th>
       <th>Tanggal</th>
      <th>Jam Masuk</th> <!-- Menambahkan kolom Jam Masuk -->
      <th>Keperluan</th>
      <th>Foto</th>
    </tr>
  </thead>
  <tbody>
    <?php
    // Memeriksa apakah bulan dan tahun telah dipilih
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

    // Menyusun query berdasarkan filter bulan dan tahun
    $where = "";
    if ($bulan && $tahun) {
      // Menambahkan filter untuk bulan dan tahun
      $where = "WHERE MONTH(tanggal) = '$bulan' AND YEAR(tanggal) = '$tahun'";
    } elseif ($bulan) {
      // Jika hanya bulan yang dipilih
      $where = "WHERE MONTH(tanggal) = '$bulan'";
    } elseif ($tahun) {
      // Jika hanya tahun yang dipilih
      $where = "WHERE YEAR(tanggal) = '$tahun'";
    }

    // Query untuk mengambil data buku tamu dengan filter
    $tampil = mysqli_query($conn, "SELECT *, DATE_FORMAT(tanggal, '%H:%i') AS jam_masuk FROM tamu $where ORDER BY id ASC");
    $no = 1;
    while ($r = mysqli_fetch_array($tampil)) {
      // Menampilkan data buku tamu beserta foto dan jam masuk
      echo "<tr>
              <td>$no</td>
              <td>$r[nama]</td>
              <td>$r[no_telp]</td>
              <td>$r[alamat]</td>
              <td>$r[instansi]</td>
              <td>$r[jenis_kelamin]</td>
              <td>$r[bertemu]</td>
              <td>". tgl_indo($r[tanggal])."</td>
              <td>$r[jam_masuk]</td>
              <td>$r[keperluan]</td>";

      // Menampilkan foto, jika ada foto yang disimpan di kolom 'foto'
      echo "<td><img src='bukutamu/$r[foto]' alt='Foto' width='100' height='75'></td>";

      // Menampilkan jam masuk

      // Aksi edit dan delete
      echo "</tr>";
      $no++;
    }

    // Proses penghapusan data
    if (isset($_GET['hapus'])) {
      mysqli_query($conn, "DELETE FROM buku_tamu WHERE idBukuTamu='$_GET[id]'");
      echo "<script>document.location='index.php?view=bukutamu';</script>";
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

    $query = mysqli_query($conn, "UPDATE jam SET nmJam='$_POST[nmJam]', 
										 dariJam='$_POST[dariJam]', sampaiJam='$_POST[sampaiJam]'  where idJam = '$_POST[id]'");
    if ($query) {
      echo "<script>document.location='index.php?view=jam&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=jam&gagal';</script>";
    }
  }
  $edit = mysqli_query($conn, "SELECT * FROM jam where idJam='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
?>
  <div class="col-md-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"> Edit Data Jam</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="post" action="" class="form-horizontal">
          <input type="hidden" name="id" value="<?php echo $record['idJam']; ?>">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Jam</label>
            <div class="col-sm-4">
              <input type="text" name="nmJam" class="form-control" value="<?php echo $record['nmJam']; ?>" required>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Dari Jam</label>
            <div class="col-sm-6">
              <input type="time" name="dariJam" class="form-control" value="<?php echo $record['dariJam']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Sampai Jam</label>
            <div class="col-sm-6">
              <input type="time" name="sampaiJam" class="form-control" value="<?php echo $record['sampaiJam']; ?>">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="update" value="Update" class="btn btn-success">
              <a href="index.php?view=jam" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

<?php
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    $query = mysqli_query($conn, "INSERT INTO jam(nmJam,dariJam,sampaiJam) VALUES('$_POST[nmJam]','$_POST[dariJam]','$_POST[sampaiJam]')");
    if ($query) {
      echo "<script>document.location='index.php?view=jam&sukses';</script>";
    } else {
      echo "<script>document.location='index.php?view=jam&gagal';</script>";
    }
  }
?>
  <div class="col-md-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title"> Tambah Data Jam </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="POST" action="" class="form-horizontal">
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Nama Jam</label>
            <div class="col-sm-4">
              <input type="text" name="nmJam" class="form-control" id="" placeholder="Nama Jam" required>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Dari Jam</label>
            <div class="col-sm-6">
              <input type="time" name="dariJam" class="form-control" id="">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Sampai Jam</label>
            <div class="col-sm-6">
              <input type="time" name="sampaiJam" class="form-control" id="">
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
              <a href="index.php?view=jam" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
}
?>