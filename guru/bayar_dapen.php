<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Riwayat Pembayaran Dapen</h3>
      </div><!-- /.box-header -->
      <table class="table table-striped">
        <form action="cetak_transaksi_guru.php" method="GET" target="output">
          <tbody>
            <tr>
              <input type="hidden" value="<?= $_SESSION['id'] ?>" name="guru">
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
              </tr>
            </thead>
            <tbody>
              <?php
              // Query to get all transactions in descending order
              $tampil = mysqli_query($conn, "SELECT * FROM transaksi_guru 
    LEFT JOIN rb_guru ON transaksi_guru.id_guru = rb_guru.id where transaksi_guru.id_guru='$_SESSION[id]'
    ORDER BY transaksi_guru.id_transaksi ASC");

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
             
            </tr>";
                $no++;
              }

              // Delete operation

              ?>
            </tbody>
          </table>


        </div><!-- /.box-body -->
      </div><!-- /.box -->
    </div>

  <?php
}
  ?>