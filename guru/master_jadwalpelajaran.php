<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Jadwal Pelajaran</h3>
				<a class="btn btn-primary pull-right" target="_blank" href="cetak_jadwal_guru.php?class=<?php echo $_GET['class']; ?>"><span class="fa fa-print"></span> Cetak </a>

      </div><!-- /.box-header -->
            <div class="box-body">
        
            <?php
            // Handle the selected class filter
            $classFilter = isset($_GET['class']) ? $_GET['class'] : '';
            
            // Fetch unique class names for the filter dropdown
            $classResult = mysqli_query($conn, "SELECT DISTINCT idKelas,nmKelas FROM kelas_siswa");
            ?>
            
            <!-- Class Filter Dropdown -->
            <form method="GET" action="">
              <label for="classFilter">Filter by Kelas:</label>
					<input type="hidden" name="view" value="jadwalpelajaran" />
              <select name="class" id="classFilter" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                <?php while ($classRow = mysqli_fetch_assoc($classResult)): ?>
                  <option value="<?php echo $classRow['idKelas']; ?>" <?php if ($classRow['idKelas'] == $classFilter) echo 'selected'; ?>>
                    <?php echo $classRow['nmKelas']; ?>
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
            </tr>
          </thead>
          <tbody>
            <?php
            $sqk = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where aktif='Y'"));
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
                    INNER JOIN rb_guru ON rb_mata_pelajaran.nip = rb_guru.nbm

                      INNER JOIN kelas_siswa ON rb_jadwal_pelajaran.idKelas = kelas_siswa.idKelas
                      INNER JOIN jam ON rb_jadwal_pelajaran.jam_ke = jam.idJam
                      WHERE hari.id = '$r[id]' and rb_guru.nbm='$_SESSION[nbm]'";

            if ($classFilter != '') {
                $query .= " AND rb_jadwal_pelajaran.idKelas = '$classFilter'";
            }

            $query .= " ORDER BY jam.idJam ASC";
            $tampils = mysqli_query($conn, $query);


              while ($row = mysqli_fetch_array($tampils)) {
                $tgl = date('Y-m-d');
              

                echo "<tr><td></td>
            <td>$row[nmJam] ($row[dariJam]-$row[sampaiJam])</td>
            
           
            <td>$row[namamatapelajaran] </td>
            <td>$row[nmKelas] </td>
            
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
                                          semester = '$_POST[semester]',
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
                    <tr><th scope='row'>Semester</th>  <td>
                    <select class='form-control' name='semester'>
             
                      <option value='$s[semester]'>$s[semester]</option>
                      <option value='Semester Ganjil'>Semester Ganjil</option>
                      <option value='Semester Genap'>Semester Genap</option>
                      
                    </select>
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
            <input type='hidden' name='tahun' value='$sqk[idTahunAjaran]' class='form-control'>
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
    $cek = mysqli_query($conn, "SELECT * FROM rb_jadwal_pelajaran where idTahunAjaran='$_POST[idTahunAjaran]' 
    AND idKelas='$_POST[d]' and kode_pelajaran='$_POST[c]' and semester='$_POST[z]' and jam_ke='$_POST[m]' and hari='$_POST[hari]'");
    $total = mysqli_num_rows($cek);

    if ($total >= 1) {
      echo "<script>document.location='?view=jadwalpelajaran&sudah';</script>";
    } else {
      $m = $_POST[m];
      $jml_dipilih = count($m);

      for ($x = 0; $x < $jml_dipilih; $x++) {
        $query = mysqli_query($conn, "INSERT INTO rb_jadwal_pelajaran VALUES('','$_POST[idTahunAjaran]','$_POST[d]'
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
                  <tr><th scope='row'>Semester</th>  <td>
									<select class='form-control' name='z'>
										<option value='0'>- Pilih Semester -</option>
										<option value='Semester Ganjil'>Semester Ganjil</option>
										<option value='Semester Genap'>Semester Genap</option>
										
									</select>
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
          <input type='hidden' name='idTahunAjaran' value='$sqk[idTahunAjaran]' class='form-control'>
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