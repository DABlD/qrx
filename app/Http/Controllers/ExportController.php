<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\{Sales, Manifest};
use App\Models\{Sale, AuditTrail};

class ExportController extends Controller
{
    public function sales(Request $req){
        $array = Sale::select("*");

        if(auth()->user()->role != "Admin"){
            $array = $array->where('company_id', auth()->user()->id);
        }

        $from = now()->parse($req->from)->startOfDay()->toDateTimeString();
        $to = now()->parse($req->to)->endOfDay()->toDateTimeString();

        $array = $array->whereBetween('created_at', [$from, $to]);
        $array = $array->where('status', 'like', $req->status);
        $array = $array->where('ticket', 'like', substr($req->device, -6));

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

        foreach($array as $sale){
            $sale->user = json_decode($sale->user);
        }

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

        $this->log(auth()->user()->fullname, "Export", "Sales");

        return Excel::download(new Sales($array), 'Sales - ' . now()->toDateTimeString() . '.xlsx');
    }

    public function manifest(Request $req){
        $array = Sale::select("*");

        if(auth()->user()->role != "Admin"){
            $array = $array->where('company_id', auth()->user()->id);
        }

        $from = now()->parse($req->from)->startOfDay()->toDateTimeString();
        $to = now()->parse($req->to)->endOfDay()->toDateTimeString();

        $array = $array->whereBetween('created_at', [$from, $to]);
        $array = $array->where('status', 'like', $req->status);
        $array = $array->where('ticket', 'like', substr($req->device, -6));

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

        foreach($array as $sale){
            $sale->user = json_decode($sale->user);
        }

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

        $this->log(auth()->user()->fullname, "Export", "Manifest");

        return Excel::download(new Manifest($array), 'Manifest - ' . now()->toDateTimeString() . '.xlsx');
    }

    public function log($user, $action, $description){
        $data = new AuditTrail();
        $data->uid = $user;
        $data->action = $action;
        $data->description = $description;
        $data->save();
    }
}
