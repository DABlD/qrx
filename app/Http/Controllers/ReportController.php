<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;

class ReportController extends Controller
{
    public function payments(Request $req){
        $from = now()->subMonth()->startOfDay()->toDateTimeString();
        $to = now()->endOfDay()->toDateTimeString();

        $temp = Transaction::whereBetween('payment_date', [$from, $to])->where('type', 'CR');
        $temp = $temp->get();

        $array = [];

        while($from <= $to){
            $tempDate = now()->parse($from);
            $array[$tempDate->toDateString()] = 0;
            
            $from = $tempDate->addDay()->toDateString();
        }

        foreach($temp as $payment){
            $array[$payment->created_at->toDateString()] += $payment->amount;
        }

        $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
        $array = [
            "dataset" => [
                [
                    "label" => "Payments per day for the last 30 days",
                    "data" => $array,
                    'borderColor' => $color,
                    'backgroundColor' => $color,
                    'hoverRadius' => 10
                ]
            ],
            // "labels" => ["label 1", "label 2", "label 3", "label 4", 'label 5']
        ];

        echo json_encode($array);
    }

    private function getDates($from, $to){
        $dates = [];

        while($from <= $to){
            $tempDate = now()->parse($from);
            array_push($dates, $tempDate->toDateTimeString());
            $from = $tempDate->addDay()->toDateString();
        }

        return $dates;
    }
}
