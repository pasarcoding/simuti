<?php if ($_GET[act] == '') {


?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data RAB</h3>
        <a class='pull-right btn btn-primary btn-sm' href='?view=rab&act=tambah'>Tambahkan Data</a>

      </div><!-- /.box-header -->
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
        <table id="example1" class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>No</th>
              <th>Kode</th>
              <th>Pengajuan Untuk</th>
              <th>Alokasi</th>
              <th>Pagu Anggaran</th>
              <th>PIC</th>
              <th>Realisasi</th>
              <th>Sisa Anggaran</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($conn, "SELECT *
            FROM realisasi ORDER BY id ASC");



            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              echo "<tr><td>$no</td>
                              <td>$r[id]</td>
                              <td>$r[untuk]</td>
                              <td>$r[alokasi]</td>
                              <td>" . buatRp($r[pagu]) . "</td>
                              <td>$r[pic]</td>
                              <td>" . buatRp($r[realisasi]) . "</td>
                              <td>" . buatRp($r[pagu] - $r[realisasi]) . "</td>
                              <td><center>
                                
                                <a class='btn btn-danger btn-xs' title='Delete Data' href='?view=rab&hapus&id=$r[idPengeluaran]' onclick=\"return confirm('Apa anda yakin untuk hapus Data ini?')\"><span class='glyphicon glyphicon-remove'></span></a>
                              </center></td>";
              echo "</tr>";
              $no++;
            }
            if (isset($_GET['hapus'])) {
              $query = mysqli_query($conn, "DELETE FROM jenis_pengeluaran where idPengeluaran='$_GET[id]'");
              if ($query) {
                echo "<script>document.location='?view=rab&sukseshapus';</script>";
              } else {
                echo "<script>document.location='?view=rab&gagalhapus';</script>";
              }
            }

            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>


<?php
} elseif ($_GET[act] == 'tambah') {
  if (isset($_POST[tambah])) {
    $query = mysqli_query($conn, "INSERT INTO realisasi(id,untuk,alokasi,pic,pagu,tgl,realisasi,status) 
    VALUES('$_POST[id]','$_POST[untuk]','$_POST[alokasi]','$_POST[pic]','$_POST[pagu]','$_POST[tgl]','0','Belum')");
    $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
    $url = $idt['link_one_sender'];
    $api_key = $idt['token'];
    $number_send = $idt['wa'];
    $id = $idt['idnya'];
    $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT *
            FROM realisasi where id='$_POST[id]'"));
    $tampils = mysqli_fetch_array(mysqli_query($conn, "SELECT *
             FROM users where level='admin'"));
    //pesan whatsapp ortu 
    $no_hp = $tampils['no_telp'];
    $pesan = 'Assalamualaikum, Harap setujui RAB sebagai berikut : 

Nama : *' . $tampil['untuk'] . '*            
Alokasi: *' . $tampil['alokasi'] . '* 
Total: *' . str_replace(".", ",", buatRp($tampil['pagu'])) . '*

Terima kasih';


    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_MAXREDIRS, 10);
    curl_setopt($curl, CURLOPT_TIMEOUT, 0); // batas waktu response
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_POST, 1);

    $data_post = [
      'id_device' => $id,
      'api-key' => $api_key,
      'no_hp'   => $no_hp,
      'pesan'   => $pesan
    ];
    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data_post));
    curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($curl);
    curl_close($curl);
   

    if ($query) {
      echo "<script>document.location='?view=rab&sukses';</script>";
    } else {
      echo "<script>document.location='?view=rab&gagal';</script>";
    }
  }
?>
  <div class="col-md-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Tambah Realisasi</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <form method="POST" action="" class="form-horizontal">
          <?php
          $query = mysqli_query($koneksi, "SELECT max(id) as kodeTerbesar FROM realisasi");
          $data = mysqli_fetch_array($query);
          $kodeBarang = $data['kodeTerbesar'];

          // mengambil angka dari kode barang terbesar, menggunakan fungsi substr
          // dan diubah ke integer dengan (int)
          $urutan = (int) substr($kodeBarang, 3, 3);

          // bilangan yang diambil ini ditambah 1 untuk menentukan nomor urut berikutnya
          $urutan++;

          // membentuk kode barang baru
          // perintah sprintf("%03s", $urutan); berguna untuk membuat string menjadi 3 karakter
          // misalnya perintah sprintf("%03s", 15); maka akan menghasilkan '015'
          // angka yang diambil tadi digabungkan dengan kode huruf yang kita inginkan, misalnya BRG 
          $huruf = "RAB";
          $kodeBarang = $huruf . sprintf("%03s", $urutan);


          ?>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Kode</label>
            <div class="col-sm-4">
              <input type="text" class="form-control" name="id" value="" />

            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Pengajuan Untuk</label>
            <div class="col-sm-4">
              <input type="text" name="untuk" class="form-control" id="" required>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Alokasi</label>
            <div class="col-sm-4">
              <input type="text" name="alokasi" class="form-control" id="" required>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">PIC</label>
            <div class="col-sm-4">
              <select name="pic" class="form-control">
                <?php
                $sqk = mysqli_query($conn, "SELECT * FROM users ORDER BY username ASC");
                while ($k = mysqli_fetch_array($sqk)) {
                  echo "<option value=" . $k['nama_lengkap'] . ">" . $k['nama_lengkap'] . "</option>";
                }
                ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Jumlah Pengajuan</label>
            <div class="col-sm-4">
              <input type="text" name="pagu" class="form-control" id="" required>
            </div>
          </div>
          <div class="form-group">
            <label for="" class="col-sm-2 control-label">Tanggal Pengajuan</label>
            <div class="col-sm-4">
              <input type="text" name="tgl" class="form-control pull-right date-picker" value="">
            </div>
          </div>

          <div class="form-group">
            <label for="" class="col-sm-2 control-label"></label>
            <div class="col-sm-10">
              <input type="submit" name="tambah" value="Simpan" class="btn btn-success">
              <a href="?view=rab" class="btn btn-default">Cancel</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<?php
}
?>