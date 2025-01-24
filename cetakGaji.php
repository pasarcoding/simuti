<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/fungsi_indotgl.php";
include "config/library.php";
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));
$ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where aktif='Y' "));
$s = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_guru 
                                              LEFT JOIN rb_jenis_ptk  ON rb_guru.id_jenis_ptk=rb_jenis_ptk.id_jenis_ptk

where rb_guru.id='$_GET[id]'"));

?>
<html>

<head>
  <title>DAFTAR HONORARIUM GURU</title>
  <link rel="stylesheet" href="bootstrap/css/printer.css">
  <link rel="shortcut icon" href="simpo.png">
  <style>
    body {
      width: 100mm;
      margin: 0 auto;
      /* Optional: Untuk membuat body berada di tengah halaman */
    }

    @media print {
      body {
        width: 100mm;
        margin: 0;
        /* Hapus margin agar sesuai dengan lebar halaman */
      }
    }
  </style>
</head>

<body onload="window.print()">
  <table width="100%">
    <tr>
      <td width="100px" align="left" valign="top" style="height: 60px;">
        <img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px" style="max-height: 100%; max-width: 100%;">
      </td>
      <td valign="top" style="height: 60px;">
        <h5 align="center" >
          DAFTAR RINCIAN HONORARIUM<br>
          GURU DAN KARYAWAN <br>
          <?php echo $idt['nmSekolah']; ?>
        </h5>
      </td>
    </tr>
  </table>
  <hr>
<table style='font-size:12px' width="100%">
    <tr>
        <td width='50%'>
            Nama : <?php echo $s['nama_guru']; ?>
        </td>
        <td width='50%' style='text-align:right;'>
            Jabatan : <?php echo $s['jenis_ptk']; ?>
        </td>
         </tr>
    <tr>
        <td width='50%'>
            Payroll : <?php echo $s['atasnama']; ?> | <?php echo $s['norek']; ?>
        </td>
   
        
        
    </tr>
    <tr>
       <td width='50%'>
            Bulan : <?php echo date('F', mktime(0, 0, 0, $_GET['bulan'], 1)); ?> <?= date('Y') ?>
        </td>
        <!-- You can add more columns as needed in the second row -->
    </tr>
</table>


  <br>
  <table style='font-size:13px' class="mt-5" width='95%'>
    <tr>
      <td width='10px'><b><u>A </u></b></td>
      <td width='10px'> . </td>
      <td> <b><u>PENERIMAAN</u></b></td>
    </tr>
  </table>
  <table width='100%' style='border-collapse: collapse; font-size:12px; margin-top: 10px;'>
    <?php
    // Ubah query jenis potongan untuk mengambil data sesuai urutan dari bayar_gaji
    $sqlSiswa = mysqli_query($conn, "SELECT *
FROM jenis_gaji WHERE jenis IN ('pokok', 'tunjangan')");
    $gajiItems = [];

    while ($r = mysqli_fetch_array($sqlSiswa)) {
      $bayarGaji = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM bayar_gaji 
    WHERE id_bulan='$_GET[bulan]' AND idTahunAjaran='$_GET[idTahunAjaran]' AND id_guru='$s[id]'"));
      $idGaji = $r['id'];
      $namaGaji = ($r['jenis'] == 'pokok') ? $r["nmGaji"]  : $r["nmGaji"];
     // $namaGaji = ($r['jenis'] == 'pokok') ? $r["nmGaji"] . ' (' . $bayarGaji["jumlah_jam"] . ' Jam)' : $r["nmGaji"];

      $gajiValue = ($r['jenis'] == 'pokok') ? $bayarGaji["gaji_pokok"] : $bayarGaji["id_jenis_tunjangan$idGaji"];
      if ($gajiValue != 0) {
        $gajiItems[] = [
          'nama' => $namaGaji,
          'nilai' => $gajiValue,
        ];
      }
      $totalGaji += $gajiValue;
    }

    // Menampilkan hasil dengan nomor baris yang disetel secara manual
    foreach ($gajiItems as $index => $item) {
      echo "<tr>
        <td>" . ($index + 1) . ". " . $item['nama'] . "</td>
        <td>:</td>
        <td  align='right'>" . buatRp($item['nilai']) . "</td>
    </tr>";
    }

    echo "
    <tr> 
        <td align='center'><b>Jumlah</b></td>
        <td>:</td>
        <td  align='right'>
            <b>" . buatRp($totalGaji) . "</b>
        </td>
    </tr>";
    ?>

  </table>
  <br>
  <table style='font-size:13px' class="mt-5" width='95%'>
    <tr>
      <td width='10px'><b><u>B </u></b></td>
      <td width='10px'> . </td>
      <td> <b><u>POTONGAN</u></b></td>
    </tr>
  </table>
  <table width='100%' style='border-collapse: collapse; font-size:12px; margin-top: 10px;'>
    <?php
    $sqlSiswa1 = mysqli_query($conn, "SELECT *
    FROM jenis_potongan");

    $potonganItems = [];
    $no = 1;
    while ($r = mysqli_fetch_array($sqlSiswa1)) {
      $bayarGaji = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM bayar_gaji 
        WHERE id_bulan='$_GET[bulan]' AND idTahunAjaran='$_GET[idTahunAjaran]' AND id_guru='$s[id]'"));
      $idPotongan = $r['id'];
      $potonganValue = $bayarGaji["id_jenis_potongan$idPotongan"];
      $namaPotongan = $r['nmPotongan'];

      if ($potonganValue != 0) {
        $potonganItems[] = [
          'nama' => $namaPotongan,
          'nilai' => $potonganValue,
        ];
      }

      $totalPotongan += $potonganValue;
      $no++;
    }

    // Menampilkan hasil dengan nomor baris yang disetel secara manual
    foreach ($potonganItems as $index => $item) {
      echo "<tr>
        <td>" . ($index + 1) . ". " . $item['nama'] . "</td>
        <td>:</td>
        <td  align='right'>" . buatRp($item['nilai']) . "</td>
    </tr>";
    }

    echo "
    <tr> 
        <td align='center'><b>Jumlah</b></td>
        <td>:</td>
        <td  align='right'>
            <b>" . buatRp($totalPotongan) . "</b>
        </td>
    </tr>
    <tr style='line-height: 4.3em;'>
        <td>
            <b>Jumlah yang diterima bersih</b>
        </td>
        <td>
            <b>:</b>
        </td>
        <td align='right'>
            <b>" . buatRp($totalGaji - $totalPotongan) . "</b>
        </td>
    </tr>";
    ?>


  </table>
  <table width='100%' style='font-size:12px; margin-top: 10px; float:right;'>
    <tr>
      <td align="left">
        <br />Kepala Sekolah,<br /><br /><br /><br />
        <b><u><?php echo $idt['nmKepsek']; ?></u></b><br>
        <?php echo $idt['nipKepsek']; ?>
      </td>
        <td align="right">
        <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date('Y-m-d')); ?>
        <br />Bendahara,<br /><br /><br /><br />
        <b><u><?php echo $idt['nmBendahara']; ?></u></b><br>
        <p><?php echo $idt['nipBendahara']; ?></p>
      </td>
    </tr>

  </table>
</body>

</html>