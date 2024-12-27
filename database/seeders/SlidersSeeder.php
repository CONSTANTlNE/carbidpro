<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SlidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slider1 = Slider::create([
            'title' => 'We take care of the fast and safe <br> transportation of tour vehicle.',
        ]);
        $slider1->addMedia(base_path('forseederimages/slider1.jpg'))->toMediaCollection('slider');


        $slider2 = Slider::create([
            'title' => 'A logistics company that <br> is at your service with <br> best of experience',
        ]);
        $slider2->addMedia(base_path('forseederimages/slider2.webp'))->toMediaCollection('slider');
    }
}
