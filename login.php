<html>
<?php $idt = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM identitas"));

?>

<head>
	<title>SIMuti | SDM3</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" href="gambar/logo/<?php echo $idt['logo_kiri']; ?>">
	<link rel="stylesheet" href="css/menu.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/bgimg.css" />
	<link rel="stylesheet" href="css/font.css" />
	<link rel="stylesheet" href="css/font-awesome.min.css" />
	<link rel="stylesheet" type="text/css" href="csss/style.css">
	<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
	<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="./assets/style.css">
	<link rel="stylesheet" href="dist/css/AdminLTE.min.css">
</head>

<body>
	<div class="background"></div>
	<div class="backdrop"></div>
	<div class="login-form-container" id="login-form"><br><br>
		<div class="login-form-content">
			<div class="login-form-header">
				<div class="logo">
					<img src="gambar/logo/<?php echo $idt['logo_kiri']; ?>" height="90" width="90" />
				</div>
				<h3>Silahkan Login</h3>
				<?php
				if (isset($_GET['sukses'])) {
					echo "<div class='alert alert-success alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Sukses!</strong> - Data telah Berhasil Di Proses,..
					</div>";
				} elseif (isset($_GET['gagal'])) {
					echo "<div class='alert alert-danger alert-dismissible fade in' role='alert'> 
					<button type='button' class='close' data-dismiss='alert' aria-label='Close'>
					<span aria-hidden='true'>×</span></button> <strong>Gagal!</strong> - Maaf Login Gagal, Silahkan Isi Username dan Password Anda Dengan Benar
					</div>";
				}
				?>
			</div>
			<form method="post" action="cek_login.php" class="login-form">
				<div class="input-container">
					<i class="fa fa-envelope"></i>
					<input type="text" class="input" name="a" placeholder="Username/Email" />
				</div>
				<div class="input-container">
					<i class="fa fa-lock"></i>
					<input type="password" id="login-password" class="input" name="b" placeholder="Password" />
					<i id="show-password" class="fa fa-eye"></i>
				</div>
				
				<input type="submit" name="login" value="Login" class="button" />
				<!--<a href="#" class="register">Register</a>-->
			</form>

		</div>
	</div>

	<script src="bootstrap/js/bootstrap.min.js"></script>
</body>

</html>