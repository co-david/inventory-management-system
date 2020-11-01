<?php
require_once __DIR__ . '/../db.php';

class Order extends dbObject
{
	const STATUS = [
		0 => 'open',
		1 => 'ordered',
		2 => 'done'
	];

	protected $dbTable = "orders";
	protected $dbFields = [
		'id' => ['int'],
		'itemId' => ['int'],
		'userId' => ['int'],
		'name' => ['text'],
		'status' => ['int'],
		'day' => ['int'],
		'month' => ['int'],
		'year' => ['int'],
		'insertTime' => ['int'],
		'lastUpdate' => ['int'],
	];
	protected $timestamps = ['insertTime'];
	protected $relations = [
		'item' => ["hasOne", "item", 'itemId'],
		'user' => ["hasOne", "user", 'userId'],
	];
}