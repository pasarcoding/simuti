<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-warning">
      <div class="box-header with-border">

        <!-- <a class="pull-left btn btn-success btn-sm" target="_blank" href="./excel_laporan_surat_masuk.php"><span class="fa fa-file-excel-o"></span> Export ke Excel</a>
		<span> --><?php if ($_SESSION['level'] == 'admin') { ?>
        <a class='pull-right btn btn-primary btn-sm' href='?view=surat_masuk&act=tambah'>Tambahkan Data</a>
        <?php } ?>
        
        <form method="GET" action="">
        <input class="hidden" name="view" value="surat_masuk"></input>
        <input class="hidden" name=""></input>

       <div class="col-md-3">
            <select name="tahun" class="form-control">
                <option value="">-- Pilih Tahun --</option>
                <?php
                // Get the current year
                $currentYear = date("Y");
                
                // Static list of years from 2022 to current year
                $years = range(2023, $currentYear); 
        
                foreach ($years as $year) {
                    // Check if the current year is selected
                    $selected = ($_GET['tahun'] == $year) ? ' selected' : '';
                    echo '<option value="' . $year . '"' . $selected . '>' . $year . '</option>';
                }
                ?>
            </select>
        </div>


        
        <div class="col-md-3">
            <select name="bulan" class="form-control">
                <option value="">-- Pilih Bulan --</option>
                <?php
                $bulan = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($bulan as $key => $value) {
                    $selected = ($_GET['bulan'] == $key) ? ' selected' : '';
                    echo '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
                }
                ?>
            </select>
        </div>
       
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary" >Tampilkan</button>
            <a href="cetak_surat_masuk.php?tahun=<?=$_GET['tahun'] ?>&bulan=<?=$_GET['bulan'] ?>" target="_blank" class="btn btn-success">Cetak PDF</a>

        </div>
    </form>
      </div><!-- /.box-header -->
      <div class="box-body">
          <div class="row mb-">
           </div>
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
            <th width="10">No</th>
            <th>Nomor Surat</th>
            <th>Tanggal Surat</th>
            <th>Asal</th>
            <th>Sifat</th>
            <th>Perihal</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Get year and month from the URL or form
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';

        // Base query
        $query = "SELECT * FROM surat_masuk WHERE 1=1 ";

        // Add filter for year if provided
        if ($tahun != '') {
            $query .= "AND YEAR(tgl) = '$tahun' ";
        }

        // Add filter for month if provided
        if ($bulan != '') {
            $query .= "AND MONTH(tgl) = '$bulan' ";
        }

        // Group by nomor_surat and order by date
        $query .= "GROUP BY nomor_surat ORDER BY tgl DESC";

        // Execute the query
        $tampil = mysqli_query($conn, $query);

        $no = 1;
        while ($r = mysqli_fetch_array($tampil)) {
            if ($r['status'] == 'Belum Diterima') {
                $a = 'Diterima';
                $icon = "fa-close";
                $btn = "btn-danger";
                $alt = "Aktifkan";
                if ($_SESSION['level'] == 'admin') {
                    $onoff = "<a class='btn $btn btn-xs' title='$alt' href='?view=surat_keluar&act=onoff&id=$r[id]&a=$a'><span class='fa $icon'></span> Belum Diterima</a>";
                } else {
                    $onoff = "<a class='btn $btn btn-xs' title='$alt' href='#'><span class='fa $icon'></span> Belum Diterima</a>";
                }
            } else {
                $a = 'Belum Diterima';
                $icon = "fa-check";
                $btn = "btn-success";
                if ($_SESSION['level'] == 'admin') {
                    $onoff = "<a class='btn $btn btn-xs' title='$alt' href='?view=surat_keluar&act=onoff&id=$r[id]&a=$a'><span class='fa $icon'></span> Diterima</a>";
                } else {
                    $onoff = "<a class='btn $btn btn-xs' title='$alt' href='#'><span class='fa $icon'></span> Diterima</a>";
                }
            }
            echo "<tr><td>$no</td>
                <td>$r[nomor_surat]</td>
                <td>" . tgl_indo($r['tgl']) . "</td>
                <td>$r[asal]</td>
                <td>$r[sifat]</td>
                <td>$r[perihal]</td>
                <td>$onoff</td>
                <td><center>
                    <a href='#lihat" . $r['id'] . "' data-toggle='modal' class='btn btn-xs btn-info'>
                    <i class='fa fa-eye' data-toggle='tooltip' title='' data-original-title='Lihat Surat Masuk'></i>  
                    </a>
                    "; 
                    if ($_SESSION['level'] == 'admin') {
                        echo "<a class='btn btn-success btn-xs' title='Edit Data' href='?view=surat_masuk&act=edit&id=$r[id]'>
                        <span class='glyphicon glyphicon-edit'></span></a>
                        <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=surat_masuk&hapus&id=$r[id]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\">
                        <span class='glyphicon glyphicon-remove'></span></a>";
                    }
                    echo "</center></td>
                </tr>";
            echo '<div class="modal modal-default fade" id="lihat' . $r['id'] . '">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                                <h3 class="modal-title">Surat Masuk ' . $r['asal'] . '</h3>
                            </div>
                            <div class="modal-body">
                            <embed src="surat/' . $r['file'] . '" type="application/pdf" width="560" height="400">
                            </div>
                            <div class="modal-footer">
                                <form action="?view=' . $_GET['view'] . '" method="post" accept-charset="utf-8">
                                    <input type="hidden" name="id" value="' . $r['id'] . '"> 
                                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><span class="fa fa-close"></span> Tutup</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>';
            $no++;
        }

        if (isset($_GET['hapus'])) {
            mysqli_query($conn, "DELETE FROM surat_masuk where id='$_GET[id]'");
            echo "<script>document.location='?view=surat_masuk';</script>";
        }
        ?>
    </tbody>
</table>

        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  <?php
} elseif ($_GET['act'] == 'onoff') {
  $query = mysqli_query($conn, "UPDATE surat_masuk SET status='$_GET[a]' where id = '$_GET[id]'");
  $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM surat_masuk 
              where id='$_GET[id]'"));
  if ($query) {
    echo "<script>document.location='master?view=surat_masuk';</script>";
  } else {
    echo "<script>document.location='master?view=surat_masuk';</script>";
  }
} elseif ($_GET['act'] == 'edit') {
  if (isset($_POST['update'])) {
    //pengecekan tipe harus pdf
    $tipe_file = $_FILES['nama_file']['type'];
    $tmp_file = $_FILES['nama_file']['tmp_name'];
    if ($tipe_file == "application/pdf") {
      $nama_file = trim($_FILES['nama_file']['name']);
      $path = "surat/" . $nama_file; //mendapatkan mime type
      if (move_uploaded_file($tmp_file, $path)) {
        $query = mysqli_query($conn, "UPDATE surat_masuk SET nomor_surat='$_POST[nomor_surat]',tgl='$_POST[tgl]', 
										 tgl_terima='$_POST[tgl_masuk]' ,asal='$_POST[asal]',sifat='$_POST[sifat]'
                     ,perihal='$_POST[perihal]',file='$nama_file'
                      where id = '$_POST[id]'");
      }
    } else {
      $query = mysqli_query($conn, "UPDATE surat_masuk SET tgl='$_POST[tgl]', 
                 tgl_terima='$_POST[tgl_masuk]' ,asal='$_POST[asal]',sifat='$_POST[sifat]'
                 ,perihal='$_POST[perihal]'
                  where id = '$_POST[id]'");
    }

    $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM surat_masuk 
              where id='$_GET[id]'"));
    $panjang_kode = 15; // Tentukan panjang kode yang ingin dibuat
    $kode_acak = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($panjang_kode / strlen($x)))), 1, $panjang_kode);
    mysqli_query($conn, "UPDATE  doc SET jenis='Surat Masuk',nomor='$_POST[nomor_surat]',judul='$_POST[perihal]',tujuan='$_POST[asal]',status='$_POST[status]' where nomor='$_POST[nomor_surat]'");
    if ($query) {
      echo "<script>document.location='?view=surat_masuk&sukses';</script>";
    } else {
      echo "<script>document.location='?view=surat_masuk&gagal';</script>";
    }
  }


  $edit = mysqli_query($conn, "SELECT * FROM surat_masuk where id='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Surat Masuk</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $record['id']; ?>">
            <input type="hidden" name="status" class="form-control" value="<?php echo $record['status']; ?>">

            <div class="col-sm-6">
              <label for="" class="control-label">Nomor Surat</label>
              <input type="text" name="nomor_surat" class="form-control" value="<?php echo $record['nomor_surat']; ?>" required>
            </div>
            <div class="col-sm-3">
              <label for="" class="control-label">Tanggal Surat</label>
              <input type="date" name="tgl" class="form-control" value="<?php echo $record['tgl']; ?>" required>
            </div>
            <div class="col-sm-3">
              <label for="" class=" control-label">Tanggal Terima</label>
              <input type="date" name="tgl_masuk" class="form-control" value="<?php echo $record['tgl_terima']; ?>">
            </div>
            <div class="col-md-12">
              <label for="" class="control-label">Perihal</label>
              <div class="md-form amber-textarea active-amber-textarea">
                <textarea id="form19" style="resize:none;width:950px;height:190px;" class="md-textarea form-control ckeditor" name="perihal" rows="3"> <?php echo $record['perihal']; ?></textarea>
              </div>
            </div>
            <div class="col-sm-6">
              <label for="" class=" control-label">Asal</label>
              <input type="text" name="asal" class="form-control" value=" <?php echo $record['asal']; ?>">
            </div>
            <div class="col-sm-6">
              <label for="" class=" control-label">Sifat</label>
              <select name="sifat" class="form-control">
                <option value="<?php echo $record['sifat']; ?>"><?php echo $record['sifat']; ?></option>
                <option value="Biasa">Biasa</option>
                <option value="Penting">Penting</option>
                <option value="Sangat Penting">Sangat Penting</option>

              </select>
            </div>
            <div class="col-md-4">
              <div class="form-group">
                <label for="" class=" control-label">File Surat Masuk</label>
                <embed src="surat/<?php echo $record['file']; ?>" type="application/pdf" width="960" height="400">
                <input type="file" name="nama_file" accept=".pdf">
              </div>
            </div>
            <div class=" form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-12">
                <input type="submit" name="update" value="Update" class="btn btn-success pull-right">
                <a href="?view=surat_masuk" class="btn btn-default pull-left">Cancel</a>
              </div>
            </div>
        </div>
      </div>
      </form>
    </div>

  <?php
} elseif ($_GET['act'] == 'tambah') {
  if (isset($_POST['tambah'])) {
    //pengecekan tipe harus pdf
    $tipe_file = $_FILES['nama_file']['type'];
    $tmp_file = $_FILES['nama_file']['tmp_name'];

    if ($tipe_file == "application/pdf") {

      $nama_file = trim($_FILES['nama_file']['name']);
      $path = "surat/" . $nama_file; //mendapatkan mime type
      if (move_uploaded_file($tmp_file, $path)) {
        $query = mysqli_query($conn, "INSERT INTO surat_masuk(nomor_surat,tgl,tgl_terima,asal,sifat,perihal,file) 
      VALUES('$_POST[nomor_surat]','$_POST[tgl]','$_POST[tgl_masuk]','$_POST[asal]','$_POST[sifat]','$_POST[perihal]','$nama_file')");
      }
    } else {
      $query = mysqli_query($conn, "INSERT INTO surat_masuk(nomor_surat,tgl,tgl_terima,asal,sifat,perihal,file) 
    VALUES('$_POST[nomor_surat]','$_POST[tgl]','$_POST[tgl_masuk]','$_POST[asal]','$_POST[sifat]','$_POST[perihal]','')");
    }

    $panjang_kode = 15; // Tentukan panjang kode yang ingin dibuat
    $kode_acak = substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($panjang_kode / strlen($x)))), 1, $panjang_kode);
    

    if ($query) {
      echo "<script>document.location='?view=surat_masuk&sukses';</script>";
    } else {
      echo "<script>document.location='?view=surat_masuk&gagal';</script>";
    }
  }
  ?>
    <div class="col-md-12">
      <div class="box box-warning">
        <div class="box-header with-border">
          <h3 class="box-title"> Tambah Surat Masuk</h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">

            <div class="col-md-6">
              <label for="" class="control-label">Nomor Surat</label>

              <input type="text" class="form-control" name="nomor_surat" />
            </div>
            <div class="col-sm-6">
              <label for="" class="control-label">Tanggal Surat</label>
              <input type="date" name="tgl" class="form-control">
            </div>
            <div class="col-sm-6">
              <label for="" class=" control-label">Tanggal Masuk</label>
              <input type="date" name="tgl_masuk" class="form-control">
            </div>
            <div class="col-sm-6">
              <label for="" class=" control-label">Asal</label>
              <input type="text" name="asal" class="form-control" placeholder="Asal Surat">
            </div>
            <div class="col-sm-6">
              <label for="" class=" control-label">Sifat</label>
              <select name="sifat" class="form-control">
                <option value="Biasa">Biasa</option>
                <option value="Penting">Penting</option>
                <option value="Sangat Penting">Sangat Penting</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="" class="control-label">Perihal</label>
              <div class="md-form amber-textarea active-amber-textarea">
                <textarea id="form19" style="resize:none;width:465px;height:100px;" class="md-textarea form-control" name="perihal" rows="3"></textarea>
              </div>
            </div>
            <div class="col-md-12">
              <label class="control-label">File Surat Masuk</label>
              <input type="file" name="nama_file">
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-12">
                <input type="submit" name="tambah" value="Simpan" class="btn btn-success pull-right">
                <a href="?view=surat_masuk" class="btn btn-default pull-left">Cancel</a>
              </div>
            </div>
        </div>
      </div>
      </form>
    </div>


  <?php
}
  ?>