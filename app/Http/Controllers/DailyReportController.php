<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\DailyStockLog;
use App\Models\Part;
use App\Models\BoxComplete;
use App\Models\BoxUncomplete;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Barryvdh\DomPDF\Facade\Pdf;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
class DailyReportController extends Controller
{
    //get halama utama
    public function index()
    {
        return view('daily_report.index');
    }
    // jika tidak ada inventory id
    public function create()
    {
        $parts = Part::with(['category', 'plant', 'area', 'rak'])->get();
        return view('daily_report.create-daily', compact('parts'));
    }

    //post untuk simpan data baru inventory id
    public function storeNew(Request $request)
    {
        $data = $request->validate([
            'inv_id' => 'required|string|exists:tbl_part,Inv_id',
            'status' => 'required|string',
            'qty_per_box' => 'required|integer',
            'qty_box' => 'required|integer',
            'total' => 'required|integer',
            'qty_per_box_2' => 'nullable|integer',
            'qty_box_2' => 'nullable|integer',
            'total_2' => 'nullable|integer',
            'grand_total' => 'required|integer',
            'plan_stock' => 'required|integer',
            'issued_date' => 'required|date',
            'prepared_by' => 'required|integer',
        ]);

        $part = Part::where('Inv_id', $data['inv_id'])->firstOrFail();

        // Cek apakah inventory untuk part ini sudah ada
        $existingInventory = Inventory::where('id_part', $part->id)->first();
        if ($existingInventory) {
            return back()->with('error', 'Inventory untuk part ini sudah ada!');
        }

        // Hitung remark & note_remark
        $gap = $data['grand_total'] - $data['plan_stock'];
        $remark = $gap === 0 ? 'normal' : 'abnormal';
        $note_remark = $gap === 0 ? null : 'gap: ' . $gap;

        // Simpan Inventory
        $inventory = Inventory::create([
            'id_part' => $part->id,
            'plan_stock' => $data['plan_stock'],
            'remark' => $remark,
            'note_remark' => $note_remark,
            'act_stock' => $data['grand_total'],
        ]);

        // Simpan Box Complete
        $boxComplete = BoxComplete::create([
            'qty_per_box' => $data['qty_per_box'],
            'qty_box' => $data['qty_box'],
            'total' => $data['total'],
        ]);

        // Simpan Box Uncomplete jika ada
        $boxUncomplete = null;
        if (!empty($data['qty_per_box_2']) && !empty($data['qty_box_2'])) {
            $boxUncomplete = BoxUncomplete::create([
                'qty_per_box' => $data['qty_per_box_2'],
                'qty_box' => $data['qty_box_2'],
                'total' => $data['total_2'],
            ]);
        }


        $dailyLog = DailyStockLog::create([
            'id_inventory' => $inventory->id,
            'id_box_complete' => $boxComplete->id,
            'id_box_uncomplete' => $boxUncomplete?->id,
            'Total_qty' => $data['grand_total'],
            'prepared_by' => $data['prepared_by'],
            'status' => $data['status'],
        ]);


        return redirect()->route('dailyreport.index')
            ->with('success', 'Inventory & Daily Stock berhasil disimpan.')
            ->with('report', $dailyLog->id);

    }

    // halaman untuk buat isi qty sto
    public function form($inventory_id)
    {
        $inventory = Inventory::find($inventory_id); // langsung by ID

        if ($inventory) {
            return view('daily_report.form', compact('inventory'));
        }

        return back()->with('error', 'Inventory not found. Please try again.');
    }

    // Proses scan Inventory ID get daily report
    public function scan(Request $request)
    {
        $request->validate([
            'inventory_id' => 'required|string'
        ]);

        // 1. Cari Part berdasarkan Inv_id
        $part = Part::where('Inv_id', $request->inventory_id)->first();

        if (!$part) {
            return back()->with('error', 'Inventory ID tidak ditemukan pada tabel Part.');
        }

        // 2. Cari Inventory berdasarkan id_part dari Part yang ditemukan
        $inventory = Inventory::where('id_part', $part->id)->latest()->first();

        if (!$inventory) {
            return back()->with('error', 'Inventory tidak ditemukan untuk Part tersebut.');
        }

        // 3. Redirect ke halaman edit atau sesuai kebutuhan
        return redirect()->route('sto.edit', ['inventory_id' => $inventory->id]);

    }

    public function storecreate(Request $request, $inventory_id)
    {
        $inventory = Inventory::findOrFail($inventory_id);

        $data = $request->validate([
            'status' => 'required|string',
            'qty_per_box' => 'nullable|integer',
            'qty_box' => 'nullable|integer',
            'total' => 'nullable|integer',
            'qty_per_box_2' => 'nullable|integer',
            'qty_box_2' => 'nullable|integer',
            'total_2' => 'nullable|integer',
            'grand_total' => 'required|integer',
            'issued_date' => 'required|date',
            'prepared_by' => 'required|integer',
        ]);

        // Simpan BoxComplete
        $boxComplete = BoxComplete::create([
            'qty_per_box' => $data['qty_per_box'],
            'qty_box' => $data['qty_box'],
            'total' => $data['total'],
        ]);

        // Simpan BoxUncomplete jika ada
        $boxUncomplete = null;
        if (!empty($data['qty_per_box_2']) && !empty($data['qty_box_2'])) {
            $boxUncomplete = BoxUncomplete::create([
                'qty_per_box' => $data['qty_per_box_2'],
                'qty_box' => $data['qty_box_2'],
                'total' => $data['total_2'],
            ]);
        }

        // Simpan DailyStockLog
        $dailyLog = DailyStockLog::create([
            'id_inventory' => $inventory->id,
            'id_box_complete' => $boxComplete->id,
            'id_box_uncomplete' => $boxUncomplete?->id,
            'Total_qty' => $data['grand_total'],
            'prepared_by' => $data['prepared_by'],
            'status' => $data['status'],
        ]);

        // Re-calculate grand total dari semua STO untuk inventory ini
        $totalActual = DailyStockLog::where('id_inventory', $inventory->id)->sum('Total_qty');

        // Hitung gap dan remark
        $gap = $totalActual - $inventory->plan_stock;
        $remark = ($gap === 0) ? 'normal' : 'abnormal';
        $note_remark = ($gap === 0) ? null : 'gap: ' . $gap;

        // Update inventory
        $inventory->update([
            'act_stock' => $totalActual,
            'remark' => $remark,
            'note_remark' => $note_remark,
        ]);

        return redirect()->route('dailyreport.index')
            ->with('success', 'Data berhasil disimpan.')
            ->with('report', $dailyLog->id);
    }


    // print pdf
    public function printReport($id)
    {
        // Ambil data laporan lengkap beserta relasinya
        $report = DailyStockLog::with([
            'inventory.part.category',
            'inventory.part.plant',
            'inventory.part.area',
            'user'
        ])->findOrFail($id);

        // Buat QR Code SVG
        $renderer = new ImageRenderer(
            new RendererStyle(300),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrSvg = $writer->writeString($report->inventory->part->Inv_id ?? '-');
        $qrBase64 = 'data:image/svg+xml;base64,' . base64_encode($qrSvg);

        // Generate PDF
        return Pdf::loadView('pdf.daily_report', [
            'report' => $report,
            'qrCodeBase64' => $qrBase64,
        ])
            ->setPaper([0, 0, 226.77, 600], 'portrait') // 80mm x 21cm
            ->download('report_' . $report->id . '.pdf');
    }

    public function search(Request $request)
    {
        $query = $request->input('query');

        $results = Part::where('Part_name', 'like', '%' . $query . '%')
            ->orWhere('Part_number', 'like', '%' . $query . '%')
            ->get(['Inv_id as inventory_id', 'Part_name as part_name', 'Part_number as part_number']);

        return view('daily_report.search', compact('results'));
    }

    // edit daily report
    public function editLog($id)
    {
        $log = DailyStockLog::with('inventory.part.category', 'inventory.part.plant', 'inventory.part.area', 'inventory.part.rak')->findOrFail($id);
        $statuses = ['OK', 'NG', 'Virgin', 'Funsai'];

        return view('daily_report.edit', compact('log', 'statuses'));
    }

    public function updateLog(Request $request, $id)
    {
        $data = $request->validate([
            'plan_stock' => 'required|integer',
        ]);

        $log = DailyStockLog::with('inventory')->findOrFail($id);
        $inventory = $log->inventory;

        if (!$inventory) {
            return back()->with('error', 'Inventory tidak ditemukan.');
        }

        // Hitung ulang remark & gap
        $gap = $inventory->act_stock - $data['plan_stock'];
        $remark = ($gap === 0) ? 'normal' : 'abnormal';
        $note_remark = ($gap === 0) ? null : 'gap: ' . $gap;

        $inventory->update([
            'plan_stock' => $data['plan_stock'],
            'remark' => $remark,
            'note_remark' => $note_remark,
        ]);

        return redirect()->route('dailyreport.index')->with('success', 'Plan stock berhasil diperbarui dari DailyStockLog.');
    }


}
