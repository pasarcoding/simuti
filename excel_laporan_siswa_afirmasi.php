<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
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

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_siswa_afirmasi" . str_replace(" ", "_", $kls['nmKelas']) . "_" . date('dmyHis') . ".xls");
?>
<table border="1">
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