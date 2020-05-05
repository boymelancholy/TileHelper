<?php

declare(strict_types=1);

namespace boymelancholy\tilehelper\utils;

/**
 * ソート
 */
class Sort
{
    public const RANDOM = -1;

    public const NAME_DESCENDING = 0;
    public const NAME_ASCENDING = 1;

    public const ID_DESCENDING = 2;
    public const ID_ASCENDING = 3;

    public const AMOUNT_DESCENDING = 4;
    public const AMOUNT_ASCENDING = 5;

    /** @var integer */
    private $sortType;

    /** @var Item[] */
    private $contents;

    /**
     * コンストラクタ
     *
     * @param Item[] $targets
     * @return Sort
     */
    public function __construct(array $targets)
    {
        $this->contents = $targets;
    }

    /**
     * ソートタイプの決定
     *
     * @param integer $type
     * @return Sort
     */
    public function setSortType(int $type = self::RANDOM) :Sort
    {
        $this->sortType = $type;
        return $this;
    }

    /**
     * ソート
     *
     * @return Item[]
     */
    public function start() :array
    {
        switch ($this->sortType) {
            case self::NAME_ASCENDING:
                return $this->sortByName();
            break;

            case self::NAME_DESCENDING:
                return $this->sortByName(false);
            break;

            case self::ID_ASCENDING:
                return $this->sortById();
            break;

            case self::ID_DESCENDING:
                return $this->sortById(false);
            break;

            case self::AMOUNT_ASCENDING:
                return $this->sortByAmount();
            break;

            case self::AMOUNT_DESCENDING:
                return $this->sortByAmount(false);
            break;
        }
    }

    /**
     * 名前でソート
     *
     * @return Item[]
     */
    private function sortByName($ascending = true) :array
    {
        foreach ($this->contents as &$content) {
            $names[$content->getName()] = $content;
        }

        if ($ascending) {
            ksort($names);
        } else {
            krsort($names);
        }

        return array_values($names);
    }

    /**
     * IDでソート
     *
     * @return Item[]
     */
    private function sortById($ascending = true) :array
    {
        foreach ($this->contents as &$content) {
            $ids[$content->getId()] = $content;
        }

        if ($ascending) {
            ksort($ids);
        } else {
            krsort($ids);
        }

        return array_values($ids);
    }

    /**
     * 個数でソート
     *
     * @return Item[]
     */
    private function sortByAmount($ascending = true) :array
    {
        foreach ($this->contents as &$content) {
            $amounts[$content->getCount()] = $content;
        }

        if ($ascending) {
            ksort($amounts);
        } else {
            krsort($amounts);
        }

        return array_values($amounts);
    }

}