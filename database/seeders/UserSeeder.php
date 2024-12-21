<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    const ROLE_ADMIN = 'Admin';
    const ROLE_EDITOR = 'Editor';
    const ROLE_DISPATCH = 'Dispatch';
    const ROLE_LOADER = 'Loader';
    const ROLE_FINANCE = 'Finance';
    const ROLE_TERMINAL_AGENT = 'Terminal Agent';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
          'name' => 'Admin',
          'email' => 'admin@admin.com',
          'password' => bcrypt('password'),
        ];

       User::create($users);

       User::create([
           'name' => 'Dispatcher',
           'email' => 'Dealer@Dealer.com',
           'password' => bcrypt('password'),
           'role' => 'Dispatch'
       ]);

        User::create([
            'name' => 'Editor',
            'email' => 'editor@editor.com',
            'password' => bcrypt('password'),
            'role' => 'Editor'
        ]);

        User::create([
            'name' => 'Loader',
            'email' => 'loader@loader.com',
            'password' => bcrypt('password'),
            'role' => 'Loader'
        ]);

        User::create([
            'name' => 'Finance',
            'email' => 'finance@finance.com',
            'password' => bcrypt('password'),
            'role' => 'Finance'
        ]);

        User::create([
            'name' => 'Terminal Agent',
            'email' => 'agent@agent.com',
            'password' => bcrypt('password'),
            'role' => 'Terminal Agent'
        ]);


        Customer::create([
            'contact_name' => 'Dealer 1',
            'email' => 'dealer1@dealer1.com',
            'password' => bcrypt('password'),
            'company_name'=>'Dealer 1 Company Name',
            'personal_number'=>'1234567890',
            'phone'=>'1234567890',
            'number_of_cars'=>50,
            'unhashed_password' => 'password',
            'is_active' => 1
        ]);


        Customer::create([
            'contact_name' => 'Dealer 2',
            'email' => 'dealer2@dealer2.com',
            'password' => bcrypt('password'),
            'company_name'=>'Dealer 2 Company Name',
            'personal_number'=>'1234567890',
            'phone'=>'1234567890',
            'number_of_cars'=>50,
            'unhashed_password' => 'password',
            'is_active' => 1
        ]);

    }
}
