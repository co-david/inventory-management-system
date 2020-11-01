<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/controllers/UserController.php';

class UserControllerTest extends TestCase
{

	public function testIsValidToken()
	{
		$responseArr = UserController::login('david@gmail.com', '123456');

		if($responseArr['status'])
		{
			$token = $responseArr['data']['token'];
			$responseArr = UserController::isValidToken($token);
			$this->assertEquals(
				true,
				$responseArr['status']
			);
		}
		else
		{
			$this->fail($responseArr['error']);
		}
	}

	public function testIsHaveAccessToPage()
	{
		$responseArr = UserController::login('david@gmail.com', '123456');

		if($responseArr['status'])
		{
			$type = $responseArr['data']['type'];
			$status = UserController::isHaveAccessToPage($type);
			$this->assertEquals(
				true,
				$status
			);
		}
		else
		{
			$this->fail($responseArr['error']);
		}
	}

	public function testLogin()
	{
		$responseArr = UserController::login('david@gmail.com', '123456');
		$this->assertEquals(
			true,
			$responseArr['status']
		);
	}
}
