<?php
require_once __DIR__ . '/../db.php';

class User extends dbObject
{
	const TYPES = [
		1 => 'sales',
		2 => 'logistic',
	];

	protected $dbTable = "users";
	protected $dbFields = [
		'id' => ['int'],
		'name' => ['text'],
		'email' => ['text'],
		'password' => ['text'],
		'lastLogin' => ['int'],
	];
	protected $timestamps = ['lastLogin'];
}