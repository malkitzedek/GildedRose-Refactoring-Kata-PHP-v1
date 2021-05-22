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

        $item = new Item('Elixir', 100, 9999);
        (new GildedRose([$item]))->updateQuality();
    }

    /**
     * Проверяет, что метод updateQuality выбрасывает исключение, если quality меньше нуля
     */
    public function testException_guardItemQualityNotNegative()
    {
        $this->expectDeprecationMessageMatches('/^Item quality cannot be negative$/');

        $item = new Item('Elixir', 100, -1);
        (new GildedRose([$item]))->updateQuality();
    }

    /**
     * Проверяет, что качество ленендарного товара никогда не изменяется
     * Проверяет, что метод updateQuality НЕ выбрасывает исключение для легендарного товара,
     * у которого значение свойства quality > 50
     */
    public function testUpdateQuality_legendaryItemQualityIsNeverChanges()
    {
        $sulfuras = new Item('Sulfuras, Hand of Ragnaros', 100, 80);
        (new GildedRose([$sulfuras]))->updateQuality();

        $this->assertEquals(80, $sulfuras->quality);
    }

    /**
     * Проверяет, что в конце дня метод updateQuality снижает значение свойства quality для каждого товара
     */
    public function testUpdateQuality_reducesQualityAtEndDay()
    {
        $item = new Item('Elixir', 100, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(39, $item->quality);
    }

    /**
     * Проверяет, что в конце дня метод updateQuality снижает значение свойства sell_in для каждого товара
     */
    public function testUpdateQuality_reducesSellinAtEndDay()
    {
        $item = new Item('Elixir', 100, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(99, $item->sell_in);
    }

    /**
     * Проверяет, что после того, как срок храния прошел,
     * значение свойства quality уменьшается в два раза быстрее
     *
     * TODO: Уточнить, когда считать, что срох хранения прошёл: при sell_in < 0 или при sell_in <= 0
     *
     */
    public function testUpdateQuality_qualityReduce2TimesFasterIfSellinIsOver()
    {
        $item = new Item('Elixir', -1, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(38, $item->quality);
    }

    /**
     * Проверяет, что качество товара «Aged Brie» увеличивается по мере уменьшения срока хранения
     */
    public function testUpdateQuality_AgedBrieItemQuality()
    {
        $item = new Item('Aged Brie', 100, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(41, $item->quality);
    }

    /**
     * Проверяет, что качество товара «Backstage passes» увеличивается по мере уменьшения срока хранения
     */
    public function testUpdateQuality_BackstagePassesItemQuality()
    {
        $item = new Item('Backstage passes to a TAFKAL80ETC concert', 100, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(41, $item->quality);
    }

    /**
     * Проверяет, что качество товара «Backstage passes» увеличивается на 2
     * когда до истечения срока хранения 10 или менее дней
     */
    public function testUpdateQuality_BackstagePassesItemQualityIfSellinLessThen11()
    {
        $item = new Item('Backstage passes to a TAFKAL80ETC concert', 10, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(42, $item->quality);
    }

    /**
     * Проверяет, что качество товара «Backstage passes» увеличивается на 3
     * когда до истечения срока хранения 5 или менее дней
     */
    public function testUpdateQuality_BackstagePassesItemQualityIfSellinLessThen5()
    {
        $item = new Item('Backstage passes to a TAFKAL80ETC concert', 5, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(43, $item->quality);
    }

    /**
     * Проверяет, что качество товара «Backstage passes» падает до 0 после даты проведения концерта,
     * то есть когда значение свойства sell_in становится равно 0
     */
    public function testUpdateQuality_BackstagePassesItemQualityIfSellinIsOver()
    {
        $item = new Item('Backstage passes to a TAFKAL80ETC concert', -1, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(0, $item->quality);
    }

    /**
     * Проверяет, что «Conjured» товары теряют качество в два раза быстрее, чем обычные товары
     */
    public function testUpdateQuality_ConjuredItemQuality()
    {
        $item = new Item('Conjured Mana Cake', 100, 40);
        (new GildedRose([$item]))->updateQuality();

        $this->assertEquals(38, $item->quality);
    }
}
