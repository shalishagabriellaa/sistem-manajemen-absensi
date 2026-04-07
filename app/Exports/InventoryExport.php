<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InventoryExport implements FromCollection, WithHeadings, WithMapping
{
    protected $jabatanId;

    // Terima jabatan_id, null berarti admin (semua data)
    public function __construct($jabatanId = null)
    {
        $this->jabatanId = $jabatanId;
    }

    public function collection()
    {
        $query = Inventory::with(['lokasi', 'jabatan']);

        if ($this->jabatanId) {
            $query->where('jabatan_id', $this->jabatanId);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return [
            'Kode Barang', 'Jenis Barang', 'Merek', 'Nama Barang',
            'Stok', 'UoM', 'Description', 'Lokasi', 'Divisi / Jabatan'
        ];
    }

    public function map($inventory): array
    {
        return [
            $inventory->kode_barang,
            $inventory->jenis_barang,
            $inventory->merek,
            $inventory->nama_barang,
            $inventory->stok,
            $inventory->uom,
            $inventory->desc,
            $inventory->lokasi->nama_lokasi ?? '',
            $inventory->jabatan->nama_jabatan ?? ''
        ];
    }
}