<?php

namespace Database\Seeders;

use App\Models\Auction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuctionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Auction::create([
            'name' => 'COPART',
        ]);
        Auction::create([
            'name' => 'I A A I',
        ]);
        Auction::create([
            'name' => 'MANHEIM',
        ]);
    }
}
