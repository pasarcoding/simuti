<?php 
include '../../config/koneksi.php';
if ($_GET['aksi']==''){
	?>

   
        <div class="row">
            <div class="col-md-12">
                <!-- Advanced Tables -->
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <span>Data Kas Masuk</span>
                        <span title="Tambah Data"><button style="float: right;" class="btn btn-primary"data-toggle="modal" data-target="#myModal">
                            <b>+ Tambah</b>
                    </button></span>
                    </div>
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Tanggal</th>
                                        <th>Keterangan</th>
                                        <th>Jumlah</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    <?php 

                                    $no = 1;
                                    $sql = mysqli_query($conn,"SELECT * FROM kas WHERE jenis = 'masuk' ");
                                    while ($data = mysqli_fetch_assoc($sql)) {

                                ?>
                                        <tr class="odd gradeX">
                                            <td>
                                                <?php echo $no++; ?>
                                            </td>
                                            <td>
                                                <?php echo $data['kode']; ?>
                                            </td>
                                            <td>
                                                <?php echo date('d F Y', strtotime($data['tgl'])); ?>
                                            </td>
                                            <td>
                                                <?php echo $data['keterangan']; ?>
                                            </td>
                                            <td align="right">
                                                <?php echo number_format($data['jumlah']).",-"; ?>
                                            </td>
                                            <td>
                                               
                                              <a onclick="return confirm('Apakah anda yakin ingin menghapus data?')" href="index.php?view=kasmas&hapus&id=<?php echo $data['kode'];?>" class="btn btn-danger btn-sm" title="Hapus Data"><i class="fa fa-trash"> </i></a>
                                             
							  </td>
                                        </tr>
                                        <?php 
                                    $total = $total+$data['jumlah'];
									  
                                    } 
                                ?>
                                </tbody>

                                <tr>
                                    <td colspan="4" style="text-align: left; font-size: 17px; color: maroon;">Total Kas Masuk :</td>
                                    <td style="font-size: 17px; text-align: right; "><font style="color: green;"><?php echo " Rp." . number_format($total).",-"; ?></font></td>
                                </tr>
                            </table>
                        </div>
<?php } ?>  
<?php 
 if(isset($_GET['hapus'])) {
$id = $_GET['id'];
$sql = mysqli_query($conn,"DELETE FROM kas WHERE kode = '$id' ");

	if($sql) {

                            echo "
                                <script>
                                alert('Data Berhasil Dihapus');
                                document.location='index.php?view=kasmas';
                                </script>";  
                        }
                    }

?>
                        <!--  Halaman Tambah-->
                        <div class="panel-body">
                            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                            <h4 class="modal-title" id="myModalLabel">Form Tambah Data</h4>
                                        </div>
										 <?php $query_2 = "SELECT max(kode) as maxREK FROM kas ";
          $hasil_2 = mysql_query($query_2);
          $data_2 = @mysql_fetch_array($hasil_2);
          $idMax_2 = $data_2['maxREK'];

          $noUrut_2 = (int) substr($idMax_2, 6, 9);
          $noUrut_2++;
          $char_2 = "TRS";
          $newID_2 = $char_2.sprintf("%05s", $noUrut_2);  ?>
                                        <div class="modal-body">
                                            <form role="form" method="POST">
                                                <div class="form-group">
                                                    <label>Kode</label>
                                                    <input type="text" class="form-control" name="kodes" value="<?php echo $newID_2;?>" placeholder="Input Kode" />
                                                </div>
                                                <div>
                                                    <label>Keterangan</label>
                                                    <textarea class="form-control" rows="3" name="ket"></textarea>
                                                </div>
                                                <div class="form-group">
                                                    <label>Tanggal</label>
                                                    <input class="form-control" type="date" name="tgl" />
                                                </div>
                                                <div class="form-group">
                                                    <label>Jumlah</label>
                                                    <input class="form-control" type="number" name="jml" />
                                                </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                            <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php 
                    if(isset($_POST['simpan'])) {
                        $kode = $_POST['kodes'];
                        $tgl = $_POST['tgl'];
                        $ket = $_POST['ket'];
                        $jml = $_POST['jml'];

                        $sql = mysql_query("INSERT INTO kas (kode, keterangan, tgl, jumlah, jenis, keluar) VALUES ('$kode', '$ket', '$tgl', '$jml', 'masuk', 0)");

                        if($sql) {

                            echo "
                                <script>
                                alert('Data Berhasil Ditambahkan');
                                document.location='index.php?view=kasmas';
                                </script>";  
                        }
                    }
                ?>
                            <!-- Akhir Halaman Tambah -->

                            <!-- Halaman Ubah -->
							
                            <div class="panel-body">
                                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                <h4 class="modal-title" id="myModalLabel">Form Ubah Data</h4>
                                            </div>
                                            <div class="modal-body" id="modal_edit">
                                                <form role="form" method="POST">
                                                    <div class="form-group">
                                                        <label>Kode</label>
                                                        <input class="form-control" name="kode" placeholder="Input Kode" id="kode" readonly />
                                                    </div>
                                                    <div>
                                                        <label>Keterangan</label>
                                                        <textarea class="form-control" rows="3" name="ket" id="ket"></textarea>
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Tanggal</label>
                                                        <input class="form-control" type="date" name="tgl" id="tgl" />
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Jumlah</label>
                                                        <input class="form-control" type="number" name="jml" id="jml" />
                                                    </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                                <button type="submit" name="ubah" class="btn btn-primary">Simpan</button>
                                                </form>
												

                                    <?php 
                                        if(isset($_POST['ubah'])) {
                                            $kode = $_POST['kode'];
                                            $ket = $_POST['ket'];
                                            $tgl = $_POST['tgl'];
                                            $jml = $_POST['jml'];

                                            $sql = mysqli_query($conn,"UPDATE kas SET keterangan = '$ket', tgl = '$tgl', jumlah = '$jml', jenis = 'masuk', keluar = 0 WHERE kode = '$kode' ");
                                            if($sql) {
                                                echo "
                                                    <script>
                                                    alert('Data Berhasil Diubah');
                                                    document.location='index.php?view=kasmas';
                                                    </script>";           
                                            }
                                        }
                                    ?>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Akhir Halaman Ubah -->
                    </div>
                </div>
            </div>
        </div>
		<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
 <script src="assets/js/jquery-1.10.2.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('#dataTables-example').dataTable();
            });
        </script>

        <script type="text/javascript">
            $(document).on("click", "#edit_data", function() {
                var kode = $(this).data('id');
                var ket = $(this).data('ket');
                var tgl = $(this).data('tgl');
                var jml = $(this).data('jml');

                $("#modal_edit #kode").val(kode);
                $("#modal_edit #ket").val(ket);
                $("#modal_edit #tgl").val(tgl);
                $("#modal_edit #jml").val(jml);

            })
        </script>
    </body>

    </html>