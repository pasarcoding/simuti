<!DOCTYPE html>
<html>
<head>
	<title>test qrcde</title>
</head>
<body>
<form action="simpan.php" method="post" autocomplete="off" enctype="multipart/form-data">
					
		<input type="text" name="nama" class="form-control" placeholder="Nama" required><br>
		<input type="text" name="telpon" class="form-control" placeholder="telpon" required><br>
		<button type="submit">Simpan</button>
</form>
<table border="1">
	<tr>
		<th>No.</th>
		<th>Nama</th>
		<th>Telpon</th>
		<th>Qrcode</th>
		<th>Image Qrcode</th>
	</tr>

		<?php
			include "../config/koneksi.php";
        	$tampil = mysqli_query($koneksi, "SELECT * FROM qrcode ORDER BY id DESC");
        	$no=1;
        	while ($r=mysqli_fetch_array($tampil)){
        ?>
	<tr>
        <td><center><?php echo "$no.";?></center></td>
		<td><?php echo $r["nama"];?></td>
		<td><?php echo $r["telpon"];?></td>
		<td><?php echo $r["qrcode"];?></td>
		<td><img src="temp/<?php echo $r["qrcode"];?>"></td>
		<?php  $no++; } ?>
	</tr>
</table>
</body>
</html>