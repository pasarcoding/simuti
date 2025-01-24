<?php
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['status'];

$templateFilePath = 'templateSuksesMitra.php';
$templateEmail = file_get_contents($templateFilePath);

if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data Mitra </h3>

      </div><!-- /.box-header -->

      <div class="table-responsive">

        <div class="box-body">
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
          } elseif (isset($_GET['belum'])) {
            echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data tidak Di Proses, pesanan belum terbayar..
                          </div>";
          } elseif (isset($_GET['sukseshapus'])) {
            echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Berhasil!</strong> - Data Berhasil dihapus.....
                          </div>";
          } elseif (isset($_GET['gagalhapus'])) {
            echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
                          <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                          <span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Data ini telah digunakan oleh data lain, sehingga tidak bida dihapus!!
                          </div>";
          }
          ?>
          <table id="listPayment" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Mitra</th>
                <th>Alamat</th>
                <th>Tautan</th>
                <th>Layanan</th>
                <th>Waktu Daftar</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>
          <script>
            $(document).ready(function() {
              $('#listPayment').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": "models/modelMitra.php",
                "autoWidth": true,
                "order": [
                  [0, 'desc']
                ], // Menambahkan opsi autoWidth untuk membuat lebar tabel otomatis
                "columns": [{
                    data: 'no',
                    name: 'id',
                    render: function(data, type, row, meta) {
                      return meta.row + meta.settings._iDisplayStart + 1;
                    }
                  },
                  {
                    "data": 'nmMitra'
                  },
                  {
                    "data": 'alamat'
                  },
                  {
                    "data": 'tautan'
                  },
                  {
                    "data": 'kategoriTiket'
                  },

                  {
                    "data": 'registerMitra'
                  },


                  {
                    "data": 'status'
                  },

                  {
                    "data": 'aksi'
                  },
                ]
              });
            });
          </script>
        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>
  <?php

} else  if ($_GET['act'] == 'edit') { ?>
    <div class="col-md-12">
      <div class="box box-primary">
        <div class="box-header with-border">
          <h3 class="box-title"> Edit Data Pesanan </h3>
        </div><!-- /.box-header -->
        <div class="box-body">
          <?php
          if (isset($_POST['update'])) {

            $query = mysqli_query($conn, "UPDATE tbl_mitra SET 
                                     nmMitra = '$_POST[nmMitra]',
                                     kategoriTiket = '$_POST[kategoriTiket]',
                                     alamat = '$_POST[alamat]',
                                     potonganPertiket = '$_POST[potonganPertiket]',
                                     
                                      where idMitra = '$_POST[id]'");


            if ($query) {
              echo "<script>document.location='?view=mitraBelum&sukses';</script>";
            } else {
              echo "<script>document.location='?view=mitraBelum&gagal';</script>";
            }
          }
          $edit = mysqli_query($conn, "SELECT * FROM tbl_mitra where idMitra='$_GET[id]'");
          $record = mysqli_fetch_array($edit);
          ?>
          <form method="post" action="" class="form-horizontal">
            <input type="hidden" name="id" value="<?php echo $record['idMitra']; ?>">
            <table class="table">
              <tr>
                <td>Email</td>
                <td>
                  <div class="col-sm-12">
                    <input type="text" name="email" value="<?php echo $record['id_user']; ?>" class="form-control" readonly>
                  </div>
                </td>
                <td>Status</td>
                <td>
                  <div class="col-sm-12">
                    <select name="verif" id="jenis" class="form-control" required>
                      <option value="<?php echo $record['verif']; ?>"> <?php echo $record['verif']; ?></option>
                      <option value="Y"> Sukses</option>
                      <option value="T"> Belum Bayar</option>
                      <option value="P"> Pending</option>
                      <option value="E"> Expired</option>
                    </select>
                  </div>
                </td>
              </tr>

              <tr>
                <td>Harga Tiket</td>
                <td>
                  <div class="col-sm-12">
                    <input type="text" name="harga" value="<?php echo $record['harga']; ?>" class="form-control">
                  </div>
                </td>
                <td>Jumlah Tiket</td>
                <td>
                  <div class="col-sm-12">
                    <input type="text" name="jumlah" value="<?php echo $record['jumlah']; ?>" class="form-control">
                  </div>
                </td>
              </tr>
              <tr>
                <td>Total</td>
                <td>
                  <div class="col-sm-12">
                    <input type="text" name="total" value="<?php echo $record['total']; ?>" class="form-control">
                  </div>
                </td>


              </tr>

              <tr>
                <td>&nbsp;</td>
                <td>
                  <div class="col-sm-12">
                    <input type="submit" name="update" value="Update" class="btn btn-success">
                    <a href="?view=mitra" class="btn btn-default">Cancel</a>
                  </div>
                </td>
              </tr>
            </table>
          </form>
        </div><!-- /.box -->
      </div>
    </div>
  <?php
} else if ($_GET['act'] == 'onoff') {
  mysqli_query($conn, "UPDATE tbl_mitra SET status='Y' WHERE idMitra='$_GET[id]'");
  mysqli_query($conn, "UPDATE tbl_users SET status='mitra' WHERE email='$_GET[id_user]'");

  $users = mysqli_fetch_array(mysqli_query($conn, "SELECT nama FROM tbl_users WHERE email='$_GET[id_user]'"));

  $email = $_GET['id_user'];
  $judul = "SUKSES - Jadi mitra di Tiket24Jam";

  $isi = str_replace('{nama}', $users['nama'], $templateEmail);
  $isi = str_replace('{email}', $email, $isi);

  send_email_to_user($email, $judul, $isi);
  echo "<script>document.location='?view=mitra&sukses';</script>";
}
  ?>