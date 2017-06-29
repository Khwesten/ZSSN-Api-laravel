<?php

use Illuminate\Database\Seeder;
use \App\Http\Controllers\SurvivorController;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [
            ['name' => SurvivorController::WATER_ITEM_NAME, 'points' => 4],
            ['name' => SurvivorController::FOOD_ITEM_NAME, 'points' => 3],
            ['name' => SurvivorController::MEDICATION_ITEM_NAME, 'points' => 2],
            ['name' => SurvivorController::AMMUNITION_ITEM_NAME, 'points' => 1]
        ];

        DB::table('items')->insert($items);
    }
}
