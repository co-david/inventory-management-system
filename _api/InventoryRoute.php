<?php
require_once __DIR__ . '/IRoute.php';
require_once __DIR__ . '/../src/valitron-master/src/Valitron/Validator.php';
require_once __DIR__ . '/../src/controllers/UserController.php';
require_once __DIR__ . '/../src/controllers/InventoryController.php';

class InventoryRoute implements IRoute
{
	private $method = '';
	private $queryArr = [];
	private $user = null;

	/**
	 * InventoryRoute constructor.
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
		return InventoryController::get($geyBy);
	}
}