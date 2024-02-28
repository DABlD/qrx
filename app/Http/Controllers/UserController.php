<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, AuditTrail, Branch};
use DB;
use Auth;

class UserController extends Controller
{
    public function get(Request $req){
        $array = DB::table('users')->select($req->select);
        $array = $array->where('deleted_at', null);

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

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 1);
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
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
        $data = new User();
        $data->username = $req->username;
        $data->fname = $req->fname;
        $data->mname = $req->mname;
        $data->lname = $req->lname;
        $data->role = $req->role;
        $data->email = $req->email;
        $data->birthday = $req->birthday;
        $data->gender = $req->gender;
        $data->address = $req->address;
        $data->contact = $req->contact;
        $data->password = $req->password;

        if($req->role != "Branch"){
            $data->save();
            $this->log(auth()->user()->fullname, 'Create User', "Device ID: " . $data->id);
        }
        else{
            $data2 = new Branch();
            $data2->user_id = $req->user_id;
            $data2->work_status = $req->work_status;
            $data2->id_type = $req->id_type;
            $data2->id_num = $req->id_num;
            $data2->id_file = $req->id_file;
            $data2->id_verified = $req->id_verified;
            $data2->percent = $req->percent;

            $data->save();
            $data2->save();
            $this->log(auth()->user()->fullname, 'Create Client', "Device ID: " . $data->id);
        }
    }

    public function store2(Request $req){
        $data = new User();
        $data->fname = $req->fname;
        $data->gender = $req->gender;
        $data->contact = $req->contact;
        $data->username = $req->username;
        $data->password = $req->password;
        $data->role = "Admin";
        $data->email_verified_at = now();
        $data->email = "test@email.com";
        $data->save();

        $this->log(auth()->user()->fullname, 'Create Admin', "Device ID: " . $data->id);
    }

    public function update(Request $req){
        $update = DB::table('users')->where('id', $req->id)->update($req->except(['id', '_token']));
        $this->log(auth()->user()->fullname, 'Updated User', "ID: $req->id");
    }

    public function updatePassword(Request $req){
        $user = User::find($req->id);
        $user->password = $req->password;
        $this->log($user->fname, 'Updated Password', "---");
        $user->save();
    }

    public function delete(Request $req){
        User::find($req->id)->delete();
        $this->log(auth()->user()->fullname, 'Delete User', "ID: $req->id");
    }

    public function restore(Request $req){
        User::withTrashed()->find($req->id)->restore();
    }

    public function index(){
        return $this->_view('index', [
            'title' => 'Clients'
        ]);
    }

    public function index2(){
        return $this->_view('index2', [
            'title' => 'Staffs'
        ]);
    }

    private function _view($view, $data = array()){
        return view('users.' . $view, $data);
    }

    public function log($user, $action, $description){
        $data = new AuditTrail();
        $data->uid = $user;
        $data->action = $action;
        $data->description = $description;
        $data->save();
    }
}
