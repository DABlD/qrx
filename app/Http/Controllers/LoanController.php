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

    public function update(Request $req){
        DB::table('loan')->where('id', $req->id)->update($req->except(['id', '_token']));
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
