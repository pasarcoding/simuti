<?php
error_reporting(0);
session_start();
include 'config/koneksi.php';
include 'config/rupiah.php';
include 'config/library.php';
include "config/fungsi_indotgl.php";
include 'lib/function.php';
ob_start();

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas "));
$tahuns = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where idTahunAjaran='$_GET[tahun]' "));
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';

if (!empty($tahun)) {
    $tampil = mysqli_query($conn, "SELECT * FROM siswa WHERE statusSiswa='Inden' AND untukAjaran='$tahun' ORDER BY idSiswa DESC");
} else {
    $tampil = mysqli_query($conn, "SELECT * FROM siswa WHERE statusSiswa='Inden' ORDER BY idSiswa DESC");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Cetak Daftar Titipan</title>
    <link rel="stylesheet" href="bootstrap/css/printer.css">
    <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
</head>

<body>
    <table width="100%">
        <tr>
            <td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
            <td valign="top">
                <h3 align="center" style="margin-bottom:8px ">
                    DAFTAR TITIPAN PPDB <?php echo $idt['nmSekolah']; ?> <br>
                    TAHUN AJARAN <?= $tahuns['nmTahunAjaran'] ?><br>
                </h3>
            </td>
        </tr>
    </table>

    <br>
    <table id="example1" class="table table-bordered table-striped" border="1">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal Daftar Titip</th>
                <th>Nama Calon Siswa</th>
                <th>Tempat</th>
                <th>Tanggal Lahir</th>
                <th>Alamat</th>
                <th>No.Hp Ayah</th>
                <th>No.Hp Ibu</th>
                <th>Sumber Informasi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            while ($r = mysqli_fetch_array($tampil)) {
                echo "<tr>
                    <td>$no</td>
                    <td>" . tgl_miring($r['tanggal_pendaftaran']) . "</td>
                    <td>" . $r['nmSiswa'] . "</td>
                    <td>" . $r['tempat_lahir'] . "</td>
                    <td>" . tgl_miring($r['tglLahirSiswa']) . "</td>
                    <td>" . $r['alamatOrtu'] . "</td>
                    <td>" . $r['noHpOrtu'] . "</td>
                    <td>" . $r['noHpSis'] . "</td>	
                    <td>" . $r['sumberInformasi'] . "</td>																
                                        <td>" . $r['statusInden'] . "</td>																

                    
                </tr>";
                $no++;
            }

            if (isset($_GET[hapus])) {
                mysqli_query($conn, "DELETE FROM siswa where idSiswa='$_GET[id]'");
                echo "<script>document.location='index.php?view=daftar_titipan';</script>";
            }

            ?>
        </tbody>
    </table>

    <br>
    <table width="100%">
        <tr>
            <!-- Left side -->
            <td align="center" width="50%">
                Mengetahui,<br />
                Kepala Sekolah <br /><br /><br /><br /><br /><br />
                <b><?php echo $idt['nmKepsek']; ?></b>
            </td>

            <!-- Right side -->
            <td align="center" width="50%">
                <?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?><br />
                Petugas,
                <br /><br /><br /><br /><br /><br />
                <b><?php echo $_SESSION['namalengkap']; ?></b>
            </td>
        </tr>
    </table>
</body>

<script>
    window.print()
</script>

</html>