<?php
namespace App\Exports;

use App\Models\Inventory;
use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class StoExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnFormatting
{
    protected $categoryId;

    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    public function collection()
    {
        $query = Inventory::with('part', 'part.category');

        if ($this->categoryId) {
            $query->whereHas('part', function ($q) {
                $q->where('id_category', $this->categoryId);
            });
        }

        return $query->get()->map(function ($sto) {
            return [
                'no' => $sto->id,
                'datetime' => $sto->created_at ? $sto->created_at->format('d-m-Y H:i:s') : '-',
                'inv_id' => $sto->part->Inv_id ?? '-',
                'part_name' => $sto->part->Part_name ?? '-',
                'part_number' => $sto->part->Part_number ?? '-',
                'plan_stock' => $sto->plan_stock ?? '-',
                'act_stock' => $sto->act_stock ?? '-',
                'category_name' => $sto->part->category->name ?? '-',
                'sto_priode' => $sto->created_at ? $sto->created_at->format('M Y') : '-',
                'remark' => $sto->remark ?? '-',
                'note_remark' => $sto->note_remark ?? '-',
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
            'Part No',
            'Plan Stok',
            'Act Stok',
            'Kategori',
            'STO Priode',
            'Remark',
            'Note Remark'
        ];
    }

    public function styles($sheet)
    {
        // Header styling
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:K1')->getFill()
            ->setFillType('solid')
            ->getStartColor()->setRGB('F4CCCC');

        // Kolom width
        $sheet->getColumnDimension('A')->setWidth(7);
        $sheet->getColumnDimension('B')->setWidth(13);
        $sheet->getColumnDimension('C')->setWidth(11);
        $sheet->getColumnDimension('D')->setWidth(14);
        $sheet->getColumnDimension('E')->setWidth(12);
        $sheet->getColumnDimension('F')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(13);
        $sheet->getColumnDimension('H')->setWidth(13);
        $sheet->getColumnDimension('I')->setWidth(15);
        $sheet->getColumnDimension('J')->setWidth(11);
        $sheet->getColumnDimension('K')->setWidth(16);

         $sheet->getStyle('A1:K' . $sheet->getHighestRow())->applyFromArray([
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
        return 'List_STO_Data';
    }

    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Plan Stock
            'G' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Act Stock
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY, // DateTime
        ];
    }
}
