<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
          'name' => 'Admin',
          'email' => '6rNlI@example.com',
          'password' => bcrypt('Pa$$w0rd'),
        ];

       User::create($users);
    }
}
