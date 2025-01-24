<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'dealer', 'guard_name' => 'customer']);
        Role::create(['name' => 'Developer']);
        Role::create(['name' => 'Dispatch']);
        Role::create(['name' => 'Loader']);
        Role::create(['name' => 'Finance']);
        Role::create(['name' => 'Terminal Agent']);
    }
}
