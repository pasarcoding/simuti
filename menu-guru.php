<section class="sidebar">
	<font face="Poppins">
		<!-- Sidebar user panel -->
		<div class="user-panel">
			<div class="pull-left image">
				<img src="<?php echo $foto; ?>" class="img-circle" alt="User Image">
			</div>
			<div class="pull-left info">
				<p><?php
					$potongan = substr($nama, 0, 16);
					// Menambahkan "..."
					$potongan_dengan_titik = $potongan . "...";
					echo $potongan_dengan_titik;
					?></p>
				<a href="#"><i class="fa fa-circle text-success"></i>Tahun Ajaran : <?= $ta['nmTahunAjaran']; ?></a>
			</div>
		</div>

		<!-- sidebar menu: : style can be found in sidebar.less -->
		<ul class="sidebar-menu">
			<li class="header">MAIN NAVIGATION</li>
			<li class="<?php echo $aktifs; ?>"><a href="index-guru.php?view=homeguru"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
			<li><a href="index-guru.php?view=absenguru&tahun=<?= $ta['idTahunAjaran']; ?>"><i class="fa fa-bell"></i> <span>Absensi </span></a></li>
			<li><a href="index-guru.php?view=absengurus&tahun=<?= $ta['idTahunAjaran']; ?>"><i class="fa fa-bookmark"></i> <span>Pengajuan Izin</span></a></li>
			<li><a href="index-guru.php?view=jadwalpelajaran"><i class="fa fa-calendar"></i> <span>Jadwal Pelajaran</span></a></li>
			<li><a href="index-guru.php?view=gaji-saya"><i class="fa fa-money"></i> <span>Gaji Saya</span></a></li>
			<li><a href="index-guru.php?view=bayar_dapen"><i class="fa fa-money"></i> <span>Dapen Saya</span></a></li>
			<li><a href="index-guru.php?view=sktahunan"><i class="fa fa-book"></i> <span>SK Saya</span></a></li>
			<li><a href="index-guru.php?view=tatib"><i class="fa fa-book"></i> <span>Tata Tertib PTK</span></a></li>
			<li><a href="index-guru.php?view=jadwal_srg"><i class="fa fa-bookmark"></i> Jadwal Seragam PTK</a></li>
			<?php if ($_SESSION['tugas'] == '0') { ?>

				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Kepala Sekolah</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja"><i class="fa fa-circle-o"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=rencana_kegiatan"><i class="fa fa-circle-o"></i> Rencana Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=realisasi_kegiatan"><i class="fa fa-circle-o"></i> Realisasi Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=approval_kegiatan"><i class="fa fa-circle-o"></i> Approval Kegiatan </a></li>

						<li class="<?php echo $kurikulum5; ?>"><a href="?view=monev_kegiatan"><i class="fa fa-circle-o"></i> Monev Kegiatan </a></li>
					</ul>
				</li>

				<li class="treeview <?php echo $aktifakademik; ?>">
					<a href="#">
						<i class="fa fa-tags faa-shake animated"></i>
						<span>Data Akademik </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifakademik0; ?>"><a href="?view=jam_all"><i class="fa fa-clock-o"></i> Jam</a></li>

						<li class="<?php echo $aktifakademik2; ?>"><a href="?view=matapelajaran_all"><i class="fa fa-book"></i> Mata Pelajaran</a></li>
						<li class="<?php echo $aktifakademik1; ?>"><a href="?view=jadwalpelajaran_all&class=&tahun="><i class="fa fa-calendar"></i> Jadwal Pelajaran</a></li>
					</ul>
				</li>
				<li class="treeview <?php echo $aktifZ; ?>">
					<a href="#">
						<i class="fa fa-users"></i> <span>Kesiswaan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifZ2; ?>"><a href="?view=lapprestasi"><i class="fa fa-bar-chart"></i> Rekap Prestasi Siswa</a></li>
						<li class="<?php echo $aktifZ4; ?>"><a href="?view=lapbk"><i class="fa fa-bar-chart"></i> Rekap Pelanggaran Siswa</a></li>
						<!--	<li class="<?php echo $aktifZ6; ?>"><a href="?view=lapkesehatan"><i class="fa fa-bar-chart"></i> Rekap Kesehatan Siswa</a></li> -->
					</ul>
				</li>
				<li class="treeview <?php echo $aktifInventaris; ?>">

					<a href="#">
						<i class="fa fa-calendar"></i> <span>Absensi PTK</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $aktifabsen1; ?>"><a href="?view=absensi_guru_kepsek"><i class="fa fa-calendar"></i> <span> Absensi PTK</span></a></li>
						<li class="<?php echo $aktifabsen2; ?>"><a href="?view=absensi_guru_rekap"><i class="fa fa-calendar"></i> <span> Rekap Absen PTK</span></a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifM; ?>">
					<a href="#">
						<i class="fa fa-envelope faa-shake animated"></i>
						<span>Data Surat </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifM1; ?>"><a href="?view=surat_masuk"><i class="fa fa-arrow-down"></i> Surat Masuk</a></li>
						<li class="<?php echo $aktifM2; ?>"><a href="?view=surat_keluar"><i class="fa fa-arrow-up"></i> Surat Keluar</a></li>
					</ul>
				</li>


				<li class="treeview <?php echo $aktifInventaris; ?>">
					<a href="#">
						<i class="fa fa-money faa-shake animated"></i>
						<span>Keuangan </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifInventaris1; ?>"><a href="?view=bayar_gaji"><i class="fa fa-arrow-down"></i> Bayar Gaji </a></li>
						<li class="<?php echo $aktifInventaris2; ?>"><a href="?view=bayar_dapen_all"><i class="fa fa-arrow-up"></i> Bayar Dapen</a></li>
						<li class="<?php echo $aktifl; ?>"><a href="?view=hutang"><i class="fa fa-balance-scale "></i> <span> Hutang Piutang</span></a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifK; ?>">
					<a href="#">
						<i class="fa fa-leanpub"></i>
						<span>Tabungan Siswa</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $aktifK1; ?>"><a href="?view=nasabah"><i class="fa fa-users "></i><span> Nasabah</span></a></li>
						<li class="<?php echo $aktifK5; ?>"><a href="?view=laptransaksi"><i class="fa fa-print "></i><span> Lap.Transaksi </span></a></li>
					</ul>
				</li>
				<li class="treeview <?php echo $aktifismubaris; ?>">
					<a href="#">
						<i class="fa fa-tasks faa-shake animated"></i>
						<span>Ismubaris</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifismubaris1; ?>"><a href="?view=program_kerja_ismubaris"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifismubaris2; ?>"><a href="?view=rencana_kegiatan_ismubaris"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifismubaris3; ?>"><a href="?view=realisasi_kegiatan_ismubaris"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifismubaris4; ?>"><a href="?view=monev_kegiatan_ismubaris"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktifInventaris; ?>">
					<a href="#">
						<i class="fa fa-envelope faa-shake animated"></i>
						<span>Sarana Prasarana </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifInventaris1; ?>"><a href="?view=inventaris_kepsek"><i class="fa fa-arrow-down"></i> Stok BHP </a></li>
						<li class="<?php echo $aktifInventaris2; ?>"><a href="?view=inventarismasuks_kepsek"><i class="fa fa-arrow-up"></i> Barang Masuk</a></li>
						<li class="<?php echo $aktifInventaris3; ?>"><a href="?view=inventariskeluar_kepsek"><i class="fa fa-arrow-up"></i> Barang Keluar</a></li>
						<li class="<?php echo $aktifInventaris4; ?>"><a href="?view=program_kerja_sarpras"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifInventaris5; ?>"><a href="?view=rencana_kegiatan_sarpras"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifInventaris6; ?>"><a href="?view=realisasi_kegiatan_sarpras"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifInventaris7; ?>"><a href="?view=monev_kegiatan_sarpras"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>
						<li class="<?php echo $aktifInventaris11; ?>"><a href="?view=inv_data_barang_kepsek"><i class="fa fa-archive"></i> Data Barang </a></li>
						<li class="<?php echo $aktifInventaris12; ?>"><a href="?view=inv_data_item_kepsek"><i class="fa fa-archive"></i> Data Item </a></li>
						<li class="<?php echo $aktifInventaris13; ?>"><a href="?view=inv_pengadaan_kepsek"><i class="fa fa-archive"></i> Data Pengadaan </a></li>
						<li class="<?php echo $aktifInventaris14; ?>"><a href="?view=inv_penghapusan_kepsek"><i class="fa fa-archive"></i> Data Penghapusan </a></li>
					</ul>
				</li>
				<li class="treeview <?php echo $aktifZ; ?>">
					<a href="#">
						<i class="fa fa-users"></i> <span>Kesiswaan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $aktifZ1; ?>"><a href="?view=prestasi"><i class="fa fa-graduation-cap"></i> Prestasi</a></li>
						<li class="<?php echo $aktifZ2; ?>"><a href="?view=lapprestasi"><i class="fa fa-bar-chart"></i> Rekap Prestasi Siswa</a></li>
						<li class="<?php echo $aktifZ3; ?>"><a href="?view=bk"><i class="fa fa-heartbeat"></i> Pelanggaran</a></li>
						<li class="<?php echo $aktifZ4; ?>"><a href="?view=lapbk"><i class="fa fa-bar-chart"></i> Rekap Pelanggaran Siswa</a></li>
						<li class="<?php echo $aktifZ5; ?>"><a href="?view=tahfidz"><i class="fa fa-book"></i> Tahfidz</a></li>
						<li class="<?php echo $aktifZ8; ?>"><a href="?view=program_kerja_kesiswaan"><i class="fa fa-tasks"></i> Program Kerja</a></li>

						<li class="<?php echo $aktifZ9; ?>"><a href="?view=rencana_kegiatan_kesiswaan"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifZ10; ?>"><a href="?view=realisasi_kegiatan_kesiswaan"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifZ11; ?>"><a href="?view=monev_kegiatan_kesiswaan"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Kurikulum</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">

						<li class="<?php echo $kurikulum1; ?>"><a href="?view=prestasi_ptk"><i class="fa fa-graduation-cap"></i> Data Prestasi PTK</a></li>
						<li class="<?php echo $kurikulum2; ?>"><a href="?view=lapprestasi_ptk"><i class="fa fa-bar-chart"></i> Rekap Prestasi PTK</a></li>
						<li class="<?php echo $kurikulum3; ?>"><a href="?view=bk_ptk"><i class="fa fa-heartbeat"></i> Data Pembinaan PTK </a></li>
						<li class="<?php echo $kurikulum4; ?>"><a href="?view=lapbk_ptk"><i class="fa fa-bar-chart"></i> Rekap Pembinaan PTK</a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja_kurikulum"><i class="fa fa-tasks"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum6; ?>"><a href="?view=rencana_kegiatan_kurikulum"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $kurikulum7; ?>"><a href="?view=realisasi_kegiatan_kurikulum"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $kurikulum8; ?>"><a href="?view=monev_kegiatan_kurikulum"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>

					</ul>
				</li>

				<li class="treeview <?php echo $aktifhumas; ?>">
					<a href="#">
						<i class="fa fa-archive faa-shake animated"></i>
						<span>Humas </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifhumas1; ?>"><a href="?view=program_kerja_humas"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo $aktifhumas2; ?>"><a href="?view=rencana_kegiatan_humas"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktifhumas3; ?>"><a href="?view=realisasi_kegiatan_humas"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktifhumas4; ?>"><a href="?view=monev_kegiatan_humas"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>
						<li class="<?php echo $aktifhumas5; ?>"><a href="?view=buku_tamu"><i class="fa fa-book"></i> Buku Tamu</a></li>

					</ul>
				</li>
				<li class="treeview <?php echo $aktiftu; ?>">
					<a href="#">
						<i class="fa fa-folder-open faa-shake animated"></i>
						<span>Tata Usaha </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktiftu1; ?>"><a href="?view=program_kerja_tu"><i class="fa fa-tasks"></i> Program Kerja</a></li>
						<li class="<?php echo  $aktiftu2; ?>"><a href="?view=rencana_kegiatan_tu"><i class="fa fa-tasks"></i> Rencana Kegiatan</a></li>
						<li class="<?php echo $aktiftu3; ?>"><a href="?view=realisasi_kegiatan_tu"><i class="fa fa-tasks"></i> Realisasi Kegiatan</a></li>
						<li class="<?php echo $aktiftu4; ?>"><a href="?view=monev_kegiatan_tu"><i class="fa fa-tasks"></i> Monev Kegiatan</a></li>
						<li class="<?php echo $aktiftu6; ?>"><a href="?view=arsip_kepsek"><i class="fa fa-briefcase"></i> Data Arsip </a></li>

					</ul>
				</li>
				<li class="<?php echo $aktifA8; ?>"><a href="?view=guru"><i class="fa fa-user-secret"></i> Data PTK</a></li>
				<li><a href="index-guru.php?view=sktahunan_all"><i class="fa fa-book"></i> <span>SK Tahunan</span></a></li>


				<li class="treeview <?php echo $aktifD; ?>">

					<a href="#">
						<i class="fa fa-print"></i>
						<span>Laporan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifD1; ?>"><a href="?view=lapsiswa"><i class="fa fa-bookmark text-yellow"></i><span> Lap. Data Siswa</span></a></li>
						<li class="<?php echo $aktifD11; ?>"><a href="?view=lapsiswaafirmasi"><i class="fa fa-bookmark text-yellow"></i><span> Lap. Data Siswa Afirmasi</span></a></li>

						<li class="<?php echo $aktifD2; ?>"><a href="?view=lappembayaran"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Kelas</span></a></li>
						<li class="<?php echo $aktifD3; ?>"><a href="?view=lappembayaranperbulan"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Bulan</span></a></li>
						<li class="<?php echo $aktifD5; ?>"><a href="?view=lappembayaranperposbayar"><i class="fa fa-bookmark text-green"></i><span> Lap. Pemb. Per Pos Bayar</span></a></li>
						<li class="<?php echo $aktifD6; ?>"><a href="?view=laptagihansiswa"><i class="fa fa-bookmark text-red"></i><span> Lap. Tagihan Siswa</span></a></li>
						<li class="<?php echo $aktifD21; ?>"><a href="?view=lappiutang"><i class="fa fa-bookmark text-red"></i><span> Lap. Piutang Per Jenis Bayar</span></a></li>
						<li class="<?php echo $aktifD12; ?>"><a href="?view=laptransaksitabungan"><i class="fa fa-bookmark text-red"></i><span> Lap.Transaksi Tabungan </span></a></li>

						<li class="<?php echo $aktifD4; ?>"><a href="?view=rekapitulasi"><i class="fa fa-bookmark "></i> <span>Rekapitulasi Pembayaran</span></a></li>
						<li class="<?php echo $aktifD7; ?>"><a href="?view=rekappengeluaran"><i class="fa fa-bookmark "></i> <span>Rekapitulasi Pengeluaran</span></a></li>
						<li class="<?php echo $aktifD7; ?>"><a href="?view=lappembayaranhari"><i class="fa fa-bookmark "></i> <span>Laporan Keuangan Perhari</span></a></li>
						<li class="<?php echo $aktifD9; ?>"><a href="?view=lapbku"><i class="fa fa-bookmark text-green"></i><span> BKU</span></a></li>
						<li class="<?php echo $aktifD10; ?>"><a href="?view=lapbank"><i class="fa fa-bookmark text-green"></i><span> Buku Bank</span></a></li>
						<li class="<?php echo $aktifD11; ?>"><a href="?view=laptunai"><i class="fa fa-bookmark text-green"></i><span> Buku Tunai</span></a></li>

						<li class="<?php echo $aktifD8; ?>"><a href="?view=rekapkondisikeuangan"><i class="fa fa-bookmark text-blue"></i> <span>Kondisi Keuangan</span></a></li>
					</ul>
				</li>
				<!--kurikulum-->
			<?php } else if ($_SESSION['tugas'] == '2') { ?>

				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Kurikulum</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja"><i class="fa fa-circle-o"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=rencana_kegiatan"><i class="fa fa-circle-o"></i> Rencana Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=realisasi_kegiatan"><i class="fa fa-circle-o"></i> Realisasi Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=monev_kegiatan"><i class="fa fa-circle-o"></i> Monev Kegiatan </a></li>
						<li class="<?php echo $kurikulum1; ?>"><a href="?view=prestasi_ptk"><i class="fa fa-graduation-cap"></i> Data Prestasi PTK</a></li>
						<li class="<?php echo $kurikulum2; ?>"><a href="?view=lapprestasi_ptk"><i class="fa fa-bar-chart"></i> Rekap Prestasi PTK</a></li>
						<li class="<?php echo $kurikulum3; ?>"><a href="?view=bk_ptk"><i class="fa fa-heartbeat"></i> Data Pembinaan PTK </a></li>
						<li class="<?php echo $kurikulum4; ?>"><a href="?view=lapbk_ptk"><i class="fa fa-bar-chart"></i> Rekap Pembinaan PTK</a></li>
					</ul>
				</li>

				<li class="treeview <?php echo $aktifakademik; ?>">
					<a href="#">
						<i class="fa fa-tags faa-shake animated"></i>
						<span>Data Akademik </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $aktifakademik0; ?>"><a href="?view=jam"><i class="fa fa-clock-o"></i> Jam</a></li>

						<li class="<?php echo $aktifakademik2; ?>"><a href="?view=matapelajaran"><i class="fa fa-circle-o"></i> Mata Pelajaran</a></li>
						<li class="<?php echo $aktifakademik1; ?>"><a href="?view=jadwalpelajaran_kurikulum"><i class="fa fa-circle-o"></i> Jadwal Pelajaran</a></li>
					</ul>
				</li>
				<li class="treeview <?php echo $aktifabsen; ?>">
					<a href="#">
						<i class="fa fa-calendar"></i>
						<span>Data Absensi </span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>

					<ul class="treeview-menu">
						<li class="<?php echo $aktifabsen1; ?>"><a href="?view=absensi_guru"><i class="fa fa-calendar"></i> <span> Absensi PTK</span></a></li>

						<li class="<?php echo $aktifabsen2; ?>"><a href="?view=absensi_guru_rekap"><i class="fa fa-calendar"></i> <span> Rekap Absen PTK</span></a></li>
					</ul>
				</li>
				<!--humas-->
			<?php } else if ($_SESSION['tugas'] == '4') { ?>
				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Humas</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja"><i class="fa fa-circle-o"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=rencana_kegiatan"><i class="fa fa-circle-o"></i> Rencana Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=realisasi_kegiatan"><i class="fa fa-circle-o"></i> Realisasi Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=monev_kegiatan"><i class="fa fa-circle-o"></i> Monev Kegiatan </a></li>
						<li class="<?php echo $aktifhumas5; ?>"><a href="?view=buku_tamu"><i class="fa fa-circle-o"></i> Buku Tamu</a></li>

					</ul>
				</li>
				<!--kesiswaan-->
			<?php } else if ($_SESSION['tugas'] == '3') { ?>
				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Kesiswaan</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja"><i class="fa fa-circle-o"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=rencana_kegiatan"><i class="fa fa-circle-o"></i> Rencana Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=realisasi_kegiatan"><i class="fa fa-circle-o"></i> Realisasi Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=monev_kegiatan"><i class="fa fa-circle-o"></i> Monev Kegiatan </a></li>
						<li class="<?php echo $aktifZ1; ?>"><a href="?view=prestasi"><i class="fa fa-circle-o"></i> Prestasi</a></li>
						<li class="<?php echo $aktifZ2; ?>"><a href="?view=lapprestasi"><i class="fa fa-circle-o"></i> Rekap Prestasi Siswa</a></li>
						<li class="<?php echo $aktifZ3; ?>"><a href="?view=bk"><i class="fa fa-circle-o"></i> Pelanggaran</a></li>
						<li class="<?php echo $aktifZ4; ?>"><a href="?view=lapbk"><i class="fa fa-circle-o"></i> Rekap Pelanggaran Siswa</a></li>
						<li class="<?php echo $aktifZ5; ?>"><a href="?view=tahfidz"><i class="fa fa-circle-o"></i> Tahfidz</a></li>
						<li class="<?php echo $aktifD1; ?>"><a href="?view=lapsiswa"><i class="fa fa-circle-o"></i><span> Lap. Data Siswa</span></a></li>
						<li class="<?php echo $aktifD11; ?>"><a href="?view=lapsiswaafirmasi"><i class="fa fa-circle-o"></i><span> Lap. Data Siswa Afirmasi</span></a></li>




					</ul>
				</li>
				<!--sarpras-->
			<?php } else if ($_SESSION['tugas'] == '5') { ?>
				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Sarana Prasarana</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja"><i class="fa fa-circle-o"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=rencana_kegiatan"><i class="fa fa-circle-o"></i> Rencana Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=realisasi_kegiatan"><i class="fa fa-circle-o"></i> Realisasi Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=monev_kegiatan"><i class="fa fa-circle-o"></i> Monev Kegiatan </a></li>
						<li class="<?php echo $aktifInventaris1; ?>"><a href="?view=inventaris"><i class="fa fa-circle-o"></i> Stok BHP </a></li>
						<li class="<?php echo $aktifInventaris2; ?>"><a href="?view=inventarismasuks"><i class="fa fa-circle-o"></i> Barang Masuk</a></li>
						<li class="<?php echo $aktifInventaris3; ?>"><a href="?view=inventariskeluar"><i class="fa fa-circle-o"></i> Barang Keluar</a></li>
						<li class="<?php echo $aktifInventaris9; ?>"><a href="?view=inv_sumber_dana"><i class="fa fa-archive"></i> Data Sumber Dana </a></li>
						<li class="<?php echo $aktifInventaris10; ?>"><a href="?view=inv_jenis_barang"><i class="fa fa-archive"></i> Data Jenis Barang </a></li>
						<li class="<?php echo $aktifInventaris11; ?>"><a href="?view=inv_data_barang"><i class="fa fa-archive"></i> Data Barang </a></li>
						<li class="<?php echo $aktifInventaris12; ?>"><a href="?view=inv_data_item"><i class="fa fa-archive"></i> Data Item </a></li>
						<li class="<?php echo $aktifInventaris13; ?>"><a href="?view=inv_pengadaan"><i class="fa fa-archive"></i> Data Pengadaan </a></li>
						<li class="<?php echo $aktifInventaris14; ?>"><a href="?view=inv_penghapusan"><i class="fa fa-archive"></i> Data Penghapusan </a></li>
					</ul>
				</li>
			<?php } else if ($_SESSION['tugas'] == '14') { ?>
				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Ismubaris</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja"><i class="fa fa-circle-o"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=rencana_kegiatan"><i class="fa fa-circle-o"></i> Rencana Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=realisasi_kegiatan"><i class="fa fa-circle-o"></i> Realisasi Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=monev_kegiatan"><i class="fa fa-circle-o"></i> Monev Kegiatan </a></li>
					</ul>
				</li>
			<?php } else if ($_SESSION['tugas'] == '10') { ?>
				<li class="treeview <?php echo $kurikulum; ?>">
					<a href="#">
						<i class="fa fa-user-plus"></i> <span>Tata Usaha</span>
						<i class="fa fa-angle-left pull-right"></i>
					</a>
					<ul class="treeview-menu">
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=program_kerja"><i class="fa fa-circle-o"></i> Program Kerja </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=rencana_kegiatan"><i class="fa fa-circle-o"></i> Rencana Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=realisasi_kegiatan"><i class="fa fa-circle-o"></i> Realisasi Kegiatan </a></li>
						<li class="<?php echo $kurikulum5; ?>"><a href="?view=monev_kegiatan"><i class="fa fa-circle-o"></i> Monev Kegiatan </a></li>
						<li class="<?php echo $aktiftu5; ?>"><a href="?view=arsip_kategori"><i class="fa fa-briefcase"></i> Kategori Arsip </a></li>
						<li class="<?php echo $aktiftu6; ?>"><a href="?view=arsip_data"><i class="fa fa-briefcase"></i> Data Arsip </a></li>
					</ul>
				</li>
			<?php } ?>
			<!--	<li class="<?php echo $aktifs; ?>"><a href="index-guru.php?view=masterguru&act=editguru&id=<?php echo $_SESSION['nips']; ?>"><i class="fa fa-wrench "></i><span>Edit Profile</span></a></li> -->
			<li><a href="logout.php"><i class="fa fa-reply-all"></i><span>Keluar</span></a></li>
</section>