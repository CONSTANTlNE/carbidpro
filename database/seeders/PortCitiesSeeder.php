<?php

namespace Database\Seeders;

use App\Models\PortCity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortCitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PortCity::create([
            'name' => 'Batumi',
            'country_id'=>1
        ]);

        PortCity::create([
            'name' => 'Poti',
            'country_id'=>1

        ]);
    }
}
