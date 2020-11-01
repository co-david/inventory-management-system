<?php
require_once __DIR__ . '/IRoute.php';
require_once __DIR__ . '/../src/valitron-master/src/Valitron/Validator.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/controllers/ItemController.php';

class ItemsRoute implements IRoute
{
	private $method = '';
	private $queryArr = [];
	private $user = null;

	/**
	 * ItemsRoute constructor.
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

	/**
	 * @return array
	 */
	public function run() : array
	{
		return $this->{$this->method}();
	}

	/**
	 * @return array
	 */
	private function get() : array
	{
		$geyBy = !empty($this->queryArr) && isset($this->queryArr['by']) ? $this->queryArr['by'] : 'all';
		return ItemController::get($geyBy);
	}

	/**
	 * @return array
	 */
	private function setMissing() : array
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
			$responseArr = ItemController::setMissing($_POST['id'], true, $this->user->id);
		}
		else
		{
			$responseArr['error'] = 'Invalid ID';
		}

		return $responseArr;
	}

	/**
	 * @return array
	 */
	private function arrive() : array
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
			$responseArr = ItemController::setMissing($_POST['id'], false, $this->user->id);
		}
		else
		{
			$responseArr['error'] = 'Invalid ID';
		}

		return $responseArr;
	}

	private function updateInventory()
	{
		$responseArr = [
			'data' => [],
			'status' => false,
			'error' => ''
		];
		$validator = new \Valitron\Validator($_POST);
		$validator->rule('required', ['id', 'inventory']);
		$validator->rule('integer', 'id');
		$validator->rule('integer', 'inventory');

		if($validator->validate())
		{
			$dataArr = ItemController::updateInventory($_POST['id'], $this->user->id, $_POST['inventory']);

			if(!$dataArr['status'])
			{
				$responseArr['error'] = $dataArr['error'];
			}
			else
			{
				$responseArr['status'] = true;
			}
		}
		else
		{
			$responseArr['error'] = 'Invalid ID';
		}

		return $responseArr;
	}
}