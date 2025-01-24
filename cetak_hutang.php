<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
if (isset($_SESSION['id'])) {
    if ($_SESSION['level'] == 'admin') {
        $iden = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users WHERE username='$_SESSION[id]'"));
        $nama = $iden['nama_lengkap'];
        $level = 'Administrator';
        $foto = 'dist/img/user.png';
    }
    $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
    $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM jurnal_umum WHERE id='$_GET[id]'"));
    $sql = mysqli_query($conn,"SELECT * FROM hutangtoko where id_hutangtoko='$_GET[id]'; ");
$data = mysqli_fetch_array($sql);
$nominal = $data['nominal'];
$sisa = $data['sisa'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Cetak - Hutang</title>
 <link rel="stylesheet" href="bootstrap/css/printer.css">

</head>
<body>
    	<table width="100%">
				<tr>
					<td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
					<td valign="top">
						<h3 align="center" style="margin-bottom:8px ">
							<?php echo $idt['nmSekolah']; ?>
						</h3>
						<center><?php echo $idt['alamat']; ?></center>
						<center class="sub-title">Laporan Piutang</center>
					</td>
					<!--<td width="100px" align="right"><img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="60px"></td>-->
				</tr>
			</table>
  
<br>
					<table class="table ">
    <tr>
        <td width="200" rowspan="2">Nama Peminjam</td>
        <td  rowspan="2"><?php echo $data['ket']; ?></td>
        <td class="label">Nominal</td>
        <td><?php echo "Rp. " . number_format($nominal, 0, "", '.') . ",-"; ?></td>
    </tr>
    <tr>
        
        <td class="label" >Sisa</td>
        <td><?php echo "Rp. " . number_format($sisa, 0, "", '.') . ",-"; ?></td>
    </tr>
</table> 
<br>

					<table class="table table-bordered table-striped">
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
            $query = mysqli_query($conn, "SELECT * FROM angsurantoko WHERE id_hutangtoko='$_GET[id]' ORDER BY tanggal DESC");
            while ($data = mysqli_fetch_array($query)) {
                $angsuran = $data['angsuran'];
            ?>
                <tr>
                    <td><?php echo $no; ?></td>
                    <td><?php echo $data['tanggal']; ?></td>
                    <td><?php echo "Rp. " . number_format($angsuran, 0, "", '.') . ",-"; ?></td>
                    <td><?php echo $data['keterangan']; ?></td>
                </tr>
            <?php $no++; } ?>
        </tbody>
    </table>
<br />
		<table width="100%">
		    
			<tr>
				<td align="center">Mengetahui,<br>
				Kepala Sekolah,<br /><br /><br /><br />
				<b><u><?php echo $idt['nmKepsek']; ?></u><br /><?php echo $idt['nipKepsek']; ?></b>
				</td>
				<td align="center" >
					<?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
					<br />Bendahara,<br /><br /><br /><br />
					<b><u><?php echo $idt['nmBendahara']; ?></u><br /><?php echo $idt['nipBendahara']; ?></b>
				</td>
			</tr>
		</table>
    <script>
        window.print();
    </script>
</body>
</html>
<?php
} else {
    include "login.php";
}
?>
