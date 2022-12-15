<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'sadmin',
            'fname' => 'Super',
            'mname' => 'Duper',
            'lname' => 'Admin',
            'role' => 'Super Admin',
            'email' => 'superadmin@gmail.com',
            'birthday' => null,
            'gender' => 'Male',
            'address' => 'Earth',
            'contact' => null,
            'email_verified_at' => now()->toDateTimeString(),
            'password' => '654321'
        ]);

        User::create([
            'username' => 'admin',
            'fname' => 'David',
            'mname' => 'Roga',
            'lname' => 'Mendoza',
            'role' => 'Admin',
            'email' => 'davidmendozaofficial@gmail.com',
            'birthday' => null,
            'gender' => 'Male',
            'address' => null,
            'contact' => null,
            'email_verified_at' => now()->toDateTimeString(),
            'password' => '123456'
        ]);

        User::create([
            'username' => 'company1',
            'fname' => 'Company 1',
            'role' => 'Company',
            'email' => 'company1@gmail.com',
            'email_verified_at' => now()->toDateTimeString(),
            'password' => '12345678'
        ]);

        User::create([
            'username' => 'company2',
            'fname' => 'Company 2',
            'role' => 'Company',
            'email' => 'company2@gmail.com',
            'email_verified_at' => now()->toDateTimeString(),
            'password' => '12345678'
        ]);

        User::create([
            'username' => 'company3',
            'fname' => 'Company 3',
            'role' => 'Company',
            'email' => 'company3@gmail.com',
            'email_verified_at' => now()->toDateTimeString(),
            'password' => '12345678'
        ]);
    }
}
