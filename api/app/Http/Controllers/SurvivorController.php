<?php

namespace App\Http\Controllers;

use App\Library\Trade;
use App\Location;
use App\Repositories\Item\ItemEloquentRepository;
use App\Repositories\Location\LocationEloquentRepository;
use App\Repositories\Survivor\SurvivorEloquentRepository;
use App\Repositories\SurvivorItem\SurvivorItemEloquentRepository;
use App\Survivor;
use App\SurvivorItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 14:53
 */
class SurvivorController extends Controller
{
    const BASIC_SURVIVOR_ITEMS = [];
    const WATER_ITEM_NAME = 'Water';
    const FOOD_ITEM_NAME = 'Food';
    const MEDICATION_ITEM_NAME = 'Medication';
    const AMMUNITION_ITEM_NAME = 'Ammunition';

    private $arrayValidItems;

    /**
     * SurvivorController constructor.
     *
     * @param SurvivorEloquentRepository $survivorEloquentRepository
     * @param LocationEloquentRepository $locationEloquentRepository
     * @param ItemEloquentRepository $itemEloquentRepository
     * @param SurvivorItemEloquentRepository $survivorItemEloquentRepository
     */
    public function __construct(
        SurvivorEloquentRepository $survivorEloquentRepository,
        LocationEloquentRepository $locationEloquentRepository,
        ItemEloquentRepository $itemEloquentRepository,
        SurvivorItemEloquentRepository $survivorItemEloquentRepository
    )
    {
        $this->survivorEloquentRepository = $survivorEloquentRepository;
        $this->locationEloquentRepository = $locationEloquentRepository;
        $this->itemEloquentRepository = $itemEloquentRepository;
        $this->survivorItemEloquentRepository = $survivorItemEloquentRepository;

        $this->arrayValidItems = [];
    }

    public function create(Request $request, Location $location, Survivor $survivor)
    {
        $bodyMessage = $request->input();

        // TODO try use it
//        $this->validate($request,[
        $validator = Validator::make($bodyMessage, [
            'name' => 'required|max:255',
            'gender' => 'required|max:1',
            'age' => 'required',
            'location.latitude' => 'required',
            'location.longitude' => 'required'
        ]);

        $errors = $validator->errors()->all();

        if ($errors) {
            return response([
                'message' => 'Validation Failed',
                'errors' => $errors
            ], 400);
        }

        $survivorData = $bodyMessage;
        $locationData = $bodyMessage['location'];

        $survivor->fill($survivorData);

        $this->survivorEloquentRepository->setModel($survivor);
        $survivor = $this->survivorEloquentRepository->create();

        $location->fill($locationData);
        $location->survivor()->associate($survivor);

        $this->makeBasicSurvivorItems($survivor);

        $this->locationEloquentRepository->setModel($location);
        $this->locationEloquentRepository->create();

        return response('Survivor saved successful!');
    }

    public function tradeItems(int $survivorId, int $anotherSurvivorId, Request $request, Trade $trade)
    {
        $bodyMessage = $request->input();

        $hasSurvivorIdOnListOfTrade = key_exists($survivorId, $bodyMessage);
        $hasAnotherSurvivorIdOnListOfTrade = key_exists($anotherSurvivorId, $bodyMessage);

        if (!$hasSurvivorIdOnListOfTrade || !$hasAnotherSurvivorIdOnListOfTrade) {
            return response('Survivor IDs do not match the body of the message!', 400);
        }

        $survivor = $this->survivorEloquentRepository->find($survivorId);
        $anotherSurvivor = $this->survivorEloquentRepository->find($anotherSurvivorId);

        if (!$survivor || !$anotherSurvivor) {
            return response("The survivors doesn't exists!", 400);
        }

        $itemsToTradeOfSurvivor = $bodyMessage[$survivorId]['items'];
        $itemsToTradeOfAnotherSurvivor = $bodyMessage[$anotherSurvivorId]['items'];

        $trade->setSurvivor($survivor);
        $trade->setAnotherSurvivor($anotherSurvivor);
        $trade->setSurvivorListOfTrade($itemsToTradeOfSurvivor);
        $trade->setAnotherSurvivorListOfTrade($itemsToTradeOfAnotherSurvivor);

        if (!$trade->isValidLists()) {
            return response(
                "The list don't can has any item with quantity 0 or your item doesn't match a valid item.", 400
            );
        }

        if (!$trade->hasAllItemsFromTrade()) {
            return response("One of survivors don't have all itens to trade!", 400);
        }

        if (!$trade->isBalanced()) {
            return response("The list of items to trade isn't balanced!", 400);
        }

        $trade->trade();

        return response("Trade successful!");
    }

    public function report()
    {
        $countOfAllSurvivors = $this->survivorEloquentRepository->countAllSurvivors();
        $countOfInfectedSurvivors = $this->survivorEloquentRepository->countInfectedSurvivors();

        $percentageOfInfectedUsers = ($countOfInfectedSurvivors * $countOfAllSurvivors) / 100;
        $percentageOfNonInfectedUsers = 100 - $percentageOfInfectedUsers;

        $numberOfSurvivors = $countOfAllSurvivors - $countOfInfectedSurvivors;

        $infectedUserPoints = $this->survivorItemEloquentRepository->countPointsFromInfectedSurvivors();

        $quantitiesOfItemsByKind = $this->survivorItemEloquentRepository->getAmountOfItemsByKind();

        $quantitiesOfResourcesTypeByUser = [];

        foreach ($quantitiesOfItemsByKind as $object) {
            $quantitiesOfResourcesTypeByUser[] = [
                "name" => $object->name,
                "quantityPerUser" => round($object->quantity / $numberOfSurvivors, 3)
            ];
        }

        $response = [
            "infectedUsers" => $percentageOfInfectedUsers,
            "nonInfectedUsers" => $percentageOfNonInfectedUsers,
            "pointsLostBecauseInfectedUser" => $infectedUserPoints,
            "averageAmountOfEachKindOfResourceBySurvivor" => $quantitiesOfResourcesTypeByUser
        ];

        return response($response);
    }

    private function makeBasicSurvivorItems(Survivor $survivor)
    {
        $water = $this->itemEloquentRepository->findByName(self::WATER_ITEM_NAME);
        $food = $this->itemEloquentRepository->findByName(self::FOOD_ITEM_NAME);
        $medication = $this->itemEloquentRepository->findByName(self::MEDICATION_ITEM_NAME);
        $ammunition = $this->itemEloquentRepository->findByName(self::AMMUNITION_ITEM_NAME);

        if (!$water || !$food || !$medication || !$ammunition) {
            return response("Seed the database!", 500);
        }

        $basicItems = [$water, $food, $medication, $ammunition];

        foreach ($basicItems as $item) {
            $survivorItem = resolve(SurvivorItem::class);

            $survivorItem->survivor()->associate($survivor);
            $survivorItem->item()->associate($item);
            $survivorItem->quantity = 1;

            $this->survivorItemEloquentRepository->setModel($survivorItem);
            $this->survivorItemEloquentRepository->addSurvivorItemWithUser();
        }
    }
}