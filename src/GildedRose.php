<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    /**
     * @var Item[]
     */
    private $items;

    /**
     * @param array $items
     */
    public function __construct(array $items)
    {
        $this->items = $items;
    }

    /**
     * @throws \Exception
     */
    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $this->guardItemQualityLessThan50($item);
            $this->guardItemQualityNotNegative($item);

            switch ($this->getItemType($item)) {
                case 'legendary':
                    $this->updateQualityForLegendaryItem($item);
                    break;
                case 'conjured':
                    $this->updateQualityForConjuredItem($item);
                    break;
                case 'aged_brie':
                    $this->updateQualityForAgedBrie($item);
                    break;
                case 'backstage_passes':
                    $this->updateQualityForBackstagePasses($item);
                    break;
                default:
                    $this->updateQualityForOrdinaryItem($item);
            }

            $item->sell_in--;

            echo $item . '<br>';
        }
    }

    /**
     * @param Item $item
     * @throws \Exception
     */
    private function guardItemQualityLessThan50(Item $item): void
    {
        if ($item->quality > 50 && !$this->isLegendary($item)) {
            throw new \Exception('Item quality cannot be more than 50');
        }
    }

    /**
     * @param Item $item
     * @throws \Exception
     */
    private function guardItemQualityNotNegative(Item $item): void
    {
        if ($item->quality < 0) {
            throw new \Exception('Item quality cannot be negative');
        }
    }

    /**
     * @param Item $item
     * @return string
     */
    private function getItemType(Item $item): string
    {
        if ($this->isLegendary($item)) {
            return 'legendary';
        }

        if ($this->isConjured($item)) {
            return 'conjured';
        }

        if ($item->name === 'Aged Brie') {
            return 'aged_brie';
        }

        if ($item->name === 'Backstage passes to a TAFKAL80ETC concert') {
            return 'backstage_passes';
        }

        return 'ordinary';
    }

    /**
     * @return string[]
     */
    private function getLegendaryItems(): array
    {
        return [
            'Sulfuras, Hand of Ragnaros',
        ];
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isLegendary(Item $item): bool
    {
        return in_array($item->name, $this->getLegendaryItems());
    }

    /**
     * @param Item $item
     * @return bool
     */
    private function isConjured(Item $item): bool
    {
        return strpos($item->name, 'Conjured') !== false;
    }

    /**
     * @param Item $item
     */
    private function updateQualityForOrdinaryItem(Item $item): void
    {
        if ($item->quality === 0) {
            return;
        }

        if ($item->sell_in <= 0) {
            $item->quality = ($item->quality - 2 > 0) ? ($item->quality - 2) : 0;
            return;
        }

        $item->quality--;
    }

    /**
     * @param Item $item
     */
    private function updateQualityForLegendaryItem(Item $item): void
    {
        // ничего не делаем со свойством $item->quality легендарного товара
    }

    /**
     * @param Item $item
     */
    private function updateQualityForConjuredItem(Item $item): void
    {
        if ($item->quality === 0) {
            return;
        }

        if ($item->sell_in <= 0) {
            $item->quality = ($item->quality - 4 > 0) ? ($item->quality - 4) : 0;
            return;
        }

        $item->quality = ($item->quality - 2 > 0) ? ($item->quality - 2) : 0;
    }

    /**
     * Вообще не понял, что означает фраза: "качество увеличивается пропорционально возрасту"
     * Скорее всего, некорректный перевод в файле GildedRoseRequirements_ru.txt
     * Нужно уточнение от составителя ТЗ
     *
     * @param Item $item
     */
    private function updateQualityForAgedBrie(Item $item): void
    {
        if ($item->quality === 50) {
            return;
        }

        if ($item->sell_in <= 0) {
            $item->quality = ($item->quality + 2 < 50) ? ($item->quality + 2) : 50;
            return;
        }

        $item->quality++;
    }

    /**
     * @param Item $item
     */
    private function updateQualityForBackstagePasses(Item $item): void
    {
        if ($item->sell_in < 0) {
            $item->quality = 0;
            return;
        }

        if ($item->quality === 50) {
            return;
        }

        if ($item->sell_in <= 5) {
            $item->quality = ($item->quality + 3 < 50) ? ($item->quality + 3) : 50;
            return;
        }


        if ($item->sell_in <= 10) {
            $item->quality = ($item->quality + 2 < 50) ? ($item->quality + 2) : 50;
            return;
        }

        $item->quality++;
    }
}
