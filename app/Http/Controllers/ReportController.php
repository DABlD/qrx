<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Transaction, Loan};

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

    public function types(Request $req){
        $from = now()->subMonth()->startOfDay()->toDateTimeString();
        $to = now()->endOfDay()->toDateTimeString();

        $temp = Loan::whereBetween('created_at', [$from, $to])->select('id', 'type', 'created_at')->get()->groupBy('type');

        $dataset = [];

        foreach($temp as $key => $type){
            $array = [];

            $from2 = $from;
            $to2 = $to;

            while($from2 <= $to2){
                $tempDate = now()->parse($from2);
                $array[$tempDate->toDateString()] = 0;
                
                $from2 = $tempDate->addDay()->toDateString();
            }

            foreach($type as $loan){
                $array[$loan->created_at->toDateString()]++;
            }

            $color = sprintf('#%06X', mt_rand(0, 0xFFFFFF));
            array_push($dataset, [
                "label" => $key,
                "data" => $array,
                'borderColor' => $color,
                'backgroundColor' => $color,
                'hoverRadius' => 10
            ]);
        }

        echo json_encode(["dataset" => $dataset]);
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
