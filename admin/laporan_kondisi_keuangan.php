<?php
$tahun = $ta['idTahunAjaran'];
$jenis = '';
$kelas = '';
?>
<div class="col-xs-12">
	<div class="box box-info box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Kondisi Keuangan</h3>
		</div><!-- /.box-header -->
			<div class="table-responsive">
			<form method="GET" action="" class="form-horizontal">
				<input type="hidden" name="view" value="rekapkondisikeuangan" />
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Tahun </th>
							<th>Bulan</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<select name="tahun" id="tahun" class="form-control">
									<?php
									// Loop dari tahun 2020 sampai 2030
									for ($tahun = 2020; $tahun <= 2030; $tahun++) {
										$selected = ($_GET['tahun'] == $tahun) ? ' selected="selected"' : "";
										echo "<option value='$tahun'$selected>$tahun</option>";
									}
									?>
								</select>
							</td>
						
							<td>
								<select name="bulan" id="bulan" class="form-control">
									<?php
									$months = [
										"01" => "Januari",
										"02" => "Februari",
										"03" => "Maret",
										"04" => "April",
										"05" => "Mei",
										"06" => "Juni",
										"07" => "Juli",
										"08" => "Agustus",
										"09" => "September",
										"10" => "Oktober",
										"11" => "November",
										"12" => "Desember"
									];

									foreach ($months as $value => $label) :
										$selected = ($_GET['bulan'] == $value) ? ' selected="selected"' : "";
									?>
										<option value="<?php echo $value; ?>" <?php echo $selected; ?>><?php echo $label; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td width="100">
								<input type="submit" name="tampil" value="Tampilkan" class="btn btn-success pull-right">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div><!-- /.box-body -->
			<?php
	if (isset($_GET['tampil'])) {

	?>
	<div class="box-body">
		<div class="table-responsive">
		    <h4><strong>Pemasukan</strong></h4>
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

			<hr>
			<a href="./cetak_rekap_kondisi_keuangan.php?tahun=<?= $_GET[tahun]?>&bulan=<?= $_GET[bulan]?>" class="btn btn-danger pull-right" target="_blank"><span class="glyphicon glyphicon-print"></span> Cetak Laporan</a>
		</div><!-- /.box-body -->	
	</div><!-- /.box-body -->
			<?php

	}
	?>
	</div><!-- /.box -->
</div>