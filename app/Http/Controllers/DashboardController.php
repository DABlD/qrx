<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Route, Device, Vehicle};

class DashboardController extends Controller
{
    function index(){
        if(auth()->user()->role != "Admin"){
            return redirect()->route('sale.manifest');
        }

        $users = User::whereIn('role', ['Admin', 'Coast Guard'])->count();
        $routes = Route::count();
        $vehicles = Vehicle::count();
        $devices = Device::count();

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
