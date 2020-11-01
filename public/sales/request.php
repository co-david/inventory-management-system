<?php
session_start();

require_once __DIR__ . '/../../src/controllers/UserController.php';

if(!UserController::isHaveAccessToPage('sales'))
{
	header('Location: /InventorySystem/login');
}

$linksArr = [
	'Items' => [
		'url' => '/InventorySystem/sales/items',
		'active' => false,
	],
	'Request' => [
		'url' => '/InventorySystem/sales/request',
		'active' => true,
	],
	'Orders' => [
		'url' => '/InventorySystem/sales/orders',
		'active' => false,
	],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Request</title>
	<link href="../css/request.css" rel="stylesheet" />
</head>
<body>
<div class="container">
	<div class="top">
		<div class="title-wrapper">
			<h1 class="title">Request</h1>
		</div>
		<div class="topnav">
			<?
			foreach ($linksArr as $name => $linkArr)
			{?>
				<a class="<?=($linkArr['active'] ? 'active' : '')?>" href="<?=$linkArr['url']?>"><?=$name?></a>
			<?}?>
		</div>
	</div>
	<div>
		<form id="frm">
			<div class="input-wrapper">
				<label for="itemName">Item name:</label>
				<input type="text" id="itemName" />
			</div>
			<div>
				<button type="submit">Add</button>
			</div>
		</form>
	</div>
</div>
<script
	src="https://code.jquery.com/jquery-3.5.1.min.js"
	integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
	crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/validator/13.1.17/validator.min.js" integrity="sha512-p1b+doSyVwyKqVTZeU1+XJtjpBSkhllBca2L6QTxPUjZZ0ELeZIHkAeQdtfNulbXxLdCwN4uKYGPpp78xeqwfQ==" crossorigin="anonymous" async></script>
<script type="module" src="../js/request.js"></script>
</body>
</html>