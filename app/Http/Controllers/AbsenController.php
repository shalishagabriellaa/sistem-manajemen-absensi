<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Shift;
use App\Models\Lokasi;
use App\Models\Jabatan;
use App\Models\settings;
use App\Exports\AbsenExport;
use App\Models\JenisKinerja;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Events\NotifApproval;
use App\Models\LaporanKinerja;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use RealRashid\SweetAlert\Facades\Alert;

class AbsenController extends Controller
{
    public function index()
    {
        date_default_timezone_set(config('app.timezone'));
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date('Y-m-d');
        $waktu_sekarang = date('Y-m-d H:i:s');

        // Priority 1: Check for any ongoing shift (jam_absen filled but jam_pulang null) - supports overnight shifts
        $ongoing_shift = MappingShift::with('Shift')
            ->where('user_id', $user_login)
            ->whereNotNull('jam_absen')
            ->whereNull('jam_pulang')
            ->orderBy('tanggal', 'desc')
            ->first();

        if($ongoing_shift && $ongoing_shift->Shift) {
            // Calculate the actual end time considering overnight shifts
            $jam_masuk = strtotime($ongoing_shift->Shift->jam_masuk);
            $jam_keluar = strtotime($ongoing_shift->Shift->jam_keluar);
            $tanggal_shift = $ongoing_shift->tanggal;

            // Check if this is an overnight shift (jam keluar < jam masuk)
            if($jam_keluar < $jam_masuk) {
                // Overnight shift: end time is next day
                $waktu_keluar_shift = date('Y-m-d', strtotime($tanggal_shift . ' +1 day')) . ' ' . $ongoing_shift->Shift->jam_keluar . ':00';
            } else {
                // Regular shift: end time is same day
                $waktu_keluar_shift = $tanggal_shift . ' ' . $ongoing_shift->Shift->jam_keluar . ':00';
            }

            // Check if current time is still within the shift duration (+ some grace period for check-out)
            $grace_period_minutes = 120; // 2 hour grace period for check-out
            $waktu_keluar_dengan_grace = date('Y-m-d H:i:s', strtotime($waktu_keluar_shift . ' +' . $grace_period_minutes . ' minutes'));

            if(strtotime($waktu_sekarang) <= strtotime($waktu_keluar_dengan_grace)) {
                // Still within valid time for this ongoing shift
                $tanggal = $ongoing_shift->tanggal;
            } else {
                // Shift has expired, look for today's shift
                $shift_today = MappingShift::where('user_id', $user_login)->where('tanggal', $tglskrg)->first();
                if($shift_today) {
                    $tanggal = $tglskrg;
                } else {
                    $tanggal = $tglskrg; // Default to today
                }
            }
        } else {
            // Priority 2: No ongoing shifts, check if there's a shift scheduled for today
            $shift_today = MappingShift::where('user_id', $user_login)->where('tanggal', $tglskrg)->first();
            if($shift_today) {
                $tanggal = $tglskrg;
            } else {
                // Priority 3: No shift for today, check if there's an incomplete shift from yesterday (fallback)
                $shift_yesterday = MappingShift::with('Shift')
                    ->where('user_id', $user_login)
                    ->where('tanggal', date('Y-m-d', strtotime('-1 days')))
                    ->whereNotNull('jam_absen')
                    ->whereNull('jam_pulang')
                    ->first();

                if($shift_yesterday && $shift_yesterday->Shift) {
                    // Check if yesterday's shift is still valid with overnight consideration
                    $jam_masuk = strtotime($shift_yesterday->Shift->jam_masuk);
                    $jam_keluar = strtotime($shift_yesterday->Shift->jam_keluar);
                    $tanggal_shift = $shift_yesterday->tanggal;

                    if($jam_keluar < $jam_masuk) {
                        // Overnight shift from yesterday
                        $waktu_keluar_shift = date('Y-m-d', strtotime($tanggal_shift . ' +1 day')) . ' ' . $shift_yesterday->Shift->jam_keluar . ':00';
                    } else {
                        // Regular shift from yesterday
                        $waktu_keluar_shift = $tanggal_shift . ' ' . $shift_yesterday->Shift->jam_keluar . ':00';
                    }

                    $grace_period_minutes = 120;
                    $waktu_keluar_dengan_grace = date('Y-m-d H:i:s', strtotime($waktu_keluar_shift . ' +' . $grace_period_minutes . ' minutes'));

                    if(strtotime($waktu_sekarang) <= strtotime($waktu_keluar_dengan_grace)) {
                        $tanggal = $shift_yesterday->tanggal;
                    } else {
                        $tanggal = $tglskrg;
                    }
                } else {
                    // No valid shifts found, default to today
                    $tanggal = $tglskrg;
                }
            }
        }

        if (auth()->user()->is_admin == 'admin') {
            return view('absen.index', [
                'title' => 'Absen',
                'shift_karyawan' => MappingShift::where('user_id', $user_login)->where('tanggal', $tanggal)->first()
            ]);
        } else {
            return view('absen.indexUser', [
                'title' => 'Absen',
                'shift_karyawan' => MappingShift::where('user_id', $user_login)->where('tanggal', $tanggal)->first()
            ]);
        }

    }

    public function myLocation(Request $request)
    {
        // Validasi bahwa latitude dan longitude tersedia
        if (empty($request["lat"]) || empty($request["long"]) || $request["lat"] == '0' || $request["long"] == '0') {
            Alert::error('Lokasi Tidak Ditemukan', 'Tidak dapat mendapatkan lokasi Anda. Pastikan GPS aktif dan berikan izin akses lokasi pada browser.');
            return redirect()->back();
        }

        // Validasi userid
        if (empty($request["userid"])) {
            Alert::error('Error', 'User ID tidak ditemukan.');
            return redirect()->back();
        }

        return redirect('maps/'.$request["lat"].'/'.$request['long'].'/'.$request['userid']);
    }

    public function absenMasuk(Request $request, $id)
    {
        date_default_timezone_set(config('app.timezone'));

        $lat_kantor = auth()->user()->Lokasi->lat_kantor;
        $long_kantor = auth()->user()->Lokasi->long_kantor;
        $radius = auth()->user()->Lokasi->radius;
        $nama_lokasi = auth()->user()->Lokasi->nama_lokasi;

        // Check if location data is available
        if ($lat_kantor === null || $long_kantor === null || $radius === null) {
            Alert::error('Data Lokasi Tidak Lengkap', 'Data lokasi kantor Anda belum diatur dengan lengkap. Silakan hubungi administrator atau HR untuk mengatur lokasi kantor Anda terlebih dahulu sebelum melakukan absen.');
            return redirect('/absen');
        }

        $request["jarak_masuk"] = $this->distance($request["lat_absen"], $request["long_absen"], $lat_kantor, $long_kantor, "K") * 1000;

        $request["jam_absen"] = date('H:i');

        $mapping_shift = MappingShift::find($id);

        if($request["jarak_masuk"] > (float)$radius && $mapping_shift->lock_location == 1) {
            Alert::error('Lokasi Di Luar Jangkauan', 'Anda berada di luar radius kantor ' . $nama_lokasi . ' (Radius: ' . number_format((float)$radius) . ' meter). Pastikan Anda berada dalam area kantor untuk melakukan absen masuk.');
            return redirect('/absen');
        } else {
            $foto_jam_absen = $request["foto_jam_absen"];

            $image_parts = explode(";base64,", $foto_jam_absen);

            if (count($image_parts) < 2) {
                Alert::error('Foto Tidak Valid', 'Format foto absen masuk tidak valid. Silakan ambil foto ulang.');
                return redirect('/absen');
            }

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_absen/' . uniqid() . '.png';

            Storage::put($fileName, $image_base64);


            $request["foto_jam_absen"] = $fileName;

            $request["status_absen"] = "Masuk";

            $shift = $mapping_shift->Shift->jam_masuk;
            $tanggal = $mapping_shift->tanggal;

            $tgl_skrg = date("Y-m-d");

            $awal  = strtotime($tanggal . $shift);
            $akhir = strtotime($tgl_skrg . $request["jam_absen"]);
            $diff  = $akhir - $awal;

            if ($diff <= 0) {
                $request["telat"] = 0;
                $jenis_kinerja = JenisKinerja::where('nama', 'Presensi Kehadiran Ontime')->first();
                $laporan_kinerja_before = LaporanKinerja::where('user_id', auth()->user()->id)->latest()->first();
                if ($laporan_kinerja_before) {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $laporan_kinerja_before->penilaian_berjalan + $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                } else {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                }
            } else {
                $request["telat"] = $diff;
                $jenis_kinerja = JenisKinerja::where('nama', 'Telat Presensi Masuk')->first();
                $laporan_kinerja_before = LaporanKinerja::where('user_id', auth()->user()->id)->latest()->first();
                if ($laporan_kinerja_before) {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $laporan_kinerja_before->penilaian_berjalan + $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                } else {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                }
            }

            if ($mapping_shift->lock_location == 1) {
                $validatedData = $request->validate([
                    'jam_absen' => 'required',
                    'telat' => 'nullable',
                    'foto_jam_absen' => 'required',
                    'lat_absen' => 'required',
                    'long_absen' => 'required',
                    'jarak_masuk' => 'required',
                    'status_absen' => 'required'
                ], [
                    'jam_absen.required' => 'Waktu absen masuk tidak dapat dideteksi.',
                    'foto_jam_absen.required' => 'Foto absen masuk wajib diambil.',
                    'lat_absen.required' => 'Koordinat latitude lokasi tidak tersedia.',
                    'long_absen.required' => 'Koordinat longitude lokasi tidak tersedia.',
                    'jarak_masuk.required' => 'Jarak ke kantor tidak dapat dihitung.',
                    'status_absen.required' => 'Status absen tidak dapat ditentukan.'
                ]);
            } else {
                $validatedData = $request->validate([
                    'jam_absen' => 'required',
                    'telat' => 'nullable',
                    'foto_jam_absen' => 'required',
                    'lat_absen' => 'required',
                    'long_absen' => 'required',
                    'jarak_masuk' => 'required',
                    'keterangan_masuk' => 'required',
                    'status_absen' => 'required'
                ], [
                    'jam_absen.required' => 'Waktu absen masuk tidak dapat dideteksi.',
                    'foto_jam_absen.required' => 'Foto absen masuk wajib diambil.',
                    'lat_absen.required' => 'Koordinat latitude lokasi tidak tersedia.',
                    'long_absen.required' => 'Koordinat longitude lokasi tidak tersedia.',
                    'jarak_masuk.required' => 'Jarak ke kantor tidak dapat dihitung.',
                    'keterangan_masuk.required' => 'Keterangan absen masuk wajib diisi.',
                    'status_absen.required' => 'Status absen tidak dapat ditentukan.'
                ]);
            }

            MappingShift::where('id', $id)->update($validatedData);

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil Absen Masuk',
                    'type' => 'masuk'
                ]);
            }

            $request->session()->flash('success', 'Berhasil Absen Masuk');
            return redirect('/my-absen');
        }

    }

    public function absenPulang(Request $request, $id)
    {
        date_default_timezone_set(config('app.timezone'));
        $request["jam_pulang"] = date('H:i');

        $lat_kantor = auth()->user()->Lokasi->lat_kantor ?? null;
        $long_kantor = auth()->user()->Lokasi->long_kantor ?? null;
        $radius = auth()->user()->Lokasi->radius ?? null;
        $nama_lokasi = auth()->user()->Lokasi->nama_lokasi ?? null;

        $mapping_shift = MappingShift::find($id);

        // Check if location data is available
        if ($lat_kantor === null || $long_kantor === null || $radius === null) {
            Alert::error('Data Lokasi Tidak Lengkap', 'Data lokasi kantor Anda belum diatur dengan lengkap. Silakan hubungi administrator atau HR untuk mengatur lokasi kantor Anda terlebih dahulu sebelum melakukan absen.');
            return redirect('/absen');
        }

        $request["jarak_pulang"] = $this->distance($request["lat_pulang"], $request["long_pulang"], $lat_kantor, $long_kantor, "K") * 1000;

        if($request["jarak_pulang"] > (float)$radius && $mapping_shift->lock_location == 1) {
            Alert::error('Lokasi Di Luar Jangkauan', 'Anda berada di luar radius kantor ' . $nama_lokasi . ' (Radius: ' . number_format((float)$radius) . ' meter). Pastikan Anda berada dalam area kantor untuk melakukan absen pulang.');
            return redirect('/absen');
        } else {
            $foto_jam_pulang = $request["foto_jam_pulang"];

            $image_parts = explode(";base64,", $foto_jam_pulang);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_pulang/' . uniqid() . '.png';

            Storage::put($fileName, $image_base64);

            $request["foto_jam_pulang"] = $fileName;


            $shiftmasuk = $mapping_shift->Shift->jam_masuk;
            $shiftpulang = $mapping_shift->Shift->jam_keluar;
            $tanggal = $mapping_shift->tanggal;
            $new_tanggal = "";
            $timeMasuk = strtotime($shiftmasuk);
            $timePulang = strtotime($shiftpulang);


            if ($timePulang < $timeMasuk) {
                $new_tanggal = date('Y-m-d', strtotime('+1 days', strtotime($tanggal)));
            } else {
                $new_tanggal = $tanggal;
            }

            $tgl_skrg = date("Y-m-d");

            $akhir = strtotime($new_tanggal . $shiftpulang);
            $awal  = strtotime($tgl_skrg . $request["jam_pulang"]);
            $diff  = $akhir - $awal;

            if ($diff <= 0) {
                $request["pulang_cepat"] = 0;
                $jenis_kinerja = JenisKinerja::where('nama', 'Pulang tepat waktu')->first();
                $laporan_kinerja_before = LaporanKinerja::where('user_id', auth()->user()->id)->latest()->first();
                if ($laporan_kinerja_before) {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $laporan_kinerja_before->penilaian_berjalan + $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                } else {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                }
            } else {
                $request["pulang_cepat"] = $diff;
                $jenis_kinerja = JenisKinerja::where('nama', 'Pulang Sebelum waktunya')->first();
                $laporan_kinerja_before = LaporanKinerja::where('user_id', auth()->user()->id)->latest()->first();
                if ($laporan_kinerja_before) {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $laporan_kinerja_before->penilaian_berjalan + $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                } else {
                    LaporanKinerja::create([
                        'user_id' => auth()->user()->id,
                        'tanggal' => $tgl_skrg,
                        'jenis_kinerja_id' => $jenis_kinerja->id,
                        'nilai' => $jenis_kinerja->bobot,
                        'penilaian_berjalan' => $jenis_kinerja->bobot,
                        'reference' => 'App\Models\MappingShift',
                        'reference_id' => $mapping_shift->id,
                    ]);
                }
            }

            if ($mapping_shift->lock_location == 1) {
                $validatedData = $request->validate([
                    'jam_pulang' => 'required',
                    'foto_jam_pulang' => 'required',
                    'lat_pulang' => 'required',
                    'long_pulang' => 'required',
                    'pulang_cepat' => 'required',
                    'jarak_pulang' => 'required'
                ], [
                    'jam_pulang.required' => 'Waktu absen pulang tidak dapat dideteksi.',
                    'foto_jam_pulang.required' => 'Foto absen pulang wajib diambil.',
                    'lat_pulang.required' => 'Koordinat latitude lokasi tidak tersedia.',
                    'long_pulang.required' => 'Koordinat longitude lokasi tidak tersedia.',
                    'pulang_cepat.required' => 'Status kepulangan tidak dapat ditentukan.',
                    'jarak_pulang.required' => 'Jarak ke kantor tidak dapat dihitung.'
                ]);
            } else {
                $validatedData = $request->validate([
                    'jam_pulang' => 'required',
                    'foto_jam_pulang' => 'required',
                    'lat_pulang' => 'required',
                    'long_pulang' => 'required',
                    'pulang_cepat' => 'required',
                    'keterangan_pulang' => 'required',
                    'jarak_pulang' => 'required'
                ], [
                    'jam_pulang.required' => 'Waktu absen pulang tidak dapat dideteksi.',
                    'foto_jam_pulang.required' => 'Foto absen pulang wajib diambil.',
                    'lat_pulang.required' => 'Koordinat latitude lokasi tidak tersedia.',
                    'long_pulang.required' => 'Koordinat longitude lokasi tidak tersedia.',
                    'pulang_cepat.required' => 'Status kepulangan tidak dapat ditentukan.',
                    'keterangan_pulang.required' => 'Keterangan absen pulang wajib diisi.',
                    'jarak_pulang.required' => 'Jarak ke kantor tidak dapat dihitung.'
                ]);
            }

            MappingShift::where('id', $id)->update($validatedData);

            // Check if request is AJAX
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil Absen Pulang',
                    'type' => 'pulang'
                ]);
            }

            return redirect('/my-absen')->with('success', 'Berhasil Absen Pulang');
        }
    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
        // Validate that all coordinates are numeric and not null
        if (!is_numeric($lat1) || !is_numeric($lon1) || !is_numeric($lat2) || !is_numeric($lon2)) {
            return 0; // Return 0 distance if any coordinate is invalid
        }

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtoupper($unit);

        if ($unit == "K") {
            return ($miles * 1.609344);
        } else if ($unit == "N") {
            return ($miles * 0.8684);
        } else {
            return $miles;
        }
    }

    public function dataAbsen(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));

        $data_absen = MappingShift::dataAbsen()->paginate(10)->withQueryString();

        return view('absen.dataabsen', [
            'title' => 'Data Absen',
            'user' => User::select('id', 'name')->get(),
            'jabatan' => \App\Models\Jabatan::select('id', 'nama_jabatan')->get(),
            'data_absen' => $data_absen
        ]);
    }

    public function exportDataAbsen()
    {
        return (new AbsenExport($_GET))->download('List Absensi.xlsx');
    }

    public function maps($lat, $long, $userid)
    {
        date_default_timezone_set(config('app.timezone'));
        if (auth()->user()->is_admin == 'admin') {
            return view('absen.maps', [
                'title' => 'Maps',
                'lat' => $lat,
                'long' => $long,
                'data_user' => User::findOrFail($userid)
            ]);
        } else {
            return view('absen.mapsUser', [
                'title' => 'Maps',
                'lat' => $lat,
                'long' => $long,
                'data_user' => User::findOrFail($userid)
            ]);
        }
    }

    public function editMasuk($id)
    {
        $mapping_shift = MappingShift::findOrFail($id);
        $user = User::findOrFail($mapping_shift->user_id);
        $lokasi = $user->Lokasi;
        return view('absen.editmasuk', [
            'title' => 'Edit Absen Masuk',
            'data_absen' => $mapping_shift,
            'lokasi_kantor' => $lokasi
        ]);
    }

    public function prosesEditMasuk(Request $request, $id)
    {
        date_default_timezone_set(config('app.timezone'));

        $mapping_shift = MappingShift::where('id', $id)->get();

        foreach ($mapping_shift as $mp) {
            $shift = $mp->Shift->jam_masuk;
            $tanggal = $mp->tanggal;
            $user_id = $mp->user_id;
        }

        $awal  = strtotime($tanggal . $shift);
        $akhir = strtotime($tanggal . $request["jam_absen"]);
        $diff  = $akhir - $awal;

        if ($diff <= 0) {
            $request["telat"] = 0;
        } else {
            $request["telat"] = $diff;
        }

        $user = User::findOrFail($user_id);
        $lat_kantor = $user->Lokasi->lat_kantor;
        $long_kantor = $user->Lokasi->long_kantor;

        // Check if location data is available
        if ($lat_kantor === null || $long_kantor === null) {
            return redirect('/data-absen')->with('error', 'Tidak dapat memproses absen masuk. Data lokasi kantor untuk user ini belum lengkap. Pastikan koordinat latitude dan longitude kantor sudah diatur.');
        }

        $request["jarak_masuk"] = $this->distance($request["lat_absen"], $request["long_absen"], $lat_kantor, $long_kantor, "K") * 1000;

        $validatedData = $request->validate([
            'jam_absen' => 'required',
            'telat' => 'nullable',
            'foto_jam_absen' => 'image|max:5000',
            'lat_absen' => 'required',
            'long_absen' => 'required',
            'jarak_masuk' => 'required',
            'status_absen' => 'required'
        ]);

        if ($request->file('foto_jam_absen')) {
            if ($request->foto_jam_absen_lama) {
                Storage::delete($request->foto_jam_absen_lama);
            }
            $validatedData['foto_jam_absen'] = $request->file('foto_jam_absen')->store('foto_jam_absen');
        }

        MappingShift::where('id', $id)->update($validatedData);
        return redirect('/data-absen')->with('success', 'Berhasil Edit Absen Masuk (Manual)');
    }

    public function editPulang($id)
    {
        $mapping_shift = MappingShift::findOrFail($id);
        $user = User::findOrFail($mapping_shift->user_id);
        $lokasi = $user->Lokasi;
        return view('absen.editpulang', [
            'title' => 'Edit Absen Pulang',
            'data_absen' => $mapping_shift,
            'lokasi_kantor' => $lokasi
        ]);
    }

    public function prosesEditPulang(Request $request, $id)
    {
        $mapping_shift = MappingShift::where('id', $id)->get();
        foreach ($mapping_shift as $mp) {
            $shiftmasuk = $mp->Shift->jam_masuk;
            $shiftpulang = $mp->Shift->jam_keluar;
            $tanggal = $mp->tanggal;
            $user_id = $mp->user_id;
        }
        $new_tanggal = "";
        $timeMasuk = strtotime($shiftmasuk);
        $timePulang = strtotime($shiftpulang);


        if ($timePulang < $timeMasuk) {
            $new_tanggal = date('Y-m-d', strtotime('+1 days', strtotime($tanggal)));
        } else {
            $new_tanggal = $tanggal;
        }

        $akhir = strtotime($new_tanggal . $shiftpulang);
        $awal  = strtotime($new_tanggal . $request["jam_pulang"]);
        $diff  = $akhir - $awal;

        if ($diff <= 0) {
            $request["pulang_cepat"] = 0;
        } else {
            $request["pulang_cepat"] = $diff;
        }

        $user = User::findOrFail($user_id);
        $lat_kantor = $user->Lokasi->lat_kantor;
        $long_kantor = $user->Lokasi->long_kantor;

        // Check if location data is available
        if ($lat_kantor === null || $long_kantor === null) {
            return redirect('/data-absen')->with('error', 'Tidak dapat memproses absen masuk. Data lokasi kantor untuk user ini belum lengkap. Pastikan koordinat latitude dan longitude kantor sudah diatur.');
        }

        $request["jarak_pulang"] = $this->distance($request["lat_pulang"], $request["long_pulang"], $lat_kantor, $long_kantor, "K") * 1000;

        $validatedData = $request->validate([
            'jam_pulang' => 'required',
            'foto_jam_pulang' => 'image|max:5000',
            'lat_pulang' => 'required',
            'long_pulang' => 'required',
            'pulang_cepat' => 'required',
            'jarak_pulang' => 'required'
        ]);

        if ($request->file('foto_jam_pulang')) {
            if ($request->foto_jam_pulang_lama) {
                Storage::delete($request->foto_jam_pulang_lama);
            }
            $validatedData['foto_jam_pulang'] = $request->file('foto_jam_pulang')->store('foto_jam_pulang');
        }

        MappingShift::where('id', $id)->update($validatedData);

        return redirect('/data-absen')->with('success', 'Berhasil Edit Absen Pulang (Manual)');
    }

    public function deleteAdmin($id)
    {
        $delete = MappingShift::find($id);
        Storage::delete($delete->foto_jam_absen);
        Storage::delete($delete->foto_jam_pulang);
        $delete->delete();
        return redirect('/data-absen')->with('success', 'Data Berhasil di Delete');
    }

    public function myAbsen(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));
        $tglskrg = date('Y-m-d');
        $data_absen = MappingShift::where('tanggal', $tglskrg)->where('user_id', auth()->user()->id);

        if($request["mulai"] == null) {
            $request["mulai"] = $request["akhir"];
        }

        if($request["akhir"] == null) {
            $request["akhir"] = $request["mulai"];
        }

        if ($request["mulai"] && $request["akhir"]) {
            $data_absen = MappingShift::where('user_id', auth()->user()->id)->whereBetween('tanggal', [$request["mulai"], $request["akhir"]]);
        }

        if (auth()->user()->is_admin == 'admin') {
            return view('absen.myabsen', [
                'title' => 'My Absen',
                'data_absen' => $data_absen->paginate(10)->withQueryString()
            ]);
        } else {
            return view('absen.myabsenuser', [
                'title' => 'My Absen',
                'data_absen' => $data_absen->paginate(10)->withQueryString()
            ]);
        }
    }

    public function pengajuan($id)
    {
        $ms = MappingShift::find($id);
        $title = 'Pengajuan Absensi';
        return view('absen.pengajuan', compact(
            'ms',
            'title'
        ));
    }

    public function pengajuanProses(Request $request, $id)
    {
        $ms = MappingShift::find($id);
        $validated = $request->validate([
            'jam_masuk_pengajuan' => 'required',
            'jam_pulang_pengajuan' => 'required',
            'deskripsi' => 'required',
            'file_pengajuan' => 'required',
            'status_pengajuan' => 'required',
        ]);

        if ($request->file('file_pengajuan')) {
            $validated['file_pengajuan'] = $request->file('file_pengajuan')->store('file_pengajuan');
        }

        $ms->update($validated);

        $jabatan = Jabatan::find(auth()->user()->jabatan_id);
        $user = User::find($jabatan->manager);

        $type = 'Approval';
        $notif = 'Pengajuan Absensi Dari ' . auth()->user()->name . ' Butuh Approval Anda';
        $url = url('/pengajuan-absensi/edit/'.$ms->id);

        $user->messages = [
            'user_id'   =>  auth()->user()->id,
            'from'   =>  auth()->user()->name,
            'message'   =>  $notif,
            'action'   =>  '/pengajuan-absensi/edit/'.$ms->id
        ];
        $user->notify(new \App\Notifications\UserNotification);

        NotifApproval::dispatch($type, $user->id, $notif, $url);

        $settings = settings::first();
        if ($settings->api_url) {
            Http::post($settings->api_url, [
                'api_key' => $settings->api_whatsapp,
                'sender' => $settings->whatsapp,
                'number' => $user->telepon,
                'message' => $notif,
                'footer' => $url,
            ]);
        }

        return redirect('/pengajuan-absensi')->with('success', 'Pengajuan Berhasil Disimpan');
    }

    public function pengajuanAbsensi()
    {
        $title = 'Pengajuan Absensi';
        $search = request()->input('search');
        $jabatan = Jabatan::find(auth()->user()->jabatan_id);
        $user_id = User::where('jabatan_id', auth()->user()->jabatan_id)->pluck('id');
        $mapping_shift = MappingShift::where('status_pengajuan', '!=', null)
                                    ->when($jabatan->manager == auth()->user()->id, function ($query) use ($user_id) {
                                        $query->where(function ($q) use ($user_id) {
                                            $q->whereIn('user_id', $user_id)
                                                ->orWhere('user_id', auth()->user()->id);
                                        });
                                    })
                                    ->when($jabatan->manager !== auth()->user()->id, function ($query) {
                                        $query->where('user_id', auth()->user()->id);
                                    })
                                    ->when($search, function ($query) use ($search) {
                                        $query->whereHas('User', function ($query) use ($search) {
                                            $query->where('name', 'LIKE', '%'.$search.'%');
                                        });
                                    })
                                    ->orderBy('tanggal', 'DESC')
                                    ->paginate(10)
                                    ->withQueryString();

        return view('absen.indexPengajuan', compact(
            'mapping_shift',
            'title'
        ));
    }

    public function editPengajuanAbsensi($id)
    {
        $ms = MappingShift::find($id);
        $jabatan = Jabatan::find(auth()->user()->jabatan_id);
        $title = 'Pengajuan Absensi';
        return view('absen.editPengajuan', compact(
            'ms',
            'jabatan',
            'title'
        ));
    }

    public function updatePengajuanAbsensi(Request $request, $id)
    {
        $ms = MappingShift::find($id);
        $validated = $request->validate([
            'jam_masuk_pengajuan' => 'required',
            'jam_pulang_pengajuan' => 'required',
            'deskripsi' => 'required',
            'file_pengajuan' => 'nullable',
            'komentar' => 'nullable',
            'status_pengajuan' => 'required',
        ]);

        $ms->update($validated);

        if ($request['status_pengajuan'] == 'Disetujui') {
            $shiftmasuk = $ms->Shift->jam_masuk;
            $tanggal = $ms->tanggal;

            $awal_masuk  = strtotime($tanggal . $shiftmasuk);
            $akhir_masuk = strtotime($tanggal . $ms->jam_masuk_pengajuan);
            $diff_masuk  = $akhir_masuk - $awal_masuk;

            if ($diff_masuk <= 0) {
                $telat = 0;
            } else {
                $telat = $diff_masuk;
            }

            $shiftpulang = $ms->Shift->jam_keluar;
            $new_tanggal = "";
            $timeMasuk = strtotime($shiftmasuk);
            $timePulang = strtotime($shiftpulang);

            if ($timePulang < $timeMasuk) {
                $new_tanggal = date('Y-m-d', strtotime('+1 days', strtotime($tanggal)));
            } else {
                $new_tanggal = $tanggal;
            }

            $akhir_pulang = strtotime($new_tanggal . $shiftpulang);
            $awal_pulang  = strtotime($new_tanggal . $ms->jam_pulang_pengajuan);
            $diff_pulang  = $akhir_pulang - $awal_pulang;

            if ($diff_pulang <= 0) {
                $pulang_cepat = 0;
            } else {
                $pulang_cepat = $diff_pulang;
            }

            $ms->update([
                'jam_absen' => $ms->jam_masuk_pengajuan,
                'telat' => $telat,
                'lat_absen' => $ms->User->Lokasi->lat_kantor,
                'long_absen' => $ms->User->Lokasi->long_kantor,
                'jarak_masuk' => 0,
                'jam_pulang' => $ms->jam_pulang_pengajuan,
                'pulang_cepat' => $pulang_cepat,
                'lat_pulang' => $ms->User->Lokasi->lat_kantor,
                'long_pulang' => $ms->User->Lokasi->long_kantor,
                'jarak_pulang' => 0,
                'status_absen' => 'Masuk',
            ]);

            $user = User::find($ms->user_id);

            $type = 'Approved';
            $notif = 'Pengajuan Absensi Anda Telah Di Setujui Oleh ' . auth()->user()->name;
            $url = url('/pengajuan-absensi/edit/'.$ms->id);

            $user->messages = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  '/pengajuan-absensi/edit/'.$ms->id
            ];
            $user->notify(new \App\Notifications\UserNotification);

            NotifApproval::dispatch($type, $user->id, $notif, $url);

            $settings = settings::first();
            if ($settings->api_url) {
                Http::post($settings->api_url, [
                    'api_key' => $settings->api_whatsapp,
                    'sender' => $settings->whatsapp,
                    'number' => $user->telepon,
                    'message' => $notif,
                    'footer' => $url,
                ]);
            }
        } else if ($request['status_pengajuan'] == 'Tidak Disejutui') {
            $user = User::find($ms->user_id);

            $type = 'Rejected';
            $notif = 'Pengajuan Absensi Anda Tidak Setujui Oleh ' . auth()->user()->name;
            $url = url('/pengajuan-absensi/edit/'.$ms->id);

            $user->messages = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  '/pengajuan-absensi/edit/'.$ms->id
            ];
            $user->notify(new \App\Notifications\UserNotification);

            NotifApproval::dispatch($type, $user->id, $notif, $url);
        } else {
            $jabatan = Jabatan::find(auth()->user()->jabatan_id);
            $user = User::find($jabatan->manager);

            $type = 'Approval';
            $notif = 'Pengajuan Absensi Dari ' . auth()->user()->name . ' Butuh Approval Anda';
            $url = url('/pengajuan-absensi/edit/'.$ms->id);

            $user->messages = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  '/pengajuan-absensi/edit/'.$ms->id
            ];
            $user->notify(new \App\Notifications\UserNotification);

            NotifApproval::dispatch($type, $user->id, $notif, $url);

            $settings = settings::first();
            if ($settings->api_url) {
                Http::post($settings->api_url, [
                    'api_key' => $settings->api_whatsapp,
                    'sender' => $settings->whatsapp,
                    'number' => $user->telepon,
                    'message' => $notif,
                    'footer' => $url,
                ]);
            }
        }

        return redirect('/pengajuan-absensi')->with('success', 'Pengajuan Berhasil Diupdate');
    }

    public function myJadwal(Request $request)
    {
        $user_id = auth()->user()->id;
        $tahun = $request->get('tahun', date('Y'));
        $bulan = $request->get('bulan', date('m'));

        // Validate tahun and bulan
        $tahun = max(2020, min(2030, (int)$tahun)); // Limit tahun between 2020-2030
        $bulan = max(1, min(12, (int)$bulan)); // Limit bulan between 1-12

        // Get all mapping shifts for selected month
        $jadwal = MappingShift::where('user_id', $user_id)
            ->whereYear('tanggal', $tahun)
            ->whereMonth('tanggal', $bulan)
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy('tanggal');

        return view('absen.my-jadwal', [
            'title' => 'Jadwal Shift Saya',
            'jadwal' => $jadwal,
            'tahun' => $tahun,
            'bulan' => $bulan
        ]);
    }

}
