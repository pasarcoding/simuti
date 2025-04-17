<?php
function tgl_indo($tgl)
{
	$tanggal = substr($tgl, 8, 2);
	$bulan = getBulan(substr($tgl, 5, 2));
	$tahun = substr($tgl, 0, 4);
	return $tanggal . ' ' . $bulan . ' ' . $tahun;
}
function tgl_indo_jam($tgl)
{
	$tanggal = substr($tgl, 8, 2);
	$bulan = getBulan(substr($tgl, 5, 2));
	$tahun = substr($tgl, 0, 4);
	$jam = substr($tgl, 11, 2);
	$menit = substr($tgl, 14, 2);
	$detik = substr($tgl, 17, 2);
	return $tanggal . ' ' . $bulan . ' ' . $tahun . ' ' . $jam . ':' . $menit . ':' . $detik;
}
function tgl_grafik($tgl)
{
	$tanggal = substr($tgl, 8, 2);
	$bulan = getBulanraport(substr($tgl, 5, 2));
	$tahun = substr($tgl, 0, 4);
	return $tanggal . '_' . $bulan;
}

function tgl_raport($tgl)
{
	$tanggal = substr($tgl, 8, 2);
	$bulan = getBulanReport(substr($tgl, 5, 2));
	$tahun = substr($tgl, 0, 4);
	return $tanggal . ' ' . $bulan . ' ' . $tahun;
}

function tgl_simpan($tgl)
{
	$tanggal = substr($tgl, 0, 2);
	$bulan = substr($tgl, 3, 2);
	$tahun = substr($tgl, 6, 4);
	return $tahun . '-' . $bulan . '-' . $tanggal;
}

function tgl_view($tgl)
{
	$tanggal = substr($tgl, 8, 2);
	$bulan = substr($tgl, 5, 2);
	$tahun = substr($tgl, 0, 4);
	return $tanggal . '-' . $bulan . '-' . $tahun;
}
function tgl_miring($tgl)
{
	$tanggal = date('d/m/Y', strtotime($tgl));
	return $tanggal;
}

function hari_ini($tanggal)
{
	$day = date('D', strtotime($tanggal));
	$dayList = array(
		'Sun' => 'Minggu',
		'Mon' => 'Senin',
		'Tue' => 'Selasa',
		'Wed' => 'Rabu',
		'Thu' => 'Kamis',
		'Fri' => 'Jumat',
		'Sat' => 'Sabtu'
	);
	return $dayList[$day];
}

function getBulan($bln)
{
	switch ($bln) {
		case 1:
			return "Jan";
			break;
		case 2:
			return "Feb";
			break;
		case 3:
			return "Mar";
			break;
		case 4:
			return "Apr";
			break;
		case 5:
			return "Mei";
			break;
		case 6:
			return "Jun";
			break;
		case 7:
			return "Jul";
			break;
		case 8:
			return "Agu";
			break;
		case 9:
			return "Sep";
			break;
		case 10:
			return "Okt";
			break;
		case 11:
			return "Nov";
			break;
		case 12:
			return "Des";
			break;
	}
}

function getBulanReport($bln)
{
	switch ($bln) {
		case '01':
			return "Januari";
			break;
		case '02':
			return "Februari";
			break;
		case '03':
			return "Maret";
			break;
		case '04':
			return "April";
			break;
		case '05':
			return "Mei";
			break;
		case '06':
			return "Juni";
			break;
		case '07':
			return "Juli";
			break;
		case '08':
			return "Agustus";
			break;
		case '09':
			return "September";
			break;
		case 10:
			return "Oktober";
			break;
		case 11:
			return "November";
			break;
		case 12:
			return "Desember";
			break;
	}
}
