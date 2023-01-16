<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Theme};

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

        $array = [
            ["logo_img", 'qrtransit/img/new_logo.png'],
            ["login_banner_img", "qrtransit/img/auth-bg.jpg"],
            ["login_bg_img", 'qrtransit/img/qr-transit-logo.png'],
            ["sidebar_bg_color", "#343a40"],
            ["sidebar_font_color", "#c2c7d0"],
            ["table_header_color", "#b96666"],
            ["table_header_font_color", "#ffffff"],
            ["table_group_color", "#66b966"],
            ["table_group_font_color", "#ffffff"],
        ];

        for($i = 3; $i <= 5; $i++){ 
            foreach($array as $theme){
                $this->seed($theme[0], $theme[1], $i);
            }
        }
    }

    private function seed($name, $value, $cid){
        $data = new Theme();
        $data->company_id = $cid;
        $data->name = $name;
        $data->value = $value;
        $data->save();
    }
}
