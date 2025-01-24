<?php
include "../config/rupiah.php";
include 'config/rupiah.php';

$query_saldo = mysqli_query($conn,"SELECT SUM(sisa) as jumlah_debit FROM hutangtoko");
$row = mysqli_fetch_array($query_saldo);
$saldo_keseluruhan = $row['jumlah_debit'];

$query = mysqli_query($conn,"SELECT max(id_hutangtoko) as maxKode FROM hutangtoko");
$data = mysqli_fetch_array($query);
$id_hutangtoko = $data['maxKode'];

$nourut = (int) substr($id_hutangtoko, 3, 3);
$nourut++;

$kode = "HT";
$kodehutangtoko = $kode . sprintf("%03s", $nourut);

?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Data Hutang
    <small></small>
  </h1>
</section>

<section class="content">
  <div class="col-xs-12">
  
  </div>

  <?php

  include "../config/koneksi.php";

  if (isset($_POST['save'])) {

    $tgl = $_POST['tanggal'];

    $save = mysqli_query($conn,"INSERT INTO hutangtoko VALUES('$kodehutangtoko','$_POST[hutangke]','$tgl','$_POST[ket]','$_POST[nominal]','$_POST[nominal]')");

    if ($save) {
      echo "<script language=javascript>
        window.location='?view=hutangtoko';
        </script>";
      exit;
    } else {
      echo "gagal";
    }
  }
  ?>


  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <table id="example1" class="table table-responsive no-padding table-striped">
            <thead>
              <tr>
                <th>Kode</th>
                <th>Hutang Ke</th>
                <th>Tanggal</th>
                <th>Nama Peminjam</th>
                <th>Nominal Hutang</th>
                <th>Sisa Hutang</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $query = mysqli_query($conn,"SELECT * FROM hutangtoko order by tanggal desc");
              while ($data = mysqli_fetch_array($query)) {
                $nominal = $data['nominal'];
                $sisa = $data['sisa'];
              ?>

                <tr>
                  <td><?php echo $data['id_hutangtoko']; ?></td>
                  <td><?php echo $data['hutangke']; ?></td>
                  <td><?php echo $data['tanggal']; ?></td>
                  <td><?php echo $data['ket']; ?></td>
                  <td><?php echo "Rp. " . number_format($nominal, 0, "", '.') . ",-" ?></td>
                  <td><?php echo "Rp. " . number_format($sisa, 0, "", '.') . ",-" ?></td>
                  <td>
                    <a href="?view=detailtoko&id_hutangtoko=<?php echo $data['id_hutangtoko']; ?>"><button type="submit" class="btn btn-primary" title="Lihat Angsuran">Angsuran</button></a>
    <!--                <a href="?view=hutangtoko&delete=<?php echo $data['id_hutangtoko']; ?>" onclick="return confirm('Are you sure you want to delete this item?');">-->
    <!--    <button type="button" class="btn btn-danger" title="Delete">Delete</button>-->
    <!--</a>-->
                  </td>
                </tr>
              <?php } ?>
              <?php
if (isset($_GET['delete'])) {
    $id_hutangtoko = $_GET['delete'];
    
    // Perform the deletion
    $delete = mysqli_query($conn, "DELETE FROM hutangtoko WHERE id_hutangtoko='$id_hutangtoko'");
    
    if ($delete) {
        echo "<script language=javascript>
            alert('Data berhasil dihapus');
            window.location='?view=hutangtoko';
            </script>";
    } else {
        echo "Gagal menghapus data";
    }
}
?>
            </tbody>
            <tfoot>
              <tr>
                <th>Kode</th>
                <th>Hutang Ke</th>
                <th>Tanggal</th>
                <th>Nama Peminjam</th>
                <th>Nominal Hutang</th>
                <th>Sisa Hutang</th>
                <th>Aksi</th>
              </tr>
            </tfoot>
          </table>
        </div>
        <!-- /.box-body -->
      </div>

      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

</section>