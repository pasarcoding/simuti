<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title"> Data Payment </h3>

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

              <th>Payment Order</th>
              <th>Waktu</th>
              <th>Total Payment</th>
              <th>Bukti Payment</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $tampil = mysqli_query($conn, "SELECT 
            paymentOrder,
            tglPayment,
            status,
            nominal,
            idPayment,
            idTagihan,
            foto,
            idSiswa

            FROM payment 
           
            where tipePayment='transfer-manual' and status='pending'");
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
              if ($r['status'] == 'pending') {
                $a = 'success';
                $icon = "fa-check";
                $btn = "btn-info";
                $alt = "Proses";
                $onoff = "<a class='btn $btn btn-xs' title='$alt' href='?view=payment&act=onoff&id=$r[idPayment]&siswa=$r[idSiswa]&a=$a'><span class='fa $icon'></span> Proses</a>";
              } else {
                $icon = "fa-check";
                $btn = "btn-success";
                $onoff = "<a class='btn $btn btn-xs' href='#'><span class='fa $icon'></span> Selesai </a> ";
              }
              echo "<tr><td>$no</td>
                              <td>$r[paymentOrder]</td>
                              <td>$r[tglPayment]</td>
                              <td>" . buatRp($r['nominal']) . "</td>
                              <td>
                              <a href='#'>
                              <img src='foto_bukti/" . $r[foto] . "' id='target'  class='img-thumbnail img-responsive' width='150' height='300'>
                            </a>
                            </td>
                              <td>$onoff</td>";
              echo "</tr>";
              $no++;
            }
            if (isset($_GET['hapus'])) {
              $query = mysqli_query($conn, "DELETE FROM pos_bayar where idPosBayar='$_GET[id]'");
              if ($query) {
                echo "<script>document.location='index-bendahara.php?view=posbayar&sukseshapus';</script>";
              } else {
                echo "<script>document.location='index-bendahara.php?view=posbayar&gagalhapus';</script>";
              }
            }

            ?>
          </tbody>
        </table>
      </div><!-- /.box-body -->
    </div><!-- /.box -->
  </div>
<?php
} elseif ($_GET[act] == 'onoff') {

  $qPayment = mysqli_query($conn, "SELECT * FROM payment WHERE idSiswa='$_GET[siswa]' AND idPayment='$_GET[id]'");
  while ($pyt = mysqli_fetch_array($qPayment)) {
    $query = mysqli_query($conn, "UPDATE payment SET status='success' where idPayment = '$_GET[id]'");

    if ($pyt['jenisTagihan'] == 'Bulanan') {
      mysqli_query($conn, "INSERT INTO tagihan_bulanan_bayar (idTagihanBulanan,tglBayar,jumlahBayar,caraBayar,user) VALUES ('$pyt[idTagihan]','$pyt[tglPayment]','$pyt[nominal]','Transfer','$_SESSION[namalengkap]') ");

      $sqlTotalBayar = mysqli_query($conn, "select sum(totalTagihan) as TotalTagihan from tagihan_bulanan WHERE idTagihanBulanan='$pyt[idTagihan]'");
      $totalDibayar = mysqli_fetch_array($sqlTotalBayar);
      if ($pyt['nominal'] < $totalDibayar['TotalTagihan']) {
        mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$pyt[tglPayment]',statusBayar='2',inv='$pyt[paymentOrder]',caraBayar='Transfer',user='$_SESSION[namalengkap]' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBulanan ='$pyt[idTagihan]'");
      } else {
        mysqli_query($conn, "UPDATE tagihan_bulanan SET tglBayar='$pyt[tglPayment]',statusBayar='1',inv='$pyt[paymentOrder]',caraBayar='Transfer',user='$_SESSION[namalengkap]' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBulanan ='$pyt[idTagihan]'");
      }
    } else {
      mysqli_query($conn, "INSERT INTO tagihan_bebas_bayar (idTagihanBebas,tglBayar,jumlahBayar,caraBayar,user) VALUES ('$pyt[idTagihan]','$pyt[tglPayment]','$pyt[nominal]','Transfer','$_SESSION[namalengkap]') ");

      $sqlTotalBayar = mysqli_query($conn, "select sum(totalTagihan) as TotalTagihan from tagihan_bebas WHERE idTagihanBebas='$pyt[idTagihan]'");
      $totalDibayar = mysqli_fetch_array($sqlTotalBayar);



      if ($pyt['nominal'] < $totalDibayar['TotalTagihan']) {
        mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='2' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBebas ='$pyt[idTagihan]'");
      } else {
        mysqli_query($conn, "UPDATE tagihan_bebas SET statusBayar='1' WHERE idSiswa='$pyt[idSiswa]' AND idTagihanBebas ='$pyt[idTagihan]'");
      }
    }
    if ($query) {
      echo "<script>document.location='index-bendahara.php?view=payment';</script>";
    } else {
      echo "<script>document.location='index-bendahara.php?view=payment';</script>";
    }
  }

?>


<?php
}
?>