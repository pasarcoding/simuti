<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
if (isset($_SESSION[id])) {
	if ($_SESSION['level'] == 'admin') {
		$iden = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM users where username='$_SESSION[id]'"));
		$nama =  $iden['nama_lengkap'];
		$level = 'Administrator';
		$foto = 'dist/img/user.png';
	}

	$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas where npsn='10700295'"));
	$dBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM jenis_bayar WHERE idJenisBayar='$_GET[jenisBayar]'"));
	$sqlJenisBayar = mysqli_query($conn, "SELECT * FROM jenis_bayar  ORDER BY tipeBayar DESC");

	header("Content-type: application/vnd-ms-excel");
	header("Content-Disposition: attachment; filename=rekapitulasi_pemasukan_harian_" . str_replace(" ", "_", $kls['nmKelas']) . "_" . date('dmyHis') . ".xls");
?>

	<?php
	$tahun = $_GET['tahun'];
	$jenis = $_GET['jenisBayar'];
	//$tgl=$_GET['tgl'];
	$tgl1 = $_GET['tgl1'];
	$tgl2 = $_GET['tgl2'];
	?>

	<center>
		<h4>Rekapitulasi Pemasukan Perhari
	</center>
	<table border="1">
		<thead>
			<tr>

				<th>Tgl </th>
				<th>Nama </th>
				<th>Kelas</th>
				<th>Opsi Bayar</th>
				<th>Tipe </th>
				<th>Jenis </th>
				<th>Jumlah/Ket</th>
				<th>Petugas </th>
			</tr>
		</thead>
		<!-- <tr>

							<td colspan="6">Rincian Pemasukan</td>

						</tr> -->
		<tbody>

			<?php
			$no = 1;
			//tagihan Lainnya
			while ($dj = mysqli_fetch_array($sqlJenisBayar)) {
				if ($dj['tipeBayar'] == 'bebas') {
					$sqlB = mysqli_query($conn, "SELECT
	tagihan_bebas_bayar.idTagihanBebasBayar,
	tagihan_bebas_bayar.idTagihanBebas,
	tagihan_bebas_bayar.tglBayar,
	tagihan_bebas_bayar.jumlahBayar,
	tagihan_bebas_bayar.ketBayar,
	tagihan_bebas_bayar.caraBayar,
	tagihan_bebas_bayar.user,
	tagihan_bebas.idJenisBayar,
	tagihan_bebas.idSiswa,
	tagihan_bebas.idKelas,
	tagihan_bebas.totalTagihan,
	tagihan_bebas.statusBayar,
	siswa.nisSiswa,
siswa.nmSiswa,
kelas_siswa.nmKelas

	FROM
		tagihan_bebas_bayar
	INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
	INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
	INNER JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas

	WHERE tagihan_bebas.idJenisBayar='$dj[idJenisBayar]' AND tagihan_bebas.statusBayar<>'0' AND DATE(tagihan_bebas_bayar.tglBayar) BETWEEN '$tgl1' AND '$tgl2'");

					while ($dtb = mysqli_fetch_array($sqlB)) {
						if ($dtb['statusBayar'] > 0) {
							$stTagihanLainya = buatRp($dtb['jumlahBayar']) . "/" . $dtb['ketBayar'];
						} else {
							$stTagihanLainya = buatRp($dtb['jumlahBayar']);
						}
						echo "<tr >
	
	<td align='center'>$dtb[tglBayar]</td>
	<td align='center'>$dtb[nmSiswa]</td>
	<td align='center'>$dtb[nmKelas]</td>
	<td align='center'>$dtb[caraBayar]</td>
	<td align='center'>Bebas</td>
		<td align='left'>" . ucwords(strtolower($dj[nmJenisBayar])) . "</td>
		
	
		<td >$stTagihanLainya</td>
			<td >$dtb[user]</td>
	</tr>";
					}
				} else if ($dj['tipeBayar'] == 'bulanan') {

					//tagihan bulanan
					$sqlLap = mysqli_query($conn, "SELECT * FROM view_laporan_bayar_bulanan 
INNER JOIN siswa ON view_laporan_bayar_bulanan.idSiswa = siswa.idSiswa
	WHERE idJenisBayar='$dj[idJenisBayar]'   AND (DATE(tglBayar) BETWEEN '$tgl1' AND '$tgl2') AND statusBayar<>'0' ORDER BY urutan ASC");
					while ($rt = mysqli_fetch_array($sqlLap)) { //while per page

						if ($rt['statusBayar'] == '1') {
							$stTagihan = buatRp($rt['jumlahBayar']) . "/Lunas";
						} else {
							$stTagihan = buatRp($rt['jumlahBayar']);
						}
						$date = date_create($rt[tglBayar]);
						echo "<tr>
	
			<td align='center'>" . date_format($date, 'd-m-Y') . "</td>	
	<td align='center'>$rt[nmSiswa]</td>
	<td align='center'>$rt[nmKelas]</td>
	<td align='center'>$rt[caraBayar]</td>
	<td align='center'>Bulanan</td>
		<td>" . ucwords(strtolower($dj[nmJenisBayar])) . "/$rt[nmBulan]</td>

		
		
			
		<td >$stTagihan</td>
	<td >$rt[user]</td>
		</tr>";
					} //end while per page
				}
			}

			//total tagihan lainnya
			$totLainya = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totBayar
						FROM tagihan_bebas_bayar
						WHERE  DATE(tglBayar) BETWEEN '$tgl1' AND '$tgl2'"));
			//total tagihan bulanan
			$totBulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totBayar FROM tagihan_bulanan_bayar WHERE  (DATE(tglBayar) BETWEEN '$tgl1' AND '$tgl2')"));

			// Hitung Pembayaran Tabungan

			$query_hari = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE (DATE(tanggal) BETWEEN '$tgl1' AND '$tgl2')  ");
			$saldo_h = mysqli_fetch_array($query_hari);

			$saldo_hari = $saldo_h['jumlah_debit'] - $saldo_h['jumlah_kredit'];
			$query_haris = mysqli_query($conn, "SELECT SUM(pengeluaran) as keluar FROM jurnal_umum WHERE (DATE(tgl) BETWEEN '$tgl1' AND '$tgl2')  ");
			$saldo_hs = mysqli_fetch_array($query_haris);
			$kas = $saldo_hs['keluar'];
			$query_harisd = mysqli_query($conn, "SELECT SUM(penerimaan) as masuk FROM jurnal_umum WHERE (DATE(tgl) BETWEEN '$tgl1' AND '$tgl2')  ");
			$saldo_hsd = mysqli_fetch_array($query_harisd);
			$kasd = $saldo_hsd['masuk'];
			$tot = $totLainya['totBayar'] + $saldo_hari + $totBulanan['totBayar'] ;
			?>


			<?php
			$no = 0;
			$query = mysqli_query($conn, "SELECT * FROM transaksi 
							JOIN siswa ON transaksi.nisnSiswa=siswa.nisnSiswa 
							INNER JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas

							WHERE  (DATE(tanggal) BETWEEN '$tgl1' AND '$tgl2') order by id_transaksi asc ");
			while ($row = mysqli_fetch_array($query)) {
				if ($row['kredit'] == 0) {
					$statusBayar = "Setor";
					$onClick = "$row[debit]";
				} else {
					$statusBayar = "Tarik";
					$onClick = "$row[kredit]";
				}
				$no++;
			?><tr>
					<td align='center'><?php echo $row['tanggal']; ?></td>
					<td align='center'><?php echo $row['nmSiswa']; ?></td>
					<td align='center'><?php echo $row['nmKelas']; ?></td>
					<td align='center'><?php echo $row['caraBayar']; ?></td>

					<td align='center'>Tabungan</td>
					<td><?php echo $statusBayar; ?></td>
					<td><?php echo buatRp($onClick); ?><?php echo $row['keterangan']; ?></td>

					<td><?php echo $row['user']; ?></td>
				</tr>
			<?php } ?>
			<?php
			$no = 0;
			$querys = mysqli_query($conn, "SELECT * FROM jurnal_umum 
						JOIN pos_bayar ON jurnal_umum.idPosBayar=pos_bayar.idPosBayar 
						WHERE (DATE(jurnal_umum.tgl) BETWEEN '$tgl1' AND '$tgl2')  and jurnal_umum.pengeluaran='0' order by jurnal_umum.id asc ");
			while ($rows = mysqli_fetch_array($querys)) {

				$no++;
			?>
				<tr>
					<td align='center'><?php echo $rows['tgl']; ?></td>
					<td align='center'><?php echo $rows['nmPosBayar']; ?></td>
					<td></td>
					<td align='center'><?php echo $rows['caraBayar']; ?></td>
					<td>Jurnal Umum</td>
					<td>Penerimaan</td>

					<td><?php echo buatRp($rows[penerimaan]); ?>/<?php echo $rows['ket']; ?></td>
					<td><?php echo $rows['user']; ?></td>
				</tr>
			<?php } ?>
			<!--	<tr>

							<td colspan="6">Rincian Pengeluaran</td>

						</tr> -->
			<?php
			$no = 0;
			$querys = mysqli_query($conn, "SELECT * FROM jurnal_umum 
						JOIN pos_bayar ON jurnal_umum.idPosBayar=pos_bayar.idPosBayar 
						WHERE (DATE(jurnal_umum.tgl) BETWEEN '$tgl1' AND '$tgl2')  and jurnal_umum.penerimaan='0' order by jurnal_umum.id asc ");
			while ($rows = mysqli_fetch_array($querys)) {

				$no++;
			?>
				<tr>
					<td align='center'><?php echo $rows['tgl']; ?></td>

					<td align='center'><?php echo $rows['nmPosBayar']; ?></td>
					<td></td>
					<td align='center'><?php echo $rows['caraBayar']; ?></td>
					<td>Jurnal Umum</td>
					<td>Pengeluaran</td>
					<td><?php echo buatRp($rows[pengeluaran]); ?>/<?php echo $rows['ket']; ?></td>
					<td><?php echo $rows['user']; ?></td>

				</tr>
			<?php } ?>
			<tr>
				<td colspan="5" align='center'><b>Jumlah Pemasukan Hari Ini</b></td>
				<td></td>
				<td><b><?php echo buatRp($tot); ?></b></td>
			</tr>


		</tbody>
		<br />
		<table width="100%">
			<tr>
				<td align="center">
					<br />Kepala Sekolah,<br /><br /><br /><br />
					<b><u><?php echo $idt['nmKepsek']; ?></u></b>
				</td>
				<td align="center" width="400px">
					<?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
					<br />Bendahara Sekolah,<br /><br /><br /><br />
					<b><u><?php echo $idt['nmBendahara']; ?></u></b>

				</td>
			</tr>
		</table>
		<!--</body>
<script>
	window.print()
</script>
</html>-->
	<?php
} else {
	include "login.php";
}
	?>