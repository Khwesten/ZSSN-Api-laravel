<?php

namespace App\Http\Controllers;

use App\Repositories\Location\LocationEloquentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class LocationController
 *
 * @package App\Http\Controllers
 */
class LocationController extends Controller
{

    /**
     * LocationController constructor.
     *
     * @param LocationEloquentRepository $locationEloquentRepository
     */
    function __construct(LocationEloquentRepository $locationEloquentRepository)
    {
        $this->locationEloquentRepository = $locationEloquentRepository;
    }

    /**
     * @SWG\Put(
     *     path="/location",
     *     description="Update location from survivor",
     *     operationId="location.update",
     *     produces={"application/json"},
     *     tags={"location"},
     *
     *     @SWG\Parameter(name="body", in="body", required=true, type="object", @SWG\Schema(ref="#/definitions/Location")),
     *     @SWG\Parameter(name="survivorId", in="path", required=true, type="integer"),
     *
     *     @SWG\Response(response=200, description="Location updated successful!"),
     *     @SWG\Response(response=404, description="Survivor not found!"),
     *     @SWG\Response(response=422, description="Validation failed!"),
     * )
     *
     * @param int $survivorId
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function updateSurvivorLocation(int $survivorId, Request $request)
    {
        $bodyMessage = $request->input();

        $validator = Validator::make($bodyMessage, [
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        if ($validator->fails()) return $this->makeResponse($validator);

        $location = $this->locationEloquentRepository->findBySurvivorId($survivorId);

        if (!$location) {
            return response('Survivor not found!', 404);
        }

        $location->fill($bodyMessage);

        $this->locationEloquentRepository->setModel($location);
        $this->locationEloquentRepository->update();

        return response('Location updated successful!');
    }
}