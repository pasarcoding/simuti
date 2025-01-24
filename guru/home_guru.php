<?php
include 'config/rupiah.php';

$edit = mysqli_query($conn, "SELECT * FROM identitas ");
$record = mysqli_fetch_array($edit);


if (isset($_SESSION['id'])) {
  $id_guru = $_SESSION['id'];

  // Query untuk menghitung saldo
  $query = "SELECT 
              (SUM(debit) - SUM(kredit)) AS saldo 
            FROM transaksi_guru 
            WHERE id_guru = '$id_guru'";

  $result = mysqli_query($conn, $query);

  if ($result) {
    $row = mysqli_fetch_assoc($result);
    $saldo = $row['saldo'];
  } else {
    echo "Terjadi kesalahan dalam menghitung saldo.";
  }
} else {
  echo "ID guru tidak ditemukan dalam sesi.";
}
?>

<div class="col-md-12">
  <?php if (isset($_GET['alert']) && $_GET['alert'] == 'updb') { ?>
    <div class="alert alert-success alert-dismissible text-center" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <p><b>Update Datbase Berhasil..</b></p>
    </div>
  <?php } ?>

  <div class="box box-primary box-solid">
    <div class="box-header with-border">
      <h3 class="box-title">

        <SCRIPT language=JavaScript>
          var d = new Date();
          var h = d.getHours();
          if (h < 11) {
            document.write('Selamat pagi, ');
          } else {
            if (h < 15) {
              document.write('Selamat siang, ');
            } else {
              if (h < 19) {
                document.write('Selamat sore, ');
              } else {
                if (h <= 23) {
                  document.write('Selamat malam, ');
                }
              }
            }
          }
        </SCRIPT> <?php echo $_SESSION['namalengkap']; ?> ||<div class="col" role="main">
      </h3>
      <h3 class="box-title">

        <?php
        $hari = date('l');
        /*$new = date('l, F d, Y', strtotime($Today));*/
        if ($hari == "Sunday") {
          echo "Minggu";
        } elseif ($hari == "Monday") {
          echo "Senin";
        } elseif ($hari == "Tuesday") {
          echo "Selasa";
        } elseif ($hari == "Wednesday") {
          echo "Rabu";
        } elseif ($hari == "Thursday") {
          echo ("Kamis");
        } elseif ($hari == "Friday") {
          echo "Jum'at";
        } elseif ($hari == "Saturday") {
          echo "Sabtu";
        }
        ?>,
        <?php
        $tgl = date('d');
        echo $tgl;
        $bulan = date('F');
        if ($bulan == "January") {
          echo " Januari ";
        } elseif ($bulan == "February") {
          echo " Februari ";
        } elseif ($bulan == "March") {
          echo " Maret ";
        } elseif ($bulan == "April") {
          echo " April ";
        } elseif ($bulan == "May") {
          echo " Mei ";
        } elseif ($bulan == "June") {
          echo " Juni ";
        } elseif ($bulan == "July") {
          echo " Juli ";
        } elseif ($bulan == "August") {
          echo " Agustus ";
        } elseif ($bulan == "September") {
          echo " September ";
        } elseif ($bulan == "October") {
          echo " Oktober ";
        } elseif ($bulan == "November") {
          echo " November ";
        } elseif ($bulan == "December") {
          echo " Desember ";
        }
        $tahun = date('Y');
        echo $tahun;
        ?> ||<script type="text/javascript">
          //fungsi displayTime yang dipanggil di bodyOnLoad dieksekusi tiap 1000ms = 1detik
          function tampilkanwaktu() {
            //buat object date berdasarkan waktu saat ini
            var waktu = new Date();
            //ambil nilai jam, 
            //tambahan script + "" supaya variable sh bertipe string sehingga bisa dihitung panjangnya : sh.length
            var sh = waktu.getHours() + "";
            //ambil nilai menit
            var sm = waktu.getMinutes() + "";
            //ambil nilai detik
            var ss = waktu.getSeconds() + "";
            //tampilkan jam:menit:detik dengan menambahkan angka 0 jika angkanya cuma satu digit (0-9)
            document.getElementById("clock").innerHTML = (sh.length == 1 ? "0" + sh : sh) + ":" + (sm.length == 1 ? "0" + sm : sm) + ":" + (ss.length == 1 ? "0" + ss : ss);
          }
        </script>

        <body onload="tampilkanwaktu();setInterval('tampilkanwaktu()', 1000);">
          <span id="clock"></span>
      </h3>
    </div><!-- /.box-header -->
    <section class="content">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-bank"></i></span>
            <div class="info-box-content">
              <span class="info-box-text dash-text">Nama Sekolah</span>
              <span class="info-box-number"><?php echo $record['nmSekolah']; ?> </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text dash-text">Nama Kepala Sekolah</span>
              <span class="info-box-number"><?php echo $record['nmKepsek']; ?> </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-md-12 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-money"></i></span>
            <div class="info-box-content">
              <span class="info-box-text dash-text">Saldo Dapen</span>
              <span class="info-box-number"><?php echo buatRp($saldo); ?> </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <?php
        $month = date('m');
        $query_absen = mysqli_query($conn, "SELECT * FROM rb_absensi_guru WHERE MONTH(tanggal) = '$month' and kode_kehadiran='H'");
        $num_absen = mysqli_num_rows($query_absen);
        $query_absen_no = mysqli_query($conn, "SELECT * FROM rb_absensi_guru WHERE MONTH(tanggal) = '$month' and kode_kehadiran!='H'");
        $num_absen_no = mysqli_num_rows($query_absen_no);
        ?>
        <!-- <div class="clearfix visible-sm-block"></div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="fa fa-bell "></i></span>
            <div class="info-box-content">
              <span class="info-box-text dash-text">Jumlah Hadir Bulan Ini</span>
              <span class="info-box-number"><?php echo $num_absen; ?> </span>
            </div> -->
        <!-- /.info-box-content -->
        <!--    </div>
        </div> -->
        <!-- <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="fa fa-bell-slash "></i></span>
            <div class="info-box-content">
              <span class="info-box-text dash-text">Jumlah Tidak Hadir Bulan Ini</span>
              <span class="info-box-number"><?php echo $num_absen_no; ?> </span>
            </div> -->
        <!-- /.info-box-content -->
        <!--   </div>
        </div> -->
      </div><br>
      <div class="alert alert-info">
        <p>Ini adalah halaman Dashboard yang digunakan untuk akses Guru dan Karyawan
        </p>
      </div>
  </div>
</div>

<div class="col-md-12">
  <div class="box box-success">
    <div class="box-header with-border">
      <h3 class="box-title">Kalender</h3>

      <div class="box-tools pull-right">
        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
        </button>
        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
      </div>
    </div>
    <!-- /.box-header -->
    <div class="box-body">
      <div id="calendar" class="col-centered"></div>
      

      <!-- Modal -->
      <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form class="form-horizontal" method="POST" action="admin/agenda/agenda.php?id=<?= $idUsers ?>">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Detail Agenda</h4>
              </div>
              <div class="modal-body">
                <input type="hidden" name="id" class="form-control" id="id">
                <input type="hidden" name="tipe" class="form-control" value="edit">
                <!-- <label>Tanggal Mulai <small data-toggle="tooltip" title="" data-original-title="Wajib diisi">*</small></label> -->
                <!-- <p id="labelDate_mulai"></p> -->
                <input type="hidden" name="tgl_mulai" id="tgl_mulai" class="form-control">
                <!-- <br>
                <label>Tanggal Selesai<small data-toggle="tooltip" title="" data-original-title="Wajib diisi">*</small></label>
                <p id="labelDate_selesai"></p> -->
                <input type="hidden" name="tgl_selesai" id="tgl_selesai" class="form-control">
                <br>
                <label>Keterangan <small data-toggle="tooltip" title="" data-original-title="Wajib diisi">*</small></label>
                <textarea name="keterangan" id="keterangan" class="form-control" required=""></textarea>
                <br>
                <label>Warna <small data-toggle="tooltip" title="" data-original-title="Wajib diisi">*</small></label>
                <select name="warna" class="form-control" id="warna" required="">
                  <option value="" disabled selected>- Pilih Warna -</option>
                  <option style="color:#0071c5;" value="#0071c5">&#9724; Dark blue</option>
                  <option style="color:#40E0D0;" value="#40E0D0">&#9724; Turquoise</option>
                  <option style="color:#008000;" value="#008000">&#9724; Green</option>
                  <option style="color:#FFD700;" value="#FFD700">&#9724; Yellow</option>
                  <option style="color:#FF8C00;" value="#FF8C00">&#9724; Orange</option>
                  <option style="color:#FF0000;" value="#FF0000">&#9724; Red</option>
                  <option style="color:#000;" value="#000">&#9724; Black</option>
                </select>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Kembali</button>
                 </div>
              </div>
            </form>
 </div>

          </div>

    </div>
  </div>
</div>
<script>
  $(document).ready(function() {

    $('#calendar').fullCalendar({
      header: {
        left: 'prev,next today',
        center: 'title',
        right: 'prevYear,nextYear',
      },
      defaultDate: '<?= date('Y-m-d'); ?>',
      editable: true,
      eventLimit: true, // allow "more" link when too many events
      selectable: true,
      selectHelper: true,
      select: function(tgl_mulai, tgl_selesai) {
        $("#ModalAdd #labelDate_mulai").text(moment(tgl_mulai).format('DD-MM-YYYY'));
        $("#ModalAdd #labelDate_selesai").text(moment(tgl_selesai).format('DD-MM-YYYY'));
        $('#ModalAdd #tgl_mulai').val(moment(tgl_mulai).format('YYYY-MM-DD HH:mm:ss'));
        $('#ModalAdd #tgl_selesai').val(moment(tgl_selesai).format('YYYY-MM-DD HH:mm:ss'));
        $('#ModalAdd').modal('show');
      },
      eventRender: function(event, element) {
        element.bind('dblclick', function() {
          $('#ModalEdit #id').val(event.id);
          $("#ModalEdit #labelDate_mulai").text(event.start.format('DD-MM-YYYY'));
          $("#ModalEdit #labelDate_selesai").text(event.end.format('DD-MM-YYYY'));
          $('#ModalEdit #keterangan').val(event.title);
          $('#ModalEdit #warna').val(event.color);
          $('#ModalEdit').modal('show');
        });
      },
      eventDrop: function(event, delta, revertFunc) { // si changement de position

        edit(event);

      },
      eventResize: function(event, dayDelta, minuteDelta, revertFunc) { // si changement de longueur

        edit(event);

      },

      events: [
        <?php
        $sql_agenda = mysqli_query($conn, "SELECT id, nama, tgl_mulai, tgl_selesai, warna FROM agenda WHERE stdel='0'");
        while ($agenda = mysqli_fetch_array($sql_agenda)) {

          $start = explode(" ", $agenda['tgl_mulai']);
          $end = explode(" ", $agenda['tgl_selesai']);
          if ($start[1] == '00:00:00') {
            $start = $start[0];
          } else {
            $start = $agenda['tgl_mulai'];
          }
          if ($end[1] == '00:00:00') {
            $end = $end[0];
          } else {
            $end = $agenda['tgl_selesai'];
          }
        ?> {
            id: '<?php echo $agenda['id']; ?>',
            title: '<?php echo $agenda['nama']; ?>',
            start: '<?php echo $start; ?>',
            end: '<?php echo $end; ?>',
            color: '<?php echo $agenda['warna']; ?>',
          },
        <?php } ?>
      ]
    });

    function edit(event) {
      start = event.start.format('YYYY-MM-DD HH:mm:ss');
      if (event.end) {
        end = event.end.format('YYYY-MM-DD HH:mm:ss');
      } else {
        end = start;
      }

      id = event.id;

      Event = [];
      Event[0] = id;
      Event[1] = start;
      Event[2] = end;

      $.ajax({
        url: 'admin/agenda/agenda.php?id=<?= $idUsers ?>',
        type: "POST",
        data: {
          Event: Event,
          tipe: 'edit_tanggal'
        },
        success: function(rep) {
          if (rep == 'berhasil') {
            toastr["success"]("Data berhasil diupdate.", "Sukses!");
          } else {
            toastr["error"]("Data gagal diupdate.", "Gagal!");
          }
        }
      });
    }

  });
</script>