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
		'active' => true,
	],
	'Request' => [
		'url' => '/InventorySystem/sales/request',
		'active' => false,
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
	<title>Sales</title>
	<link href="../css/items.css" rel="stylesheet" />
</head>
<body>
<div class="container">
	<div class="top">
		<div class="title-wrapper">
			<h1 class="title">Items</h1>
		</div>
		<div class="topnav">
			<?
			foreach ($linksArr as $name => $linkArr)
			{?>
				<a class="<?=($linkArr['active'] ? 'active' : '')?>" href="<?=$linkArr['url']?>"><?=$name?></a>
			<?}?>
		</div>
	</div>
	<div class="search">
		<label for="searchInput">Search: </label>
		<input type="text" id="searchInput" class="search-input" placeholder="Item name" />
	</div>
	<div class="table">
		<table id="itemsTable" class="cell-border hover row-border order-column stripe" style="width:100%">
			<thead>
			<tr>
				<th>Name</th>
				<th>inventory</th>
				<th>Missing</th>
				<th>Last Update</th>
				<th>Actions</th>
			</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
</div>
<script
	src="https://code.jquery.com/jquery-3.5.1.min.js"
	integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0="
	crossorigin="anonymous">
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/validator/13.1.17/validator.min.js" integrity="sha512-p1b+doSyVwyKqVTZeU1+XJtjpBSkhllBca2L6QTxPUjZZ0ELeZIHkAeQdtfNulbXxLdCwN4uKYGPpp78xeqwfQ==" crossorigin="anonymous" async></script>
<script type="module" src="../js/itemsSales.js"></script>
</body>
</html>