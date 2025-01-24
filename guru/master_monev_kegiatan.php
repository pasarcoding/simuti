<?php 

        if ($_SESSION['tugas'] == '2') {  
            $where = "kurikulum";
        } else if  ($_SESSION['tugas'] == '3'){
            $where = "kesiswaan";
        } else if  ($_SESSION['tugas'] == '4'){
            $where = "humas";
        } else if  ($_SESSION['tugas'] == '5'){
            $where = "sarpras";
        }else if  ($_SESSION['tugas'] == '14'){
            $where = "ismubaris";
        }else if  ($_SESSION['tugas'] == '10'){
            $where = "tu";
        }else if  ($_SESSION['tugas'] == '0'){
            $where = "kepsek";
        }else{
            $where = "";
        }

if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
        <h3 class="box-title">  Monev Kegiatan</h3>
      <div class="box-header with-border">
          <div class="row">
    <!-- Filter Form -->
    <form method="GET" action="">
        <input class="hidden" name="view" value="monev_kegiatan"></input>
        <input class="hidden" name=""></input>
        <?php if($_SESSION['tugas']=='0'){?>
         <div class="col-md-3">
           <select name="devisi" class="form-control">
             <option value="">-- Pilih Devisi --</option>
            <?php
            // Daftar semua devisi
            $devisi = [
                "kurikulum" => "Kurikulum",
                "kesiswaan" => "Kesiswaan",
                "humas" => "Humas",
                "sarpras" => "Sarpras",
                "ismubaris" => "Ismubaris",
                "tu" => "TU",
                "kepsek" => "Kepsek"
            ];
        
            // Ambil nilai 'devisi' dari query string URL, jika ada
            $devisi_from_url = isset($_GET['devisi']) ? $_GET['devisi'] : '';
        
            // Menampilkan semua devisi dalam dropdown
            foreach ($devisi as $key => $value) {
                // Tentukan apakah devisi ini dipilih berdasarkan $_GET['devisi']
                $selected = ($devisi_from_url == $key) ? 'selected' : '';
                echo '<option value="' . $key . '" ' . $selected . '>' . $value . '</option>';
            }
            ?>
        </select>

        </div>
        <?php }?>
        <div class="col-md-3">
            <select name="idTahunAjaran" class="form-control">
                <option value="">-- Pilih Tahun Ajaran --</option>
                <?php
                $sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
                while ($t = mysqli_fetch_array($sqltahun)) {
                    $selected = ($_GET['idTahunAjaran'] == $t['idTahunAjaran']) ? ' selected' : '';
                    echo '<option value="' . $t['idTahunAjaran'] . '"' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
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
            <button type="submit" class="btn btn-primary" name="filter">Tampilkan</button>
            
              <?php if($_SESSION['tugas']=='0'){?>
            <a href="excel_monev_kegiatan_admin.php?unit=<?= $_GET['devisi'] ?>&idTahunAjaran=<?=$_GET['idTahunAjaran'] ?>&bulan=<?=$_GET['bulan'] ?>" class="btn btn-success">Cetak Excel</a>
            <?php } else {?>
            <a href="excel_monev_kegiatan_admin.php?unit=<?= $where ?>&idTahunAjaran=<?=$_GET['idTahunAjaran'] ?>&bulan=<?=$_GET['bulan'] ?>" class="btn btn-success">Cetak Excel</a>

            <?php } ?>

        </div>
    </form>
    </div>
      </div><!-- /.box-header -->
      
        
      <div class="box-body">
          

<br>
          <?php
                 if($_SESSION['tugas']=='0'){
               $whereQuery = "WHERE 1=1";
                }else {
                  $whereQuery = "WHERE unit='$where'";

                }
                                // Filter berdasarkan Tahun Ajaran
                if (!empty($_GET['idTahunAjaran'])) {
                    $whereQuery .= " AND rb_rencana_kegiatan.idTahunAjaran = '" . $_GET['idTahunAjaran'] . "' ";
                }
                
                // Filter berdasarkan Bulan (jika dipilih)
                if (!empty($_GET['bulan'])) {
                    $whereQuery .= " AND MONTH(rb_rencana_kegiatan.waktu_mulai) = '" . $_GET['bulan'] . "'";
                }
                if (!empty($_GET['devisi'])) {
                        $whereQuery .= " AND rb_rencana_kegiatan.unit = '" . mysqli_real_escape_string($conn, $_GET['devisi']) . "' ";
                }
                    
            // Menghitung jumlah kegiatan dan total anggaran pada unit tertentu
            $queryCount = mysqli_query($conn, "SELECT COUNT(*) as jumlah_kegiatan, SUM(anggaran) as total_anggaran 
                                               FROM rb_rencana_kegiatan 
                                               $whereQuery  AND (status='setuju' OR status='realisasi')  ");
            
            $countData = mysqli_fetch_array($queryCount);
            $jumlahKegiatan = $countData['jumlah_kegiatan'];
            $totalAnggaran = $countData['total_anggaran'];
            
            // Format total anggaran agar lebih mudah dibaca (misalnya format angka Indonesia)
            $totalAnggaranFormatted = number_format($totalAnggaran, 0, ',', '.');
            ?>
            
            <div class="row">
                <div class="col-md-3">
                    <h4><strong class="text-danger">Jumlah Kegiatan: </strong><span class="text-danger"><?php echo $jumlahKegiatan; ?> Kegiatan</span></h4>
                </div>
                <div class="col-md-3">
                    <h4><strong class="text-danger">Total Anggaran: </strong><span class="text-danger">Rp <?php echo $totalAnggaranFormatted; ?></span></h4>
                </div>
            </div>

            
        <div class="table-responsive">
          <?php
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
                <th>Tahun Ajaran</th>
                <th>Nama Kegiatan</th>
                <th>Uraian</th>
                <th>Penanggung Jawab</th>
                <th>Waktu Mulai</th>
                <th>Waktu Selesai</th>
                <th>Tempat</th>
                <th>Rencana Anggaran</th>                <th>Catatan</th>

                
                    <th>Keterlaksanaan</th>
                <th>Evaluasi</th>
                <th>Tindak Lanjut</th>
            </tr>
        </thead>
        <tbody>
        <?php
               
                    
                
                
                // Query untuk mengambil data sesuai filter
                $tampil = mysqli_query($conn, "
                    SELECT *, rb_rencana_kegiatan.idRk AS Id 
                    FROM rb_rencana_kegiatan 
                    INNER JOIN tahun_ajaran 
                        ON rb_rencana_kegiatan.idTahunAjaran = tahun_ajaran.idTahunAjaran 
                    $whereQuery 
                    AND (status='setuju' OR status='realisasi') 
                    ORDER BY rb_rencana_kegiatan.waktu_mulai ASC
                ");

                $no = 1;
                while ($r = mysqli_fetch_array($tampil)) {
                    // Menentukan tombol berdasarkan status
                        if ($_SESSION['tugas'] == '0') {
                            $tombol =  "<a class='btn btn-warning btn-xs' title='Pending'>$r[keterlaksanaan]</a>";
                            $editDelete = "<center>
                                            <a class='btn btn-success btn-xs' title='Edit Data' href='?view=monev_kegiatan&act=isi&id=$r[idRk]'>
                                                <span class='fa fa-pencil'></span> Isi
                                            </a>
                                            $tombol
                                          </center>";
                        
                        } else {
                            $tombol =  "<a class='btn btn-warning btn-xs' title='Pending'>$r[keterlaksanaan]</a>";
                            $editDelete = "<center>
                                            
                                            $tombol
                                          </center>";
                        
                        }
                
                    echo "<tr>
                            <td>$no</td>
                            <td>$r[nmTahunAjaran]</td>
                            <td>$r[nmKegiatan]</td>
                            <td>$r[uraian]</td>
                            <td>$r[pj]</td>
                            <td>$r[waktu_mulai]</td>
                            <td>$r[waktu_selesai]</td>
                            <td>$r[tempat]</td>
                            <td>" . buatRp($r['anggaran']) . "</td> <!-- Format Anggaran -->
                            <td>$r[catatan]</td>
                            <td>$editDelete</td>
                            <td>$r[evaluasi]</td>
                            <td>$r[tindak_lanjut]</td>
                          </tr>";
                    $no++;
                }
                
                // Menghapus data jika ada perintah untuk menghapus
                if (isset($_GET['hapus'])) {
                    mysqli_query($conn, "DELETE FROM rb_rencana_kegiatan WHERE idRk='$_GET[id]'");
                    echo "<script>document.location='?view=monev_kegiatan';</script>";
                }
?>

    </tbody>
</table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>

<?php
}else if ($_GET['act'] == 'isi') {
    if (isset($_POST['isi'])) {
        $id = $_POST['idRk'];  // id yang diedit
        $keterlaksanaan = $_POST['keterlaksanaan'];
        $evaluasi = $_POST['evaluasi'];
        $tindak_lanjut = $_POST['tindak_lanjut'];
        $status_monev = $_POST['status_monev'];

        // Update query tanpa file upload (hanya update data tanpa file)
        $query = mysqli_query($conn, "UPDATE rb_rencana_kegiatan SET 
         
            keterlaksanaan='$keterlaksanaan',
            evaluasi='$evaluasi',
            tindak_lanjut='$tindak_lanjut', status_monev='$status_monev'
            WHERE idRk='$id'");

        // Redirect setelah update
        if ($query) {
            echo "<script>document.location='?view=monev_kegiatan&sukses';</script>";
        } else {
            echo "<script>document.location='?view=monev_kegiatan&gagal';</script>";
        }
    }

    // Ambil data untuk diedit
    $edit = mysqli_query($conn, "SELECT * FROM rb_rencana_kegiatan WHERE idRk='$_GET[id]'");
    $record = mysqli_fetch_array($edit);
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Data Rencana Kegiatan</h3>
            </div>
            <div class="box-body">
                <form method="post" action="" class="form-horizontal">
                    <input type="hidden" name="idRk" value="<?php echo $record['idRk']; ?>">

                   

                    <!-- Nama Kegiatan -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Kegiatan</label>
                        <div class="col-sm-4">
                            <input type="text" name="nmKegiatan" class="form-control" value="<?php echo $record['nmKegiatan']; ?>" readonly>
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Keterlaksanaan</label>
                        <div class="col-sm-4">
                           <select name="keterlaksanaan" class="form-control">
                                <option value="">-- Pilih Keterlaksanaan --</option>
                                <option value="terlaksana" <?php echo ($record['keterlaksanaan'] == 'terlaksana') ? 'selected' : ''; ?>>Terlaksana</option>
                                <option value="tidak terlaksana" <?php echo ($record['keterlaksanaan'] == 'tidak terlaksana') ? 'selected' : ''; ?>>Tidak Terlaksana</option>
                                <option value="batal" <?php echo ($record['keterlaksanaan'] == 'batal') ? 'selected' : ''; ?>>Batal</option>
                                <option value="tunda" <?php echo ($record['keterlaksanaan'] == 'tunda') ? 'selected' : ''; ?>>Ditunda</option>
                            </select>
                        </div>
                    </div>

                    <!-- Penanggung Jawab -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Evaluasi</label>
                        <div class="col-sm-4">
                            <textarea name="evaluasi" class="form-control" rows="3" required> <?php echo $record['evaluasi']; ?> </textarea>
                        </div>
                    </div>

                    <!-- Waktu Mulai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tindak Lanjut</label>
                        <div class="col-sm-4">
                            <textarea name="tindak_lanjut" class="form-control" rows="3" required> <?php echo $record['tindak_lanjut']; ?> </textarea>
                        </div>
                    </div>


                    <!-- Submit Button -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" name="isi" value="Update" class="btn btn-success">
                            <a href="?view=monev_kegiatan" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php
}else if ($_GET['act'] == 'edit') {
   if (isset($_POST['update'])) {
    $id = $_POST['idRk'];  // id yang diedit
    $idTahunAjaran = $_POST['idTahunAjaran'];
    $nmKegiatan = $_POST['nmKegiatan'];
    $uraian = $_POST['uraian'];
    $pj = $_POST['pj'];
    $waktu_mulai = $_POST['waktu_mulai'];
    $waktu_selesai = $_POST['waktu_selesai'];
    $tempat = $_POST['tempat'];
    $anggaran = $_POST['anggaran'];

    // Mengambil file yang diupload
    $file_realisasi = $_FILES['file_realisasi']['name'];
    $file_tmp = $_FILES['file_realisasi']['tmp_name'];
    $file_size = $_FILES['file_realisasi']['size'];
    $file_error = $_FILES['file_realisasi']['error'];

    // Tentukan folder tempat file akan disimpan
    $folder = "file_realisasi/";

    // Tentukan ekstensi file yang diterima
    $allowed_ext = array('pdf');
    $file_ext = strtolower(pathinfo($file_realisasi, PATHINFO_EXTENSION));

    // Jika file diupload, lakukan validasi dan proses upload
    if ($file_error === 0) {
        if ($file_size <= 41943040) { // 40MB = 40 * 1024 * 1024
            if (in_array($file_ext, $allowed_ext)) {
                // Membuat nama file unik agar tidak ada konflik
                $file_new_name = uniqid('', true) . '.' . $file_ext;
                $file_destination = $folder . $file_new_name;

                // Pindahkan file ke folder tujuan
                if (move_uploaded_file($file_tmp, $file_destination)) {
                    // Update query untuk menyertakan file baru
                    $query = mysqli_query($conn, "UPDATE rb_rencana_kegiatan SET 
                        idTahunAjaran='$idTahunAjaran', 
                        nmKegiatan='$nmKegiatan', 
                        uraian='$uraian',
                        pj='$pj', 
                        waktu_mulai='$waktu_mulai',
                        waktu_selesai='$waktu_selesai',
                        tempat='$tempat',
                        anggaran='$anggaran',
                        file_realisasi='$file_new_name',
                        status='realisasi'
                        WHERE idRk='$id'");

                    // Redirect setelah update
                    if ($query) {
                        echo "<script>document.location='?view=monev_kegiatan&sukses';</script>";
                    } else {
                        echo "<script>document.location='?view=monev_kegiatan&gagal';</script>";
                    }
                } else {
                    echo "<script>document.location='?view=monev_kegiatan&gagal_upload';</script>";
                }
            } else {
                echo "<script>document.location='?view=monev_kegiatan&gagal_format';</script>";
            }
        } else {
            echo "<script>document.location='?view=monev_kegiatan&gagal_size';</script>";
        }
    } else {
        // Jika tidak ada file diupload, lakukan update tanpa mengubah file_realisasi
        $query = mysqli_query($conn, "UPDATE rb_rencana_kegiatan SET 
            idTahunAjaran='$idTahunAjaran', 
            nmKegiatan='$nmKegiatan', 
            uraian='$uraian',
            pj='$pj', 
            waktu_mulai='$waktu_mulai',
            waktu_selesai='$waktu_selesai',
            tempat='$tempat',
            anggaran='$anggaran',
            status='realisasi'
            WHERE idRk='$id'");

        // Redirect setelah update
        if ($query) {
            echo "<script>document.location='?view=monev_kegiatan&sukses';</script>";
        } else {
            echo "<script>document.location='?view=monev_kegiatan&gagal';</script>";
        }
    }
}



    // Ambil data untuk diedit
    $edit = mysqli_query($conn, "SELECT * FROM rb_rencana_kegiatan WHERE idRk='$_GET[id]'");
    $record = mysqli_fetch_array($edit);
?>
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Edit Data Rencana Kegiatan</h3>
            </div>
            <div class="box-body">
            <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="idRk" value="<?php echo $record['idRk']; ?>">

                    <!-- Tahun Ajaran -->
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
                            </select>
                        </div>
                    </div>

                    <!-- Nama Kegiatan -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Nama Kegiatan</label>
                        <div class="col-sm-4">
                            <input type="text" name="nmKegiatan" class="form-control" value="<?php echo $record['nmKegiatan']; ?>" required>
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Uraian</label>
                        <div class="col-sm-4">
                            <textarea name="uraian" class="form-control" rows="3" required><?php echo $record['uraian']; ?></textarea>
                        </div>
                    </div>

                    <!-- Penanggung Jawab -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Penanggung Jawab</label>
                        <div class="col-sm-4">
                            <input type="text" name="pj" class="form-control" value="<?php echo $record['pj']; ?>" required>
                        </div>
                    </div>

                    <!-- Waktu Mulai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Mulai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_mulai" class="form-control" value="<?php echo $record['waktu_mulai']; ?>" required>
                        </div>
                    </div>

                    <!-- Waktu Selesai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Selesai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_selesai" class="form-control" value="<?php echo $record['waktu_selesai']; ?>" required>
                        </div>
                    </div>

                    <!-- Tempat -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tempat</label>
                        <div class="col-sm-4">
                            <input type="text" name="tempat" class="form-control" value="<?php echo $record['tempat']; ?>" required>
                        </div>
                    </div>

                    <!-- Anggaran -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Realisasi Anggaran</label>
                        <div class="col-sm-4">
                            <input type="number" name="anggaran" class="form-control" value="<?php echo $record['realisasi']; ?>" required>
                        </div>
                    </div>
                    <!-- File Upload (PDF) -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Upload File Realisasi (PDF)</label>
                        <div class="col-sm-4">
                            <?php if ($record['file_realisasi']) { ?>
                                <p>File Realisasi: <a href="file_realisasi/<?php echo $record['file_realisasi']; ?>" target="_blank">Download PDF</a></p>
                            <?php } ?>
                            <input type="file" name="file_realisasi" class="form-control" accept=".pdf">
                            <small>File harus berformat PDF dan maksimal 40MB.</small>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-2">
                            <input type="submit" name="update" value="Update" class="btn btn-success">
                            <a href="?view=monev_kegiatan" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php
}
?>
