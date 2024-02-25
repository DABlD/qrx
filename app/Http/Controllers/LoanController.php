<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Loan, AuditTrail};
use DB;

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

        $array = explode(" ", $loan->type);
        foreach($array as $arr){
            $loan->contract_no .= $arr[0];
        }

        $count = Loan::where('created_at', 'like', now()->format('Y-m') . "%")->count() + 1;
        $loan->contract_no .= now()->format('Y') . now()->format('m') . str_pad($count, 4, '0', STR_PAD_LEFT);

        echo $loan->save();
    }

    public function update(Request $req){
        DB::table('loans')->where('id', $req->id)->update($req->except(['id', '_token']));
        $this->log(auth()->user()->fullname, 'Updated Loan', "ID: $req->id");
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
