<?php

use GildedRose\GildedRose;
use GildedRose\Item;

define('DIRSEP', DIRECTORY_SEPARATOR);

require_once __DIR__ . DIRSEP . '..' . DIRSEP . 'src' . DIRSEP . 'GildedRose.php';
require_once __DIR__ . DIRSEP . '..' . DIRSEP . 'src' . DIRSEP . 'Item.php';

$items = [
    new Item('+5 Dexterity Vest', 10, 20),
    new Item('Aged Brie', 2, 0),
    new Item('Elixir of the Mongoose', 5, 7),
    new Item('Sulfuras, Hand of Ragnaros', 0, 80),
    new Item('Sulfuras, Hand of Ragnaros', -1, 80),
    new Item('Backstage passes to a TAFKAL80ETC concert', 15, 20),
    new Item('Backstage passes to a TAFKAL80ETC concert', 10, 49),
    new Item('Backstage passes to a TAFKAL80ETC concert', 5, 49),
    new Item('Conjured Mana Cake', 3, 6),
];

for ($i = 1; $i <= 30; $i++) {
    echo '---------- day ' . $i . ' ----------<br>';

    $gildedRose = new GildedRose($items);
    $gildedRose->updateQuality();

    echo '<br>';
}
