<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PegawaiExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithStyles, WithEvents
{
    use Exportable;

    protected $filters;

    // ── Warna palette Metech ──────────────────────────────────
    const NAVY      = '1B2B5E';
    const BLUE      = '2563EB';
    const LBLUE     = 'DBEAFE';
    const WHITE     = 'FFFFFF';
    const GREY_ROW  = 'F0F4FF';   // zebra ganjil
    const GREY_EVEN = 'FFFFFF';   // zebra genap
    const DIVIDER   = 'FEF9C3';   // baris section (kuning muda)
    const DIV_FONT  = '92400E';   // font section
    const SLATE     = 'F1F5F9';

    public function __construct($filters = [])
    {
        $this->filters = $filters;
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                request()->merge([$key => $value]);
            }
        }
    }

    // ── Headings (baris 3, setelah 2 baris banner) ───────────
    public function headings(): array
    {
        return [
            // Baris 1 & 2 diisi via AfterSheet event (banner Metech)
            // Baris 3 = heading kolom
            'No.',
            'Nama Pegawai',
            'Username',
            'Email',
            'Nomor Handphone',
            'Lokasi Kantor',
            'Divisi',
            'Tanggal Lahir',
            'Gender',
            'Tanggal Masuk',
            'Status Pernikahan',
            'KTP',
            'Kartu Keluarga',
            'BPJS Kesehatan',
            'BPJS Ketenagakerjaan',
            'NPWP',
            'SIM',
            'No. PKWT',
            'No. Kontrak',
            'Tgl Mulai PKWT',
            'Tgl Berakhir PKWT',
            'No. Rekening',
            'Nama Pemilik Rekening',
            'Alamat',
            '─── CUTI & IZIN ───',
            'Cuti',
            'Izin Masuk',
            'Izin Telat',
            'Izin Pulang Cepat',
            '─── PENAMBAHAN GAJI ───',
            'Gaji Pokok',
            'Makan & Transport',
            'Lembur',
            '100% Kehadiran',
            'THR',
            'Bonus Pribadi',
            'Bonus Team',
            'Bonus Jackpot',
            '─── PENGURANGAN GAJI ───',
            'Izin',
            'Terlambat',
            'Mangkir',
            'Kasbon',
        ];
    }

    // ── Data per baris ────────────────────────────────────────
    protected static $rowCounter = 0;

    public function map($model): array
    {
        self::$rowCounter++;

        return [
            self::$rowCounter,
            $model->name                    ?? '-',
            $model->username                ?? '-',
            $model->email                   ?? '-',
            $model->telepon                 ?? '-',
            $model->Lokasi->nama_lokasi     ?? '-',
            $model->Jabatan->nama_jabatan   ?? '-',
            $model->tgl_lahir               ?? '-',
            $model->gender                  ?? '-',
            $model->tgl_join                ?? '-',
            $model->status_nikah            ?? '-',
            $model->ktp                     ?? '-',
            $model->kartu_keluarga          ?? '-',
            $model->bpjs_kesehatan          ?? '-',
            $model->bpjs_ketenagakerjaan    ?? '-',
            $model->npwp                    ?? '-',
            $model->sim                     ?? '-',
            $model->no_pkwt                 ?? '-',
            $model->no_kontrak              ?? '-',
            $model->tanggal_mulai_pkwt      ?? '-',
            $model->tanggal_berakhir_pkwt   ?? '-',
            $model->rekening                ?? '-',
            $model->nama_rekening           ?? '-',
            $model->alamat                  ?? '-',
            '▼ CUTI & IZIN',
            ($model->izin_cuti       ?? 0) . ' x',
            ($model->izin_lainnya    ?? 0) . ' x',
            ($model->izin_telat      ?? 0) . ' x',
            ($model->izin_pulang_cepat ?? 0) . ' x',
            '▼ PENAMBAHAN GAJI',
            'Rp ' . number_format($model->gaji_pokok      ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->makan_transport ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->lembur          ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->kehadiran       ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->thr             ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->bonus_pribadi   ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->bonus_team      ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->bonus_jackpot   ?? 0, 0, ',', '.'),
            '▼ PENGURANGAN GAJI',
            'Rp ' . number_format($model->izin            ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->terlambat       ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->mangkir         ?? 0, 0, ',', '.'),
            'Rp ' . number_format($model->saldo_kasbon    ?? 0, 0, ',', '.'),
        ];
    }

    // ── Query (sama seperti semula) ───────────────────────────
    public function query()
    {
        $search     = request()->input('search');
        $jabatan_id = request()->input('jabatan_id');

        return User::when($jabatan_id, fn($q) => $q->where('jabatan_id', $jabatan_id))
            ->when($search, function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('name',     'LIKE', "%$search%")
                        ->orWhere('email',    'LIKE', "%$search%")
                        ->orWhere('telepon',  'LIKE', "%$search%")
                        ->orWhere('username', 'LIKE', "%$search%")
                        ->orWhereHas('Jabatan', fn($j) => $j->where('nama_jabatan', 'LIKE', "%$search%"));
                });
            })
            ->orderBy('name', 'ASC');
    }

    // ── Styles dasar (diperkuat via AfterSheet) ───────────────
    public function styles(Worksheet $sheet)
    {
        // Heading row (row 1 setelah prepend banner di event)
        // Styling detail dikerjakan di registerEvents()
        return [];
    }

    // ── Event AfterSheet: banner + warna + border ─────────────
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $highestCol = $sheet->getHighestColumn();   // misal AQ
                $highestRow = $sheet->getHighestRow();

                // ── 1. Sisipkan 2 baris banner di atas ──────────
                $sheet->insertNewRowBefore(1, 2);
                $highestRow += 2;

                // ── 2. Banner baris 1: Judul Metech ─────────────
                $sheet->mergeCells("A1:{$highestCol}1");
                $sheet->setCellValue('A1', '🏢  DATA PEGAWAI  —  PT Metech Indonesia');
                $sheet->getStyle('A1')->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 14, 'color' => ['rgb' => self::WHITE], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::NAVY]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(1)->setRowHeight(36);

                // ── 3. Banner baris 2: Sub-judul ─────────────────
                $sheet->mergeCells("A2:{$highestCol}2");
                $sheet->setCellValue('A2', 'Dicetak: ' . now()->translatedFormat('d F Y') . '   |   Total Pegawai: ' . ($highestRow - 3));
                $sheet->getStyle('A2')->applyFromArray([
                    'font'      => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '1E3A5F'], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::LBLUE]],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                ]);
                $sheet->getRowDimension(2)->setRowHeight(22);

                // ── 4. Style baris heading (row 3) ───────────────
                $sheet->getStyle("A3:{$highestCol}3")->applyFromArray([
                    'font'      => ['bold' => true, 'size' => 10, 'color' => ['rgb' => self::WHITE], 'name' => 'Arial'],
                    'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::NAVY]],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical'   => Alignment::VERTICAL_CENTER,
                        'wrapText'   => true,
                    ],
                    'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'FFFFFF']]],
                ]);
                $sheet->getRowDimension(3)->setRowHeight(30);

                // ── 5. Zebra stripe + warna section divider ──────
                // Kolom section divider (Y, AD, AJ = indeks 25, 30, 39 dalam headings → row offset +3)
                // Posisi di heading array (0-based): 24=CUTI, 29=PENAMBAHAN, 38=PENGURANGAN
                // Di sheet kolom = huruf ke-25, 30, 39
                $sectionCols = ['Y', 'AD', 'AJ']; // Kolom divider (sesuaikan jika kolom bergeser)

                for ($row = 4; $row <= $highestRow; $row++) {
                    // Cek apakah baris ini adalah section divider
                    $cellVal = $sheet->getCell("Y{$row}")->getValue();
                    $isSection = (
                        str_contains((string)$cellVal, 'CUTI') ||
                        str_contains((string)$cellVal, 'PENAMBAHAN') ||
                        str_contains((string)$cellVal, 'PENGURANGAN')
                    );

                    if ($isSection) {
                        // Section divider row: background kuning muda
                        $sheet->getStyle("A{$row}:{$highestCol}{$row}")->applyFromArray([
                            'font' => ['bold' => true, 'italic' => true, 'color' => ['rgb' => self::DIV_FONT], 'size' => 9],
                            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => self::DIVIDER]],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        ]);
                    } else {
                        // Zebra stripe
                        $bg = ($row % 2 === 0) ? self::GREY_EVEN : self::GREY_ROW;
                        $sheet->getStyle("A{$row}:{$highestCol}{$row}")->applyFromArray([
                            'font'      => ['size' => 9, 'name' => 'Arial', 'color' => ['rgb' => '1E293B']],
                            'fill'      => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => $bg]],
                            'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true],
                        ]);
                    }

                    $sheet->getRowDimension($row)->setRowHeight(18);
                }

                // ── 6. Border seluruh tabel ───────────────────────
                $sheet->getStyle("A3:{$highestCol}{$highestRow}")->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['rgb' => 'CBD5E1']],
                        'outline'    => ['borderStyle' => Border::BORDER_MEDIUM, 'color' => ['rgb' => self::NAVY]],
                    ],
                ]);

                // ── 7. Kolom No. lebih kecil ──────────────────────
                $sheet->getColumnDimension('A')->setWidth(5);

                // ── 8. Freeze pane di baris data pertama ──────────
                $sheet->freezePane('B4');

                // ── 9. Warna tab sheet ────────────────────────────
                $sheet->getTabColor()->setRGB(self::NAVY);
            },
        ];
    }

    public function columnFormats(): array
    {
        return [];
    }
}