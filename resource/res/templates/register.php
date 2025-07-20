<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= Config::TITLE ?></title>
	<link rel="stylesheet" href="res/css/style.min.css">
	<link rel="stylesheet" href="res/css/font-awesome.css">
	<link rel="stylesheet" href="res/css/materialize.min.css">
	<script src="res/js/jquery.js"></script>
	<script src="res/js/register.js"></script>
	<script src="res/js/materialize.min.js"></script>
</head>

<body class="bg-login">
	<div id="status" class="status"></div>
	<div class="fade">
		<div class="login fadeInLogin">
			<h4 class="title-login">
				<i id="reload" class="material-icons" onclick="reload()" title="Quay lại">arrow_back</i>
				Đăng ký tài khoản
			</h4>
			<div id="box-register_form" class="login-form" style="display: block;">
				<div class="row">
					<span style="font-size: 14px;"><i>Thông tin tài khoản sẽ được gửi đến Email của bạn</i></span>
					<div id="show-error" style="text-align: left; color: red;">
					</div>
				</div>
				<img src="/res/img/loading.gif" alt="" id="loading" class="img-loading hidden">
				<form action="" method="POST" role="form" id="register_form">
					<div class="row">
						<div class="col-12">
							<div class="input-field">
								<label for="name">Tên</label>
								<input type="text" id="name" name="name" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="input-field">
								<label for="username">Tài khoản</label>
								<input type="text" name="username" id="username" required oninput="valid_username(this.value)">
								<img src="res/img/true.png" class="valid-img hidden" id="valid-username-true">
								<img src="res/img/false.png" class="valid-img" id="valid-username-false">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="input-field">
								<label for="email">Email</label>
								<input type="email" id="email" name="email" required oninput="valid_email(this.value)">
								<img src="res/img/true.png" class="valid-img hidden" id="valid-email-true">
								<img src="res/img/false.png" class="valid-img" id="valid-email-false">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<div class="input-field">
								<input type="date" name="birthday" id="birthday" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-12">
							<button type="submit" class="btn" id="btn-register">Đăng ký</button>
						</div>
					</div>
				</form>
			</div>

			<div id="box-register-success" style="display: none; padding: 30px;">
				<?php
				$domain = (empty($_SERVER['HTTPS']) ? 'http' : 'https') . "://$_SERVER[HTTP_HOST]";
				?>
				<h5 style="color: green;">Đăng ký thành công</h5>
				<i>Hãy kiểm thông tin tài khoản đã đươc gửi đến Email</i>

				<br>
				<br>
				<a href="<?= $domain ?>">→ Trang Chủ</a>
			</div>
		</div>
	</div>
</body>

</html>