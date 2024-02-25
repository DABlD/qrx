<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Branch, User, AuditTrail};
use DB;

class BranchController extends Controller
{
    public function __construct(){
        $this->table = "branches";
    }

    public function get(Request $req){
        $array = Branch::select($req->select);
        
        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS WHERE
        if($req->where){
            $array = $array->where($req->where[0], isset($req->where[2]) ? $req->where[1] : "=", $req->where[2] ?? $req->where[1]);
        }

        // IF HAS WHERE2
        if($req->where2){
            $array = $array->where($req->where2[0], isset($req->where2[2]) ? $req->where2[1] : "=", $req->where2[2] ?? $req->where2[1]);
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
        $branch = Branch::find($req->id);
        $branch->work_status = $req->work_status;
        $branch->percent = $req->percent;
        $branch->id_type = $req->id_type;
        $branch->id_num = $req->id_num;
        $branch->id_verified = $req->id_verified;
        $branch->save();

        $user = User::find($branch->user_id);
        $user->fname = $req->fname;
        $user->email = $req->email;
        $user->gender = $req->gender;
        $user->contact = $req->contact;
        $user->username = $req->username;
        $user->address = $req->address;

        echo $user->save();
    }

    public function delete(Request $req){
        $temp = Branch::find($req->id);
        User::find($temp->user_id)->delete();
        $temp->delete();

        $this->log(auth()->user()->fullname, 'Delete Branch', "ID: $req->id");
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
