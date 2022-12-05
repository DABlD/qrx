<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;
use App\Models\{Device, Route, Station, User, Vehicle, Sale, AuditTrail};
use DB;

class ApiController extends Controller
{
    public function users(Request $req){
        $array = User::select($req->select ?? "*");
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

        return $array;
    }

    public function routes(Request $req){
        $array = Route::select($req->select ?? "*");
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
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'routes.id');
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

        return $array;
    }

    public function vehicles(Request $req){
        $array = Vehicle::select($req->select ?? "*");
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
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'vehicles.id');
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

        return $array;
    }

    public function devices(Request $req){
        $array = Device::select($req->select ?? "*");
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
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'devices.id');
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

        return $array;
    }

    public function stations(Request $req){
        $array = Station::select($req->select ?? "*");
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
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'stations.id');
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

        return $array;
    }

    public function sales(Request $req){
        $array = Sale::select($req->select ?? "*");
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
            $array = $array->join("$req->join as $alias", "$alias.fid", '=', 'sales.id');
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

        // decode user
        foreach($array as $sale){
            $sale->user = json_decode($sale->user);
        }

        return $array;
    }

    public function createSale(Request $req){
        $user = Http::get('https://qr-transit.onehealthnetwork.com.ph/api/v1/users/' . $req->user_id);
        $user = json_decode($user)->data;

        $ticket = substr($req->device_id, -5);
        $ticket_no = Sale::where('ticket', $ticket)->where('created_at', 'like', now()->format('Y-m-d') . "%")->count() + 1;

        $data = new Sale();
        $data->origin_id = $req->origin_id;
        $data->destination_id = $req->destination_id;
        $data->user = json_encode($user);
        $data->ticket = $ticket . now()->format('ymd');
        $data->ticket_no = $ticket_no;
        $data->amount = $req->amount;

        // DEDUCT FIRST
        $deduction = $this->deductUser($user, $req->amount);

        // CHECK IF SUCCESSFULLY DETECTED
        if($deduction->successful()){

            // SEND SMS NOTIF
            $sms = $this->sendSms($data, $user);

            // CHECK IF SAVE SALE SUCCESFFUL
            if($data->save()){
                $data->user = json_decode($data->user);
                $this->log($user->name, "Transact", "Sales ID: " . $data->id);

                // IF HAS LOAD
                if(isset($req->load)){
                    foreach($req->load as $table){
                        $data->load($table);
                    }
                }

                return [
                    "status" => "Success",
                    "data" => $data
                ];
            }
            else{
                return [
                    "status" => "Error",
                    "error" => "Failed To Create Transaction"
                ];
            }
        }
        else{
            return [
                "status" => "Error",
                "error" => "Failed to Deduct from User"
            ];
        }
    }

    public function updateSale(Request $req){
        $sale = Sale::where('id', $req->id)->first();

        if(isset($req->vehicle_id)){
            $sale->vehicle_id = $req->vehicle_id;
        }

        $sale->status = $req->status;

        if($req->status == "Embarked"){
            $sale->embarked_date = now()->toDateTimeString();
        }

        if($sale->save()){
            $sale->user = json_decode($sale->user);

            // IF HAS LOAD
            if(isset($req->load)){
                foreach($req->load as $table){
                    $sale->load($table);
                }
            }

            $this->log($sale->user->name, "Updated Transaction", "ID #" . $sale->id . " status updated to " . $req->status);
            return $sale;
        }
        else{
            return [
                "status" => "Error",
                "error" => "Failed To Update Status"
            ];
        }
    }

    public function deductUser($user, $amount){
        $response = Http::withHeaders([
            "X-API-KEY" => env("LEDGER_APIKEY")
        ])->post('https://qr-transit.onehealthnetwork.com.ph/api/v1/ledger-entry', [
            "remark" => "Loading",
            "type" => "Debit",
            "amount" => $amount,
            "mobile_number" => $user->mobile_number
        ]);

        return $response;
    }

    public function sendSms($sale, $user){
        $from = Station::find($sale->origin_id)->name;
        $to = Station::find($sale->destination_id)->name;
        $now = now()->format('Y-m-d h:i A');

        $response = Http::withBasicAuth(env("SMS_USERNAME"),env("SMS_PASSWORD"))
            ->post('http://13.228.103.95:8063/core/sender', [
                "accesskey" => env('SMS_ACCESSKEY'),
                "service" => "mt",
                "data" => [
                    // "to" => $user->mobile_number,
                    "to" => "639154590172",
                    "message" => `
                        You have successfully paid the trip. Here is your boarding pass information: \n
                        Ticket No: $sale->ticket_no\n
                        Amount: $sale->amount\n
                        From: $from\n
                        To: $to\n
                        Date: $now
                    `
                ]
            ]);
    }

    public function log($user, $action, $description){
        $data = new AuditTrail();
        $data->uid = $user;
        $data->action = $action;
        $data->description = $description;
        $data->save();
    }
}