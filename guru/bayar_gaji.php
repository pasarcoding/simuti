<?php
$uri = str_replace('&id=' . $_GET['id'], NULL, $_SERVER['REQUEST_URI']);

//tahun ajaran
$t = mysqli_query($conn, "SELECT idTahunAjaran as ta FROM tahun_ajaran WHERE aktif = 'Y'");
$ta = mysqli_fetch_array($t);
$thn_ajar = $ta['ta'];

$page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);

$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$id = $idt['idnya'];
$status = $idt['statusWa'];
//$headers = array();
//$headers[] = $token_send;
//$headers[] = 'Content-Type: application/x-www-form-urlencoded';
?>

<?php
if ($_GET['act'] == '') { ?>
	<div class="col-xs-12">
		<div class="box box-info box-solid">
			<div class="box-header with-border">
				<h3 class="box-title">Pilih Tahun dan Bulan</h3>
			</div><!-- /.box-header -->
			<div class="box-body">
				<form method="GET" action="" class="form-horizontal">
					<input type="hidden" name="view" value="gaji-saya">
					<div class="form-group">
						<div class="col-sm-3">
							<select name="idTahunAjaran" class="form-control">
								<?php
								$sqltahun = mysqli_query($conn, "SELECT * FROM tahun_ajaran ORDER BY idTahunAjaran DESC");
								while ($t = mysqli_fetch_array($sqltahun)) {
									$selected = ($t['idTahunAjaran'] == $thn_ajar) ? ' selected="selected"' : "";
									echo '<option value="' . $t['idTahunAjaran'] . '" ' . $selected . '>' . $t['nmTahunAjaran'] . '</option>';
								}
								?>
							</select>
						</div>
						<div class="col-sm-3">
							<select name="bulan" data-live-search="true" class="form-control selectpicker">
								<option value="">- Pilih Bulan -</option>
								<?php
								$sqlSiswa = mysqli_query($conn, "SELECT * FROM bulan order by urutan");
								while ($s = mysqli_fetch_array($sqlSiswa)) {
									echo "<option value='$s[idBulan]'>$s[nmBulan] </option>";
								}
								?>
							</select>
						</div>
					
						<div class="col-sm-3">
							<input type="submit" name="cari" value="Lihat Riwayat Gaji" class="btn btn-success">

						</div>
					</div>
				</form>
			</div><!-- /.box-body -->
		</div>
	</div>
	<?php if (isset($_GET['cari'])) {
	?>
		<div class="col-xs-12">
			<div class="box box-warning box-solid">
				<div class="box-header with-border">
					<h3 class="box-title">Pembayaran Gaji Bulan <?php echo date('F', mktime(0, 0, 0, $_GET['bulan'], 1)); ?></h3>
				</div><!-- /.box-header -->
				<div class="table-responsive">
					<!-- Your HTML code -->
					<form method="POST" action="admin/simpan_bayar_gaji.php">
						<table class="table table-bordered table-striped">
							<thead>
								<tr>
									<th width="300">Nama Guru</th>
									<th width='150'>Jenis PTK</th>
								</tr>
							</thead>
							<tbody>
								<?php
								$sqlSiswa1 = mysqli_query($conn, "SELECT * FROM rb_guru 
								INNER JOIN rb_jenis_ptk ON rb_guru.id_jenis_ptk=rb_jenis_ptk.id_jenis_ptk
								LEFT JOIN kelas_siswa ON rb_guru.idKelas=kelas_siswa.idKelas where rb_guru.id='$_SESSION[id]'
								");
								$no = 1;
								while ($r = mysqli_fetch_array($sqlSiswa1)) {
									$bayarGaji = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM bayar_gaji 
									WHERE id_bulan='$_GET[bulan]' AND idTahunAjaran='$_GET[idTahunAjaran]' AND id_guru='$r[id]'"));
									$gj = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM gaji_pokok 
									INNER JOIN jenis_gaji ON gaji_pokok.id_jenis=jenis_gaji.id
									WHERE jenis_gaji.jenis='pokok' and gaji_pokok.id_bulan='$_GET[bulan]'"));
									echo "<tr class='header1 expand'>
											<td colspan=''><span class='btn btn-danger btn-xs sign' style='margin-right:30px; '></span> <b> $r[nama_guru] </b></td>
											<td rowspan=''>$r[jenis_ptk] $r[nmKelas]</td>
											<input type='hidden' class='form-control' name='jam_$no' value='{$r['jam']}' readonly>
											<input type='hidden' name='gaji_pokok' value='" . $gj['nominal'] . "'>
										</tr>
										<tr >
											<th width='300' >A. PENERIMAAN</th>
											<th width='150'>Jumlah</th>
										</tr>
										<tr>
											<td >Honor / Gaji ($r[jam] jam)</td>
											<td colspan='2'><input type='text' class='form-control' value='" . $gj['nominal'] * $r['jam'] . "' readonly/></td>
										</tr>";
									// Display jenis gaji values in a separate form
									$jenisGajiQuery = mysqli_query($conn, "SELECT * FROM jenis_gaji WHERE jenis='tunjangan'");
									while ($jenisGaji = mysqli_fetch_array($jenisGajiQuery)) {
										$queryTMT = mysqli_query($conn, "SELECT
												tmt_pengangkatan,
												CASE
													WHEN DATEDIFF(NOW(), tmt_pengangkatan) > 730 THEN 200000
													ELSE 0
												END AS nilai,
												CASE
													WHEN DATEDIFF(NOW(), tmt_pengangkatan) > 730 THEN 100000
													ELSE 0
												END AS nilai_dapen
											FROM
												rb_guru
											WHERE
												id='$r[id]'");
										$tmtData = mysqli_fetch_array($queryTMT);
										// Mengakses nilai tmt_pengangkatan dan nilai dari hasil query
										$mamin = $tmtData['nilai'];
										$dapen = $tmtData['nilai_dapen'];
										$idJenis = $jenisGaji['id'];
										if ($idJenis != 1) {
											$idJenisTunjangan = "id_jenis_tunjangan$idJenis";
											$nmGaji = $jenisGaji['nmGaji'];
											$id_status_pendidikan = $r['id_status_pendidikan'];
											$idJabatan = $r['id_jenis_ptk'];
											// Inisialisasi nilai default untuk $tunjanganValue, $alt, dan $access
											$tunjanganValue = $bayarGaji["id_jenis_tunjangan$idJenis"];
											$alt = "";
											$access = "";
											if (stripos($nmGaji, 'transport') !== false) {

    											$nip = $r['id'];
    										     $hari_kerja = $r['hari_kerja'] * 25000;

    										
    											if($idJabatan !='6'){
    											$tunjanganValue =$hari_kerja;
												$access = "readonly";

    											}else{
    											$tunjanganValue ="";
												$access = "";

    											}
											} else if (stripos($nmGaji, 'mamin') !== false) {
											   if($tunjanganValue == 0){
    												$tunjanganValue = $mamin;
    												$access = "";
											    }else{
											    	$tunjanganValue = $bayarGaji["id_jenis_tunjangan$idJenis"];
                                                	$access = "readonly";
											    }
											} else if (stripos($nmGaji, 'dapen') !== false) {
											    if($tunjanganValue == 0){
    												$tunjanganValue = $dapen;
												    $access = "readonly";
											    }else{
											    	$tunjanganValue = $bayarGaji["id_jenis_tunjangan$idJenis"];
                                                	$access = "readonly";
											    }
												
											} else if (stripos($nmGaji, 'pendidikan') !== false) {
											     $tmt_pengangkatan = new DateTime($r['tmt_pengangkatan']);
                                                    $currentDate = new DateTime();
                                                    
                                                    // Calculate the difference in years
                                                    $yearsDifference = $tmt_pengangkatan->diff($currentDate)->y;
                                                    
                                                    // Check if the difference is more than 2 years
                                                    if ($yearsDifference > 2) {
                                                      switch ($id_status_pendidikan) {
        													case 'SMA':
        														$access = "readonly";
        														break;
        													case 'S1':
        														$tunjanganValue = 150000;
        														$access = "readonly";
        														break;
        													case 'S2':
        														$tunjanganValue = 300000;
        														$access = "readonly";
        														break;
        													case 'D3':
        														$tunjanganValue = 100000;
        														$access = "readonly";
        														break;
        													case 'S3':
        														// Tidak perlu mengubah nilai default jika S3
        														break;
        												}
                                                    } else {
                                                        // Do something else if the difference is not more than 2 years
													    $tunjanganValue ="0";
													    $access = "";
                                                    }
												
											} else if (stripos($nmGaji, 'jabatan') !== false) {
												$queryJenisPtk = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_jenis_ptk WHERE id_jenis_ptk = '$r[id_jenis_ptk]'"));
												if ($tunjanganValue != 0) {
													$tunjanganValue = $bayarGaji["id_jenis_tunjangan$idJenis"];
													$access = $queryJenisPtk['akses'];
												} else {
												    $tmt_pengangkatan = new DateTime($r['tmt_pengangkatan']);
                                                    $currentDate = new DateTime();
                                                    
                                                    // Calculate the difference in years
                                                    $yearsDifference = $tmt_pengangkatan->diff($currentDate)->y;
                                                    
                                                    // Check if the difference is more than 2 years
                                                    if ($yearsDifference > 2) {
                                                        $access = $queryJenisPtk['akses'];                                                       
													    $tunjanganValue = $queryJenisPtk['keterangan'];
                                                    } else {
                                                        // Do something else if the difference is not more than 2 years
													    $tunjanganValue ="0";
													    $access = "";
                                                    }
												}
												
												$alt = $queryJenisPtk['warna'];
											}
											echo "<tr>
														<td style='background-color:$alt'>$nmGaji </td>
														<td colspan='2'><input type='number' class='form-control' name='gaji_{$idJenis}_$no' value='$tunjanganValue' $access></td>
													</tr>";
										}
										$totalpenerimaan += $tunjanganValue;
									}
									$tot = 0; // Initialize $tot to zero
									// Check if both gaji_pokok and jumlah_jam are not empty or not equal to zero
									if (!empty($bayarGaji['gaji_pokok']) && !empty($bayarGaji['jumlah_jam']) && $bayarGaji['gaji_pokok'] != 0 && $bayarGaji['jumlah_jam'] != 0) {
										$tot = $bayarGaji['total_gaji'];
									} else {
										// Data is not available, display a message or perform alternative actions
										$tot = 0;
									}
									echo "
											<tr>
												<th width='300'>Total Penerimaan</th>
												<th colspan='2' id='totalPenerimaan'>" . buatRp($tot) . "</th>
											</tr>
											<tr>
												<th width='300'></th>
												<th colspan='2'></th>
											</tr>
											
												<tr>
													<th >B. POTONGAN</th>
													<th colspan='2'>Jumlah</th>
												</tr>";
												
												
 
									// Add jenis potongan values in a separate form
									$jenisPotonganQuery = mysqli_query($conn, "SELECT * FROM jenis_potongan");
									echo "";
									while ($jenisPotongan = mysqli_fetch_array($jenisPotonganQuery)) {
										$idPotongan = $jenisPotongan['id'];
										$nmPotongan = $jenisPotongan['nmPotongan'];
										$jumlahPotongan = $jenisPotongan['jumlah'];

										if (stripos($nmPotongan, 'cabang') !== false) {
										    	if($idJabatan !='6'){
    											$potonganValue = 10000;
										    	$alt = "readonly";

    											}else{
    											$potonganValue ="";
												$access = "";

    											}
										
										} elseif (stripos($nmPotongan, 'infaq') !== false) {
											$potonganValue = $bayarGaji["id_jenis_potongan$idPotongan"];
											$alt = "readonly";
										} elseif (stripos($nmPotongan, 'pensiun') !== false) {
										             $tmt_pengangkatan = new DateTime($r['tmt_pengangkatan']);
                                                    $currentDate = new DateTime();
                                                    
                                                    // Calculate the difference in years
                                                    $yearsDifference = $tmt_pengangkatan->diff($currentDate)->y;
                                                    
                                                    // Check if the difference is more than 2 years
                                                    if ($yearsDifference > 2) {
            											$alt = "readonly";
            											$potonganValue = $jumlahPotongan;
                                                    }else if($jumlahPotongan !== 0){
                                                        $potonganValue = $bayarGaji["id_jenis_potongan$idPotongan"];
                                                	    $alt = "";
                                                    
                                                    } else {
                                                        // Do something else if the difference is not more than 2 years
        											$potonganValue = "0";
        											$alt = "";
                                             }
										} elseif (stripos($nmPotongan, 'hadiran') !== false) {
											$tahun = date('Y');
											$bulan_get = $_GET['bulan'];
											$bulan_formatted = ($bulan_get > 9) ? $bulan_get : sprintf("%02d", $bulan_get);
											$nip = $r['id'];
											$query_pegawai = mysqli_query($conn, "SELECT *  FROM rb_absensi_guru 
											WHERE MONTH(tanggal) = $bulan_formatted AND YEAR(tanggal) = $tahun 
											AND nip='$nip' AND kode_kehadiran IN ('A','S','I')");
											$num_pegawai = mysqli_num_rows($query_pegawai);

											$potonganValue = $jumlahPotongan * $num_pegawai;
											$alt = "";
										} elseif ($jumlahPotongan !== 0) {
										    if($tunjanganValue == 0){
										            $potonganValue = $jumlahPotongan;
    												$alt = "";
											    }else{
											    	$potonganValue = $bayarGaji["id_jenis_potongan$idPotongan"];
                                                	$alt = "";
											    }
											
										} else {
											$potonganValue = $bayarGaji["id_jenis_potongan$idPotongan"];
											$alt = "";
										}
										echo "
											<tr>
												<td >{$jenisPotongan['nmPotongan']}</td>
												<td colspan='2' ><input type='number' class='form-control' name='potongan_{$idPotongan}_$no' value='$potonganValue' $alt></td>
											</tr>";
										$totalpotongan += $potonganValue;
									}

									if (!empty($bayarGaji['gaji_pokok']) && !empty($bayarGaji['jumlah_jam']) && $bayarGaji['gaji_pokok'] != 0 && $bayarGaji['jumlah_jam'] != 0) {
										$totPotongan = $bayarGaji['total_potongan'];
									} else {
										// Data is not available, display a message or perform alternative actions
										$totPotongan = 0;
									}
									echo "
									
											<tr>
												<th width='300'>Total Potongan</th>
												<th colspan='2'>" . buatRp($totPotongan) . "</th>
											</tr>
										
										<tr>
											<th width='300'></th>
											<th colspan='2'></th>
										</tr>
										<tr>
										<th>Jumlah yang diterima bersih</th>
										
										<th colspan='2'>" . buatRp($bayarGaji['total']) . "</th>
										
												<input type='hidden' name='id_guru_$no' value='$r[id]'>
												<input type='hidden' name='id_bulan' value='$_GET[bulan]'>
												<input type='hidden' name='idTahunAjaran' value='$_GET[idTahunAjaran]'>
											
									</tr></tbody>";
									echo "<tr>
										<td colspan='3'>
											<center>
												<a type='button' class='btn btn-primary btn-sm' target='_blank' href='cetakGaji.php?id=$r[id]&bulan=$_GET[bulan]&idTahunAjaran=$_GET[idTahunAjaran]'><i class='fa fa-print'></i> Cetak</a>
												</center>
										</td>
									</tr>";
									$no++;
								}
								?>
							</tbody>
						</table>
					</form>
				</div><!-- /.box-body -->
			</div><!-- /.box -->
		</div>
		<script>
			function cetakGaji(no) {
				// Toggle visibility of the result row
				var resultRow = document.getElementById('result_' + no);
				resultRow.style.display = (resultRow.style.display == 'none') ? 'table-row' : 'none';
			}
		</script>
<?php
	}
}
?>