<?php

declare(strict_types=1);

namespace boymelancholy\tilehelper;

use pocketmine\tile\Chest;
use pocketmine\tile\Sign;
use pocketmine\tile\Furnace;
use pocketmine\tile\ItemFrame;

/**
 * チェスト
 *
 * @author boymelancholy
 */
class TileHelper
{
	/**
	 * チェストの中身を並び変える
	 *
	 * @param Chest $chest
	 * @param integer $sort
	 */
	public function sortContents(Chest $chest, int $sort) :void
	{
		$contents = $chest->getInventory()->getContents();
	}
}