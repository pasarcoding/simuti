<?php
include 'config/rupiah.php';
$query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi");
$row_saldo = mysqli_fetch_array($query_saldo);
$saldo_keseluruhan = $row_saldo['jumlah_debit'] - $row_saldo['jumlah_kredit'];
$hari_ini = date('Y-m-d');
$total = 0;

//$sqlJU = mysqli_query($conn,"SELECT * FROM jurnal_umum WHERE DATE(tgl) BETWEEN '$tgl1' AND '$tgl2' ORDER BY tgl ASC");

//hitung pemasukan dan pengeluaran dari jurnal umum
$dPJU = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(pengeluaran) AS totalKeluar FROM jurnal_umum"));
$totalPengeluaran = $dPJU['totalKeluar'];

$dPJUs = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(penerimaan) AS totalMasuk FROM jurnal_umums"));
$totalPemasukan = $dPJUs['totalMasuk'];
// Hitung Pembayaran Bulanan
$dBul = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalBul FROM tagihan_bulanan_bayar "));
$totalPendapatanBulanan = $dBul['totalBul'];

$dHarian = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalHarian FROM tagihan_bulanan_bayar where date(tglBayar) = '$hari_ini' "));
$totalPendapatanHarian = $dHarian['totalHarian'];

// Hitung Pembayaran Bebas
$dBeb = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalBeb FROM tagihan_bebas_bayar"));
$totalPendapatanBebas = $dBeb['totalBeb'];

$dBebHari = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalBeb FROM tagihan_bebas_bayar where date(tglBayar) = '$hari_ini'"));
$totalPendapatanBebasHari = $dBebHari['totalBeb'];

$query_saldo = mysqli_query($conn, "SELECT SUM(sisa) as jumlah_debit FROM hutangtoko");
$row = mysqli_fetch_array($query_saldo);
$saldo_keseluruhans = $row['jumlah_debit'];

$bulan = date('m');

$query_saldo = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE DATE_FORMAT((tanggal),'%m') like '%$bulan%'");
$saldo = mysqli_fetch_array($query_saldo);
$saldo_bulan = $saldo['jumlah_debit'] - $saldo['jumlah_kredit'];


$hari = date('d');
$query_hari = mysqli_query($conn, "SELECT SUM(debit) as jumlah_debit, SUM(kredit) as jumlah_kredit FROM transaksi WHERE DATE_FORMAT((tanggal),'%m') like '%$bulan%'");
$saldo_h = mysqli_fetch_array($query_hari);
$saldo_hari = $saldo_h['jumlah_debit'] - $saldo_h['jumlah_kredit'];


$dBuls = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalBul FROM tagihan_bulanan_bayar  WHERE DATE_FORMAT((tglBayar),'%d') like '%$hari%'"));
$totalPendapatanBulanans = $dBuls['totalBul'];

// Hitung Pembayaran Bebas
$dBebs = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) AS totalBeb FROM tagihan_bebas_bayar  WHERE DATE_FORMAT((tglBayar),'%d') like '%$hari%'"));
$totalPendapatanBebass = $dBebs['totalBeb'];

$edit = mysqli_query($conn, "SELECT * FROM identitas ");
$record = mysqli_fetch_array($edit);
?>

<div class="col-xs-12">
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

        <!-- <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box"> -->


        <!--            <span class="info-box-icon bg-aqua"><i class="fa fa-bank"></i></span>

             <div class="info-box-content">
              <span class="info-box-text dash-text">Nama Sekolah</span>
              <span class="info-box-number"><?php echo $record['nmSekolah']; ?> </span>
            </div> -->
        <!-- /.info-box-content -->
        <!-- </div> -->
        <!-- /.info-box -->
        <!-- </div> -->
        <!-- /.col -->
        <!-- <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box"> -->
        <!-- <span class="info-box-icon bg-red"><i class="fa fa-user"></i></span>


            <div class="info-box-content">
              <span class="info-box-text dash-text">Nama Kepala Sekolah</span>
              <span class="info-box-number"><?php echo $record['nmKepsek']; ?> </span>
            </div> -->
        <!-- /.info-box-content -->
        <!-- </div> -->
        <!-- /.info-box -->
        <!-- </div> -->
        <!-- /.col -->
        <?php
        $query_pegawai = mysqli_query($conn, "SELECT *  FROM users");
        $num_pegawai = mysqli_num_rows($query_pegawai);
        $query_nasabah = mysqli_query($conn, "SELECT *  FROM siswa where statusSiswa='Aktif'");
        $num_nasabah = mysqli_num_rows($query_nasabah);
        $query_nasabahs = mysqli_query($conn, "SELECT *  FROM siswa where statusSiswa='Calon Siswa'");
        $num_nasabahs = mysqli_num_rows($query_nasabahs);

        ?>
        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>

            <div class="info-box-content">

              <span class="info-box-text dash-text">User Aktif</span>
              <span class="info-box-number"><?php echo $num_pegawai; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text dash-text">Calon Siswa</span>
              <span class="info-box-number"><?php echo $num_nasabahs; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-users"></i></span>

            <div class="info-box-content">
              <span class="info-box-text dash-text">Siswa Aktif</span>
              <span class="info-box-number"><?php echo $num_nasabah; ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- /.col -->
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-money "></i></span>

            <div class="info-box-content">
              <span class="info-box-text dash-text">Pembayaran Bulanan Hari Ini</span>
              <span class="info-box-number">Rp. <?php echo rupiah($totalPendapatanHarian); ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-money "></i></span>

            <div class="info-box-content">
              <span class="info-box-text dash-text">Pembayaran Bebas Hari Ini</span>
              <span class="info-box-number">Rp. <?php echo rupiah($totalPendapatanBebasHari); ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-toggle-down text-azure"></i></span>

            <div class="info-box-content">
              <span class="info-box-text dash-text">Total Pembayaran Hari ini</span>
              <span class="info-box-number">Rp. <?php echo rupiah($totalPendapatanHarian + $totalPendapatanBebasHari); ?></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        <?php
        $query_jenisptk = mysqli_query($conn, "SELECT rb_jenis_ptk.jenis_ptk,rb_guru.id_jenis_ptk, COUNT(*) AS jumlah 
            FROM rb_guru
            INNER JOIN rb_jenis_ptk ON rb_guru.id_jenis_ptk=rb_jenis_ptk.id_jenis_ptk where rb_guru.id_status_keaktifan='Aktif'
            GROUP BY rb_guru.id_jenis_ptk");

        $bgColors = ['bg-green', 'bg-black', 'bg-red', 'bg-yellow', 'bg-blue', 'bg-aqua'];

        while ($row_jenisptk = mysqli_fetch_assoc($query_jenisptk)) {
          $jenisptk = $row_jenisptk['jenis_ptk'];
          $jumlah_jenisptk = $row_jenisptk['jumlah'];

          // Randomly select a background color
          $randomBgColor = $bgColors[array_rand($bgColors)];

          echo '<div class="col-md-6 col-sm-6 col-xs-12">
                <div class="info-box">
                  <span class="info-box-icon ' . $randomBgColor . '"><i class="fa fa-user"></i></span>
                  <div class="info-box-content">
                    <span class="info-box-text dash-text">' . $jenisptk . ' </span>
                    <span class="info-box-number">' . $jumlah_jenisptk . '</span>
                  </div>
                  <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
              </div>';
        }
        ?>

      </div>
    
<br>

      <div class="alert alert-info">
        <!-- panggil file "content" untuk menampilkan content -->

        <p>Halaman Dashboard yang digunakan untuk memonitor Data Siswa, Data Calon Siswa, Data PTK, Pembayaran Bulanan, Pembayaran Bebas, Tabungan Siswa dan Kalender Agenda.

        </p>
      </div>

  </section>
  </div>

<!-- /.col -->
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
      <div class="modal fade" id="ModalAdd" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <form class="form-horizontal" method="POST" action="admin/agenda/agenda.php?id=<?= $idUsers ?>">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Tambah Agenda</h4>
              </div>
              <div class="modal-body">
                <input type="hidden" name="tipe" class="form-control" value="tambah">
                <!-- <label>Tanggal Mulai <small data-toggle="tooltip" title="" data-original-title="Wajib diisi">*</small></label> -->
                <!-- <p id="labelDate_mulai"></p> -->
                <input type="hidden" name="tgl_mulai" id="tgl_mulai" class="form-control">
               
                <!-- <label>Tanggal Selesai<small data-toggle="tooltip" title="" data-original-title="Wajib diisi">*</small></label>
                <p id="labelDate_selesai"></p> -->
                <input type="hidden" name="tgl_selesai" id="tgl_selesai" class="form-control">
                
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Simpan</button>
              </div>
            </div>
          </div>
        </form>
      </div>



      <!-- Modal -->
      <div class="modal fade" id="ModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form class="form-horizontal" method="POST" action="admin/agenda/agenda.php?id=<?= $idUsers ?>">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Edit Agenda</h4>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                <button type="submit" name="hapus" class="btn btn-danger">Hapus</button>
                <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
              </div>
            </form>
          </div>
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
</div>
</div>