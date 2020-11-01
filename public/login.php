<?php
session_start();

require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/models/User.php';

//Check if user already logged in
if(isset($_SESSION['user']['token']))
{
	if($responseArr = UserController::isValidToken($_SESSION['user']['token']))
	{
		if($responseArr['status'])
		{
			$user = $responseArr['data'];
			$type = User::TYPES[$user->type];

			switch ($type)
			{
				case 'sales':
					header('Location: /InventorySystem/sales/items');
					exit();
				case 'logistic':
					header('Location: /InventorySystem/logistic/items');
					exit();
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login</title>
	<link href="css/login.css" rel="stylesheet" />
</head>
<body>
	<div class="container">
		<div class="wrap-login">
			<form id="loginFrm" class="login-form" method="post">
				<span class="title">Login</span>
				<div class="wrap-input">
					<input id="email" type="test" placeholder="Email" name="email" />
					<span class="error-txt"></span>
				</div>
				<div class="wrap-input">
					<input id="password" type="password" placeholder="Password" name="password" />
					<span class="error-txt"></span>
				</div>
				<button class="login-btn">Login</button>
				<span id="generalError" class="general-error"></span>
			</form>
		</div>
	</div>
	<script
			src="https://code.jquery.com/jquery-3.5.1.min.js"
			integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
			crossorigin="anonymous">
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/validator/13.1.17/validator.min.js" integrity="sha512-p1b+doSyVwyKqVTZeU1+XJtjpBSkhllBca2L6QTxPUjZZ0ELeZIHkAeQdtfNulbXxLdCwN4uKYGPpp78xeqwfQ==" crossorigin="anonymous"></script>
	<script src="js/login.js"></script>
</body>
</html>