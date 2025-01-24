<?php
include '../../config/koneksi.php';

$query = mysqli_query($conn,"SELECT max(id_angsurantoko) as maxKode FROM angsurantoko");
$data = mysqli_fetch_array($query);
$id_angsurantoko = $data['maxKode'];

$nourut = (int) substr($id_angsurantoko, 3, 3);
$nourut++;

$kode = "AP";
$kodeangsurantoko = $kode . sprintf("%03s", $nourut);
?>

<?php
$sql = mysqli_query($conn,"SELECT * FROM hutangtoko where id_hutangtoko='$_GET[id_hutangtoko]'; ");
$data = mysqli_fetch_array($sql);
$nominal = $data['nominal'];

$sql2 = mysqli_query($conn,"SELECT sum(angsuran) as jumlah FROM angsurantoko WHERE id_hutangtoko='$_GET[id_hutangtoko]';");
$data2 = mysqli_fetch_array($sql2);
$sisa = $data['nominal'] - $data2['jumlah'];
mysqli_query($conn,"UPDATE hutangtoko SET sisa='$sisa' where id_hutangtoko='$_GET[id_hutangtoko]';");
?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Data Angsuran
    <small>Dari</small>
  </h1>
</section>
<section class="content">
  <div class="row">
    <div class="col-xs-12">
      <div class="box box-danger">
        <div class="box-header with-border">
          <h3 class="box-title">Tambah Angsuran</h3>
             <a class='btn btn-primary btn-sm pull-right' title='Edit Data' href='cetak_hutang.php?id=<?= $_GET[id_hutangtoko] ?>' target='_blank'><span class='glyphicon glyphicon-print'></span> Cetak PDF</a>
        </div>
        <div class="box-body">
          <div class="row">
            <form method="POST">
              <div class="col-xs-2">
                <input type="hidden" name="id_angsurantoko" class="form-control" value="<?php echo $kodeangsurantoko; ?>">
                <input type="hidden" name="id_hutangtoko" class="form-control" value="<?php echo $_GET['id_hutangtoko'] ?>">
                <label>Masukkan Angsuran</label>
              </div>
              <div class="col-xs-3">
                <input type="text" name="angsuran" class="form-control" placeholder="Angsuran">
              </div>
               <div class="col-xs-3">
                <input type="date" name="tanggal" class="form-control" placeholder="tanggal">
              </div>
              <div class="col-xs-3">
                <input type="text" name="keterangan" class="form-control" placeholder="Keterangan">
              </div>
              <div class="col-xs-1">
                <button type="submit" name="save" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>

  <?php

  include '../../config/koneksi.php';
  if (isset($_POST['save'])) {

    $tgl = $_POST['tanggal'];

    $save = mysqli_query($conn,"INSERT INTO angsurantoko VALUES('$kodeangsurantoko','$_POST[id_hutangtoko]','$tgl','$_POST[angsuran]','$_POST[keterangan]')");



    if ($save) {
      echo "<script language=javascript>
        window.location='?view=detailtoko&id_hutangtoko=" . $_POST['id_hutangtoko'] . "';
        </script>";
      exit;
    } else {
      echo "gagal";
    }
  }
  ?>

  <div class="row">
    <div class="col-xs-12">
      <div class="box box-info">
        <div class="box-body">
          <div class="row">
            <div class="col-xs-1">
              <label>Hutang Ke</label>
            </div>
            <div class="col-xs-2">
              <input type="text" value="<?php echo $data['hutangke'] ?>" class="form-control" readonly>
            </div>
            <div class="col-xs-6">

            </div>
            <div class="col-xs-1">
              <label>Nominal</label>
            </div>
            <div class="col-xs-2">
              <input type="text" value="<?php echo "Rp. " . number_format($nominal, 0, "", '.') . ",-" ?>" name="notelp" class="form-control" readonly>
            </div>
          </div><br>
          <div class="row">
            <div class="col-xs-1">
              <label>Nama Peminjam</label>
            </div>
            <div class="col-xs-2">
              <input type="text" value="<?php echo $data['ket'] ?>" class="form-control" readonly>
            </div>
            <div class="col-xs-6">

            </div>
            <div class="col-xs-1">
              <label>Sisa</label>
            </div>

            <div class="col-xs-2">
              <input type="text" value="<?php echo "Rp. " . number_format($sisa, 0, "", '.') . ",-" ?>" name="notelp" class="form-control" readonly>
            </div>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>


  <div class="row">
    <div class="col-xs-12">
      <div class="box">
        <!-- /.box-header -->
        <div class="box-body">
          <table id="example2" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Angsuran</th>
                <th>Keterangan</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 1;
              $query = mysqli_query($conn,"SELECT * FROM angsurantoko WHERE id_hutangtoko='$_GET[id_hutangtoko]' order by tanggal desc");
              while ($data = mysqli_fetch_array($query)) {
                $angsuran = $data['angsuran'];
              ?>

                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $data['tanggal']; ?></td>
                  <td><?php echo "Rp. " . number_format($angsuran, 0, "", '.') . ",-" ?></td>
                    <td><?php echo $data['keterangan']; ?></td>
                </tr>
              <?php $no++;
              } ?>
            </tbody>
          </table>
        </div>
        <!-- /.box-body -->
      </div>

      <!-- /.box -->
    </div>
    <!-- /.col -->
  </div>

</section>