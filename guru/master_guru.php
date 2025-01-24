<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box">
      <div class="box-header">
        <h3 class="box-title">Semua Data Guru </h3>

        <a class='pull-right btn btn-primary btn-sm' href='index-guru.php?view=mastermasterguru&act=tambahguru'>Tambahkan Data Guru</a>
        <a style='margin-right:5px' class='pull-right btn btn-danger btn-sm' href='index-guru.php?view=mastermasterguru&act=imports'>
          <i class='fa fa-file-excel-o'></i> Import Data Guru
        </a>

      </div><!-- /.box-header -->
      <div class="table-responsive">
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>NIP</th>
              <th>Nama Lengkap</th>
              <th>Jenis Kelamin</th>
              <th>No Telpon</th>
              <th>Status Pegawai</th>
              <th>Jenis PTK</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($conn, "SELECT * FROM rb_guru a 
                                          LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                            LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                              LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk
                                              ORDER BY a.nip DESC");
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              $tanggal = tgl_indo($r[tgl_posting]);
              echo "<tr><td>$no</td>
                              <td>$r[nip]</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[jenis_kelamin]</td>
                              <td>$r[hp]</td>
                              <td>$r[status_kepegawaian]</td>
                              <td>$r[jenis_ptk]</td>";
              if ($_SESSION[level] != 'kepala') {
                echo "<td><center>
                                <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=masterguru&act=detailguru&id=$r[nip]'><span class='glyphicon glyphicon-search'></span></a>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=masterguru&act=editguru&id=$r[nip]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=guru&hapus=$r[nip]'><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
              } else {
                echo "<td><center>
                                <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=masterguru&act=detailguru&id=$r[nip]'><span class='glyphicon glyphicon-search'></span></a>
                              </center></td>";
              }
              echo "</tr>";
              $no++;
            }
            if (isset($_GET[hapus])) {
              mysqli_query($conn, "DELETE FROM rb_guru where nip='$_GET[hapus]'");
              echo "<script>document.location='index-guru.php?view=guru';</script>";
            }

            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>


<?php

} elseif ($_GET[act] == 'editguru') {
  if (isset($_POST[update1])) {
    $rtrw = explode('/', $_POST[al]);
    $rt = $rtrw[0];
    $rw = $rtrw[1];
    $dir_gambar = 'foto_pegawai/';
    $filename = basename($_FILES['ax']['name']);
    $filenamee = date("YmdHis") . '-' . basename($_FILES['ax']['name']);
    $uploadfile = $dir_gambar . $filenamee;
    if ($filename != '') {
      if (move_uploaded_file($_FILES['ax']['tmp_name'], $uploadfile)) {
        mysqli_query($conn, "UPDATE rb_guru SET 
                           email          = '$_POST[aa]',
                           password     = '$_POST[ab]',
                           foto = '$filenamee'
						                where nip='$_POST[id]'");
      }
    } else {
      mysqli_query($conn, "UPDATE rb_guru SET 
                           email          = '$_POST[aa]',
                           password     = '$_POST[ab]'
                           where id='$_POST[id]'");
    }
    echo "<script>document.location='index-guru.php?view=masterguru&act=editguru&id=" . $_POST[id] . "';</script>";
  }

  $detail = mysqli_query($conn, "SELECT * FROM rb_guru where id='$_GET[id]'");
  $s = mysqli_fetch_array($detail);
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Edit Data Guru</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-7'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[nip]'>
                    <tr><th style='background-color:#E7EAEC' width='160px' rowspan='25'>";
  if (trim($s[foto]) == '') {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
  } else {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_pegawai/$s[foto]'>";
  }
  echo "</th>
                    </tr>
                    <input type='hidden' name='id' value='$s[id]'>
                     <tr><th width='120px' scope='row'>Email</th>      <td><input type='text' class='form-control' value='$s[email]' name='aa' readonly></td></tr>
                      <tr><th width='120px' scope='row'>NBM</th>      <td><input type='text' class='form-control' value='$s[nbm]' name='aa' readonly></td></tr>
                      <tr><th width='120px' scope='row'>NUPTK</th>      <td><input type='text' class='form-control' value='$s[nuptk]' name='aa' readonly></td></tr>
                <!--    <tr><th scope='row'>Password</th>               <td><input type='text' class='form-control' value='$s[password]' name='ab'></td></tr> -->
                    <tr><th scope='row'>Ganti Foto</th>             <td><div style='position:relative;''>
                    <a class='btn btn-warning' href='javascript:;'>
                      <span class='glyphicon glyphicon-search'></span> Browse..."; ?>
  <input type='file' class='files' name='ax' accept='.png, .jpg, .jpeg' onchange='$("#upload-file-info").html($(this).val());'>
<?php echo "</a> <span style='width:155px' class='label label-info' id='upload-file-info'></span>
                  </div>
</td></tr> 
                  </tbody>
                  </table>
                </div> 
                <div style='clear:both'></div>
                        <div class='box-footer'>
                          <button type='submit' name='update1' class='btn btn-info'>Update</button>
                          <a href='index-guru.php?view=homeguru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                        </div> 
              </div>
            </form>
            </div>";
} elseif ($_GET[act] == 'detailguru') {
  $detail = mysqli_query($conn, "SELECT a.*, b.jenis_kelamin, c.status_kepegawaian, d.jenis_ptk, e.nama_agama, f.nama_status_keaktifan, g.nama_golongan, h.status_pernikahan 
                                FROM rb_guru a LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                  LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                    LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk 
                                      LEFT JOIN rb_agama e ON a.id_agama=e.id_agama 
                                        LEFT JOIN rb_status_keaktifan f ON a.id_status_keaktifan=f.id_status_keaktifan 
                                          LEFT JOIN rb_golongan g ON a.id_golongan=g.id_golongan
                                            LEFT JOIN rb_status_pernikahan h ON a.id_status_pernikahan=h.id_status_pernikahan
                                              where a.nip='$_GET[id]'");
  $s = mysqli_fetch_array($detail);
  echo "<div class='col-md-12'>
              <div class='box box-info'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Detail Data Guru</h3>
                </div>
              <div class='table-responsive'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-7'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <input type='hidden' name='id' value='$s[nip]'>
                    <tr><th style='background-color:#E7EAEC' width='160px' rowspan='25'>";
  if (trim($s[foto]) == '') {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
  } else {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_pegawai/$s[foto]'>";
  }
  if ($_SESSION[level] != 'kepala') {
    echo "<a href='index-guru.php?view=masterguru&act=editguru&id=$_GET[id]' class='btn btn-success btn-block'>Edit Profile</a>";
  }
  echo "</th>
                    </tr>
                    <tr><th width='120px' scope='row'>Nip</th>      <td>$s[nip]</td></tr>
                    <tr><th scope='row'>Password</th>               <td>$s[password]</td></tr>
                  
                  </tbody>
                  </table>
                </div> 
              </div>
            </form>
            </div>";
}
?>