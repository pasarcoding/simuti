<?php
date_default_timezone_set('Asia/Jakarta');
$tahun = $ta['idTahunAjaran'];
$jenis = '';
$kelas = '';
include 'config/rupiah.php';
$tgl = date('Y-m-d');
$sqlJenisBayar = mysqli_query($conn, "SELECT * FROM jenis_bayar  ORDER BY tipeBayar DESC");
?>



<div class="col-xs-12">
	<div class="box box-info box-solid">
		<div class="box-header with-border">
			<h3 class="box-title"><span class="fa fa-file-text-o"></span> Laporan Pemasukan Perhari</h3>
		</div><!-- /.box-header -->
		<div class="box-body">
			<form method="GET" action="" class="form-horizontal">
				<input type="hidden" name="view" value="lappembayaranhari" />
				<table class="table table-striped">
					<thead>
						<tr>

							<th>Pilih Tanggal</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>


							<td>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
										<label for="">Dari Tanggal</label>
									</div>
									<?php
									if ($_GET['tgl1'] != '') {
										echo '<input type="text" name="tgl1" class="form-control pull-right date-picker" autocomplete="off" value="' . $_GET[tgl1] . '">';
									} else {
										echo '<input type="text" name="tgl1" class="form-control pull-right date-picker" autocomplete="off" >';
									}
									?>
								</div>
								<!-- /.input group -->
							</td>
							<td>
								<div class="input-group date">
									<div class="input-group-addon">
										<i class="fa fa-calendar"></i>
										<label for="">Sampai Tanggal</label>
									</div>
									<?php
									if ($_GET['tgl2'] != '') {
										echo '<input type="text" name="tgl2" class="form-control pull-right date-picker" autocomplete="off" value="' . $_GET[tgl2] . '">';
									} else {
										echo '<input type="text" name="tgl2" class="form-control pull-right date-picker" autocomplete="off" >';
									}
									?>
								</div>
								<!-- /.input group -->
							</td>

							<td width="100">
								<input type="submit" name="proses" value="Proses" class="btn btn-success pull-right">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
		</div><!-- /.box-body -->
		<?php
		if (isset($_GET['proses'])) {
			$tgl1 = $_GET['tgl1'];
			$tgl2 = $_GET['tgl2'];
		?>
			<div class="table-responsive">
				<table class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Tgl </th>
							<th>Nama </th>
							<th>Kelas</th>
							<th>Opsi Bayar</th>
							<!--	<th>Tipe </th> -->
							<th>Jenis </th>

							<th>Jumlah/Ket</th>
							<th>Petugas </th>

						</tr>
					</thead>
					<!--	<tr>
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

WHERE tagihan_bebas.idJenisBayar='$dj[idJenisBayar]' AND tagihan_bebas.statusBayar<>'0' AND DATE(tagihan_bebas_bayar.tglBayar) BETWEEN '$tgl1' AND '$tgl2'  ");

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
<!-- <td align='center'>Bebas</td> -->
<td align='left'>" . ucwords(strtolower($dj[nmJenisBayar])) . "</td>


<td >$stTagihanLainya</td>
<td >$dtb[user]</td>

</tr>";
								}
							} else if ($dj['tipeBayar'] == 'bulanan') {

								//tagihan bulanan
								$sqlLap = mysqli_query($conn, "SELECT * FROM view_laporan_bayar_bulanan 
INNER JOIN siswa ON view_laporan_bayar_bulanan.idSiswa = siswa.idSiswa
WHERE idJenisBayar='$dj[idJenisBayar]'  AND statusBayar<>'0'  AND DATE(tglBayar) BETWEEN '$tgl1' AND '$tgl2'  ORDER BY urutan ASC");
								while ($rt = mysqli_fetch_array($sqlLap)) { //while per page

									if ($rt['statusBayar'] == '1') {
										$stTagihan = buatRp($rt['diBayar']) . "";
									} else {
										$stTagihan = buatRp($rt['diBayar']);
									}
									$date = date_create($rt[tglBayar]);
									echo "<tr>

<td align='center'>" . date_format($date, 'd-m-Y') . "</td>
<td align='center'>$rt[nmSiswa]</td>
<td align='center'>$rt[nmKelas]</td>
<td align='center'>$rt[caraBayar]</td>
<!-- <td align='center'>Bulanan</td> -->
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
						$tot = $totLainya['totBayar'] + $saldo_hari  + $totBulanan['totBayar'] ;
						?>


						<?php
						$no = 0;
						$query = mysqli_query($conn, "SELECT * FROM transaksi 
						 
						JOIN siswa ON transaksi.nisnSiswa=siswa.nisnSiswa
						JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas
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

								<td align='center'>Tabungan</td>
								<td ><?php echo $statusBayar; ?></td>
								<td><?php echo buatRp($onClick); ?><?php echo $row['']; ?></td>

								<td ><?php echo $row['user']; ?></td>
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
								<td>Jurnal Umum/Penerimaan</td>
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
								<td>Jurnal Umum/Pengeluaran</td>
								<td><?php echo buatRp($rows[pengeluaran]); ?>/<?php echo $rows['ket']; ?></td>
								<td><?php echo $rows['user']; ?></td>

								</tr>
							<?php } ?>
							<tr>
								<td colspan="4" align='center'><b>Jumlah Pemasukan Hari ini</b></td>
								<td></td>
								<td><b><?php echo buatRp($tot); ?></b></td>
							</tr>
							<a href="./cetak_rekap_pemasukan_harian.php?&tgl1=<?php echo $tgl1; ?>&tgl2=<?php echo $tgl2; ?>" class="btn btn-danger pull-right" target="_blank"><span class="glyphicon glyphicon-print"></span> Cetak Laporan</a>

							<a href="./excel_rekapitulasi_pemasukan_harian.php?&tgl1=<?php echo $tgl1; ?>&tgl2=<?php echo $tgl2; ?>" class="btn btn-success pull-left" style="margin: 5px;"><span class="fa fa-file-excel-o"></span> Cetak Laporan xls</a>
						<?php
					}
						?>

					</tbody>

				</table>



			</div><!-- /.box-body -->
	</div><!-- /.box -->
</div><!-- /.box -->