<?php
include '../../config/koneksi.php';
include 'config/rupiah.php';
if ($_GET['aksi'] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-warning ">
      <div class="box-header with-border">
        <h3 class="box-title"> Nasabah</h3>
      </div><!-- /.box-header -->
      <div class="box-body">
        <div class="table-responsive">
          <table id="example1" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th width="50">No</th>
                <th>ID Nasabah</th>
                <th>Nisn</th>
                <th>Nama</th>
                <th>Kelas</th>
                <th>Orang Tua</th>
                <th>Saldo</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $no = 0;
              $query = mysqli_query($conn, "SELECT * FROM siswa  JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas ORDER BY idSiswa DESC");
              while ($row = mysqli_fetch_array($query)) {
                $no++;
              ?>
                <tr>
                  <td><?php echo $no; ?></td>
                  <td><?php echo $row['idSiswa']; ?></td>
                  <td><?php echo $row['nisnSiswa']; ?></td>
                  <td><?php echo $row['nmSiswa']; ?></td>
                  <td><?php echo $row['nmKelas']; ?></td>
                  <td><?php echo $row['nmOrtu']; ?></td>
                  <td>Rp. <?php echo rupiah($row['saldo']); ?></td>
                </tr>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>


      <?php

    } ?>
      </div>
    </div>