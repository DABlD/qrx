<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Theme};
use DB;

class CompanyController extends Controller
{
    public function __construct(){
        $this->table = "users";
    }

    public function get(Request $req){
        $array = DB::table($this->table)->select($req->select);
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
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', '$this->table.id');
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
        $data->email = $req->email;
        $data->contact = $req->contact;
        $data->password = $req->password;

        $data->role = "Company";

        $result = $data->save();

        $array = [
            ["logo_img", 'qrtransit/img/qr-transit-logo.png'],
            ["login_banner_img", "img/auth-bg.jpg"],
            ["login_bg_img", 'qrtransit/img/qr-transit-logo.png'],
            ["sidebar_bg_color", "#343a40"],
            ["sidebar_font_color", "#c2c7d0"],
            ["table_header_color", "#b96666"],
            ["table_header_font_color", "#ffffff"],
            ["table_group_color", "#66b966"],
            ["table_group_font_color", "#ffffff"],
        ];

        foreach($array as $theme){
            $this->seed($theme[0], $theme[1], $data->id);
        }

        echo $result;
    }

    private function seed($name, $value, $cid){
        $data = new Theme();
        $data->company_id = $cid;
        $data->name = $name;
        $data->value = $value;
        $data->save();
    }

    public function update(Request $req){
        echo DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token']));
    }

    public function delete(Request $req){
        User::find($req->id)->delete();
    }

    public function index(){
        return $this->_view('index', [
            'title' => "Company"
        ]);
    }

    private function _view($view, $data = array()){
        return view("company." . $view, $data);
    }
}
