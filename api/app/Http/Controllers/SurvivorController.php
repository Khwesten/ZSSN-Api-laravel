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
use Illuminate\Validation\Rule;

/**
 * Class SurvivorController
 *
 * @package App\Http\Controllers
 */
class SurvivorController extends Controller
{
    const BASIC_SURVIVOR_ITEMS = [];
    const WATER_ITEM_NAME = 'Water';
    const FOOD_ITEM_NAME = 'Food';
    const MEDICATION_ITEM_NAME = 'Medication';
    const AMMUNITION_ITEM_NAME = 'Ammunition';

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
    }

    /**
     * @SWG\Post(
     *     path="/survivor",
     *     description="Create a survivor",
     *     operationId="survivor.create",
     *     produces={"application/json"},
     *     tags={"survivor"},
     *
     *     @SWG\Response(response=200, description="Survivor saved successful!"),
     *     @SWG\Response(response=422, description="Validation failed!"),
     *
     *     @SWG\Parameter(
     *         name="body", in="body", required=true, type="object", @SWG\Schema(ref="#/definitions/Survivor")
     *     )
     * )
     *
     * @param Request $request
     * @param Location $location
     * @param Survivor $survivor
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, Location $location, Survivor $survivor)
    {
        $bodyMessage = $request->input();

        // TODO try to use this
//        $this->validate($request,[
        $validator = Validator::make($bodyMessage, [
            'name' => 'required|max:255',
            'gender' => [Rule::in(['M', 'F']), 'required', 'max:1'],
            'age' => 'required|numeric|min:1',
            'location.latitude' => 'required',
            'location.longitude' => 'required'
        ]);

        if ($validator->fails()) return $this->makeResponse($validator);

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

    /**
     * @SWG\Post(
     *     path="/survivor/{survivorId}/trade-items-with/{anotherSurvivorId}",
     *     description="Create a survivor",
     *     operationId="survivor.create",
     *     produces={"application/json"},
     *     tags={"survivor"},
     *
     *     @SWG\Response(response=200, description="Trade successful!"),
     *     @SWG\Response(response=400, description="The server cannot process the request!"),
     *     @SWG\Response(response=404, description="Survivor not found!"),
     *
     *     @SWG\Parameter(name="survivorId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="anotherSurvivorId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(
     *         name="body", in="body", required=true, type="object", @SWG\Schema(ref="#/definitions/Trade")
     *     )
     * )
     *
     * @param int $survivorId
     * @param int $anotherSurvivorId
     * @param Request $request
     * @param Trade $trade
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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
            return response("Survivor not found!", 404);
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
            return response("One of survivors don't have all items to trade!", 400);
        }

        if (!$trade->isBalanced()) {
            return response("The list of items to trade isn't balanced!", 400);
        }

        $trade->trade();

        return response("Trade successful!");
    }

    /**
     * @SWG\Get(
     *     path="/survivor/report",
     *     description="Report from Survivor",
     *     operationId="survivor.report",
     *     produces={"application/json"},
     *     tags={"survivor"},
     *     @SWG\Response(response=200, description="")
     * )
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
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
            "infectedSurvivors" => $percentageOfInfectedUsers,
            "nonInfectedSurvivors" => $percentageOfNonInfectedUsers,
            "pointsLost" => $infectedUserPoints,
            "resourceBySurvivor" => $quantitiesOfResourcesTypeByUser
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