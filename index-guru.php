<?php
session_start();
error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
include "config/koneksi.php";
include "config/library.php";
include "config/fungsi_indotgl.php";
include "config/excel_reader.php";
include "config/fungsi_seo.php";
include "config/fungsi_thumb.php";
include "config/variabel_default.php";
include "config/fungsi_wa.php";

if (isset($_SESSION[idg])) {

  $iden = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM rb_guru where id='$_SESSION[nips]'"));
  $nama =  $iden[nama_guru];
  $level = 'Guru';
  $foto = 'foto_pegawai/' . $iden['foto'];

  $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));
  $sqlPolygon = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM rb_absensi_setting WHERE type='polygonSetting'"));
  $listPolygon = base64_encode(json_encode(isset($sqlPolygon) ? json_decode($sqlPolygon['value']) : []));
  $ta = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM tahun_ajaran where aktif='Y'"));
?>
  <!DOCTYPE html>
  <html>

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SIMuti | SD Muhammadiyah 3</title>

    <link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kanan']; ?>">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="./assets/font-awesome-4.6.3/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="./assets/ionicons/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables/dataTables.bootstrap.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="dist/css/skins/_all-skins.min.css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/iCheck/flat/blue.css">
    <!-- Morris chart -->
    <link rel="stylesheet" href="plugins/morris/morris.css">
    <!-- jvectormap -->
    <link rel="stylesheet" href="plugins/jvectormap/jquery-jvectormap-1.2.2.css">
    <!-- Date Picker -->
    <link rel="stylesheet" href="plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="plugins/datetimepicker/bootstrap-datetimepicker.css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker-bs3.css">
    <!-- Bootstrap Select -->
    <link rel="stylesheet" href="assets/bootstrap-select/css/bootstrap-select.min.css" />
    <!-- bootstrap wysihtml5 - text editor -->
    <link rel="stylesheet" href="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css" integrity="sha512-puBpdR0798OZvTTbP4A8Ix/l+A4dHDD0DGqYW6RQ+9jxkRFclaxxQb/SJAWZfWAkuyeQUytO7+7N4QKrDh+drA==" crossorigin="" />
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://kit.fontawesome.com/049c89ac09.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="plugins/fullcalendar/fullcalendar.css">

    <style type="text/css">
      .files {
        position: absolute;
        z-index: 2;
        top: 0;
        left: 0;
        filter: alpha(opacity=0);
        -ms-filter: "progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";
        opacity: 0;
        background-color: transparent;
        color: transparent;
      }
    </style>
    <style type="text/css">
      #menu-bottom.nav {
        z-index: 999999;
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 55px;
        box-shadow: 0 0 3px rgba(0, 0, 0, 0.2);
        background-color: #ffffff;
        display: flex;
        overflow-x: auto;
      }

      #menu-bottom .nav__link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        flex-grow: 1;
        min-width: 50px;
        overflow: hidden;
        white-space: nowrap;
        font-family: sans-serif;
        font-size: 13px;
        color: #444444;
        text-decoration: none;
        -webkit-tap-highlight-color: transparent;
        transition: background-color 0.1s ease-in-out;
      }

      #menu-bottom .nav__link:hover {
        background-color: #eeeeee;
      }

      #menu-bottom .nav__link--active {
        color: #009578;
      }

      #nav-icon {
        font-size: 18px;
      }

      @media (min-width: 1102px) {
        #menu-bottom.nav {
          display: none !important;
        }

      }
    </style>
    <script type="text/javascript" src="plugins/jQuery/jquery-1.12.3.min.js"></script>
    <script language="javascript" type="text/javascript">
      var maxAmount = 160;

      function textCounter(textField, showCountField) {
        if (textField.value.length > maxAmount) {
          textField.value = textField.value.substring(0, maxAmount);
        } else {
          showCountField.value = maxAmount - textField.value.length;
        }
      }
    </script>
    <script type="text/javascript" src="getDataCombo.js"></script>

  </head>

  <body class="hold-transition skin-yellow sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <?php include "main-headerg.php"; ?>
      </header>

      <aside class="main-sidebar">
        <?php
        include "menu-guru.php";
        ?>
      </aside>

      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            <?php echo $judul; ?>
          </h1>
        </section>

        <section class="content">
          <?php if ($_GET[view] == 'home') {
            echo "<div class='row'>";
            include "guru/home_admin_row.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'absengurus') {

            echo "<div class='row'>";
            include "guru/absensi_guru_non_radius.php";
            echo "</div>";
          } elseif ($_GET[view] == 'masterguru') {

            echo "<div class='row'>";
            include "guru/master_guru.php";
            echo "</div>";
          } elseif ($_GET[view] == 'gaji-saya') {

            echo "<div class='row'>";
            include "guru/bayar_gaji.php";
            echo "</div>";
          } elseif ($_GET[view] == 'bayar_dapen') {

            echo "<div class='row'>";
            include "guru/bayar_dapen.php";
            echo "</div>";
          } elseif ($_GET[view] == 'homeguru') {

            echo "<div class='row'>";
            include "guru/home_guru.php";
            echo "</div>";
          } elseif ($_GET[view] == 'jadwalpelajaran') {

            echo "<div class='row'>";
            include "guru/master_jadwalpelajaran.php";
            echo "</div>";
          } elseif ($_GET[view] == 'absenguru') {

            echo "<div class='row'>";
            include "guru/absensi_guru.php";
            echo "</div>";
          } elseif ($_GET[view] == 'absengurupulang') {

            echo "<div class='row'>";
            include "guru/absensi_pulang_guru.php";
            echo "</div>";
          } elseif ($_GET[view] == 'matapelajaran') {

            echo "<div class='row'>";
            include "guru/master_matapelajaran.php";
            echo "</div>";
          } elseif ($_GET[view] == 'tatib') {

            echo "<div class='row'>";
            include "guru/tatib.php";
            echo "</div>";
          } elseif ($_GET[view] == 'jadwal_srg') {

            echo "<div class='row'>";
            include "guru/jadwal_srg.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'sktahunan') {

            echo "<div class='row'>";
            include "guru/master_sk_tahunan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja') {

            echo "<div class='row'>";
            include "guru/master_program_kerja.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan') {

            echo "<div class='row'>";
            include "guru/master_rencana_kegiatan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan') {

            echo "<div class='row'>";
            include "guru/master_realisasi_kegiatan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan') {

            echo "<div class='row'>";
            include "guru/master_monev_kegiatan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'approval_kegiatan') {

            echo "<div class='row'>";
            include "guru/master_approval_kegiatan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'prestasi') {

            echo "<div class='row'>";
            include "guru/master_prestasi.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapprestasi') {
            echo "<div class='row'>";
            include "guru/master_lapprestasi.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'bk') {

            echo "<div class='row'>";
            include "guru/master_pelanggaran.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapbk') {

            echo "<div class='row'>";
            include "guru/master_laporanpelanggaran.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'siswa') {

            echo "<div class='row'>";
            include "guru/master_siswa.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'siswa_afirmasi') {

            echo "<div class='row'>";
            include "guru/master_siswa_afirmasi.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapsiswa') {

            echo "<div class='row'>";
            include "admin/laporan_siswa.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapsiswaafirmasi') {

            echo "<div class='row'>";
            include "admin/laporan_siswa_afirmasi.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'tahfidz') {

            echo "<div class='row'>";
            include "guru/master_tahfidz.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'prestasi_ptk') {

            echo "<div class='row'>";
            include "guru/master_prestasi_ptk.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapprestasi_ptk') {
            echo "<div class='row'>";
            include "guru/master_lapprestasi_ptk.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'bk_ptk') {

            echo "<div class='row'>";
            include "guru/master_pelanggaran_ptk.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapbk_ptk') {

            echo "<div class='row'>";
            include "guru/master_laporanpelanggaran_ptk.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'jam') {

            echo "<div class='row'>";
            include "guru/master_jam.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'jadwalpelajaran') {

            echo "<div class='row'>";
            include "guru/master_jadwalpelajaran.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'matapelajaran') {

            echo "<div class='row'>";
            include "guru/master_matapelajaran.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'absensi_guru') {

            echo "<div class='row'>";
            include "admin/absensi_guru.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'absensi_guru_rekap') {

            echo "<div class='row'>";
            include "admin/absensi_guru_rekap.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inventaris') {

            echo "<div class='row'>";
            include "guru/master_inventaris.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inventarismasuks') {

            echo "<div class='row'>";
            include "guru/master_inventaris_masuk.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inventariskeluar') {

            echo "<div class='row'>";
            include "guru/master_inventaris_keluar.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'buku_tamu') {

            echo "<div class='row'>";
            include "guru/master_buku_tamu.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'jadwalpelajaran_kurikulum') {

            echo "<div class='row'>";
            include "guru/master_jadwalpelajaran_kurikulum.php";
            echo "</div>";

            //Kepsek
          } elseif ($_GET['view'] == 'absensi_guru_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/absensi_guru.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inventaris_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/master_inventaris.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inventarismasuks_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/master_inventaris_masuk.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inventariskeluar_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/master_inventaris_keluar.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'surat_masuk') {

            echo "<div class='row'>";
            include "guru/kepsek/surat_masuk.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'surat_keluar') {

            echo "<div class='row'>";
            include "guru/kepsek/surat_keluar.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'guru') {

            echo "<div class='row'>";
            include "guru/kepsek/master_guru.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'sktahunan_all') {

            echo "<div class='row'>";
            include "guru/kepsek/master_sk_tahunan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'jam_all') {

            echo "<div class='row'>";
            include "guru/kepsek/master_jam.php";
            echo "</div>";
          } elseif ($_GET[view] == 'matapelajaran_all') {

            echo "<div class='row'>";
            include "guru/kepsek/master_matapelajaran.php";
            echo "</div>";
          } elseif ($_GET[view] == 'jadwalpelajaran_all') {

            echo "<div class='row'>";
            include "guru/kepsek/master_jadwalpelajaran.php";
            echo "</div>";
          } elseif ($_GET[view] == 'bayar_gaji') {

            echo "<div class='row'>";
            include "guru/kepsek/bayar_gaji.php";
            echo "</div>";
          } elseif ($_GET[view] == 'bayar_dapen_all') {

            echo "<div class='row'>";
            include "guru/kepsek/bayar_dapen.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'hutang') {

            echo "<div class='row'>";
            include "guru/kepsek/hutangtoko.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'detailtoko') {

            echo "<div class='row'>";
            include "guru/kepsek/detailtoko.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'nasabah') {

            echo "<div class='row'>";
            include "guru/kepsek/com_nasabah/nasabah.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'laptransaksi') {

            echo "<div class='row'>";
            include "guru/kepsek/com_laporan/laporan-transaksi.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja') {

            echo "<div class='row'>";
            include "guru/kepsek/program_kerja/master_program_kerja.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja_ismubaris') {

            echo "<div class='row'>";
            include "guru/kepsek/program_kerja/master_program_kerja_ismubaris.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja_kesiswaan') {

            echo "<div class='row'>";
            include "guru/kepsek/program_kerja/master_program_kerja_kesiswaan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja_kurikulum') {

            echo "<div class='row'>";
            include "guru/kepsek/program_kerja/master_program_kerja_kurikulum.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja_sarpras') {

            echo "<div class='row'>";
            include "guru/kepsek/program_kerja/master_program_kerja_sarpras.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja_humas') {

            echo "<div class='row'>";
            include "guru/kepsek/program_kerja/master_program_kerja_humas.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'program_kerja_tu') {

            echo "<div class='row'>";
            include "guru/kepsek/program_kerja/master_program_kerja_tu.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan') {

            echo "<div class='row'>";
            include "guru/kepsek/rencana_kegiatan/master_rencana_kegiatan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan_ismubaris') {

            echo "<div class='row'>";
            include "guru/kepsek/rencana_kegiatan/master_rencana_kegiatan_ismubaris.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan_kesiswaan') {

            echo "<div class='row'>";
            include "guru/kepsek/rencana_kegiatan/master_rencana_kegiatan_kesiswaan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan_kurikulum') {

            echo "<div class='row'>";
            include "guru/kepsek/rencana_kegiatan/master_rencana_kegiatan_kurikulum.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan_sarpras') {

            echo "<div class='row'>";
            include "guru/kepsek/rencana_kegiatan/master_rencana_kegiatan_sarpras.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan_humas') {

            echo "<div class='row'>";
            include "guru/kepsek/rencana_kegiatan/master_rencana_kegiatan_humas.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rencana_kegiatan_tu') {

            echo "<div class='row'>";
            include "guru/kepsek/rencana_kegiatan/master_rencana_kegiatan_tu.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan') {

            echo "<div class='row'>";
            include "guru/kepsek/realisasi_kegiatan/master_realisasi_kegiatan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan_ismubaris') {

            echo "<div class='row'>";
            include "guru/kepsek/realisasi_kegiatan/master_realisasi_kegiatan_ismubaris.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan_kesiswaan') {

            echo "<div class='row'>";
            include "guru/kepsek/realisasi_kegiatan/master_realisasi_kegiatan_kesiswaan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan_kurikulum') {

            echo "<div class='row'>";
            include "guru/kepsek/realisasi_kegiatan/master_realisasi_kegiatan_kurikulum.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan_sarpras') {

            echo "<div class='row'>";
            include "guru/kepsek/realisasi_kegiatan/master_realisasi_kegiatan_sarpras.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan_humas') {

            echo "<div class='row'>";
            include "guru/kepsek/realisasi_kegiatan/master_realisasi_kegiatan_humas.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'realisasi_kegiatan_tu') {

            echo "<div class='row'>";
            include "guru/kepsek/realisasi_kegiatan/master_realisasi_kegiatan_tu.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan') {

            echo "<div class='row'>";
            include "guru/kepsek/monev_kegiatan/master_monev_kegiatan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan_ismubaris') {

            echo "<div class='row'>";
            include "guru/kepsek/monev_kegiatan/master_monev_kegiatan_ismubaris.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan_kesiswaan') {

            echo "<div class='row'>";
            include "guru/kepsek/monev_kegiatan/master_monev_kegiatan_kesiswaan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan_kurikulum') {

            echo "<div class='row'>";
            include "guru/kepsek/monev_kegiatan/master_monev_kegiatan_kurikulum.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan_sarpras') {

            echo "<div class='row'>";
            include "guru/kepsek/monev_kegiatan/master_monev_kegiatan_sarpras.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan_humas') {

            echo "<div class='row'>";
            include "guru/kepsek/monev_kegiatan/master_monev_kegiatan_humas.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'monev_kegiatan_tu') {

            echo "<div class='row'>";
            include "guru/kepsek/monev_kegiatan/master_monev_kegiatan_tu.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapsiswa') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_siswa.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapsiswaafirmasi') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_siswa_afirmasi.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lappembayaran') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_pembayaran_perkelas.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lappiutang') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_piutang_perjenisbayar.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapbku') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_bku.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lapbank') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_bank.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'laptunai') {

            echo "<div class='row'>";
            include "admin/laporan_tunai.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lappembayaranhari') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_kondisi_keuangan_perhari.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lappembayaranperbulan') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_pembayaran_perbulan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'lappembayaranperposbayar') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_pembayaran_perposbayar.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'laptagihansiswa') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_tagihan_siswa.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rekapitulasi') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_rekapitulasi.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rekappengeluaran') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_rekappengeluaran.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'rekapkondisikeuangan') {

            echo "<div class='row'>";
            include "guru/kepsek/laporan_kondisi_keuangan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_lok_barang') {

            echo "<div class='row'>";
            include "guru/inv_lok_barang.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_sumber_dana') {

            echo "<div class='row'>";
            include "guru/inv_sumber_dana.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_jenis_barang') {

            echo "<div class='row'>";
            include "guru/inv_jenis_barang.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_data_barang') {

            echo "<div class='row'>";
            include "guru/inv_data_barang.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_data_item') {

            echo "<div class='row'>";
            include "guru/inv_data_item.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_pengadaan') {

            echo "<div class='row'>";
            include "guru/inv_pengadaan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_penghapusan') {

            echo "<div class='row'>";
            include "guru/inv_penghapusan.php";
            echo "</div>";
            //kepsek
          } elseif ($_GET['view'] == 'inv_data_barang_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/inv_data_barang.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_data_item_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/inv_data_item.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_pengadaan_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/inv_pengadaan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'inv_penghapusan_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/inv_penghapusan.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'arsip_kategori') {

            echo "<div class='row'>";
            include "guru/arsip_kategori.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'arsip_data') {

            echo "<div class='row'>";
            include "guru/arsip_data.php";
            echo "</div>";
          } elseif ($_GET['view'] == 'arsip_kepsek') {

            echo "<div class='row'>";
            include "guru/kepsek/arsip_data.php";
            echo "</div>";
          }

          ?>
        </section>
      </div><!-- /.content-wrapper -->
      <footer class="main-footer">
        <?php include "footer.php"; ?>
      </footer>
      <!--  <nav id="menu-bottom" class="nav">
        <a href="index-guru.php?view=home" class="nav__link">
          <i class="fas fa-home"></i>
          <span class="nav__text">Home</span>
        </a>
        <a href="index-guru.php?view=absengurus&tahun=<?php echo $ta['idTahunAjaran']; ?>" class="nav__link nav__link">
          <i class="fas fa-bell"></i>
          <span class="nav__text">Absen Guru</span>
        </a>
        <a href="index-guru.php?view=jadwalpelajaran" class="nav__link">
          <i class="fas fa-calendar"></i>
          <span class="nav__text">Jadwal</span>
        </a>
        <a href="index-guru.php?view=jurnalgur" class="nav__link">
          <i class="fa fa-pencil"></i>
          <span class="nav__text">Jurnal </span>
        </a>
        <a href="index-guru.php?view=masterguru&act=editguru&id=<?php echo $_SESSION['nips']; ?>" class="nav__link">
          <i class="fas fa-user-lock"></i>
          <span class="nav__text">Profile</span>
        </a> -->

      </nav>
    </div><!-- ./wrapper -->


    <!-- panggil ckeditor.js -->
    <script type="text/javascript" src="assets/ckeditor/ckeditor.js"></script>
    <!-- panggil adapter jquery ckeditor -->
    <script type="text/javascript" src="assets/ckeditor/adapters/jquery.js"></script>
    <!-- setup selector -->

    <script src="plugins/jQuery/jQuery-2.1.4.min.js"></script>
    <!-- jQuery UI 1.11.4 -->
    <script src="plugins/jQueryUI/jquery-ui.min.js"></script>
    <!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
    <script>
      $.widget.bridge('uibutton', $.ui.button);
    </script>
    <script src="plugins/highcharts/js/highcharts.js"></script>
    <script src="plugins/highcharts/js/modules/data.js"></script>
    <script src="plugins/highcharts/js/modules/exporting.js"></script>
    <!-- Bootstrap 3.3.5 -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
    <!-- DataTables -->
    <script src="plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="plugins/datatables/dataTables.bootstrap.min.js"></script>
    <!-- Morris.js charts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
    <script src="plugins/morris/morris.min.js"></script>
    <!-- Sparkline -->
    <script src="plugins/sparkline/jquery.sparkline.min.js"></script>
    <!-- jvectormap -->
    <script src="plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
    <script src="plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
    <!-- jQuery Knob Chart -->
    <script src="plugins/knob/jquery.knob.js"></script>
    <!-- daterangepicker -->
    <script src="plugins/moment/moment.min.js"></script>
    <script src="plugins/daterangepicker/daterangepicker.js"></script>
    <script src="plugins/datetimepicker/bootstrap-datetimepicker.js"></script>
    <!-- datepicker -->
    <script src="plugins/datepicker/bootstrap-datepicker.js"></script>
    <!-- Bootstrap WYSIHTML5 -->
    <script src="plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
    <!-- Slimscroll -->
    <script src="plugins/slimScroll/jquery.slimscroll.min.js"></script>
    <!-- FastClick -->
    <script src="plugins/fastclick/fastclick.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/app.min.js"></script>

    <script src="assets/js/script.js"></script>
    <script src="assets/app.js"></script>
    <script src='plugins/fullcalendar/fullcalendar.min.js'></script>

    <script src="assets/bootstrap-select/js/bootstrap-select.min.js"></script>
    <script>
      $(function() {
        // calendar
        $(function() {
          $("#datepicker").datepicker();
        });
        // $('#calendar').datepicker();
        // datepicker plugin
        $('.date-picker').datepicker({
          autoclose: true,
          todayHighlight: true,
          format: 'yyyy-mm-dd'
        });
        $("#example1").DataTable();
        $('#example2').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": true,
          "info": true,
          "autoWidth": false
        });

        $('#example3').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "ordering": false,
          "info": false,
          "autoWidth": false,
          "pageLength": 200
        });

        $('#mastersiswa').DataTable({
          "paging": false,
          "lengthChange": false,
          "searching": true,
          "ordering": false,
          "info": false,
          "autoWidth": false,
          "pageLength": 200
        });

        $('#example5').DataTable({
          "paging": true,
          "lengthChange": false,
          "searching": false,
          "info": false,
          "autoWidth": false,
          "pageLength": 200,
          "order": [
            [5, "desc"]
          ]
        });
      });
    </script>
    <?php if (isset($_GET['view']) && $_GET['view'] == 'absengurupulang') { ?>
      <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

      <script>
        $(document).ready(function() {
          var localstream = null;
          var vd;

          function initCam() {
            const video = document.createElement('video');
            video.id = 'video-cam';
            video.autoplay = 'true';
            video.style = 'width: 100%; height: auto;'

            navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

            if (navigator.getUserMedia) {
              navigator.getUserMedia({
                video: true
              }, function(stream) {
                video.srcObject = stream;
                localstream = stream;
              }, function(error) {
                alert('CAM ERROR : ' + error);
              })
            }

            const parent = document.getElementById('vid-cam');
            parent.append(video);
            vd = video;
          }

          initCam();

          document.getElementById('take').addEventListener('click', function(e) {
            e.preventDefault();
            var width = vd.offsetWidth;
            var height = vd.offsetHeight;

            var canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            var context = canvas.getContext('2d');
            context.drawImage(vd, 0, 0, width, height);

            var img = document.createElement('img');
            img.width = width;
            img.height = height;
            img.src = canvas.toDataURL('image/png');

            const parent = document.getElementById('vid-cam');
            parent.innerHTML = '';
            parent.append(img);
            document.getElementById('file-cam').files = base64ImageToBlob(img.src);
            // console.log(base64ImageToBlob(img.src))

            if (localstream != null) {
              localstream.getTracks()[0].stop()
              this.style.display = 'none'
              document.getElementById('reCam').style.display = 'block'
            }

          })

          document.getElementById('reCam').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('vid-cam').innerHTML = '';
            initCam();
            this.style.display = 'none'
            document.getElementById('take').style.display = 'block'
          })

          // function take() {
          //   var width = vd.offsetWidth;
          //   var height = vd.offsetHeight;

          //   var canvas = document.createElement('canvas');
          //   canvas.width = width;
          //   canvas.height = height;
          //   var context = canvas.getContext('2d');
          //   context.drawImage(vd, 0, 0, width, height);

          //   var img = document.getElementById('target-cam');
          //   img.src = canvas.toDataURL('image/png');
          //   // document.getElementById('filer').files = base64ImageToBlob(img.src);
          //   // console.log(base64ImageToBlob(img.src))

          //   if (localstream != null) {
          //     localstream.getTracks()[0].stop()
          //     document.getElementById('take').style.display = 'none'
          //     document.getElementById('reCam').style.display = 'block'
          //   }
          // }


          function base64ImageToBlob(str) {
            var pos = str.indexOf(';base64,');
            var type = str.substring(5, pos);
            var b64 = str.substr(pos + 8);

            var imageContent = atob(b64);

            var buffer = new ArrayBuffer(imageContent.length);
            var view = new Uint8Array(buffer);

            for (var n = 0; n < imageContent.length; n++) {
              view[n] = imageContent.charCodeAt(n);
            }

            var blob = new Blob([buffer], {
              type: type
            });

            let fileName = new Date().getTime() + '.' + type.split('/')[1]
            let file = new File([blob], fileName, {
              type: "image/jpeg",
              lastModified: new Date().getTime()
            }, 'utf-8');
            let container = new DataTransfer();
            container.items.add(file);

            return container.files;
          }

          // MAPS
          // Creating map options
          // var mapOptions = {
          //   // center: [112.35400252810952, 112.35400252810952],
          //   maxZoom: 18
          // }
          var map = L.map('map');
          var layer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoicml2YW5pIiwiYSI6ImNscTYzeWZyYzBneGYya252NWczemNkNjEifQ.m2iojpd1aoNA9sfsFDKQLw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
              'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/satellite-v9',
            tileSize: 512,
            zoomOffset: -1
          }).addTo(map);

          var listPolygon = [];
          JSON.parse(atob('<?= $listPolygon ?>')).forEach(item => {
            listPolygon.push([item.lat, item.lng]);
          });

          var polygon = L.polygon(listPolygon);
          polygon.addTo(map);

          // Adding layer to the map
          // map.addLayer(layer);
          map.on('click', function(position) {
            console.log([position.latlng.lat, position.latlng.lng]);
          })


          function isMarkerInsidePolygon(marker, poly) {
            var inside = false;
            var x = marker.getLatLng().lat,
              y = marker.getLatLng().lng;
            for (var ii = 0; ii < poly.getLatLngs().length; ii++) {
              var polyPoints = poly.getLatLngs()[ii];
              for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
                var xi = polyPoints[i].lat,
                  yi = polyPoints[i].lng;
                var xj = polyPoints[j].lat,
                  yj = polyPoints[j].lng;
                var intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                if (intersect) inside = !inside;
              }
            }
            return inside;
          };

          let start = false;
          var interval;

          let mymarker = null;



          function get() {
            interval = setInterval(function() {
              clearInterval(interval);
              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                  document.getElementById('latlng').value = `${position.coords.latitude}, ${position.coords.longitude}`;

                  if (mymarker != null) {
                    mymarker.setLatLng([position.coords.latitude, position.coords.longitude]);
                    // map.setView([position.coords.latitude, position.coords.longitude], 18, {
                    //   animation: true
                    // });
                  } else {
                    mymarker = new L.Marker([position.coords.latitude, position.coords.longitude]);
                    mymarker.addTo(map);
                    map.setView([position.coords.latitude, position.coords.longitude], 18, {
                      animation: true
                    });
                  }

                  if (isMarkerInsidePolygon(mymarker, polygon)) {
                    polygon.setStyle({
                      fillColor: '#38db21',
                      color: '#38db21',
                      weight: 2
                    });

                    $('#toSubmit').css({
                      'display': 'block'
                    });

                  } else {
                    polygon.setStyle({
                      fillColor: '#db2121',
                      color: '#db2121',
                      weight: 2
                    })
                    $('#toSubmit').css({
                      'display': 'none'
                    });

                  }
                })
              }
              get();
            }, 5000);
          }
          get();
        })
      </script>
    <?php } ?>
    <?php if (isset($_GET['view']) && $_GET['view'] == 'absenguru') { ?>
      <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js" integrity="sha512-XQoYMqMTK8LvdxXYG3nZ448hOEQiglfqkJs1NOQV44cWnUrBc8PkAOcXy20w0vlaXaVUearIOBhiXZ5V3ynxwA==" crossorigin=""></script>

      <script>
        $(document).ready(function() {
          var localstream = null;
          var vd;

          function initCam() {
            const video = document.createElement('video');
            video.id = 'video-cam';
            video.autoplay = 'true';
            video.style = 'width: 100%; height: auto;'

            navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

            if (navigator.getUserMedia) {
              navigator.getUserMedia({
                video: true
              }, function(stream) {
                video.srcObject = stream;
                localstream = stream;
              }, function(error) {
                alert('CAM ERROR : ' + error);
              })
            }

            const parent = document.getElementById('vid-cam');
            parent.append(video);
            vd = video;
          }

          initCam();

          document.getElementById('take').addEventListener('click', function(e) {
            e.preventDefault();
            var width = vd.offsetWidth;
            var height = vd.offsetHeight;

            var canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            var context = canvas.getContext('2d');
            context.drawImage(vd, 0, 0, width, height);

            var img = document.createElement('img');
            img.width = width;
            img.height = height;
            img.src = canvas.toDataURL('image/png');

            const parent = document.getElementById('vid-cam');
            parent.innerHTML = '';
            parent.append(img);
            document.getElementById('file-cam').files = base64ImageToBlob(img.src);
            // console.log(base64ImageToBlob(img.src))

            if (localstream != null) {
              localstream.getTracks()[0].stop()
              this.style.display = 'none'
              document.getElementById('reCam').style.display = 'block'
            }

          })

          document.getElementById('reCam').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('vid-cam').innerHTML = '';
            initCam();
            this.style.display = 'none'
            document.getElementById('take').style.display = 'block'
          })

          // function take() {
          //   var width = vd.offsetWidth;
          //   var height = vd.offsetHeight;

          //   var canvas = document.createElement('canvas');
          //   canvas.width = width;
          //   canvas.height = height;
          //   var context = canvas.getContext('2d');
          //   context.drawImage(vd, 0, 0, width, height);

          //   var img = document.getElementById('target-cam');
          //   img.src = canvas.toDataURL('image/png');
          //   // document.getElementById('filer').files = base64ImageToBlob(img.src);
          //   // console.log(base64ImageToBlob(img.src))

          //   if (localstream != null) {
          //     localstream.getTracks()[0].stop()
          //     document.getElementById('take').style.display = 'none'
          //     document.getElementById('reCam').style.display = 'block'
          //   }
          // }


          function base64ImageToBlob(str) {
            var pos = str.indexOf(';base64,');
            var type = str.substring(5, pos);
            var b64 = str.substr(pos + 8);

            var imageContent = atob(b64);

            var buffer = new ArrayBuffer(imageContent.length);
            var view = new Uint8Array(buffer);

            for (var n = 0; n < imageContent.length; n++) {
              view[n] = imageContent.charCodeAt(n);
            }

            var blob = new Blob([buffer], {
              type: type
            });

            let fileName = new Date().getTime() + '.' + type.split('/')[1]
            let file = new File([blob], fileName, {
              type: "image/jpeg",
              lastModified: new Date().getTime()
            }, 'utf-8');
            let container = new DataTransfer();
            container.items.add(file);

            return container.files;
          }

          // MAPS
          // Creating map options
          // var mapOptions = {
          //   // center: [112.35400252810952, 112.35400252810952],
          //   maxZoom: 18
          // }
          var map = L.map('map');
          var layer = L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoicml2YW5pIiwiYSI6ImNscTYzeWZyYzBneGYya252NWczemNkNjEifQ.m2iojpd1aoNA9sfsFDKQLw', {
            maxZoom: 18,
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, ' +
              'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/satellite-v9',
            tileSize: 512,
            zoomOffset: -1
          }).addTo(map);

          var listPolygon = [];
          JSON.parse(atob('<?= $listPolygon ?>')).forEach(item => {
            listPolygon.push([item.lat, item.lng]);
          });

          var polygon = L.polygon(listPolygon);
          polygon.addTo(map);

          // Adding layer to the map
          // map.addLayer(layer);
          map.on('click', function(position) {
            console.log([position.latlng.lat, position.latlng.lng]);
          })


          function isMarkerInsidePolygon(marker, poly) {
            var inside = false;
            var x = marker.getLatLng().lat,
              y = marker.getLatLng().lng;
            for (var ii = 0; ii < poly.getLatLngs().length; ii++) {
              var polyPoints = poly.getLatLngs()[ii];
              for (var i = 0, j = polyPoints.length - 1; i < polyPoints.length; j = i++) {
                var xi = polyPoints[i].lat,
                  yi = polyPoints[i].lng;
                var xj = polyPoints[j].lat,
                  yj = polyPoints[j].lng;
                var intersect = ((yi > y) != (yj > y)) && (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                if (intersect) inside = !inside;
              }
            }
            return inside;
          };

          let start = false;
          var interval;

          let mymarker = null;



          function get() {
            interval = setInterval(function() {
              clearInterval(interval);
              if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                  document.getElementById('latlng').value = `${position.coords.latitude}, ${position.coords.longitude}`;

                  if (mymarker != null) {
                    mymarker.setLatLng([position.coords.latitude, position.coords.longitude]);
                    // map.setView([position.coords.latitude, position.coords.longitude], 18, {
                    //   animation: true
                    // });
                  } else {
                    mymarker = new L.Marker([position.coords.latitude, position.coords.longitude]);
                    mymarker.addTo(map);
                    map.setView([position.coords.latitude, position.coords.longitude], 18, {
                      animation: true
                    });
                  }

                  if (isMarkerInsidePolygon(mymarker, polygon)) {
                    polygon.setStyle({
                      fillColor: '#38db21',
                      color: '#38db21',
                      weight: 2
                    });

                    $('#toSubmit').css({
                      'display': 'block'
                    });

                  } else {
                    polygon.setStyle({
                      fillColor: '#db2121',
                      color: '#db2121',
                      weight: 2
                    })
                    $('#toSubmit').css({
                      'display': 'none'
                    });

                  }
                })
              }
              get();
            }, 5000);
          }
          get();
        })
      </script>
    <?php } ?>

    <?php if (isset($_GET['view']) && $_GET['view'] == 'absengurus') { ?>
      <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js" integrity="sha512-nMMmRyTVoLYqjP9hrbed9S+FzjZHW5gY1TWCHA5ckwXZBadntCNs8kEqAWdrb9O7rxbCaA4lKTIWjDXZxflOcA==" crossorigin=""></script>
      <script>
        $(document).ready(function() {
          var localstream = null;
          var vd;

          function initCam() {
            const video = document.createElement('video');
            video.id = 'video-cam';
            video.autoplay = 'true';
            video.style = 'width: 100%; height: auto;'

            navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

            if (navigator.getUserMedia) {
              navigator.getUserMedia({
                video: true
              }, function(stream) {
                video.srcObject = stream;
                localstream = stream;
              }, function(error) {
                alert('CAM ERROR : ' + error);
              })
            }

            const parent = document.getElementById('vid-cam');
            parent.append(video);
            vd = video;
          }

          initCam();

          document.getElementById('take').addEventListener('click', function(e) {
            e.preventDefault();
            var width = vd.offsetWidth;
            var height = vd.offsetHeight;

            var canvas = document.createElement('canvas');
            canvas.width = width;
            canvas.height = height;
            var context = canvas.getContext('2d');
            context.drawImage(vd, 0, 0, width, height);

            var img = document.createElement('img');
            img.width = width;
            img.height = height;
            img.src = canvas.toDataURL('image/png');

            const parent = document.getElementById('vid-cam');
            parent.innerHTML = '';
            parent.append(img);
            document.getElementById('file-cam').files = base64ImageToBlob(img.src);

            if (localstream != null) {
              localstream.getTracks()[0].stop()
              this.style.display = 'none'
              document.getElementById('reCam').style.display = 'block'
            }
          })

          document.getElementById('reCam').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('vid-cam').innerHTML = '';
            initCam();
            this.style.display = 'none'
            document.getElementById('take').style.display = 'block'
          })

          function base64ImageToBlob(str) {
            var pos = str.indexOf(';base64,');
            var type = str.substring(5, pos);
            var b64 = str.substr(pos + 8);

            var imageContent = atob(b64);

            var buffer = new ArrayBuffer(imageContent.length);
            var view = new Uint8Array(buffer);

            for (var n = 0; n < imageContent.length; n++) {
              view[n] = imageContent.charCodeAt(n);
            }

            var blob = new Blob([buffer], {
              type: type
            });

            let fileName = new Date().getTime() + '.' + type.split('/')[1]
            let file = new File([blob], fileName, {
              type: "image/jpeg",
              lastModified: new Date().getTime()
            }, 'utf-8');
            let container = new DataTransfer();
            container.items.add(file);

            return container.files;
          }

          // MAPS
          var mapOptions = {
            zoom: 13
          }
          var layer = new L.TileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoicml2YW5pIiwiYSI6ImNscTYzeWZyYzBneGYya252NWczemNkNjEifQ.m2iojpd1aoNA9sfsFDKQLw', {
            attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, ' +
              '<a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, ' +
              'Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
            id: 'mapbox/satellite-v9'
          });
          var map = new L.map('map', mapOptions);
          map.addLayer(layer);

          let start = false;
          var interval;

          let mymarker = null;

          function updateMap() {
            if (navigator.geolocation) {
              navigator.geolocation.getCurrentPosition(function(position) {
                if (mymarker != null) {
                  mymarker.setLatLng([position.coords.latitude, position.coords.longitude]);
                  map.setView([position.coords.latitude, position.coords.longitude], 18, {
                    animation: true
                  });
                } else {
                  mymarker = new L.Marker([position.coords.latitude, position.coords.longitude]);
                  mymarker.addTo(map);
                  map.setView([position.coords.latitude, position.coords.longitude], 18, {
                    animation: true
                  });
                }

                document.getElementById('latlng').value = `${position.coords.latitude}, ${position.coords.longitude}`;
              })
            }
          }

          updateMap(); // Panggil fungsi sekali saat halaman dimuat
        });
      </script>

      </script>
    <?php } ?>

    <script type="text/javascript">
      $(document).ready(function() {
        //tahun ajaran
        var idTahunAjaran = $("#idTahunAjaran").val();
        $.ajax({
          type: 'POST',
          url: "guru/combobox/pilihan_tahunajaran.php",
          data: {
            idTahunAjaran: idTahunAjaran
          },
          cache: false,
          success: function(msg) {
            $("#Ctahunajaran").html(msg);
          }
        });
        //kelas
        var idKelas = $('#idKelas').val();
        var tipe_kelas = $('#tipe_kelas').val();
        $.ajax({
          type: 'POST',
          url: "guru/combobox/pilihan_kelas.php",
          data: {

            idKelas: idKelas,
            tipe_kelas: tipe_kelas
          },
          cache: false,
          success: function(msg) {
            $("#Ckelas").html(msg);
          }
        });

      });
    </script>
    <script>
      $('.textarea').wysihtml5();



      //$('.datepicker').datepicker();

      $('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
      });

      $('.datetimepicker').datetimepicker({
        format: 'yyyy-mm-dd hh:ii:ss',
        weekStart: 1,
        todayBtn: 1,
        autoclose: 1
      });

      $(".harusAngka").keypress(function(e) {
        //if the letter is not digit then display error and don't type anything
        if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
          return false;
        }
      });

      $("#parent").click(function() {
        $(".child").prop("checked", this.checked);
      });

      $('.child').click(function() {
        if ($('.child:checked').length == $('.child').length) {
          $('#parent').prop('checked', true);
        } else {
          $('#parent').prop('checked', false);
        }
      });

      //hitung
      /*
      $('#hitungBayaran').keyup(function(){
      	if ($(this).val() > $("#sisa").val()){
      		alert('Anda memasukkan nilai melebihi total tagihan!');
      		$(this).val($("#sisa").val());
      	}
      });
      */

      $("#allTarif").keypress(function(e) {
        var allTarif = $("#allTarif").val();
        if (e.which == 13) {
          $("#n1").val(allTarif);
          $("#n2").val(allTarif);
          $("#n3").val(allTarif);
          $("#n4").val(allTarif);
          $("#n5").val(allTarif);
          $("#n6").val(allTarif);
          $("#n7").val(allTarif);
          $("#n8").val(allTarif);
          $("#n9").val(allTarif);
          $("#n10").val(allTarif);
          $("#n11").val(allTarif);
          $("#n12").val(allTarif);
        }
      });
      $("#allTarifBebas").keypress(function(e) {
        var allTarif = $("#allTarifBebas").val();
        if (e.which == 13) {
          $(".nTagihan").val(allTarif);
        }
      }); <
      script type = "text/javascript" >
        $(document).ready(function() {
          myGrafikMasukKeluar("<?= $ta['idTahunAjaran'] ?>");
        });
      $('#comboGrafik').change(function() {
        var idTahunAjaran = $('#comboGrafik').val();
        myGrafikMasukKeluar(idTahunAjaran);
      });

      function myGrafikMasukKeluar(idTahunAjaran) {
        document.getElementById("bar-chart").innerHTML = '&nbsp;';
        $.post("guru/grafik/data-pemasukan-pengeluaran.php?thnAjaran=" + idTahunAjaran,
          function(dataVal) {
            //BAR CHART
            var bar = new Morris.Bar({
              element: 'bar-chart',
              resize: true,
              data: dataVal,
              barColors: ['#00a65a', '#f56954'],
              xkey: 'y',
              ykeys: ['a', 'b'],
              labels: ['Pemasukan', 'Pengeluaran'],
              hideHover: 'auto'
            });
          });
      }
    </script>

    <script>
      $(function() {
        "use strict";

        //BAR CHART
        var bar = new Morris.Bar({
          element: 'bar-chart',
          resize: true,
          data: [
            <?php
            $sqlBulan = mysqli_query($conn, "SELECT * FROM bulan ORDER BY urutan ASC");
            while ($bln = mysqli_fetch_array($sqlBulan)) :
              $bulan = $bln['idBulan'];

              $ta_pisah = explode("/", $ta['nmTahunAjaran']);
              if ($bln['urutan'] <= 6) {
                $tahun = $ta_pisah[0];
              } else {
                $tahun = $ta_pisah[1];
              }

              // Hitung Pemasukan
              $totalMasuk = 0;
              $dBulananMasuk = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalMasuk FROM tagihan_bulanan 
                                                              WHERE statusBayar='1' AND month(tglBayar) = '$bulan' AND year(tglBayar)='$tahun'"));
              $totalMasuk += $dBulananMasuk['totalMasuk'];
              $dBebasMasuk = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalMasuk FROM tagihan_bebas_bayar 
                                                          WHERE month(tglBayar) = '$bulan' AND year(tglBayar)='$tahun'"));
              $totalMasuk += $dBebasMasuk['totalMasuk'];


              // Hitung Pengeluaran
              $totalKeluar = 0;
              $dJurnalKeluar = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(pengeluaran) AS totalKeluar FROM jurnal_umum WHERE month(tgl)='$bulan' AND year(tgl)='$tahun'"));
              $totalKeluar += $dJurnalKeluar['totalKeluar'];

            ?>

              {
                y: '<?= getBulan($bln[idBulan]) . ' ' . $tahun ?>',
                a: <?= $totalMasuk ?>,
                b: <?= $totalKeluar ?>
              },

            <?php
            endwhile;
            ?>
          ],
          barColors: ['#00a65a', '#f56954'],
          xkey: 'y',
          ykeys: ['a', 'b'],
          labels: ['Pemasukan', 'Pengeluaran'],
          hideHover: 'auto'
        });
      });
    </script>
    <div class="modal fade" id="nilaiessai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Berikan Siswa Nilai Essai</h4>
          </div>
          <form method='POST' action='?view=soal&act=semuajawabansiswa&jdwl=<?php echo $_GET[jdwl]; ?>&idsoal=<?php echo $_GET[idsoal]; ?>&id=<?php echo $_GET[id]; ?>&kd=<?php echo $_GET[kd]; ?>&noinduk=<?php echo $_GET[noinduk]; ?>' class="form-horizontal">
            <div class="modal-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Nilai Essai</label>
                <div class="col-sm-10">
                  <input type="number" name='a' class="form-control">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" name='nilaiessai' class="btn btn-primary">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="modal fade" id="essai" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Tambahkan Soal Essai</h4>
          </div>
          <form method='POST' action='?view=soal&act=semuasoal&jdwl=<?php echo $_GET[jdwl]; ?>&idsoal=<?php echo $_GET[idsoal]; ?>&id=<?php echo $_GET[id]; ?>&kd=<?php echo $_GET[kd]; ?>' class="form-horizontal">
            <div class="modal-body">


              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Soal</label>
                <div class="col-sm-10">
                  <textarea rows='6' name='a' class="form-control" placeholder="Tuliskan Soal Essai..."></textarea>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" name='essai' class="btn btn-primary">Tambahkan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="modal fade" id="objektif" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title" id="myModalLabel">Tambahkan Soal Objektif</h4>
          </div>
          <form method='POST' action='?view=soal&act=semuasoal&jdwl=<?php echo $_GET[jdwl]; ?>&idsoal=<?php echo $_GET[idsoal]; ?>&id=<?php echo $_GET[id]; ?>&kd=<?php echo $_GET[kd]; ?>' class="form-horizontal">
            <div class="modal-body">
              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Soal</label>
                <div class="col-sm-10">
                  <textarea rows='3' name='a' class="form-control" placeholder="Tuliskan Soal Objektif..."></textarea>
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Jawab A</label>
                <div class="col-sm-10">
                  <input style='width:50%' type="text" name='b' class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Jawab B</label>
                <div class="col-sm-10">
                  <input style='width:50%' type="text" name='c' class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Jawab C</label>
                <div class="col-sm-10">
                  <input style='width:50%' type="text" name='d' class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Jawab D</label>
                <div class="col-sm-10">
                  <input style='width:50%' type="text" name='e' class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Jawab E</label>
                <div class="col-sm-10">
                  <input style='width:50%' type="text" name='f' class="form-control">
                </div>
              </div>

              <div class="form-group">
                <label for="inputEmail3" class="col-sm-2 control-label">Kunci</label>
                <div class="col-sm-10">
                  <input style='width:50%' type="text" name='g' class="form-control">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="submit" name='objektif' class="btn btn-primary">Tambahkan</button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </body>

  </html>

<?php
} else {
  include "login-guru.php";
}
?>