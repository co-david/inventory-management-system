<?php
require_once __DIR__ . '/../db.php';

class Item extends dbObject
{
	protected $dbTable = "items";
	protected $dbFields = [
		'id' => ['int'],
		'name' => ['text'],
		'inventory' => ['text'],
		'isMissing' => ['text'],
		'lastUpdate' => ['int'],
	];
	protected $timestamps = ['lastUpdate'];
}