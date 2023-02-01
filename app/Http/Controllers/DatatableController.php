<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Route, Device, Station, Vehicle, Sale, AuditTrail, Ad, Ledger};
use DB;

class DatatableController extends Controller
{
    public function user(Request $req){
        $array = User::select($req->select);
        $array = $array->whereIn('role', ['Admin', 'Coast Guard']);

        // IF HAS SORT PARAMETER $ORDER
        if($req->order){
            $array = $array->orderBy($req->order[0], $req->order[1]);
        }

        // IF HAS JOIN
        if($req->join){
            $alias = substr($req->join, 1);
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        }

        $array = $array->get();

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
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


        echo json_encode($array);
    }

    public function company(Request $req){
        $array = User::select($req->select);
        $array = $array->where('role', 'Company');

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

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
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

        echo json_encode($array);
    }

    public function route(Request $req){
        $array = Route::select($req->select);

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
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

        $array = $array->get();

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        foreach($array as $key => $data){
            if($data->company == null){
                $array->forget($key);
            }
        }

        $array = collect($array->values());

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }


        echo json_encode($array);
    }

    public function station(Request $req){
        $array = Station::select($req->select);

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
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

        $array = $array->get();

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
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


        echo json_encode($array);
    }

    public function device(Request $req){
        $array = Device::select($req->select);

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
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

        $array = $array->get();

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        foreach($array as $key => $data){
            if($data->company == null){
                $array->forget($key);
            }
        }

        $array = collect($array->values());

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }


        echo json_encode($array);
    }

    public function vehicle(Request $req){
        $array = Vehicle::select($req->select);

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
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

        $array = $array->get();

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        foreach($array as $key => $data){
            if($data->company == null){
                $array->forget($key);
            }
        }

        $array = collect($array->values());

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }


        echo json_encode($array);
    }

    public function sale(Request $req){
        $array = Sale::select($req->select);

        $from = now()->parse($req->from)->startOfDay()->toDateTimeString();
        $to = now()->parse($req->to)->endOfDay()->toDateTimeString();

        $array = $array->whereBetween('created_at', [$from, $to]);
        $array = $array->where('status', 'like', $req->status);
        $array = $array->where('ticket', 'like', substr($req->device, -6));

        // dd(substr($req->device, -5));

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
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

        $array = $array->get();

        foreach($array as $sale){
            $sale->user = json_decode($sale->user);
            $sale->actions = $sale->actions;

            $sale->di = substr($sale->ticket, 0, 5);
        }

        // IF HAS LOAD
        if($array->count() && $req->load){
            foreach($req->load as $table){
                $array->load($table);
            }
        }

        foreach($array as $key => $data){
            if($data->company == null){
                $array->forget($key);
            }
        }

        $array = collect($array->values());

        // IF HAS GROUP
        if($req->group){
            $array = $array->groupBy($req->group);
        }


        echo json_encode($array);
    }
    public function ad(Request $req){
        $array = Ad::select($req->select);

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

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
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


        echo json_encode($array);
    }

    public function audit_trails(Request $req){
        $array = AuditTrail::select($req->select);

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
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

        $array = $array->get();

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
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


        echo json_encode($array);
    }

    public function ledgers(Request $req){
        $array = Ledger::select($req->select);

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
        // if($req->join){
        //     $alias = substr($req->join, 1);
        //     $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'users.id');
        // }

        $array = $array->get();

        // FOR ACTIONS
        foreach($array as $item){
            $item->actions = $item->actions;
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


        echo json_encode($array);
    }
}