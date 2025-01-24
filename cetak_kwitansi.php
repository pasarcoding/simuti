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
	$idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas "));

    $tampil = mysqli_fetch_array(mysqli_query($conn, "SELECT
												*
												
											FROM
												jurnal_umum
                      where  id='$_GET[id]' "));
?>
	<!DOCTYPE html>
	<html>

	<head>
		<title>Cetak - Kwitansi</title>
		<link rel="stylesheet" href="bootstrap/css/printer.css">
		<style>
        .cell {
            width: 20px;
            height: 12px;
            /*border: 0.5px solid black;*/
            background-color: rgb(192, 200, 209);
            font-family: Arial;
            font-weight: bold;
            /*font-size: 10px;*/
            text-align: left;
        }
        .background {
            width: 210px;
            height:30px;
            padding : 10px;
            border: 0.5px dashed black;
            background-color: rgb(192, 200, 209);
            font-family: Arial;
            font-size: 18px;
            text-align: left;
        }
    </style>
	</head>

	<body>
		<?php
		$tahun = $_GET['tahun'];
		$jenis = $_GET['jenisBayar'];
		$kelas = $_GET['kelas'];

		//tagihan bebas
		$sqlTagihanBebas = mysqli_query($conn,"SELECT
									tagihan_bebas.*,
									jenis_bayar.idPosBayar,
									jenis_bayar.idTahunAjaran,
									jenis_bayar.nmJenisBayar,
									jenis_bayar.tipeBayar,
									siswa.nisSiswa,
									siswa.nisnSiswa,
									siswa.nmSiswa,
									siswa.jkSiswa,
									siswa.agamaSiswa,
									siswa.idKelas,
									siswa.statusSiswa,
									tahun_ajaran.nmTahunAjaran,
									kelas_siswa.nmKelas
								FROM
									tagihan_bebas
								INNER JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
								INNER JOIN siswa ON tagihan_bebas.idSiswa = siswa.idSiswa
								INNER JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
								INNER JOIN kelas_siswa ON tagihan_bebas.idKelas = kelas_siswa.idKelas
								INNER JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
								WHERE tagihan_bebas.idJenisBayar='$jenis' AND siswa.idKelas='$kelas' AND jenis_bayar.idTahunAjaran='$tahun' ORDER BY tagihan_bebas.idTagihanBebas ASC");
		?>
		<!-- Box Data -->
			<table width="100%">
				<tr>
					<td width="100px" align="left"><img src="./gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="60px"></td>
					<td valign="top">
						<h3 align="center" style="margin-bottom:8px ">
							<?php echo $idt['nmSekolah']; ?>
						</h3>
						<center><?php echo $idt['alamat']; ?></center>
					</td>
					<!--<td width="100px" align="right"><img src="./gambar/logo/<?php echo $idt['logo_kanan']; ?>" height="60px"></td>-->
				</tr>
			</table>
			<hr>
            <table width="100%" >
                <tr>
                    <td  style="text-align: center; font-size:23px;">
                     <strong>KWITANSI</strong> 
                    </td>
                 
                </tr>
            </table> 
            <hr> 
			<table width="100%" class="table-condensed">
                <tr>
                    <td class="font-13"  >No.
                    </td>
                    <td width="10%" border="1" style="border: 0.5px solid black; width:30px;"><b> : BK<?= $tampil['id']?></b> </td> 
                </tr><br>
                <tr>
                    <td class="font-13" >Telah terima dari
                    </td>
                    <td width="70%" ><b>: <?= $idt['nmSekolah'] ?></b> </td> 
                </tr>
                <br>
                  <tr>
                    <td class="font-13">Uang sejumlah
                    </td>
                    <td border="1" style="vertical-align: middle; border: 0.5px dashed black;"> : # <b><?= terbilang($tampil['pengeluaran']) ?> rupiah</b></td>
                </tr>
                  <br>
              
                 
                <tr>
                    <td class="font-13">Untuk pembayaran
                    </td>
                    <td >: <?= $tampil['ket'] ?></td>
                </tr>
                
            </table>  
		<br />

        <div class="background" ><strong>Jumlah: <?= buatRp($tampil['pengeluaran']) ?></strong></div>
    	<?php	$penerimaan = $tampil['pengeluaran']; // Misalnya, untuk keperluan contoh
    
        if ($penerimaan < 1000000) {
            // Jika penerimaan kurang dari 1 juta
            $menyetujui = '<td align="center" width="60%"> <br /> <br />Menyetujui<br /><br /><br /><br><br><br>  <b><u>' . $idt['nmBendahara'] . '</u><br>' . $idt['nipBendahara'] . '</b> </td>';
        } else {
            // Jika penerimaan sama dengan atau lebih dari 1 juta
            $menyetujui = '<td align="center" width="60%"> <br /> <br />Menyetujui<br /><br /><br /><br><br><br>  <b><u>' . $idt['nmKepsek'] . '</u><br>' . $idt['nipKepsek'] . '</b> </td>';
        }
        
        echo '<br><br><table width="100%" border="0" >
                    <tr align="center">
                        ' . $menyetujui . '
                        <td  align="right" width="40%">
                            ' . $idt['kabupaten'] . ', ' . tgl_raport($tampil['tgl']) . '
                            <br />Penerima<br />
        
                            <br><br><br><br><br>
                            <b><u>.........................</u><br></b><br>
        
                        </td>
                    </tr>
                </table>';
                ?>
            
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