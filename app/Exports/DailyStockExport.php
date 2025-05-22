<?php

namespace App\Exports;

use App\Models\DailyStockLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class DailyStockExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnFormatting, WithCustomStartCell
{
    protected $status;
    protected $category;
    protected $date;

    public function __construct($status = null, $category = null, $date = null)
    {
        $this->status = $status;
        $this->category = $category;
        $this->date = $date;
    }


    public function startCell(): string
    {
        return 'A2';
    }

    public function collection()
    {
        $query = DailyStockLog::with(['inventory.part.forecast', 'inventory.part.customer', 'user']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        if ($this->category) {
            $query->whereHas('inventory.part', function ($q) {
                $q->where('id_category', $this->category);
            });
        }

        if ($this->date) {
            $query->whereDate('created_at', $this->date);
        }

        return $query->get()->map(function ($log, $index) {
            $part = optional($log->inventory)->part;
            $forecastMin = '-';
            $forecastMax = '-';

            if ($part && $log->created_at) {
                $forecastMonth = Carbon::parse($log->created_at)->startOfMonth();
                $forecast = $part->forecast()->whereDate('forecast_month', $forecastMonth)->first();

                if ($forecast) {
                    $forecastMin = $forecast->min ?? '-';
                    $forecastMax = $forecast->max ?? '-';
                }
            }

            return [
                $index + 1,
                optional($log->created_at)->format('d-m-Y H:i:s'),
                $part->Inv_id ?? '-',
                $part->Part_name ?? '-',
                $part->Part_number ?? '-',
                $forecastMin,
                $forecastMax,
                $log->Total_qty,
                $log->stock_per_day ?? '-',
                optional($part->customer)->username ?? '-',
                strtoupper($log->status ?? '-'),
                $log->user->username ?? '-',
            ];
        });
    }


    public function headings(): array
    {
        return [
            [
                'No',
                'DateTime',
                'Inv ID',
                'Part Name',
                'Part No',
                'STO STOCK PCS',
                '',
                'ACT STOCK',
                '',
                'Customer',
                'Status',
                'Prepared By'
            ],
            [
                '',
                '',
                '',
                '',
                '',
                'Min',
                'Max',
                'Qty',
                'Day',
                '',
                '',
                ''
            ]
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Merge header
        $sheet->mergeCells('A2:A3');
        $sheet->mergeCells('B2:B3');
        $sheet->mergeCells('C2:C3');
        $sheet->mergeCells('D2:D3');
        $sheet->mergeCells('E2:E3');
        $sheet->mergeCells('F2:G2');
        $sheet->mergeCells('H2:I2');
        $sheet->mergeCells('J2:J3');
        $sheet->mergeCells('K2:K3');
        $sheet->mergeCells('L2:L3');

        // Style header
        $sheet->getStyle('A2:L3')->getFont()->setBold(true);
        $sheet->getStyle('A2:L3')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A2:L3')->getAlignment()->setVertical('center');
        $sheet->getStyle('A2:L3')->getFill()->setFillType('solid')->getStartColor()->setRGB('D9E1F2');

        // Set column width
        $columns = range('A', 'L');
        $widths = [5, 20, 15, 25, 20, 8, 8, 10, 8, 15, 10, 20];
        foreach ($columns as $i => $col) {
            $sheet->getColumnDimension($col)->setWidth($widths[$i]);
        }

        // Tambahkan border ke seluruh tabel
        $sheet->getStyle('A2:L' . $sheet->getHighestRow())->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }

    public function title(): string
    {
        return 'Daily_Stock';
    }

    public function columnFormats(): array
    {
        return [
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,
            'H' => NumberFormat::FORMAT_NUMBER,
            'I' => NumberFormat::FORMAT_NUMBER,
        ];
    }
}
