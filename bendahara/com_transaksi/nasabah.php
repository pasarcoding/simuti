
<?php 
include '../../config/koneksi.php';
include 'config/rupiah.php';
if ($_GET['aksi']==''){?>

        

    <ol class="breadcrumb">
        <li><a href=""><i class="fa fa-dashboard"></i>Dashboard</a></li>
        <li class="active">Data Nasabah <small></small></li>
    </ol>

        <div class="col-xs-12">
          <div class="box">
            <!-- /.box-header -->
          <div class="table-responsive">
              <table id="example1" class="table table-responsive no-padding table-striped">
                <thead>
                        <tr>
                          <th width="50">No</th>
                          <th>ID Nasabah</th>
                          <th>Nisn</th>
                          <th>Nama</th>
                          <th>Kelas</th>
                          <th>Orang Tua</th>
                          <th>Saldo</th>
                         
                        </tr>
                      </thead>
                      <tbody>
                      <?php  
                          $no = 0;
                          $query=mysqli_query($conn,"SELECT * FROM siswa  JOIN kelas_siswa ON siswa.idKelas=kelas_siswa.idKelas ORDER BY idSiswa DESC");
                          while($row=mysqli_fetch_array($query)){
                          $no++;
                      ?>
                        <tr>
                          <td><?php echo $no;?></td>
                          <td><?php echo $row['idSiswa'];?></td>
                          <td><?php echo $row['nisnSiswa'];?></td>
                          <td><?php echo $row['nmSiswa'];?></td>
                          <td><?php echo $row['nmKelas'];?></td>
                          <td><?php echo $row['nmOrtu'];?></td>
                          <td>Rp. <?php echo rupiah($row['saldo']);?></td>
                          
							 
                        </tr>
						</tr>
					 <?php } ?>  
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>



<?php  }elseif ($_GET['aksi'] == 'edit') {
  $idd= $_GET[id];
  $id = ($idd);
  $query=mysqli_query($conn,"SELECT * FROM siswa WHERE idSiswa='$id'");
  $r=mysqli_fetch_array($query);
  $tgl = date('d-m-Y', strtotime($r[tanggal_lahir]));
?>

         <div class="col-md-12">
    <div class="box box-solid box-primary">
		<div class="box-header with-border">
            <div class="form-group">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title" style="text-transform: capitalize;">
                    <h2 >Edit Data <?php echo $_GET['view'];?></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <form action="?view=nasabah&aksi=simpan_edit"  enctype="multipart/form-data" method="POST">
                    <div class="form-group">
                    <div class="col-md-6">
                      <label for="id">ID Nasabah :</label>
                      <input type="text"  class="form-control" disabled value="<?php echo $r['idSiswa'];?>"  />
                      <input type="hidden"  class="form-control" name="id" value="<?php echo $r['idSiswa'];?>"  />

                      <label for="id">No Rekening :</label>
                      <input type="text"  class="form-control" disabled value="<?php echo $r['nisnSiswa'];?>"  />
                      <input type="hidden"  class="form-control" name="nisnSiswa" value="<?php echo $r['nisnSiswa'];?>"  />

                       
                      <label for="nama">Nama :</label>
                      <input type="text"  class="form-control" name="nama"  value="<?php echo $r['nmSiswa'];?>" />

                      <label for="nama">Jenis Kelamin:</label>
                      <input type="text"  class="form-control" name="jkSiswa"  value="<?php echo $r['jkSiswa'];?>" />

                      <label for="nama">Agama :</label>
                     <input type="text"  class="form-control" name="agamaSiswa"  value="<?php echo $r['agamaSiswa'];?>" />

                      <label>Kelas :</label>
                      <select id="heard" class="form-control" required name="kelas">
                            <?php if ($r['idKelas']==0){ ?>
                              <option value="0" selected>- Pilih Kelas -</option>
                            <?php 
                            }
                            $query2  = "SELECT * FROM kelas_siswa  ORDER BY nmKelas";
                            $tampil2 = mysql_query($query2);
                            while($w=mysqli_fetch_array($tampil2)){
                              if ($r['idKelas']==$w['idKelas']){
                                echo "<option value=\"$w[idKelas]\" selected>$w[nmKelas]</option>";
                              }
                              else{
                                echo "<option value=\"$w[idKelas]\">$w[nmKelas]</option>";
                              }
                            }
                          ?>
                      </select>
                      </div>
                      <div class="col-md-6">
                      <label for="nama">Alamat :</label>
                      <input type="text"  class="form-control" name="alamatOrtu" disabled value="<?php echo $r['alamatOrtu'];?>" />

                      <label for="nama">No Hp :</label>
                      <input type="password"  class="form-control" disabled value="<?php echo $r['noHpOrtu'];?>" />

                     
                     

                      <label for="nama">Nama Orang Tua :</label>
                      <input type="text"  class="form-control" name="orang_tua"  value="<?php echo $r['orang_tua'];?>" />
						
						<br>
                      </div>
                      </div>
                      <div class="ln_solid"></div>
                      <div class="form-group">
                        <button type="button" class="btn btn-default btn-sm" onclick=self.history.back()>Batal</button>
                        <button type="submit" class="btn btn-success btn-sm">Simpan</button><br>
                      </div><br><br>
                  </div>
                </div>
              </div>
          </div>
        </div>
      </div>

<?php 
  
  } elseif ($_GET['aksi'] == 'simpan_edit'){
  $view = $_GET['view'];
  $tanggal = $_POST[tanggal_lahir];
  $password   = md5($_POST[password]);
  $tgl = date('Y-m-d', strtotime($tanggal));
     if (empty($_POST['password'])) {
      mysqli_query($conn,"UPDATE nasabah SET nisnSiswa = '$_POST[nisnSiswa]',
                                    nama = '$_POST[nama]',
                                    alamatOrtu = '$_POST[alamatOrtu]',
									idKelas = '$_POST[kelas]',
                                    alamat = '$_POST[alamat]',
                                    jkSiswa = '$_POST[jkSiswa]',
                                    tanggal_lahir = '$tgl',
                                    orang_tua = '$_POST[orang_tua]',
									 level = '$_POST[level]',
                                    status = '$_POST[status]' 
                                    WHERE idSiswa = '$_POST[id]'");

     }else{

      mysqli_query($conn,"UPDATE nasabah SET nisnSiswa = '$_POST[nisnSiswa]',
                                    nama = '$_POST[nama]',
                                    alamatOrtu = '$_POST[alamatOrtu]',
                                    password = '$password',
                                    idKelas = '$_POST[kelas]',
                                    alamat = '$_POST[alamat]',
                                    jkSiswa = '$_POST[jkSiswa]',
                                    tanggal_lahir = '$tgl',
                                    orang_tua = '$_POST[orang_tua]',
									level = '$_POST[level]',
                                    status = '$_POST[status]' 
                                    WHERE idSiswa = '$_POST[id]'");

     }
   echo "<script language='javascript'>document.location='?view=".$view."';</script>";

} elseif ($_GET['aksi'] == 'hapus'){
  $view = $_GET['view'];  
  $idd= $_GET[id];
  $id = ($idd);
  $query=mysqli_query($conn,"Delete FROM nasabah WHERE idSiswa='$id'");
  echo "<script language='javascript'>document.location='?view=".$view."';</script>";    
}?>



