<html>

<head>
	<title>Form Login Dengan Background Image</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" href="img/Mybudaya.id.jpg" />
	<link rel="stylesheet" href="css/menu.css" />
	<link rel="stylesheet" href="css/main.css" />
	<link rel="stylesheet" href="css/bgimg.css" />
	<link rel="stylesheet" href="css/font.css" />
	<link rel="stylesheet" href="css/font-awesome.min.css" />
	<script type="text/javascript" src="js/jquery-1.12.4.min.js"></script>
	<script type="text/javascript" src="js/main.js"></script>
</head>

<body>

	<div class="background"></div>
	<div class="backdrop"></div>
	<div class="login-form-container" id="login-form">
		<div class="login-form-content">
			<div class="login-form-header">
				<div class="logo">
					<img src="img/Mybudaya.id.jpg" />
				</div>
				<h3>Login ke akun Anda</h3>
			</div>
			<form method="post" action="cek_login_siswa.php" class="login-form">
				<div class="input-container">
					<i class="fa fa-envelope"></i>
					<input type="text" class="input" name="a" placeholder="Username" />
				</div>
				<div class="input-container">
					<i class="fa fa-lock"></i>
					<input type="password" id="login-password" class="input" name="b" placeholder="Password" />
					<i id="show-password" class="fa fa-eye"></i>
				</div>
				<div class="rememberme-container">
					<input type="checkbox" name="rememberme" id="rememberme" />
					<label for="rememberme" class="rememberme"><span>Biarkan tetap masuk</span></label>
					<a class="forgot-password" href="#">Lupa Password?</a>
				</div>
				<input type="submit" name="login" value="Login" class="button" />
				<!--<a href="#" class="register">Register</a>
			</form>
			<div class="separator">
				<span class="separator-text">atau</span>
			</div>
			<div class="socmed-login">
				<a href="#facebook" class="socmed-btn facebook-btn">
					<i class="fa fa-facebook"></i>
					<span>Login dengan Facebook</span>
					<a>
						<a href="#g-plus" class="socmed-btn google-btn">
							<i class="fa fa-google"></i>
							<span>Login dengan Google</span>
							<a>
								<a href="#g-plus" class="socmed-btn yahoo-btn">
									<i class="fa fa-yahoo"></i>
									<span>Login dengan Yahoo</span>
									<a>
			</div>-->
		</div>
	</div>
</body>

</html>