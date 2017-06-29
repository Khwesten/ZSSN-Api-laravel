<?php

const LOCATION_ROOT_PATH = "/location";

Route::put(LOCATION_ROOT_PATH . '/survivor/{survivorId}', 'LocationController@updateSurvivorLocation');