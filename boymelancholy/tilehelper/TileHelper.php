<?php

declare(strict_types=1);

namespace boymelancholy\tilehelper;

use boymelancholy\tilehelper\utils\Angle;
use boymelancholy\tilehelper\utils\Sort;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\BlockIds;
use pocketmine\inventory\Inventory;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\nbt\JsonNbtParser;
use pocketmine\Player;
use pocketmine\tile\Chest;
use pocketmine\tile\Sign;
use pocketmine\tile\Furnace;
use pocketmine\tile\ItemFrame;
use pocketmine\tile\Tile;

/**
 * Tileのヘルパー
 * チェスト、アイテムフレーム、釜戸、看板
 *
 * @author boymelancholy
 */
class TileHelper
{
    /**
     * 扱えるチェストか確認
     *
     * @param Block $block
     * @return Chest|null
     */
    public static function isValidChestTile(Block $block) :?Chest
    {
        $tile = $block->getLevel()->getTile($block);
        if ($tile instanceof Chest) {
            if ($tile->isValid()) {
                return $tile;
            } else {
                return null;
            }
        }
        return null;
    }

    /**
     * 扱える看板か確認
     *
     * @param Block $block
     * @return Sign|null
     */
    public static function isValidSignTile(Block $block) :?Sign
    {
        $tile = $block->getLevel()->getTile($block);
        if ($tile instanceof Sign) {
            if ($tile->isValid()) {
                return $tile;
            } else {
                return null;
            }
        }
        return null;
    }

    /**
     * 扱える釜戸か確認
     *
     * @param Block $block
     * @return Furnace|null
     */
    public static function isValidFurnaceTile(Block $block) :?Furnace
    {
        $tile = $block->getLevel()->getTile($block);
        if ($tile instanceof Furnace) {
            if ($tile->isValid()) {
                return $tile;
            } else {
                return null;
            }
        }
        return null;
    }

    /**
     * 扱えるアイテムフレームか確認
     *
     * @param Block $block
     * @return ItemFrame|null
     */
    public static function isValidItemFrameTile(Block $block) :?ItemFrame
    {
        $tile = $block->getLevel()->getTile($block);
        if ($tile instanceof ItemFrame) {
            if ($tile->isValid()) {
                return $tile;
            } else {
                return null;
            }
        }
        return null;
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
     * 鍵付きチェストの取得
     *
     * @param string $key 鍵の名前
     * @return Item
     */
    public static function getLockedChest(string $key) :Item
    {
        $item = ItemFactory::get(ItemIds::CHEST);
        $item->setNamedTag(JsonNbtParser::parseJson('BlockEntityTag:{Lock:'.$key.'}'));
        return $item;
    }

    /**
     * 鍵付き釜戸の取得
     *
     * @param string $key 鍵の名前
     * @return Item
     */
    public static function getLockedFurnace(string $key) :Item
    {
        $item = ItemFactory::get(ItemIds::FURNACE);
        $item->setNamedTag(JsonNbtParser::parseJson('BlockEntityTag:{Lock:'.$key.'}'));
        return $item;
    }

    /**
     * 施錠するアイテムの作成
     *
     * @param Item $item
     * @param string $key 鍵の名前
     * @return Item
     */
    public static function getUnLockKey(Item $item, string $key) :Item
    {
        $item = clone $item;
        $item->setCustomName($key);
        return $item;
    }

    /**
     * 文字を含む看板の取得
     *
     * @param string[] $lines
     * @return Item
     */
    public static function getWrittenSign(array $lines) :Item
    {
        $item = ItemFactory::get(ItemIds::SIGN);
        $item->setNamedTag(JsonNbtParser::parseJson('{BlockEntityTag:{Text1:'.$lines[0].',Text2:'.$lines[1].',Text3:'.$lines[2].',Text4:'.$lines[3].'}}'));
        return $item;
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

    /**
     * インベントリーの情報置き換え
     *
     * @param Inventory $base
     * @param Inventory $latest
     * @return void
     */
    public static function updateInventory(Inventory $base, Inventory $latest) :void
    {
        $base->setContents($latest->getContents());
    }
}