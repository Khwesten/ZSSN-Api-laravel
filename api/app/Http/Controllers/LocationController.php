<?php

namespace App\Http\Controllers;

use App\Repositories\Location\LocationEloquentRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/06/2017
 * Time: 10:13
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

    public function updateSurvivorLocation(int $survivorId, Request $request)
    {
        $location = $this->locationEloquentRepository->findBySurvivorId($survivorId);

        if (!$location) {
            return response('Survivor not found!', 404);
        }

        $bodyMessage = $request->input();

        $validator = Validator::make($bodyMessage, [
            'latitude' => 'required',
            'longitude' => 'required'
        ]);

        $errors = $validator->errors()->all();

        if ($errors) {
            return response([
                'message' => 'Validation Failed',
                'errors' => $errors
            ], 400);
        }

        $location->fill($bodyMessage);

        $this->locationEloquentRepository->setModel($location);
        $this->locationEloquentRepository->update();

        return response('Location updated successful!');
    }
}