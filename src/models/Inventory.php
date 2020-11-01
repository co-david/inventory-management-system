<?php
require_once __DIR__ . '/../db.php';

class Inventory extends dbObject
{
	protected $dbTable = "inventory";
	protected $dbFields = [
		'id' => ['int'],
		'itemId' => ['int'],
		'userId' => ['int'],
		'old' => ['int'],
		'new' => ['int'],
		'day' => ['int'],
		'month' => ['int'],
		'year' => ['int'],
		'insertTime' => ['int'],
	];
	protected $timestamps = ['insertTime'];
	protected $relations = [
		'item' => ["hasOne", "item", 'itemId'],
		'user' => ["hasOne", "user", 'userId'],
	];
}