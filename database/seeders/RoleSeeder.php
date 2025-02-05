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
        Role::create(['name' => 'Admin', 'guard_name' => 'web']);
        Role::create(['name' => 'dealer', 'guard_name' => 'customer']);
        Role::create(['name' => 'Developer', 'guard_name' => 'web']);
        Role::create(['name' => 'Dispatch', 'guard_name' => 'web']);
        Role::create(['name' => 'Loader', 'guard_name' => 'web']);
        Role::create(['name' => 'Finance', 'guard_name' => 'web']);
        Role::create(['name' => 'Terminal Agent', 'guard_name' => 'web']);
    }
}
