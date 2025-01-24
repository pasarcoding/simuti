<?php 
session_start();
error_reporting(0);
include "../../config/koneksi.php";
include "../../config/rupiah.php";
include "../../config/library.php";
include "../../config/fungsi_indotgl.php";
include "../../config/variabel_default.php";

if (isset($_SESSION['id'])){
    $idt = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM identitas"));
    $tgl_mulai = $_GET['tgl_mulai'];
    $tgl_akhir = $_GET['tgl_akhir'];
    $idTahunAjaran = $_GET['thn_ajar'];
    $idKelas = $_GET['kelas'];
    $ta = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM tahun_ajaran WHERE idTahunAjaran='$idTahunAjaran'"));
    if ($idKelas !='all'){
      $kls = mysqli_fetch_array(mysqli_query($conn,"SELECT * FROM kelas_siswa WHERE idKelas='$idKelas'"));
      $kelas=$kls['nmKelas'];
    }else{
      $kelas = 'Semua Kelas';
    }
    $file = 'Laporan Pelanggaran Kelas '.$kelas.' Tanggal'.tgl_raport($tgl_mulai).' Sampai '.tgl_raport($tgl_akhir).'Tahun Ajaran '.$ta['nmTahunAjaran'];
    $nama_file    =str_replace(' ', '_', $file);
    header("Content-type: application/vnd-ms-excel");
    header("Content-Disposition: attachment; filename=".$nama_file.".xls");

            echo '<table border="0" cellpadding="2px">
                        <tr>
                            <td style="font-weight: bold; font-size: 14pt" colspan="7">'.$idt['nmSekolah'].'</td>
                        </tr>
                        <tr>
                            <td style="font-size: 8pt;" colspan="7">'.$idt['alamat'].', '.$idt['kecamatan'].', '.$idt['kabupaten'].', '.$idt['propinsi'].' Telp. '.$idt['noTelp'].'</td>
                        </tr>
                    </table><br>';
            echo '<table style="font-size: 8pt" cellpadding="1" border="0">
                        <tr>
                            <td style=" font-size: 8pt">
                                <table align="left">
                                   
                                    <tr>
                                        <td width="70px">Kelas :</td>
                                        <td>'.$kelas.'</td>
                                    </tr>
                                    
                                </table>
                            </td>
                        </tr>
                        <tr><td></td></tr>
                        <tr>
                            <td align="center" style="font-size: 10pt"><b>Laporan Pelanggaran per Tanggal '.tgl_raport($tgl_mulai).' Sampai '.tgl_raport($tgl_akhir).'</b><br>Tahun Ajaran '.$ta['nmTahunAjaran'].'</td>
                        </tr>
                        <tr><td></td></tr>';
                echo    '<tr>
                            <td>
                                <table id="example1" border="1" class="table table-hover table-bordered dataTable no-footer text-center" style="white-space: nowrap;">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>NIS</th>
                                        <th>Nama Siswa</th>
                                        <th>Kelas</th>
                                        <th>Pelanggaran</th>
                                        <th>Tindakan</th>
                                        <th>Poin</th>
                                        <th>Catatan</th>
                                    </tr>';
                                    if ($idKelas == 'all' ){
                                        $sql_konseling = mysqli_query($conn,"SELECT siswa_konseling.*, view_detil_siswa.* FROM siswa_konseling LEFT JOIN view_detil_siswa ON siswa_konseling.siswa=view_detil_siswa.idSiswa  WHERE DATE(siswa_konseling.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND siswa_konseling.tahunAjaran='$idTahunAjaran' AND siswa_konseling.stdel='0'  ORDER BY siswa_konseling.tanggal ASC");
                                    }elseif ($idKelas != 'all' ){
                                        $sql_konseling = mysqli_query($conn,"SELECT siswa_konseling.*, view_detil_siswa.* FROM siswa_konseling LEFT JOIN view_detil_siswa ON siswa_konseling.siswa=view_detil_siswa.idSiswa  WHERE DATE(siswa_konseling.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND siswa_konseling.tahunAjaran='$idTahunAjaran' AND siswa_konseling.stdel='0'  AND view_detil_siswa.idKelas='$idKelas' ORDER BY siswa_konseling.tanggal ASC");
                                    }elseif ($idKelas != 'all' ){
                                        $sql_konseling = mysqli_query($conn,"SELECT siswa_konseling.*, view_detil_siswa.* FROM siswa_konseling LEFT JOIN view_detil_siswa ON siswa_konseling.siswa=view_detil_siswa.idSiswa  WHERE DATE(siswa_konseling.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND siswa_konseling.tahunAjaran='$idTahunAjaran' AND siswa_konseling.stdel='0'   ORDER BY siswa_konseling.tanggal ASC");
                                    }else{
                                        $sql_konseling = mysqli_query($conn,"SELECT siswa_konseling.*, view_detil_siswa.* FROM siswa_konseling LEFT JOIN view_detil_siswa ON siswa_konseling.siswa=view_detil_siswa.idSiswa  WHERE DATE(siswa_konseling.tanggal) BETWEEN '$tgl_mulai' AND '$tgl_akhir' AND siswa_konseling.tahunAjaran='$idTahunAjaran' AND siswa_konseling.stdel='0'  AND view_detil_siswa.idKelas='$idKelas'   ORDER BY siswa_konseling.tanggal ASC");
                                    }
                                    $no=1;
                                    while ($konseling = mysqli_fetch_array($sql_konseling)) {
                                        echo '<tr>
                                                  <td>'.$no++.'</td>
                                                  <td>'.tgl_miring($konseling['tanggal']).'</td>
                                                  <td>'.$konseling['nisSiswa'].'</td>
                                                  <td>'.$konseling['nmSiswa'].'</td>
                                                  <td>'.$konseling['nmKelas'].'</td>

                                                  <td>'.$konseling['pelanggaran'].'</td>
                                                  <td>'.$konseling['tindakan'].'</td>
                                                  <td>'.$konseling['poin'].' Poin</td>
                                                  <td>'.$konseling['catatan'].'</td>
                                              </tr>';
                                    }
                echo'           </table>
                            </td>
                        </tr>';
                                
                echo'   <tr><td></td></tr>
                        <tr>
                            <td>
                                <table border="0" style="font-size:8pt">
                                    <tr>
                                        <td colspan="6"></td>
                                        <td colspan="4" align="center">'.$idt['kabupaten'].', '.tgl_raport(date('Y-m-d')).'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td colspan="4" align="center">Kepala Sekolah</td>
                                    </tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr><td colspan="10"></td></tr>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td colspan="4" align="center" style="font-weight:bold;">'.$idt['nmKepsek'].'</td>
                                    </tr>
                                    <tr>
                                        <td colspan="6"></td>
                                        <td colspan="4" align="center">Nip. '.$idt['nipKepsek'].'</td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                    </table>';

}else{
    include "../../login.php";
}