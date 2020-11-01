<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/controllers/OrderController.php';

class OrderControllerTest extends TestCase
{
	public function testAdd()
	{
		$id = OrderController::add('PHP unit test', 1);
		$this->assertGreaterThan(
			$id,
			0
		);
	}

	public function testClose()
	{
		$responseArr = OrderController::close(1, 1);
		$this->assertEquals(
			true,
			$responseArr['status']
		);
	}

	public function testGet()
	{
		$responseArr = OrderController::get('all');
		$this->assertEquals(
			true,
			$responseArr['status']
		);
	}
}
