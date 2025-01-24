<?php 
    include '../../config/koneksi.php'; 
    include '../../config/library.php'; 
    $idSiswa = $_POST['siswa'];
    $biayaAdmin = $_POST['biayaAdmin'];
?>


<table width="100%" cellpadding="2" class="table table-striped table-hover table-bordered">
  <thead>
    <tr>
      <th class="text-left">Nama Pembayaran</th>
      <th class="text-center">Nominal</th>
      <th class="text-center">Action</th>
    </tr>
  </thead>
  <?php
    $totalNominal = 0;
    $totalNominal += $biayaAdmin;
    $sqlPayment = mysqli_query($conn,"SELECT * FROM payment WHERE idSiswa='$idSiswa' AND paymentOrder='' ORDER BY idPayment ASC");
    if (mysqli_num_rows($sqlPayment) <> 0) :
  ?>
      <tbody>
      <?php
        while($py=mysqli_fetch_array($sqlPayment)) :
          $idTagihan = $py['idTagihan'];
          $totalNominal += $py['nominal'];
          $namaTagihan = '';
          if ($py['jenisTagihan'] == 'Bulanan'){
            $sqlbulanan = mysqli_fetch_array(mysqli_query($conn,"SELECT tagihan_bulanan.*,
                                                                  jenis_bayar.idPosBayar,
                                                                  jenis_bayar.nmJenisBayar,
                                                                  pos_bayar.nmPosBayar,
                                                                  tahun_ajaran.nmTahunAjaran,
                                                                  bulan.nmBulan, 
                                                                  bulan.urutan
                                                                  FROM tagihan_bulanan
                                                                  LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
                                                                  LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                                                                  LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                                                  LEFT JOIN bulan ON tagihan_bulanan.idBulan=bulan.idBulan
                                                                  WHERE tagihan_bulanan.idTagihanBulanan='$idTagihan'"));
             $pisah_TA = explode('/', $sqlbulanan['nmTahunAjaran']);
            if ($sqlbulanan['urutan'] <= 6) {
              $tahun = $pisah_TA[0];
            } else {
              $tahun = $pisah_TA[1];
            }
            $namaTagihan = $sqlbulanan['nmJenisBayar']." (".$sqlbulanan['nmBulan']." ".$tahun.") T.A ".$sqlbulanan['nmTahunAjaran'];
          }else{
            $sqlbebas = mysqli_fetch_array(mysqli_query($conn,"SELECT
                                    tagihan_bebas.*,
                                    jenis_bayar.idPosBayar,
                                    jenis_bayar.nmJenisBayar,
                                    pos_bayar.nmPosBayar,
                                    tahun_ajaran.nmTahunAjaran
                                  FROM
                                    tagihan_bebas
                                  INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                                  INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                                  INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
                                  WHERE tagihan_bebas.idTagihanBebas='$idTagihan'"));
            $namaTagihan = $sqlbebas['nmJenisBayar']." T.A ".$sqlbebas['nmTahunAjaran'];
          }
      ?>
          
          <tr>
            <td width="60%">
              <?= $namaTagihan ?>
            </td>
            <td width="30%" class="text-right">
              <?= buatRp($py['nominal']) ?>
            </td>
            <td width="5%" class="text-center">
           
              <a class="text-danger fa-lg" href="#" onclick="deleteCart(<?= $py['idPayment'] ?>)">
                <span class="fa fa-trash" data-toggle="tooltip" title="" data-original-title="Hapus"></span>
              </a> 
            </td>
          </tr>
        <?php endwhile; ?>
      <tr>
        <td>Biaya Admin</td>
        <td class="text-right"><?= buatRp($biayaAdmin) ?></td>
        <td></td>
      </tr>
    </tbody>
    <tfoot>
      <tr>
        <th>Total Pembayaran</th>
        <th class="text-right"><?= buatRp($totalNominal) ?></th>
        <td></td>
      </tr>
    </tfoot>
  <?php else : ?>
    <tr>
      <td colspan="3"><center>Maaf, data transaksi belum ada.</center></td>
    </tr>
  <?php endif ?>
</table>
