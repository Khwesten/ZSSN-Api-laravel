<?php

namespace App\Http\Controllers;

use App\Repositories\Survivor\SurvivorEloquentRepository;
use Illuminate\Http\Request;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 14:53
 */
class SurvivorController extends Controller
{
    /**
     * The user repository implementation.
     *
     * @var SurvivorEloquentRepository
     */
    protected $survivors;

    /**
     * Survivor constructor.
     *
     * @param SurvivorEloquentRepository $survivors
     */
    public function __construct(SurvivorEloquentRepository $survivors)
    {
        $this->survivors = $survivors;
    }

    public function create(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
            'gender' => 'required|max:1',
            'age' => 'required',
            'location.latitude' => 'required',
            'location.longitude' => 'required',
        ]);

        $user = $request->input();

        return $this->survivors->create($user);
    }

    public function updateLocation(Request $request, int $survivorId)
    {
    }

    public function markAsInfected(int $survivorId, int $infectedSurvivorId)
    {
    }

    public function tradeWith()
    {
    }

    public function report()
    {
    }
}