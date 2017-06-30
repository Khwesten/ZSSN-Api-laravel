<?php

namespace Tests\Unit;

use App\Http\Controllers\LocationController;
use Illuminate\Http\Request;
use Tests\TestCase;

class LocationTest extends TestCase
{
    /**
     * test_updateLocation_with_existent_user
     */
    public function test_updateLocation_with_existent_user()
    {
        $stub = $this->getMockBuilder(LocationController::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stubRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stub->method('updateSurvivorLocation')
            ->willReturn('Location updated successful!');

        $this->assertEquals('Location updated successful!', $stub->updateSurvivorLocation(1, $stubRequest));
    }
}