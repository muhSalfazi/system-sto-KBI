<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class convertExcelToCsv extends Controller
{
    public function convert(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        // Load file excel
        $spreadsheet = IOFactory::load($path);

        // Buat writer CSV
        $writer = IOFactory::createWriter($spreadsheet, 'Csv');

        // Ambil nama file tanpa ekstensi
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $cleanName = Str::slug($originalName, '_'); // aman untuk filename

        // Buat nama file baru
        $filename = 'KBi_' . $cleanName . '_' . now()->format('dmY_His') . '.csv';

        // Simpan CSV di storage sementara
        $csvPath = storage_path('app/public/' . $filename);
        $writer->save($csvPath);

        return response()->download($csvPath)->deleteFileAfterSend(true);
    }
}
