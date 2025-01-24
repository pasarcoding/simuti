<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
$jenis = $_GET['posBayar'];
$posBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * from pos_bayar where idPosBayar='$jenis'"));
$dBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT * from jenis_bayar where idPosBayar='$jenis'"));
$bulan = $_GET['bulan'] ?? ''; // Menggunakan null coalescing operator untuk menghindari error jika parameter tidak ada
$tahun_ini = date("Y");

// Mendefinisikan array dengan nama-nama bulan
$nama_bulan = [
	'01' => 'Januari',
	'02' => 'Februari',
	'03' => 'Maret',
	'04' => 'April',
	'05' => 'Mei',
	'06' => 'Juni',
	'07' => 'Juli',
	'08' => 'Agustus',
	'09' => 'September',
	'10' => 'Oktober',
	'11' => 'November',
	'12' => 'Desember',
];

// Mengambil nama bulan sesuai dengan nilai bulan dari parameter GET
$nama_bulan_terpilih = isset($nama_bulan[$bulan]) ? $nama_bulan[$bulan] : ''; // Memeriksa apakah nilai bulan valid

header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_buku_kas_umum_" . str_replace(" ", "_", $posBayar['nmPosBayar']) . "_" . date('dmyHis') . ".xls");
?>
<style>
	th {
		background-color: #4CAF50;
		/* Warna latar belakang hijau */
		color: white;
		/* Warna teks putih */
	}
</style>
<table border="1">
	<center>
		<h2>BUKU KAS UMUM</h2>
		<h3>Periode 1 s.d 30/31 <?php echo $nama_bulan_terpilih . ' ' . $_GET['tahun']; ?></h3>
	</center>
	<b>Pos Bayar : <?php echo $posBayar['nmPosBayar']; ?></b>
	<thead>
		<tr>
			<th>Tanggal</th>
			<th>Buku</th>
			<th>No Bukti</th>
			<th width="600">Uraian</th>
			<th width="140">Peneriamaan (Debet)</th>
			<th width="140">Pengeluaran (Kredit)</th>
			<th width="140">Saldo</th>
		</tr>
		<tr>
			<th>1</th>
			<th>2</th>
			<th>3</th>
			<th width="600">4</th>
			<th width="140">5</th>
			<th width="140">6</th>
			<th width="140">7</th>
		</tr>
	</thead>
	<tbody>
		<?php
		$caraBayar = 'Transfer';
		$idBulan = $_GET['bulan'];
		$tahun = $_GET['tahun'];

		$results = []; // Initialize an array to store the results
		$no = 1;
		$saldo = 0;
		$totalmasuk = 0;
		$totalkeluar = 0;

		// // Query Pembayaran
		// if ($dBayar['tipeBayar'] == 'bulanan') {
		// 	$sqlB1 = mysqli_query($conn, "SELECT
		// 							tagihan_bulanan.idTagihanBulanan,
		// 							tagihan_bulanan.idJenisBayar,
		// 							tagihan_bulanan.idSiswa,
		// 							tagihan_bulanan.idKelas,
		// 							tagihan_bulanan.idBulan,
		// 							tagihan_bulanan.user,
		// 							tagihan_bulanan_bayar.jumlahBayar,
		// 							tagihan_bulanan_bayar.caraBayar,

		// 							tagihan_bulanan_bayar.idTagihanBulananBayar as idTagihan,
				
		// 							DATE(tagihan_bulanan.tglBayar) as tglBayar,
		// 							tagihan_bulanan.tglUpdate,
		// 							tagihan_bulanan.statusBayar,
		// 							jenis_bayar.idTahunAjaran,
		// 							jenis_bayar.nmJenisBayar,
		// 							tahun_ajaran.nmTahunAjaran,
		// 							siswa.nisSiswa,
		// 							siswa.nmSiswa,
		// 							bulan.nmBulan AS ket,
		// 							bulan.urutan,
		// 							kelas_siswa.nmKelas
		// 							FROM
		// 							tagihan_bulanan
		// 						INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
		// 						INNER JOIN tagihan_bulanan_bayar ON tagihan_bulanan.idTagihanBulanan = tagihan_bulanan_bayar.idTagihanBulanan 
		// 						INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
		// 						INNER JOIN siswa ON tagihan_bulanan.idSiswa = siswa.idSiswa
		// 						INNER JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
		// 						INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
		// 						INNER JOIN kelas_siswa ON tagihan_bulanan.idKelas = kelas_siswa.idKelas 
		// 						WHERE jenis_bayar.idPosBayar='$jenis' AND tagihan_bulanan.statusBayar!='0'  AND MONTH(tagihan_bulanan_bayar.tglBayar) = '$idBulan' AND YEAR(tagihan_bulanan_bayar.tglBayar) = '$tahun' ORDER BY tagihan_bulanan.tglBayar ASC");
		// } else if ($dBayar['tipeBayar'] == 'bebas') {
		// 	$sqlB1 = mysqli_query($conn, "SELECT
		// 						tagihan_bebas_bayar.idTagihanBebasBayar as idTagihan,
		// 						tagihan_bebas_bayar.idTagihanBebas,
		// 						tagihan_bebas_bayar.tglBayar,
		// 						tagihan_bebas_bayar.jumlahBayar,
		// 						tagihan_bebas_bayar.caraBayar,

		// 						tagihan_bebas_bayar.ketBayar AS ket,
		// 						tagihan_bebas.idJenisBayar,
		// 						tagihan_bebas.idSiswa,
		// 						tagihan_bebas.idKelas,
		// 						tagihan_bebas.totalTagihan,
		// 						tagihan_bebas.statusBayar,
		// 						tagihan_bebas_bayar.user,
		// 						jenis_bayar.idTahunAjaran,
		// 						jenis_bayar.nmJenisBayar,
		// 						siswa.nisSiswa,
		// 						siswa.nmSiswa,
		// 						kelas_siswa.nmKelas
		// 					FROM
		// 						tagihan_bebas_bayar

		// 					INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
		// 					INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar

		// 					INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
		// 					INNER JOIN kelas_siswa ON tagihan_bebas.idKelas = kelas_siswa.idKelas
		// 					INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar

		// 					WHERE jenis_bayar.idPosBayar='$jenis' AND tagihan_bebas.statusBayar<>'0' AND MONTH(tagihan_bebas_bayar.tglBayar) = '$idBulan' AND YEAR(tagihan_bebas_bayar.tglBayar) = '$tahun' ORDER BY tagihan_bebas_bayar.tglBayar ASC");
		// }

		// while ($row = mysqli_fetch_array($sqlB1)) {
		// 	$results[] = [
		// 		'tgl' => $row['tglBayar'],
		// 		'ket' => '', // You need to define the correct value for 'ket'
		// 		'id' => $row['idTagihan'],
		// 		'jumlahBayar' => $row['jumlahBayar'],
		// 		'pengeluaran' => '0', // You need to define the correct value for 'pengeluaran'
		// 		'caraBayar' => $row['caraBayar'],
		// 		'nmSiswa' => $row['nmSiswa'],
		// 		'nmKelas' => $row['nmKelas'],
		// 		'nmJenisBayar' => $row['nmJenisBayar'],
		// 	];
		// }

		// Query Jurnal Umum
		$query = mysqli_query($conn, "SELECT
						penerimaan as jumlahBayar,
						caraBayar,
						pengeluaran,
						id,
						tgl,
						ket
						FROM
						jurnal_umum
						WHERE MONTH(tgl) = '$idBulan' AND YEAR(tgl)='$tahun' and idPosBayar='$jenis' ORDER BY tgl ASC");
		while ($row = mysqli_fetch_array($query)) {
			$results[] = [
				'tgl' => $row['tgl'],
				'ket' => $row['ket'],
				'id' => $row['id'],
				'jumlahBayar' => $row['jumlahBayar'],
				'caraBayar' => $row['caraBayar'],
				'pengeluaran' => $row['pengeluaran'],
				'nmSiswa' => '',
				'nmKelas' => '',
				'nmJenisBayar' => '',
			];
		}

		// Sort the array by date
		usort($results, function ($a, $b) {
			return strtotime($a['tgl']) - strtotime($b['tgl']);
		});

		// Loop through the sorted array to display the results
		foreach ($results as $result) {
			if ($result['pengeluaran'] == '0') {
				$ket = 'BK';
			} else {
				$ket = 'BD';
			}
			if ($result['caraBayar'] == 'Transfer') {
				$totalTransfer += $result['jumlahBayar'];
				$totalkeluarTransfer += $result['pengeluaran'];
				$cara = 'BANK';
			} else {
				$cara = 'TUNAI';
				$totalTunai += $result['jumlahBayar'];
				$totalkeluarTunai += $result['pengeluaran'];
			}
			$saldo += $result['jumlahBayar'] - $result['pengeluaran'];
			echo "<tr>
								<td>" . tgl_indo($result['tgl']) . "</td>
								<td>$cara</td>
								<td>$ket$result[id]</td>
								<td>$result[nmSiswa] $result[nmKelas] $result[nmJenisBayar] $result[ket]</td>
								<td>" . buatRp($result['jumlahBayar']) . "</td>
								<td>" . buatRp($result['pengeluaran']) . "</td>
								<td>" . buatRp($saldo) . "</td>
							</tr>";
			$no++;
			$totalmasuk += $result['jumlahBayar'];
			$totalkeluar += $result['pengeluaran'];
		}
		?>

	</tbody>
	<tr>
		<td colspan="4" align='right'><b>Sub Total Buku Bank</b></td>
		<td><b><?php echo buatRp($totalTransfer); ?></b></td>
		<td><b><?php echo buatRp($totalkeluarTransfer); ?></b></td>
		<td><b><?php echo buatRp($totalTransfer - $totalkeluarTransfer); ?></b></td>
	</tr>

	<tr>
		<td colspan="4" align='right'><b>Sub Total Buku Tunai</b></td>
		<td><b><?php echo buatRp($totalTunai); ?></b></td>
		<td><b><?php echo buatRp($totalkeluarTunai); ?></b></td>
		<td><b><?php echo buatRp($totalTunai - $totalkeluarTunai); ?></b></td>
	</tr>


	<tr>
		<td colspan="4" align='right'><b>Total Buku Kas Umum</b></td>
		<td><b><?php echo buatRp($totalmasuk); ?></b></td>
		<td><b><?php echo buatRp($totalkeluar); ?></b></td>
		<td><b><?php echo buatRp($totalmasuk - $totalkeluar); ?></b></td>

	</tr>
	<tr>
		<td colspan="4" align='right'><b>Saldo Akhir</b></td>
		<td colspan="3" align='right'><b><?php echo buatRp($totalmasuk - $totalkeluar); ?></b></td>

	</tr>
	</tbody>
</table>