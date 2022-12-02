<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Sale};
use DB;

class SaleController extends Controller
{
    public function __construct(){
        $this->table = "sales";
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
        $data = new Sale();
        $data->origin_id = $req->origin_id;
        $data->destination_id = $req->destination_id;
        $data->vehicle_id = $req->vehicle_id;
        $data->user_id = $req->user_id;
        $data->ticket = $req->ticket;
        $data->ticket_no = $req->ticket_no;
        $data->amount = $req->amount;
        $data->status = $req->status;
        $data->embarked_date = $req->embarked_date;

        echo $data->save();
    }

    public function update(Request $req){
        echo DB::table($this->table)->where('id', $req->id)->update($req->except(['id', '_token']));
    }

    public function delete(Request $req){
        Sale::find($req->id)->delete();
    }

    public function index(){
        return $this->_view('index', [
            'title' => ucfirst($this->table)
        ]);
    }

    public function manifest(){
        return $this->_view('manifest', [
            'title' => ucfirst($this->table)
        ]);
    }

    private function _view($view, $data = array()){
        return view("$this->table." . $view, $data);
    }
}
