<?php

declare(strict_types=1);

namespace TileHandleAPI\boymelancholy\tileapi\handlers;

use pocketmine\tile\Furnace;
use pocketmine\block\Block;
use pocketmine\item\Item;

/**
 * 釜戸
 *
 * @author boymelancholy
 */
class FurnaceHandler extends Handler
{
	/**
	 * 継承
	 *
	 * @param Block $block
	 * @return FurnaceHandler|null
	 */
	public function __construct(Block $block)
	{
		$tile = $block->getLevel()->getTile($block);
		if (!($tile instanceof Furnace)) return null;
		parent::__construct($tile);
	}

	/**
	 * 精錬されるアイテムを取得
	 *
	 * @return Item
	 */
	public function getRefining() :Item
	{
		return $this->tile->getInventory()->getSmelting();
	}

	/**
	 * 燃料アイテムを取得
	 *
	 * @return Item
	 */
	public function getFuel() :Item
	{
		return $this->tile->getInventory()->getFuel();
	}

	/**
	 * 精錬後のアイテムを取得
	 *
	 * @return Item
	 */
	public function getRefined() :Item
	{
		return $this->tile->getInventory()->getResult();
	}

	/**
	 * 精錬する
	 *
	 * @param Item $refining 材料
	 * @param Item $fuel 燃料
	 * @return boolean
	 */
	public function burning(Item $refining, Item $fuel) :bool
	{
		if ($fuel->getFuelTime() == 0) return false;
		$this->tile->getInventory()->setSmelting($refining);
		$this->tile->getInventory()->setFuel($fuel);
		return true;
	}

	/**
	 * カスタムネームの取得
	 *
	 * @return string
	 */
	public function getCustomName() :string
	{
		return $this->tile->getName();
	}

	/**
	 * カスタムネーム設定
	 *
	 * @param string $name
	 * @return void
	 */
	public function setCustomName(string $name) :void
	{
		$this->tile->setName($name);
		$this->tile->saveNBT();
	}
}