<?php

const SURVIVOR_ROOT_PATH = "/survivor";

Route::post(SURVIVOR_ROOT_PATH, 'SurvivorController@create');

Route::post(SURVIVOR_ROOT_PATH . '/infection/{survivorId}/{infectedSurvivorId}', 'SurvivorController@markAsInfected');

Route::get(SURVIVOR_ROOT_PATH . '/report', 'SurvivorController@markAsInfected');

Route::put(SURVIVOR_ROOT_PATH . '/location/{survivorId}', 'SurvivorController@updateLocation');