<?php

namespace App\Imports;

use App\Models\{User, Branch};
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UserImport implements ToCollection, WithCalculatedFormulas
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $clients)
    {
        foreach($clients as $key => $client){
            if($key > 0 && trim($client[2]) != ""){
                $user = new User();
                $user->fname    = $client[1];
                $user->gender   = $client[2];
                $user->birthday = $client[3];
                $user->email    = $client[4];
                $user->contact  = $client[5];
                $user->address  = $client[6];
                $user->role     = "Branch";
                $user->username = str_replace(' ', '_', $user->fname);
                $user->password = 12345678;
                $user->email_verified_at = now();
                $user->save();

                $branch = new Branch();
                $branch->user_id = $user->id;
                $branch->work_status = $client[7];
                $branch->work_status = "test";
                $branch->id_type = null;
                $branch->id_num = bin2hex(random_bytes(8));
                $branch->percent = 0;
                $branch->save();
            }
        }
    }
}