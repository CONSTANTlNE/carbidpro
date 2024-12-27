<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $service1=Service::create([
          'title'=>'Sea Shipments',
          'button_title' => 'Get Started',
          'button_url' => '/',
        ]);
        $service1->addMedia(base_path('forseederimages/service1.jpg'))->toMediaCollection('services');



        $service2=Service::create([
            'title'=>'Sea Shipments',
            'button_title' => 'Get Started',
            'button_url' => '/',
        ]);
        $service2->addMedia(base_path('forseederimages/service2.jpg'))->toMediaCollection('services');


        $service3=Service::create([
            'title'=>'Sea Shipments',
            'button_title' => 'Get Started',
            'button_url' => '/',
        ]);
        $service3->addMedia(base_path('forseederimages/service3.jpg'))->toMediaCollection('services');

    }
}
