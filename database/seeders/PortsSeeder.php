<?php

namespace Database\Seeders;

use App\Models\Port;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PortsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Port::create([
            'name'=>'NJ New Jersey',
            'state_id'=>4
        ]);
        Port::create([
            'name'=>'TX Texas',
            'state_id'=>51
        ]);
        Port::create([
            'name'=>'CA California',
            'state_id'=>6
        ]);
        Port::create([
            'name'=>'GA Georgia',
            'state_id'=>13
        ]);
    }
}
