<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, WithEvents, ShouldAutoSize};
use Maatwebsite\Excel\Events\{AfterSheet};

class Transaction implements FromView, WithEvents, ShouldAutoSize
{
    public function __construct($transactions){
        $this->transactions = $transactions;
    }

    public function view(): View
    {
        return view('exports.transactions', [
            'datas' => $this->transactions,
        ]);
    }

    public function registerEvents(): array{
        $data = $this->transactions;

        return [
            AfterSheet::class => function(AfterSheet $event) use($data) {
                $event->sheet->getDelegate()->setTitle("Transactions", false);

                $event->sheet->getDelegate()->getStyle("A1:G1")->applyFromArray(
                    [
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => [
                                'rgb' => 'FFFF00'
                            ]
                        ],
                    ]
                );

                $event->sheet->getDelegate()->getStyle('A1:G' . sizeof($data) + 1)->applyFromArray(
                    [
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            ],
                        ]
                    ]
                );

                $event->sheet->getDelegate()->getStyle('A1:G' . sizeof($data) + 1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);
            }
        ];
    }
}