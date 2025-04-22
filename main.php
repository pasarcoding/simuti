<?php
if ($_GET['view'] == 'home' or $_GET['view'] == '') {
        echo "<div class='row'>";
        include "admin/home_admin_row.php";
        echo "</div>";
} elseif ($_GET['view'] == 'pengaturan') {

        echo "<div class='row'>";
        include "admin/pengaturan_identitas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'pengaturan_wa') {

        echo "<div class='row'>";
        include "admin/pengaturan_wa.php";
        echo "</div>";
} elseif ($_GET['view'] == 'surat_masuk') {

        echo "<div class='row'>";
        include "admin/surat_masuk.php";
        echo "</div>";
} elseif ($_GET['view'] == 'surat_keluar') {

        echo "<div class='row'>";
        include "admin/surat_keluar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'prestasi') {

        echo "<div class='row'>";
        include "admin/master_prestasi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapprestasi') {
        echo "<div class='row'>";
        include "admin/master_lapprestasi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'bk') {

        echo "<div class='row'>";
        include "admin/master_pelanggaran.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapbk') {

        echo "<div class='row'>";
        include "admin/master_laporanpelanggaran.php";
        echo "</div>";
} elseif ($_GET['view'] == 'setor') {

        echo "<div class='row'>";
        include "admin/com_transaksi/setoran.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapkas') {

        echo "<div class='row'>";
        include "admin/com_laporan/laporan-kas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kasmas') {

        echo "<div class='row'>";
        include "admin/com_kas/masuk.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kaskel') {

        echo "<div class='row'>";
        include "admin/com_kas/keluar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'tagihan1') {
        echo "<div class='row'>";
        include "admin/tagihan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'calendar') {
        echo "<div class='row'>";
        include "admin/calendar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'admin') {

        echo "<div class='row'>";
        include "admin/master_admin.php";
        echo "</div>";
} elseif ($_GET['view'] == 'tahun') {

        echo "<div class='row'>";
        include "admin/master_tahun.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kelas') {

        echo "<div class='row'>";
        include "admin/master_kelas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kamar') {

        echo "<div class='row'>";
        include "admin/master_kamar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'siswa') {

        echo "<div class='row'>";
        include "admin/master_siswa.php";
        echo "</div>";
} elseif ($_GET['view'] == 'siswa_afirmasi') {

        echo "<div class='row'>";
        include "admin/master_siswa_afirmasi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'guru') {

        echo "<div class='row'>";
        include "admin/master_guru.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kelulusan') {

        echo "<div class='row'>";
        include "admin/master_kelulusan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kenaikankelas') {

        echo "<div class='row'>";
        include "admin/master_kenaikankelas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'hutangtoko') {

        echo "<div class='row'>";
        include "admin/hutangtoko.php";
        echo "</div>";
} elseif ($_GET['view'] == 'detailtoko') {

        echo "<div class='row'>";
        include "admin/detailtoko.php";
        echo "</div>";
} elseif ($_GET['view'] == 'posbayar') {

        echo "<div class='row'>";
        include "admin/keuangan_posbayar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'jenisbayar') {

        echo "<div class='row'>";
        include "admin/keuangan_jenisbayar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'setting_gaji') {

        echo "<div class='row'>";
        include "admin/setting_gaji.php";
        echo "</div>";
} elseif ($_GET['view'] == 'bayar_gaji') {

        echo "<div class='row'>";
        include "admin/bayar_gaji.php";
        echo "</div>";
} elseif ($_GET['view'] == 'absensi_guru') {

        echo "<div class='row'>";
        include "admin/absensi_guru.php";
        echo "</div>";
} elseif ($_GET['view'] == 'absensi_guru_rekap') {

        echo "<div class='row'>";
        include "admin/absensi_guru_rekap.php";
        echo "</div>";
} elseif ($_GET['view'] == 'setting_absen') {

        echo "<div class='row'>";
        include "admin/setting_absen.php";
        echo "</div>";
} elseif ($_GET['view'] == 'set_tarif_gaji') {

        echo "<div class='row'>";
        include "admin/setting_tarif_gaji.php";
        echo "</div>";
} elseif ($_GET['view'] == 'tarif' && $_GET['tipe'] == 'bulanan') {

        echo "<div class='row'>";
        include "admin/keuangan_tarif_bulanan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'tarif' && $_GET['tipe'] == 'bebas') {

        echo "<div class='row'>";
        include "admin/keuangan_tarif_bebas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'pembayaran') {

        echo "<div class='row'>";
        include "admin/keuangan_pembayaran.php";
        echo "</div>";
} elseif ($_GET['view'] == 'angsuran') {

        echo "<div class='row'>";
        include "admin/keuangan_pembayaran_bebas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'bayarbulanan') {

        echo "<div class='row'>";
        include "admin/keuangan_pembayaran_bulanan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'jurnalumum') {

        echo "<div class='row'>";
        include "admin/keuangan_jurnalumum.php";
        echo "</div>";
} elseif ($_GET['view'] == 'jurnalumums') {

        echo "<div class='row'>";
        include "admin/keuangan_jurnalumums.php";
        echo "</div>";
} elseif ($_GET['view'] == 'jurnal') {

        echo "<div class='row'>";
        include "admin/keuangan_jurnal.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapsiswa') {

        echo "<div class='row'>";
        include "admin/laporan_siswa.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapsiswaafirmasi') {

        echo "<div class='row'>";
        include "admin/laporan_siswa_afirmasi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lappembayaran') {

        echo "<div class='row'>";
        include "admin/laporan_pembayaran_perkelas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lappiutang') {

        echo "<div class='row'>";
        include "admin/laporan_piutang_perjenisbayar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapbku') {

        echo "<div class='row'>";
        include "admin/laporan_bku.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapbank') {

        echo "<div class='row'>";
        include "admin/laporan_bank.php";
        echo "</div>";
} elseif ($_GET['view'] == 'laptunai') {

        echo "<div class='row'>";
        include "admin/laporan_tunai.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lappembayaranhari') {

        echo "<div class='row'>";
        include "admin/laporan_kondisi_keuangan_perhari.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lappembayaranperbulan') {

        echo "<div class='row'>";
        include "admin/laporan_pembayaran_perbulan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lappembayaranperposbayar') {

        echo "<div class='row'>";
        include "admin/laporan_pembayaran_perposbayar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'laptagihansiswa') {

        echo "<div class='row'>";
        include "admin/laporan_tagihan_siswa.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rekapitulasi') {

        echo "<div class='row'>";
        include "admin/laporan_rekapitulasi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rekappengeluaran') {

        echo "<div class='row'>";
        include "admin/laporan_rekappengeluaran.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rekapkondisikeuangan') {

        echo "<div class='row'>";
        include "admin/laporan_kondisi_keuangan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'backup') {

        echo "<div class='row'>";
        include "admin/backup-datas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'restore') {

        echo "<div class='row'>";
        include "admin/restore.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kelasnya') {

        echo "<div class='row'>";
        include "admin/com_kelas/kelas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'nasabah') {

        echo "<div class='row'>";
        include "admin/com_nasabah/nasabah.php";
        echo "</div>";
} elseif ($_GET['view'] == 'transaksi') {

        echo "<div class='row'>";
        include "admin/com_transaksi/transaksi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'transaksi_yatim') {

        echo "<div class='row'>";
        include "admin/com_transaksi/transaksi_yatim.php";
        echo "</div>";
} elseif ($_GET['view'] == 'laporan-transaksi') {

        echo "<div class='row'>";
        include "admin/com_laporan/laporan-transaksi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'pengaturant') {

        echo "<div class='row'>";
        include "admin/com_pengaturan/pengaturan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'laptransaksinasabah') {

        echo "<div class='row'>";
        include "admin/com_laporan/laporan-nasabah.php";
        echo "</div>";
} elseif ($_GET['view'] == 'laptransaksi') {

        echo "<div class='row'>";
        include "admin/com_laporan/laporan-transaksi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'laptransaksitabungan') {

        echo "<div class='row'>";
        include "admin/com_laporan/laporan-transaksi.php";
        echo "</div>";
} elseif ($_GET['view'] == 'whatsapp') {

        echo "<div class='row'>";
        include "admin/wa.php";
        echo "</div>";
} elseif ($_GET['view'] == 'kesehatan') {

        echo "<div class='row'>";
        include "admin/master_kesehatan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapkesehatan') {

        echo "<div class='row'>";
        include "admin/master_laporankesehatan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapkeg') {

        echo "<div class='row'>";
        include "admin/master_laporankegiatan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'payment') {

        echo "<div class='row'>";
        include "admin/payment.php";
        echo "</div>";
} elseif ($_GET['view'] == 'bank') {

        echo "<div class='row'>";
        include "admin/master_bank.php";
        echo "</div>";
} elseif ($_GET['view'] == 'jadwalpelajaran') {

        echo "<div class='row'>";
        include "admin/master_jadwalpelajaran.php";
        echo "</div>";
} elseif ($_GET['view'] == 'matapelajaran') {

        echo "<div class='row'>";
        include "admin/master_matapelajaran.php";
        echo "</div>";
} elseif ($_GET['view'] == 'jam') {

        echo "<div class='row'>";
        include "admin/master_jam.php";
        echo "</div>";
} elseif ($_GET['view'] == 'bayar_dapen') {

        echo "<div class='row'>";
        include "admin/bayar_dapen.php";
        echo "</div>";
} elseif ($_GET['view'] == 'tahfidz') {

        echo "<div class='row'>";
        include "admin/master_tahfidz.php";
        echo "</div>";
} elseif ($_GET['view'] == 'prestasi_ptk') {

        echo "<div class='row'>";
        include "admin/master_prestasi_ptk.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapprestasi_ptk') {
        echo "<div class='row'>";
        include "admin/master_lapprestasi_ptk.php";
        echo "</div>";
} elseif ($_GET['view'] == 'bk_ptk') {

        echo "<div class='row'>";
        include "admin/master_pelanggaran_ptk.php";
        echo "</div>";
} elseif ($_GET['view'] == 'lapbk_ptk') {

        echo "<div class='row'>";
        include "admin/master_laporanpelanggaran_ptk.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inventaris') {

        echo "<div class='row'>";
        include "admin/master_inventaris.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inventarismasuks') {

        echo "<div class='row'>";
        include "admin/master_inventaris_masuk.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inventariskeluar') {

        echo "<div class='row'>";
        include "admin/master_inventaris_keluar.php";
        echo "</div>";
} elseif ($_GET['view'] == 'sktahunan') {

        echo "<div class='row'>";
        include "admin/master_sk_tahunan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'program_kerja') {

        echo "<div class='row'>";
        include "admin/program_kerja/master_program_kerja.php";
        echo "</div>";
} elseif ($_GET['view'] == 'program_kerja_ismubaris') {

        echo "<div class='row'>";
        include "admin/program_kerja/master_program_kerja_ismubaris.php";
        echo "</div>";
} elseif ($_GET['view'] == 'program_kerja_kesiswaan') {

        echo "<div class='row'>";
        include "admin/program_kerja/master_program_kerja_kesiswaan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'program_kerja_kurikulum') {

        echo "<div class='row'>";
        include "admin/program_kerja/master_program_kerja_kurikulum.php";
        echo "</div>";
} elseif ($_GET['view'] == 'program_kerja_sarpras') {

        echo "<div class='row'>";
        include "admin/program_kerja/master_program_kerja_sarpras.php";
        echo "</div>";
} elseif ($_GET['view'] == 'program_kerja_humas') {

        echo "<div class='row'>";
        include "admin/program_kerja/master_program_kerja_humas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'program_kerja_tu') {

        echo "<div class='row'>";
        include "admin/program_kerja/master_program_kerja_tu.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rencana_kegiatan') {

        echo "<div class='row'>";
        include "admin/rencana_kegiatan/master_rencana_kegiatan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rencana_kegiatan_ismubaris') {

        echo "<div class='row'>";
        include "admin/rencana_kegiatan/master_rencana_kegiatan_ismubaris.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rencana_kegiatan_kesiswaan') {

        echo "<div class='row'>";
        include "admin/rencana_kegiatan/master_rencana_kegiatan_kesiswaan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rencana_kegiatan_kurikulum') {

        echo "<div class='row'>";
        include "admin/rencana_kegiatan/master_rencana_kegiatan_kurikulum.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rencana_kegiatan_sarpras') {

        echo "<div class='row'>";
        include "admin/rencana_kegiatan/master_rencana_kegiatan_sarpras.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rencana_kegiatan_humas') {

        echo "<div class='row'>";
        include "admin/rencana_kegiatan/master_rencana_kegiatan_humas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'rencana_kegiatan_tu') {

        echo "<div class='row'>";
        include "admin/rencana_kegiatan/master_rencana_kegiatan_tu.php";
        echo "</div>";
} elseif ($_GET['view'] == 'realisasi_kegiatan') {

        echo "<div class='row'>";
        include "admin/realisasi_kegiatan/master_realisasi_kegiatan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'realisasi_kegiatan_ismubaris') {

        echo "<div class='row'>";
        include "admin/realisasi_kegiatan/master_realisasi_kegiatan_ismubaris.php";
        echo "</div>";
} elseif ($_GET['view'] == 'realisasi_kegiatan_kesiswaan') {

        echo "<div class='row'>";
        include "admin/realisasi_kegiatan/master_realisasi_kegiatan_kesiswaan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'realisasi_kegiatan_kurikulum') {

        echo "<div class='row'>";
        include "admin/realisasi_kegiatan/master_realisasi_kegiatan_kurikulum.php";
        echo "</div>";
} elseif ($_GET['view'] == 'realisasi_kegiatan_sarpras') {

        echo "<div class='row'>";
        include "admin/realisasi_kegiatan/master_realisasi_kegiatan_sarpras.php";
        echo "</div>";
} elseif ($_GET['view'] == 'realisasi_kegiatan_humas') {

        echo "<div class='row'>";
        include "admin/realisasi_kegiatan/master_realisasi_kegiatan_humas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'realisasi_kegiatan_tu') {

        echo "<div class='row'>";
        include "admin/realisasi_kegiatan/master_realisasi_kegiatan_tu.php";
        echo "</div>";
} elseif ($_GET['view'] == 'monev_kegiatan') {

        echo "<div class='row'>";
        include "admin/monev_kegiatan/master_monev_kegiatan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'monev_kegiatan_ismubaris') {

        echo "<div class='row'>";
        include "admin/monev_kegiatan/master_monev_kegiatan_ismubaris.php";
        echo "</div>";
} elseif ($_GET['view'] == 'monev_kegiatan_kesiswaan') {

        echo "<div class='row'>";
        include "admin/monev_kegiatan/master_monev_kegiatan_kesiswaan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'monev_kegiatan_kurikulum') {

        echo "<div class='row'>";
        include "admin/monev_kegiatan/master_monev_kegiatan_kurikulum.php";
        echo "</div>";
} elseif ($_GET['view'] == 'monev_kegiatan_sarpras') {

        echo "<div class='row'>";
        include "admin/monev_kegiatan/master_monev_kegiatan_sarpras.php";
        echo "</div>";
} elseif ($_GET['view'] == 'monev_kegiatan_humas') {

        echo "<div class='row'>";
        include "admin/monev_kegiatan/master_monev_kegiatan_humas.php";
        echo "</div>";
} elseif ($_GET['view'] == 'monev_kegiatan_tu') {

        echo "<div class='row'>";
        include "admin/monev_kegiatan/master_monev_kegiatan_tu.php";
        echo "</div>";

        //bukutamu
} elseif ($_GET['view'] == 'buku_tamu') {

        echo "<div class='row'>";
        include "admin/master_buku_tamu.php";
        echo "</div>";

        //INVENTARIS
} elseif ($_GET['view'] == 'inv_lok_barang') {

        echo "<div class='row'>";
        include "admin/inv_lok_barang.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inv_sumber_dana') {

        echo "<div class='row'>";
        include "admin/inv_sumber_dana.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inv_jenis_barang') {

        echo "<div class='row'>";
        include "admin/inv_jenis_barang.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inv_data_barang') {

        echo "<div class='row'>";
        include "admin/inv_data_barang.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inv_data_item') {

        echo "<div class='row'>";
        include "admin/inv_data_item.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inv_pengadaan') {

        echo "<div class='row'>";
        include "admin/inv_pengadaan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'inv_penghapusan') {

        echo "<div class='row'>";
        include "admin/inv_penghapusan.php";
        echo "</div>";

        // ARSIP
} elseif ($_GET['view'] == 'arsip_kategori') {

        echo "<div class='row'>";
        include "admin/arsip_kategori.php";
        echo "</div>";
} elseif ($_GET['view'] == 'arsip_data') {

        echo "<div class='row'>";
        include "admin/arsip_data.php";
        echo "</div>";
        //ppdb
} elseif ($_GET['view'] == 'ppdb_slider') {

        echo "<div class='row'>";
        include "admin/master_ppdb_slider.php";
        echo "</div>";
} elseif ($_GET['view'] == 'ppdb_jalur') {

        echo "<div class='row'>";
        include "admin/master_ppdb_jalur.php";
        echo "</div>";
} elseif ($_GET['view'] == 'ppdb_gelombang') {

        echo "<div class='row'>";
        include "admin/master_ppdb_gelombang.php";
        echo "</div>";
} elseif ($_GET['view'] == 'daftar_titipan') {

        echo "<div class='row'>";
        include "admin/master_ppdb_titipan.php";
        echo "</div>";
} elseif ($_GET['view'] == 'data petugas') {

        echo "<div class='row'>";
        include "admin/master_ppdb_data_petugas.php";
        echo "</div>";
}
