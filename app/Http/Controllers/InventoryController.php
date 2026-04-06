<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Counter;
use App\Models\Jabatan;
use App\Models\Inventory;
use App\Imports\InventoryImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    public function index()
    {
        $title = 'Inventory';
        $search = request()->input('search');
        $inventories = Inventory::when($search, function ($query) use ($search) {
                                    $query->where('nama_barang', 'LIKE', '%' . $search . '%')
                                          ->orWhere('kode_barang', 'LIKE', '%' . $search . '%');
                                })
                                ->when(auth()->user()->is_admin !== 'admin', function ($query) {
                                    $query->where('jabatan_id', auth()->user()->jabatan_id);
                                })
                                ->orderBy('id', 'DESC')
                                ->paginate(10)
                                ->withQueryString();

        return view(auth()->user()->is_admin == 'admin' ? 'inventory.index' : 'inventory.indexUser', compact(
            'title',
            'inventories'
        ));
    }

    public function tambah()
    {
        $title    = 'Inventory';
        $lokasi   = Lokasi::orderBy('nama_lokasi')->get();
        $jabatan  = Jabatan::orderBy('nama_jabatan')->get();
        $counter  = Counter::where('name', 'Inventory')->first();
        $counter->update(['counter' => $counter->counter + 1]);
        $next_number = str_pad($counter->counter, 6, '0', STR_PAD_LEFT);
        $kode_barang = $counter->text . '/' . $next_number;

        return view(auth()->user()->is_admin == 'admin' ? 'inventory.tambah' : 'inventory.tambahUser', compact(
            'title', 'lokasi', 'jabatan', 'kode_barang'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang'  => 'required',
            'jenis_barang' => 'nullable',
            'merek'        => 'nullable',
            'nama_barang'  => 'required',
            'stok'         => 'required',
            'uom'          => 'required',
            'desc'         => 'nullable',
            'lokasi_id'    => 'required',
            'jabatan_id'   => 'required',
        ]);

        // tambahkan jabatan user login
        $validated['jabatan_id'] = auth()->user()->jabatan_id;

        Inventory::create($validated);

        return redirect('/inventory')->with('success', 'Data Berhasil Disimpan');
    }

    public function edit($id)
    {
        $title     = 'Inventory';
        $lokasi    = Lokasi::orderBy('nama_lokasi')->get();
        $jabatan   = Jabatan::orderBy('nama_jabatan')->get();
        $inventory = Inventory::find($id);

        return view(auth()->user()->is_admin == 'admin' ? 'inventory.edit' : 'inventory.editUser', compact(
            'title', 'lokasi', 'jabatan', 'inventory'
        ));
    }

    public function export()
    {
        return Excel::download(new InventoryExport, 'inventory.xlsx');
    }

    public function update(Request $request, $id)
    {
        $inventory = Inventory::find($id);
        $validated = $request->validate([
            'kode_barang'  => 'required',
            'jenis_barang' => 'nullable',
            'merek'        => 'nullable',
            'nama_barang'  => 'required',
            'stok'         => 'required',
            'uom'          => 'required',
            'desc'         => 'nullable',
            'lokasi_id'    => 'required',
            'jabatan_id'   => 'required',
        ]);

        $inventory->update($validated);
        return redirect('/inventory')->with('success', 'Data Berhasil Diupdate');
    }

    public function delete($id)
    {
        $inventory = Inventory::find($id);
        $inventory->delete();
        return redirect('/inventory')->with('success', 'Data Berhasil Dihapus');
    }

    /**
     * Import data inventory dari file Excel (.xlsx)
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'Format file harus .xlsx atau .xls.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            $import = new InventoryImport();
            Excel::import($import, $request->file('file'));

            $errors = $import->errors();
            if ($errors->count() > 0) {
                $msgs = collect($errors)->map(fn($e) => $e->getMessage())->implode(' | ');
                return redirect('/inventory')->with('warning', 'Import selesai dengan beberapa error: ' . $msgs);
            }

            return redirect('/inventory')->with('success', 'Data Inventory berhasil diimport!');
        } catch (\Exception $e) {
            return redirect('/inventory')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Download template Excel Metech (sama persis dengan template asli)
     */
    public function downloadTemplate()
    {
        $filePath = '/mnt/user-data/uploads/inventory_metech_template.xlsx';

        // Jika file template asli tersedia, langsung kembalikan
        if (file_exists($filePath)) {
            return response()->download(
                $filePath,
                'inventory_metech_template.xlsx',
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            );
        }

        // Fallback: generate template dengan PhpSpreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Inventory');

        // Baris 1: Judul
        $sheet->setCellValue('A1', 'Inventory Metech');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Baris 2: kosong

        // Baris 3: Header kolom (SAMA PERSIS dengan template)
        $headers = ['Kode Barang', 'Jenis Barang', 'Merek', 'Nama Barang', 'Stok', 'UoM', 'Description', 'Lokasi', 'Divisi / Jabatan'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '3', $header);
        }

        // Style header baris 3
        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        foreach ($cols as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $headers_response = [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="inventory_metech_template.xlsx"',
        ];

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, $headers_response);
    }
}
