<?php

declare(strict_types=1);

namespace TileHandleAPI\boymelancholy\tileapi\handlers;

use pocketmine\tile\Chest;
use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\Player;

/**
 * チェスト
 *
 * @author boymelancholy
 */
class ChestHandler extends Handler
{
	/**
	 * 継承
	 *
	 * @param Block $block
	 * @return ChestHandler|null
	 */
	public function __construct(Block $block)
	{
		$tile = $block->getLevel()->getTile($block);
		if (!($tile instanceof Chest)) return null;
		parent::__construct($tile);
	}

	/**
	 * 看板の特定IDを取得
	 *
	 * @return string
	 */
	public function getIdentifyId() :string
	{
		return $this->identifyId;
	}

	/**
	 * 特定のアイテムをチェストからとってインベントリーにしまう
	 *
	 * @param Player $player
	 * @param Item $item 目的のアイテム
	 * @param integer $slot しまうときの自分のインベントリーのスロット数 (-1でインベントリー空きの先頭)
	 * @return void
	 */
	public function takeOut(Player $player, Item $item, int $slot=-1) :void
	{
		$pick = $this->pick($item);
		if (!$pick) return;
		if ($slot != -1) {
			$player->getInventory()->setItem($slot, $item);
			return;
		}
		$player->getInventory()->addItem($item);
	}

	/**
	 * アイテムを取り出してチェストから消す
	 *
	 * @param Item $item
	 * @return boolean
	 */
	public function pick(Item $item) :bool
	{
		$found = false;
		foreach ($this->tile->getInventory()->getContents() as &$content) {
			if ($content->equals($item)) {
				$this->tile->getInventory()->remove($item);
				$found = true;
				break;
			}
		}
		return $found;
	}

	/**
	 * インベントリーから取り出してチェストにいれる
	 *
	 * @param Player $player
	 * @param Item $item
	 * @return boolean
	 */
	public function put(Player $player, Item $item) :bool
	{
		$couldAdd = false;
        if ($this->tile->getInventory()->canAddItem($item)) {
			$this->tile->getInventory()->addItem($item);
			$player->getInventory()->remove($item);
            $couldAdd = true;
        }
		return $couldAdd;
	}

	/**
	 * チェストのアイテムを取得
	 *
	 * @return Item[]
	 */
	public function pickAll() :array
	{
		return $this->tile->getInventory()->getContents();
	}

	/**
	 * チェストのアイテムを取得
	 *
	 * @param Item[] $contents
	 * @return void
	 */
	public function putAll(array $contents) :void
	{
		$this->tile->getInventory()->setContents($contents);
	}

	/**
	 * チェストを開かせる
	 *
	 * @param Player $player
	 * @return void
	 */
	public function sendWindow(Player $player) :void
	{
		$player->addWindow($this->tile->getInventory());
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