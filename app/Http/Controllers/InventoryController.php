<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\Counter;
use App\Models\Jabatan;
use App\Models\Inventory;
use App\Models\User;
use App\Imports\InventoryImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventoryExport;
use App\Notifications\InventoryNotification;

class InventoryController extends Controller
{
    // Helper kirim notifikasi ke semua admin
    private function notifikasiAdmin(string $message, string $action = '/inventory')
    {
        $admins = User::where('is_admin', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new InventoryNotification([
                'user_id' => auth()->user()->id,
                'from'    => auth()->user()->name,
                'message' => $message,
                'action'  => $action,
            ]));
        }
    }

    public function index(Request $request)
    {
        $search = $request->search;

        $inventories = Inventory::with(['lokasi','jabatan'])
            ->when($search, function ($query) use ($search) {
                $query->where('kode_barang', 'like', "%{$search}%")
                    ->orWhere('jenis_barang', 'like', "%{$search}%")
                    ->orWhere('merek', 'like', "%{$search}%")
                    ->orWhere('nama_barang', 'like', "%{$search}%")
                    ->orWhere('uom', 'like', "%{$search}%")
                    ->orWhere('desc', 'like', "%{$search}%");
            });

        if (auth()->user()->is_admin !== 'admin') {
            $inventories->where('jabatan_id', auth()->user()->jabatan_id);
        }

        $inventories = $inventories->paginate(10)->withQueryString();

        $viewName = auth()->user()->is_admin === 'admin'
            ? 'inventory.index'
            : 'inventory.indexUser';

        return view($viewName, [
            'title'       => 'Data Inventory',
            'inventories' => $inventories,
        ]);
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

        $validated['jabatan_id'] = auth()->user()->jabatan_id;

        $inventory = Inventory::create($validated);

        $this->notifikasiAdmin(
            auth()->user()->name . ' menambahkan barang baru: ' . $inventory->nama_barang . ' (' . $inventory->kode_barang . ')'
        );

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

    // Cek field mana yang berubah sebelum update
    $labelMap = [
        'kode_barang'  => 'Kode Barang',
        'jenis_barang' => 'Jenis Barang',
        'merek'        => 'Merek',
        'nama_barang'  => 'Nama Barang',
        'stok'         => 'Stok',
        'uom'          => 'UoM',
        'desc'         => 'Description',
        'lokasi_id'    => 'Lokasi',
        'jabatan_id'   => 'Divisi/Jabatan',
    ];

    $perubahan = [];
    foreach ($labelMap as $field => $label) {
        $lama = (string) $inventory->$field;
        $baru = (string) ($validated[$field] ?? '');
        if ($lama !== $baru) {
            $perubahan[] = "{$label}: {$lama} → {$baru}";
        }
    }

    $inventory->update($validated);

    $detailPerubahan = count($perubahan) > 0
        ? ' | Perubahan: ' . implode(', ', $perubahan)
        : '';

    $this->notifikasiAdmin(
        auth()->user()->name . ' mengupdate barang: ' . $inventory->kode_barang . ' ' . $detailPerubahan
    );

    return redirect('/inventory')->with('success', 'Data Berhasil Diupdate');
}

    public function export()
    {
        $jabatanId = auth()->user()->is_admin === 'admin'
            ? null
            : auth()->user()->jabatan_id;

        $this->notifikasiAdmin(
            auth()->user()->name . ' mengekspor data inventory ke Excel'
        );

        return Excel::download(new InventoryExport($jabatanId), 'inventory.xlsx');
    }

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

                $this->notifikasiAdmin(
                    auth()->user()->name . ' mengimpor inventory namun ada error: ' . $msgs
                );

                return redirect('/inventory')->with('warning', 'Import selesai dengan beberapa error: ' . $msgs);
            }

            $this->notifikasiAdmin(
                auth()->user()->name . ' berhasil mengimpor data inventory dari Excel'
            );

            return redirect('/inventory')->with('success', 'Data Inventory berhasil diimport!');
        } catch (\Exception $e) {
            return redirect('/inventory')->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    public function downloadTemplate()
    {
        $filePath = '/mnt/user-data/uploads/inventory_metech_template.xlsx';

        if (file_exists($filePath)) {
            return response()->download(
                $filePath,
                'inventory_metech_template.xlsx',
                ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
            );
        }

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Inventory');

        $sheet->setCellValue('A1', 'Inventory Metech');
        $sheet->mergeCells('A1:I1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $headers = ['Kode Barang', 'Jenis Barang', 'Merek', 'Nama Barang', 'Stok', 'UoM', 'Description', 'Lokasi', 'Divisi / Jabatan'];
        $cols    = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I'];

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($cols[$i] . '3', $header);
        }

        $sheet->getStyle('A3:I3')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['rgb' => '2563EB']],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        foreach ($cols as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="inventory_metech_template.xlsx"',
        ]);
    }
}   