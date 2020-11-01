<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/controllers/ItemController.php';

class ItemControllerTest extends TestCase
{
	public function testSetMissing()
	{
		$responseArr = ItemController::setMissing(1, false, 1);
		$this->assertEquals(
			true,
			$responseArr['status']
		);
	}

	public function testUpdateInventory()
	{
		$responseArr = ItemController::updateInventory(1, 1, 10);
		$this->assertEquals(
			true,
			$responseArr['status']
		);
	}

	public function testInset()
	{
		$id = ItemController::inset('PHP unit test');
		$this->assertGreaterThan(
			$id,
			0
		);
	}

	public function testGet()
	{
		$responseArr = ItemController::get('all');
		$this->assertEquals(
			true,
			$responseArr['status']
		);
	}
}
