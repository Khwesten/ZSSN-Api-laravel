<?php

namespace App\Repositories\VoteOfInfection;

use App\Repositories\AbstractRepository;

/**
 * Created by IntelliJ IDEA.
 * User: k-heiner@hotmail.com
 * Date: 22/06/2017
 * Time: 17:58
 */
abstract class AbstractVoteOfInfection extends AbstractRepository
{
    public abstract function findBySurvivors(int $survivorId, int $infectedSurvivorId);
    public abstract function votesOfInfectedUser(int $infectedSurvivorId);
}