<?php

namespace Database\Seeders;

use App\Models\Customer;
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
        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('Admin');


        $dispatcher = User::create([
            'name'     => 'Dispatcher',
            'email'    => 'Dealer@Dealer.com',
            'password' => bcrypt('password'),
            'role'     => 'Dispatch',
        ]);

        $dispatcher->assignRole('Dispatch');

        $editor = User::create([
            'name'     => 'Editor',
            'email'    => 'editor@editor.com',
            'password' => bcrypt('password'),
            'role'     => 'Editor',
        ]);

        $editor->assignRole('Editor');

        $loader = User::create([
            'name'     => 'Loader',
            'email'    => 'loader@loader.com',
            'password' => bcrypt('password'),
            'role'     => 'Loader',
        ]);

        $loader->assignRole('Loader');

        $finance = User::create([
            'name'     => 'Finance',
            'email'    => 'finance@finance.com',
            'password' => bcrypt('password'),
            'role'     => 'Finance',
        ]);

        $finance->assignRole('Finance');

        $agent = User::create([
            'name'     => 'Terminal Agent',
            'email'    => 'agent@agent.com',
            'password' => bcrypt('password'),
            'role'     => 'Terminal Agent',
        ]);

        $agent->assignRole('Terminal Agent');

        $developer = User::create([
            'name'     => 'Developer',
            'email'    => 'gmta.constantine@gmail.com',
            'password' => bcrypt('password'),
        ]);

        $developer->assignRole('Developer');


        Customer::create([
            'contact_name'      => 'Dealer 1',
            'email'             => 'dealer1@dealer1.com',
            'password'          => bcrypt('password'),
            'company_name'      => 'Dealer 1 Company Name',
            'personal_number'   => '1234567890',
            'phone'             => '1234567890',
            'number_of_cars'    => 50,
            'unhashed_password' => 'password',
            'is_active'         => 1,
        ]);


        Customer::create([
            'contact_name'      => 'Dealer 2',
            'email'             => 'dealer2@dealer2.com',
            'password'          => bcrypt('password'),
            'company_name'      => 'Dealer 2 Company Name',
            'personal_number'   => '1234567890',
            'phone'             => '1234567890',
            'number_of_cars'    => 50,
            'unhashed_password' => 'password',
            'is_active'         => 1,
        ]);
    }
}
