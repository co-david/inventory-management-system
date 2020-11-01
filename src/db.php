<?php
require_once __DIR__ . '/MySQLi-Database/MysqliDb.php';
require_once __DIR__ . '/MySQLi-Database/dbObject.php';

$db = new Mysqlidb('localhost', 'root', '', 'InventorySystem');
dbObject::autoload("models");
?>