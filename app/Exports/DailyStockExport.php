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
        $query = DailyStockLog::with(['inventory.part.customer', 'user']);

        if ($this->status) {
            $query->where('status', $this->status);
        }

        return $query->get()->map(function ($log, $index) {
            return [
                'no' => $index + 1,
                'datetime' => optional($log->created_at)->format('d-m-Y H:i:s'),
                'inv_id' => $log->inventory->part->Inv_id ?? '-',
                'part_name' => $log->inventory->part->Part_name ?? '-',
                'part_number' => $log->inventory->part->Part_number ?? '-',
                'total_qty' => $log->Total_qty,
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
            'Total Qty',
            'Status',
            'Prepared By',
        ];
    }

    public function styles($sheet)
    {
        $sheet->getStyle('A1:H1')->getFont()->setBold(true);
        $sheet->getStyle('A1:H1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:H1')->getFill()->setFillType('solid')->getStartColor()->setRGB('D9E1F2');

        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(25);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(12);
        $sheet->getColumnDimension('G')->setWidth(10);
        $sheet->getColumnDimension('H')->setWidth(20);
    }

    public function title(): string
    {
        return 'Daily_Stock';
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Total Qty
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,           // DateTime
        ];
    }
}
