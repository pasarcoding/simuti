<?php 

            $where = "humas";


if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
          <div class="row">
    <!-- Filter Form -->
    <form method="GET" action="">
        <input class="hidden" name="view" value="realisasi_kegiatan_<?= $where?>"></input>
        <input class="hidden" name=""></input>

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
        
        
        <input class="hidden" value="<?= $where ?>" name="unit"></input>
        <div class="col-md-3">
            <button type="submit" class="btn btn-primary" name="filter">Tampilkan</button>
            <a href="excel_realisasi_kegiatan_admin.php?unit=<?= $where ?>&idTahunAjaran=<?=$_GET['idTahunAjaran'] ?>&bulan=<?=$_GET['bulan'] ?>" class="btn btn-success">Cetak Excel</a>

        </div>
    </form>
    </div>
      </div><!-- /.box-header -->
      
        
      <div class="box-body">
          

<br>
          <?php
            $whereQuery = "WHERE unit='$where' AND (status='setuju' OR status='realisasi')";
          if (!empty($_GET['idTahunAjaran'])) {
                    $whereQuery .= " AND idTahunAjaran = '" . $_GET['idTahunAjaran'] . "'";
                }
                
                // Filter berdasarkan Bulan (jika dipilih)
                if (!empty($_GET['bulan'])) {
                    $whereQuery .= " AND MONTH(waktu_mulai) = '" . $_GET['bulan'] . "'";
                }
                
                // Filter berdasarkan Approval
                if (!empty($_GET['approval'])) {
                    $whereQuery .= " AND status = '" . $_GET['approval'] . "'";
                }
            
                                                
            // Menghitung jumlah kegiatan dan total anggaran pada unit tertentu
            $queryCount = mysqli_query($conn, "SELECT COUNT(*) as jumlah_kegiatan, SUM(realisasi) as total_anggaran 
                                               FROM rb_rencana_kegiatan 
                                               $whereQuery"); 
            
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
                <th>Realisasi Anggaran</th>
                 <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        <?php
            // Menangani query filter
                $whereQuery = "WHERE unit='$where'";  // Filter berdasarkan unit
                
                // Filter berdasarkan Tahun Ajaran
                if (!empty($_GET['idTahunAjaran'])) {
                    $whereQuery .= " AND rb_rencana_kegiatan.idTahunAjaran = '" . $_GET['idTahunAjaran'] . "' ";
                }
                
                // Filter berdasarkan Bulan (jika dipilih)
                if (!empty($_GET['bulan'])) {
                    $whereQuery .= " AND MONTH(rb_rencana_kegiatan.waktu_mulai) = '" . $_GET['bulan'] . "'";
                }
                
                
                
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
                        if ($r['status'] == 'setuju') {
                            $tombol =  "<a class='btn btn-warning btn-xs' title='Pending'>$r[status]</a>";
                            $editDelete = "<center>
                                            <a class='btn btn-success btn-xs' title='Edit Data' href='?view=realisasi_kegiatan_$where&act=edit&id=$r[idRk]'>
                                                <span class='fa fa-pencil'></span> Input Realisasi
                                            </a>
                                            
                                          </center>";
                        
                        } else if ($r['status'] == 'realisasi') {
                            // Untuk status lain yang tidak terduga
                            $tombol =  "<a class='btn btn-default btn-xs' title='Status Tidak Diketahui'>$r[status]</a>";
                            $editDelete = "<center>
                                            <a class='btn btn-success btn-xs' title='Edit Data' href='?view=realisasi_kegiatan_$where&act=edit&id=$r[idRk]'>
                                                <span class='fa fa-pencil'></span> Input Realisasi
                                            </a>
                                            
                                          </center>";
                        } else{
                             // Untuk status lain yang tidak terduga
                            $tombol =  "<a class='btn btn-default btn-xs' title='Status Tidak Diketahui'>$r[status]</a>";
                            $editDelete = "<center>
                                            <a class='btn btn-success btn-xs' title='Edit Data' href='?view=realisasi_kegiatan_$where&act=edit&id=$r[idRk]'>
                                                <span class='fa fa-pencil'></span> Input Realisasi
                                            </a>
                                            
                                          </center>";
                        }
                
                    echo "<tr>
                            <td>$no</td>
                            <td>$r[nmTahunAjaran]</td>
                            <td>$r[nmKegiatan]</td>
                            <td>$r[uraian_u]</td>
                            <td>$r[pj_u]</td>
                            <td>$r[waktu_mulai_u]</td>
                            <td>$r[waktu_selesai_u]</td>
                            <td>$r[tempat_u]</td>
                            <td>" . buatRp($r['realisasi']) . "</td> <!-- Format Anggaran -->
                            <td>
                                <a class='btn btn-info btn-xs' title='Lihat File' href='file_rencana/$r[file_rencana]' target='_blank'>
                                    <span class='glyphicon glyphicon-eye-open'></span> Lihat File
                                </a>
                            </td>
                          </tr>";
                    $no++;
                }
                
                // Menghapus data jika ada perintah untuk menghapus
                if (isset($_GET['hapus'])) {
                    mysqli_query($conn, "DELETE FROM rb_rencana_kegiatan WHERE idRk='$_GET[id]'");
                    echo "<script>document.location='?view=realisasi_kegiatan_$where';</script>";
                }
?>

    </tbody>
</table>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
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
                        uraian_u='$uraian',
                        pj_u='$pj', 
                        waktu_mulai_u='$waktu_mulai',
                        waktu_selesai_u='$waktu_selesai',
                        tempat_u='$tempat',
                        realisasi='$anggaran',
                        file_realisasi='$file_new_name',
                        status='realisasi'
                        WHERE idRk='$id'");

                    // Redirect setelah update
                    if ($query) {
                        echo "<script>document.location='?view=realisasi_kegiatan_$where&sukses';</script>";
                    } else {
                        echo "<script>document.location='?view=realisasi_kegiatan_$where&gagal';</script>";
                    }
                } else {
                    echo "<script>document.location='?view=realisasi_kegiatan_$where&gagal_upload';</script>";
                }
            } else {
                echo "<script>document.location='?view=realisasi_kegiatan_$where&gagal_format';</script>";
            }
        } else {
            echo "<script>document.location='?view=realisasi_kegiatan_$where&gagal_size';</script>";
        }
    } else {
        // Jika tidak ada file diupload, lakukan update tanpa mengubah file_realisasi
        $query = mysqli_query($conn, "UPDATE rb_rencana_kegiatan SET 
            idTahunAjaran='$idTahunAjaran', 
            nmKegiatan='$nmKegiatan', 
            uraian_u='$uraian',
            pj_u='$pj', 
            waktu_mulai_u='$waktu_mulai',
            waktu_selesai_u='$waktu_selesai',
            tempat_u='$tempat',
            realisasi='$anggaran',
            status='realisasi'
            WHERE idRk='$id'");

        // Redirect setelah update
        if ($query) {
            echo "<script>document.location='?view=realisasi_kegiatan_$where&sukses';</script>";
        } else {
            echo "<script>document.location='?view=realisasi_kegiatan_$where&gagal';</script>";
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
                <h3 class="box-title">Input Realisasi Kegiatan</h3>
            </div>
            <div class="box-body">
            <form method="post" action="" class="form-horizontal" enctype="multipart/form-data">
                    <input type="hidden" name="idRk" value="<?php echo $record['idRk']; ?>">

                    <!-- Tahun Ajaran -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tahun Ajaran</label>
                        <div class="col-sm-4">
                            <select name="idTahunAjaran" class="form-control" readonly>
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
                            <input type="text" name="nmKegiatan" class="form-control" value="<?php echo $record['nmKegiatan']; ?>" readonly>
                        </div>
                    </div>

                    <!-- Uraian -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Uraian</label>
                        <div class="col-sm-4">
                            <textarea name="uraian" class="form-control" rows="3">
                                <?php echo isset($record['uraian_u']) && $record['uraian_u'] != NULL ? $record['uraian_u'] : $record['uraian']; ?>
                            </textarea>
                        </div>
                    </div>
                    
                    <!-- Penanggung Jawab -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Penanggung Jawab</label>
                        <div class="col-sm-4">
                            <input type="text" name="pj" class="form-control" 
                                value="<?php echo isset($record['pj_u']) && $record['pj_u'] != NULL ? $record['pj_u'] : $record['pj']; ?>" >
                        </div>
                    </div>
                    
                    <!-- Waktu Mulai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Mulai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_mulai" class="form-control" 
                                value="<?php echo isset($record['waktu_mulai_u']) && $record['waktu_mulai_u'] != NULL ? $record['waktu_mulai_u'] : $record['waktu_mulai']; ?>" >
                        </div>
                    </div>
                    
                    <!-- Waktu Selesai -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Waktu Selesai</label>
                        <div class="col-sm-4">
                            <input type="date" name="waktu_selesai" class="form-control" 
                                value="<?php echo isset($record['waktu_selesai_u']) && $record['waktu_selesai_u'] != NULL ? $record['waktu_selesai_u'] : $record['waktu_selesai']; ?>" >
                        </div>
                    </div>
                    
                    <!-- Tempat -->
                    <div class="form-group">
                        <label for="" class="col-sm-2 control-label">Tempat</label>
                        <div class="col-sm-4">
                            <input type="text" name="tempat" class="form-control" 
                                value="<?php echo isset($record['tempat_u']) && $record['tempat_u'] != NULL ? $record['tempat_u'] : $record['tempat']; ?>" >
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
                            <a href="?view=realisasi_kegiatan_$where" class="btn btn-default">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


<?php
}
?>
