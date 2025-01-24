<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/fungsi_indotgl.php";
$idTahunAjaran = $_GET[idTahunAjaran];
$jenis = $_GET[jenis];
$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas "));
 if(($idTahunAjaran != 'all') and ($jenis != 'all')) {
            $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.jenis='$_GET[jenis]' AND siswa_afirmasi.idTahunAjaran='$_GET[idTahunAjaran]'  ORDER BY siswa.idSiswa ASC");
        }else if (($idTahunAjaran != 'all') and ($jenis == 'all') ){
            $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.idTahunAjaran='$_GET[idTahunAjaran]'  ORDER BY siswa.idSiswa ASC");
        }else if (($idTahunAjaran == 'all') and ($jenis != 'all') ){   
             $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			WHERE siswa_afirmasi.jenis='$_GET[jenis]' ORDER BY siswa.idSiswa ASC");
        }else {
            $tampil = mysqli_query($conn, "SELECT * FROM siswa_afirmasi 
			INNER JOIN siswa ON siswa_afirmasi.idSiswa=siswa.idSiswa 
			INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas 
			ORDER BY siswa.idSiswa ASC");
        }   


?>
<!DOCTYPE html>
<html>

<head>
	<title>Cetak - Laporan Siswa Afirmasi</title>
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
			</td>
			<td width="100px" align="right"><img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="60px"></td>
		</tr>
	</table>
	<hr>
	<h3 align="center">LAPORAN DATA SISWA AFIRMASI</h3>
	<table border="1" class="table table-bordered table-striped">
			<thead>
							<tr>
								<th>No</th>
								<th>NISN</th>
								<th>Nama Siswa</th>
								<th>Jenis Kelamin</th>
								<th>Kelas</th>
								<th>Nama Ortu</th>
								<th>No.Hp Ortu</th>
								<th>Alamat</th>
								<th>Keterangan</th>

							</tr>
						</thead>
						<tbody>
							<?php
							$no = 1;
							while ($r = mysqli_fetch_array($tampil)) {
								echo "<tr><td>$no</td>
							
								<td>" . $r['nisnSiswa'] . "</td>
								<td>" . $r['nmSiswa'] . "</td>
								<td>" . $r['jkSiswa'] . "</td>
								<td>" . $r['nmKelas'] . "</td>
								<td>" . $r['nama_ayah'] . "</td>
								<td>" . $r['noHpOrtu'] . "</td>
								<td>" . $r['alamatOrtu'] . "</td>
								<td>" . $r['keterangan'] . "</td>																
								
								";
								echo "</tr>";
								$no++;
							}
							

							?>
						</tbody>
	</table>
	<br />
	<table width="100%">
		<tr>
			<td align="center"></td>
			<td align="center" width="400px">
				<?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
				<br />Kepala Tata Usaha,<br /><br /><br /><br />
				<b><u><?php echo $idt['nmKaTU']; ?></u><br /><?php echo $idt['nipKaTU']; ?></b>
			</td>
		</tr>
	</table>
</body>
<script>
	window.print()
</script>

</html>