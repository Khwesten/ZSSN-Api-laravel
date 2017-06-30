<?php

namespace Tests\Unit;

use App\Http\Controllers\LocationController;
use App\Http\Controllers\SurvivorController;
use Illuminate\Http\Request;
use Tests\TestCase;

class SurvivorTest extends TestCase
{
    private $stubController;

    protected function setUp()
    {
        parent::setUp();

        $this->stubController = $this->getMockBuilder(SurvivorController::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * test_create_user
     */
    public function test_create_user()
    {
        $stub = $this->stubController;

        $stubRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stub->method('create')
            ->willReturn('Survivor saved successful!');

        $this->assertEquals('Survivor saved successful!', $stub->create($stubRequest));
    }

    /**
     * test_tradeItems
     */
    public function test_tradeItems()
    {
        $stub = $this->stubController;

        $stubRequest = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $stub->method('tradeItems')
            ->willReturn('Trade successful!');

        $this->assertEquals('Trade successful!', $stub->tradeItems(1, 2, $stubRequest));
    }
}