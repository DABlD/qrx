<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Route, Device, Vehicle};

class DashboardController extends Controller
{
    function index(){
        $id = auth()->user()->id;

        // USERS
        $users = User::whereIn('role', ['Admin', "Branch"]);
        $users = $users->count();

        return $this->_view('dashboard', [
            'title'         => 'Dashboard',
            'users'         => $users,
            'routes'         => 0,
            'vehicles'         => 0,
            'devices'         => 0

        ]);
    }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
