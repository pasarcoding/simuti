<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
	if ($_GET['kelas'] == 'all') {
	    	$sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE statusSiswa='Aktif' ORDER BY nmSiswa ASC");
	    		$sqlGender = mysqli_query($conn, "SELECT jkSiswa, COUNT(*) as total FROM view_detil_siswa WHERE  statusSiswa='Aktif' GROUP BY jkSiswa");
        		$totalLakiLaki = 0;
        		$totalPerempuan = 0;
        
        		while ($gender = mysqli_fetch_array($sqlGender)) {
        			if ($gender['jkSiswa'] == 'L') {
        				$totalLakiLaki = $gender['total'];
        			} elseif ($gender['jkSiswa'] == 'P') {
        				$totalPerempuan = $gender['total'];
        			}
        		}
		}else{
		    	$sqlSiswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idKelas='$_GET[kelas]' AND statusSiswa='Aktif' ORDER BY nmSiswa ASC");
		    		$sqlGender = mysqli_query($conn, "SELECT jkSiswa, COUNT(*) as total FROM view_detil_siswa WHERE idKelas='$_GET[kelas]' AND statusSiswa='Aktif' GROUP BY jkSiswa");
        		$totalLakiLaki = 0;
        		$totalPerempuan = 0;
        
        		while ($gender = mysqli_fetch_array($sqlGender)) {
        			if ($gender['jkSiswa'] == 'L') {
        				$totalLakiLaki = $gender['total'];
        			} elseif ($gender['jkSiswa'] == 'P') {
        				$totalPerempuan = $gender['total'];
        			}
        		}
		}
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_siswa_" . str_replace(" ", "_", $kls['nmKelas']) . "_" . date('dmyHis') . ".xls");
?>
<table border="1">
<thead>
						<tr>
							<th>No.</th>
							<th>NIS</th>
							<th>NISN</th>
							<th>Nama Siswa</th>
							<th>Jenis Kelamin</th>
							<th>Kelas</th>
							<th>Nama Orang Tua</th>
							<th>Alamat</th>
							<th>RT/RW</th>
							<th>Kelurahan</th>
							<th>Kecamatan</th>
							<th>Kab/Kota</th>
							<th>Provinsi</th>
							<th>No. Hp</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$no = 1;
						while ($ds = mysqli_fetch_array($sqlSiswa)) {
						    if ($gender['jkSiswa'] == 'L') {
                                $totalLakiLaki = $gender['total'];
                            } elseif ($gender['jkSiswa'] == 'P') {
                                $totalPerempuan = $gender['total'];
                            }
							echo "<tr>
								<td>$no</td>
								<td>$ds[nisSiswa]</td>
								<td>$ds[nisnSiswa]</td>
								<td>$ds[nmSiswa]</td>
								<td>$ds[jkSiswa]</td>
								<td>$ds[nmKelas]</td>
								<td>$ds[nmOrtu]</td>
								<td>$ds[alamatOrtu]</td>
								<td>$ds[rt_rw]</td>
								<td>$ds[kelurahan]</td>
								<td>$ds[kecamatan]</td>
								<td>$ds[kab_kota]</td>
								<td>$ds[provinsi]</td>
								<td align='center'>'$ds[noHpOrtu]</td>
								</tr>";
							$no++;
						}
						?>
					</tbody>
		<tr>
					    <td colspan="4"><b>Jumlah Siswa Laki - Laki</b></td>
					     <td colspan="4" align="center"><b><?php echo $totalLakiLaki; ?></b></td>
					</tr>
					<tr>
					    <td colspan="4"><b>Jumlah Siswa Perempuan</b></td>
					     <td colspan="4"  align="center"><b><?php echo $totalPerempuan; ?></b></td>
					</tr>
</table>