<?php

const INFECTION_ROOT_PATH = "/infection";

Route::post(INFECTION_ROOT_PATH . '/{survivorId}/vote/{infectedSurvivorId}', 'VoteOfInfectionController@voteOfInfection');