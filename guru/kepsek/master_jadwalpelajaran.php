<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
    <h3 class="box-title">Silahkan isi jadwal </h3>
    <a class="btn btn-primary pull-right" target="_blank" href="cetak_jadwal.php?class=<?php echo $_GET['class']; ?>&tahun=<?php echo $_GET['tahun']; ?>"><span class="fa fa-print"></span> Cetak Jadwal</a>
</div><!-- /.box-header -->

<div class="box-body">
    <?php
    $tahun = date('Y');
    // Handle the selected class and year filters
    $classFilter = isset($_GET['class']) ? $_GET['class'] : '';
    $tahunFilter = isset($_GET['tahun']) ? $_GET['tahun'] : '2';

    // Fetch unique class names for the filter dropdown
    $classResult = mysqli_query($conn, "SELECT DISTINCT idKelas, nmKelas FROM kelas_siswa");

    // Fetch unique academic years for the filter dropdown
    $tahunResult = mysqli_query($conn, "SELECT DISTINCT idTahunAjaran,nmTahunAjaran FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
    ?>

    <!-- Class and Tahun Ajaran Filters Form -->
    <form method="GET" action="">
        <input type="hidden" name="view" value="jadwalpelajaran_all" />
        
        <label for="classFilter">Filter by Kelas:</label>
        <select name="class" id="classFilter" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Kelas</option>
            <?php while ($classRow = mysqli_fetch_assoc($classResult)): ?>
                <option value="<?php echo $classRow['idKelas']; ?>" <?php if ($classRow['idKelas'] == $classFilter) echo 'selected'; ?>>
                    <?php echo $classRow['nmKelas']; ?>
                </option>
            <?php endwhile; ?>
        </select>
        
        <label for="tahunFilter">Filter by Tahun Ajaran:</label>
        <select name="tahun" id="tahunFilter" class="form-control" onchange="this.form.submit()">
            <option value="">Semua Tahun Ajaran</option>
            <?php while ($tahunRow = mysqli_fetch_assoc($tahunResult)): ?>
                <option value="<?php echo $tahunRow['idTahunAjaran']; ?>" <?php if ($tahunRow['idTahunAjaran'] == $tahunFilter) echo 'selected'; ?>>
                    <?php echo $tahunRow['nmTahunAjaran']; ?>
                </option>
            <?php endwhile; ?>
        </select>
    </form>

            <br>
      <div class="table-responsive">
        <?php
        if (isset($_GET['sukses'])) {
          echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
					</div>";
        } elseif (isset($_GET['sudah'])) {
          echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, data sudah ada sebelumnya..
					</div>";
        }
        ?>
        <table id="example" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>HARI</th>
              <th> JAM</th>
               <th>MATA PELAJARAN</th>
               <th>KELAS</th> 
                <th>AKSI</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($conn, "SELECT *
            FROM hari ");
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              echo "<tr>
                              <td >$r[nmHari]</td>
                              <td colspan='7'><a class='btn btn-primary btn-xs ' title='Edit Data' href='?view=jadwalpelajaran&act=tambah&id=$r[id]'><span class='glyphicon glyphicon-plus'></span> Tambah </a>
                            </tr>";

             $query = "SELECT rb_jadwal_pelajaran.*, hari.nmHari, rb_mata_pelajaran.namamatapelajaran,
                      jam.idJam, jam.nmJam, jam.dariJam, jam.sampaiJam, kelas_siswa.nmKelas, rb_jadwal_pelajaran.kodejdwl
                      FROM rb_jadwal_pelajaran
                      INNER JOIN hari ON rb_jadwal_pelajaran.hari = hari.nmHari
                      INNER JOIN rb_mata_pelajaran ON rb_jadwal_pelajaran.kode_pelajaran = rb_mata_pelajaran.kode_pelajaran
                      INNER JOIN kelas_siswa ON rb_jadwal_pelajaran.idKelas = kelas_siswa.idKelas
                      INNER JOIN jam ON rb_jadwal_pelajaran.jam_ke = jam.idJam
                      WHERE hari.id = '$r[id]'";

            if ($classFilter != '') {
                $query .= " AND rb_jadwal_pelajaran.idKelas = '$classFilter'";
            }
            if ($tahunFilter != '') {
                $query .= " AND rb_jadwal_pelajaran.idTahunAjaran = '$tahunFilter'";
            }
            $query .= " ORDER BY jam.idJam ASC";
            $tampils = mysqli_query($conn, $query);

              while ($row = mysqli_fetch_array($tampils)) {
                $tgl = date('Y-m-d');
              

                echo "<tr><td></td>
            <td>$row[nmJam] ($row[dariJam]-$row[sampaiJam])</td>
            
           
            <td>$row[namamatapelajaran] </td>
            <td>$row[nmKelas] </td>
            <td style='width:70px !important'><center>
            <a class='btn btn-success btn-xs' title='Edit Jadwal' href='?view=jadwalpelajaran&act=edit&id=$row[kodejdwl]'><span class='glyphicon glyphicon-edit'></span></a>
            <a class='btn btn-danger btn-xs' title='Hapus Jadwal' href='?view=jadwalpelajaran&hapus=$row[kodejdwl]' onclick=\"return confirm('Apakah anda Yakin Data ini Dihapus?')\"><span class='glyphicon glyphicon-remove'></span></a>
            </center></td>
            </tr>";
                $no++;
              }
            }
            if (isset($_GET[hapus])) {
              mysqli_query($conn, "DELETE FROM rb_jadwal_pelajaran where kodejdwl='$_GET[hapus]'");
              echo "<script>document.location='?view=matapelajaran';</script>";
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
    mysqli_query($conn, "UPDATE rb_jadwal_pelajaran SET idTahunAjaran = '$_POST[tahun]',
                                          idKelas = '$_POST[kelas]',
                                          kode_pelajaran = '$_POST[pelajaran]',
                                         nip = '$_POST[nip]',
                                         jam_ke = '$_POST[jam]',
                                           hari = '$_POST[hari]' where kodejdwl='$_POST[id]'");
    echo "<script>document.location='?view=jadwalpelajaran';</script>";
  }
  $edit = mysqli_query($conn, "SELECT * FROM rb_jadwal_pelajaran where kodejdwl='$_GET[id]'");
  $s = mysqli_fetch_array($edit);
  $sqk = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where aktif='Y'"));

  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[kodejdwl]'>
                    <tr><th scope='row'>Tahun Ajaran</th>  <td>
                    <select class='form-control' name='tahun'>
             <option value='0' selected>- Pilih Tahun Ajaran -</option>";
  $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
                while ($t = mysqli_fetch_array($sqltahun)) {
     $selected = ($s['idTahunAjaran'] == $t['idTahunAjaran']) ? ' selected' : '';
        echo '<option value="' . $t['idTahunAjaran'] . '"' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
  }
  echo "</select>
                       
                  </td></tr>
                      <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='kelas'> 
                               <option value='0' selected>- Pilih Kelas -</option>";
  $guru = mysqli_query($conn, "SELECT * FROM kelas_siswa");
  while ($a = mysqli_fetch_array($guru)) {
    if ($s[idKelas] == $a[idKelas]) {
      echo "<option value='$a[idKelas]' selected>$a[nmKelas]</option>";
    } else {
      echo "<option value='$a[idKelas]'>$a[nmKelas]</option>";
    }
  }
  echo "</select>
                      </td></tr>
                      <tr><th scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='pelajaran'> 
                      <option value='0' selected>- Pilih Mata Pelajaran -</option>";
  $mapel = mysqli_query($conn, "SELECT * FROM rb_mata_pelajaran");
  while ($a = mysqli_fetch_array($mapel)) {
    if ($s[kode_pelajaran] == $a[kode_pelajaran]) {
      echo "<option value='$a[kode_pelajaran]' selected>$a[namamatapelajaran]</option>";
    } else {
      echo "<option value='$a[kode_pelajaran]'>$a[namamatapelajaran]</option>";
    }
  }
  echo "</select>
  </td></tr>
            <input type='hidden' name='hari' value='$s[hari]' class='form-control'>
            <input type='hidden' name='nip' value='$_SESSION[nips]' class='form-control'>
                      <tr><th scope='row'>Jam</th><td>";
  $mapel = mysqli_query($conn, "SELECT * FROM jam");
  while ($a = mysqli_fetch_array($mapel)) {
    if ($s[jam_ke] == $a[idJam]) {
      echo "<input type='radio' name='jam' value='$a[idJam]' checked>$a[nmJam] <b>($a[dariJam]-$a[sampaiJam] WIB)</b><br>";
    } else {
      echo "<input type='radio' name='jam' value='$a[idJam]'>$a[nmJam] <b>($a[dariJam]-$a[sampaiJam] WIB)</b><br>";
    }
  }
  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='?view=jadwalpelajaran'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    $cek = mysqli_query($conn, "SELECT * FROM rb_jadwal_pelajaran where idTahunAjaran='$_POST[tahun]' 
    AND idKelas='$_POST[d]' and kode_pelajaran='$_POST[c]' and jam_ke='$_POST[m]' and hari='$_POST[hari]'");
    $total = mysqli_num_rows($cek);

    if ($total >= 1) {
      echo "<script>document.location='?view=jadwalpelajaran&sudah';</script>";
    } else {
      $m = $_POST[m];
      $jml_dipilih = count($m);

      for ($x = 0; $x < $jml_dipilih; $x++) {
        $query = mysqli_query($conn, "INSERT INTO rb_jadwal_pelajaran VALUES('','$_POST[tahun]','$_POST[d]'
      ,'$_POST[c]','$_POST[z]','$_POST[nip]','$m[$x]','$_POST[hari]','Ya')");
        echo "<script>document.location='?view=jadwalpelajaran&sukses';</script>";
      }
    }
  }

  $r = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM hari where id='$_GET[id]'
  "));
  $sqk = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where aktif='Y'"));

  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Jadwal Pelajaran Hari : $r[nmHari]</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>         
                  <tr><th scope='row'>Tahun Ajaran</th>  <td>
                    <select class='form-control' name='tahun'>
             <option value='0' selected>- Pilih Tahun Ajaran -</option>";
  $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
                while ($t = mysqli_fetch_array($sqltahun)) {
     $selected = ($_GET['idTahunAjaran'] == $t['idTahunAjaran']) ? ' selected' : '';
        echo '<option value="' . $t['idTahunAjaran'] . '"' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
  }
  echo "</select>
                       
                  </td></tr>
                    <tr><th scope='row'>Kelas</th> <td><select class='form-control' name='d'> 
                             <option value='0' selected>- Pilih Kelas -</option>";
  $guru = mysqli_query($conn, "SELECT * FROM kelas_siswa");
  while ($a = mysqli_fetch_array($guru)) {
    echo "<option value='$a[idKelas]'>$a[nmKelas]</option>";
  }
  echo "</select>
                    </td></tr>
                    <tr><th scope='row'>Mata Pelajaran</th>   <td><select class='form-control' name='c'> 
                    <option value='0' selected>- Pilih Mata Pelajaran -</option>";
  $mapel = mysqli_query($conn, "SELECT * FROM rb_mata_pelajaran");
  while ($a = mysqli_fetch_array($mapel)) {
    echo "<option value='$a[kode_pelajaran]'>$a[namamatapelajaran]</option>";
  }
  echo "</select>
</td></tr>
          <input type='hidden' name='hari' value='$r[nmHari]' class='form-control'>
          <tr><th scope='row'>Guru</th>   <td><select class='form-control' name='nip'> 
          <option value='0' selected>- Pilih Guru -</option>";
  $mapel = mysqli_query($conn, "SELECT * FROM rb_guru");
  while ($a = mysqli_fetch_array($mapel)) {
    echo "<option value='$a[nip]'>$a[nama_guru]</option>";
  }
  echo "</select>
</td></tr>
                    <tr><th scope='row'>Jam</th><td>";
  $mapel = mysqli_query($conn, "SELECT * FROM jam");
  while ($a = mysqli_fetch_array($mapel)) {
    echo "<input type='checkbox' name='m[]' value='$a[idJam]'>$a[nmJam] <b>($a[dariJam]-$a[sampaiJam] WIB)</b><br>";
  }
  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='?view=jadwalpelajaran'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
}

?>