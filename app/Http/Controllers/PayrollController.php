<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payroll;
use App\Models\StatusPtkp;
use Illuminate\Http\Request;
use App\Models\MappingShift;
use Carbon\Carbon;
use App\Notifications\PayrollGenerated;
use Illuminate\Support\Facades\Notification;

class PayrollController extends Controller
{
public function generate()
{
    return view('payroll.generate', [
        'title'     => 'Generate Slip Gaji',
        'data_user' => User::select('id', 'name')->orderBy('name')->get(),
    ]);
}

public function generateProses(Request $request)
{
    $request->validate([
        'user_id'       => 'required|exists:users,id',
        'tanggal_mulai' => 'required|date',
        'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
        'bayar_kasbon'  => 'nullable|integer|min:0',
        'total_thr'     => 'nullable|integer|min:0',
        'loss'          => 'nullable|integer|min:0',
    ]);

    $user       = User::find($request->user_id);
    $tglMulai   = Carbon::parse($request->tanggal_mulai);
    $tglAkhir   = Carbon::parse($request->tanggal_akhir);
    $bulan      = $tglMulai->month;
    $tahun      = $tglMulai->year;

    // ===============================
    // ABSENSI DARI mapping_shifts
    // ===============================

    $absensi = MappingShift::where('user_id', $user->id)
        ->whereBetween('tanggal', [$tglMulai->toDateString(), $tglAkhir->toDateString()])
        ->get();

    $hariMasuk = $absensi
    ->whereNotNull('jam_absen')
    ->count();

    $hariMangkir = $absensi
        ->whereNull('jam_absen')
        ->where('shift_id','!=',1)
        ->count();

    $jumlahTerlambat = $absensi
        ->whereNotNull('jam_absen')
        ->filter(fn($a) => (int)$a->telat > 0)
        ->count();

    $jumlahIzin = 0;

    $totalHariKerja = $absensi
        ->where('shift_id','!=',1)
        ->count();
    $hariEfektif = $totalHariKerja - $jumlahIzin;

    $persen = $hariEfektif > 0
        ? round(($hariMasuk / $hariEfektif) * 100, 2)
        : 0;

    // ===============================
    // LEMBUR
    // ===============================

    $totalJamLembur = (int) \App\Models\Lembur::where('user_id', $user->id)
        ->where('status', 'approved')
        ->whereBetween('tanggal', [$tglMulai->toDateString(), $tglAkhir->toDateString()])
        ->sum('total_lembur');

    // ===============================
    // REIMBURSEMENT
    // ===============================

    $totalReimburse = \App\Models\Reimbursement::where('user_id', $user->id)
        ->where('status', 'Approved')
        ->whereBetween('tanggal', [$tglMulai->toDateString(), $tglAkhir->toDateString()])
        ->sum('total');

    // ===============================
    // KOMPONEN GAJI USER
    // ===============================

    $gajiPokok      = $user->gaji_pokok ?? 0;
    $uangMakan      = $user->tunjangan_makan ?? 0;
    $uangTransport  = $user->tunjangan_transport ?? 0;

    $totalMakan     = $uangMakan * $hariMasuk;
    $totalTransport = $uangTransport * $hariMasuk;

    $uangLembur     = $user->lembur ?? 0;
    $totalLembur    = $uangLembur * $totalJamLembur;

    $uangKehadiran  = $user->kehadiran ?? 0;
    $totalKehadiran = ($persen == 100) ? $uangKehadiran : 0;

    $bonusPribadi   = $user->bonus_pribadi ?? 0;
    $bonusTeam      = $user->bonus_team ?? 0;
    $bonusJackpot   = $user->bonus_jackpot ?? 0;

    // ===============================
    // BPJS
    // ===============================

    $tunjBpjsKesehatan       = $user->tunjangan_bpjs_kesehatan ?? 0;
    $tunjBpjsKetenagakerjaan = $user->tunjangan_bpjs_ketenagakerjaan ?? 0;

    $potBpjsKesehatan        = $user->potongan_bpjs_kesehatan ?? 0;
    $potBpjsKetenagakerjaan  = $user->potongan_bpjs_ketenagakerjaan ?? 0;

    // ===============================
    // POTONGAN ABSENSI
    // ===============================

    $uangMangkir    = $user->mangkir ?? 0;
    $totalMangkir   = $uangMangkir * $hariMangkir;

    $uangIzin       = $user->izin ?? 0;
    $totalIzin      = $uangIzin * $jumlahIzin;

    $uangTerlambat  = $user->terlambat ?? 0;
    $totalTerlambat = $uangTerlambat * $jumlahTerlambat;

    // ===============================
    // KASBON
    // ===============================

    $saldoKasbon    = $user->saldo_kasbon ?? 0;

    $bayarKasbon = min((int)($request->bayar_kasbon ?? $saldoKasbon), $saldoKasbon);

    // ===============================
    // THR DAN LOSS
    // ===============================

    $totalThr    = (int)($request->total_thr ?? 0);
    $uangThr     = $user->thr ?? 0;
    $loss        = (int)($request->loss ?? 0);

    // ===============================
    // TOTAL PENDAPATAN
    // ===============================

    $totalPendapatan =
        $gajiPokok +
        $totalReimburse +
        $totalTransport +
        $totalMakan +
        $tunjBpjsKesehatan +
        $tunjBpjsKetenagakerjaan +
        $totalLembur +
        $totalKehadiran +
        $bonusPribadi +
        $bonusTeam +
        $bonusJackpot +
        $totalThr;

    // ===============================
    // TOTAL POTONGAN
    // ===============================

    $totalPotongan =
        $potBpjsKesehatan +
        $potBpjsKetenagakerjaan +
        $totalTerlambat +
        $totalMangkir +
        $totalIzin +
        $bayarKasbon +
        $loss;

    $grandTotal = max(0, $totalPendapatan - $totalPotongan);

    // ===============================
    // NOMOR SLIP
    // ===============================

    $counter = \App\Models\Counter::where('name', 'Gaji')->first();
    $noUrut  = str_pad(($counter->counter ?? 0) + 1, 4, '0', STR_PAD_LEFT);

    $noGaji  = 'GJ/' . $tahun . '/' . str_pad($bulan, 2, '0', STR_PAD_LEFT) . '/' . $noUrut;

    if ($counter) $counter->increment('counter');

    // ===============================
    // SIMPAN PAYROLL
    // ===============================

    $payroll = Payroll::create([
        'user_id'      => $user->id,
        'tanggal_mulai'=> $tglMulai->toDateString(),
        'tanggal_akhir'=> $tglAkhir->toDateString(),
        'bulan'        => $bulan,
        'tahun'        => $tahun,
        'persentase_kehadiran' => $persen,
        'no_gaji'      => $noGaji,

        'gaji_pokok'   => $gajiPokok,
        'total_reimbursement' => $totalReimburse,

        'jumlah_tunjangan_transport' => $hariMasuk,
        'uang_tunjangan_transport'   => $uangTransport,
        'total_tunjangan_transport'  => $totalTransport,

        'jumlah_tunjangan_makan' => $hariMasuk,
        'uang_tunjangan_makan'   => $uangMakan,
        'total_tunjangan_makan'  => $totalMakan,

        'total_tunjangan_bpjs_kesehatan' => $tunjBpjsKesehatan,
        'total_tunjangan_bpjs_ketenagakerjaan' => $tunjBpjsKetenagakerjaan,

        'total_potongan_bpjs_kesehatan' => $potBpjsKesehatan,
        'total_potongan_bpjs_ketenagakerjaan' => $potBpjsKetenagakerjaan,

        'jumlah_mangkir' => $hariMangkir,
        'uang_mangkir'   => $uangMangkir,
        'total_mangkir'  => $totalMangkir,

        'jumlah_lembur'  => $totalJamLembur,
        'uang_lembur'    => $uangLembur,
        'total_lembur'   => $totalLembur,

        'jumlah_izin'    => $jumlahIzin,
        'uang_izin'      => $uangIzin,
        'total_izin'     => $totalIzin,

        'bonus_pribadi'  => $bonusPribadi,
        'bonus_team'     => $bonusTeam,
        'bonus_jackpot'  => $bonusJackpot,

        'jumlah_terlambat' => $jumlahTerlambat,
        'uang_terlambat'   => $uangTerlambat,
        'total_terlambat'  => $totalTerlambat,

        'jumlah_kehadiran' => $persen == 100 ? 1 : 0,
        'uang_kehadiran'   => $uangKehadiran,
        'total_kehadiran'  => $totalKehadiran,

        'saldo_kasbon' => $saldoKasbon,
        'bayar_kasbon' => $bayarKasbon,

        'jumlah_thr' => $totalThr > 0 ? 1 : 0,
        'uang_thr'   => $uangThr,
        'total_thr'  => $totalThr,

        'loss' => $loss,

        'total_penjumlahan' => $totalPendapatan,
        'total_pengurangan' => $totalPotongan,
        'grand_total'       => $grandTotal,
    ]);

    $user->notify(new \App\Notifications\PayrollGenerated($payroll));

    if ($bayarKasbon > 0) {
        $user->update([
            'saldo_kasbon' => max(0, $saldoKasbon - $bayarKasbon)
        ]);
    }

    return redirect('/payroll/' . $payroll->id . '/download')
        ->with('success', 'Slip gaji berhasil digenerate!');
}
    public function index()
    {
        $bulan = request()->input('bulan');
        $tahun = request()->input('tahun');
        if (auth()->user()->is_admin == 'admin') {
            $data = Payroll::when($bulan, function ($query) use ($bulan) {
                                return $query->where('bulan', $bulan);
                            })
                            ->when($tahun, function ($query) use ($tahun) {
                                return $query->where('tahun', $tahun);
                            })
                            ->orderBy('no_gaji', 'DESC');

            return view('payroll.index', [
                'title' => 'Payroll',
                'data' => $data->paginate(10)->withQueryString()
            ]);
        } else {
            $data = Payroll::where('user_id', auth()->user()->id)
                            ->when($bulan, function ($query) use ($bulan) {
                                return $query->where('bulan', $bulan);
                            })
                            ->when($tahun, function ($query) use ($tahun) {
                                return $query->where('tahun', $tahun);
                            })
                            ->orderBy('no_gaji', 'DESC');

            return view('payroll.indexuser', [
                'title' => 'Data Penggajian Karyawan',
                'data' => $data->paginate(10)->withQueryString()
            ]);
        }
    }

    public function tambah()
    {
        return view('payroll.tambah', [
            'title' => 'Tambah Data Penggajian Karyawan',
            'data_user' => User::select('id', 'name')->orderBy('name', 'ASC')->get(),
        ]);
    }

    /**
     * AJAX: Ambil data kompensasi pegawai berdasarkan user_id
     * Route: GET /payroll/get-user-data/{id}
     */
    public function getUserData($id)
    {
        $user = User::select([
            'id', 'name', 'rekening', 'nama_rekening', 'tgl_join', 'izin_cuti',
            'gaji_pokok', 'tunjangan_makan', 'tunjangan_transport',
            'tunjangan_bpjs_kesehatan', 'tunjangan_bpjs_ketenagakerjaan',
            'lembur', 'kehadiran', 'thr',
            'bonus_pribadi', 'bonus_team',
            'izin', 'terlambat', 'mangkir', 'saldo_kasbon',
            'potongan_bpjs_kesehatan', 'potongan_bpjs_ketenagakerjaan',
        ])->find($id);

        if (!$user) {
            return response()->json(null, 404);
        }

        return response()->json($user);
    }

    public function tambahProses(Request $request)
    {
        $validated = $request->validate([
            'user_id'                          => 'required',
            'bulan'                            => 'required',
            'tahun'                            => 'required',
            'tanggal_mulai'                    => 'required|date',
            'tanggal_akhir'                    => 'required|date',
            'persentase_kehadiran'             => 'required',
            'no_gaji'                          => 'required',
            'gaji_pokok'                       => 'nullable',
            'total_reimbursement'              => 'nullable',
            'jumlah_tunjangan_transport'       => 'nullable',
            'uang_tunjangan_transport'         => 'nullable',
            'total_tunjangan_transport'        => 'nullable',
            'jumlah_tunjangan_makan'           => 'nullable',
            'uang_tunjangan_makan'             => 'nullable',
            'total_tunjangan_makan'            => 'nullable',
            'total_tunjangan_bpjs_kesehatan'   => 'nullable',
            'total_tunjangan_bpjs_ketenagakerjaan' => 'nullable',
            'total_potongan_bpjs_kesehatan'    => 'nullable',
            'total_potongan_bpjs_ketenagakerjaan'  => 'nullable',
            'jumlah_mangkir'                   => 'nullable',
            'uang_mangkir'                     => 'nullable',
            'total_mangkir'                    => 'nullable',
            'jumlah_lembur'                    => 'nullable',
            'uang_lembur'                      => 'nullable',
            'total_lembur'                     => 'nullable',
            'jumlah_izin'                      => 'nullable',
            'uang_izin'                        => 'nullable',
            'total_izin'                       => 'nullable',
            'bonus_pribadi'                    => 'nullable',
            'bonus_team'                       => 'nullable',
            'bonus_jackpot'                    => 'nullable',
            'jumlah_terlambat'                 => 'nullable',
            'uang_terlambat'                   => 'nullable',
            'total_terlambat'                  => 'nullable',
            'jumlah_kehadiran'                 => 'nullable',
            'uang_kehadiran'                   => 'nullable',
            'total_kehadiran'                  => 'nullable',
            'saldo_kasbon'                     => 'nullable',
            'bayar_kasbon'                     => 'nullable',
            'jumlah_thr'                       => 'nullable',
            'uang_thr'                         => 'nullable',
            'total_thr'                        => 'nullable',
            'loss'                             => 'nullable',
            'total_penjumlahan'                => 'nullable',
            'total_pengurangan'                => 'nullable',
            'grand_total'                      => 'nullable',
        ]);

        $moneyFields = [
            'gaji_pokok','total_reimbursement',
            'uang_tunjangan_transport','total_tunjangan_transport',
            'uang_tunjangan_makan','total_tunjangan_makan',
            'total_tunjangan_bpjs_kesehatan','total_tunjangan_bpjs_ketenagakerjaan',
            'total_potongan_bpjs_kesehatan','total_potongan_bpjs_ketenagakerjaan',
            'uang_mangkir','total_mangkir',
            'uang_lembur','total_lembur',
            'uang_izin','total_izin',
            'bonus_pribadi','bonus_team','bonus_jackpot',
            'uang_terlambat','total_terlambat',
            'uang_kehadiran','total_kehadiran',
            'saldo_kasbon','bayar_kasbon',
            'uang_thr','total_thr',
            'loss',
            'total_penjumlahan','total_pengurangan','grand_total',
        ];

        foreach ($moneyFields as $field) {
            $raw = $validated[$field] ?? '0';
                // Hapus titik (pemisah ribuan) DAN koma, lalu cast ke integer
                $validated[$field] = (int) str_replace(['.', ','], '', $raw);
        }

        $intFields = [
            'jumlah_tunjangan_transport','jumlah_tunjangan_makan',
            'jumlah_mangkir','jumlah_lembur','jumlah_izin',
            'jumlah_terlambat','jumlah_kehadiran','jumlah_thr',
        ];
        foreach ($intFields as $field) {
            $validated[$field] = isset($validated[$field]) ? (int)$validated[$field] : 0;
        }

        // Hitung ulang di server untuk memastikan akurasi
        $validated['total_penjumlahan'] =
            $validated['gaji_pokok'] +
            $validated['total_reimbursement'] +
            $validated['total_tunjangan_transport'] +
            $validated['total_tunjangan_makan'] +
            $validated['total_tunjangan_bpjs_kesehatan'] +
            $validated['total_tunjangan_bpjs_ketenagakerjaan'] +
            $validated['total_lembur'] +
            $validated['total_kehadiran'] +
            $validated['bonus_pribadi'] +
            $validated['bonus_team'] +
            $validated['bonus_jackpot'] +
            $validated['total_thr'];

        $validated['total_pengurangan'] =
            $validated['total_potongan_bpjs_kesehatan'] +
            $validated['total_potongan_bpjs_ketenagakerjaan'] +
            $validated['total_terlambat'] +
            $validated['total_mangkir'] +
            $validated['total_izin'] +
            $validated['bayar_kasbon'] +
            $validated['loss'];

        $validated['grand_total'] = max(0, $validated['total_penjumlahan'] - $validated['total_pengurangan']);

        if (!empty($validated['bayar_kasbon']) && $validated['bayar_kasbon'] > 0) {
            $user = User::find($validated['user_id']);
            if ($user) {
                $user->update([
                    'saldo_kasbon'  => max(0, $user->saldo_kasbon - $validated['bayar_kasbon']),
                    'bonus_pribadi' => max(0, $user->bonus_pribadi - $validated['bonus_pribadi']),
                    'bonus_team'    => max(0, $user->bonus_team    - $validated['bonus_team']),
                    'bonus_jackpot' => max(0, $user->bonus_jackpot - $validated['bonus_jackpot']),
                ]);
            }
        }

        Payroll::create($validated);

        $userNotif = User::find($validated['user_id']);
if ($userNotif) {
    $userNotif->notify(new \App\Notifications\PayrollGenerated($payroll));
}

// ubah juga Payroll::create($validated) jadi:
$payroll = Payroll::create($validated);

        return redirect('/payroll')->with('success', 'Data Payroll Berhasil Disimpan!');
    }

    public function edit($id)
    {
        return view('payroll.edit', [
            'title' => 'Edit Data Penggajian',
            'data'  => Payroll::find($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $payroll = Payroll::find($id);
        $validated = $request->validate([
            'user_id'                          => 'required',
            'bulan'                            => 'required',
            'tahun'                            => 'required',
            'tanggal_mulai'                    => 'required',
            'tanggal_akhir'                    => 'required',
            'persentase_kehadiran'             => 'required',
            'no_gaji'                          => 'required',
            'gaji_pokok'                       => 'required',
            'total_reimbursement'              => 'required',
            'jumlah_tunjangan_transport'       => 'required',
            'uang_tunjangan_transport'         => 'required',
            'total_tunjangan_transport'        => 'required',
            'jumlah_tunjangan_makan'           => 'required',
            'uang_tunjangan_makan'             => 'required',
            'total_tunjangan_makan'            => 'required',
            'total_tunjangan_bpjs_kesehatan'   => 'required',
            'total_tunjangan_bpjs_ketenagakerjaan' => 'required',
            'total_potongan_bpjs_kesehatan'    => 'required',
            'total_potongan_bpjs_ketenagakerjaan'  => 'required',
            'jumlah_mangkir'                   => 'required',
            'uang_mangkir'                     => 'required',
            'total_mangkir'                    => 'required',
            'jumlah_lembur'                    => 'required',
            'uang_lembur'                      => 'required',
            'total_lembur'                     => 'required',
            'jumlah_izin'                      => 'required',
            'uang_izin'                        => 'required',
            'total_izin'                       => 'required',
            'bonus_pribadi'                    => 'required',
            'bonus_team'                       => 'required',
            'bonus_jackpot'                    => 'nullable',
            'jumlah_terlambat'                 => 'required',
            'uang_terlambat'                   => 'required',
            'total_terlambat'                  => 'required',
            'jumlah_kehadiran'                 => 'required',
            'uang_kehadiran'                   => 'required',
            'total_kehadiran'                  => 'required',
            'saldo_kasbon'                     => 'required',
            'bayar_kasbon'                     => 'required',
            'jumlah_thr'                       => 'required',
            'uang_thr'                         => 'required',
            'total_thr'                        => 'required',
            'loss'                             => 'required',
        ]);

        $moneyFields = [
            'gaji_pokok','total_reimbursement',
            'uang_tunjangan_transport','total_tunjangan_transport',
            'uang_tunjangan_makan','total_tunjangan_makan',
            'total_tunjangan_bpjs_kesehatan','total_tunjangan_bpjs_ketenagakerjaan',
            'total_potongan_bpjs_kesehatan','total_potongan_bpjs_ketenagakerjaan',
            'uang_mangkir','total_mangkir',
            'uang_lembur','total_lembur',
            'uang_izin','total_izin',
            'bonus_pribadi','bonus_team','bonus_jackpot',
            'uang_terlambat','total_terlambat',
            'uang_kehadiran','total_kehadiran',
            'saldo_kasbon','bayar_kasbon',
            'uang_thr','total_thr',
            'loss',
        ];

        foreach ($moneyFields as $field) {
            $raw = $validated[$field] ?? '0';
                // Hapus titik (pemisah ribuan) DAN koma, lalu cast ke integer
                $validated[$field] = (int) str_replace(['.', ','], '', $raw);
        }

        // Hitung ulang di server
        $validated['total_penjumlahan'] =
            $validated['gaji_pokok'] +
            $validated['total_reimbursement'] +
            $validated['total_tunjangan_transport'] +
            $validated['total_tunjangan_makan'] +
            $validated['total_tunjangan_bpjs_kesehatan'] +
            $validated['total_tunjangan_bpjs_ketenagakerjaan'] +
            $validated['total_lembur'] +
            $validated['total_kehadiran'] +
            $validated['bonus_pribadi'] +
            $validated['bonus_team'] +
            ($validated['bonus_jackpot'] ?? 0) +
            $validated['total_thr'];

        $validated['total_pengurangan'] =
            $validated['total_potongan_bpjs_kesehatan'] +
            $validated['total_potongan_bpjs_ketenagakerjaan'] +
            $validated['total_terlambat'] +
            $validated['total_mangkir'] +
            $validated['total_izin'] +
            $validated['bayar_kasbon'] +
            $validated['loss'];

        $validated['grand_total'] = max(0, $validated['total_penjumlahan'] - $validated['total_pengurangan']);

        $user = User::find($payroll->user_id);
        if ($user) {
            $user->update([
                'saldo_kasbon'  => $user->saldo_kasbon  + $payroll->bayar_kasbon,
                'bonus_pribadi' => $user->bonus_pribadi + $payroll->bonus_pribadi,
                'bonus_team'    => $user->bonus_team    + $payroll->bonus_team,
                'bonus_jackpot' => $user->bonus_jackpot + $payroll->bonus_jackpot,
            ]);
        }

        $payroll->update($validated);

        $user_update = User::find($request->user_id);
        if ($user_update) {
            $user_update->update([
                'saldo_kasbon'  => max(0, $user_update->saldo_kasbon  - $validated['bayar_kasbon']),
                'bonus_pribadi' => max(0, $user_update->bonus_pribadi - $validated['bonus_pribadi']),
                'bonus_team'    => max(0, $user_update->bonus_team    - $validated['bonus_team']),
                'bonus_jackpot' => max(0, $user_update->bonus_jackpot - ($validated['bonus_jackpot'] ?? 0)),
            ]);
        }

        return redirect('/payroll')->with('success', 'Data Berhasil Diupdate');
    }

    public function delete($id)
    {
        $payroll = Payroll::find($id);
        $user = User::find($payroll->user_id);
        if ($user) {
            $user->update(['saldo_kasbon' => $user->saldo_kasbon + $payroll->bayar_kasbon]);
        }
        $payroll->delete();
        return redirect('/payroll')->with('success', 'Data Berhasil di Hapus');
    }

    public function download($id)
    {
        $data = Payroll::with('user.Jabatan')->find($id);
        if (!$data) { abort(404); }
        return view('payroll.download', ['title' => 'Penggajian', 'data' => $data]);
    }
}