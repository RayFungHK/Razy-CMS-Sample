<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,initial-scale=1.0,user-scalable=yes,maximum-scale=1.0">
<meta name="format-detection" content="telephone=no">
<title>Razy CMS Sample - Login</title>
<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="{$shared_view_url}css/flexit.css">
<link rel="stylesheet" type="text/css" href="{$shared_view_url}css/fontone.css">
<link rel="stylesheet" type="text/css" href="{$shared_view_url}css/login.css">
</head>

<body>
	<div id="background-deco"></div>
	<div class="flex f-middle f-center">
		<div id="login-box">
			<div id="branded-color"></div>
			<div id="login-title">Razy CMS Sample</div>
			<div class="login-panel">
				<!-- START BLOCK: error -->
				<div id="login-error" class="flex f-middle f-nowrap">
					<span class="fo-warning-o"></span>
					<div>{$error_message}</div>
				</div>
				<!-- END BLOCK: error -->
				<form action="{$form_target}" method="post" autocomplete="off">
					<div class="login-form-row">
						<span class="fo-user"></span>
						<input type="text" name="username" value="{$val_username}" />
					</div>
					<div class="login-form-row">
						<span class="fo-key"></span>
						<input type="password" name="password" value="" />
					</div>
					<div class="login-form-row">
						<input type="submit" value="Login" />
					</div>
				</form>
			</div>
		</div>
	</div>
</body>
</html>
