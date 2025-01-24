<?php

include 'config/rupiah.php';

// Get current date
$hari = date('d');
$bulan = date('m');

// Calculate saldo for the current month
$query_hari = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE DATE_FORMAT(tanggal, '%m') LIKE '%$bulan%'");
$saldo_h = mysqli_fetch_array($query_hari);
$saldo_hari = $saldo_h['jumlah_debit'] - $saldo_h['jumlah_kredit'];

// Get active school year
$ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran WHERE aktif='Y'"));
$idTahun = $ta['idTahunAjaran'];
$tahun = $ta['nmTahunAjaran'];

// Get school identity
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas WHERE npsn='10700295'"));

// Get student details
$dtsiswa = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE nisnSiswa='$_SESSION[ids]'"));
$apa = mysqli_query($conn, "SELECT * FROM siswa WHERE nisnSiswa='$_SESSION[ids]'");
$ra = mysqli_fetch_array($apa);

// Get school record
$edit = mysqli_query($conn, "SELECT * FROM identitas");
$record = mysqli_fetch_array($edit);
?>

<div class="col-xs-12">
    <?php if ($_SESSION['level'] == 'ketuakelas') { ?>
        <div class="alert alert-warning alert-dismissible text-center" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <p>Anda login sebagai <?php echo $ra['level']; ?>, silahkan kelola kas kelas anda dengan baik :) </p>
        </div>
    <?php } ?>

    <div class="box box-success box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <?php
                date_default_timezone_set('Asia/Jakarta');
                $namaHari = ["Ahad", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu", "Minggu"];
                $namaBulan = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                $sekarang = $namaHari[date('N')] . ", " . date('j') . " " . $namaBulan[(date('n') - 1)] . " " . date('Y');
                echo $sekarang . " | " . date('H:i:s') . " | ";
                ?>
                <script language="JavaScript">
                    var h = new Date().getHours();
                    document.write(h < 11 ? 'Selamat pagi, ' : h < 15 ? 'Selamat siang, ' : h < 19 ? 'Selamat sore, ' : 'Selamat malam, ');
                </script>
                <?php echo $ra['nmSiswa']; ?>
            </h3>
        </div>

        <section class="content">
            <div class="row">
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text dash-text">NISN</span>
                            <span class="info-box-number"><?php echo $ra['nisnSiswa']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text dash-text">Nama Siswa</span>
                            <span class="info-box-number"><?php echo $ra['nmSiswa']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-bank"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text dash-text">Kelas</span>
                            <span class="info-box-number"><?php echo $dtsiswa['nmKelas']; ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-blue"><i class="fa fa-usd"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text dash-text">Saldo Tabungan Anda</span>
                            <span class="info-box-number">Rp. <?php echo rupiah($ra['saldo']); ?></span>
                        </div>
                    </div>
                </div>

                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-credit-card"></i></span>
                        <div class="info-box-content">
                            <?php
                            $totDibayar = 0;
                            $totTagihan = 0;

                            $sqlJenisBayar = mysqli_query($conn, "SELECT jenis_bayar.*, pos_bayar.nmPosBayar FROM jenis_bayar INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar");
                            while ($djb = mysqli_fetch_array($sqlJenisBayar)) {
                                if ($djb['tipeBayar'] == 'bulanan') {
                                    // Calculate all monthly bills
                                    $tgbul = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bulanan.jumlahBayar) AS TotalSemuaTagihanBulanan FROM tagihan_bulanan INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar WHERE tagihan_bulanan.idJenisBayar='$djb[idJenisBayar]' AND tagihan_bulanan.idSiswa='$dtsiswa[idSiswa]'"));
                                    $semuaTagihan = $tgbul['TotalSemuaTagihanBulanan'];

                                    $dbayar = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bulanan.jumlahBayar) AS TotalPembayaranPerJenis FROM tagihan_bulanan WHERE tagihan_bulanan.idJenisBayar='$djb[idJenisBayar]' AND tagihan_bulanan.statusBayar='1' AND tagihan_bulanan.idSiswa='$dtsiswa[idSiswa]'"));
                                    $jBayar = $dbayar['TotalPembayaranPerJenis'];
                                    $tagihan = $semuaTagihan - $jBayar;
                                } else {
                                    // Calculate all free charges
                                    $tgb = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bebas.totalTagihan) As TotalSemuaTagihanBebas FROM tagihan_bebas INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar WHERE tagihan_bebas.idJenisBayar='$djb[idJenisBayar]' AND tagihan_bebas.idSiswa='$dtsiswa[idSiswa]'"));
                                    $semuaTagihan = $tgb['TotalSemuaTagihanBebas'];

                                    $dbayar = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bebas_bayar.jumlahBayar) AS TotalPembayaranPerJenis FROM tagihan_bebas_bayar INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas WHERE tagihan_bebas_bayar.idTagihanBebas='$tgb[idTagihanBebas]'"));
                                    $jBayar = $dbayar['TotalPembayaranPerJenis'];
                                    $tagihan = $semuaTagihan - $jBayar;
                                }
                                $totDibayar += $jBayar;
                                $totTagihan += $tagihan;
                            }
                            ?>
                            <span class="info-box-text dash-text">Total Tagihan</span>
                            <span class="info-box-number"><?php echo buatRp($totTagihan); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
