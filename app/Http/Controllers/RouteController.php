<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Route, AuditTrail};
use DB;

class RouteController extends Controller
{
    public function __construct(){
        $this->table = "routes";
    }

    public function get(Request $req){
        $array = Route::select($req->select);
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
        $data = new Route();
        $data->company_id = auth()->user()->id;

        $data->from = $req->from;
        $data->to = $req->to;
        $data->direction = $req->direction;
        $data->stations = json_encode([]);
        $data->base_fare = $req->base_fare;
        $data->per_km_fare = $req->per_km_fare;

        $data->save();
        $this->log(auth()->user()->fullname, 'Create Route', "Route ID: " . $data->id);
    }

    public function update(Request $req){
        $update = DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token']));
        $this->log(auth()->user()->fullname, 'Updated Route', "ID: $req->id");
    }

    public function delete(Request $req){
        Route::find($req->id)->delete();
        $this->log(auth()->user()->fullname, 'Delete Route', "ID: $req->id");
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
