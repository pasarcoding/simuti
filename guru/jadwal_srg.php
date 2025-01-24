<?php if ($_GET[act] == '') { ?>
  <div class="col-xs-12">
    <div class="box box-primary">
      <div class="box-header with-border">
        <h3 class="box-title">Jadwal Seragam Guru dan Staff Karyawan TP. 2024/2025 </h3>
      </div><!-- /.box-header -->
      <table class="table table-striped">
     <!--   <form action="cetak_transaksi_guru.php" method="GET" target="output">
          <tbody>
            <tr>
              <input type="hidden" value="<?= $_SESSION['id'] ?>" name="guru">
              <td>
               < <utton type="submit" name="pdf" class="btn btn-warning btn-sm">
                  <i class="glyphicon glyphicon-print"></i> Cetak Laporan Saldo
                </button>
                <button type="submit" name="excel" class="btn btn-success btn-sm">
                  <i class="fa fa-file-excel-o"></i> Excel Laporan Saldo
                </button> 
              </td>
            </tr>
          </tbody>
        </form> -->
      </table>
      <div class="box-body">
        <div class="table-responsive">
            
  <!--      <p>
            <strong>Tata Tertib</strong>
            </p>
            <p>
            1. Semua guru, staff dan karyawan SDM3 harus datang di sekolah 10 menit sebelum jam pelajaran dimulai (Pkl. 06.15 WIB)
            </p>
          <p>
           2. Bagi guru, staff dan karyawan SDM3 yang datang terlambat wajib memberitahu kepala sekolah atau wakil kepala sekolah atau guru piket
            </p> -->
            
            
            <table id="" class="table table-bordered table-striped">
            <thead>
              <tr>
                <th rowspan="2">Minggu</th>
                <th rowspan="2">JK</th>
                <th colspan="5">Hari</th> </tr> 
                <tr>
            <th>Senin</th>
            <th>Selasa</th>
            <th>Rabu</th>
            <th>Kamis</th>
            <th>Jum'at</th>
        </tr> </thead>
        
        <tr>
            <td rowspan="2" >Minggu Ke 1</td>
            <td>Laki-laki</td>
            <td>Khaki</td>
            <td>Kemeja Navy</td>
            <td>Kemeja Putih</td>
             <td>Batik Bebas</td>
              <td>Kaos Abu</td>
                       </tr>
        
         <tr>
            
            <td>Perempuan</td>
            <td>Khaki + kerudung bebas</td>
            <td>Gamis navy + kerudung biru</td>
            <td>Putih + kerudung Hitam</td>
             <td>Batik bebas </td>
            <td>Kaos pink</td>
        </tr>
         
          <tr>
            <td rowspan="2" >Minggu Ke 2</td>
            <td>Laki-laki</td>
            <td>Hijau wardah (baru)</td>
            <td>Kemeja hitam</td>
            <td>Koko</td>
             <td>Batik Perpisahan</td>
              <td>HW</td>
                       </tr>
        
         <tr>
            
            <td>Perempuan</td>
            <td>Hijau wardah (baru)</td>
            <td>Gamis abu/hitam + kerudung bebas</td>
            <td>Putih + kerudung pink</td>
             <td>Kebaya ungu perpisahan</td>
            <td>HW / gamis nuansa HW</td>
        </tr>
        
          <tr>
            <td rowspan="2" >Minggu Ke 3</td>
            <td>Laki-laki</td>
            <td>Ungu kombinasi (baru)</td>
            <td>Kemeja Maroon</td>
            <td>Kemeja Putih</td>
             <td>Batik Sage</td>
              <td>Kaos HW</td>
                       </tr>
        
         <tr>
            
            <td>Perempuan</td>
            <td>Ungu kombinasi (baru)</td>
            <td>Gamis Maroon + kerudung bebas</td>
            <td>Putih + kerudung navy</td>
             <td>Batik Sage</td>
            <td>Kaos HW Kerudung Otak</td>
        </tr>
        
          <tr>
            <td rowspan="2" >Minggu Ke 4</td>
            <td>Laki-laki</td>
            <td>Hijau tosca  / abu</td>
            <td>Kemeja Biru</td>
            <td>Koko</td>
             <td>Pangsi</td>
              <td>HW</td>
                       </tr>
        
         <tr>
            
            <td>Perempuan</td>
            <td>Hijau tosca  / abu</td>
            <td>Gamis beige + kerudung bebas</td>
            <td>Putih + kerudung  coksu</td>
             <td>Kebaya Hitam / bebas </td>
            <td>HW / gamis nuansa HW</td>
        </tr>
        
           <tr>
            <td rowspan="2" >Minggu Ke 5</td>
            <td>Laki-laki</td>
            <td>hitam plus dasi</td>
            <td>Kemeja Bebas</td>
            <td>Kemeja Putih</td>
             <td>Batik Muktamar</td>
              <td>Kaos PGRI Navy</td>
                       </tr>
        
         <tr>
            
            <td>Perempuan</td>
            <td>Coklat Hitam</td>
            <td>Gamis pink  + kerudung bebas</td>
            <td>Putih + kerudung Abu</td>
             <td>Batik Muktamar</td>
            <td>Kaos PGRI Navy</td>
        </tr>
        </table>
        
        <!--       <th>Tanggal</th>
               <th>Keterangan</th>
              <th>Saldo</th> -->
              
           
        
          <!-- ?php
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
              <--?php 
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