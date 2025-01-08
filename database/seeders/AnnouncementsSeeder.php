<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnnouncementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Announcement::create([
            'title' => 'DONT BUY Flooded hybrid and electric vehicles!',
            'content'=>'<p><span style="background-color: rgb(255, 255, 255); color: rgb(224, 62, 45);">Please note that due to new regulations, the transportation of Flooded hybrid and electric vehicles by container is prohibited.!</span></p>',
            'date'=>'2024-04-12'
        ]);

        Announcement::create([
            'title' => 'Shipping Cost Decreased',
            'content'=>'<p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Dear customers</span></p><p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Good news for you, the price of container shipping has decreased by</span><strong style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);"> </strong><strong style="background-color: rgb(255, 255, 255); color: rgb(45, 194, 107);">-200</strong><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);"> dollars per car (which will be reflected in your calculator)</span></p>',
            'date'=>'2024-10-10'
        ]);

        Announcement::create([
            'title' => 'Announcement !',
            'content'=>'<p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Dear customers</span></p><p><br></p><p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">We would like to inform you that due to the strike of the employees in the American ports, the work of the ports has been interrupted, the terminals are overloaded. This situation has potentially caused delays and limited space for receiving vehicles in all states. This leads to the delay of vehicles in the auction area, which increases the costs of vehicle storage (Storage FEE). CarBidPro does not take any responsibility for the costs of storage (Storage FEE) of vehicles purchased from September 27!</span></p><p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Please also note that the ports have increased the tariffs, unfortunately we will have to revise the tariffs accordingly. In all states, the price of a container increased by </span><strong style="background-color: rgb(255, 255, 255); color: rgb(224, 62, 45);">$250. </strong><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Our company negotiates to minimize cost increases.</span></p><p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Please plan your purchases by consulting with us and keep in mind the news.</span></p><p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Thank you for your cooperation.</span></p><p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Sincerely: CARBIDPRO</span></p><p><br></p>',
            'date'=>'2024-09-14'
        ]);

        Announcement::create([
            'title' => 'All US States, Rate Increase',
            'content'=>'<p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Due to problematic delays at the ports, we are forced to increase the transportation cost by $50 per vehicle. From 1 December</span></p><p><br></p><p><span style="background-color: rgb(255, 255, 255); color: rgb(136, 136, 136);">Sincerely: CARBIDPRO</span></p><p><br></p>',
            'date'=>'2024-11-27'
        ]);

    }
}
