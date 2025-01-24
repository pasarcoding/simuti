<?php
session_start();
error_reporting(0);
include "config/koneksi.php";
 $tampil = mysqli_query($conn, "SELECT *, a.id as idGuru FROM rb_guru a 
                                          LEFT JOIN rb_jenis_kelamin b ON a.id_jenis_kelamin=b.id_jenis_kelamin 
                                            LEFT JOIN rb_status_kepegawaian c ON a.id_status_kepegawaian=c.id_status_kepegawaian 
                                              LEFT JOIN rb_jenis_ptk d ON a.id_jenis_ptk=d.id_jenis_ptk
                                              LEFT JOIN rb_tugas_tambahan e ON a.tugas_tambahan=e.id WHERE a.id_status_keaktifan='Aktif'
                                              ORDER BY a.nama_guru ASC");
header("Content-type: application/vnd-ms-excel");
header("Content-Disposition: attachment; filename=laporan_guru_" . str_replace(" ", "_", $kls['nmKelas']) . "_" . date('dmyHis') . ".xls");
?>
<table border="1">
<thead>
    <tr>
        <th>No.</th>
        <th>NIK</th>
        <th>NBM</th>
        <th>NUPTK</th>
        <th>Nama Lengkap</th>
        <th>Tempat Lahir</th>
        <th>Tanggal Lahir</th>
        <th>Pendidikan</th>
        <th>Status Pegawai</th>
        <th>Jenis PTK</th>
        <th>Tugas Tambahan</th>
        <th>TMT Pengangkatan</th>
        <th>Masa Kerja</th>
        <th>Status Peningkatan</th>
        <th>Alamat </th>
        <th>RT/RW</th>
        <th>Kelurahan</th>
        <th>Kecamatan</th>
        <th>Kab/Kota</th>
        <th>No HP</th>
    </tr>
</thead>

		<tbody>
		<?php
		$no = 1;
		while ($ds = mysqli_fetch_array($tampil)) {
		$tanggalAwal = $ds['tmt_pengangkatan'];

        $tanggalSekarang = date("Y-m-d");

         // Menghitung selisih tahun dan bulan
        $selisih = date_diff(date_create($tanggalAwal), date_create($tanggalSekarang));

        $tmtTahun = $selisih->y;
        $tmtBulan = $selisih->m;
    echo "<tr>
        <td>$no</td>
        <td>'$ds[nik]</td>
        <td>'$ds[nbm]</td>
        <td>'$ds[nuptk]</td>
        <td>$ds[nama_guru]</td>
        <td>$ds[tempat_lahir]</td>
        <td>$ds[tanggal_lahir]</td>
        <td>$ds[id_status_pendidikan]</td>
         <td>$ds[status_kepegawaian]</td>
        <td>$ds[jenis_ptk]</td>
        <td>$ds[nmTugas]</td>
        <td>$ds[tmt_pengangkatan]</td>
        <td>$tmtTahun tahun $tmtBulan bulan</td>
        <td>$ds[sts_peningkatan]</td>
        <td>$ds[alamat_jalan]</td>
        <td>$ds[rt]/$ds[rw]</td>
        <td>$ds[desa_kelurahan]</td>
        <td>$ds[kecamatan]</td>
        <td>$ds[kab]</td>
        <td>$ds[hp]</td>
        
        
    </tr>";
    $no++;
}

						?>
					</tbody>
</table>