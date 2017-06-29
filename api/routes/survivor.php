<?php

const SURVIVOR_ROOT_PATH = "/survivor";

Route::post(SURVIVOR_ROOT_PATH, 'SurvivorController@create');

Route::post(SURVIVOR_ROOT_PATH . '/{survivorId}/trade-items-with/{anotherSurvivorId}', 'SurvivorController@tradeItems');