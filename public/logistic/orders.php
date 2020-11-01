<?php
session_start();

require_once __DIR__ . '/../../src/controllers/UserController.php';

if(!UserController::isHaveAccessToPage('logistic'))
{
	header('Location: /InventorySystem/login');
}

$linksArr = [
	'Items' => [
		'url' => '/InventorySystem/logistic/items',
		'active' => false,
	],
	'Orders' => [
		'url' => '/InventorySystem/logistic/orders',
		'active' => true,
	],
	'Inventory' => [
		'url' => '/InventorySystem/logistic/inventory',
		'active' => false,
	],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Orders</title>
	<link href="../css/orders.css" rel="stylesheet" />
</head>
<body>
<div class="container">
	<div class="top">
		<div class="title-wrapper">
			<h1 class="title">Orders</h1>
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
		<section class="light">
			<h3>Show By:</h3>
			<label>
				<input type="radio" value="all" name="showBy" checked>
				<span class="design"></span>
				<span class="text">All</span>
			</label>
			<label>
				<input type="radio" value="day" name="showBy">
				<span class="design"></span>
				<span class="text">Day</span>
			</label>
			<label>
				<input type="radio" value="month" name="showBy">
				<span class="design"></span>
				<span class="text">Month</span>
			</label>
		</section>
	</div>
	<div class="table">
		<table id="itemsTable" class="cell-border hover row-border order-column stripe" style="width:100%">
			<thead>
			<tr>
				<th>Item Name</th>
				<th>User Ordered</th>
				<th>Status</th>
				<th>Date</th>
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
<script type="module" src="../js/ordersLogistic.js"></script>
</body>
</html>