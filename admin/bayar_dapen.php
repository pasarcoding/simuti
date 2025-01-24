<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <a class='pull-left btn btn-success btn-md' style="margin-right: 10px;" href='index.php?view=bayar_dapen&act=setor'><i class="fa fa-plus"></i> Setor</a>
        <span style="margin: 40px;"></span> <!-- Tambahkan jarak di antara tombol -->
        <a class='pull-left btn btn-danger btn-md' href='index.php?view=bayar_dapen&act=tarik'><i class="fa fa-minus"></i> Tarik</a>

      </div><!-- /.box-header -->
      <table class="table table-striped">
        <form action="cetak_transaksi_guru.php" method="GET" target="output">

          <tbody>
            <tr>
              <td>

                <select id="guru" name="guru" class="form-control">
                  <option value="all">- Semua Guru -</option>
                  <?php
                  $sqk = mysqli_query($conn, "SELECT * FROM rb_guru ORDER BY id ASC");
                  while ($k = mysqli_fetch_array($sqk)) {
                    $selected = ($k['id'] == $kelas) ? ' selected="selected"' : "";
                    echo "<option value='" . $k['id'] . "'" . $selected . ">" . $k['nama_guru'] . "</option>";
                  }
                  ?>
                </select>

              </td>
              <td>
                <button type="submit" name="pdf" class="btn btn-warning btn-sm">
                  <i class="glyphicon glyphicon-print"></i> Cetak Laporan Saldo
                </button>


                <button type="submit" name="excel" class="btn btn-success btn-sm">
                  <i class="fa fa-file-excel-o"></i> Excel Laporan Saldo
                </button>
              </td>

            </tr>
          </tbody>
        </form>
      </table>


      <div class="box-body">

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
                <th>No</th>
                <th>Nama Guru</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Saldo</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              // Query to get all transactions in descending order
              $tampil = mysqli_query($conn, "SELECT * FROM transaksi_guru 
    INNER JOIN rb_guru ON transaksi_guru.id_guru = rb_guru.id
    ORDER BY id_transaksi ASC");

              // Array to keep track of the saldo for each guru
              $saldo = [];
              $no = 1;

              while ($r = mysqli_fetch_array($tampil)) {
                // Initialize saldo for new guru if not already initialized
                if (!isset($saldo[$r['id_guru']])) {
                  $saldo[$r['id_guru']] = 0;
                }

                // Update saldo
                $saldo[$r['id_guru']] += $r['debit'];
                $saldo[$r['id_guru']] -= $r['kredit'];

                echo "<tr>
              <td>$no</td>
              <td>$r[nama_guru]</td>
              <td>" . buatRp($r['debit']) . "</td>
              <td>" . buatRp($r['kredit']) . "</td>
              <td>$r[tanggal]</td>
              <td>$r[keterangan]</td>
              <td>" . buatRp($saldo[$r['id_guru']]) . "</td>
              <td><center>
                <a class='btn btn-success btn-xs' title='Edit Data' href='?view=bayar_dapen&act=edit_tarik&id=$r[id_transaksi]'><span class='glyphicon glyphicon-edit'></span></a>
                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=bayar_dapen&hapus&id=$r[id_transaksi]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
              </center></td>
            </tr>";
                $no++;
              }

              // Delete operation
              if (isset($_GET['hapus'])) {
                mysqli_query($conn, "DELETE FROM transaksi_guru WHERE id_transaksi='$_GET[id]'");
                echo "<script>document.location='index.php?view=bayar_dapen';</script>";
              }
              ?>
            </tbody>
          </table>


        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>

  <?php
} elseif ($_GET['act'] == 'tarik') {
  if (isset($_POST['tarik'])) {
    // Ambil data dari form
    $nama_guru = $_POST['nama_guru'];
    $debit = 0; // Debit diatur menjadi 0 karena ini adalah form tarik
    $kredit = $_POST['kredit'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    // Lakukan validasi data sesuai kebutuhan

    // Proses simpan data ke database
    $query = mysqli_query($conn, "INSERT INTO transaksi_guru(id_guru, debit, kredit, tanggal, keterangan, user) VALUES ('$nama_guru', '$debit', '$kredit', '$tanggal', '$keterangan', '$_SESSION[namalengkap]')");

    if ($query) {
      echo "<script>alert('Penarikan berhasil disimpan');</script>";
      echo "<script>document.location='index.php?view=bayar_dapen';</script>";
    } else {
      echo "<script>alert('Penarikan gagal disimpan');</script>";
    }
  }
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Tarik Dapen Guru </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nama Guru</label>
              <div class="col-sm-6">
                <select name="nama_guru" data-live-search="true" class="form-control selectpicker">
                  <option value="">- Cari Guru -</option>
                  <?php
                  $sqlGuru = mysqli_query($conn, "SELECT * FROM rb_guru");
                  while ($g = mysqli_fetch_array($sqlGuru)) {
                    echo "<option value='$g[id]'>$g[nama_guru]</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Tarik</label>
              <div class="col-sm-6">
                <input type="number" name="kredit" class="form-control" placeholder="Jumlah Tarik" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Tanggal</label>
              <div class="col-sm-6">
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Keterangan</label>
              <div class="col-sm-6">
                <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="tarik" value="Tarik" class="btn btn-success">
                <a href="index.php?view=bayar_dapen" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
} elseif ($_GET[act] == 'setor') {
  if (isset($_POST['setor'])) {
    // Ambil data dari form
    $nama_guru = $_POST['nama_guru'];
    $debit = $_POST['debit'];
    $kredit = 0; // Kredit diatur menjadi 0 karena ini adalah form setor
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    // Lakukan validasi data sesuai kebutuhan

    // Proses simpan data ke database
    $query = mysqli_query($conn, "INSERT INTO transaksi_guru(id_guru, debit, kredit, tanggal, keterangan,user) VALUES ('$nama_guru', '$debit', '$kredit', '$tanggal', '$keterangan','$_SESSION[namalengkap]')");

    if ($query) {
      echo "<script>alert('Setoran berhasil disimpan');</script>";
      echo "<script>document.location='index.php?view=bayar_dapen';</script>";
    } else {
      echo "<script>alert('Setoran gagal disimpan');</script>";
    }
  }
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Setor Dapen Guru </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nama Guru</label>
              <div class="col-sm-6">
                <select name="nama_guru" data-live-search="true" class="form-control selectpicker">
                  <option value="">- Cari Guru -</option>
                  <?php
                  $sqlSiswa = mysqli_query($conn, "SELECT * FROM rb_guru");
                  while ($s = mysqli_fetch_array($sqlSiswa)) {
                    echo "<option value='$s[id]'>$s[nama_guru] </option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Setor</label>
              <div class="col-sm-6">
                <input type="number" name="debit" class="form-control" placeholder="Jumlah Setor" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Tanggal</label>
              <div class="col-sm-6">
                <input type="date" name="tanggal" class="form-control" value="<?= date('Y-m-d') ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Keterangan</label>
              <div class="col-sm-6">
                <textarea name="keterangan" class="form-control" placeholder="Keterangan"></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="setor" value="Setor" class="btn btn-success">
                <a href="index.php?view=bayar_dapen" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
} elseif ($_GET['act'] == 'edit_tarik') {
  if (isset($_POST['edit'])) {
    // Ambil data dari form
    $id_transaksi = $_POST['id_transaksi'];
    $nama_guru = $_POST['nama_guru'];
    $debit = $_POST['debit'];
    $kredit = $_POST['kredit'];
    $tanggal = $_POST['tanggal'];
    $keterangan = $_POST['keterangan'];

    // Lakukan validasi data sesuai kebutuhan

    // Proses simpan atau update data ke database
    if (isset($id_transaksi) && $id_transaksi != '') {
      $query = mysqli_query($conn, "UPDATE transaksi_guru SET id_guru='$nama_guru', debit='$debit', kredit='$kredit', tanggal='$tanggal', keterangan='$keterangan', user='$_SESSION[namalengkap]' WHERE id_transaksi='$id_transaksi'");
    } else {
      $query = mysqli_query($conn, "INSERT INTO transaksi_guru(id_guru, debit, kredit, tanggal, keterangan, user) VALUES ('$nama_guru', '$debit', '$kredit', '$tanggal', '$keterangan', '$_SESSION[namalengkap]')");
    }

    if ($query) {
      echo "<script>alert('Penarikan berhasil disimpan');</script>";
      echo "<script>document.location='index.php?view=bayar_dapen';</script>";
    } else {
      echo "<script>alert('Penarikan gagal disimpan');</script>";
    }
  }

  $edit = mysqli_query($conn, "SELECT * FROM transaksi_guru where id_transaksi='$_GET[id]'");
  $record = mysqli_fetch_array($edit);
  ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Dapen Guru </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <form method="POST" action="" class="form-horizontal">
            <input type="hidden" name="id_transaksi" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Nama Guru</label>
              <div class="col-sm-6">
                <select name="nama_guru" data-live-search="true" class="form-control selectpicker">
                  <option value="">- Cari Guru -</option>
                  <?php
                  $sqlGuru = mysqli_query($conn, "SELECT * FROM rb_guru");
                  while ($g = mysqli_fetch_array($sqlGuru)) {
                    $selected = ($g['id'] == $record['id_guru']) ? ' selected="selected"' : "";
                    echo "<option value='" . $g['id'] . "'" . $selected . ">" . $g['nama_guru'] . "</option>";
                  }
                  ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Setor</label>
              <div class="col-sm-6">
                <input type="number" name="debit" class="form-control" value="<?= $record['debit'] ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Tarik</label>
              <div class="col-sm-6">
                <input type="number" name="kredit" class="form-control" value="<?= $record['kredit'] ?>" required>
              </div>
            </div>

            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Tanggal</label>
              <div class="col-sm-6">
                <input type="date" name="tanggal" class="form-control" value="<?= $record['tanggal'] ?>" required>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label">Keterangan</label>
              <div class="col-sm-6">
                <textarea name="keterangan" class="form-control" value=""><?= $record['keterangan'] ?></textarea>
              </div>
            </div>
            <div class="form-group">
              <label for="" class="col-sm-2 control-label"></label>
              <div class="col-sm-10">
                <input type="submit" name="edit" value="Tarik" class="btn btn-success">
                <a href="index.php?view=bayar_dapen" class="btn btn-default">Cancel</a>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  <?php
}
  ?>