<?php

namespace App\Http\Controllers;

use App\Repositories\Item\ItemEloquentRepository;
use App\Repositories\Survivor\SurvivorEloquentRepository;
use App\Repositories\SurvivorItem\SurvivorItemEloquentRepository;
use App\Repositories\VoteOfInfection\VoteOfInfectionEloquentRepository;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * The survivor repository implementation.
     *
     * @var SurvivorEloquentRepository
     */
    protected $survivorEloquentRepository;

    /**
     * The location repository implementation.
     *
     * @var LocationEloquentRepository
     */
    protected $locationEloquentRepository;

    /**
     * The location repository implementation.
     *
     * @var VoteOfInfectionEloquentRepository
     */
    protected $voteOfInfectionEloquentRepository;

    /**
     * The location repository implementation.
     *
     * @var ItemEloquentRepository
     */
    protected $itemEloquentRepository;

    /**
     * The location repository implementation.
     *
     * @var SurvivorItemEloquentRepository
     */
    protected $survivorItemEloquentRepository;
}
