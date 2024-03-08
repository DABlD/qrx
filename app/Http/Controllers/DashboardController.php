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
        $loans = Loan::where('status', '!=', "Applied")->get();
        $transactions = Transaction::count();
        $revenue = 0;

        foreach($loans as $loan){
            $total = (($loan->amount * ($loan->percent / 100)) + ($loan->amount / $loan->months)) * $loan->months;
            $revenue += $total - $loan->amount;
        }

        return $this->_view('dashboard', [
            'title'         => 'Dashboard',
            'clients'       => $clients,
            'loans'         => $loans->count(),
            'payments'      => $transactions,
            'revenue'       => $revenue
        ]);
    }

    private function _view($view, $data = array()){
        return view($view, $data);
    }
}
