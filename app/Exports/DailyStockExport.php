<?php

namespace App\Exports;

use App\Models\DailyStockLog;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class DailyStockExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnFormatting
{
    protected $status;

    public function __construct($status = null)
    {
        $this->status = $status;
    }

    public function collection()
    {
        $query = DailyStockLog::with(['inventory.part.forecast', 'inventory.part.customer', 'user']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get()->map(function ($log, $index) {
            $part = optional($log->inventory)->part;
            $forecast = optional($part)->forecast->first(); // ambil hanya 1 forecast

            return [
                'no' => $index + 1,
                'datetime' => optional($log->created_at)->format('d-m-Y H:i:s'),
                'inv_id' => $part->Inv_id ?? '-',
                'part_name' => $part->Part_name ?? '-',
                'part_number' => $part->Part_number ?? '-',
                'min' => $forecast->min ?? '-',
                'max' => $forecast->max ?? '-',
                'total_qty' => $log->Total_qty,
                'daily_stock' => $log->stock_per_day ?? '-',
                'customer' => optional($part->customer)->username ?? '-',
                'status' => strtoupper($log->status ?? '-'),
                'prepared_by' => $log->user->username ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'DateTime',
            'Inv ID',
            'Part Name',
            'Part Number',
            'Min',
            'Max',
            'Total Qty',
            'Daily Stock',
            'Customer',
            'Status',
            'Prepared By',
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:L1')->getFont()->setBold(true);
        $sheet->getStyle('A1:L1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:L1')->getFill()->setFillType('solid')->getStartColor()->setRGB('D9E1F2');

        // Atur lebar kolom
        $columns = range('A', 'L');
        $widths = [5, 20, 15, 25, 20, 8, 8, 12, 12, 15, 10, 20];

        foreach ($columns as $i => $col) {
            $sheet->getColumnDimension($col)->setWidth($widths[$i]);
        }
    }

    public function title(): string
    {
        return 'Daily_Stock';
    }

    public function columnFormats(): array
    {
        return [
            'H' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Total Qty
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,            // DateTime
        ];
    }
}
