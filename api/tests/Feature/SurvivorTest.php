<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SurvivorTest extends TestCase
{
    use WithoutMiddleware, DatabaseMigrations, DatabaseTransactions;

    protected $baseUrl = 'http://localhost:8000';

    function setUp()
    {
        parent::setUp();

        config(['app.url' => 'http://localhost:8000']);
    }

    /**
     * @return void
     */
    public function test_create_with_empty_name()
    {
        $data = [
            "name" => ""
        ];

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_create_with_empty_gender()
    {
        $data = [
            "name" => "Valid name",
            "gender" => ""
        ];

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_create_with_gender_different_of_M_and_F()
    {
        $data = [
            "name" => "Valid name",
            "gender" => "a"
        ];

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
    }

    /**
     * @return void
     */
    public function test_report_status()
    {
        $response = $this->get('/survivor/report');
        $response->assertSuccessful();
    }
}
