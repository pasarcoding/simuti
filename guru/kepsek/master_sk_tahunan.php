<?php
if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Data SK Tahunan</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <!-- Filter Tahun Ajaran -->
          <form method="GET" action="">
            <input type="hidden" name="view" value="sktahunan_all">
            <div class="form-group">
              <label for="tahunAjaran">Pilih Tahun Ajaran:</label>
              <select name="tahunAjaran" id="tahunAjaran" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Tahun Ajaran</option>
                <?php
                // Secure the database query to prevent SQL injection
                $tahunAjaranQuery = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran");
                while ($tahun = mysqli_fetch_array($tahunAjaranQuery)) {
                    $selected = (isset($_GET['tahunAjaran']) && $_GET['tahunAjaran'] == $tahun['idTahunAjaran']) ? 'selected' : '';
                    echo "<option value='{$tahun['idTahunAjaran']}' $selected>{$tahun['nmTahunAjaran']}</option>";
                }
                ?>
              </select>
            </div>
          </form>

          <?php
          // Display success or error messages
          if (isset($_GET['sukses'])) {
            echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses..
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
                      <th>Tahun Ajaran</th>
                      <th>Nama PTK</th>
                      <th>Nama File</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  // Secure and filter by academic year
                  $tahunAjaranFilter = isset($_GET['tahunAjaran']) && $_GET['tahunAjaran'] != '' 
                      ? "AND rb_sk_tahunan.idTahunAjaran = '".mysqli_real_escape_string($conn, $_GET['tahunAjaran'])."'" 
                      : '';

                  // Query to retrieve data
                  $tampil = mysqli_query($conn, "SELECT * 
                                                  FROM rb_sk_tahunan 
                                                  LEFT JOIN rb_guru ON rb_sk_tahunan.nik = rb_guru.id
                                                  LEFT JOIN tahun_ajaran ON rb_sk_tahunan.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                                  WHERE 1=1 $tahunAjaranFilter
                                                  ORDER BY rb_sk_tahunan.idTahunAjaran ASC");

                  // Check for database errors
                  if (!$tampil) {
                      echo "<p>Error: " . mysqli_error($conn) . "</p>";
                  } else {
                      $no = 1;
                      while ($r = mysqli_fetch_array($tampil)) {
                          echo "<tr>
                                  <td>$no</td>
                                  <td>{$r['nmTahunAjaran']}</td>
                                  <td>{$r['nama_guru']}</td>
                                  <td>{$r['judul_file']}</td>
                                  <td>
                                      <a class='btn btn-info btn-xs' title='Lihat File' href='{$r['file']}' target='_blank'>
                                          <span class='glyphicon glyphicon-eye-open'></span> Lihat File
                                      </a>
                                  </td>
                                </tr>";
                          $no++;
                      }
                  }

                  // Handle deletion of SK file
                  if (isset($_GET['hapus']) && isset($_GET['id'])) {
                      $idToDelete = mysqli_real_escape_string($conn, $_GET['id']);
                      $deleteQuery = mysqli_query($conn, "DELETE FROM rb_sk_tahunan WHERE id = '$idToDelete'");
                      if ($deleteQuery) {
                          echo "<script>document.location='?view=sktahunan';</script>";
                      } else {
                          echo "<script>alert('Error deleting record');</script>";
                      }
                  }
                  ?>
              </tbody>
          </table>
        </div><!-- /.table-responsive -->
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
<?php
}
?>
