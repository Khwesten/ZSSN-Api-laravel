<?php

Route::post('/infection/{survivorId}/vote/{infectedSurvivorId}', 'VoteOfInfectionController@voteOfInfection');