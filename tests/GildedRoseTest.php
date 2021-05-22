<?php

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'GildedRose.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'Item.php';

class GildedRoseTest extends TestCase
{
    private GildedRose $gildedRose;

    /**
     * Проверяет, что метод updateQuality выбрасывает исключение, если quality > 50
     */
    public function testException_guardItemQualityLessThan50()
    {
        $this->expectExceptionMessageMatches('/^Item quality cannot be more than 50$/');

        $item = new Item('Elixir', 50, 9999);
        (new GildedRose([$item]))->updateQuality();
    }

    /**
     * Проверяет, что метод updateQuality выбрасывает исключение, если quality меньше нуля
     */
    public function testException_guardItemQualityNotNegative()
    {
        $this->expectDeprecationMessageMatches('/^Item quality cannot be negative$/');

        $item = new Item('Elixir', 50, -1);
        (new GildedRose([$item]))->updateQuality();
    }

    /**
     * Проверяет, что качество ленендарного товара никогда не изменяется
     * Проверяет, что метод updateQuality НЕ выбрасывает исключение для легендарного товара,
     * у которого значение свойства quality > 50
     */
    public function testUpdateQuality_legendaryItemQualityIsNeverChanges()
    {
        $sulfuras = new Item('Sulfuras, Hand of Ragnaros', 50, 80);
        (new GildedRose([$sulfuras]))->updateQuality();

        $this->assertEquals(80, $sulfuras->quality);
    }

    /**
     * Проверяет, что в конце дня метод updateQuality снижает значение свойства quality для каждого товара
     */
    public function testUpdateQuality_reducesQualityAtEndDay()
    {
        $item = new Item('Elixir', 50, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(39, $item->quality);
    }

    /**
     * Проверяет, что в конце дня метод updateQuality снижает значение свойства sell_in для каждого товара
     */
    public function testUpdateQuality_reducesSellinAtEndDay()
    {
        $item = new Item('Elixir', 50, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(49, $item->sell_in);
    }
}
