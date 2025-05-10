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
use Carbon\Carbon;

class StoExport implements FromCollection, WithHeadings, WithStyles, WithTitle, WithColumnFormatting
{
    protected $categoryId;

    // Constructor untuk menerima category_id
    public function __construct($categoryId = null)
    {
        $this->categoryId = $categoryId;
    }

    // Mengambil data sesuai dengan kategori
    public function collection()
    {
        $query = Inventory::with('part', 'Category');

        if ($this->categoryId) {
            $query->where('id_category', $this->categoryId);
        }

        // Mengambil data dengan format yang sudah ditentukan
        return $query->get()->map(function ($sto) {
            return [
                'no' => $sto->id,
                'datetime' => $sto->created_at ? $sto->created_at->format('d-m-Y H:i:s') : '-',
                'inv_id' => $sto->part->Inv_id ?? '-',
                'part_name' => $sto->part->Part_name ?? '-',
                'part_number' => $sto->part->Part_number ?? '-',
                'plan_stock' => $sto->plan_stock ?? '-',
                'category_name' => $sto->Category->name ?? '-',
                'status' => $sto->status ? strtoupper($sto->status) : '-',
                'sto_priode' => $sto->created_at ? $sto->created_at->format('M Y') : '-',
            ];
        });
    }

    // Menambahkan Headings pada Excel
    public function headings(): array
    {
        return [
            'No',
            'DateTime',
            'Inv ID',
            'Part Name',
            'Part No',
            'Plan Stok',
            'Kategori',
            'Status',
            'STO Priode'
        ];
    }

    // Menambahkan Style pada Excel
    public function styles($sheet)
    {
        // Mengatur style untuk seluruh tabel
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);
        $sheet->getStyle('A1:I1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A1:I1')->getFill()->setFillType('solid')->getStartColor()->setRGB('F4CCCC'); // Header warna latar belakang

        // Mengatur ukuran kolom
        $sheet->getColumnDimension('A')->setWidth(5); // No
        $sheet->getColumnDimension('B')->setWidth(20); // DateTime
        $sheet->getColumnDimension('C')->setWidth(15); // Inv ID
        $sheet->getColumnDimension('D')->setWidth(25); // Part Name
        $sheet->getColumnDimension('E')->setWidth(15); // Part No
        $sheet->getColumnDimension('F')->setWidth(15); // Plan Stock
        $sheet->getColumnDimension('G')->setWidth(20); // Kategori
        $sheet->getColumnDimension('H')->setWidth(10); // Status
        $sheet->getColumnDimension('I')->setWidth(15); // STO Priode
    }

    // Memberikan judul untuk sheet
    public function title(): string
    {
        return 'List_STO_Data';  // Nama sheet
    }

    // Menambahkan formatting untuk kolom tertentu
    public function columnFormats(): array
    {
        return [
            'F' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1, // Format Plan Stok sebagai angka
            'B' => NumberFormat::FORMAT_DATE_DDMMYYYY,  // Format DateTime
        ];
    }
}
