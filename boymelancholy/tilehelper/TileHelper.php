<?php

declare(strict_types=1);

namespace boymelancholy\tilehelper;

use boymelancholy\tilehelper\utils\Angle;
use boymelancholy\tilehelper\utils\Sort;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\inventory\Inventory;
use pocketmine\Player;
use pocketmine\tile\Chest;
use pocketmine\tile\Sign;
use pocketmine\tile\Furnace;
use pocketmine\tile\ItemFrame;
use pocketmine\tile\Tile;

/**
 * Tileのヘルパー
 * チェスト、アイテムフレーム、釜戸
 *
 * @author boymelancholy
 */
class TileHelper
{
    /**
     * 扱えるタイルオブジェクトか確認
     *
     * @param Block $block
     * @return boolean
     */
    public static function isValidTile(Block $block) :bool
    {
        $tile = $block->getLevel()->getTile($block);
        if ($tile instanceof Tile) {
            return ($tile->isValid());
        }
        return false;
    }

    /**
     * チェストの中身を並び変える
     *
     * @param Chest $chest
     * @param integer $sort
     * @return Item[]
     */
    public static function sortContents(Chest $chest, int $sort = Sort::RANDOM) :array
    {
        $contents = $chest->getInventory()->getContents();
        $sorter = new Sort($contents);
        $sorter->setSortType($sort);
        return $sorter->start();
    }

    /**
     * アイテムフレームのアイテムの角度調整
     *
     * @param ItemFrame $itemFrame
     * @return void
     */
    public static function setItemAngle(ItemFrame $itemFrame, int $angle = Angle::ANGLE_0) :void
    {
        $angler = new Angle($itemFrame);
        $angler->setAngle($angle);
    }

    /**
     * 看板の文字の置き換え
     *
     * @param Sign $sign
     * @param string[] $replacements
     * @return void
     */
    public static function replaceLines(Sign $sign, array $replacements = []) :void
    {
        for ($i=0; $i<4; ++$i) {
            if (!isset($replacements[$i])) {
                $replacements[$i] = '';
            }
            $sign->setLine($i, $replacements[$i]);
        }
        $sign->saveNBT();
    }

    /**
     * 看板の文字の上書き
     *
     * @param Sign $sign
     * @param string[] $overwriting
     * @return void
     */
    public static function overwriteLines(Sign $sign, array $overwriting = []) :void
    {
        for ($i=0; $i<4; ++$i) {
            if (!isset($overwriting[$i]) || $overwriting[$i] === '') {
                return;
            }
            $sign->setLine($i, $overwriting[$i]);
        }
        $sign->saveNBT();
    }

    /**
     * 偽インベントリーの取得
     *
     * @param Player $player
     * @param Chest|Furnace $baseTile
     * @return Inventory
     */
    public static function getFakeInventory(Player $player, $baseTile) :Inventory
    {
        $makePos = $player->add(0, 3, 0);
        $level = $player->getLevel();
        $nbt = Tile::createNBT($makePos);
        switch (true) {

            case $baseTile instanceof Chest:
                $block = $level->setBlock($makePos, BlockFactory::get(BlockIds::CHEST));
                $type = Tile::CHEST;
            break;

            case $baseTile instanceof Furnace:
                $block = $level->setBlock($makePos, BlockFactory::get(BlockIds::FURNACE));
                $type = Tile::FURNACE;
            break;
        }
        $level->sendBlocks([$player], [$block]);
        $fake = Tile::createTile($type, $level, $nbt);

        /** @var Chest|Furnace $fake */
        $fake->spawnTo($player);

        $contents = $fake->getInventory()->getContents();
        $fake->getInventory()->setContents($contents);

        return $fake->getInventory();
    }
}