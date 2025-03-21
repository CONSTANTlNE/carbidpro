<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(PortsSeeder::class);
        $this->call(AuctionsSeeder::class);
        $this->call(LoadTypesSeeder::class);
        $this->call(ContainerStatusesSeeder::class);
        $this->call(CarStatusSeeder::class);
        $this->call(SlidersSeeder::class);
        $this->call(ServicesSeeder::class);
        $this->call(SettingsSeeder::class);
        $this->call(AnnouncementsSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(PortCitiesSeeder::class);
        $this->call(ExtraexpenceSeeder::class);



    }
}
