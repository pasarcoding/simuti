<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['username'])) {
	header('location:index.php');
}
?>
<html lang="en">

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>SPPS | Portal</title>
	<link rel="shortcut icon" href="favicon.ico">
	<link rel="stylesheet" href="./assets/font-awesome-4.6.3/css/font-awesome.min.css">
	<!-- Bootstrap Core CSS -->
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="css/load-font-googleapis.css">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="css/font-awesome.min.css">


	<!-- Custom CSS -->
	<link href="css/frontend-style.css" rel="stylesheet">
	<link href="css/portal.css" rel="stylesheet">
	<font face="Comic Sans MS">
</head>

<body>


	<section class="content-section">
		<div class="container text-center">
			<div class="row">
				<div class="col-md-12">
					<h2><i class="fa fa-graduation-cap"></i> Selamat Datang</h2>
					<p class="lead mb-5 colr">Yayasan Pesantren Budaya Indonesia</p>
				</div>
				<div class="col-md-4">
					<a href="login.php">
						<div class="box">
							<i class="fa fa-user icon-menu"></i>
							<br>
							Login Admin
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a href="login-siswa.php">
						<div class="box">
							<i class="fa fa-users icon-menu"></i>
							<br>
							Login Siswa
						</div>
					</a>
				</div>
				<div class="col-md-4">
					<a href="login-bendahara.php">
						<div class="box">
							<i class="fa fa-credit-card icon-menu"></i>
							<br>
							Login Bendahara
						</div>
					</a>
				</div>


			</div>
		</div>
	</section>


</body>

</html>