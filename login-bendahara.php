<html>

<head>
	<title>Form Login Dengan Background Image</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" href="img/loo.png" />
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
					<img src="img/loo.png" />
				</div>
				<h3>Login ke akun Anda</h3>
			</div>
			<form method="post" action="cek_login_bendahara.php" class="login-form">
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
			
			</form>
			
			
		</div>
	</div>
</body>

</html>