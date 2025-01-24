<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/fungsi_seo.php";
if (isset($_SESSION[id])) {
	if ($_SESSION['level'] == 'admin') {
		$iden = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM users where username='$_SESSION[id]'"));
		$nama =  $iden['nama_lengkap'];
		$level = 'Administrator';
		$foto = 'dist/img/user.png';
	}

	$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas"));
	$dBayar = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM jenis_bayar WHERE idJenisBayar='$_GET[jenisBayar]'"));
?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>Cetak - Rekapitulasi Pembayaran Siswa</title>
		<link rel="stylesheet" href="bootstrap/css/printer.css">
	</head>

	<body>
		<?php
		$tgl1 = $_GET['tgl1'];
		$tgl2 = $_GET['tgl2'];
		?>
		<div class="col-xs-12">
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
			<h4 align="center">
				REKAPITULASI KONDISI KEUANGAN (SALDO KEUANGAN SEKOLAH)
			</h4>
			<div class="box box-info box-solid">
				<div class="box-header with-border">
					<!--<h3 class="box-title">Tanggal : <?php echo tgl_raport($tgl1); ?> s/d <?php echo tgl_raport($tgl2); ?></h3>-->
				</div><!-- /.box-header -->
				<div class="box-body">
					 h4><strong>Pemasukan</strong></h4>
			<table class="table table-bordered table-striped" id="example3">
					<thead>
						<tr>
							<th width="50">No.</th>
							<th width="150">Pos Bayar</th>
							<th width="150">Buku Bank</th>
							<th width="150">Buku Tunai</th>
							<th width="150">Jumlah</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$idBulan = $_GET['bulan'];
						$tahun = $_GET['tahun'];
						$results = []; // Initialize an array to store the results
						$no = 1;
						$saldo = 0;
						$totalmasuk = 0;
                        $totalTransfer = 0;
                        $totalTunai = 0;
						// Query Pembayaran
					  
                        $resultQuery = mysqli_query($conn, "SELECT 
                            tgl,
                            ket,
                            id,
                            SUM(totalTunai) AS totalTunai,
                            SUM(totalTransfer) AS totalTransfer,
                            nmPosBayar
                        FROM (
                            (SELECT 
                                tagihan_bulanan_bayar.tglBayar AS tgl,
                                tagihan_bulanan_bayar.ketBayar AS ket,
                                tagihan_bulanan_bayar.idTagihanBulananBayar AS id,
                                SUM(CASE WHEN tagihan_bulanan_bayar.caraBayar = 'Tunai' THEN tagihan_bulanan_bayar.jumlahBayar ELSE 0 END) AS totalTunai,
                                SUM(CASE WHEN tagihan_bulanan_bayar.caraBayar = 'Transfer' THEN tagihan_bulanan_bayar.jumlahBayar ELSE 0 END) AS totalTransfer,
                                pos_bayar.nmPosBayar
                            FROM
                                tagihan_bulanan_bayar
                                INNER JOIN tagihan_bulanan ON tagihan_bulanan_bayar.idTagihanBulanan = tagihan_bulanan.idTagihanBulanan
                                INNER JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
                                INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                            WHERE tagihan_bulanan.statusBayar != '0' AND MONTH(tagihan_bulanan_bayar.tglBayar) = '$idBulan' AND YEAR(tagihan_bulanan_bayar.tglBayar) = '$tahun'
                            GROUP BY jenis_bayar.idPosBayar)
                        
                            UNION ALL
                        
                            (SELECT 
                                tagihan_bebas_bayar.tglBayar AS tgl,
                                tagihan_bebas_bayar.ketBayar AS ket,
                                tagihan_bebas_bayar.idTagihanBebasBayar AS id,
                                SUM(CASE WHEN tagihan_bebas_bayar.caraBayar = 'Tunai' THEN tagihan_bebas_bayar.jumlahBayar ELSE 0 END) AS totalTunai,
                                SUM(CASE WHEN tagihan_bebas_bayar.caraBayar = 'Transfer' THEN tagihan_bebas_bayar.jumlahBayar ELSE 0 END) AS totalTransfer,
                                pos_bayar.nmPosBayar
                            FROM
                                tagihan_bebas_bayar
                                INNER JOIN tagihan_bebas ON tagihan_bebas_bayar.idTagihanBebas = tagihan_bebas.idTagihanBebas
                                INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
                                INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
                            WHERE tagihan_bebas.statusBayar <> '0' AND MONTH(tagihan_bebas_bayar.tglBayar) = '$idBulan' AND YEAR(tagihan_bebas_bayar.tglBayar) = '$tahun'
                            GROUP BY jenis_bayar.idPosBayar)
                        
                            UNION ALL
                        
                            (SELECT 
                                jurnal_umum.tgl AS tgl,
                                jurnal_umum.ket,
                                jurnal_umum.id,
                                SUM(CASE WHEN jurnal_umum.caraBayar = 'Tunai' THEN jurnal_umum.penerimaan ELSE 0 END) AS totalTunai,
                                SUM(CASE WHEN jurnal_umum.caraBayar = 'Transfer' THEN jurnal_umum.penerimaan ELSE 0 END) AS totalTransfer,
                                pos_bayar.nmPosBayar
                            FROM
                                jurnal_umum
                                INNER JOIN pos_bayar ON jurnal_umum.idPosBayar = pos_bayar.idPosBayar
                            WHERE MONTH(jurnal_umum.tgl) = '$idBulan' AND YEAR(jurnal_umum.tgl) = '$tahun'
                            GROUP BY jurnal_umum.idPosBayar)
                        ) AS combinedResults
                        GROUP BY nmPosBayar
                        ORDER BY nmPosBayar ASC");
                        
                        while ($row = mysqli_fetch_array($resultQuery)) {
                                    $results[] = [
                                        'tgl' => $row['tgl'],
                                        'ket' => $row['ket'],
                                        'id' => $row['id'],
                                        'totalTunai' => $row['totalTunai'],
                                        'totalTransfer' => $row['totalTransfer'],
                                        'nmPosBayar' => $row['nmPosBayar'],
                                    ];
                                }

						// Sort the array by date
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
                           
                            $totalTransfer = $result['totalTransfer'];
                               
                            $totalTunai = $result['totalTunai'];
                            
                            echo "<tr>
                                <td>$no</td>
                                <td>$result[nmPosBayar]</td>
                                <td>" . buatRp($totalTransfer) . "</td>
                                <td>" . buatRp($totalTunai) . "</td>
                                <td>" . buatRp($totalTransfer+$totalTunai) . "</td>
                            </tr>";
                           
                            $no++;
                            $totTransfer +=$totalTransfer;
                            $totTunai +=$totalTunai;
                            $totalmasuk += $totalTransfer+$totalTunai;
                        }

						?>

					</tbody>

					<tr>
						<td colspan="2" align='center'><b> Total Pemasukan</b></td>
						<td><b><?php echo buatRp($totTransfer); ?></b></td>
						<td><b><?php echo buatRp($totTunai); ?></b></td>
						<td><b><?php echo buatRp($totalmasuk); ?></b></td>
					</tr>

				</table>
				<h4><strong>Pengeluaran</strong></h4>
			    <table class="table table-bordered table-striped" id="example4">
					<thead>
						<tr>
							<th width="50">No.</th>
							<th width="150">Pos Bayar</th>
							<th width="150">Buku Bank</th>
							<th width="150">Buku Tunai</th>
							<th width="150">Jumlah</th>
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
						$totalkeluar = 0;

						// Query Jurnal Umum
						$query = mysqli_query($conn, "SELECT
                        SUM(CASE WHEN jurnal_umum.caraBayar = 'Tunai' THEN jurnal_umum.pengeluaran ELSE 0 END) AS totalTunai,
                        SUM(CASE WHEN jurnal_umum.caraBayar = 'Transfer' THEN jurnal_umum.pengeluaran ELSE 0 END) AS totalTransfer,						
						jurnal_umum.id,
						jurnal_umum.tgl,
						jurnal_umum.ket,
						pos_bayar.nmPosBayar
						FROM
						jurnal_umum
						LEFT JOIN pos_bayar ON jurnal_umum.idPosBayar = pos_bayar.idPosBayar
						WHERE MONTH(jurnal_umum.tgl) = '$idBulan' AND YEAR(jurnal_umum.tgl)='$tahun' GROUP BY jurnal_umum.idPosBayar ORDER BY jurnal_umum.idPosBayar ASC");
						while ($row = mysqli_fetch_array($query)) {
						        $results[] = [
                                        'tgl' => $row['tgl'],
                                        'ket' => $row['ket'],
                                        'id' => $row['id'],
                                        'totalTunai' => $row['totalTunai'],
                                        'totalTransfer' => $row['totalTransfer'],
                                        'nmPosBayar' => $row['nmPosBayar'],
                                    ];
						}

						// Sort the array by date
						// Sort the array by date
                        usort($results, function ($a, $b) {
                            return strtotime($a['tgl']) - strtotime($b['tgl']);
                        });
                        
                        // Loop through the sorted array to display the results
                        foreach ($results as $result) {
                            $totalkeluarTransfer = $result['totalTransfer'];
                               
                            $totalkeluarTunai = $result['totalTunai'];
                            echo "<tr>
                                <td>$no</td>
                                <td>$result[nmPosBayar]</td>
                                <td>" . buatRp($totalkeluarTransfer) . "</td>
                                <td>" . buatRp($totalkeluarTunai) . "</td>
                                <td>" . buatRp($totalkeluarTunai+$totalkeluarTransfer) . "</td>
                            </tr>";
                            
                             
                            $no++;
                             $totKeluarTransfer +=$totalkeluarTransfer;
                            $totKeluarTunai +=$totalkeluarTunai;
                            $totalkeluar += $totalkeluarTunai+$totalkeluarTransfer;
                        }

						?>

					</tbody>
	                <tr>
						<td colspan="2" align='center'><b> Total Pengeluaran</b></td>
						<td><b><?php echo buatRp($totKeluarTransfer); ?></b></td>
					    <td><b><?php echo buatRp($totKeluarTunai); ?></b></td>
					    <td><b><?php echo buatRp($totalkeluar); ?></b></td>
					</tr>

				
				</table>
				<table class="table table-bordered table-striped" id="example3">
				<thead>
				 <tr>
						<td  align='center'><b> Kondisi Keuangan Total Pemasukan - Total Pengeluaran</b></td>
						<td align='left'><b><?php echo buatRp($totalmasuk - $totalkeluar); ?></b></td>
					
					</tr>
				</thead>

				</table>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
		<br />
		<table width="100%">
			<tr>
				<td align="center"></td>
				<td align="center" width="400px">
					<?php echo $idt['kabupaten']; ?>, <?php echo tgl_raport(date("Y-m-d")); ?>
					<br />Bendahara,<br /><br /><br /><br />
					<b><u><?php echo $idt['nmBendahara']; ?></u><br /><?php echo $idt['nipBendahara']; ?></b>
				</td>
			</tr>
		</table>
	</body>
	<script>
		window.print()
	</script>

	</html>
<?php
} else {
	include "login.php";
}
?>