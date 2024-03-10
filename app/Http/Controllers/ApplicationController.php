<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Loan, User, Branch};

class ApplicationController extends Controller
{
    public function create(Request $req){
        $user = new User();
        $user->type = "Application";
        $user->fname = $req->fname;
        $user->mname = $req->mname;
        $user->lname = $req->lname;
        $user->email = $req->email;
        $user->birthday = $req->birthday;
        $user->gender = $req->gender;
        $user->address = $req->address;
        $user->contact = $req->contact;

        $user->username = $req->lname . '_' . str_pad($req->user_id, 6, '0', STR_PAD_LEFT);
        $user->password = 12345678;
        $user->email_verified_at = now();
        $user->role = "Branch";
        $user->save();

        $branch = new Branch();
        $branch->user_id = $user->id;
        $branch->work_status = $req->work;
        $branch->id_type = null;
        $branch->id_num = null;
        $branch->percent = 10;
        $branch->save();

        $loan = new Loan();
        $loan->type = $req->use_of_loan;
        $loan->branch_id = $branch->id;
        $loan->amount = $req->amount;
        $loan->percent = 0;
        $loan->balance = $req->amount;
        $loan->months = 0;
        $loan->paid_months = 0;
        $loan->source_of_income = $req->source_of_income;
        $loan->repayment_plan = $req->repayment_plan;
        $loan->type_of_organization = $req->type_of_organization;
        $loan->work_name = $req->work_name;
        $loan->work_address = $req->work_address;
        $loan->position = $req->position;
        $loan->salary = $req->salary;
        $loan->date_of_employment = $req->date_of_employment;
        $loan->industry = $req->industry;
        $loan->capitalization = $req->capitalization;
        $loan->tin = $req->tin;

        $array = explode(" ", $req->use_of_loan);
        if(sizeof($array)){
            foreach($array as $arr){
                $loan->contract_no .= $arr[0];
            }
        }
        else{
            $loan->contract_no .= $req->use_of_loan[0];
        }

        $count = Loan::where('created_at', 'like', now()->format('Y-m') . "%")->count() + 1;
        $loan->contract_no .= now()->format('Y') . now()->format('m') . str_pad($count, 4, '0', STR_PAD_LEFT);
        $loan->save();

        return back()->withErrors([
            'success' => 'Successfully Applied. Kindly wait for the us to contact you.',
        ]);
    }
}
