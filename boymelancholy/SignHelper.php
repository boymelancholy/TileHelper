<?php

declare(strict_types=1);

namespace TileHandleAPI\boymelancholy\tileapi\handlers;

use pocketmine\tile\Sign;
use pocketmine\block\Block;

/**
 * 看板
 *
 * @author boymelancholy
 */
class SignHandler extends Handler
{
	/**
	 * 継承
	 *
	 * @param Block $block
	 * @return SignHandler|null
	 */
	public function __construct(Block $block)
	{
		$tile = $block->getLevel()->getTile($block);
		if (!($tile instanceof Sign)) return null;
		parent::__construct($tile);
	}

	/**
	 * 看板の文字を取得
	 *
	 * @param Block $block
	 * @param integer $num 行数
	 * @return string|null
	 */
	public function read(int $num) :?string
	{
		return $this->readAll($this->tile)[$num];
	}

	/**
	 * 看板の文字をすべて取得
	 *
	 * @param Block $block
	 * @return string[]|null
	 */
	public function readAll() :?array
	{
		return $this->tile->getText();
	}

	/**
	 * 看板の文字を書き換え
	 *
	 * @param integer $num 行数
	 * @param string $replacement 変換する新規文字列
	 * @return boolean
	 */
	public function write(int $num, string $replacement='') :bool
	{
		$this->tile->setLine($num, $replacement);
		$this->tile->saveNBT();
		return true;
	}

	/**
	 * 看板の文字を書き換え
	 *
	 * @param array $replacements 変換する新規文字列配列
	 * @return boolean
	 */
	public function writeAll(array $replacements=[]) :bool
	{
		$lines = $this->readAll();
		$newLines = array_replace($lines, $replacements);
		for ($i=0; $i<4; ++$i) $this->tile->setLine($i, $newLines[$i]);
		$this->tile->saveNBT();
		return true;
	}
}