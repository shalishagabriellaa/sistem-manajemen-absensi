<?php

namespace App\Http\Controllers;

use App\Models\Kontrak;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Style\Font;
use PhpOffice\PhpWord\Shared\Converter;
use App\Exports\KontrakExport;

class KontrakController extends Controller
{
    // ─────────────────────────────────────────
    //  INDEX
    // ─────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Kontrak::with('user');

        if ($request->nama) {
            $query->whereHas('user', fn($q) => $q->where('name', 'like', '%' . $request->nama . '%'));
        }
        if ($request->mulai) {
            $query->whereDate('tanggal', '>=', $request->mulai);
        }
        if ($request->akhir) {
            $query->whereDate('tanggal', '<=', $request->akhir);
        }

        $kontraks = $query->latest()->paginate(15)->withQueryString();

        return view('kontrak.index', [
            'title'    => 'Kontrak Kerja Pekerja',
            'kontraks' => $kontraks,
        ]);
    }

    // ─────────────────────────────────────────
    //  TAMBAH
    // ─────────────────────────────────────────
    public function tambah()
    {
        return view('kontrak.tambah', [
            'title' => 'Tambah Kontrak',
            'users' => User::orderBy('name')->get(),
        ]);
    }

    // ─────────────────────────────────────────
    //  EDIT
    // ─────────────────────────────────────────
    public function edit($id)
    {
        $kontrak = Kontrak::findOrFail($id);
        return view('kontrak.edit', [
            'title'   => 'Edit Kontrak',
            'kontrak' => $kontrak,
            'users'   => User::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'tanggal'       => 'required|date',
            'jenis_kontrak' => 'required',
            'tanggal_mulai' => 'required|date',
        ]);

        $kontrak = Kontrak::findOrFail($id);

        $data = $request->only([
            'user_id', 'tanggal', 'jenis_kontrak',
            'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'no_surat',
        ]);

        if ($request->hasFile('kontrak_file_path')) {
            // Hapus file lama jika ada
            if ($kontrak->kontrak_file_path) {
                \Storage::disk('public')->delete($kontrak->kontrak_file_path);
            }
            $file = $request->file('kontrak_file_path');
            $path = $file->store('kontrak_files', 'public');
            $data['kontrak_file_path'] = $path;
            $data['kontrak_file_name'] = $file->getClientOriginalName();
        }

        $kontrak->update($data);

        return redirect('/kontrak')->with('success', 'Kontrak berhasil diperbarui.');
    }

    // ─────────────────────────────────────────
    //  DELETE
    // ─────────────────────────────────────────
    public function delete($id)
    {
        $kontrak = Kontrak::findOrFail($id);
        if ($kontrak->kontrak_file_path) {
            \Storage::disk('public')->delete($kontrak->kontrak_file_path);
        }
        $kontrak->delete();

        return redirect('/kontrak')->with('success', 'Kontrak berhasil dihapus.');
    }

    // ─────────────────────────────────────────
    //  DRAFT — halaman preview sebelum generate
    // ─────────────────────────────────────────
    public function draft($id)
    {
        $kontrak = Kontrak::with('user.jabatan')->findOrFail($id);

        return view('kontrak.draft', [
            'title'   => 'Generate Dokumen Kontrak',
            'kontrak' => $kontrak,
        ]);
    }

    // ─────────────────────────────────────────
    //  GENERATE — download .docx
    // ─────────────────────────────────────────
    public function generate(Request $request, $id)
    {
        $kontrak  = Kontrak::with('user.jabatan')->findOrFail($id);
        $pegawai  = $kontrak->user;

        // Data yang bisa di-override dari form draft
        $noSurat         = $request->no_surat        ?? ($kontrak->no_surat ?? '_____________________________________________');
        $namaPerusahaan  = $request->nama_perusahaan ?? config('app.nama_perusahaan', 'PT. NAMA PERUSAHAAN');
        $namaDirektur    = $request->nama_direktur   ?? config('app.nama_direktur', 'NAMA DIREKTUR');
        $jabatanDirektur = $request->jabatan_direktur ?? config('app.jabatan_direktur', 'DIREKTUR');

        Carbon::setLocale('id');

        $tanggalSurat  = $kontrak->tanggal
            ? Carbon::parse($kontrak->tanggal)->translatedFormat('d F Y')
            : now()->translatedFormat('d F Y');
        
        $tanggalMulai  = $kontrak->tanggal_mulai
            ? Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y')
            : '-';
        
        $tanggalSelesai = $kontrak->tanggal_selesai
            ? Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y')
            : null;

        $lingkupKerja   = $kontrak->keterangan ?? 'Pekerjaan sesuai dengan jabatan yang tercantum.';
        $jabatanPegawai = $pegawai->jabatan->nama_jabatan ?? 'Karyawan';
        $namaPegawai    = $pegawai->name ?? '-';
        $alamatPegawai  = $pegawai->alamat ?? '-';
        $jenisKontrak   = $kontrak->jenis_kontrak ?? 'Perjanjian Kerja';

        // ── Build PhpWord ──────────────────────────
        $phpWord = new PhpWord();
        $phpWord->setDefaultFontName('Times New Roman');
        $phpWord->setDefaultFontSize(12);

        $section = $phpWord->addSection([
            'marginTop'    => Converter::cmToTwip(3),
            'marginBottom' => Converter::cmToTwip(3),
            'marginLeft'   => Converter::cmToTwip(3),
            'marginRight'  => Converter::cmToTwip(2.5),
        ]);

        // ── Helper Closures ────────────────────────
        $boldFont  = ['bold' => true, 'size' => 12];
        $normalFont = ['size' => 12];
        $centerPara = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER, 'spaceAfter' => 0];
        $justifyPara = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 120];
        $indentPara  = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 120, 'indentation' => ['left' => 720]];

        // ── JUDUL ──────────────────────────────────
        $section->addParagraph('', [], ['spaceAfter' => 60]);

        $title = $section->addTextRun($centerPara);
        $title->addText('SURAT PERJANJIAN KERJA', ['bold' => true, 'size' => 14]);

        $nomor = $section->addTextRun($centerPara);
        $nomor->addText('Nomor : ', $boldFont);
        $nomor->addText($noSurat, $normalFont);

        $section->addParagraph('', [], ['spaceAfter' => 60]);

        $tentang = $section->addTextRun($centerPara);
        $tentang->addText('TENTANG ', $boldFont);
        $tentang->addText(strtoupper($jenisKontrak), $boldFont);

        $pihak = $section->addTextRun($centerPara);
        $pihak->addText('ANTARA ' . strtoupper($namaPerusahaan) . ' DENGAN ' . strtoupper($namaPegawai), $boldFont);

        $section->addParagraph('', [], ['spaceAfter' => 120]);

        // ── PEMBUKA ────────────────────────────────
        $section->addText(
            'Pada hari ini, ' . $tanggalSurat . ', kami yang bertanda tangan di bawah ini, masing-masing:',
            $normalFont,
            $justifyPara
        );

        $section->addParagraph('', [], ['spaceAfter' => 60]);

        // Pihak Pertama
        $this->addPihakRow($section, 'Nama',    $namaDirektur,   $boldFont, $normalFont);
        $this->addPihakRow($section, 'Jabatan', $jabatanDirektur, $boldFont, $normalFont);
        $this->addPihakRow($section, 'Instansi', $namaPerusahaan, $boldFont, $normalFont);

        $section->addText(
            'Bertindak untuk dan atas nama ' . $namaPerusahaan . ', selanjutnya disebut sebagai PIHAK PERTAMA',
            $normalFont, $justifyPara
        );

        $section->addParagraph('', [], ['spaceAfter' => 60]);

        // Pihak Kedua
        $this->addPihakRow($section, 'Nama',    $namaPegawai,    $boldFont, $normalFont);
        $this->addPihakRow($section, 'Jabatan', $jabatanPegawai, $boldFont, $normalFont);
        $this->addPihakRow($section, 'Alamat',  $alamatPegawai,  $boldFont, $normalFont);

        $section->addText(
            'Bertindak untuk dan atas nama PEKERJA, selanjutnya disebut sebagai PIHAK KEDUA.',
            $normalFont, $justifyPara
        );

        $section->addText(
            'Berdasarkan kesepakatan kedua belah pihak, dengan ini Pihak Pertama dan Pihak Kedua telah sepakat untuk mengadakan perjanjian kerja yang mengikat dengan ketentuan sebagai berikut:',
            $normalFont, $justifyPara
        );

        // ── PASAL 1 ────────────────────────────────
        $this->addPasal($section, 'PASAL 1', 'PEMBERIAN TUGAS', $centerPara, $boldFont);

        $section->addText(
            '1. Pihak Pertama memberikan tugas kepada Pihak Kedua dan Pihak Kedua menerima tugas Pihak Pertama serta mengikatkan diri sebagai pelaksana pekerjaan sebagaimana diatur dalam surat perjanjian ini.',
            $normalFont, $indentPara
        );
        $section->addText(
            '2. Pihak Kedua diwajibkan melaksanakan Surat Perjanjian ini dengan sebaik-baiknya sesuai ruang lingkup yang disepakati.',
            $normalFont, $indentPara
        );

        // ── PASAL 2 ────────────────────────────────
        $this->addPasal($section, 'PASAL 2', 'LINGKUP PEKERJAAN', $centerPara, $boldFont);
        $section->addText(
            'Perjanjian kerja ini meliputi segala kegiatan: ' . $lingkupKerja,
            $normalFont, $justifyPara
        );

        // ── PASAL 3 ────────────────────────────────
        $this->addPasal($section, 'PASAL 3', 'JANGKA WAKTU PELAKSANAAN', $centerPara, $boldFont);

        if ($tanggalSelesai) {
            $section->addText(
                'Perjanjian kerja ini berlaku terhitung mulai tanggal ' . $tanggalMulai . ' dan berakhir pada tanggal ' . $tanggalSelesai . '.',
                $normalFont, $justifyPara
            );
        } else {
            $section->addText(
                'Perjanjian kerja ini berlaku terhitung mulai tanggal ' . $tanggalMulai . ' dan tidak memiliki batas waktu tertentu (Perjanjian Kerja Waktu Tidak Tertentu / PKWTT).',
                $normalFont, $justifyPara
            );
        }

        // ── PASAL 4 ────────────────────────────────
        $this->addPasal($section, 'PASAL 4', 'HAK DAN KEWAJIBAN', $centerPara, $boldFont);
        $section->addText('1. Pihak Kedua berhak mendapatkan imbalan jasa sebagaimana yang disepakati bersama.', $normalFont, $indentPara);
        $section->addText('2. Pihak Kedua wajib menaati peraturan dan tata tertib yang berlaku di lingkungan Pihak Pertama.', $normalFont, $indentPara);
        $section->addText('3. Pihak Pertama wajib memberikan fasilitas dan sarana yang dibutuhkan untuk pelaksanaan pekerjaan.', $normalFont, $indentPara);

        // ── PASAL 5 ────────────────────────────────
        $this->addPasal($section, 'PASAL 5', 'KEADAAN MEMAKSA (FORCE MAJEURE)', $centerPara, $boldFont);
        $section->addText(
            'Pihak Kedua dibebaskan dari tanggung jawab atas kerugian dan keterlambatan penyelesaian pekerjaan apabila terjadi keadaan memaksa (Force Majeure) seperti bencana alam, wabah, dan peristiwa di luar kendali manusia, dengan catatan harus dilaporkan dalam 2x24 jam.',
            $normalFont, $justifyPara
        );

        // ── PASAL 6 ────────────────────────────────
        $this->addPasal($section, 'PASAL 6', 'PENYELESAIAN PERSELISIHAN', $centerPara, $boldFont);
        $section->addText(
            'Apabila terjadi perselisihan, kedua belah pihak sepakat untuk menyelesaikannya secara musyawarah mufakat. Jika tidak tercapai kesepakatan, akan diselesaikan melalui jalur hukum yang berlaku.',
            $normalFont, $justifyPara
        );

        // ── PASAL 7 ────────────────────────────────
        $this->addPasal($section, 'PASAL 7', 'PENUTUP', $centerPara, $boldFont);
        $section->addText('1. Kontrak ini dianggap sah setelah ditandatangani oleh kedua belah pihak di atas meterai.', $normalFont, $indentPara);
        $section->addText('2. Segala sesuatu yang belum diatur dalam surat perjanjian ini akan diatur dalam addendum/lampiran yang merupakan satu kesatuan dengan perjanjian ini.', $normalFont, $indentPara);
        $section->addText('3. Perjanjian ini dibuat rangkap 2 (dua) dan masing-masing pihak memegang 1 (satu) eksemplar asli.', $normalFont, $indentPara);

        $section->addParagraph('', [], ['spaceAfter' => 240]);

        // ── TTD ───────────────────────────────────
        $ttdPara = ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH, 'spaceAfter' => 0];
        $ttdTable = $section->addTable(['borderSize' => 0, 'borderColor' => 'FFFFFF']);
        $ttdTable->addRow();

        $cell1 = $ttdTable->addCell(4500);
        $cell1->addText('Ditandatangani di _______________', $normalFont, $ttdPara);
        $cell1->addText('Pada Tanggal : ' . $tanggalSurat, $normalFont, $ttdPara);
        $cell1->addParagraph('');
        $cell1->addText('Pihak Pertama', $boldFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $cell1->addParagraph(''); $cell1->addParagraph(''); $cell1->addParagraph('');
        $cell1->addText(strtoupper($namaDirektur), $boldFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $cell1->addText($jabatanDirektur, $normalFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        $cell2 = $ttdTable->addCell(500);
        $cell2->addText('');

        $cell3 = $ttdTable->addCell(4500);
        $cell3->addText('', $normalFont, $ttdPara);
        $cell3->addText('', $normalFont, $ttdPara);
        $cell3->addParagraph('');
        $cell3->addText('Pihak Kedua', $boldFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $cell3->addParagraph(''); $cell3->addParagraph(''); $cell3->addParagraph('');
        $cell3->addText(strtoupper($namaPegawai), $boldFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        $cell3->addText($jabatanPegawai, $normalFont, ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);

        // ── Output ────────────────────────────────
        $fileName = 'Kontrak_' . str_replace(' ', '_', $namaPegawai) . '_' . date('Ymd') . '.docx';

        $writer = IOFactory::createWriter($phpWord, 'Word2007');
        $tempFile = tempnam(sys_get_temp_dir(), 'kontrak_');
        $writer->save($tempFile);

        return response()->download($tempFile, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ])->deleteFileAfterSend(true);
    }

    // ── Helpers ───────────────────────────────
    private function addPihakRow($section, $label, $value, $boldFont, $normalFont)
    {
        $run = $section->addTextRun([
            'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::BOTH,
            'spaceAfter' => 60,
            'indentation' => ['left' => 720],
        ]);
        $run->addText('- ' . $label, $boldFont);
        $run->addText(' : ', $normalFont);
        $run->addText($value, $boldFont);
    }

    private function addPasal($section, $pasal, $judul, $centerPara, $boldFont)
    {
        $section->addParagraph('', [], ['spaceAfter' => 120]);
        $section->addText($pasal, $boldFont, $centerPara);
        $section->addText($judul, $boldFont, $centerPara);
        $section->addParagraph('', [], ['spaceAfter' => 60]);
    }
    
    public function export()
    {
        return (new KontrakExport($_GET))->download('List Kontrak.xlsx');
    }

    public function store(Request $request)
    {
        $rules = [
            'user_id' => 'required',
            'tanggal' => 'required',
            'jenis_kontrak' => 'required',
            'tanggal_mulai' => 'required',
            'keterangan' => 'required',
            'kontrak_file_path' => 'nullable',
        ];

        if ($request->jenis_kontrak !== 'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)') {
            $rules['tanggal_selesai'] = 'required';
        }

        $validated = $request->validate($rules);

        if ($request->file('kontrak_file_path')) {
            $validated['kontrak_file_path'] = $request->file('kontrak_file_path')->store('kontrak_file_path');
            $validated['kontrak_file_name'] = $request->file('kontrak_file_path')->getClientOriginalName();
        }

        $user = User::find($request->user_id);
        $validated['masa_berlaku_sebelumnya'] = $user->masa_berlaku;

        $kontrak = Kontrak::create($validated);

        $user->update([
            'masa_berlaku' => $kontrak->tanggal_selesai
        ]);

        return redirect('/kontrak')->with('success', 'Data Berhasil Disimpan');
    }
}