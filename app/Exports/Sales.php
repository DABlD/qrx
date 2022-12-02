<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, WithEvents, ShouldAutoSize};
use Maatwebsite\Excel\Events\{AfterSheet};

class Sales implements FromView, WithEvents, ShouldAutoSize
{
    public function __construct($sales){
        $this->sales = $sales;
    }

    public function view(): View
    {
        return view('exports.sales', [
            'datas' => $this->sales,
        ]);
    }

    public function registerEvents(): array{
        $data = $this->sales;

        return [
            AfterSheet::class => function(AfterSheet $event) use($data) {
                $event->sheet->getDelegate()->setTitle("Sales", false);

                $event->sheet->getDelegate()->getStyle('A1:H' . sizeof($data) + 1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);
            }
        ];
    }
}