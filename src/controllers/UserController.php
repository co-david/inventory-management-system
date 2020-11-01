<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../jwt.php';
require_once __DIR__ . '/../models/User.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class UserController
{
	/**
	 * @param string $email
	 * @param string $password
	 * @return array
	 */
	public static function login(string $email, string $password) : array
	{
		$responseArr = [
			'data' => [],
			'status' => false,
			'error' => '',
		];

		try
		{
			$user = User::where('email', $email)->getOne();

			if ($user->data)
			{
				if (password_verify($password, $user->data['password']))
				{
					$payloadArray = [
						'id' => $user->data['id'],
						'email' => $user->data['email'],
						'type' => $user->data['type'],
					];
					$token = JWT::encode($payloadArray, $_ENV['API_SECRET']);
					$_SESSION['user']['token'] = $token;
					$responseArr['status'] = true;
					$responseArr['data'] = [
						'type' => User::TYPES[$user->data['type']],
						'token' => $token,
					];
					$user->lastLogin = time();
					$user->save();
				}

			}
		} catch (Exception $error) {
			die('<hr /><pre>' . print_r(array($error->getMessage(),__LINE__, __FILE__), true) . '</pre><hr />');
		}

		if (!$responseArr['status'])
		{
			$responseArr['error'] = 'Email address or password is incorrect';
		}

		return $responseArr;
	}

	/**
	 * @param string $type
	 * @return bool
	 */
	public static function isHaveAccessToPage(string $type) : bool
	{
		$token = $_SESSION['user']['token'];

		if($token)
		{
			try
			{
				$decodeToken = JWT::decode($token, $_ENV['API_SECRET'], ['HS256']);
			} catch (Exception $e) {
				return false;
			}

			if($decodeToken)
			{
				$useType = User::TYPES[$decodeToken->type];

				if($useType == $type)
				{
					return true;
				}
			}
		}

		return false;
	}

	/**
	 * @param string $token
	 * @return array
	 */
	public static function isValidToken(string $token) : array
	{
		$responseArr = [
			'status' => true,
			'data' => [],
		];

		try
		{
			$decodeToken = JWT::decode($token, $_ENV['API_SECRET'], ['HS256']);
			$responseArr['data'] = $decodeToken;
		} catch (Exception $e) {
			$responseArr['status'] = false;
		}

		return $responseArr;
	}
}