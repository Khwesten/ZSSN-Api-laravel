<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LocationTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_updateLocation_with_empty_latitude()
    {
        $data = [
            "latitude" => "",
            "longitude" => "3"
        ];

        $response = $this->json('PUT', '/survivor/1', $data);

        $response->assertStatus(422);
    }
}
