<?php
require_once __DIR__ . '/IRoute.php';
require_once __DIR__ . '/../src/valitron-master/src/Valitron/Validator.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/controllers/OrderController.php';

class OrdersRoute implements IRoute
{
	private $method = '';
	private $queryArr = [];
	private $user = null;

	/**
	 * OrdersRoute constructor.
	 * @param string $method
	 * @param array $queryArr
	 * @throws Exception
	 */
	public function __construct(string $method, array $queryArr = [])
	{
		//Check token
		if(isset($_SERVER['HTTP_AUTHORIZATION']))
		{
			$token = trim(str_replace('Bearer', '', $_SERVER['HTTP_AUTHORIZATION']));
			$dataArr = UserController::isValidToken($token);

			if($dataArr['status'])
			{
				$this->user = $dataArr['data'];
			}
			else
			{
				throw new Exception('Please login');
			}
		}
		else
		{
			throw new Exception('Please login');
		}

		$this->method = $method;
		$this->queryArr = $queryArr;
	}

	public function run(): array
	{
		return $this->{$this->method}();
	}

	private function request() : array
	{
		$responseArr = [
			'data' => [],
			'status' => false,
			'error' => ''
		];
		$validator = new \Valitron\Validator($_POST);
		$validator->rule('required', ['name']);
		$validator->rule('lengthMin', 'name', 1);

		if($validator->validate())
		{
			$id = OrderController::add($_POST['name'], $this->user->id);

			if($id)
			{
				$responseArr['status'] = true;
			}
			else
			{
				$responseArr['error'] = 'Some error occurred';
			}
		}
		else
		{
			$responseArr['error'] = 'Invalid item name';
		}

		return $responseArr;
	}

	private function get() : array
	{
		$geyBy = !empty($this->queryArr) && isset($this->queryArr['by']) ? $this->queryArr['by'] : 'all';
		return OrderController::get($geyBy);
	}

	private function close() :  array
	{
		$responseArr = [
			'data' => [],
			'status' => false,
			'error' => ''
		];
		$validator = new \Valitron\Validator($_POST);
		$validator->rule('required', ['id']);
		$validator->rule('integer', 'id');

		if($validator->validate())
		{
			$dataArr = OrderController::close($_POST['id'], $this->user->id);
			$responseArr['status'] = true;
		}
		else
		{
			$responseArr['error'] = 'Invalid ID';
		}

		return $responseArr;
	}
}