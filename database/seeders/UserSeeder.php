<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Theme, Branch};

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
            'username' => 'branch1',
            'fname' => 'Client 1',
            'role' => 'Branch',
            'email' => 'branch1@gmail.com',
            'email_verified_at' => now()->toDateTimeString(),
            'contact' => "09123456789",
            'gender' => "Male",
            'password' => '12345678'
        ]);

        User::create([
            'username' => 'branch2',
            'fname' => 'Client 2',
            'role' => 'Branch',
            'email' => 'branch2@gmail.com',
            'email_verified_at' => now()->toDateTimeString(),
            'contact' => "09123456789",
            'gender' => "Male",
            'password' => '12345678'
        ]);

        User::create([
            'username' => 'branch3',
            'fname' => 'Client 3',
            'role' => 'Branch',
            'email' => 'branch3@gmail.com',
            'email_verified_at' => now()->toDateTimeString(),
            'contact' => "09123456789",
            'gender' => "Male",
            'password' => '12345678'
        ]);

        Branch::create([
            "user_id" => 3,
            "work_status" => 'Private',
            "id_type" => 'Passport',
            "id_num" => 'P21234561A',
            "id_file" => null,
            "id_verified" => 0,
            "percent" => 3.5,
        ]);

        Branch::create([
            "user_id" => 4,
            "work_status" => 'Government',
            "id_type" => 'Passport',
            "id_num" => 'P21234571A',
            "id_file" => null,
            "id_verified" => 0,
            "percent" => 2.0,
        ]);

        Branch::create([
            "user_id" => 5,
            "work_status" => 'Self Employed',
            "id_type" => 'Passport',
            "id_num" => 'P21234581A',
            "id_file" => null,
            "id_verified" => 0,
            "percent" => 3,
        ]);

        $array = [
            ["logo_img", 'qrx/img/LOGO.png'],
            ["login_banner_img", "qrx/img/FRONT1.jpg"],
            ["login_bg_img", 'qrx/img/FRONT2.jpg'],
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
