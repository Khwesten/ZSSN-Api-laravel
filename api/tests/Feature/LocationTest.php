<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocationTest extends TestCase
{
    /**
     * test_updateLocation_with_empty_latitude
     */
    public function test_updateLocation_with_empty_latitude()
    {
        $data = [
            "latitude" => "",
            "longitude" => "3"
        ];

        $response = $this->json('PUT', '/location/survivor/1', $data);

        $response->assertStatus(422);
        $response->assertJson(['The latitude field is required.']);
    }
    /**
     * test_updateLocation_with_empty_longitude
     */
    public function test_updateLocation_with_empty_longitude()
    {
        $data = [
            "latitude" => "3",
            "longitude" => ""
        ];

        $response = $this->json('PUT', '/location/survivor/1', $data);

        $response->assertStatus(422);
        $response->assertJson(['The longitude field is required.']);
    }

    /**
     * test_updateLocation_with_undefined_user
     */
    public function test_updateLocation_with_undefined_user()
    {
        $data = [
            "latitude" => "3",
            "longitude" => "3"
        ];

        $response = $this->json('PUT', '/location/survivor/0', $data);

        $response->assertStatus(404);
        $response->assertSeeText('Survivor not found!');
    }
}
