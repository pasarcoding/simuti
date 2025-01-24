<?php if ($_GET['act'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data SK Tahunan </h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <!-- Filter Tahun Ajaran -->
          <form method="GET" action="">
            <input type="hidden" name="view" value="sktahunan">
            <div class="form-group">
              <label for="tahunAjaran">Pilih Tahun Ajaran:</label>
              <select name="tahunAjaran" id="tahunAjaran" class="form-control" onchange="this.form.submit()">
                <option value="">Semua Tahun Ajaran</option>
                <?php
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
                      <th>Nama File</th>
                      <th>Aksi</th>
                  </tr>
              </thead>
              <tbody>
                  <?php
                  // Filter berdasarkan tahun ajaran
                  $tahunAjaranFilter = isset($_GET['tahunAjaran']) && $_GET['tahunAjaran'] != '' 
                      ? "AND rb_sk_tahunan.idTahunAjaran='$_GET[tahunAjaran]'" 
                      : '';

                  // Query untuk mengambil data
                  $tampil = mysqli_query($conn, "SELECT *, rb_sk_tahunan.id as Id FROM rb_sk_tahunan 
                  INNER JOIN rb_guru ON rb_sk_tahunan.nik=rb_guru.id
                  INNER JOIN tahun_ajaran ON rb_sk_tahunan.idTahunAjaran=tahun_ajaran.idTahunAjaran 
                  WHERE rb_sk_tahunan.nik='$_SESSION[id]' $tahunAjaranFilter
                  ORDER BY rb_sk_tahunan.idTahunAjaran ASC"); 
                  $no = 1;
                  while ($r = mysqli_fetch_array($tampil)) {
                      echo "<tr>
                              <td>$no</td>
                              <td>$r[nmTahunAjaran]</td>
                              <td>$r[judul_file]</td>
                              <td>
                                  <a class='btn btn-info btn-xs' title='Lihat File' href='$r[file]' target='_blank'>
                                      <span class='glyphicon glyphicon-eye-open'></span> Lihat File
                                  </a>
                              </td>
                            </tr>";
                      $no++;
                  }
                  if (isset($_GET['hapus'])) {
                      mysqli_query($conn, "DELETE FROM rb_sk_tahunan WHERE id='$_GET[id]'");
                      echo "<script>document.location='?view=sktahunan';</script>";
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