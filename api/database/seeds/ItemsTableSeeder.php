<?php

use Illuminate\Database\Seeder;

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
            ['name' => 'Water', 'points' => 4],
            ['name' => 'Food', 'points' => 3],
            ['name' => 'Medication', 'points' => 2],
            ['name' => 'Ammunition', 'points' => 1]
        ];

        DB::table('items')->insert($items);
    }
}
