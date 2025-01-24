<section class="sidebar">
	<font face="Arial">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo $foto; ?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?php echo $nama; ?></p>
				<a href="#"><i class="fa fa-circle text-success"></i>Tahun Ajaran : <?= $ta['nmTahunAjaran']; ?></a>
			</div>
		</div>
		<?php
		switch ($_GET['view']) {
				//menu master data
			case 'admin':
				$judul = "<span class='fa fa-users'></span> Manajemen Admin";
				$aktifA = 'active';
				$aktifA1 = 'active';
				break;
			case 'tahun':
				$judul = "<span class='fa fa-calendar'></span> Manajemen Tahun Ajaran";
				$aktifA = 'active';
				$aktifA2 = 'active';
				break;
			case 'kelas':
				$judul = "<span class='fa fa-tasks'></span> Manajemen Data Kelas";
				$aktifA = 'active';
				$aktifA3 = 'active';
				break;
			case 'siswa':
				$judul = "<span class='fa fa-users'></span> Manajemen Data Siswa";
				$aktifA = 'active';
				$aktifA4 = 'active';
				break;
			case 'siswa_afirmasi':
				$judul = "<span class='fa fa-users'></span> Manajemen Data Siswa Afirmasi";
				$aktifA = 'active';
				$aktifA10 = 'active';
				break;
			case 'kelulusan':
				$judul = "<span class='fa fa-graduation-cap'></span> Kelulusan";
				$aktifA = 'active';
				$aktifA5 = 'active';
				break;
			case 'kenaikankelas':
				$judul = "<span class='fa  fa-cubes'></span> Proses Pindah Kelas dan Kenaikan Kelas";
				$aktifA = 'active';
				$aktifA6 = 'active';
				break;
			case 'guru':
				$judul = "<span class='fa fa-secret'></span> Manajemen Data Guru";
				$aktifA = 'active';
				$aktifA8 = 'active';
				break;
			case 'sktahunan':
				$judul = "<span class='fa  fa-book'></span>SK Tahunan PTK";
				$aktifA = 'active';
				$aktifA11 = 'active';
				break;
			case 'kamar':
				$judul = "<span class='fa  fa-cubes'></span> Kamar";
				$aktifA = 'active';
				$aktifA7 = 'active';
				break;
			case 'pindahkelas':
				$judul = "<span class='fa fa-tasks'></span> Pindah Kelas";
				$aktifA = 'active';
				$aktifA6 = 'active';
				break;
			case 'setting_absen':
				$judul = "<span class='fa fa-cogs'></span> Setting Absen";
				$aktifA = 'active';
				$aktifA9 = 'active';
				break;

			case 'surat_masuk':
				$judul = "<span class='fa fa-briefcase'></span> Surat Masuk";
				$aktifM = 'active';
				$aktifM1 = 'active';
				break;
			case 'surat_keluar':
				$judul = "<span class='fa fa-arrow-up'></span> Surat Keluar";
				$aktifM = 'active';
				$aktifM2 = 'active';
				break;
				//menu kesiswaan
			case 'prestasi':
				$judul = "<span class='fa fa-tasks'></span> Prestasi";
				$aktifZ = 'active';
				$aktifZ1 = 'active';
				break;
			case 'lapprestasi':
				$judul = "<span class='fa fa-tasks'></span> Laporan Prestasi";
				$aktifZ = 'active';
				$aktifZ2 = 'active';
				break;
			case 'bk':
				$judul = "<span class='fa fa-tasks'></span> Pelanggaran";
				$aktifZ = 'active';
				$aktifZ3 = 'active';
				break;
			case 'lapbk':
				$judul = "<span class='fa fa-tasks'></span> Laporan Pelanggaran";
				$aktifZ = 'active';
				$aktifZ4 = 'active';
				break;
			case 'tahfidz':
				$judul = "<span class='fa fa-book'></span> Tahfidz";
				$aktifZ = 'active';
				$aktifZ5 = 'active';
				break;
			case 'lapkesehatan':
				$judul = "<span class='fa fa-medkit'></span> Laporan Kesehatan";
				$aktifZ = 'active';
				$aktifZ6 = 'active';
				break;

			case 'lapkeg':
				$judul = "<span class='fa fa-tasks'></span> Laporan Kegiatan dan Kesiswaan";
				$aktifZ = 'active';
				$aktifZ7 = 'active';
				break;
			case 'program_kerja_kesiswaan':
				$judul = "<span class='fa fa-tasks'></span> Program Kerja";
				$aktifZ = 'active';
				$aktifZ8 = 'active';
				break;
			case 'rencana_kegiatan_kesiswaan':
				$judul = "<span class='fa fa-tasks'></span> Rencana Kegiatan";
				$aktifZ = 'active';
				$aktifZ9 = 'active';
				break;
			case 'realisasi_kegiatan_kesiswaan':
				$judul = "<span class='fa fa-tasks'></span> Realisasi Kegiatan";
				$aktifZ = 'active';
				$aktifZ10 = 'active';
				break;
			case 'monev_kegiatan_kesiswaan':
				$judul = "<span class='fa fa-tasks'></span> Monev Kegiatan";
				$aktifZ = 'active';
				$aktifZ11 = 'active';
				break;



				//menu keuangan
			case 'posbayar':
				$judul = "<span class='fa fa-money'></span> Pos Bayar";
				$aktifB = 'active';
				$aktifB1 = 'active';
				break;
			case 'jenisbayar':
				$judul = "<span class='fa fa-ils'></span> Jenis Pembayaran";
				$aktifB = 'active';
				$aktifB2 = 'active';
				break;
			case 'bank':
				$judul = "<span class='fa fa-bank'></span>Bank";
				$aktifB = 'active';
				$aktifB3 = 'active';
				break;
			case 'tarif':
				$judul = "<span class='fa fa-gg-circle'></span> Tarif Pembayaran";
				$aktifB = 'active';
				$aktifB2 = 'active';
				break;
			case 'setting_gaji':
				$judul = "<span class='fa fa-money'></span> Setting Gaji";
				$aktifB = 'active';
				$aktifB4 = 'active';
				break;
			case 'bayar_gaji':
				$judul = "<span class='fa fa-money'></span> Bayar Gaji";
				$aktifB = 'active';
				$aktifB5 = 'active';
				break;
			case 'bayar_dapen':
				$judul = "<span class='fa fa-money'></span> Bayar Dapen";
				$aktifB = 'active';
				$aktifB6 = 'active';
				break;
			case 'jurnalumums':
				$judul = "<span class='fa fa-line-chart'></span> Pemasukan Kas";
				$aktifm = 'active';
				$aktifm1 = 'active';
				break;
			case 'jurnalumum':
				$judul = "<span class='fa fa-line-chart'></span> Pengeluaran Kas";
				$aktifm = 'active';
				$aktifm2 = 'active';
				break;

			case 'jurnal':
				$judul = "<span class='fa fa-line-chart'></span> Saldo Awal";
				$aktifn = 'active';
				$aktifn = 'active';
				break;
				//menu backup&restore
			case 'restore':
				$judul = "";
				$aktifZ = 'active';
				break;
			case 'backup':
				$judul = "";
				$aktifZ = 'active';
				break;
			case 'tagihan1':
				$judul = "<span class='fa fa-download'></span> Tagihan";
				$aktifZ = 'active';
				break;
				//akademik
			case 'jadwalpelajaran':
				$judul = "<span class='fa fa-book'></span> Jadwal Pelajaran";
				$aktifakademik = 'active';
				$aktifakademik1 = 'active';
				break;
			case 'matapelajaran':
				$judul = "<span class='fa fa-book'></span> Mata Pelajaran";
				$aktifakademik = 'active';
				$aktifakademik2 = 'active';
				break;
			case 'jam':
				$judul = "<span class='fa fa-book'></span> Jam Jadwal Pelajaran";
				$aktifakademik = 'active';
				$aktifakademik0 = 'active';
				break;
				//menu tabungan
			case 'nasabah':
				$judul = "<span class='fa fa-users'></span> Nasabah";
				$aktifK = 'active';
				$aktifK1 = 'active';
				break;
			case 'transaksi':
				$judul = "<span class='fa fa-money'></span> Tabungan";
				$aktifK = 'active';
				$aktifK2 = 'active';
				break;
			case 'transaksi_yatim':
				$judul = "<span class='fa fa-money'></span> Tabungan Yatim";
				$aktifK = 'active';
				$aktifK4 = 'active';
				break;
			case 'laptransaksi':
				$judul = "<span class='fa fa-print'></span> Laporan Transaksi";
				$aktifK = 'active';
				$aktifK5 = 'active';
				break;
			case 'pengaturan':
				$judul = "<span class='fa fa-wrench'></span> Pengaturan";
				$aktifK = 'active';
				$aktifK5 = 'active';
				break;
				//menu bhutangpiutang

			case 'hutangtoko':
				$judul = "<span class='fa fa-money'></span> Hutang ";
				$aktifl = 'active';
				break;

			case 'prestasi':
				$judul = "<span class='fa fa-tasks'></span> Prestasi";
				$aktifZ = 'active';
				$aktifZ1 = 'active';
				break;
			case 'lapprestasi':
				$judul = "<span class='fa fa-tasks'></span> Laporan Prestasi";
				$aktifZ = 'active';
				$aktifZ2 = 'active';
				break;
			case 'bk':
				$judul = "<span class='fa fa-tasks'></span> Pelanggaran";
				$aktifZ = 'active';
				$aktifZ3 = 'active';
				break;
			case 'lapbk':
				$judul = "<span class='fa fa-tasks'></span> Laporan Pelanggaran";
				$aktifZ = 'active';
				$aktifZ4 = 'active';
				break;
				//kurikulum
			case 'prestasi_ptk':
				$judul = "<span class='fa fa-tasks'></span>Data Prestasi PTK";
				$kurikulum = 'active';
				$kurikulum1 = 'active';
				break;
			case 'lapprestasi_ptk':
				$judul = "<span class='fa fa-tasks'></span> Laporan Prestasi PTK";
				$kurikulum = 'active';
				$kurikulum2 = 'active';
				break;
			case 'bk_ptk':
				$judul = "<span class='fa fa-tasks'></span> Data Pembinaan PTK";
				$kurikulum = 'active';
				$kurikulum3 = 'active';
				break;
			case 'lapbk_ptk':
				$judul = "<span class='fa fa-tasks'></span> Laporan Pembinaan PTK";
				$kurikulum = 'active';
				$kurikulum4 = 'active';
				break;
			case 'program_kerja_kurikulum':
				$judul = "<span class='fa fa-tasks'></span> Program Kerja";
				$kurikulum = 'active';
				$kurikulum5 = 'active';
				break;
			case 'rencana_kegiatan_kurikulum':
				$judul = "<span class='fa fa-tasks'></span> Rencana Kegiatan";
				$kurikulum = 'active';
				$kurikulum6 = 'active';
				break;
			case 'realisasi_kegiatan_kurikulum':
				$judul = "<span class='fa fa-tasks'></span> Realisasi Kegiatan";
				$kurikulum = 'active';
				$kurikulum7 = 'active';
				break;
			case 'monev_kegiatan_kurikulum':
				$judul = "<span class='fa fa-tasks'></span> Monev Kegiatan";
				$kurikulum = 'active';
				$kurikulum8 = 'active';
				break;


			case 'tahfidz':
				$judul = "<span class='fa fa-book'></span> Tahfidz";
				$aktifZ = 'active';
				$aktifZ5 = 'active';
				break;
			case 'lapkesehatan':
				$judul = "<span class='fa fa-medkit'></span> Laporan Kesehatan";
				$aktifZ = 'active';
				$aktifZ6 = 'active';
			case 'lapkeg':
				$judul = "<span class='fa fa-tasks'></span> Laporan Kegiatan dan Kesiswaan";
				$aktifZ = 'active';
				$aktifZ7 = 'active';
				break;


				//menu pembayaran
			case 'pembayaran':
				$judul = "<span class='fa fa-money'></span> Transaksi Pembayaran";
				$aktifC = 'active';
				break;
			case 'angsuran':
				$judul = "<span class='fa fa-money'></span> Angsuran Pembayaran";
				$aktifC = 'active';
				break;
			case 'bayarbulanan':
				$judul = "<span class='fa fa-money'></span> Pembayaran Bulanan";
				$aktifC = 'active';
				break;
				//inventaris
			case 'inventaris':
				$judul = "<span class='fa fa-money'></span> Inventaris";
				$aktifInventaris = 'active';
				$aktifInventaris1 = 'active';

				break;
			case 'inventarismasuks':
				$judul = "<span class='fa fa-money'></span> Inventaris Masuk";
				$aktifInventaris = 'active';
				$aktifInventaris2 = 'active';
				break;
			case 'inventariskeluar':
				$judul = "<span class='fa fa-money'></span> Inventaris Keluar";
				$aktifInventaris = 'active';
				$aktifInventaris3 = 'active';

				break;

			case 'program_kerja_sarpras':
				$judul = "<span class='fa fa-tasks'></span> Program Kerja";
				$aktifInventaris = 'active';
				$aktifInventaris4 = 'active';
			case 'rencana_kegiatan_sarpras':
				$judul = "<span class='fa fa-tasks'></span> Rencana Kegiatan";
				$aktifInventaris = 'active';
				$aktifInventaris5 = 'active';
				break;
			case 'realisasi_kegiatan_sarpras':
				$judul = "<span class='fa fa-tasks'></span> Realisasi Kegiatan";
				$aktifInventaris = 'active';
				$aktifInventaris6 = 'active';
				break;
			case 'monev_kegiatan_sarpras':
				$judul = "<span class='fa fa-tasks'></span> Monev Kegiatan";
				$aktifInventaris = 'active';
				$aktifInventaris7 = 'active';
				break;
			case 'inv_lok_barang':
				$judul = "<span class='fa fa-tasks'></span> Data Lokasi Barang";
				$aktifInventaris = 'active';
				$aktifInventaris8 = 'active';
				break;
			case 'inv_sumber_dana':
				$judul = "<span class='fa fa-tasks'></span>Data Sumber Dana";
				$aktifInventaris = 'active';
				$aktifInventaris9 = 'active';
				break;
			case 'inv_jenis_barang':
				$judul = "<span class='fa fa-tasks'></span> Data Jenis Barang";
				$aktifInventaris = 'active';
				$aktifInventaris10 = 'active';
				break;
			case 'inv_data_barang':
				$judul = "<span class='fa fa-tasks'></span> Data Data Barang";
				$aktifInventaris = 'active';
				$aktifInventaris11 = 'active';
				break;
			case 'inv_data_item':
				$judul = "<span class='fa fa-tasks'></span> Data Item Barang";
				$aktifInventaris = 'active';
				$aktifInventaris12 = 'active';
				break;
			case 'inv_pengadaan':
				$judul = "<span class='fa fa-tasks'></span> Data Pengadaan Barang";
				$aktifInventaris = 'active';
				$aktifInventaris13 = 'active';
				break;
			case 'inv_penghapusan':
				$judul = "<span class='fa fa-tasks'></span> Data Penghapusan Barang";
				$aktifInventaris = 'active';
				$aktifInventaris14 = 'active';
				break;
				//menu laporan

			case 'lapsiswaafirmasi':
				$judul = "<span class='fa fa-tasks'></span> Laporan Siswa Afirmasi Per Kelas";
				$aktifD = 'active';
				$aktifD11 = 'active';
				break;
			case 'lapsiswa':
				$judul = "<span class='fa fa-tasks'></span> Laporan Siswa Per Kelas";
				$aktifD = 'active';
				$aktifD1 = 'active';
				break;
			case 'lappembayaran':
				$judul = "<span class='fa fa-tasks'></span> Laporan Pembayaran Per Kelas";
				$aktifD = 'active';
				$aktifD2 = 'active';
				break;
			case 'lappembayaranperbulan':
				$judul = "<span class='fa fa-tasks'></span> Laporan Pembayaran Per Bulan";
				$aktifD = 'active';
				$aktifD3 = 'active';
				break;
			case 'lappembayaranperposbayar':
				$judul = "<span class='fa fa-tasks'></span> Laporan Pembayaran Per Pos Bayar";
				$aktifD = 'active';
				$aktifD5 = 'active';
				break;
			case 'laptagihansiswa':
				$judul = "<span class='fa fa-tasks'></span> Laporan Tagihan Siswa";
				$aktifD = 'active';
				$aktifD6 = 'active';
				break;
			case 'lapbku':
				$judul = "<span class='fa fa-tasks'></span> Buku Kas Umum";
				$aktifD = 'active';
				$aktifD9 = 'active';
				break;
			case 'lapbank':
				$judul = "<span class='fa fa-tasks'></span> Buku Bank";
				$aktifD = 'active';
				$aktifD10 = 'active';
				break;
			case 'laptunai':
				$judul = "<span class='fa fa-tasks'></span> Buku Tunai";
				$aktifD = 'active';
				$aktifD11 = 'active';
				break;
			case 'lapkas':
				$judul = "<span class='fa fa-tasks'></span> Laporan Kas Kelas";
				$aktifD = 'active';
				$aktifD7 = 'active';
				break;
			case 'rekapitulasi':
				$judul = "<span class='fa fa-tasks'></span> Rekapitulasi Pembayaran";
				$aktifD = 'active';
				$aktifD4 = 'active';
				break;
			case 'lappiutang':
				$judul = "<span class='fa fa-tasks'></span> Laporan Piutang Per Jenis Bayar";
				$aktifD = 'active';
				$aktifD21 = 'active';
				break;
			case 'laptransaksitabungan':
				$judul = "<span class='fa fa-print'></span> Laporan Transaksi";
				$aktifD = 'active';
				$aktifD12 = 'active';
				break;

			case 'whatsapp':
				$judul = "<span class='fa fa-tasks'></span> Whatsapp";
				$aktifw1 = 'active';
				break;
			case 'absensi_guru':
				$judul = "<span class='fa fa-calendar'></span> Absensi Guru";
				$aktifabsen = 'active';
				$aktifabsen1 = 'active';
				break;
			case 'absensi_guru_rekap':
				$judul = "<span class='fa fa-print'></span> Rekap Absensi Guru";
				$aktifabsen = 'active';
				$aktifabsen2 = 'active';
				break;
				//menu home

			case 'program_kerja':
				$judul = "<span class='fa fa-tasks'></span> Program Kerja";
				$aktifkepalasekolah = 'active';
				$aktifkepalasekolah1 = 'active';
				break;
			case 'rencana_kegiatan':
				$judul = "<span class='fa fa-tasks'></span> Rencana Kegiatan";
				$aktifkepalasekolah = 'active';
				$aktifkepalasekolah2 = 'active';
				break;
			case 'realisasi_kegiatan':
				$judul = "<span class='fa fa-tasks'></span> Realisasi Kegiatan";
				$aktifkepalasekolah = 'active';
				$aktifkepalasekolah3 = 'active';
				break;
			case 'monev_kegiatan':
				$judul = "<span class='fa fa-tasks'></span> Monev Kegiatan";
				$aktifkepalasekolah = 'active';
				$aktifkepalasekolah4 = 'active';
				break;


			case 'program_kerja_ismubaris':
				$judul = "<span class='fa fa-tasks'></span> Program Kerja";
				$aktifismubaris = 'active';
				$aktifismubaris1 = 'active';
				break;
			case 'rencana_kegiatan_ismubaris':
				$judul = "<span class='fa fa-tasks'></span> Rencana Kegiatan";
				$aktifismubaris = 'active';
				$aktifismubaris2 = 'active';
				break;
			case 'realisasi_kegiatan_ismubaris':
				$judul = "<span class='fa fa-tasks'></span> Realisasi Kegiatan";
				$aktifismubaris = 'active';
				$aktifismubaris3 = 'active';
				break;
			case 'monev_kegiatan_ismubaris':
				$judul = "<span class='fa fa-tasks'></span> Monev Kegiatan";
				$aktifismubaris = 'active';
				$aktifismubaris3 = 'active';
				break;



			case 'program_kerja_humas':
				$judul = "<span class='fa fa-tasks'></span> Program Kerja";
				$aktifhumas = 'active';
				$aktifhumas1 = 'active';
				break;
			case 'rencana_kegiatan_humas':
				$judul = "<span class='fa fa-tasks'></span> Rencana Kegiatan";
				$aktifhumas = 'active';
				$aktifhumas2 = 'active';
				break;
			case 'realisasi_kegiatan_humas':
				$judul = "<span class='fa fa-tasks'></span> Realisasi Kegiatan";
				$aktifhumas = 'active';
				$aktifhumas3 = 'active';
				break;
			case 'monev_kegiatan_humas':
				$judul = "<span class='fa fa-tasks'></span> Monev Kegiatan";
				$aktifhumas = 'active';
				$aktifhumas4 = 'active';
				break;
			case 'buku_tamu':
				$judul = "<span class='fa fa-book'></span> Buku Tamu";
				$aktifhumas = 'active';
				$aktifhumas5 = 'active';
				break;


			case 'program_kerja_tu':
				$judul = "<span class='fa fa-tasks'></span> Program Kerja";
				$aktiftu = 'active';
				$aktiftu1 = 'active';
				break;
			case 'rencana_kegiatan_tu':
				$judul = "<span class='fa fa-tasks'></span> Rencana Kegiatan";
				$aktiftu = 'active';
				$aktiftu2 = 'active';
				break;
			case 'realisasi_kegiatan_tu':
				$judul = "<span class='fa fa-tasks'></span> Realisasi Kegiatan";
				$aktiftu = 'active';
				$aktiftu3 = 'active';
				break;
			case 'monev_kegiatan_tu':
				$judul = "<span class='fa fa-tasks'></span> Monev Kegiatan";
				$aktiftu = 'active';
				$aktiftu4 = 'active';
				break;

			default:
				$judul = "<span class='fa fa-dashboard'></span> Dashboard";
				$aktifHome = 'active';
		}
		?>
		<!-- sidebar menu: : style can be found in sidebar.less -->

		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<?php if ($_SESSION['level'] == 'kepsek') { ?>
				<li class="<?php echo $aktifHome; ?>"><a href="./"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
				<li class="treeview <?php echo $aktifkepalasekolah; ?>">
					<a href="#">
						<i class="fa fa-tasks faa-shake animated"></i>
						<span>Kepala Sekolah</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifkepalasekolah1; ?>"><a href="index.php?view=program_kerja"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifkepalasekolah2; ?>"><a href="index.php?view=rencana_kegiatan"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifkepalasekolah3; ?>"><a href="index.php?view=realisasi_kegiatan"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifkepalasekolah4; ?>"><a href="index.php?view=monev_kegiatan"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifZ; ?>">
					<a href="#">
						<i class="fa fa-users"></i> <span>Kesiswaan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifZ2; ?>"><a href="index.php?view=lapprestasi"><i class="fa fa-bar-chart"></i> Rekap Prestasi Siswa</a></li>
						<li class="<?php echo $aktifZ4; ?>"><a href="index.php?view=lapbk"><i class="fa fa-bar-chart"></i> Rekap Pelanggaran Siswa</a></li>
						<!--	<li class="<?php echo $aktifZ6; ?>"><a href="index.php?view=lapkesehatan"><i class="fa fa-bar-chart"></i> Rekap Kesehatan Siswa</a></li> -->
					</ul>

					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Kurikulum</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum2; ?>"><a href="index.php?view=lapprestasi_ptk"><i class="fa fa-bar-chart"></i> Rekap Prestasi PTK</a></li>
						<li class="<?php echo $kurikulum4; ?>"><a href="index.php?view=lapbk_ptk"><i class="fa fa-bar-chart"></i> Rekap Pembinaan PTK</a></li>

					</ul>
				</li>

				<li class="treeview <?php echo $aktifInventaris; ?>">
					<a href="#">
						<i class="fa fa-envelope faa-shake animated"></i>
						<span>Sarana Prasarana </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifInventaris1; ?>"><a href="index.php?view=inventaris"><i class="fa fa-arrow-down"></i> Stok BHP </a></li>
						<li class="<?php echo $aktifInventaris2; ?>"><a href="index.php?view=inventarismasuks"><i class="fa fa-arrow-up"></i> Barang Masuk</a></li>
						<li class="<?php echo $aktifInventaris3; ?>"><a href="index.php?view=inventariskeluar"><i class="fa fa-arrow-up"></i> Barang Keluar</a></li>

					</ul>
				</li>

				<li class="treeview <?php echo $aktifakademik; ?>">
					<a href="#">
						<i class="fa fa-tags faa-shake animated"></i>
						<span>Data Akademik </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifakademik0; ?>"><a href="index.php?view=jam"><i class="fa fa-clock-o"></i> Jam</a></li>

						<li class="<?php echo $aktifakademik2; ?>"><a href="index.php?view=matapelajaran"><i class="fa fa-book"></i> Mata Pelajaran</a></li>
						<li class="<?php echo $aktifakademik1; ?>"><a href="index.php?view=jadwalpelajaran"><i class="fa fa-calendar"></i> Jadwal Pelajaran</a></li>
					</ul>
				</li>
				<li class="<?php echo $aktifabsen1; ?>"><a href="index.php?view=absensi_guru"><i class="fa fa-calendar"></i> <span> Absensi PTK</span></a></li>
				<li class="<?php echo $aktifabsen2; ?>"><a href="index.php?view=absensi_guru_rekap"><i class="fa fa-calendar"></i> <span> Rekap Absen PTK</span></a></li>

				<!-- <li class="treeview <?php echo $aktifK; ?>">
					<a href="#">
						<i class="fa fa-leanpub"></i>
						<span>Tabungan Siswa</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $aktifK1; ?>"><a href="index.php?view=nasabah"><i class="fa fa-users "></i><span> Nasabah</span></a></li>
						<li class="<?php echo $aktifK5; ?>"><a href="index.php?view=laptransaksi"><i class="fa fa-print "></i><span> Lap.Transaksi </span></a></li>
					</ul>
				</li> -->
				<li class="<?php echo $aktifA8; ?>"><a href="index.php?view=guru"><i class="fa fa-user-secret"></i> Data PTK</a></li>

				<li class="treeview <?php echo $aktifM; ?>">
					<a href="#">
						<i class="fa fa-envelope faa-shake animated"></i>
						<span>Data Surat </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifM1; ?>"><a href="index.php?view=surat_masuk"><i class="fa fa-arrow-down"></i> Surat Masuk</a></li>
						<li class="<?php echo $aktifM2; ?>"><a href="index.php?view=surat_keluar"><i class="fa fa-arrow-up"></i> Surat Keluar</a></li>
					</ul>
				</li>

				<li class="treeview <?php echo $aktifD; ?>">

					<a href="#">
						<i class="fa fa-print"></i>
						<span>Laporan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifD1; ?>"><a href="index.php?view=lapsiswa"><i class="fa fa-bookmark text-yellow"></i><span> Lap. Data Siswa</span></a></li>
						<li class="<?php echo $aktifD11; ?>"><a href="index.php?view=lapsiswaafirmasi"><i class="fa fa-bookmark text-yellow"></i><span> Lap. Data Siswa Afirmasi</span></a></li>

						<li class="<?php echo $aktifD2; ?>"><a href="index.php?view=lappembayaran"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Kelas</span></a></li>
						<li class="<?php echo $aktifD3; ?>"><a href="index.php?view=lappembayaranperbulan"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Bulan</span></a></li>
						<li class="<?php echo $aktifD5; ?>"><a href="index.php?view=lappembayaranperposbayar"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Pos Bayar</span></a></li>
						<li class="<?php echo $aktifD6; ?>"><a href="index.php?view=laptagihansiswa"><i class="fa fa-bookmark text-red"></i><span> Lap. Tagihan Siswa</span></a></li>
						<li class="<?php echo $aktifD21; ?>"><a href="index.php?view=lappiutang"><i class="fa fa-bookmark text-red"></i><span> Lap. Piutang Per Jenis Bayar</span></a></li>
						<li class="<?php echo $aktifD12; ?>"><a href="index.php?view=laptransaksitabungan"><i class="fa fa-bookmark text-red"></i><span> Lap.Transaksi Tabungan </span></a></li>

						<li class="<?php echo $aktifD4; ?>"><a href="index.php?view=rekapitulasi"><i class="fa fa-bookmark "></i> <span>Rekapitulasi Pembayaran</span></a></li>
						<li class="<?php echo $aktifD7; ?>"><a href="index.php?view=rekappengeluaran"><i class="fa fa-bookmark "></i> <span>Rekapitulasi Pengeluaran</span></a></li>
						<li class="<?php echo $aktifD7; ?>"><a href="index.php?view=lappembayaranhari"><i class="fa fa-bookmark "></i> <span>Laporan Keuangan Perhari</span></a></li>
						<li class="<?php echo $aktifD9; ?>"><a href="index.php?view=lapbku"><i class="fa fa-bookmark text-green"></i><span> BKU</span></a></li>
						<li class="<?php echo $aktifD10; ?>"><a href="index.php?view=lapbank"><i class="fa fa-bookmark text-green"></i><span> Buku Bank</span></a></li>
						<li class="<?php echo $aktifD11; ?>"><a href="index.php?view=laptunai"><i class="fa fa-bookmark text-green"></i><span> Buku Tunai</span></a></li>

						<li class="<?php echo $aktifD8; ?>"><a href="index.php?view=rekapkondisikeuangan"><i class="fa fa-bookmark text-blue"></i> <span>Kondisi Keuangan</span></a></li>
					</ul>
				</li>
				<li><a href="logout.php"><i class="fa fa-reply-all"></i>Keluar</a></li>

			<?php } ?>
			<?php if ($_SESSION['level'] == 'absen') { ?>
				<li class="treeview <?php echo $aktifabsen; ?>">
					<a href="#">
						<i class="fa fa-calendar"></i>
						<span>Data Absensi </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>

					<ul class="treeview-menu">
						<li class="<?php echo $aktifabsen1; ?>"><a href="index.php?view=absensi_guru"><i class="fa fa-calendar"></i> <span> Absensi Guru</span></a></li>

						<li class="<?php echo $aktifabsen2; ?>"><a href="index.php?view=absensi_guru_rekap"><i class="fa fa-calendar"></i> <span> Rekap Absen Guru</span></a></li>
					</ul>
				</li>
				<li><a href="logout.php"><i class="fa fa-reply-all"></i>Keluar</a></li>

			<?php } ?>
			<?php if ($_SESSION['level'] == 'admin') { ?>
				<li class="<?php echo $aktifHome; ?>"><a href="./"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
				<li class="<?php echo $aktifC; ?>"><a href="index.php?view=pembayaran"><i class="fa fa-money"></i> <span>Pembayaran Siswa</span></a></li>
				<li class="<?php echo $aktifw1; ?>"><a href="index.php?view=whatsapp"><i class="fa fa-send"></i><span> Kirim Tagihan </span></a></li>
				<li class="treeview <?php echo $aktifA; ?>">
					<a href="#">
						<i class="fa fa-book"></i>
						<span>Master Data</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>

					<ul class="treeview-menu">
						<!--	<li class="<?php echo $aktifA1; ?>"><a href="index.php?view=admin"><i class="fa fa-user"></i> Data Pengguna</a></li> -->
						<li class="<?php echo $aktifA2; ?>"><a href="index.php?view=tahun"><i class="fa fa-calendar"></i> Tahun Ajaran</a></li>
						<li class="<?php echo $aktifA8; ?>"><a href="index.php?view=guru"><i class="fa fa-user-secret"></i> Data PTK</a></li>
						<li class="<?php echo $aktifA11; ?>"><a href="index.php?view=sktahunan"><i class="fa fa-book"></i> SK Tahunan PTK</a></li>

						<li class="<?php echo $aktifA3; ?>"><a href="index.php?view=kelas"><i class="fa fa-bank"></i> Kelas</a></li>
						<li class="<?php echo $aktifA4; ?>"><a href="index.php?view=siswa"><i class="fa fa-users"></i> Siswa</a></li>
						<li class="<?php echo $aktifA10; ?>"><a href="index.php?view=siswa_afirmasi"><i class="fa fa-user-plus"></i> Siswa Afirmasi</a></li>

						<li class="<?php echo $aktifA6; ?>"><a href="index.php?view=kenaikankelas"><i class="fa fa-rocket"></i> Kenaikan Kelas</a></li>
						<li class="<?php echo $aktifA5; ?>"><a href="index.php?view=kelulusan"><i class="fa fa-graduation-cap"></i> Kelulusan</a></li>
						<li class="<?php echo $aktifA9; ?>"><a href="index.php?view=setting_absen"><i class="fa fa-cogs"></i> Setting Waktu Absen</a></li>
					</ul>
				</li>

				<li class="treeview <?php echo $aktifakademik; ?>">
					<a href="#">
						<i class="fa fa-tags faa-shake animated"></i>
						<span>Data Akademik </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifakademik0; ?>"><a href="index.php?view=jam"><i class="fa fa-clock-o"></i> Jam</a></li>

						<li class="<?php echo $aktifakademik2; ?>"><a href="index.php?view=matapelajaran"><i class="fa fa-book"></i> Mata Pelajaran</a></li>
						<li class="<?php echo $aktifakademik1; ?>"><a href="index.php?view=jadwalpelajaran"><i class="fa fa-calendar"></i> Jadwal Pelajaran</a></li>
					</ul>
				</li>
				<li class="treeview <?php echo $aktifabsen; ?>">
					<a href="#">
						<i class="fa fa-calendar"></i>
						<span>Data Absensi </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>

					<ul class="treeview-menu">
						<li class="<?php echo $aktifabsen1; ?>"><a href="index.php?view=absensi_guru"><i class="fa fa-calendar"></i> <span> Absensi PTK</span></a></li>

						<li class="<?php echo $aktifabsen2; ?>"><a href="index.php?view=absensi_guru_rekap"><i class="fa fa-calendar"></i> <span> Rekap Absen PTK</span></a></li>
					</ul>
				</li>
				<li class="treeview <?php echo $aktifkepalasekolah; ?>">
					<a href="#">
						<i class="fa fa-tasks faa-shake animated"></i>
						<span>Kepala Sekolah</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifkepalasekolah1; ?>"><a href="index.php?view=program_kerja"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifkepalasekolah2; ?>"><a href="index.php?view=rencana_kegiatan"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifkepalasekolah3; ?>"><a href="index.php?view=realisasi_kegiatan"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifkepalasekolah4; ?>"><a href="index.php?view=monev_kegiatan"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifismubaris; ?>">
					<a href="#">
						<i class="fa fa-tasks faa-shake animated"></i>
						<span>Ismubaris</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifismubaris1; ?>"><a href="index.php?view=program_kerja_ismubaris"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifismubaris2; ?>"><a href="index.php?view=rencana_kegiatan_ismubaris"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifismubaris3; ?>"><a href="index.php?view=realisasi_kegiatan_ismubaris"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifismubaris4; ?>"><a href="index.php?view=monev_kegiatan_ismubaris"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifZ; ?>">
					<a href="#">
						<i class="fa fa-users"></i> <span>Kesiswaan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $aktifZ1; ?>"><a href="index.php?view=prestasi"><i class="fa fa-graduation-cap"></i> Prestasi</a></li>
						<li class="<?php echo $aktifZ2; ?>"><a href="index.php?view=lapprestasi"><i class="fa fa-bar-chart"></i> Rekap Prestasi Siswa</a></li>
						<li class="<?php echo $aktifZ3; ?>"><a href="index.php?view=bk"><i class="fa fa-heartbeat"></i> Pelanggaran</a></li>
						<li class="<?php echo $aktifZ4; ?>"><a href="index.php?view=lapbk"><i class="fa fa-bar-chart"></i> Rekap Pelanggaran Siswa</a></li>
						<li class="<?php echo $aktifZ5; ?>"><a href="index.php?view=tahfidz"><i class="fa fa-book"></i> Tahfidz</a></li>
						<li class="<?php echo $aktifZ8; ?>"><a href="index.php?view=program_kerja_kesiswaan"><i class="fa fa-tasks"></i> Program Kerja</a></li>

						<li class="<?php echo $aktifZ9; ?>"><a href="index.php?view=rencana_kegiatan_kesiswaan"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifZ10; ?>"><a href="index.php?view=realisasi_kegiatan_kesiswaan"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifZ11; ?>"><a href="index.php?view=monev_kegiatan_kesiswaan"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Kurikulum</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $kurikulum1; ?>"><a href="index.php?view=prestasi_ptk"><i class="fa fa-graduation-cap"></i> Data Prestasi PTK</a></li>
						<li class="<?php echo $kurikulum2; ?>"><a href="index.php?view=lapprestasi_ptk"><i class="fa fa-bar-chart"></i> Rekap Prestasi PTK</a></li>
						<li class="<?php echo $kurikulum3; ?>"><a href="index.php?view=bk_ptk"><i class="fa fa-heartbeat"></i> Data Pembinaan PTK </a></li>
						<li class="<?php echo $kurikulum4; ?>"><a href="index.php?view=lapbk_ptk"><i class="fa fa-bar-chart"></i> Rekap Pembinaan PTK</a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="index.php?view=program_kerja_kurikulum"><i class="fa fa-tasks"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum6; ?>"><a href="index.php?view=rencana_kegiatan_kurikulum"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $kurikulum7; ?>"><a href="index.php?view=realisasi_kegiatan_kurikulum"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $kurikulum8; ?>"><a href="index.php?view=monev_kegiatan_kurikulum"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifInventaris; ?>">
					<a href="#">
						<i class="fa fa-archive faa-shake animated"></i>
						<span>Sarana Prasarana </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifInventaris1; ?>"><a href="index.php?view=inventaris"><i class="fa fa-archive"></i> Stok BHP </a></li>
						<li class="<?php echo $aktifInventaris2; ?>"><a href="index.php?view=inventarismasuks"><i class="fa fa-arrow-up"></i> Barang Masuk</a></li>
						<li class="<?php echo $aktifInventaris3; ?>"><a href="index.php?view=inventariskeluar"><i class="fa fa-arrow-up"></i> Barang Keluar</a></li>
						<li class="<?php echo $aktifInventaris4; ?>"><a href="index.php?view=program_kerja_sarpras"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifInventaris5; ?>"><a href="index.php?view=rencana_kegiatan_sarpras"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifInventaris6; ?>"><a href="index.php?view=realisasi_kegiatan_sarpras"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifInventaris7; ?>"><a href="index.php?view=monev_kegiatan_sarpras"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>
						<li class="<?php echo $aktifInventaris8; ?>"><a href="index.php?view=inv_lok_barang"><i class="fa fa-archive"></i> Data Lokasi Barang </a></li>
						<li class="<?php echo $aktifInventaris9; ?>"><a href="index.php?view=inv_sumber_dana"><i class="fa fa-archive"></i> Data Sumber Dana </a></li>
						<li class="<?php echo $aktifInventaris10; ?>"><a href="index.php?view=inv_jenis_barang"><i class="fa fa-archive"></i> Data Jenis Barang </a></li>
						<li class="<?php echo $aktifInventaris11; ?>"><a href="index.php?view=inv_data_barang"><i class="fa fa-archive"></i> Data Barang </a></li>
						<li class="<?php echo $aktifInventaris12; ?>"><a href="index.php?view=inv_data_item"><i class="fa fa-archive"></i> Data Item </a></li>
						<li class="<?php echo $aktifInventaris13; ?>"><a href="index.php?view=inv_pengadaan"><i class="fa fa-archive"></i> Data Pengadaan </a></li>
						<li class="<?php echo $aktifInventaris14; ?>"><a href="index.php?view=inv_penghapusan"><i class="fa fa-archive"></i> Data Penghapusan </a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifhumas; ?>">
					<a href="#">
						<i class="fa fa-archive faa-shake animated"></i>
						<span>Humas </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifhumas1; ?>"><a href="index.php?view=program_kerja_humas"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifhumas2; ?>"><a href="index.php?view=rencana_kegiatan_humas"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifhumas3; ?>"><a href="index.php?view=realisasi_kegiatan_humas"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifhumas4; ?>"><a href="index.php?view=monev_kegiatan_humas"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>
						<li class="<?php echo $aktifhumas5; ?>"><a href="index.php?view=buku_tamu"><i class="fa fa-book"></i> Buku Tamu</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktiftu; ?>">
					<a href="#">
						<i class="fa fa-folder-open faa-shake animated"></i>
						<span>Tata Usaha </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktiftu1; ?>"><a href="index.php?view=program_kerja_tu"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo  $aktiftu2; ?>"><a href="index.php?view=rencana_kegiatan_tu"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktiftu3; ?>"><a href="index.php?view=realisasi_kegiatan_tu"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktiftu4; ?>"><a href="index.php?view=monev_kegiatan_tu"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>

				<li class="treeview <?php echo $aktifM; ?>">
					<a href="#">
						<i class="fa fa-envelope faa-shake animated"></i>
						<span>Data Surat </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifM1; ?>"><a href="index.php?view=surat_masuk"><i class="fa fa-arrow-down"></i> Surat Masuk</a></li>
						<li class="<?php echo $aktifM2; ?>"><a href="index.php?view=surat_keluar"><i class="fa fa-arrow-up"></i> Surat Keluar</a></li>
					</ul>
				</li>
				<li class="treeview <?php echo $aktifB; ?>">
					<a href="#">
						<i class="fa fa-credit-card"></i> <span>Keuangan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifB3; ?>"><a href="index.php?view=bank"><i class="fa fa-bank"></i> Bank</a></li>
						<li class="<?php echo $aktifB1; ?>"><a href="index.php?view=posbayar"><i class="fa fa-credit-card-alt"></i> Pos Bayar</a></li>
						<li class="<?php echo $aktifB2; ?>"><a href="index.php?view=jenisbayar"><i class="fa fa-shekel"></i> Jenis Bayar</a></li>
						<!--	<li class="<?php echo $aktifB4; ?>"><a href="index.php?view=setting_gaji"><i class="fa fa-money"></i> Setting Gaji</a></li> -->
						<li class="<?php echo $aktifB5; ?>"><a href="index.php?view=bayar_gaji"><i class="fa fa-money"></i> Bayar Gaji</a></li>
						<li class="<?php echo $aktifB6; ?>"><a href="index.php?view=bayar_dapen"><i class="fa fa-money"></i> Bayar Dapen</a></li>
						<li class="<?php echo $aktifl; ?>"><a href="index.php?view=hutangtoko"><i class="fa fa-balance-scale "></i> <span> Hutang Piutang</span></a></li>

					</ul>
				</li>

				<li class="treeview <?php echo $aktifm; ?>">
					<a href="#">
						<i class="fa fa-bar-chart"></i> <span>Jurnal Umum</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $aktifm1; ?>"><a href="index.php?view=jurnalumums"><i class="fa fa-bar-chart"></i> <span> Pemasukan Kas</span></a></li>

						<li class="<?php echo $aktifm2; ?>"><a href="index.php?view=jurnalumum"><i class="fa fa-bar-chart"></i> <span> Pengeluaran Kas</span></a></li>

					</ul>
				</li>


				<li class="treeview <?php echo $aktifK; ?>">
					<a href="#">
						<i class="fa fa-leanpub"></i>
						<span>Tabungan Siswa</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $aktifK1; ?>"><a href="index.php?view=nasabah"><i class="fa fa-users "></i><span> Nasabah</span></a></li>
						<li class="<?php echo $aktifK3; ?>"><a href="index.php?view=transaksi"><i class="fa fa-money"></i><span> Tabungan</span></a></li>
						<li class="<?php echo $aktifK5; ?>"><a href="index.php?view=laptransaksi"><i class="fa fa-print "></i><span> Lap.Transaksi </span></a></li>
					</ul>
				</li>
				<!--		<li class="<?php echo $aktifl; ?>"><a href="index.php?view=hutangtoko"><i class="fa fa-balance-scale "></i> <span> Hutang Piutang</span></a></li> -->


				<li class="treeview <?php echo $aktifD; ?>">

					<a href="#">
						<i class="fa fa-print"></i>
						<span>Laporan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifD1; ?>"><a href="index.php?view=lapsiswa"><i class="fa fa-bookmark text-yellow"></i><span> Lap. Data Siswa</span></a></li>
						<li class="<?php echo $aktifD11; ?>"><a href="index.php?view=lapsiswaafirmasi"><i class="fa fa-bookmark text-yellow"></i><span> Lap. Data Siswa Afirmasi</span></a></li>

						<li class="<?php echo $aktifD2; ?>"><a href="index.php?view=lappembayaran"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Kelas</span></a></li>
						<li class="<?php echo $aktifD3; ?>"><a href="index.php?view=lappembayaranperbulan"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Bulan</span></a></li>
						<li class="<?php echo $aktifD5; ?>"><a href="index.php?view=lappembayaranperposbayar"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Pos Bayar</span></a></li>
						<li class="<?php echo $aktifD6; ?>"><a href="index.php?view=laptagihansiswa"><i class="fa fa-bookmark text-red"></i><span> Lap. Tagihan Siswa</span></a></li>
						<li class="<?php echo $aktifD21; ?>"><a href="index.php?view=lappiutang"><i class="fa fa-bookmark text-red"></i><span> Lap. Piutang Per Jenis Bayar</span></a></li>
						<li class="<?php echo $aktifD4; ?>"><a href="index.php?view=rekapitulasi"><i class="fa fa-bookmark "></i> <span>Rekapitulasi Pembayaran</span></a></li>
						<li class="<?php echo $aktifD7; ?>"><a href="index.php?view=rekappengeluaran"><i class="fa fa-bookmark "></i> <span>Rekapitulasi Pengeluaran</span></a></li>
						<li class="<?php echo $aktifD7; ?>"><a href="index.php?view=lappembayaranhari"><i class="fa fa-bookmark "></i> <span>Laporan Keuangan Perhari</span></a></li>
						<li class="<?php echo $aktifD9; ?>"><a href="index.php?view=lapbku"><i class="fa fa-bookmark text-green"></i><span> BKU</span></a></li>
						<li class="<?php echo $aktifD10; ?>"><a href="index.php?view=lapbank"><i class="fa fa-bookmark text-green"></i><span> Buku Bank</span></a></li>
						<li class="<?php echo $aktifD11; ?>"><a href="index.php?view=laptunai"><i class="fa fa-bookmark text-green"></i><span> Buku Tunai</span></a></li>

						<li class="<?php echo $aktifD8; ?>"><a href="index.php?view=rekapkondisikeuangan"><i class="fa fa-bookmark text-blue"></i> <span>Kondisi Keuangan</span></a></li>
					</ul>
				</li>

				<!-- <li class="treeview <?php echo $aktifD; ?>">
	<a href="#">
			<i class="fa fa-database"></i>
			<span>Backup Database </span>
			<i class="fa fa-angle-left pull-right"></i>
		</a>
						<ul class="treeview-menu">
			<li class="<?php echo $aktifZ; ?>"><a href="index.php?view=backup"><i class="fa fa-download"></i><span> Backup Database</span></a></li>
			<li class="<?php echo $aktifZ; ?>"><a href="index.php?view=restore"><i class="fa fa-upload"></i><span> Restore Database</span></a></li>
			
			</ul>
	  </li>-->

				<!--		<li class="<?php echo $aktifh; ?>"><a href="index.php?view=pengaturan"><i class="fa fa-gears"></i> <span>Pengaturan Sekolah</span></a></li> -->
				<li><a href="logout.php"><i class="fa fa-reply-all"></i><span> Keluar </span></a></li>
			<?php } ?>

</section>