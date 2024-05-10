<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\{FromView, WithEvents, ShouldAutoSize};
use Maatwebsite\Excel\Events\{AfterSheet};

class Loans implements FromView, WithEvents, ShouldAutoSize
{
    public function __construct($loans){
        $this->loans = $loans;
    }

    public function view(): View
    {
        return view('exports.loans', [
            'datas' => $this->loans,
        ]);
    }

    public function registerEvents(): array{
        $data = $this->loans;

        return [
            AfterSheet::class => function(AfterSheet $event) use($data) {
                $event->sheet->getDelegate()->setTitle("Loans", false);

                $event->sheet->getDelegate()->getStyle("A1:L1")->applyFromArray(
                    [
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'color' => [
                                'rgb' => 'FFFF00'
                            ]
                        ],
                    ]
                );

                $event->sheet->getDelegate()->getStyle('A1:L' . sizeof($data) + 1)->applyFromArray(
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

                $event->sheet->getDelegate()->getStyle('A1:L' . sizeof($data) + 1)->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ]
                ]);
            }
        ];
    }
}