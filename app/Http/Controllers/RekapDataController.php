<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use App\Models\User;
use App\Models\Lembur;
use App\Models\Counter;
use App\Models\Payroll;
use App\Models\Jabatan;
use App\Exports\RekapExport;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use RealRashid\SweetAlert\Facades\Alert;

class RekapDataController extends Controller
{
    public function index()
    {
        return view('rekapdata.index', [
            'title' => 'Rekap Data Absensi',
            'jabatan' => Jabatan::all(),
        ]);
    }

    public function getData()
    {
        request()->validate([
            'mulai' => 'required',
            'akhir' => 'required',
        ]);

        date_default_timezone_set(config('app.timezone'));

        $jabatan_id = request()->input('jabatan_id');

        $user = User::when($jabatan_id, function($query) use ($jabatan_id) {
            return $query->where('jabatan_id', $jabatan_id);
        })->orderBy('name', 'ASC')->paginate(10)->withQueryString();

        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');
        $title = "Rekap Data Absensi";

        return view('rekapdata.getdata', [
            'title' => $title,
            'data_user' => $user,
            'tanggal_mulai' => $mulai,
            'tanggal_akhir' => $akhir,
            'jabatan_id' => $jabatan_id,
        ]);
    }

    public function export()
    {
        return (new RekapExport($_GET))->download('List Rekap Data.xlsx');
    }

    public function payroll($id)
    {
        $user = User::find($id);
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');
        $counter = Counter::where('name', 'Gaji')->first();
        $counter->update(['counter' => $counter->counter + 1]);
        $next_number = str_pad($counter->counter, 6, '0', STR_PAD_LEFT);
        $no_gaji = $counter->text . '/' . $next_number;

        return view('rekapdata.payroll', [
            'title' => 'Penggajian',
            'user' => $user,
            'tanggal_mulai' => $mulai,
            'tanggal_akhir' => $akhir,
            'no_gaji' => $no_gaji
        ]);
    }

    public function getPreviousPayroll(Request $request)
    {
        try {
            $user_id = $request->input('user_id');
            $bulan = $request->input('bulan');
            $tahun = $request->input('tahun');
            
            if (!$user_id || !$bulan || !$tahun) {
                return response()->json([
                    'success' => false,
                    'message' => 'User ID, Bulan, dan Tahun harus diisi'
                ], 400);
            }
            
            // Convert to integer untuk perhitungan
            $bulanInt = (int) $bulan;
            $tahunInt = (int) $tahun;
            $user_id = (int) $user_id;
            
            // Hitung bulan dan tahun sebelumnya
            $prevBulanInt = $bulanInt - 1;
            $prevTahunInt = $tahunInt;
            
            if ($prevBulanInt < 1) {
                $prevBulanInt = 12;
                $prevTahunInt = $tahunInt - 1;
            }
            
            // Convert kembali ke string karena kolom di database adalah string
            $prevBulan = (string) $prevBulanInt;
            $prevTahun = (string) $prevTahunInt;
            
            // Cari payroll bulan sebelumnya
            $previousPayroll = Payroll::where('user_id', $user_id)
                ->where('bulan', $prevBulan)
                ->where('tahun', $prevTahun)
                ->first();
            
            if ($previousPayroll) {
                // Return hanya field yang diperlukan untuk populate form
                // Pastikan semua nilai adalah integer atau 0
                $data = [
                    'gaji_pokok' => (int) ($previousPayroll->gaji_pokok ?? 0),
                    'total_reimbursement' => (int) ($previousPayroll->total_reimbursement ?? 0),
                    'jumlah_tunjangan_makan' => (int) ($previousPayroll->jumlah_tunjangan_makan ?? 0),
                    'uang_tunjangan_makan' => (int) ($previousPayroll->uang_tunjangan_makan ?? 0),
                    'total_tunjangan_makan' => (int) ($previousPayroll->total_tunjangan_makan ?? 0),
                    'jumlah_tunjangan_transport' => (int) ($previousPayroll->jumlah_tunjangan_transport ?? 0),
                    'uang_tunjangan_transport' => (int) ($previousPayroll->uang_tunjangan_transport ?? 0),
                    'total_tunjangan_transport' => (int) ($previousPayroll->total_tunjangan_transport ?? 0),
                    'total_tunjangan_bpjs_kesehatan' => (int) ($previousPayroll->total_tunjangan_bpjs_kesehatan ?? 0),
                    'total_potongan_bpjs_kesehatan' => (int) ($previousPayroll->total_potongan_bpjs_kesehatan ?? 0),
                    'total_tunjangan_bpjs_ketenagakerjaan' => (int) ($previousPayroll->total_tunjangan_bpjs_ketenagakerjaan ?? 0),
                    'total_potongan_bpjs_ketenagakerjaan' => (int) ($previousPayroll->total_potongan_bpjs_ketenagakerjaan ?? 0),
                    'jumlah_mangkir' => (int) ($previousPayroll->jumlah_mangkir ?? 0),
                    'uang_mangkir' => (int) ($previousPayroll->uang_mangkir ?? 0),
                    'total_mangkir' => (int) ($previousPayroll->total_mangkir ?? 0),
                    'jumlah_lembur' => (int) ($previousPayroll->jumlah_lembur ?? 0),
                    'uang_lembur' => (int) ($previousPayroll->uang_lembur ?? 0),
                    'total_lembur' => (int) ($previousPayroll->total_lembur ?? 0),
                    'jumlah_izin' => (int) ($previousPayroll->jumlah_izin ?? 0),
                    'uang_izin' => (int) ($previousPayroll->uang_izin ?? 0),
                    'total_izin' => (int) ($previousPayroll->total_izin ?? 0),
                    'bonus_pribadi' => (int) ($previousPayroll->bonus_pribadi ?? 0),
                    'bonus_team' => (int) ($previousPayroll->bonus_team ?? 0),
                    'bonus_jackpot' => (int) ($previousPayroll->bonus_jackpot ?? 0),
                    'jumlah_terlambat' => (int) ($previousPayroll->jumlah_terlambat ?? 0),
                    'uang_terlambat' => (int) ($previousPayroll->uang_terlambat ?? 0),
                    'total_terlambat' => (int) ($previousPayroll->total_terlambat ?? 0),
                    'jumlah_kehadiran' => (int) ($previousPayroll->jumlah_kehadiran ?? 0),
                    'uang_kehadiran' => (int) ($previousPayroll->uang_kehadiran ?? 0),
                    'total_kehadiran' => (int) ($previousPayroll->total_kehadiran ?? 0),
                    'jumlah_thr' => (int) ($previousPayroll->jumlah_thr ?? 0),
                    'uang_thr' => (int) ($previousPayroll->uang_thr ?? 0),
                    'total_thr' => (int) ($previousPayroll->total_thr ?? 0),
                    'saldo_kasbon' => (int) ($previousPayroll->saldo_kasbon ?? 0),
                    'bayar_kasbon' => (int) ($previousPayroll->bayar_kasbon ?? 0),
                    'loss' => (int) ($previousPayroll->loss ?? 0),
                ];
                
                return response()->json([
                    'success' => true,
                    'data' => $data
                ]);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Tidak ada data payroll bulan sebelumnya'
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getPreviousPayroll: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            \Log::error('Request data: ' . json_encode($request->all()));
            
            // Return error message yang lebih aman untuk production
            $errorMessage = config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat memuat data payroll bulan sebelumnya';
            
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        } catch (\Throwable $e) {
            \Log::error('Fatal Error getPreviousPayroll: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan fatal saat memuat data'
            ], 500);
        }
    }

    public function tambahPayroll(Request $request)
    {
        $cek = Payroll::where('user_id', $request['user_id'])->where('bulan', $request['bulan'])->where('tahun', $request['tahun'])->first();
        if ($cek) {
            Alert::error('Failed', 'Sudah Ada Data Pada Bulan Dan Tahun Tersebut!');
            return redirect('/rekap-data/get-data?mulai='.$request['tanggal_mulai'].'&akhir='.$request['tanggal_akhir'])->with('failed', 'Data Berhasil Disimpan');
        } else {
            $validated = $request->validate([
                'user_id' => 'required',
                'bulan' => 'required',
                'tahun' => 'required',
                'tanggal_mulai' => 'required',
                'tanggal_akhir' => 'required',
                'persentase_kehadiran' => 'required',
                'no_gaji' => 'required',
                'gaji_pokok' => 'required',
                'total_reimbursement' => 'required',

                'jumlah_tunjangan_transport' => 'required',
                'uang_tunjangan_transport' => 'required',
                'total_tunjangan_transport' => 'required',

                'jumlah_tunjangan_makan' => 'required',
                'uang_tunjangan_makan' => 'required',
                'total_tunjangan_makan' => 'required',

                'total_tunjangan_bpjs_kesehatan' => 'required',

                'total_tunjangan_bpjs_ketenagakerjaan' => 'required',

                'total_potongan_bpjs_kesehatan' => 'required',

                'total_potongan_bpjs_ketenagakerjaan' => 'required',

                'jumlah_mangkir' => 'required',
                'uang_mangkir' => 'required',
                'total_mangkir' => 'required',
                'jumlah_lembur' => 'required',
                'uang_lembur' => 'required',
                'total_lembur' => 'required',
                'jumlah_izin' => 'required',
                'uang_izin' => 'required',
                'total_izin' => 'required',
                'bonus_pribadi' => 'required',
                'bonus_team' => 'required',
                'bonus_jackpot' => 'required',
                'jumlah_terlambat' => 'required',
                'uang_terlambat' => 'required',
                'total_terlambat' => 'required',
                'jumlah_kehadiran' => 'required',
                'uang_kehadiran' => 'required',
                'total_kehadiran' => 'required',
                'saldo_kasbon' => 'required',
                'bayar_kasbon' => 'required',
                'jumlah_thr' => 'required',
                'uang_thr' => 'required',
                'total_thr' => 'required',
                'loss' => 'required',
                'total_penjumlahan' => 'required',
                'total_pengurangan' => 'required',
                'grand_total' => 'required',
            ]);

            $validated['gaji_pokok'] = str_replace(',', '', $validated['gaji_pokok']);
            $validated['total_reimbursement'] = str_replace(',', '', $validated['total_reimbursement']);

            $validated['uang_tunjangan_transport'] = str_replace(',', '', $validated['uang_tunjangan_transport']);
            $validated['total_tunjangan_transport'] = str_replace(',', '', $validated['total_tunjangan_transport']);

            $validated['uang_tunjangan_makan'] = str_replace(',', '', $validated['uang_tunjangan_makan']);
            $validated['total_tunjangan_makan'] = str_replace(',', '', $validated['total_tunjangan_makan']);

            $validated['total_tunjangan_bpjs_kesehatan'] = str_replace(',', '', $validated['total_tunjangan_bpjs_kesehatan']);
            $validated['total_tunjangan_bpjs_ketenagakerjaan'] = str_replace(',', '', $validated['total_tunjangan_bpjs_ketenagakerjaan']);

            $validated['total_potongan_bpjs_kesehatan'] = str_replace(',', '', $validated['total_potongan_bpjs_kesehatan']);
            $validated['total_potongan_bpjs_ketenagakerjaan'] = str_replace(',', '', $validated['total_potongan_bpjs_ketenagakerjaan']);

            $validated['uang_mangkir'] = str_replace(',', '', $validated['uang_mangkir']);
            $validated['total_mangkir'] = str_replace(',', '', $validated['total_mangkir']);
            $validated['uang_lembur'] = str_replace(',', '', $validated['uang_lembur']);
            $validated['total_lembur'] = str_replace(',', '', $validated['total_lembur']);
            $validated['uang_izin'] = str_replace(',', '', $validated['uang_izin']);
            $validated['total_izin'] = str_replace(',', '', $validated['total_izin']);
            $validated['bonus_pribadi'] = str_replace(',', '', $validated['bonus_pribadi']);
            $validated['bonus_team'] = str_replace(',', '', $validated['bonus_team']);
            $validated['bonus_jackpot'] = str_replace(',', '', $validated['bonus_jackpot']);
            $validated['uang_terlambat'] = str_replace(',', '', $validated['uang_terlambat']);
            $validated['total_terlambat'] = str_replace(',', '', $validated['total_terlambat']);
            $validated['uang_kehadiran'] = str_replace(',', '', $validated['uang_kehadiran']);
            $validated['total_kehadiran'] = str_replace(',', '', $validated['total_kehadiran']);
            $validated['saldo_kasbon'] = str_replace(',', '', $validated['saldo_kasbon']);
            $validated['bayar_kasbon'] = str_replace(',', '', $validated['bayar_kasbon']);
            $validated['uang_thr'] = str_replace(',', '', $validated['uang_thr']);
            $validated['total_thr'] = str_replace(',', '', $validated['total_thr']);
            $validated['loss'] = str_replace(',', '', $validated['loss']);
            $validated['total_penjumlahan'] = str_replace(',', '', $validated['total_penjumlahan']);
            $validated['total_pengurangan'] = str_replace(',', '', $validated['total_pengurangan']);
            $validated['grand_total'] = str_replace(',', '', $validated['grand_total']);

            $user = User::find($request['user_id']);
            $user->update([
                'saldo_kasbon' => $user->saldo_kasbon - $validated['bayar_kasbon'],
                'bonus_pribadi' => $user->bonus_pribadi - $validated['bonus_pribadi'],
                'bonus_team' => $user->bonus_team - $validated['bonus_team'],
                'bonus_jackpot' => $user->bonus_jackpot - $validated['bonus_jackpot'],
            ]);

            Payroll::create($validated);
            return redirect('/rekap-data/get-data?mulai='.$request['tanggal_mulai'].'&akhir='.$request['tanggal_akhir'])->with('success', 'Data Berhasil Disimpan');
        }
    }

    public function detailPdf()
    {
        $jabatan_id = request()->input('jabatan_id');

        $pdf = Pdf::loadView('rekapdata.detailPdf', [
            'title' => 'Detail PDF',
            'data' => MappingShift::when($jabatan_id, function($query) use ($jabatan_id) {
                return $query->whereHas('User', function($q) use ($jabatan_id) {
                    $q->where('jabatan_id', $jabatan_id);
                });
            })->dataAbsen()->get()
        ]);

        return $pdf->stream();
    }

    public function rekapPdf()
    {
        $jabatan_id = request()->input('jabatan_id');

        $pdf = Pdf::loadView('rekapdata.rekapPdf', [
            'title' => 'Rekap PDF',
            'data' => User::when($jabatan_id, function($query) use ($jabatan_id) {
                return $query->where('jabatan_id', $jabatan_id);
            })->orderBy('name', 'ASC')->get(),
            'jabatan_id' => $jabatan_id
        ]);

        return $pdf->stream();
    }
}
