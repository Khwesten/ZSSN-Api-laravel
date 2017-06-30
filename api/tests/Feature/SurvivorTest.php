<?php

namespace Tests\Feature;

use App\Survivor;
use App\SurvivorItem;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SurvivorTest extends TestCase
{
    use DatabaseTransactions;

    private $survivor;
    private $trade;

    public function setUp()
    {
        parent::setUp();

        $this->survivor = [
            "name" => "Valid name",
            "age" => 20,
            "gender" => "M",
            "location" => [
                "latitude" => 1,
                "longitude" => 1
            ]
        ];

        $this->trade = [
            "1" => [
                "items" => [
                    ["id" => 1, "quantity" => 1]
                ]
            ],
            "2" => [
                "items" => [
                    ["id" => 2, "quantity" => 1],
                    ["id" => 4, "quantity" => 1]
                ]
            ]
        ];
    }

    /**
     * test_create_with_empty_name
     */
    public function test_create_with_empty_name()
    {
        $this->survivor['name'] = '';

        $data = $this->survivor;

        $response = $this->post('/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The name field is required."]);
    }

    /**
     * test_create_with_empty_gender
     */
    public function test_create_with_empty_gender()
    {
        $this->survivor['gender'] = '';

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The selected gender is invalid."]);
    }

    /**
     * test_create_with_gender_different_of_M_and_F
     */
    public function test_create_with_gender_different_of_M_and_F()
    {
        $this->survivor['gender'] = 'A';

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The selected gender is invalid."]);
    }

    /**
     * test_create_with_age_equals_to_0
     */
    public function test_create_with_age_equals_to_0()
    {
        $this->survivor['age'] = 0;

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The age must be at least 1."]);
    }

    /**
     * test_create_with_age_equals_to_letter
     */
    public function test_create_with_age_equals_to_letter()
    {
        $this->survivor['age'] = "o";

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The age must be a number."]);
    }

    /**
     * test_create_with_empty_age
     */
    public function test_create_with_empty_age()
    {
        $this->survivor['age'] = "";

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The age must be a number."]);
    }

    /**
     * test_create_with_empty_location
     */
    public function test_create_with_empty_location()
    {
        $this->survivor['location'] = "";

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The location field is required."]);
    }

    /**
     * test_create_with_empty_latitude_location
     */
    public function test_create_with_empty_latitude_location()
    {
        $this->survivor['location']['latitude'] = "";

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The location.latitude field is required."]);
    }

    /**
     * test_create_with_empty_longitude_location
     */
    public function test_create_with_empty_longitude_location()
    {
        $this->survivor['location']['longitude'] = "";

        $data = $this->survivor;

        $response = $this->json('POST', '/survivor', $data);

        $response->assertStatus(422);
        $response->assertJson(["The location.longitude field is required."]);
    }

    /**
     * test_tradeItems_with_different_id_of_url_from_body
     */
    public function test_tradeItems_with_different_id_of_url_from_body()
    {
        $data = $this->trade;

        $response = $this->json('POST', '/survivor/1/trade-items-with/3', $data);

        $response->assertStatus(400);
        $response->assertSeeText("Survivor IDs do not match the body of the message!");
    }

    /**
     * test_tradeItems_with_nonexistent_survivor
     */
    public function test_tradeItems_with_nonexistent_survivor()
    {
        $data = $this->trade;

        $data[0] = $data[2];
        unset($data[2]);

        $response = $this->json('POST', '/survivor/1/trade-items-with/0', $data);

        $response->assertStatus(404);
        $response->assertSeeText("Survivor not found!");
    }

    /**
     * test_tradeItems_without_item_on_survivor
     */
    public function test_tradeItems_without_item_on_survivor()
    {
        $survivor = factory(Survivor::class)->create();
        $anotherSurvivor = factory(Survivor::class)->create();

        $trade = $this->trade;

        $trade[$survivor->id] = $trade[1];
        unset($trade[1]);

        $trade[$anotherSurvivor->id] = $trade[2];
        unset($trade[2]);

        $trade[1]["items"] = [["id" => 0, "quantity" => 1]];

        $response = $this->json('POST', "/survivor/{$survivor->id}/trade-items-with/{$anotherSurvivor->id}", $trade);

        $response->assertStatus(400);
        $response->assertSeeText("One of survivors don't have all items to trade!");
    }

    /**
     * test_report_status
     */
    public function test_report_status()
    {
        $response = $this->get('/survivor/report');
        $response->assertSuccessful();
    }
}
