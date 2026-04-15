<?php

namespace App\Http\Controllers;

use App\Models\Sip;
use App\Models\Cuti;
use App\Models\User;
use App\Models\Shift;
use App\Models\Lembur;
use App\Models\Lokasi;
use App\Models\Jabatan;
use App\Models\Kontrak;
use App\Models\Payroll;
use App\Models\dinasLuar;
use App\Models\ResetCuti;
use App\Models\StatusPajak;
use App\Imports\UsersImport;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Exports\PegawaiExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use RealRashid\SweetAlert\Facades\Alert;

class karyawanController extends Controller
{
    private function hasAdminAccess()
    {
        $adminRoles = ['admin', 'hrd', 'kepala_cabang', 'general_manager', 'finance', 'regional_manager'];
        foreach ($adminRoles as $role) {
            if (auth()->user()->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    private function uploadFoto($file, $folder)
    {
        $filename = time() . '_' . $file->getClientOriginalName();
        $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/' . $folder;

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $filename);
        return 'uploads/' . $folder . '/' . $filename;
    }

    public function index()
    {
        $search = request()->input('search');
        $jabatan_id = request()->input('jabatan_id');

        $data = User::when($jabatan_id, function ($query) use ($jabatan_id) {
                        return $query->where('jabatan_id', $jabatan_id);
                    })
                    ->when($search, function ($query) use ($search) {
                        return $query->where(function ($subQuery) use ($search) {
                            $subQuery->where('name', 'LIKE', '%'.$search.'%')
                                ->orWhere('email', 'LIKE', '%'.$search.'%')
                                ->orWhere('telepon', 'LIKE', '%'.$search.'%')
                                ->orWhere('username', 'LIKE', '%'.$search.'%')
                                ->orWhereHas('Jabatan', function ($query) use ($search) {
                                    $query->where('nama_jabatan', 'LIKE', '%'.$search.'%');
                                });
                        });
                    })
                    ->orderBy('name', 'ASC')
                    ->paginate(10)
                    ->withQueryString();

        $jabatan = \App\Models\Jabatan::select('id', 'nama_jabatan')->get();

        if ($this->hasAdminAccess() && auth()->user()->is_admin == 'admin') {
            return view('karyawan.index', [
                'title' => 'Pegawai',
                'data_user' => $data,
                'jabatan' => $jabatan
            ]);
        } else {
            return view('karyawan.indexUser', [
                'title' => 'Pegawai',
                'data_user' => $data,
                'jabatan' => $jabatan
            ]);
        }
    }

    public function kontrak($id)
    {
        $user = User::find($id);
        $title = 'List Kontrak';
        $kontraks = Kontrak::where('user_id', $id)
                            ->orderBy('tanggal', 'DESC')
                            ->paginate(10)
                            ->withQueryString();

        return view('karyawan.kontrak', compact('title', 'kontraks', 'user'));
    }

    public function export()
    {
        return (new PegawaiExport($_GET))->download('List Pegawai.xlsx');
    }

    public function kartuPegawai()
    {
        $title = 'Kartu Pegawai';
        return view('karyawan.kartuPegawai', compact('title'));
    }

    public function qrcode($id)
    {
        $title = 'Kartu';
        $user = User::find($id);
        return view('karyawan.qrcode', compact('title', 'user'));
    }

    public function print($id)
    {
        $user = User::find($id);
        $pdf = Pdf::loadView('karyawan.print', [
            'title' => 'Kartu',
            'user' => $user
        ]);
        $pdf->setPaper('A6', 'portrait');
        return $pdf->stream('kartu-pegawai.pdf');
    }

    public function euforia()
    {
        date_default_timezone_set(config('app.timezone'));
        $data = User::where('tgl_lahir', date('Y-m-d'))
                ->orderBy('name', 'ASC')
                ->paginate(10)
                ->withQueryString();

        return view('karyawan.euforia', [
            'title' => 'Euforia',
            'data_user' => $data
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('karyawan.show', [
            'title' => 'Detail Karyawan',
            'user' => $user
        ]);
    }

    public function importUsers(Request $request)
    {
        $request->validate([
            'file_excel' => 'required|mimes:xls,xlsx,csv|max:5000'
        ]);

        $file     = $request->file('file_excel');
        $filename = time() . '_' . $file->getClientOriginalName();
        $dir      = $_SERVER['DOCUMENT_ROOT'] . '/uploads/file_excel';

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $file->move($dir, $filename);
        $filePath = $dir . '/' . $filename;

        Excel::import(new UsersImport, $filePath);
        return back()->with('success', 'Data Berhasil Di Import');
    }

    public function tambahKaryawan()
    {
        return view('karyawan.tambah', [
            "title"       => 'Tambah Pegawai',
            "data_jabatan" => Jabatan::all(),
            "data_lokasi"  => Lokasi::where('status', 'approved')->where('keterangan', 'Office')->get(),
            "status_pajak" => StatusPajak::orderBy('id')->get(),
            "roles"        => Role::orderBy('name')->get()
        ]);
    }

    public function tambahKaryawanProses(Request $request)
    {
        $validatedData = $request->validate([
            'name'                          => 'required|max:255',
            'email'                         => 'required|email:dns|unique:users',
            'telepon'                       => 'required',
            'foto_karyawan'                 => 'image|file|max:10240',
            'username'                      => 'required|max:255|unique:users',
            'password'                      => 'required|min:6|max:255',
            'lokasi_id'                     => 'required',
            'tgl_lahir'                     => 'required',
            'tgl_join'                      => 'required',
            'gender'                        => 'required',
            'status_nikah'                  => 'nullable',
            'is_admin'                      => 'required',
            'status_pajak_id'               => 'required',
            'jabatan_id'                    => 'required',
            'ktp'                           => 'nullable',
            'kartu_keluarga'                => 'nullable',
            'bpjs_kesehatan'                => 'nullable',
            'bpjs_ketenagakerjaan'          => 'nullable',
            'npwp'                          => 'nullable',
            'sim'                           => 'nullable',
            'no_pkwt'                       => 'nullable',
            'no_kontrak'                    => 'nullable',
            'tanggal_mulai_pkwt'            => 'nullable',
            'tanggal_berakhir_pkwt'         => 'nullable',
            'rekening'                      => 'nullable',
            'nama_rekening'                 => 'nullable',
            'alamat'                        => 'nullable',
            'izin_cuti'                     => 'nullable',
            'izin_lainnya'                  => 'nullable',
            'izin_telat'                    => 'nullable',
            'izin_pulang_cepat'             => 'nullable',
            'gaji_pokok'                    => 'nullable',
            'tunjangan_makan'               => 'nullable',
            'tunjangan_transport'           => 'nullable',
            'tunjangan_bpjs_kesehatan'      => 'nullable',
            'tunjangan_bpjs_ketenagakerjaan'=> 'nullable',
            'lembur'                        => 'nullable',
            'kehadiran'                     => 'nullable',
            'thr'                           => 'nullable',
            'bonus_pribadi'                 => 'nullable',
            'bonus_team'                    => 'nullable',
            'bonus_jackpot'                 => 'nullable',
            'izin'                          => 'nullable',
            'terlambat'                     => 'nullable',
            'mangkir'                       => 'nullable',
            'saldo_kasbon'                  => 'nullable',
            'potongan_bpjs_kesehatan'       => 'nullable',
            'potongan_bpjs_ketenagakerjaan' => 'nullable',
            'masa_berlaku'                  => 'nullable',
        ]);

        $validatedData["izin_cuti"]          = $request->izin_cuti ?? 0;
        $validatedData["izin_lainnya"]       = $request->izin_lainnya ?? 0;
        $validatedData["izin_telat"]         = $request->izin_telat ?? 0;
        $validatedData["izin_pulang_cepat"]  = $request->izin_pulang_cepat ?? 0;

        $validatedData['gaji_pokok']                     = $request->gaji_pokok ? str_replace(',', '', $request->gaji_pokok) : 0;
        $validatedData['tunjangan_makan']                = $request->tunjangan_makan ? str_replace(',', '', $request->tunjangan_makan) : 0;
        $validatedData['tunjangan_transport']            = $request->tunjangan_transport ? str_replace(',', '', $request->tunjangan_transport) : 0;
        $validatedData['tunjangan_bpjs_kesehatan']       = $request->tunjangan_bpjs_kesehatan ? str_replace(',', '', $request->tunjangan_bpjs_kesehatan) : 0;
        $validatedData['tunjangan_bpjs_ketenagakerjaan'] = $request->tunjangan_bpjs_ketenagakerjaan ? str_replace(',', '', $request->tunjangan_bpjs_ketenagakerjaan) : 0;
        $validatedData['lembur']                         = $request->lembur ? str_replace(',', '', $request->lembur) : 0;
        $validatedData['kehadiran']                      = $request->kehadiran ? str_replace(',', '', $request->kehadiran) : 0;
        $validatedData['thr']                            = $request->thr ? str_replace(',', '', $request->thr) : 0;
        $validatedData['bonus_pribadi']                  = $request->bonus_pribadi ? str_replace(',', '', $request->bonus_pribadi) : 0;
        $validatedData['bonus_team']                     = $request->bonus_team ? str_replace(',', '', $request->bonus_team) : 0;
        $validatedData['bonus_jackpot']                  = $request->bonus_jackpot ? str_replace(',', '', $request->bonus_jackpot) : 0;
        $validatedData['izin']                           = $request->izin ? str_replace(',', '', $request->izin) : 0;
        $validatedData['terlambat']                      = $request->terlambat ? str_replace(',', '', $request->terlambat) : 0;
        $validatedData['mangkir']                        = $request->mangkir ? str_replace(',', '', $request->mangkir) : 0;
        $validatedData['saldo_kasbon']                   = $request->saldo_kasbon ? str_replace(',', '', $request->saldo_kasbon) : 0;
        $validatedData['potongan_bpjs_kesehatan']        = $request->potongan_bpjs_kesehatan ? str_replace(',', '', $request->potongan_bpjs_kesehatan) : 0;
        $validatedData['potongan_bpjs_ketenagakerjaan']  = $request->potongan_bpjs_ketenagakerjaan ? str_replace(',', '', $request->potongan_bpjs_ketenagakerjaan) : 0;

        if ($request->file('foto_karyawan')) {
            $validatedData['foto_karyawan'] = $this->uploadFoto($request->file('foto_karyawan'), 'foto_karyawan');
        }

        $validatedData['password'] = Hash::make($validatedData['password']);
        $user = User::create($validatedData);

        if ($request->role) {
            foreach ($request->role as $role) {
                $user->assignRole($role);
            }
        }

        return redirect('/pegawai')->with('success', 'Data Berhasil di Tambahkan');
    }

    public function detail($id)
    {
        $user = User::find($id);
        return view('karyawan.editkaryawan', [
            'title'        => 'Detail Pegawai',
            'karyawan'     => $user,
            'data_jabatan' => Jabatan::all(),
            "data_lokasi"  => Lokasi::where('status', 'approved')->where('keterangan', 'Office')->get(),
            "status_pajak" => StatusPajak::orderBy('id')->get(),
            "roles"        => Role::orderBy('name')->get(),
            'user_roles'   => $user->roles->pluck('name')->toArray()
        ]);
    }

    public function editKaryawanProses(Request $request, $id)
    {
        $rules = [
            'name'                          => 'required|max:255',
            'telepon'                       => 'required',
            'foto_karyawan'                 => 'image|file|max:10240',
            'lokasi_id'                     => 'required',
            'tgl_lahir'                     => 'required',
            'tgl_join'                      => 'required',
            'gender'                        => 'required',
            'status_nikah'                  => 'nullable',
            'is_admin'                      => 'required',
            'status_pajak_id'               => 'required',
            'jabatan_id'                    => 'required',
            'ktp'                           => 'nullable',
            'kartu_keluarga'                => 'nullable',
            'bpjs_kesehatan'                => 'nullable',
            'bpjs_ketenagakerjaan'          => 'nullable',
            'npwp'                          => 'nullable',
            'sim'                           => 'nullable',
            'no_pkwt'                       => 'nullable',
            'no_kontrak'                    => 'nullable',
            'tanggal_mulai_pkwt'            => 'nullable',
            'tanggal_berakhir_pkwt'         => 'nullable',
            'rekening'                      => 'nullable',
            'nama_rekening'                 => 'nullable',
            'alamat'                        => 'nullable',
            'izin_cuti'                     => 'nullable',
            'izin_lainnya'                  => 'nullable',
            'izin_telat'                    => 'nullable',
            'izin_pulang_cepat'             => 'nullable',
            'gaji_pokok'                    => 'nullable',
            'tunjangan_makan'               => 'nullable',
            'tunjangan_transport'           => 'nullable',
            'tunjangan_bpjs_kesehatan'      => 'nullable',
            'tunjangan_bpjs_ketenagakerjaan'=> 'nullable',
            'lembur'                        => 'nullable',
            'kehadiran'                     => 'nullable',
            'thr'                           => 'nullable',
            'bonus_pribadi'                 => 'nullable',
            'bonus_team'                    => 'nullable',
            'bonus_jackpot'                 => 'nullable',
            'izin'                          => 'nullable',
            'terlambat'                     => 'nullable',
            'mangkir'                       => 'nullable',
            'saldo_kasbon'                  => 'nullable',
            'potongan_bpjs_kesehatan'       => 'nullable',
            'potongan_bpjs_ketenagakerjaan' => 'nullable',
            'masa_berlaku'                  => 'nullable',
        ];

        $user = User::find($id);

        foreach ($user->roles as $r) {
            $user->removeRole($r->name);
        }

        if ($request->email != $user->email) {
            $rules['email'] = 'required|email:dns|unique:users';
        }

        if ($request->username != $user->username) {
            $rules['username'] = 'required|max:255|unique:users';
        }

        $validatedData = $request->validate($rules);

        $validatedData["izin_cuti"]         = $request->izin_cuti ?? 0;
        $validatedData["izin_lainnya"]      = $request->izin_lainnya ?? 0;
        $validatedData["izin_telat"]        = $request->izin_telat ?? 0;
        $validatedData["izin_pulang_cepat"] = $request->izin_pulang_cepat ?? 0;

        $validatedData['gaji_pokok']                     = $request->gaji_pokok ? str_replace(',', '', $request->gaji_pokok) : 0;
        $validatedData['tunjangan_makan']                = $request->tunjangan_makan ? str_replace(',', '', $request->tunjangan_makan) : 0;
        $validatedData['tunjangan_transport']            = $request->tunjangan_transport ? str_replace(',', '', $request->tunjangan_transport) : 0;
        $validatedData['tunjangan_bpjs_kesehatan']       = $request->tunjangan_bpjs_kesehatan ? str_replace(',', '', $request->tunjangan_bpjs_kesehatan) : 0;
        $validatedData['tunjangan_bpjs_ketenagakerjaan'] = $request->tunjangan_bpjs_ketenagakerjaan ? str_replace(',', '', $request->tunjangan_bpjs_ketenagakerjaan) : 0;
        $validatedData['makan_transport']                = $request->makan_transport ? str_replace(',', '', $request->makan_transport) : 0;
        $validatedData['lembur']                         = $request->lembur ? str_replace(',', '', $request->lembur) : 0;
        $validatedData['kehadiran']                      = $request->kehadiran ? str_replace(',', '', $request->kehadiran) : 0;
        $validatedData['thr']                            = $request->thr ? str_replace(',', '', $request->thr) : 0;
        $validatedData['bonus_pribadi']                  = $request->bonus_pribadi ? str_replace(',', '', $request->bonus_pribadi) : 0;
        $validatedData['bonus_team']                     = $request->bonus_team ? str_replace(',', '', $request->bonus_team) : 0;
        $validatedData['bonus_jackpot']                  = $request->bonus_jackpot ? str_replace(',', '', $request->bonus_jackpot) : 0;
        $validatedData['izin']                           = $request->izin ? str_replace(',', '', $request->izin) : 0;
        $validatedData['terlambat']                      = $request->terlambat ? str_replace(',', '', $request->terlambat) : 0;
        $validatedData['mangkir']                        = $request->mangkir ? str_replace(',', '', $request->mangkir) : 0;
        $validatedData['saldo_kasbon']                   = $request->saldo_kasbon ? str_replace(',', '', $request->saldo_kasbon) : 0;
        $validatedData['potongan_bpjs_kesehatan']        = $request->potongan_bpjs_kesehatan ? str_replace(',', '', $request->potongan_bpjs_kesehatan) : 0;
        $validatedData['potongan_bpjs_ketenagakerjaan']  = $request->potongan_bpjs_ketenagakerjaan ? str_replace(',', '', $request->potongan_bpjs_ketenagakerjaan) : 0;

        if ($request->file('foto_karyawan')) {
            if ($request->foto_karyawan_lama && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $request->foto_karyawan_lama)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $request->foto_karyawan_lama);
            }
            $validatedData['foto_karyawan'] = $this->uploadFoto($request->file('foto_karyawan'), 'foto_karyawan');
        }

        $path = $_SERVER['DOCUMENT_ROOT'] . '/neural.json';
        $neural   = File::get($path);
        $dataface = json_decode($neural, true);

        foreach ($dataface as &$item) {
            if ($item['label'] === $user->username) {
                $item['label'] = $request->username;
            }
        }

        File::put($path, json_encode($dataface, JSON_PRETTY_PRINT));

        $user->update($validatedData);
        if ($request->role) {
            foreach ($request->role as $role) {
                $user->assignRole($role);
            }
        }

        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/pegawai');
    }

    public function deleteKaryawan($id)
    {
        $delete = User::find($id);

        if ($delete->hasRole('admin')) {
            $totalAdmins = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin');
            })->count();

            if ($totalAdmins <= 1) {
                Alert::error('Tidak Dapat Menghapus Admin Terakhir', 'Pegawai "' . $delete->name . '" adalah admin terakhir di sistem.');
                return redirect('/pegawai');
            }
        }

        $isManager = Jabatan::where('manager', $id)->first();
        if ($isManager) {
            Alert::error('Tidak Dapat Menghapus Pegawai', 'Pegawai "' . $delete->name . '" masih menjadi Manager di divisi "' . $isManager->nama_jabatan . '".');
            return redirect('/pegawai');
        }

        MappingShift::where('user_id', $id)->delete();
        Lembur::where('user_id', $id)->delete();
        Cuti::where('user_id', $id)->delete();
        Sip::where('user_id', $id)->delete();
        Payroll::where('user_id', $id)->delete();

        if ($delete->foto_karyawan && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $delete->foto_karyawan)) {
            unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $delete->foto_karyawan);
        }

       $path = $_SERVER['DOCUMENT_ROOT'] . '/neural.json';
        $neural   = File::get($path);
        $dataface = json_decode($neural, true);

        $filterface = array_filter($dataface, function ($item) use ($delete) {
            return $item['label'] !== $delete->username;
        });

        File::put($path, json_encode(array_values($filterface), JSON_PRETTY_PRINT));
        $delete->delete();
        return redirect('/pegawai')->with('success', 'Data Berhasil di Delete');
    }

    public function checkAdminCount()
    {
        $totalAdmins = User::whereHas('roles', function ($query) {
            $query->where('name', 'admin');
        })->count();

        return response()->json([
            'total_admins'     => $totalAdmins,
            'can_delete_admin' => $totalAdmins > 1
        ]);
    }

    public function editpassword($id)
    {
        return view('karyawan.editpassword', [
            'title'    => 'Edit Password',
            'karyawan' => User::find($id)
        ]);
    }

    public function face($id)
    {
        return view('karyawan.face', [
            'title'    => 'Daftar Wajah',
            'karyawan' => User::find($id)
        ]);
    }

public function ajaxDescrip(Request $request)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . '/neural.json';
    $neural   = File::get($path);
    $dataface = json_decode($neural, true);
    $user     = User::find($request->user_id);

    $filterface = array_filter($dataface, function ($item) use ($user) {
        return $item['label'] !== $user->username;
    });

    $filtered = array_values($filterface);

    // Decode data baru dari request
    $newData = json_decode($request["myData"], true);

    if ($newData) {
        if (is_array($newData) && isset($newData[0])) {
            // Array of descriptors
            foreach ($newData as $item) {
                $filtered[] = $item;
            }
        } else {
            // Single descriptor
            $filtered[] = $newData;
        }
    }

    File::put($path, json_encode($filtered, JSON_PRETTY_PRINT));

    return response()->json(['success' => true, 'count' => count($filtered)]);
}

    public function ajaxPhoto(Request $request)
    {
        $image       = $request["image"];
        $image_parts = explode(";base64,", $image);
        $image_base64 = base64_decode($image_parts[1]);

        $dir = $_SERVER['DOCUMENT_ROOT'] . '/uploads/foto_face_recognition';
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        $fileName = 'uploads/foto_face_recognition/' . $request["path"] . '.png';
        file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/' . $fileName, $image_base64);

        $user = User::where('username', $request['path'])->update(["foto_face_recognition" => $fileName]);
        return $user;
    }

    public function editPasswordProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|min:6|max:255',
        ]);

        $validatedData['password'] = Hash::make($request->password);
        User::where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Password Berhasil Diganti');
        return redirect('/pegawai');
    }

    public function shift($id)
    {
        $tanggal_mulai = request()->input('tanggal_mulai');
        $tanggal_akhir = request()->input('tanggal_akhir');
        $per_page      = request()->input('per_page', 10);

        $mapping_shift = MappingShift::where('user_id', $id)
                            ->when($tanggal_mulai && $tanggal_akhir, function ($query) use ($tanggal_mulai, $tanggal_akhir) {
                                return $query->whereBetween('tanggal', [$tanggal_mulai, $tanggal_akhir]);
                            })
                            ->when($tanggal_mulai && !$tanggal_akhir, function ($query) use ($tanggal_mulai) {
                                return $query->where('tanggal', '>=', $tanggal_mulai);
                            })
                            ->when(!$tanggal_mulai && $tanggal_akhir, function ($query) use ($tanggal_akhir) {
                                return $query->where('tanggal', '<=', $tanggal_akhir);
                            })
                            ->orderBy('tanggal', 'DESC')
                            ->paginate($per_page)
                            ->withQueryString();

        return view('karyawan.mappingshift', [
            'title'          => 'Mapping Shift',
            'karyawan'       => User::find($id),
            'shift_karyawan' => $mapping_shift,
            'shift'          => Shift::all()
        ]);
    }

    public function dinasLuar($id)
    {
        $tanggal   = request()->input('tanggal');
        $dinas_luar = dinasLuar::where('user_id', $id)
                        ->when($tanggal, function ($query) use ($tanggal) {
                            return $query->where('tanggal', $tanggal);
                        })
                        ->orderBy('id', 'desc')
                        ->paginate(10)
                        ->withQueryString();

        return view('karyawan.dinasluar', [
            'title'      => 'Mapping Dinas Luar',
            'karyawan'   => User::find($id),
            'dinas_luar' => $dinas_luar,
            'shift'      => Shift::all()
        ]);
    }

    public function prosesTambahShift(Request $request)
    {
        \Log::info('prosesTambahShift called', [
            'user_id'       => $request->user_id,
            'shift_id'      => $request->shift_id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
        ]);

        date_default_timezone_set(config('app.timezone'));

        $request->validate([
            'shift_id'      => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required',
            'holiday_days'   => 'nullable|array',
            'holiday_days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
        ]);

        $request["tanggal_mulai"] = $request["tanggal_mulai"] ?? $request["tanggal_akhir"];
        $request["tanggal_akhir"] = $request["tanggal_akhir"] ?? $request["tanggal_mulai"];

        $begin    = new \DateTime($request["tanggal_mulai"]);
        $end      = new \DateTime($request["tanggal_akhir"]);
        $end      = $end->modify('+1 day');
        $interval = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);

        $holidayShift   = \App\Models\Shift::whereRaw('LOWER(nama_shift) = ?', ['libur'])->first();
        $holidayShiftId = $holidayShift ? $holidayShift->id : null;

        foreach ($daterange as $date) {
            $tanggal      = $date->format("Y-m-d");
            $holiday_days = $request->input('holiday_days', []);
            $day_name     = $date->format('l');

            $currentShiftId    = in_array($day_name, $holiday_days) ? $holidayShiftId : $request->shift_id;
            $currentLockLocation = $request->lock_location;

            if (!$currentShiftId) continue;

            $cek = MappingShift::where('user_id', $request['user_id'])->where('tanggal', $tanggal)->first();

            if (!$cek) {
                $shift = \App\Models\Shift::find($currentShiftId);
                if (!$shift) continue;

                $statusAbsen = strtolower($shift->nama_shift) === 'libur' ? "Libur" : "Tidak Masuk";

                MappingShift::create([
                    'user_id'       => $request['user_id'],
                    'shift_id'      => $currentShiftId,
                    'tanggal'       => $tanggal,
                    'status_absen'  => $statusAbsen,
                    'lock_location' => $currentLockLocation ?: null,
                    'telat'         => 0,
                    'pulang_cepat'  => 0,
                ]);
            }
        }

        return redirect('/pegawai/shift/' . $request["user_id"])->with('success', 'Data Berhasil di Tambahkan');
    }

    public function prosesTambahShiftBulk(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));

        $request->validate([
            'user_id'     => 'required|integer|exists:users,id',
            'shifts_data' => 'required|json',
        ]);

        $userId     = $request->user_id;
        $shiftsData = json_decode($request->shifts_data, true);

        if (!$shiftsData || !is_array($shiftsData)) {
            return response()->json(['message' => 'Invalid shifts data'], 400);
        }

        $createdCount = 0;

        foreach ($shiftsData as $date => $shiftInfo) {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) continue;

            $shiftId      = $shiftInfo['shift_id'] ?? null;
            $lockLocation = $shiftInfo['lock_location'] ?? 0;

            if (!$shiftId) continue;

            $existingMapping = MappingShift::where('user_id', $userId)->where('tanggal', $date)->first();

            if (!$existingMapping) {
                $shift = \App\Models\Shift::find($shiftId);
                if (!$shift) continue;

                $statusAbsen = $shiftId == 1 ? "Libur" : "Tidak Masuk";

                MappingShift::create([
                    'user_id'       => $userId,
                    'shift_id'      => $shiftId,
                    'tanggal'       => $date,
                    'status_absen'  => $statusAbsen,
                    'lock_location' => $lockLocation,
                    'telat'         => 0,
                    'pulang_cepat'  => 0,
                ]);

                $createdCount++;
            }
        }

        return response()->json([
            'message'       => "Berhasil menjadwalkan {$createdCount} shift",
            'created_count' => $createdCount
        ]);
    }

    public function prosesTambahDinas(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));

        $request["tanggal_mulai"] = $request["tanggal_mulai"] ?? $request["tanggal_akhir"];
        $request["tanggal_akhir"] = $request["tanggal_akhir"] ?? $request["tanggal_mulai"];

        $begin     = new \DateTime($request["tanggal_mulai"]);
        $end       = new \DateTime($request["tanggal_akhir"]);
        $end       = $end->modify('+1 day');
        $interval  = new \DateInterval('P1D');
        $daterange = new \DatePeriod($begin, $interval, $end);

        foreach ($daterange as $date) {
            $tanggal = $date->format("Y-m-d");
            $request["status_absen"] = $request["shift_id"] == 1 ? "Libur" : "Tidak Masuk";
            $request["tanggal"]      = $tanggal;

            $validatedData = $request->validate([
                'user_id'      => 'required',
                'shift_id'     => 'required',
                'tanggal'      => 'required',
                'status_absen' => 'required',
            ]);

            dinasLuar::create($validatedData);
        }

        return redirect('/pegawai/dinas-luar/' . $request["user_id"])->with('success', 'Data Berhasil di Tambahkan');
    }

    public function deleteShift(Request $request, $id)
    {
        MappingShift::find($id)->delete();
        return redirect('/pegawai/shift/' . $request["user_id"])->with('success', 'Data Berhasil di Delete');
    }

    public function bulkDeleteShift(Request $request)
    {
        $validated = $request->validate([
            'ids'     => 'required|array|min:1',
            'ids.*'   => 'integer|exists:mapping_shifts,id',
            'user_id' => 'required|integer',
        ]);

        MappingShift::whereIn('id', $validated['ids'])->where('user_id', $validated['user_id'])->delete();
        return redirect('/pegawai/shift/' . $validated['user_id'])->with('success', 'Data Berhasil di Hapus');
    }

    public function bulkUpdateShift(Request $request)
    {
        $validated = $request->validate([
            'ids'           => 'required|array|min:1',
            'ids.*'         => 'integer|exists:mapping_shifts,id',
            'user_id'       => 'required|integer',
            'shift_id'      => 'required|integer|exists:shifts,id',
            'lock_location' => 'nullable|in:1',
        ]);

        $statusAbsen = $validated['shift_id'] == 1 ? 'Libur' : 'Tidak Masuk';

        MappingShift::whereIn('id', $validated['ids'])
            ->where('user_id', $validated['user_id'])
            ->update([
                'shift_id'      => $validated['shift_id'],
                'status_absen'  => $statusAbsen,
                'lock_location' => $request->boolean('lock_location') ? 1 : null,
            ]);

        return redirect('/pegawai/shift/' . $validated['user_id'])->with('success', 'Data Berhasil di Update');
    }

    public function deleteDinas(Request $request, $id)
    {
        dinasLuar::find($id)->delete();
        return redirect('/pegawai/dinas-luar/' . $request["user_id"])->with('success', 'Data Berhasil di Delete');
    }

    public function editShift($id)
    {
        return view('karyawan.editshift', [
            'title'          => 'Edit Shift',
            'shift_karyawan' => MappingShift::find($id),
            'shift'          => Shift::all()
        ]);
    }

    public function editDinas($id)
    {
        return view('karyawan.editdinas', [
            'title'      => 'Edit Dinas',
            'dinas_luar' => dinasLuar::find($id),
            'shift'      => Shift::all()
        ]);
    }

    public function prosesEditShift(Request $request, $id)
    {
        date_default_timezone_set(config('app.timezone'));
        $request["status_absen"] = $request["shift_id"] == 1 ? "Libur" : "Tidak Masuk";

        $validatedData = $request->validate([
            'shift_id'     => 'required',
            'tanggal'      => 'required',
            'status_absen' => 'required'
        ]);

        $validatedData['lock_location'] = $request['lock_location'] ?: null;
        MappingShift::where('id', $id)->update($validatedData);
        return redirect('/pegawai/shift/' . $request["user_id"])->with('success', 'Data Berhasil di Update');
    }

    public function prosesEditDinas(Request $request, $id)
    {
        date_default_timezone_set(config('app.timezone'));
        $request["status_absen"] = $request["shift_id"] == 1 ? "Libur" : "Tidak Masuk";

        $validatedData = $request->validate([
            'shift_id'     => 'required',
            'tanggal'      => 'required',
            'status_absen' => 'required'
        ]);

        dinasLuar::where('id', $id)->update($validatedData);
        return redirect('/pegawai/dinas-luar/' . $request["user_id"])->with('success', 'Data Berhasil di Update');
    }

            public function myProfile()
            {
                $user_id  = auth()->user()->id;
                $kontraks = \App\Models\Kontrak::where('user_id', $user_id)
                                ->orderBy('tanggal', 'DESC')
                                ->get();
                $payrolls = \App\Models\Payroll::where('user_id', $user_id)
                                ->orderBy('tahun', 'DESC')
                                ->orderBy('bulan', 'DESC')
                                ->get();
            
                $viewData = [
                    'title'        => 'My Profile',
                    'data_jabatan' => Jabatan::all(),
                    'data_lokasi'  => Lokasi::where('status', 'approved')->get(),
                    'kontraks'     => $kontraks,
                    'payrolls'     => $payrolls,
                ];
            
                if ($this->hasAdminAccess() && auth()->user()->is_admin == 'admin') {
                    return view('karyawan.myprofile', $viewData);
                } else {
                    return view('karyawan.myprofileuser', $viewData);
                }
            }

    public function myProfileUpdate(Request $request, $id)
    {
        $rules = [
            'name'          => 'required|max:255',
            'telepon'       => 'required',
            'foto_karyawan' => 'image|file|max:10240',
            'tgl_lahir'     => 'required',
            'gender'        => 'required',
            'status_nikah'  => 'required',
            'ktp'           => 'nullable',
            'kartu_keluarga'=> 'nullable',
            'bpjs_kesehatan'=> 'nullable',
            'bpjs_ketenagakerjaan' => 'nullable',
            'npwp'          => 'nullable',
            'sim'           => 'nullable',
            'rekening'      => 'nullable',
            'nama_rekening' => 'nullable',
            'alamat'        => 'nullable',
        ];

        $userId = User::find($id);

        if ($request->email != $userId->email) {
            $rules['email'] = 'required|email:dns|unique:users';
        }

        if ($request->username != $userId->username) {
            $rules['username'] = 'required|max:255|unique:users';
        }

        $validatedData = $request->validate($rules);

        if ($request->file('foto_karyawan')) {
            if ($request->foto_karyawan_lama && file_exists($_SERVER['DOCUMENT_ROOT'] . '/' . $request->foto_karyawan_lama)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/' . $request->foto_karyawan_lama);
            }
            $validatedData['foto_karyawan'] = $this->uploadFoto($request->file('foto_karyawan'), 'foto_karyawan');
        }

        $path = $_SERVER['DOCUMENT_ROOT'] . '/neural.json';
        $neural   = File::get($path);
        $dataface = json_decode($neural, true);

        foreach ($dataface as &$item) {
            if ($item['label'] === $userId->username) {
                $item['label'] = $request->username;
            }
        }

        File::put($path, json_encode($dataface, JSON_PRETTY_PRINT));
        User::where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Data Berhasil di Update');
        return redirect('/my-profile');
    }

    public function editPassMyProfile()
    {
        if ($this->hasAdminAccess() && auth()->user()->is_admin == 'admin') {
            return view('karyawan.editpassmyprofile', ['title' => 'Ganti Password']);
        } else {
            return view('karyawan.editpassworduser', ['title' => 'Ganti Password']);
        }
    }

    public function editPassMyProfileProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'password' => 'required|min:6|max:255|confirmed',
        ]);

        $validatedData['password'] = Hash::make($request->password);
        User::where('id', $id)->update($validatedData);
        $request->session()->flash('success', 'Password Berhasil di Update');
        return redirect('/dashboard');
    }

    public function resetCuti()
    {
        return view('karyawan.masterreset', [
            'title'      => 'Master Data Reset Cuti',
            'data_cuti'  => ResetCuti::first()
        ]);
    }

    public function resetCutiProses(Request $request, $id)
    {
        $validatedData = $request->validate([
            'izin_cuti'              => 'required',
            'izin_dinas_luar'        => 'required',
            'izin_sakit'             => 'required',
            'izin_cek_kesehatan'     => 'required',
            'izin_keperluan_pribadi' => 'required',
            'izin_lainnya'           => 'required',
            'izin_telat'             => 'required',
            'izin_pulang_cepat'      => 'required'
        ]);

        ResetCuti::where('id', $id)->update($validatedData);
        return redirect('/reset-cuti')->with('success', 'Master Cuti Berhasil Diupdate');
    }

    public function switchUser()
    {
        User::find(auth()->user()->id)->update(['is_admin' => 'user']);
        return redirect('/dashboard')->with('success', 'Berhasil Pindah Dashboard User');
    }

    public function switchAdmin()
    {
        $user        = User::find(auth()->user()->id);
        $adminRoles  = ['admin', 'hrd', 'kepala_cabang', 'general_manager', 'finance', 'regional_manager'];
        $hasAdminAccess = false;

        foreach ($adminRoles as $role) {
            if ($user->hasRole($role)) {
                $hasAdminAccess = true;
                break;
            }
        }

        if (!$hasAdminAccess) {
            return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses untuk beralih ke Dashboard Admin');
        }

        $user->update(['is_admin' => 'admin']);
        return redirect('/dashboard')->with('success', 'Berhasil Pindah Dashboard Admin');
    }
}