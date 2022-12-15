<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Route, Device, Vehicle};

class DashboardController extends Controller
{
    function index(){
        if(auth()->user()->role == "Coast Guard"){
            return redirect()->route('sale.manifest');
        }

        $id = auth()->user()->id;

        // USERS
        $users = User::whereIn('role', ['Admin', 'Coast Guard', "Company"]);
        $users = $users->count();

        // ROUTES
        $routes = Route::select('*');
        if(auth()->user()->role == "Company"){
            $routes = $routes->where('company_id', $id);
        }
        $routes = $routes->count();

        // VEHICLES
        $vehicles = Vehicle::select('*');
        if(auth()->user()->role == "Company"){
            $vehicles = $vehicles->where('company_id', $id);
        }
        $vehicles = $vehicles->count();

        // DEVICES
        $devices = Device::select('*');
        if(auth()->user()->role == "Company"){
            $devices = $devices->where('company_id', $id);
        }
        $devices = $devices->count();

        return $this->_view('dashboard', [
            'title'         => 'Dashboard',
            'users'         => $users,
            'routes'         => $routes,
            'vehicles'         => $vehicles,
            'devices'         => $devices

        ]);
    }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
