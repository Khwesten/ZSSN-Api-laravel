<?php

namespace App\Repositories\VoteOfInfection;

use App\Survivor;
use App\VoteOfInfection;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:54
 */
class VoteOfInfectionEloquentRepository extends AbstractVoteOfInfection
{
    public function create()
    {
        $this->model->save();

        return $this->model;
    }

    public function update()
    {
        $this->model->save();

        return $this->model;
    }

    public function delete(int $id)
    {
        VoteOfInfection::destroy($id);
    }

    public function find(int $id)
    {
        $survivor = Survivor::find($id);
        return $survivor;
    }

    public function findBySurvivors(int $survivorId, int $infectedSurvivorId)
    {
        $voteOfInfection = VoteOfInfection::where('survivor_id', $survivorId)
            ->where('infected_survivor_id', $infectedSurvivorId)
            ->first();

        return $voteOfInfection;
    }

    public function votesOfInfectedUser(int $infectedSurvivorId)
    {
        $numberOfVotes = VoteOfInfection::where('infected_survivor_id', $infectedSurvivorId)
            ->where('survivor_id', '!=', $infectedSurvivorId)
            ->count();

        return $numberOfVotes;
    }
}