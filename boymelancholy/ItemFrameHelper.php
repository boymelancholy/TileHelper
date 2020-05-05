<?php

declare(strict_types=1);

namespace TileHandleAPI\boymelancholy\tileapi\handlers;

use pocketmine\tile\ItemFrame;
use pocketmine\block\Block;
use pocketmine\item\Item;

/**
 * 額縁
 *
 * @author boymelancholy
 */
class ItemFrameHandler extends Handler
{
	/**
	 * 継承
	 *
	 * @param Block $block
	 * @return ItemFrameHandler|null
	 */
	public function __construct(Block $block)
	{
		$tile = $block->getLevel()->getTile($block);
		if (!($tile instanceof ItemFrame)) return null;
		parent::__construct($tile);
	}

	/**
	 * フレームに飾られているアイテムを取得
	 *
	 * @return Item
	 */
	public function getExhibit() :Item
	{
		return $this->tile->getItem();
	}

	/**
	 * フレームに飾られているかを取得
	 *
	 * @return boolean
	 */
	public function hasExhibit() :bool
	{
		return $this->tile->hasItem();
	}

	/**
	 * フレームに飾る
	 *
	 * @param Item $item
	 * @param integer $rotate
	 * @return void
	 */
	public function setExhibit(Item $item, int $rotate = 0) :void
	{
		$this->tile->setItem($item);
		$this->tile->setItemRotation($rotate);
	}

	/**
	 * アイテムの角度取得
	 *
	 * @return void
	 */
	public function getAngle() :int
	{
		return $this->tile->getItemRotation();
	}

	/**
	 * アイテムの角度の変更
	 *
	 * @param integer $rotate
	 * @return void
	 */
	public function setAngle(int $rotate = 0) :void
	{
		$this->tile->setItemRotation($rotate);
	}
}