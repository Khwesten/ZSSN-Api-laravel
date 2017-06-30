<?php

namespace App\Http\Controllers;

use App\Repositories\Survivor\SurvivorEloquentRepository;
use App\Repositories\VoteOfInfection\VoteOfInfectionEloquentRepository;
use App\VoteOfInfection;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 27/06/2017
 * Time: 12:01
 */
class VoteOfInfectionController extends Controller
{
    /**
     * SurvivorController constructor.
     *
     * @param SurvivorEloquentRepository $survivorEloquentRepository
     * @param VoteOfInfectionEloquentRepository $voteOfInfectionEloquentRepository
     */
    public function __construct(
        SurvivorEloquentRepository $survivorEloquentRepository,
        VoteOfInfectionEloquentRepository $voteOfInfectionEloquentRepository
    )
    {
        $this->survivorEloquentRepository = $survivorEloquentRepository;
        $this->voteOfInfectionEloquentRepository = $voteOfInfectionEloquentRepository;
    }

    /**
     * @SWG\Post(
     *     path="/infection",
     *     description="Vote to mark another survivor as infected",
     *     operationId="voteOfInfection.voteOfInfection",
     *     produces={"application/json"},
     *     tags={"infection"},
     *
     *     @SWG\Parameter(name="survivorId", in="path", required=true, type="integer"),
     *     @SWG\Parameter(name="infectedSurvivorId", in="path", required=true, type="integer"),
     *
     *     @SWG\Response(response=200, description="Vote saved successful!"),
     *     @SWG\Response(response=404, description="Survivor not found!")
     * )
     *
     * @param int $survivorId
     * @param int $infectedSurvivorId
     * @param VoteOfInfection $voteOfInfection
     *
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function voteOfInfection(int $survivorId, int $infectedSurvivorId, VoteOfInfection $voteOfInfection)
    {
        $voteResult = $this->voteOfInfectionEloquentRepository->findBySurvivors($survivorId, $infectedSurvivorId);

        if (!$voteResult) {
            $survivor = $this->survivorEloquentRepository->find($survivorId);
            $infectedSurvivor = $this->survivorEloquentRepository->find($infectedSurvivorId);

            if (!$survivor || !$infectedSurvivor) {
                return response('Survivor not found!', 404);
            }

            $voteOfInfection->survivor()->associate($survivor);
            $voteOfInfection->infectedSurvivor()->associate($infectedSurvivor);

            $this->voteOfInfectionEloquentRepository->setModel($voteOfInfection);
            $this->voteOfInfectionEloquentRepository->create();

            $votes = $this->voteOfInfectionEloquentRepository->votesOfInfectedUser($infectedSurvivorId);

            if ($votes > 2) {
                $this->survivorEloquentRepository->markAsInfected($infectedSurvivorId);
            }
        }

        return response('Vote saved successful!');
    }
}