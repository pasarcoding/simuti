<?php
require 'vendor/autoload.php';


use PhpOffice\PhpSpreadsheet\Spreadsheet;
?>
<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">

    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">Semua Data Guru </h3>
        <?php if ($_SESSION['level'] == 'admin') { ?>

          <a class='pull-right btn btn-primary btn-sm' href='index.php?view=guru&act=tambahguru'>Tambahkan Data Guru</a>
        <?php } ?>
         <a style='margin-right:5px' class='pull-right btn btn-success btn-sm' href='excel_data_guru.php'>
          <i class='fa fa-file-excel-o'></i> Export Data Guru
        </a> 
      </div>
      <div class="box-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Status Pegawai</th>
                <th>Jenis PTK</th>
                <th>Tugas Tambahan</th>
                <th>TMT</th>
                <th>Masa Kerja</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $tampil = mysqli_query($conn, "SELECT *, a.id as idGuru FROM rb_guru a 
                                          LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                            LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                              LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk
                                              LEFT JOIN rb_tugas_tambahan e ON a.tugas_tambahan=e.id
                                              ORDER BY a.nama_guru ASC");
              $no = 1;
              while ($r = mysqli_fetch_array($tampil)) {
                $tanggal = tgl_indo($r['tgl_posting']);
                if ($r['page_absen'] == 'T') {
                  $a = 'Y';
                  $icon = "fa-close";
                  $btn = "btn-danger ";
                  $alt = "Aktifkan";
                  $onoff = "<a class='btn $btn btn-xs' title='$alt' href='?view=guru&act=onoff&id=$r[nik]&a=$a'><span class='fa $icon'></span> Radius Tidak Aktif</a>";
                } else {
                  $a = 'T';
                  $icon = "fa-map-marker";
                  $btn = "btn-warning";
                  $alt = "Non Aktifkan";
                  $onoff = "<a class='btn $btn btn-xs' title='$alt' href='?view=guru&act=onoff&id=$r[nik]&a=$a'><span class='fa $icon'></span> Radius Aktif</a>";
                }
                $tanggalAwal = $r['tmt_pengangkatan'];

                $tanggalSekarang = date("Y-m-d");

                // Menghitung selisih tahun dan bulan
                $selisih = date_diff(date_create($tanggalAwal), date_create($tanggalSekarang));

                $tmtTahun = $selisih->y;
                $tmtBulan = $selisih->m;

                echo "<tr><td>$no</td>
                              <td>$r[nama_guru]</td>
                              <td>$r[status_kepegawaian]</td>
                              <td>$r[jenis_ptk]</td>
                              <td>$r[nmTugas]</td>                              
                              <td>" . tgl_indo($r[tmt_pengangkatan]) . "</td>                              
                              <td>$tmtTahun tahun $tmtBulan bulan</td>                              

                              ";
                if ($_SESSION['level'] == 'admin') {
                  echo "<td><center>
                                <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=guru&act=detailguru&id=$r[idGuru]'><span class='glyphicon glyphicon-search'></span></a>
                                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=guru&act=editguru&id=$r[idGuru]'><span class='glyphicon glyphicon-edit'></span></a>
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=guru&hapus=$r[idGuru]'  onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
                } else {
                  echo "<td><center>
                                <a class='btn btn-info btn-xs' title='Lihat Detail' href='?view=guru&act=detailguru&id=$r[idGuru]'><span class='glyphicon glyphicon-search'></span></a>
                              </center></td>";
                }
                echo "</tr>";
                $no++;
              }
              if (isset($_GET['hapus'])) {
                mysqli_query($conn, "DELETE FROM rb_guru where id='$_GET[hapus]'");
                echo "<script>document.location='index.php?view=guru';</script>";
              }

              ?>
            </tbody>
          </table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  <?php
} elseif ($_GET['act'] == 'onoff') {
  $a = $_GET['a'];
  $query = mysqli_query($conn, "UPDATE rb_guru SET page_absen='$a' where nip = '$_GET[id]'");

  if ($query) {
    echo "<script>document.location='index.php?view=guru';</script>";
  } else {
    echo "<script>document.location='index.php?view=guru';</script>";
  }
} elseif ($_GET['act'] == 'imports') {
  ?>
    <div class="col-xs-12">

      <div class="box box-warning">
        <!-- /.box-header -->
        <div class="box-body">
          <h3 class="box-title"> Import Data Guru</h3>
          <?php
          //jika tombol import ditekan
          if (isset($_POST['prosesimport'])) {

            $target = "temp/" . uniqid() . '.xlsx';
            move_uploaded_file($_FILES['fileSiswa']['tmp_name'], $target);

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $excel = $reader->load($target);
            $excelData = $excel->getActiveSheet()->toArray();
            unset($excelData[0]);

            foreach ($excelData as $item) {
              // setelah data dibaca, masukkan ke tabel pegawai sql
              $hasil = mysqli_query($conn, "INSERT INTO rb_guru(nip,password,nama_guru,id_jenis_kelamin,nuptk,id_jenis_ptk,id_agama,id_status_keaktifan) VALUES('$item[0]','$item[1]','$item[2]','$item[3]','$item[4]','$item[5]','1','1')");
            }

            if (!$hasil) {
              //          jika import gagal

              echo "<div class='alert alert-danger' role='alert'>
              Data Gagal Diimport....!<br>" . die(mysql_error()) . "
              </div>";
            } else {
              //          jika impor berhasil
              echo "<div class='alert alert-success' role='alert'>
              Data Berhasil Diimport....!
              </div>";
            }
            //hapus file xls yang udah dibaca
            unlink($target);
            // echo '1';

            // require_once 'config/excel_reader.php';

            // echo '2';

            // $data = new Spreadsheet_Excel_Reader($target);

            // echo '3';
            // echo $data->dump(true, true);
            //  menghitung jumlah baris file xls
            // $baris = $data->rowcount($sheet_index = 0);

            //    import data excel mulai baris ke-2 (karena tabel xls ada header pada baris 1)
            // for ($i = 2; $i <= $baris; $i++) {


            //   // membaca data (kolom ke-1 sd terakhir)
            //   $nis     = $data->val($i, 1);
            //   $nisn     = $data->val($i, 2);
            //   $username  = $data->val($i, 3);
            //   $ubah    = $data->val($i, 4);
            //   $nama      = $data->val($i, 5);
            //   $jk      = $data->val($i, 6);



            //   // setelah data dibaca, masukkan ke tabel pegawai sql
            //   $hasil = mysqli_query($conn, "INSERT INTO rb_guru(nip,password,nama_guru,id_jenis_kelamin,nuptk,id_jenis_ptk) 
            // 		VALUES('$nis','$nisn','$username','$ubah','$nama','$jk')");
            // }

            // if (!$hasil) {
            //   //          jika import gagal

            //   echo "<div class='alert alert-danger' role='alert'>
            // 	Data Gagal Diimport....!<br>" . die(mysql_error()) . "
            // 	</div>";
            // } else {
            //   //          jika impor berhasil
            //   echo "<div class='alert alert-success' role='alert'>
            // 	Data Berhasil Diimport....!
            // 	</div>";
            // }
            //    hapus file xls yang udah dibaca
            // unlink("temp/" . $_FILES['fileSiswa']['name']);
            // unlink("temp/formatdataguru.xls");
          }
          ?>

          <form method="POST" action="" class="form-horizontal" onSubmit="return validateForm()" enctype="multipart/form-data">
            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Download Format Data Guru</label>
              <div class="col-sm-8">
                <a href="./files/formatdataguru.xlsx" class="btn btn-info"><span class="fa fa-file-excel-o"></span> formatdataguru.xlsx</a>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-4 control-label">Pilih File Excel </label>
              <div class="col-sm-8">
                <input type="file" name="fileSiswa" class="form-control" id="fileSiswa" placeholder="" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-4 control-label"></label>
              <div class="col-sm-8">
                <input type="submit" name="prosesimport" value="Proses Import" class="btn btn-success">
                <a href="index.php?view=guru" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript">
      //    validasi form (hanya file .xls yang diijinkan)
      function validateForm() {
        function hasExtension(inputID, exts) {
          var fileName = document.getElementById(inputID).value;
          return (new RegExp('(' + exts.join('|').replace(/\./g, '\\.') + ')$')).test(fileName);
        }
        if (!hasExtension('fileSiswa', ['.xlsx'])) {
          alert("Hanya file XLS (Excel 2003) yang diizinkan.");
          return false;
        }
      }
    </script>
  <?php
} elseif ($_GET['act'] == 'tambahguru') {
  if (isset($_POST['tambah'])) {
    $rtrw = explode('/', $_POST['al']);
    $rt = $rtrw[0];
    $rw = $rtrw[1];
    $dir_gambar = 'foto_pegawai/';
    $filename = $_FILES['ax']['name'];
    $filenamee = date("YmdHis") . '-' . $_FILES['ax']['name'];
    $uploadfile = $dir_gambar . $filenamee;
    if ($filename != '') {
      if (move_uploaded_file($_FILES['ax']['tmp_name'], $uploadfile)) {
        $query = mysqli_query($conn, "INSERT INTO rb_guru (nik,nbm, password, nama_guru, id_jenis_kelamin, tempat_lahir, tanggal_lahir, idKelas, nuptk, id_status_pendidikan, id_jenis_ptk, id_agama, alamat_jalan, rt, rw, desa_kelurahan, kecamatan,kab, kode_pos, hp, email, tugas_tambahan, id_status_keaktifan, tmt_pengangkatan, id_status_pernikahan, norek, atasnama, foto, jam,hari_kerja,sts_peningkatan, id_status_kepegawaian,page_absen) 
                          VALUES('$_POST[nik]','$_POST[nbm]','$_POST[pass]','$_POST[nm]','$_POST[jk]','$_POST[tt]',
                           '$_POST[tl]','$_POST[kelas]','$_POST[nuptk]','$_POST[pendidikan]','$_POST[jptk]','$_POST[agama]','$_POST[alamat]','$rt','$rw',
                           '$_POST[kel]','$_POST[kec]','$_POST[kab]','$_POST[kdpos]','$_POST[nohp]','$_POST[email]','$_POST[tugastambahan]','$_POST[aktif]','$_POST[tmt]',
                           '$_POST[stsnikah]','$_POST[norek]','$_POST[nmBank]','$filenamee','$_POST[jam]','$_POST[hari_kerja]','$_POST[sts_peningkatan]','$_POST[st_pegawai]','Y')");
      }
    } else {
      $query = mysqli_query($conn, "INSERT INTO rb_guru (nik,nbm,  password, nama_guru, id_jenis_kelamin, tempat_lahir, tanggal_lahir, idKelas, nuptk, id_status_pendidikan, id_jenis_ptk, id_agama, alamat_jalan, rt, rw, desa_kelurahan, kecamatan, kode_pos, hp, email, tugas_tambahan, id_status_keaktifan, tmt_pengangkatan, id_status_pernikahan, norek, atasnama, foto, jam,hari_kerja,sts_peningkatan, id_status_kepegawaian,page_absen)
                           VALUES('$_POST[nik]','$_POST[nbm]','$_POST[pass]','$_POST[nm]','$_POST[jk]','$_POST[tt]',
                           '$_POST[tl]','$_POST[kelas]','$_POST[nuptk]','$_POST[pendidikan]','$_POST[jptk]','$_POST[agama]','$_POST[alamat]','$rt','$rw',
                           '$_POST[kel]','$_POST[kec]','$_POST[kab]','$_POST[kdpos]','$_POST[nohp]','$_POST[email]','$_POST[tugastambahan]','$_POST[aktif]','$_POST[tmt]',
                           '$_POST[stsnikah]','$_POST[norek]','$_POST[nmBank]','','$_POST[jam]','$_POST[hari_kerja]','$_POST[sts_peningkatan]','$_POST[st_pegawai]','Y')");
    }
    echo "<script>document.location='index.php?view=guru&act=detailguru&id=" . $_POST['nik'] . "';</script>";
  }

  echo "<div class='col-md-12'>
              <div class='box box-warning'>
                <div class='box-header with-border'>
                  <h3 class='box-title'>Tambah Data Guru</h3>
                </div>
              <div class='box-body'>
              <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                <div class='col-md-6'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                    <tr><th width='120px' scope='row'>NIK</th>      <td><input type='text' class='form-control' name='nik'></td></tr>
                     <tr><th width='120px' scope='row'>NBM</th>      <td><input type='text' class='form-control'  name='nbm' ></td></tr>

                    <tr><th scope='row'>NUPTK</th>                  <td><input type='text' class='form-control' name='nuptk'></td></tr>
                    <tr><th scope='row'>Nama Lengkap</th>           <td><input type='text' class='form-control' name='nm'></td></tr>
                    <tr><th scope='row'>Tempat Lahir</th>           <td><input type='text' class='form-control' name='tt'></td></tr>
                    <tr><th scope='row'>Tanggal Lahir</th>          <td><input type='date' class='form-control' name='tl'></td></tr>
                    <tr><th scope='row'>Jenis Kelamin</th>          <td><select class='form-control' name='jk'> 
                                                                          <option value='0' selected>- Pilih Jenis Kelamin -</option>";
  $jk = mysqli_query($conn, "SELECT * FROM rb_jenis_kelamin");
  while ($a = mysqli_fetch_array($jk)) {
    echo "<option value='$a[id_jenis_kelamin]'>$a[jenis_kelamin]</option>";
  }
  echo "</select></td></tr>
  <tr><th scope='row'>Agama</th> <td><select class='form-control' name='agama'> 
                                                                          <option value='0' selected>- Pilih Agama -</option>";
  $agama = mysqli_query($conn, "SELECT * FROM rb_agama");
  while ($a = mysqli_fetch_array($agama)) {
    echo "<option value='$a[id_agama]'>$a[nama_agama]</option>";
  }
  echo "</select></td></tr>
                    <tr><th scope='row'>No Hp</th>                  <td><input type='text' class='form-control' name='nohp'></td></tr>
                    <tr><th scope='row'>Email</th>           <td><input type='text' class='form-control' name='email'></td></tr>
                    <tr><th scope='row'>Password</th>               <td><input type='text' class='form-control' name='pass'></td></tr>
                    <tr><th scope='row'>Nomor Rekening</th>              <td><input type='text' class='form-control' name='norek'></td></tr>
                    <tr><th scope='row'>Nama Bank</th>              <td><input type='text' class='form-control' name='nmBank'></td></tr>
                    <tr><th scope='row'>Alamat</th>                 <td><input type='text' class='form-control' name='alamat'></td></tr>
                    <tr><th scope='row'>RT/RW</th>                  <td><input type='text' class='form-control' value='00/00' name='al'></td></tr>
                  </tbody>
                  </table>
                </div>
                <div class='col-md-6'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <tr><th scope='row'>Kelurahan</th>              <td><input type='text' class='form-control' name='kel'></td></tr>
                  <tr><th scope='row'>Kecamatan</th>              <td><input type='text' class='form-control' name='kec'></td></tr>
                  <tr><th scope='row'>Kab/Kota</th>              <td><input type='text' class='form-control' name='kab'></td></tr>
                  <tr><th scope='row'>Kode Pos</th>               <td><input type='text' class='form-control' name='kdpos'></td></tr>
                  <tr><th scope='row'>Jenis PTK</th>              <td><select class='form-control' name='jptk'> 
                  <option value='0' selected>- Pilih Jenis PTK -</option>";
  $ptk = mysqli_query($conn, "SELECT * FROM rb_jenis_ptk order by id_jenis_ptk");
  while ($a = mysqli_fetch_array($ptk)) {
    echo "<option value='$a[id_jenis_ptk]'>$a[jenis_ptk]</option>";
  }
  echo "</select></td></tr>
<tr><th scope='row'>Tugas Tambahan</th>              <td><select class='form-control' name='tugastambahan'> 
<option value='0' selected>- Pilih Tugas Tambahan -</option>";
  $ptk = mysqli_query($conn, "SELECT * FROM rb_tugas_tambahan order by id");
  while ($a = mysqli_fetch_array($ptk)) {
    echo "<option value='$a[id]'>$a[nmTugas]</option>";
  }
  echo "</select></td></tr>
<tr><th scope='row'>Kelas </th> <td><select class='form-control' name='kelas'> 
<option value='0' selected>- Pilih Kelas -</option>";
  $agama = mysqli_query($conn, "SELECT * FROM kelas_siswa");
  while ($a = mysqli_fetch_array($agama)) {
    if ($a['idKelas'] == $s['nmKelas']) {
      echo "<option value='$a[idKelas]' selected>$a[nmKelas]</option>";
    } else {
      echo "<option value='$a[idKelas]'>$a[nmKelas]</option>";
    }
  }
  echo "</select><b><p style='padding:2px; color:red'>*Isi Jika Wali Kelas</p></b></td></tr>
<tr><th scope='row'>Jam Mengajar/Kerja</th>               <td><input type='number' class='form-control' name='jam'></td></tr>
<tr><th scope='row'>Jml Hari Kerja</th>               <td><input type='number' class='form-control' name='hari_kerja'></td></tr>

<tr><th scope='row'>Status Pegawai</th>         <td><select class='form-control' name='st_pegawai'> 
                  <option value='0' selected>- Pilih Status Kepegawaian -</option>";
  $status_kepegawaian = mysqli_query($conn, "SELECT * FROM rb_status_kepegawaian order by id_status_kepegawaian");
  while ($a = mysqli_fetch_array($status_kepegawaian)) {
    echo "<option value='$a[id_status_kepegawaian]'>$a[status_kepegawaian]</option>";
  }
  echo "</select></td></tr>
   <tr><th scope='row'>Status Peningkatan</th>         <td><select class='form-control' name='sts_peningkatan'> 
                                                        <option value='0' selected>- Pilih Status Peningkatan -</option>";
        
      echo "<option value='tidak' selected>Tidak</option>";
          echo "<option value='HPM' >HPM</option>";
          echo "<option value='NUPTK' >NUPTK</option>";
      echo "<option value='Sertifikasi' >Sertifikasi</option>";

  echo "</select></td></tr>
<tr>
    <th scope='row'>Status Keaktifan</th>
    <td>
        <select class='form-control' name='aktif'> 
            <option value='0' selected>- Pilih Status Keaktifan -</option>
            <option value='Aktif'>Aktif</option>
            <option value='Tidak'>Tidak</option>
            <option value='Resign'>Resign</option>
            <option value='Mutasi/Pindah Tugas'>Mutasi/Pindah Tugas</option>
            <option value='Pecat'>Pecat</option>
            <option value='Pensiun'>Pensiun</option>
       
";

  echo "</select></td></tr>
  <tr><th scope='row'>Pendidikan</th>        <td><select class='form-control' name='pendidikan'> 
                  <option value='0' selected>- Pilih Pendidikan -</option>
                  <option value='SMA' >SMA</option>
                  <option value='D3' >D3</option>
                  <option value='S1' >S1</option>
                  <option value='S2' >S2</option>
                  <option value='S3' >S3</option></tr>
                  <tr><th scope='row'>TMT</th>          <td><input type='date' class='form-control' name='tmt'></td></tr>
                  ";


  echo "</select></td></tr>
  
<tr><th scope='row'>Status Nikah</th>           <td><select class='form-control' name='stsnikah'> 
                  <option value='0' selected>- Pilih Status Pernikahan -</option>";
  $status_pernikahan = mysqli_query($conn, "SELECT * FROM rb_status_pernikahan");
  while ($a = mysqli_fetch_array($status_pernikahan)) {
    echo "<option value='$a[id_status_pernikahan]'>$a[status_pernikahan]</option>";
  }
  echo "</select></td></tr>
<tr><th scope='row'>Foto</th>             <td><div style='position:relative;''>
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
                          <button type='submit' name='tambah' class='btn btn-info'>Tambahkan</button>
                          <a href='index.php?view=guru'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                        </div> 
              </div>
            </form>
            </div>";
} elseif ($_GET['act'] == 'editguru') {
  if (isset($_POST['update1'])) {
    $rtrw = explode('/', $_POST['al']);
    $rt = $rtrw[0];
    $rw = $rtrw[1];
    $dir_gambar = 'foto_pegawai/';
    $filename = $_FILES['ax']['name'];
    $filenamee = date("YmdHis") . '-' . $_FILES['ax']['name'];
    $uploadfile = $dir_gambar . $filenamee;
    if ($filename != '') {
      if (move_uploaded_file($_FILES['ax']['tmp_name'], $uploadfile)) {
        mysqli_query($conn, "UPDATE rb_guru SET                                    
                                  nik = '$_POST[nik]', 
                                  nbm = '$_POST[nbm]', 
                                   password     = '$_POST[pass]',
                                   nama_guru         = '$_POST[nm]',
                                   tempat_lahir       = '$_POST[tt]',
                                   tanggal_lahir = '$_POST[tl]',
                                   id_jenis_kelamin       = '$_POST[jk]',
                                   id_agama           = '$_POST[agama]',
                                   hp         = '$_POST[nohp]',
                                   email        = '$_POST[email]',
                                   alamat_jalan      = '$_POST[alamat]',
                                   rt = '$rt',
                                   rw          = '$rw',
                                   desa_kelurahan = '$_POST[desa]',
                                   kecamatan = '$_POST[kec]',
                                    kab = '$_POST[kab]',
                                   kode_pos = '$_POST[kdpos]',
                                   nuptk = '$_POST[nuptk]',
                                   id_jenis_ptk = '$_POST[jnsptk]',
                                   id_status_pendidikan= '$_POST[pendidikan]',
                                   tugas_tambahan = '$_POST[tugastambahan]', 
                                   id_status_kepegawaian = '$_POST[stspegawai]',
                                   id_status_keaktifan = '$_POST[aktif]',
                                   id_status_pernikahan = '$_POST[stsnikah]', 
                                   foto = '$filenamee', 
                                   idKelas = '$_POST[kelas]',
                                   tmt_pengangkatan = '$_POST[tmt]', 
                                   norek = '$_POST[norek]', 
                                   atasnama = '$_POST[nmBank]', 
                                   jam = '$_POST[jam]',
                                    hari_kerja = '$_POST[hari_kerja]',
                                    sts_peningkatan = '$_POST[sts_peningkatan]'
                                   where id='$_POST[id]'");
      }
    } else {
      mysqli_query($conn, "UPDATE rb_guru SET 
                                    nik = '$_POST[nik]', 
                                     nbm = '$_POST[nbm]', 
                                   password     = '$_POST[pass]',
                                   nama_guru         = '$_POST[nm]',
                                   tempat_lahir       = '$_POST[tt]',
                                   tanggal_lahir = '$_POST[tl]',
                                   id_jenis_kelamin       = '$_POST[jk]',
                                   id_agama           = '$_POST[agama]',
                                   hp         = '$_POST[nohp]',
                                   email        = '$_POST[email]',
                                   alamat_jalan      = '$_POST[alamat]',
                                   rt = '$rt',
                                   rw          = '$rw',
                                   desa_kelurahan = '$_POST[desa]',
                                   kecamatan = '$_POST[kec]',
                                    kab = '$_POST[kab]',
                                   kode_pos = '$_POST[kdpos]',
                                   nuptk = '$_POST[nuptk]',
                                   id_jenis_ptk = '$_POST[jnsptk]',
                                     id_status_pendidikan= '$_POST[pendidikan]',

                                   tugas_tambahan = '$_POST[tugastambahan]', 
                                   id_status_kepegawaian = '$_POST[stspegawai]',
                                   id_status_keaktifan = '$_POST[aktif]',
                                   id_status_pernikahan = '$_POST[stsnikah]', 
                                   idKelas = '$_POST[kelas]',
                                   tmt_pengangkatan = '$_POST[tmt]', 
                                   norek = '$_POST[norek]', 
                                   atasnama = '$_POST[nmBank]', 
                                   jam = '$_POST[jam]',
                                   hari_kerja = '$_POST[hari_kerja]',
                                   sts_peningkatan = '$_POST[sts_peningkatan]'
                                    where id='$_POST[id]'");
    }
    echo "<script>document.location='index.php?view=guru&act=detailguru&id=" . $_POST['id'] . "';</script>";
  }

  $detail = mysqli_query($conn, "SELECT * FROM rb_guru where id='$_GET[id]'");
  $s = mysqli_fetch_array($detail);
  echo "<div class='col-md-12'>
                      <div class='box box-warning'>
                        <div class='box-header with-border'>
                          <h3 class='box-title'>Edit Data Guru</h3>
                        </div>
                      <div class='box-body'>
                      <form method='POST' class='form-horizontal' action='' enctype='multipart/form-data'>
                        <div class='col-md-7'>
                          <table class='table table-condensed table-bordered'>
                          <tbody>
                            <tr><th style='background-color:#E7EAEC' width='160px' rowspan='25'>";
  if (trim($s[foto]) == '') {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
  } else {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_pegawai/$s[foto]'>";
  }
  echo "</th>
                            </tr>
                            <input type='hidden' name='id' value='$s[id]'>
                            <tr><th width='120px' scope='row'>NIK</th>      <td><input type='text' class='form-control' value='$s[nik]' name='nik' ></td></tr>
                             <tr><th width='120px' scope='row'>NBM</th>      <td><input type='text' class='form-control' value='$s[nbm]' name='nbm' ></td></tr>
                            <tr><th scope='row'>NUPTK</th>                  <td><input type='text' class='form-control' value='$s[nuptk]' name='nuptk'></td></tr>
                            <tr><th scope='row'>Nama Lengkap</th>           <td><input type='text' class='form-control' value='$s[nama_guru]' name='nm'></td></tr>
                            <tr><th scope='row'>Tempat Lahir</th>           <td><input type='text' class='form-control' value='$s[tempat_lahir]' name='tt'></td></tr>
                            <tr><th scope='row'>Tanggal Lahir</th>          <td><input type='date' class='form-control' value='$s[tanggal_lahir]' name='tl'></td></tr>
                            <tr><th scope='row'>Jenis Kelamin</th>          <td><select class='form-control' name='jk'> 
                                                                                  <option value='0' selected>- Pilih Jenis Kelamin -</option>";
  $jk = mysqli_query($conn, "SELECT * FROM rb_jenis_kelamin");
  while ($a = mysqli_fetch_array($jk)) {
    if ($a[id_jenis_kelamin] == $s[id_jenis_kelamin]) {
      echo "<option value='$a[id_jenis_kelamin]' selected>$a[jenis_kelamin]</option>";
    } else {
      echo "<option value='$a[id_jenis_kelamin]'>$a[jenis_kelamin]</option>";
    }
  }
  echo "</select></td></tr>
                            <tr><th scope='row'>Agama</th>                  <td><select class='form-control' name='agama'> 
                                                                                  <option value='0' selected>- Pilih Agama -</option>";
  $agama = mysqli_query($conn, "SELECT * FROM rb_agama");
  while ($a = mysqli_fetch_array($agama)) {
    if ($a[id_agama] == $s[id_agama]) {
      echo "<option value='$a[id_agama]' selected>$a[nama_agama]</option>";
    } else {
      echo "<option value='$a[id_agama]'>$a[nama_agama]</option>";
    }
  }
  echo "</select></td></tr>
                            <tr><th scope='row'>No Hp</th>                  <td><input type='text' class='form-control' value='$s[hp]' name='nohp'></td></tr>
                            <tr><th scope='row'>Email</th>           <td><input type='text' class='form-control' value='$s[email]' name='email'></td></tr>
                            <tr><th scope='row'>Password</th>               <td><input type='text' class='form-control' value='$s[password]' name='pass'></td></tr>
                            <tr><th scope='row'>Alamat</th>                 <td><input type='text' class='form-control' value='$s[alamat_jalan]' name='alamat'></td></tr>
                            <tr><th scope='row'>RT/RW</th>                  <td><input type='text' class='form-control' value='$s[rt]/$s[rw]' name='al'></td></tr>
                            <tr><th scope='row'>Nomor Rekening</th>              <td><input type='text' class='form-control' value='$s[norek]' name='norek'></td></tr>
                            <tr><th scope='row'>Nama Bank</th>              <td><input type='text' class='form-control' value='$s[atasnama]' name='nmBank'></td></tr>
                          </tbody>
                          </table>
                        </div>
        
                        <div class='col-md-5'>
                          <table class='table table-condensed table-bordered'>
                          <tbody>
  <tr><th scope='row'>Kelurahan</th>              <td><input type='text' class='form-control' value='$s[desa_kelurahan]' name='desa'></td></tr>
  <tr><th scope='row'>Kecamatan</th>              <td><input type='text' class='form-control' value='$s[kecamatan]' name='kec'></td></tr>
                    <tr><th scope='row'>Kab/Kota</th>              <td><input type='text' class='form-control' value='$s[kab]' name='kab'></td></tr>

  <tr><th scope='row'>Kode Pos</th>               <td><input type='text' class='form-control' value='$s[kode_pos]' name='kdpos'></td></tr>
  <tr><th scope='row'>Jenis PTK</th>              <td><select class='form-control' name='jnsptk'> 
                                                        <option value='0' selected>- Pilih Jenis PTK -</option>";
  $ptk = mysqli_query($conn, "SELECT * FROM rb_jenis_ptk order by id_jenis_ptk");
  while ($a = mysqli_fetch_array($ptk)) {
    if ($a[id_jenis_ptk] == $s[id_jenis_ptk]) {
      echo "<option value='$a[id_jenis_ptk]' selected>$a[jenis_ptk]</option>";
    } else {
      echo "<option value='$a[id_jenis_ptk]'>$a[jenis_ptk]</option>";
    }
  }
  echo "</select></td></tr>
  <tr><th scope='row'>TMT Pengangkat</th>         <td><input type='date' class='form-control' value='$s[tmt_pengangkatan]' name='tmt'></td></tr>

<tr><th scope='row'>Tugas Tambahan</th>              <td><select class='form-control' name='tugastambahan'> 
<option value='0' selected>- Pilih Tugas Tambahan -</option>";
  $ptk = mysqli_query($conn, "SELECT * FROM rb_tugas_tambahan order by id");
  while ($a = mysqli_fetch_array($ptk)) {
    if ($a[idate()] == $s[tugas_tambahan]) {
      echo "<option value='$a[id]' selected>$a[nmTugas]</option>";
    } else {
      echo "<option value='$a[id]'>$a[nmTugas]</option>";
    }
  }
  echo "</select></td></tr>
<tr><th scope='row'>Kelas </th>                  <td><select class='form-control' name='kelas'> 
<option value='0' selected>- Pilih Kelas -</option>";
  $kelas = mysqli_query($conn, "SELECT * FROM kelas_siswa");
  while ($a = mysqli_fetch_array($kelas)) {
    if ($a[idKelas] == $s[idKelas]) {
      echo "<option value='$a[idKelas]' selected>$a[nmKelas]</option>";
    } else {
      echo "<option value='$a[idKelas]'>$a[nmKelas]</option>";
    }
  }
  echo "</select><b><p style='padding:2px; color:red'>*Isi Jika Wali Kelas</p></b></td></tr>
  <tr><th scope='row'>Jam</th>               <td><input type='text' class='form-control' value='$s[jam]' name='jam'></td></tr>
  <tr><th scope='row'>Jml Hari Kerja</th>               <td><input type='text' class='form-control' value='$s[hari_kerja]' name='hari_kerja'></td></tr>

  <tr><th scope='row'>Status Pegawai</th>         <td><select class='form-control' name='stspegawai'> 
                                                        <option value='0' selected>- Pilih Status Kepegawaian -</option>";
  $status_kepegawaian = mysqli_query($conn, "SELECT * FROM rb_status_kepegawaian order by id_status_kepegawaian");
  while ($a = mysqli_fetch_array($status_kepegawaian)) {
    if ($a[id_status_kepegawaian] == $s[id_status_kepegawaian]) {
      echo "<option value='$a[id_status_kepegawaian]' selected>$a[status_kepegawaian]</option>";
    } else {
      echo "<option value='$a[id_status_kepegawaian]'>$a[status_kepegawaian]</option>";
    }
  }
  echo "</select></td></tr>
   <tr><th scope='row'>Status Peningkatan</th>         <td><select class='form-control' name='sts_peningkatan'> 
                                                        <option value='$a[sts_peningkatan]' selected>$a[sts_peningkatan]</option>";
  
      echo "<option value='tidak' selected>Tidak</option>";
      echo "<option value='HPM' >HPM</option>";
          echo "<option value='NUPTK' >NUPTK</option>";
      echo "<option value='Sertifikasi' >Sertifikasi</option>";

  echo "</select></td></tr>
</tr>
<tr><th scope='row'>Status Keaktifan</th>       <td><select class='form-control' name='aktif'> 
<option value='" . $s['id_status_keaktifan'] . "' selected>" . $s['id_status_keaktifan'] . "</option>
            <option value='Aktif'>Aktif</option>
            <option value='Tidak'>Tidak</option>
            <option value='Resign'>Resign</option>
            <option value='Mutasi/Pindah Tugas'>Mutasi/Pindah Tugas</option>
            <option value='Pecat'>Pecat</option>
            <option value='Pensiun'>Pensiun</option>";

  echo "</select></td></tr>
  <tr><th scope='row'>Pendidikan</th>        <td><select class='form-control' name='pendidikan'> 
                  <option value='" . $s['id_status_pendidikan'] . "' selected>" . $s['id_status_pendidikan'] . "</option>
                  <option value='SMA' >SMA</option>
                  <option value='D3' >D3</option>
                  <option value='S1' >S1</option>
                  <option value='S2' >S2</option>
                  <option value='S3' >S3</option></tr>
  <tr><th scope='row'>Status Nikah</th>           <td><select class='form-control' name='stsnikah'> 
                                                        <option value='0' selected>- Pilih Status Pernikahan -</option>";
  $status_pernikahan = mysqli_query($conn, "SELECT * FROM rb_status_pernikahan");
  while ($a = mysqli_fetch_array($status_pernikahan)) {
    if ($a['id_status_pernikahan'] == $s['id_status_pernikahan']) {
      echo "<option value='$a[id_status_pernikahan]' selected>$a[status_pernikahan]</option>";
    } else {
      echo "<option value='$a[id_status_pernikahan]'>$a[status_pernikahan]</option>";
    }
  }
  echo "</select></td></tr>
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
                                  <a href='index.php?view=siswa'><button type='button' class='btn btn-default pull-right'>Cancel</button></a>
                                </div> 
                      </div>
                    </form>
                    </div>";
} elseif ($_GET['act'] == 'detailguru') {
  $detail = mysqli_query($conn, "SELECT a.*, b.jenis_kelamin, c.status_kepegawaian, d.jenis_ptk, e.nama_agama, h.status_pernikahan ,f.nmTugas,g.nmKelas
                                FROM rb_guru a LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                  LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                    LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk 
                                      LEFT JOIN rb_agama e ON a.id_agama=e.id_agama 
                                      LEFT JOIN rb_tugas_tambahan f ON a.tugas_tambahan=f.id 
                                      LEFT JOIN kelas_siswa g ON a.idKelas=g.idKelas 

                                            LEFT JOIN rb_status_pernikahan h ON a.id_status_pernikahan=h.id_status_pernikahan
                                              where a.id='$_GET[id]'");
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
                    <input type='hidden' name='id' value='$s[nik]'>
                    <tr><th style='background-color:#E7EAEC' width='160px' rowspan='25'>";
  if (trim($s[foto]) == '') {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_siswa/no-image.jpg'>";
  } else {
    echo "<img class='img-thumbnail' style='width:155px' src='foto_pegawai/$s[foto]'>";
  }
  if ($_SESSION[level] == 'admin') {
    echo "<a href='index.php?view=guru&act=editguru&id=$_GET[id]' class='btn btn-success btn-block'>Edit Profile</a>";
  }
  echo "</th>
                    </tr>
                    <tr><th width='120px' scope='row'>NIK</th>      <td>$s[nik]</td></tr>
                    <tr><th width='120px' scope='row'>NBM</th>      <td>$s[nbm]</td></tr>
                    <tr><th scope='row'>Nama Lengkap</th>           <td>$s[nama_guru]</td></tr>
                    <tr><th scope='row'>Tempat Lahir</th>           <td>$s[tempat_lahir]</td></tr>
                    <tr><th scope='row'>Tanggal Lahir</th>          <td>$s[tanggal_lahir]</td></tr>
					<tr><th scope='row'> Kelas</th>               <td>$s[nmKelas]</td></tr>
                    <tr><th scope='row'>Jenis Kelamin</th>          <td>$s[jenis_kelamin]</td></tr>
                    <tr><th scope='row'>Agama</th>                  <td>$s[nama_agama]</td></tr>
                    <tr><th scope='row'>No Hp</th>                  <td>$s[hp]</td></tr>
                    <tr><th scope='row'>No rekening</th>            <td>$s[norek]</td></tr>
                    <tr><th scope='row'>Nama Bank</th>                <td>$s[atasnama]</td></tr>
                    <tr><th scope='row'>Alamat Email</th>           <td>$s[email]</td></tr>
                    <tr><th scope='row'>Password</th>               <td>$s[password]</td></tr>

                  </tbody>
                  </table>
                </div>

                <div class='col-md-5'>
                  <table class='table table-condensed table-bordered'>
                  <tbody>
                  <tr><th scope='row'>Alamat</th>                 <td>$s[alamat_jalan]</td></tr>
                  <tr><th scope='row'>RT/RW</th>                  <td>$s[rt]/$s[rw]</td></tr>
                  <tr><th scope='row'>Kelurahan</th>              <td>$s[desa_kelurahan]</td></tr>
                  <tr><th scope='row'>Kecamatan</th>              <td>$s[kecamatan]</td></tr>
                   <tr><th scope='row'>Kab/Kota</th>              <td>$s[kab]</td></tr>
                  <tr><th scope='row'>Kode Pos</th>               <td>$s[kode_pos]</td></tr>
                  <tr><th scope='row'>NUPTK</th>                  <td>$s[nuptk]</td></tr>
                  <tr><th scope='row'>Jenis PTK</th>              <td>$s[jenis_ptk]</td></tr>
                    <tr><th scope='row'>Pendidikan</th>              <td>$s[id_status_pendidikan]</td></tr>

                  <tr><th scope='row'>Tugas Tambahan</th>         <td>$s[nmTugas]</td></tr>
                <tr><th scope='row'>Jam Mengajar</th>         <td>$s[jam]</td></tr>
                  <tr><th scope='row'>Jml Hari Kerja</th>         <td>$s[hari_kerja]</td></tr>
                  <tr><th scope='row'>Status Peningkatan</th>         <td>$s[sts_peningkatan]</td></tr>

                  <tr><th scope='row'>Status Pegawai</th>         <td>$s[status_kepegawaian]</td></tr>
                  <tr><th scope='row'>Status Keaktifan</th>       <td>$s[id_status_keaktifan]</td></tr>
                  <tr><th scope='row'>Status Nikah</th>           <td>$s[status_pernikahan]</td></tr>

                  </tbody>
                  </table>
                </div> 
              </div>
            </form>
            </div>";
}
  ?>