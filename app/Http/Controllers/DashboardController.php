<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{User, Loan, Transaction, Branch};

class DashboardController extends Controller
{
    function index(){
        $id = auth()->user()->id;

        // USERS
        $clients = Branch::count();
        $loans = Loan::count();
        $transactions = Transaction::count();

        return $this->_view('dashboard', [
            'title'         => 'Dashboard',
            'clients'         => $clients,
            'loans'         => $loans,
            'payments'         => $transactions,
            'revenue'         => 0

        ]);
    }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
