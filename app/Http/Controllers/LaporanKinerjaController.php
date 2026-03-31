<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanKinerja;

class LaporanKinerjaController extends Controller
{
    public function index()
    {
        date_default_timezone_set(config('app.timezone'));
        $title = 'Laporan Kinerja';
        $tglskrg = date('Y-m-d');
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');
        $jabatan_id = request()->input('jabatan_id');

        $laporan_kinerja = LaporanKinerja::when($jabatan_id, function ($query) use ($jabatan_id) {
                                        return $query->whereHas('user', function ($q) use ($jabatan_id) {
                                            $q->where('jabatan_id', $jabatan_id);
                                        });
                                    })
                                    ->when(!$mulai && !$akhir, function ($query) use ($tglskrg) {
                                        return $query->where('tanggal', '=', $tglskrg);
                                    })
                                    ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
                                        return $query->whereBetween('tanggal', [$mulai, $akhir]);
                                    })
                                    ->orderBy('id', 'DESC')
                                    ->paginate(10)
                                    ->withQueryString();

        $jabatan = \App\Models\Jabatan::select('id', 'nama_jabatan')->get();

        return view('laporan-kinerja.index', compact(
            'title',
            'laporan_kinerja',
            'jabatan'
        ));
    }
}
