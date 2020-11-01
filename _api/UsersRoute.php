<?php
require_once __DIR__ . '/IRoute.php';
require_once __DIR__ . '/../src/valitron-master/src/Valitron/Validator.php';
require_once __DIR__ . '/../src/controllers/UserController.php';

class UsersRoute implements IRoute
{
	private $method = '';
	private $queryArr = [];

	/**
	 * UsersRoute constructor.
	 * @param string $method
	 * @param array $queryArr
	 */
	public function __construct(string $method, array $queryArr = [])
	{
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
	private function login() : array
	{
		$responseArr = [
			'data' => [],
			'status' => false,
			'error' => ''
		];
		$validator = new \Valitron\Validator($_POST);
		$validator->rule('required', ['email', 'password']);
		$validator->rule('lengthMax', 'email', 50);
		$validator->rule('lengthMin', 'password', 6);
		$validator->rule('email', 'email');

		if ($validator->validate())
		{
			$dataArr = UserController::login($_POST['email'], $_POST['password']);
			if($dataArr['status'])
			{
				$responseArr['status'] = true;
				$responseArr['data'] = $dataArr['data'];
			}
			else
			{
				$responseArr['error'] = $dataArr['error'];
			}
		}
		else
		{
			$responseArr['error'] = 'Please enter valid email and password';
		}

		return $responseArr;
	}
}