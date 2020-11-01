<?php
session_start();

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/UsersRoute.php';
require_once __DIR__ . '/ItemsRoute.php';
require_once __DIR__ . '/OrdersRoute.php';
require_once __DIR__ . '/InventoryRoute.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$responseArr = [
	'data' => [],
	'status' => false,
	'error' => ''
];
$route = null;
$uriArr = explode('/', $_SERVER['REQUEST_URI']);
$routeName = $uriArr[3];
$method = $uriArr[4];
$queryArr = [];

if($_SERVER['QUERY_STRING'])
{
	$method = substr($method, 0, strpos($method, '?'));
	parse_str($_SERVER['QUERY_STRING'], $queryArr);
}

try
{
	switch ($routeName)
	{
		case 'users':
			$route = new UsersRoute($method, $queryArr);
			break;
		case 'items':
			$route = new ItemsRoute($method, $queryArr);
			break;
		case 'orders':
			$route = new OrdersRoute($method, $queryArr);
			break;
		case 'inventory':
			$route = new InventoryRoute($method, $queryArr);
			break;
	}

	if($route)
	{
		if(method_exists($route, $method))
		{
			$dataArr = $route->run();

			if ($dataArr['status'])
			{
				$responseArr['data'] = $dataArr['data'];
				$responseArr['status'] = true;
			}
			else
			{
				$responseArr['error'] = $dataArr['error'];
			}
		}
		else
		{
			http_response_code(404);
		}
	}
	else
	{
		http_response_code(404);
	}
} catch (Exception $e) {
	$error = $e->getMessage();

	if($error == '404')
	{
		http_response_code(404);
	}

	$responseArr['error'] = $error;
}

exit(json_encode($responseArr, true));
?>