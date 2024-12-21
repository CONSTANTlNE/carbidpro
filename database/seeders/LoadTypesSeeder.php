<?php

namespace Database\Seeders;

use App\Models\LoadType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LoadTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LoadType::create([
            'name' => 'Sedan',
            'icon'=> '01HR01XE5MA938HA90RQYS94EH.svg',
            'price' => 0
        ]);

        LoadType::create([
            'name' => 'Pickup',
            'icon'=> '01HR01Y5H8RR4WPESHMKZJ1010.png',
            'price' => 400
        ]);

        LoadType::create([
            'name' => 'Small,Medium SUV',
            'icon'=> '01HR01YTPP3FE1FAWN82SP8PDA.png',
            'price' => 100
        ]);

        LoadType::create([
            'name' => 'VAN',
            'icon'=> '01HR01Z8MG73XCK4WDP0VRA1GY.png',
            'price' => 350
        ]);

        LoadType::create([
            'name' => 'Big SUV',
            'icon'=> '01HR01ZNYVTQVPR5SX549FTZCF.png',
            'price' => 350
        ]);

        LoadType::create([
            'name' => 'Sprinter',
            'icon'=> '01HR02052GRC2550HBE6QHZKZ2.webp',
            'price' => 970
        ]);

        LoadType::create([
            'name' => 'Motorcyrcle',
            'icon'=> '01HR020HDSNBKACX8BDKT3NEHX.png',
            'price' => 0
        ]);

        LoadType::create([
            'name' => 'Quadricycle',
            'icon'=> '01HR020WCJEHVY3WEGK4SGNQRK.png',
            'price' => 0
        ]);
    }
}
