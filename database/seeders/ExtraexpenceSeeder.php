<?php

namespace Database\Seeders;

use App\Models\Extraexpence;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtraexpenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Extraexpence::create([
            'name' => 'Hybrid',
        ]);
        Extraexpence::create([
            'name' => 'Sublot',
        ]);
        Extraexpence::create([
            'name' => 'Offsite',
        ]);
        Extraexpence::create([
            'name' => 'Insurance',
        ]);
        Extraexpence::create([
            'name' => 'TitleP-Pending',
        ]);
    }
}
