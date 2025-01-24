<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
  <div class="box box-warning">
      <div class="box-header">
        <h3 class="box-title">Data Mata Pelajaran </h3>
        <?php if ($_SESSION[level] != 'kepala') { ?>
          <a class='pull-right btn btn-primary btn-sm' href='?view=matapelajaran&act=tambah'>Tambahkan Data</a>
        <?php } ?>
      </div><!-- /.box-header -->
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th style='width:30px'>No</th>
              <th>Kode Mapel</th>
              <th>Nama Mapel</th>
              <th>Guru Pengampu</th>
              <?php if ($_SESSION[level] != 'kepala') { ?>
                <th style='width:70px'>Action</th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($conn, "SELECT * FROM rb_mata_pelajaran a 
                                                LEFT JOIN rb_guru c ON a.nip=c.nbm 
                                                       ");
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              echo "<tr><td>$no</td>
                              <td>$r[kode_pelajaran]</td>
                              <td>$r[namamatapelajaran]</td>
                              <td>$r[nama_guru]</td>
                           ";
              if ($_SESSION[level] != 'kepala') {
                echo "<td><center>
                                <a class='btn btn-primary btn-xs' title='Detail Data' href='?view=matapelajaran&act=detail&id=$r[kode_pelajaran]'><span class='glyphicon glyphicon-search'></span></a>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=matapelajaran&act=edit&id=$r[kode_pelajaran]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=matapelajaran&hapus=$r[kode_pelajaran]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini? ')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
              }
              echo "</tr>";
              $no++;
            }
            if (isset($_GET[hapus])) {
              mysqli_query($conn, "DELETE FROM rb_mata_pelajaran where kode_pelajaran='$_GET[hapus]'");
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
    mysqli_query($conn, "UPDATE rb_mata_pelajaran SET kode_pelajaran = '$_POST[a]',
                                         nip = '$_POST[d]',
                                         namamatapelajaran = '$_POST[f]',
                                           aktif = '$_POST[m]' where kode_pelajaran='$_POST[id]'");
    echo "<script>document.location='?view=matapelajaran';</script>";
  }
  $edit = mysqli_query($conn, "SELECT * FROM rb_mata_pelajaran where kode_pelajaran='$_GET[id]'");
  $s = mysqli_fetch_array($edit);
  echo "<div class='col-md-12'>
              <div class='box box-warning'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[kode_pelajaran]'>
                   
                    <tr><th scope='row'>Kode Pelajaran</th>       <td><input type='text' class='form-control' name='a' value='$s[kode_pelajaran]'> </td></tr>
                    <tr><th scope='row'>Nama Mapel</th>           <td><input type='text' class='form-control' name='f' value='$s[namamatapelajaran]'></td></tr>
                  
                    <tr><th scope='row'>Guru Pengampu</th> <td><select class='form-control' name='d'> 
                             <option value='0' selected>- Pilih Guru Pengampu -</option>";
  $guru = mysqli_query($conn, "SELECT * FROM rb_guru");
  while ($a = mysqli_fetch_array($guru)) {
    if ($s[nip] == $a[nbm]) {
      echo "<option value='$a[nbm]' selected>$a[nama_guru]</option>";
    } else {
      echo "<option value='$a[nbm]'>$a[nama_guru]</option>";
    }
  }
  echo "</select>
                    </td></tr>
                 
               
                    <tr><th scope='row'>Aktif</th>                <td>";
  if ($s[aktif] == 'Ya') {
    echo "<input type='radio' name='m' value='Ya' checked> Ya
                                                                             <input type='radio' name='m' value='Tidak'> Tidak";
  } else {
    echo "<input type='radio' name='m' value='Ya'> Ya
                                                                             <input type='radio' name='m' value='Tidak' checked> Tidak";
  }
  echo "</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='update' class='btn btn-info'>Update</button>
                    <a href='?view=matapelajaran'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    mysqli_query($conn, "INSERT INTO rb_mata_pelajaran VALUES('$_POST[a]','$_POST[d]','$_POST[f]','$_POST[m]')");
    echo "<script>document.location='?view=matapelajaran';</script>";
  }

  echo "<div class='col-md-12'>
              <div class='box box-warning'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                   
                    <tr><th scope='row'>Kode Pelajaran</th>       <td><input type='text' class='form-control' name='a' value='$s[kode_pelajaran]'> </td></tr>
                    <tr><th scope='row'>Nama Mapel</th>           <td><input type='text' class='form-control' name='f' value='$s[namamatapelajaran]'></td></tr>
              
                  
                    <tr><th scope='row'>Guru Pengampu</th> <td><select class='form-control' name='d'> 
                             <option value='0' selected>- Pilih Guru Pengampu -</option>";
  $guru = mysqli_query($conn, "SELECT * FROM rb_guru");
  while ($a = mysqli_fetch_array($guru)) {
    echo "<option value='$a[nbm]'>$a[nama_guru]</option>";
  }
  echo "</select>
                    </td></tr>
  
                            
                  
                    <tr><th scope='row'>Aktif</th>                <td><input type='radio' name='m' value='Ya' checked> Ya
                                                                             <input type='radio' name='m' value='Tidak'> Tidak</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                    <a href='?view=matapelajaran'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                    
                  </div>
              </form>
            </div>";
} elseif ($_GET[act] == 'detail') {
  $edit = mysqli_query($conn, "SELECT a.*, c.nama_guru FROM rb_mata_pelajaran a 
                                            
                                                JOIN rb_guru c ON a.nip=c.nbm
                                             
                                                      where a.kode_pelajaran='$_GET[id]'");
  $s = mysqli_fetch_array($edit);
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Detail Data Mata Pelajaran</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-12'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                
                    <tr><th scope='row'>Kode Pelajaran</th>       <td>$s[kode_pelajaran] </td></tr>
                    <tr><th scope='row'>Nama Mapel</th>           <td>$s[namamatapelajaran]</td></tr>
                    <tr><th scope='row'>Guru Pengampu</th>        <td>$s[nama_guru]</td></tr>
                    <tr><th scope='row'>Aktif</th>                <td>$s[aktif]</td></tr>
                  </tbody>
                  </table>
                </div>
              </div>
              <div class='box-footer'>
                    <a href='?view=matapelajaran'><button type='button' class='btn btn-default pull-right'>Kembali</button></a>
                    
                  </div>
              </form>
            </div>";
}
?>