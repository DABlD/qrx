<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Loan, AuditTrail, Transaction};
use DB;
use Image;

class LoanController extends Controller
{
    public function __construct(){
        $this->table = "loans";
    }

    public function get(Request $req){
        $array = Loan::select($req->select);

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], $req->where[1]);
        }

        // IF HAS WHERENOTNULL
        if($req->whereNotNull){
            $array = $array->whereNotNull($req->whereNotNull);
        }

        $array = $array->get();

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }

        echo json_encode($array);
    }

    public function store(Request $req){
        $loan = new Loan();
        $loan->branch_id = $req->branch_id;
        $loan->amount = $req->amount;
        $loan->percent = $req->percent;
        $loan->months = $req->months;
        $loan->balance = $req->amount;
        $loan->paid_months = 0;
        $loan->type = $req->type;
        $loan->contract_no = "";
        $loan->collateral1 = $req->collateral1;
        $loan->collateral2 = $req->collateral2;
        $loan->collateral3 = $req->collateral3;

        $array = explode(" ", $loan->type);
        foreach($array as $arr){
            $loan->contract_no .= $arr[0];
        }

        $count = Loan::where('created_at', 'like', now()->format('Y-m') . "%")->count() + 1;
        $loan->contract_no .= now()->format('Y') . now()->format('m') . str_pad($count, 4, '0', STR_PAD_LEFT);

        if($req->hasFile('file1')){
            $temp = $req->file('file1');
            $image = Image::make($temp);

            $name = $loan->contract_no . ' - ' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');
            $image->save($destinationPath . $name);

            $loan->file1 = 'uploads/' . $name;
        }
        if($req->hasFile('file2')){
            $temp = $req->file('file2');
            $image = Image::make($temp);

            $name = $loan->contract_no . ' - ' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');
            $image->save($destinationPath . $name);

            $loan->file2 = 'uploads/' . $name;
        }
        if($req->hasFile('file3')){
            $temp = $req->file('file3');
            $image = Image::make($temp);

            $name = $loan->contract_no . ' - ' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');
            $image->save($destinationPath . $name);

            $loan->file3 = 'uploads/' . $name;
        }

        echo $loan->save();
    }

    public function update(Request $req){
        if(isset($req->payments)){
            $loan = Loan::find($req->id);


            $payments = Transaction::whereIn('id', json_decode($loan->payments))->sum('amount');

            $percent = $loan->percent / 100 / 12;
            $months = $loan->months;
            $amount = $loan->amount * -1;
            
            $required = round($percent * $amount * pow((1 + $percent), $months) / (1 - pow((1 + $percent), $months)), 2);
            
            $loan->paid_months = round($payments / $required, 0);

            $loan->save();
        }
        else{
            DB::table('loans')->where('id', $req->id)->update($req->except(['id', '_token']));
        }

        $this->log(auth()->user()->fullname, 'Updated Loan', "ID: $req->id");
    }

    public function update2(Request $req){
        $loan = Loan::find($req->id);
        $loan->status = $req->status;
        $loan->percent = $req->percent;
        $loan->months = $req->months;
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
        $loan->collateral1 = $req->collateral1;
        $loan->collateral2 = $req->collateral2;
        $loan->collateral3 = $req->collateral3;

        if($req->hasFile('file1')){
            $temp = $req->file('file1');
            $image = Image::make($temp);

            $name = $loan->contract_no . ' - ' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');
            $image->save($destinationPath . $name);

            $loan->file1 = 'uploads/' . $name;
        }
        if($req->hasFile('file2')){
            $temp = $req->file('file2');
            $image = Image::make($temp);

            $name = $loan->contract_no . ' - ' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');
            $image->save($destinationPath . $name);

            $loan->file2 = 'uploads/' . $name;
        }
        if($req->hasFile('file3')){
            $temp = $req->file('file3');
            $image = Image::make($temp);

            $name = $loan->contract_no . ' - ' . time() . "." . $temp->getClientOriginalExtension();
            $destinationPath = public_path('uploads/');
            $image->save($destinationPath . $name);

            $loan->file3 = 'uploads/' . $name;
        }

        echo $loan->save();
    }

    public function delete(Request $req){
        $temp = Loan::find($req->id);
        $temp->delete();

        $this->log(auth()->user()->fullname, 'Delete Loan', "ID: $req->id");
    }

    public function index(){
        return $this->_view('index', [
            'title' => ucfirst($this->table)
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table." . $view, $data);
    }

    public function log($user, $action, $description){
        $data = new AuditTrail();
        $data->uid = $user;
        $data->action = $action;
        $data->description = $description;
        $data->save();
    }
}
