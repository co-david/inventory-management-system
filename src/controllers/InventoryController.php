<?php
require_once __DIR__ . '/../models/Inventory.php';

class InventoryController
{
	/**
	 * @param int $itemId
	 * @param int $userId
	 * @param int $old
	 * @param int $new
	 * @return int
	 */
	public static function insert(int $itemId, int $userId, int $old, int $new) : int
	{
		$t = time();
		$inventory = new Inventory();
		$inventory->itemId = $itemId;
		$inventory->userId = $userId;
		$inventory->old = $old;
		$inventory->new = $new;
		$inventory->day = date('d', $t);
		$inventory->month = date('m', $t);
		$inventory->year = date('Y', $t);
		$inventory->insertTime = $t;
		$id = $inventory->save();

		return $id;
	}

	/**
	 * @param string $getBy
	 * @return array
	 */
	public static function get(string $getBy) : array
	{
		$inventories = [];
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
				$inventories = Inventory::where('day', $day)->where('month', $month)->where('year', $year)->orderBy("insertTime","Desc")->get();
				break;
			case 'month':
				$month = date('m', $t);
				$year = date('Y', $t);
				$inventories = Inventory::where('month', $month)->where('year', $year)->orderBy("insertTime","Desc")->get();
				break;
			case 'all':
				$inventories = Inventory::orderBy("insertTime","Desc")->get();
				break;
		}

		foreach ($inventories as $inventory)
		{
			$inventory->data['user'] = $inventory->user->name;
			$inventory->data['item'] = $inventory->item->name;
			$inventory->data['insertTime'] = date('d-m-Y H:i', $inventory->data['insertTime']);
			$responseArr['data'][] = $inventory->data;
		}

		return $responseArr;
	}
}