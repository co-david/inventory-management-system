<?php
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/../models/Inventory.php';
require_once __DIR__ . '/OrderController.php';
require_once __DIR__ . '/InventoryController.php';

class ItemController
{
	/**
	 * @param string $name
	 * @return int
	 */
	public static function inset(string $name) : int
	{
		$item = new Item();
		$item->name = $name;
		$item->inventory = 1;
		$item->isMissing = false;
		$item->lastUpdate = time();

		return $item->save();
	}

	/**
	 * @param string $getBy
	 * @return array
	 */
	public static function get(string $getBy) : array
	{
		$items = [];
		$responseArr = [
			'data' => [],
			'status' => false,
		];

		switch ($getBy)
		{
			case 'missing':
				$items = Item::where('isMissing', 1)->orderBy("id","Desc")->get();
				break;
			case 'all':
				$items = Item::orderBy("id","Desc")->get();
				break;
		}

		if($items)
		{
			$responseArr['status'] = true;

			foreach ($items as $item)
			{
				$item->data['lastUpdate'] = date('d-m-Y H:i', $item->data['lastUpdate']);
				$item->data['isMissing'] = $item->data['isMissing'] ? 'Yes' : 'No';
				$responseArr['data'][] = $item->data;
			}
		}

		return $responseArr;
	}

	/**
	 * @param int $id
	 * @param bool $isMissing
	 * @param int $userId
	 * @return array
	 */
	public static function setMissing(int $id, bool $isMissing, int $userId) : array
	{
		$responseArr = [
			'data' => [],
			'status' => false,
			'error' => '',
		];

		$item = Item::byId($id);

		if($item)
		{
			$item->isMissing = $isMissing;
			$item->save();
			$responseArr['status'] = true;

			if($isMissing)
			{
				OrderController::add($item->data['name'], $userId, $item->data['id']);
			}
		}
		else
		{
			$responseArr['error'] = 'Not found item';
		}

		return $responseArr;
	}

	/**
	 * @param int $id
	 * @param int $userId
	 * @param int $newInventory
	 * @return array
	 */
	public static function updateInventory(int $id, int $userId, int $newInventory) : array
	{
		$responseArr = [
			'status' => true,
			'error' => '',
		];
		$t = time();
		$item = Item::byId($id);

		if($item)
		{
			$oldInventory = $item->inventory;
			$item->inventory = $newInventory;
			$item->lastUpdate = $t;
			$item->save();
			InventoryController::insert($id, $userId, $oldInventory, $newInventory);
		}
		else
		{
			$responseArr = [
				'status' => true,
				'error' => 'Not found item',
			];
		}

		return $responseArr;
	}
}