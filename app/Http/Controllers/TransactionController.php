<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaction, AuditTrail, Loan};
use DB;

class TransactionController extends Controller
{
    public function __construct(){
        $this->table = "transactions";
    }

    public function get(Request $req){
        $array = Transaction::select($req->select);

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
        $loan = Loan::find($req->loan_id);

        $transaction = new Transaction();
        $transaction->user_id = $loan->branch_id;
        $transaction->loan_id = $req->loan_id;
        $transaction->type = $req->type;
        $transaction->payment_channel = $req->payment_channel;
        $transaction->trx_number = $req->reference;
        $transaction->amount = $loan->amount;
        $transaction->payment_date = now();

        echo $transaction->save();
    }

    public function update(Request $req){
        DB::table('transactions')->where('id', $req->id)->update($req->except(['id', '_token']));
        $this->log(auth()->user()->fullname, 'Updated Transaction', "ID: $req->id");
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
