<?php

namespace App\Http\Controllers;

use App\Models\LaporanKerja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanKerjaController extends Controller
{
    public function index()
    {
        $title = 'Laporan Kerja';
        $search = request()->input('search');
        $jabatan_id = request()->input('jabatan_id');
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $laporan_kerjas = LaporanKerja::when($search, function ($query) use ($search) {
            $query->where(function ($subQuery) use ($search) {
                $subQuery->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'LIKE', '%'.$search.'%');
                })
                ->orWhere('informasi_umum', 'LIKE', '%'.$search.'%');
            });

        })
        ->when($jabatan_id, function ($query) use ($jabatan_id) {
            $query->whereHas('user', function ($q) use ($jabatan_id) {
                $q->where('jabatan_id', $jabatan_id);
            });
        })
        ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
            $query->whereBetween('tanggal', [$mulai, $akhir]);
        })
        ->when(!$mulai && !$akhir && auth()->user()->is_admin == 'admin', function ($query) {
            // Jika admin dan tidak ada filter tanggal, tampilkan data hari ini
            $query->where('tanggal', date('Y-m-d'));
        })
        ->when(auth()->user()->is_admin == 'user', function ($query) {
            $query->where('user_id', auth()->user()->id);
        })
        ->orderBy('tanggal', 'DESC')
        ->orderBy('id', 'DESC')
        ->paginate(10)
        ->withQueryString();

        $jabatan = \App\Models\Jabatan::select('id', 'nama_jabatan')->get();

        if (auth()->user()->is_admin == 'admin') {
            return view('laporan-kerja.index', compact(
                'title',
                'laporan_kerjas',
                'jabatan'
            ));
        } else {
            return view('laporan-kerja.indexUser', compact(
                'title',
                'laporan_kerjas'
            ));
        }
    }

    public function tambah()
    {
        $title = 'Laporan Kerja';
        return view('laporan-kerja.tambah' , compact('title'));
    }

    public function store(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));
        $validated = $request->validate([
            'informasi_umum' => 'required',
            'pekerjaan_dilaksanakan' => 'required',
            'pekerjaan_belum_selesai' => 'required',
            'catatan' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Optional, max 5MB
        ]);

        $validated['tanggal'] = date('Y-m-d');
        $validated['user_id'] = auth()->user()->id;

        // Handle photo upload if provided
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $fotoName = 'laporan-kerja/' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();

            // Store the file directly to public disk
            $path = $foto->storeAs('laporan-kerja', basename($fotoName), 'public');
            $validated['foto'] = $path;
        }

        $laporan_kerja = LaporanKerja::create($validated);

        return redirect('/laporan-kerja/show/'.$laporan_kerja->id)->with('success', 'Data Berhasil Disimpan');
    }

    public function show($id)
    {
        $title = 'Laporan Kerja';
        $laporan_kerja = LaporanKerja::find($id);
        return view('laporan-kerja.show' , compact(
            'title',
            'laporan_kerja',
        ));
    }

    public function edit($id)
    {
        $title = 'Laporan Kerja';
        $laporan_kerja = LaporanKerja::find($id);
        return view('laporan-kerja.edit' , compact(
            'title',
            'laporan_kerja',
        ));
    }

    public function update(Request $request, $id)
    {
        $laporan_kerja = LaporanKerja::find($id);

        $validated = $request->validate([
            'informasi_umum' => 'required',
            'pekerjaan_dilaksanakan' => 'required',
            'pekerjaan_belum_selesai' => 'required',
            'catatan' => 'required',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // Optional, max 5MB
        ]);

        // Handle photo deletion if requested
        if ($request->input('delete_current_photo') == '1') {
            if ($laporan_kerja->foto && Storage::disk('public')->exists($laporan_kerja->foto)) {
                Storage::disk('public')->delete($laporan_kerja->foto);
            }
            $validated['foto'] = null;
        }
        // Handle photo upload if provided
        elseif ($request->hasFile('foto')) {
            // Delete old photo if exists
            if ($laporan_kerja->foto && Storage::disk('public')->exists($laporan_kerja->foto)) {
                Storage::disk('public')->delete($laporan_kerja->foto);
            }

            $foto = $request->file('foto');
            $fotoName = 'laporan-kerja/' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();

            // Store the file directly to public disk
            $path = $foto->storeAs('laporan-kerja', basename($fotoName), 'public');
            $validated['foto'] = $path;
        }

        $laporan_kerja->update($validated);

        return redirect('/laporan-kerja/show/'.$laporan_kerja->id)->with('success', 'Data Berhasil Diupdate');
    }

    public function delete($id)
    {
        $laporan_kerja = LaporanKerja::find($id);

        // Delete photo file if exists
        if ($laporan_kerja->foto && Storage::exists('public/' . $laporan_kerja->foto)) {
            Storage::delete('public/' . $laporan_kerja->foto);
        }

        $laporan_kerja->delete();
        return redirect('/laporan-kerja')->with('success', 'Data Berhasil Didelete');
    }
}
