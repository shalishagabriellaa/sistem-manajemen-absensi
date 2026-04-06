<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Lokasi;
use App\Models\Jabatan;
use App\Models\Counter;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class InventoryImport implements ToModel, WithHeadingRow, SkipsOnError
{
    use SkipsErrors;

    private $lokasi;
    private $jabatan;

    public function __construct()
    {
        $this->lokasi  = Lokasi::pluck('id', 'nama_lokasi')->toArray();
        $this->jabatan = Jabatan::pluck('id', 'nama_jabatan')->toArray();
    }

    /**
     * Header ada di baris ke-3 pada template Excel Metech
     * Baris 1 = "Inventory Metech", Baris 2 = kosong, Baris 3 = header kolom
     */
    public function headingRow(): int
    {
        return 3;
    }

    public function model(array $row)
    {
        // Lewati baris kosong
        if (empty($row['nama_barang'])) {
            return null;
        }

        // Generate kode_barang otomatis jika dikosongkan
        $kode_barang = !empty($row['kode_barang']) ? $row['kode_barang'] : null;
        if (empty($kode_barang)) {
            $counter = Counter::where('name', 'Inventory')->first();
            if ($counter) {
                $counter->update(['counter' => $counter->counter + 1]);
                $next_number = str_pad($counter->counter, 6, '0', STR_PAD_LEFT);
                $kode_barang = $counter->text . '/' . $next_number;
            }
        }

        // Kolom "Lokasi"          → heading key otomatis: "lokasi"
        // Kolom "Divisi / Jabatan"→ heading key otomatis: "divisi_jabatan"
        $lokasi_id  = $this->resolveId($this->lokasi,  $row['lokasi']          ?? null);
        $jabatan_id = $this->resolveId($this->jabatan, $row['divisi_jabatan']  ?? null);

        return new Inventory([
            'kode_barang'  => $kode_barang,
            'jenis_barang' => $row['jenis_barang'] ?? null,
            'merek'        => $row['merek']        ?? null,
            'nama_barang'  => $row['nama_barang'],
            'stok'         => $row['stok']         ?? 0,
            'uom'          => $row['uom']          ?? null,
            'desc'         => $row['description']  ?? null,
            'lokasi_id'    => $lokasi_id,
            'jabatan_id' => auth()->user()->jabatan_id,
            'user_id' => auth()->id(),
        ]);
    }

    /**
     * Cari id dari map berdasarkan nama (case-insensitive)
     */
    private function resolveId(array $map, ?string $nama): ?int
    {
        if (empty($nama)) return null;
        $nama = trim($nama);

        if (isset($map[$nama])) return $map[$nama];

        foreach ($map as $key => $id) {
            if (strtolower($key) === strtolower($nama)) return $id;
        }

        return null;
    }
}
