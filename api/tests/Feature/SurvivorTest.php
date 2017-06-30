<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\Utils\Utils;

class SurvivorTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function create()
    {
        $data = [
            "name" => ""
        ];

        $response = $this->post('/survivor', $data);

        var_dump($response);

        $response->assertStatus(200);
    }
}
