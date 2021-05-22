<?php

declare(strict_types=1);

namespace GildedRose;

final class Item
{
    /**
     * @var string
     */
    public $name;

    /**
     * @var int срок хранения - количество дней в течение которых мы должны продать товар
     */
    public $sell_in;

    /**
     * @var int качество
     */
    public $quality;

    /**
     * @param string $name
     * @param int $sell_in
     * @param int $quality
     */
    public function __construct(string $name, int $sell_in, int $quality)
    {
        $this->name = $name;
        $this->sell_in = $sell_in;
        $this->quality = $quality;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->name}, {$this->sell_in}, {$this->quality}";
    }
}
