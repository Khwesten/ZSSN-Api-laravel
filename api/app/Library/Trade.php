<?php

namespace App\Library;

use App\Repositories\Item\ItemEloquentRepository;
use App\Repositories\SurvivorItem\SurvivorItemEloquentRepository;
use App\Survivor;
use App\SurvivorItem;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 29/06/2017
 * Time: 16:53
 */
class Trade
{
    private $survivor;
    private $anotherSurvivor;
    private $itemsOnTrade;
    private $survivorListOfTrade;
    private $anotherSurvivorListOfTrade;
    private $itemEloquentRepository;
    private $survivorItemEloquentRepository;

    function __construct(ItemEloquentRepository $itemEloquentRepository, SurvivorItemEloquentRepository $survivorItemEloquentRepository)
    {
        $this->itemEloquentRepository = $itemEloquentRepository;
        $this->survivorItemEloquentRepository = $survivorItemEloquentRepository;
        $this->itemsOnTrade = [];
    }

    public function isValidLists(): bool
    {
        $isValidList = $this->checkIsValidList($this->survivorListOfTrade);
        $isValidAnotherList = $this->checkIsValidList($this->anotherSurvivorListOfTrade);

        $isValid = ($isValidList && $isValidAnotherList);

        return $isValid;
    }

    public function isBalanced(): bool
    {
        $amountOfSurvivor = $this->amountPointsOnList($this->survivorListOfTrade);
        $amountOfAnotherSurvivor = $this->amountPointsOnList($this->anotherSurvivorListOfTrade);

        $isBalanced = ($amountOfSurvivor == $amountOfAnotherSurvivor);

        return $isBalanced;
    }

    public function hasAllItemsFromTrade(): bool
    {
        $hasItemsToTradeOnSurvivor = $this->hasAllItemsOnSurvivor($this->survivor, $this->survivorListOfTrade);
        $hasItemsToTradeOnAnotherSurvivor = $this->hasAllItemsOnSurvivor(
            $this->anotherSurvivor, $this->anotherSurvivorListOfTrade
        );

        $hasAllItemsFromTrade = ($hasItemsToTradeOnSurvivor && $hasItemsToTradeOnAnotherSurvivor);

        return $hasAllItemsFromTrade;
    }

    public function trade()
    {
        $this->giveItemsTo($this->survivor, $this->anotherSurvivorListOfTrade);
        $this->giveItemsTo($this->anotherSurvivor, $this->survivorListOfTrade);

        //todo make assync
        $this->removeItemsFrom($this->survivor, $this->survivorListOfTrade);
        $this->removeItemsFrom($this->anotherSurvivor, $this->anotherSurvivorListOfTrade);
    }

    private function giveItemsTo(Survivor $survivor, array $listOfItems)
    {
        $collectionSurvivorItem = $survivor->survivorItems()->get();

        foreach ($listOfItems as $itemFromTrade) {
            $hasItem = false;

            $itemFromTradeId = $itemFromTrade['id'];
            $itemFromTradeQuantity = $itemFromTrade['quantity'];

            foreach ($collectionSurvivorItem as $survivorItem) {
                if ($survivorItem->item->id == $itemFromTradeId) {
                    $survivorItem->quantity += $itemFromTradeQuantity;

                    $this->survivorItemEloquentRepository->setModel($survivorItem);
                    $this->survivorItemEloquentRepository->update();

                    $hasItem = true;
                }
            }

            if (!$hasItem) {
                $item = $this->itemEloquentRepository->find($itemFromTradeId);

                $survivorItem = resolve(SurvivorItem::class);

                $survivorItem->survivor()->associate($survivor);
                $survivorItem->item()->associate($item);

                $survivorItem->quantity = $itemFromTradeQuantity;

                $this->survivorItemEloquentRepository->setModel($survivorItem);
                $this->survivorItemEloquentRepository->addSurvivorItemWithUser();
            }
        };
    }

    private function removeItemsFrom(Survivor $survivor, array $listOfItems)
    {
        $collectionSurvivorItem = $survivor->survivorItems()->get();

        foreach ($collectionSurvivorItem as $survivorItem) {
            $this->survivorItemEloquentRepository->setModel($survivorItem);

            foreach ($listOfItems as $itemToRemove) {
                $itemToRemoveId = $itemToRemove['id'];
                $itemToRemoveQuantity = $itemToRemove['quantity'];

                if ($survivorItem->item->id == $itemToRemoveId) {
                    if ($survivorItem->quantity == $itemToRemoveQuantity) {
                        $this->survivorItemEloquentRepository->delete($survivorItem->id);
                    } else {
                        $survivorItem->quantity -= $itemToRemoveQuantity;

                        $this->survivorItemEloquentRepository->update();
                    }
                }
            }
        }
    }

    private function hasAllItemsOnSurvivor(Survivor $survivor, array $listOfItemsToTrade): bool
    {
        $arrayToTrade = $this->makeArrayToCompare($listOfItemsToTrade);
        $arrayUserItems = $this->makeArrayFromCollectionToCompare($survivor->survivorItems());

        $arrayDiff = array_diff_assoc($arrayToTrade, $arrayUserItems);

        $hasAllItems = (sizeof($arrayDiff) == 0);

        return $hasAllItems;
    }

    public function setSurvivor(Survivor $survivor)
    {
        $this->survivor = $survivor;
    }

    public function setAnotherSurvivor(Survivor $anotherSurvivor)
    {
        $this->anotherSurvivor = $anotherSurvivor;
    }

    public function setSurvivorListOfTrade(array $listOfTrade)
    {
        $this->survivorListOfTrade = $listOfTrade;
    }

    public function setAnotherSurvivorListOfTrade(array $listOfTrade)
    {
        $this->anotherSurvivorListOfTrade = $listOfTrade;
    }

    private function makeArrayToCompare(array $itemsToTrade): array
    {
        $arrayToCompare = [];

        foreach ($itemsToTrade as $key => $item) {
            $arrayToCompare[$item['id']] = $item['quantity'];
        }

        return $arrayToCompare;
    }

    private function makeArrayFromCollectionToCompare(HasMany $collection): array
    {
        $arrayToCompare = [];

        $collection->each(function ($survivorItem) use (&$arrayToCompare) {
            $id = $survivorItem->item->id;
            $quantity = $survivorItem->quantity;

            $arrayToCompare[$id] = $quantity;
        });

        return $arrayToCompare;
    }

    private function checkIsValidList(array $array): bool
    {
        foreach ($array as $item) {
            $itemId = $item['id'];

            if (!array_key_exists($itemId, $this->itemsOnTrade)) {
                $item = $this->itemEloquentRepository->find($itemId);

                if ($item) {
                    $this->itemsOnTrade[$itemId] = $item->points;
                } else {
                    return false;
                }
            }
        }

        return true;
    }

    private function amountPointsOnList(array $listOfItems): int
    {
        $amountOfPoints = 0;

        foreach ($listOfItems as $item) {
            $quantity = $item['quantity'];
            $id = $item['id'];

            $points = $this->itemsOnTrade[$id] * $quantity;

            $amountOfPoints += $points;
        }

        return $amountOfPoints;
    }
}