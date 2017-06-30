<?php

Route::post('/survivor', 'SurvivorController@create');

Route::post('/survivor/{survivorId}/trade-items-with/{anotherSurvivorId}', 'SurvivorController@tradeItems');

Route::get('/survivor/report', 'SurvivorController@report');