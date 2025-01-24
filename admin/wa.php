<?php if ($_GET[act] == '') { ?>

<?php
//notif
//whatsapp api
$idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas LIMIT 1"));
$link_send = $idt['link_one_sender'];
$token_send = $idt['token'];
$number_send = $idt['wa'];
$page_URL = (@$_SERVER['HTTPS'] == 'on') ? "https://" : "http://";
$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri_segments = explode('/', $uri_path);
if ($_SESSION['notif'] == 'wa_sukses') {
    echo '<script>toastr["success"]("Berhasil mengirimkan Tagihan.","Sukses!")</script>';
} elseif ($_SESSION['notif'] == 'wa_gagal') {
    echo '<script>toastr["error"]("Gagal mengirimkan Tagihan.","Gagal!")</script>';
}
unset($_SESSION['notif']);

if (isset($_POST['kirim_tagihan_wa'])) {
    $id_siswa = $_POST['id_siswa'];
    $id_tahun_ajaran = $_POST['id_tahun_ajaran'];
    $id_kelas = $_POST['id_kelas'];
    $idBulan = $_POST['idBulan'];

    for ($i = 0; $i < count($id_siswa); $i++) {
        $bulan_aktif = (int)date('m') + 6;
        $total_tagihan_bulanan_bebas = 0;
        $siswa = mysqli_fetch_array(mysqli_query($conn, "SELECT siswa.*, kelas_siswa.nmKelas FROM siswa LEFT JOIN kelas_siswa ON siswa.idKelas = kelas_siswa.idKelas WHERE siswa.idSiswa='$id_siswa[$i]' AND siswa.idKelas='$id_kelas' "));
        $link_url_tagihan = "$page_URL$_SERVER[HTTP_HOST]/" . 'laporan_tagihan_siswa.php?tahun=' . $id_tahun_ajaran . '&siswa=' . $siswa['idSiswa'];
        $rincian_tagihan = '';
        $total_tagihan = 0;
        $no = 1;
        //semua bulan
        if ($_POST['idBulan'] == 'all') {
            $tag_bln = mysqli_query($conn, "SELECT tagihan_bulanan.idSiswa, tagihan_bulanan.jumlahBayar,
            jenis_bayar.idPosBayar, 
            jenis_bayar.nmJenisBayar, 
            tahun_ajaran.nmTahunAjaran,
            pos_bayar.nmPosBayar,
            bulan.nmBulan,
            bulan.urutan
    FROM tagihan_bulanan 
    LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
    LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
    LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
    LEFT JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
    WHERE tagihan_bulanan.idSiswa='$siswa[idSiswa]' AND jenis_bayar.idTahunAjaran<='$id_tahun_ajaran' AND tagihan_bulanan.statusBayar='0' AND bulan.urutan<='$bulan_aktif'
    order by bulan.urutan asc ");
            while ($tBln = mysqli_fetch_array($tag_bln)) {
                if ($tBln['jumlahBayar'] <> 0) {
                    $pisah_TA = explode('/', $tBln['nmTahunAjaran']);
                    if ($tBln['urutan'] <= 6) {
                        $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[0];
                    } else {
                        $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[1];
                    }
                    $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBln['nmJenisBayar'] . " - T.A" . $tBln['nmTahunAjaran'] . " - (" . $nmBulan . ") => *" . str_replace('.', ',', buatRp($tBln['jumlahBayar'])) . "*
";
                    $total_tagihan += $tBln['jumlahBayar'];
                }
            }

            // tagihan bebas
            $tag_bebas = mysqli_query($conn, "SELECT tagihan_bebas.*, 
            SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, 
            jenis_bayar.idPosBayar, 
            jenis_bayar.nmJenisBayar, 
            tahun_ajaran.nmTahunAjaran,
            pos_bayar.nmPosBayar,
            bulan.nmBulan,
            bulan.urutan
    FROM tagihan_bebas 
    LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
    LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
    LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
    LEFT JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan
    WHERE tagihan_bebas.idSiswa='$siswa[idSiswa]' AND jenis_bayar.idTahunAjaran<='$id_tahun_ajaran' AND tagihan_bebas.statusBayar!='1' AND bulan.urutan<='$bulan_aktif'
    GROUP BY tagihan_bebas.idJenisBayar order by bulan.urutan asc");

            while ($tBbs = mysqli_fetch_array($tag_bebas)) {
                if ($tBbs['totalTagihanBebas'] <> 0) {
                    $pisah_TA = explode('/', $tBbs['nmTahunAjaran']);
                    if ($tBbs['urutan'] <= 6) {
                        $nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[0];
                    } else {
                        $nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[1];
                    }
                    $bayar_bebas = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayarBebas FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tBbs[idTagihanBebas]'"));
                    $sisa_tag_bebas = $tBbs['totalTagihanBebas'] - $bayar_bebas['totalBayarBebas'];
                    if ($sisa_tag_bebas <> 0) {
                        $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBbs['nmJenisBayar'] . " - T.A" . $tBbs['nmTahunAjaran'] . " - (" . $nmBulan . ") => *" . str_replace('.', ',', buatRp($sisa_tag_bebas)) . "*
";
                        $total_tagihan += $sisa_tag_bebas;
                    }
                }
            }
            //Perbulan
        } else {
            $tag_bln = mysqli_query($conn, "SELECT tagihan_bulanan.idSiswa, tagihan_bulanan.jumlahBayar,
            jenis_bayar.idPosBayar, 
            jenis_bayar.nmJenisBayar, 
            tahun_ajaran.nmTahunAjaran,
            pos_bayar.nmPosBayar,
            bulan.nmBulan,
            bulan.urutan
    FROM tagihan_bulanan 
    LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar
    LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
    LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
    LEFT JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan
    WHERE tagihan_bulanan.idSiswa='$siswa[idSiswa]' AND jenis_bayar.idTahunAjaran<='$id_tahun_ajaran' AND tagihan_bulanan.statusBayar='0' AND bulan.idBulan='$_POST[idBulan]'
    order by bulan.urutan asc ");
            while ($tBln = mysqli_fetch_array($tag_bln)) {
                if ($tBln['jumlahBayar'] <> 0) {
                    $pisah_TA = explode('/', $tBln['nmTahunAjaran']);
                    if ($tBln['urutan'] <= 6) {
                        $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[0];
                    } else {
                        $nmBulan = $tBln['nmBulan'] . ' ' . $pisah_TA[1];
                    }
                    $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBln['nmJenisBayar'] . " - T.A" . $tBln['nmTahunAjaran'] . " - (" . $nmBulan . ") => *" . str_replace('.', ',', buatRp($tBln['jumlahBayar'])) . "*
";
                    $total_tagihan += $tBln['jumlahBayar'];
                }
            }

            // tagihan bebas
            $tag_bebas = mysqli_query($conn, "SELECT tagihan_bebas.*, 
            SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, 
            jenis_bayar.idPosBayar, 
            jenis_bayar.nmJenisBayar, 
            tahun_ajaran.nmTahunAjaran,
            pos_bayar.nmPosBayar,
            bulan.nmBulan,
            bulan.urutan
    FROM tagihan_bebas 
    LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar
    LEFT JOIN tahun_ajaran ON jenis_bayar.idTahunAjaran = tahun_ajaran.idTahunAjaran
    LEFT JOIN pos_bayar ON jenis_bayar.idPosBayar = pos_bayar.idPosBayar
    LEFT JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan
    WHERE tagihan_bebas.idSiswa='$siswa[idSiswa]' AND jenis_bayar.idTahunAjaran<='$id_tahun_ajaran' AND tagihan_bebas.statusBayar!='1' AND bulan.idBulan='$_POST[idBulan]'
    GROUP BY tagihan_bebas.idJenisBayar order by bulan.urutan asc");

            while ($tBbs = mysqli_fetch_array($tag_bebas)) {
                if ($tBbs['totalTagihanBebas'] <> 0) {
                    $pisah_TA = explode('/', $tBbs['nmTahunAjaran']);
                    if ($tBbs['urutan'] <= 6) {
                        $nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[0];
                    } else {
                        $nmBulan = $tBbs['nmBulan'] . ' ' . $pisah_TA[1];
                    }
                    $bayar_bebas = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(jumlahBayar) as totalBayarBebas FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tBbs[idTagihanBebas]'"));
                    $sisa_tag_bebas = $tBbs['totalTagihanBebas'] - $bayar_bebas['totalBayarBebas'];
                    if ($sisa_tag_bebas <> 0) {
                        $rincian_tagihan = $rincian_tagihan . $no++ . ". " . $tBbs['nmJenisBayar'] . " - T.A" . $tBbs['nmTahunAjaran'] . " - (" . $nmBulan . ") => *" . str_replace('.', ',', buatRp($sisa_tag_bebas)) . "*
";
                        $total_tagihan += $sisa_tag_bebas;
                    }
                }
            }
        }


        // kirim Tagihan WAu
        $noHp_ortu = $siswa['noHpOrtu'];

        $text_wa = 'Assalamualaikum Wr Wb, Ayah/Bunda mengingatkan untuk segera menyelesaikan pembayaran Tagihan sebesar *' . str_replace(".", ",", buatRp($total_tagihan)) . '* peserta didik a/n *' . $siswa['nmSiswa'] . '* Kelas *' . $siswa['nmKelas'] . '*, dengan rincian di bawah ini:
    
' . $rincian_tagihan . '

(Keuangan SD Muhammadiyah 3 Bandung)';
        send_wa($link_send, $token_send, $number_send, $noHp_ortu, $text_wa);

        header('Location: ' . $_POST['uri']);
    }
}
?>
<div class="col-md-12">
    <div class="box box-success">
        <div class="box-header">
            <form action="" method="get" accept-charset="utf-8">
                <input type="hidden" name="view" value="<?= $_GET[view] ?>">
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Tahun Ajaran</label>
                            <input type="hidden" id="idTahunAjaran" value="<?= $_GET[thn_ajar] ?>">
                            <select class="form-control" name="thn_ajar" id="Ctahunajaran"></select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Kelas</label>
                            <input type="hidden" id="idKelas" value="<?= $_GET[kelas] ?>">
                            <select class="form-control" name="kelas" id="Ckelas" required="">
                                <option value="">- Pilih Kelas -</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Bulan</label>
                            <input type="hidden" id="idBulan" value="<?= $_GET[idBulan] ?>">
                            <select class="form-control" name="idBulan" id="Cbulan" required="">
                                <option value="all">- Semua Bulan -</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div style="margin-top:25px;">
                            <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> Cari</button>
                            <?php
                            if (isset($_GET['thn_ajar'])  && isset($_GET['kelas'])  && isset($_GET['idBulan'])) {
                                echo "<a data-toggle='modal' class='btn btn-success' title='Kirimkan Tagihan' href='#kirimTagihan' onclick='get_form()'><i class='fa fa-whatsapp'></i>Kirim Tagihan</a>";
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade in" id="kirimTagihan" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h4 class="modal-title">Kirim Tagihan</h4>
            </div>
            <form action="" method="POST" accept-charset="utf-8">
                <div class="modal-body">
                    <p>Anda Yakin Akan Mengirim Tagihan ke Orang Tua Pesdik Tersebut?</p>
                    <input type="hidden" class="form-group" name="id_tahun_ajaran" value="<?= $_GET[thn_ajar] ?>">
                    <input type="hidden" class="form-group" name="id_kelas" value="<?= $_GET[kelas] ?>">
                    <input type="hidden" class="form-group" name="idBulan" value="<?= $_GET[idBulan] ?>">
                    <div id="fbatch"></div>
                </div>
                <div class="modal-footer">
                    <button type="submit" name="kirim_tagihan_wa" class="btn btn-success">Kirim</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php } ?>

<?php if (isset($_GET['thn_ajar'])  && isset($_GET['kelas']) && isset($_GET['idBulan'])) {

$kls = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM kelas_siswa WHERE idKelas='$_GET[kelas]'"));
?>

<div class="col-md-12">
    <div class="box box-success">
        <div class="box-body">
            <div class="table-responsive">
                <table class="table table-bordered" style="white-space: nowrap;">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="check-all"></th>
                            <th>No.</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Wa Ortu</th>
                            <th>Total Tagihan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $total_seluruh_tagihan = 0;
                        $bulan_aktif = (int)date('m') + 6;
                        $sql_siswa = mysqli_query($conn, "SELECT * FROM view_detil_siswa WHERE idKelas='$_GET[kelas]' ");
                        while ($siswa = mysqli_fetch_array($sql_siswa)) {
                            if (isset($_GET['idBulan']) && $_GET['idBulan'] === 'all') {
                                $tagihanBulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bulanan.jumlahBayar) as totalTagihanBulanan, jenis_bayar.idTahunAjaran FROM tagihan_bulanan LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar  LEFT JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan WHERE tagihan_bulanan.idSiswa='$siswa[idSiswa]' AND tagihan_bulanan.statusBayar='0' AND jenis_bayar.idTahunAjaran='$_GET[thn_ajar]' AND bulan.urutan<='$bulan_aktif'"));
                                $tagihanBebas = mysqli_fetch_array(mysqli_query($conn, "SELECT tagihan_bebas.idTagihanBebas, SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, jenis_bayar.idTahunAjaran FROM tagihan_bebas LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar LEFT JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan WHERE tagihan_bebas.idSiswa='$siswa[idSiswa]' AND tagihan_bebas.statusBayar!='1' AND jenis_bayar.idTahunAjaran='$_GET[thn_ajar]' AND bulan.urutan<='$bulan_aktif'"));
                                $tagihanBebasBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bebas_bayar.jumlahBayar) as totalTagihanBebasBayar FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tagihanBebas[idTagihanBebas]' GROUP BY idTagihanBebas"));
                                $totalTagihan = ($tagihanBulanan['totalTagihanBulanan'] + ($tagihanBebas['totalTagihanBebas'] - $tagihanBebasBayar['totalTagihanBebasBayar']));
                            } else {
                                    $idBulan = isset($_GET['idBulan']) ? $_GET['idBulan'] : 0;

                                $tagihanBulanan = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bulanan.jumlahBayar) as totalTagihanBulanan, jenis_bayar.idTahunAjaran FROM tagihan_bulanan LEFT JOIN jenis_bayar ON tagihan_bulanan.idJenisBayar = jenis_bayar.idJenisBayar  LEFT JOIN bulan ON tagihan_bulanan.idBulan = bulan.idBulan WHERE tagihan_bulanan.idSiswa='$siswa[idSiswa]' AND tagihan_bulanan.statusBayar='0' AND jenis_bayar.idTahunAjaran='$_GET[thn_ajar]' AND tagihan_bulanan.idBulan='$idBulan'"));
                                $tagihanBebas = mysqli_fetch_array(mysqli_query($conn, "SELECT tagihan_bebas.idTagihanBebas, SUM(tagihan_bebas.totalTagihan) as totalTagihanBebas, jenis_bayar.idTahunAjaran FROM tagihan_bebas LEFT JOIN jenis_bayar ON tagihan_bebas.idJenisBayar = jenis_bayar.idJenisBayar LEFT JOIN bulan ON tagihan_bebas.idBulan = bulan.idBulan WHERE tagihan_bebas.idSiswa='$siswa[idSiswa]' AND tagihan_bebas.statusBayar!='1' AND jenis_bayar.idTahunAjaran='$_GET[thn_ajar]' AND tagihan_bulanan.idBulan='$idBulan'"));
                                $tagihanBebasBayar = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(tagihan_bebas_bayar.jumlahBayar) as totalTagihanBebasBayar FROM tagihan_bebas_bayar WHERE idTagihanBebas='$tagihanBebas[idTagihanBebas]' AND idBulan='$_GET[idBulan]' "));
                                $totalTagihan = ($tagihanBulanan['totalTagihanBulanan'] + ($tagihanBebas['totalTagihanBebas'] - $tagihanBebasBayar['totalTagihanBebasBayar']));
                            }

                            echo '<tr>
                    <td style="background-color: #fff !important;">';
                            if ($totalTagihan == '0') {
                                echo '<input type="checkbox1" disabled="disabled"></center>';
                            } else {
                                echo '<input type="checkbox" class="checkbox" name="msg[]" id="msg" value="' . $siswa['idSiswa'] . '"></center>';
                            }
                            echo   '</td>
                    <td>' . $no++ . '</td>
                    <td>' . $siswa['nisSiswa'] . '</td>
                    <td>' . $siswa['nmSiswa'] . '</td>
                    <td>' . $siswa['nmKelas'] . '</td>
                    <td>' . $siswa['noHpOrtu'] . '</td>
                    <td>' . buatRp($totalTagihan) . '</td>
                  </tr>';
                            $total_seluruh_tagihan = $total_seluruh_tagihan + $totalTagihan;
                        }
                        ?>

                    </tbody>
                    <tfoot>
                        <tr style="background-color: #f0f0f0;">
                            <td colspan="6" align="center" style="font-weight: bold;">Total Tagihan Kelas <?= ucwords($kls['nmKelas']) ?></td>
                            <td><?= buatRp($total_seluruh_tagihan) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>


<?php } ?>

<script type="text/javascript">
function get_form() {
    var id_siswa = $('#msg:checked');
    if (id_siswa.length > 0) {
        var id_siswa_value = [];
        $(id_siswa).each(function() {
            id_siswa_value.push($(this).val());
        });

        $.ajax({
            url: 'admin/form/form_add_kirim_tagihan_siswa.php',
            method: "POST",
            data: {
                id_siswa: id_siswa_value,
            },
            success: function(msg) {
                $("#fbatch").html(msg);
            },
            error: function(msg) {
                toastr["error"]("msg", "Gagal!");
            }
        });
    } else {
        $("#fbatch").html('');
        toastr["error"]("Belum ada Siswa yang dipilih", "Gagal!");
    }
}
var checkAll = document.getElementById('check-all');
var checkboxes = document.querySelectorAll('.checkbox');

checkAll.addEventListener('change', function() {
    for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].checked = this.checked;
    }
});
// function checkAll() {
//     var checkboxes = document.querySelectorAll("input[type='checkbox']");
//     for (var i = 0; i < checkboxes.length; i++) {
//         checkboxes[i].checked = true;
//     }
// }

// function uncheckAll() {
//     var checkboxes = document.querySelectorAll("input[type='checkbox']");
//     for (var i = 0; i < checkboxes.length; i++) {
//         checkboxes[i].checked = false;
//     }
// }
</script>