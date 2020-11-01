<?php
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Item.php';
require_once __DIR__ . '/ItemController.php';
require_once __DIR__ . '/InventoryController.php';

class OrderController
{
	/**
	 * @param string $name
	 * @param int $userId
	 * @param int $itemId
	 * @return int
	 */
	public static function add(string $name, int $userId, int $itemId = 0) : int
	{
		$id = 0;

		//Check if order with same item and status not exist yet
		if($itemId)
		{
			$order = Order::where('itemId', $itemId)->where('status', 0)->getOne();

			if(!empty($order->data))
			{
				return $id;
			}
		}

		$t = time();
		$order = new Order();
		$order->name = $name;
		$order->itemId = $itemId;
		$order->userId = $userId;
		$order->status = 0;
		$order->day = date('d', $t);
		$order->month = date('m', $t);
		$order->year = date('Y', $t);
		$order->insertTime = $t;
		$order->lastUpdate = $t;
		$id = $order->save();

		return $id;
	}

	/**
	 * @param string $getBy
	 * @return array
	 */
	public static function get(string $getBy) : array
	{
		$orders = [];
		$t = time();
		$responseArr = [
			'status' => true,
			'data' => [],
		];

		switch ($getBy)
		{
			case 'day':
				$day = date('d', $t);
				$month = date('m', $t);
				$year = date('Y', $t);
				$orders = Order::where('day', $day)->where('month', $month)->where('year', $year)->orderBy("insertTime","Desc")->get();
				break;
			case 'month':
				$month = date('m', $t);
				$year = date('Y', $t);
				$orders = Order::where('month', $month)->where('year', $year)->orderBy("insertTime","Desc")->get();
				break;
			case 'all':
				$orders = Order::orderBy("insertTime","Desc")->get();
				break;
		}

		foreach ($orders as $order)
		{
			$order->data['status'] = Order::STATUS[$order->data['status']];
			$order->data['insertTime'] = date('d-m-Y H:i', $order->data['insertTime']);
			$order->data['user'] = $order->user->name;
			$responseArr['data'][] = $order->data;
		}

		return $responseArr;
	}

	/**
	 * @param int $id
	 * @param int $userId
	 * @return array
	 */
	public static function close(int $id, int $userId) : array
	{
		$responseArr = [
			'status' => true,
			'error' => '',
		];
		$itemId = 0;
		$oldInventory = 0;
		$newInventory = 0;
		$t = time();
		$order = Order::byId($id);

		if($order)
		{
			$order->status = array_search('done', Order::STATUS);
			$order->lastUpdate = $t;
			$order->save();

			if($order->itemId)
			{
				$item = Item::byId($order->itemId);

				if($item)
				{
					$itemId = $item->id;
					$oldInventory = $item->inventory;
					$newInventory = $item->inventory + 1;
					$item->isMissing = false;
					$item->lastUpdate = $t;
					$item->inventory = $newInventory;
					$item->save();
				}
			}
			else
			{
				$oldInventory = 0;
				$newInventory = 1;
				$itemId = ItemController::inset($order->name);
			}

			if($itemId)
			{
				InventoryController::insert($itemId, $userId, $oldInventory, $newInventory);
			}
		}
		else
		{
			$responseArr = [
				'status' => false,
				'error' => 'Not found order',
			];
		}

		return $responseArr;
	}
}