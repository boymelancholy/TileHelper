<?php

declare(strict_types=1);

namespace boymelancholy\tilehelper\utils;

use pocketmine\tile\ItemFrame;

/**
 * フレームに飾られたアイテムの角度調整
 *
 * @author boymelancholy
 */
class Angle
{
    const ANGLE_0 = 0;
    const ANGLE_45 = 1;
    const ANGLE_90 = 2;
    const ANGLE_135 = 3;
    const ANGLE_180 = 4;
    const ANGLE_225 = 5;
    const ANGLE_270 = 6;
    const ANGLE_315 = 7;

    /** @var ItemFrame */
    private $itemFrame;

    public function __construct(ItemFrame $itemFrame)
    {
        $this->itemFrame = $itemFrame;
    }

    public function setAngle(int $angle = self::ANGLE_0) :void
    {
        $this->itemFrame->setItemRotation($angle);
        $this->itemFrame->saveNBT();
    }
}