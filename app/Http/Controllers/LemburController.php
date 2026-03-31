<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Lembur;
use App\Models\Lokasi;
use App\Models\settings;
use Illuminate\Http\Request;
use App\Events\NotifApproval;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class LemburController extends Controller
{
    /**
     * Validasi status lembur untuk mencegah perubahan yang tidak diinginkan
     */
    private function validateLemburStatus($lembur, $newStatus)
    {
        // Jika status sudah Approved atau Rejected, tidak boleh diubah lagi
        if (in_array($lembur->status, ['Approved', 'Rejected']) && $lembur->status !== $newStatus) {
            return false;
        }
        
        // Jika lembur belum selesai (jam_keluar null), hanya bisa Pending
        if ($lembur->jam_keluar === null && $newStatus !== 'Pending') {
            return false;
        }
        
        return true;
    }

    /**
     * Method untuk memeriksa status lembur dan memberikan informasi debugging
     */
    public function checkLemburStatus($id)
    {
        $lembur = Lembur::find($id);
        
        if (!$lembur) {
            return response()->json(['error' => 'Lembur tidak ditemukan'], 404);
        }
        
        $statusHistory = [
            'current_status' => $lembur->status,
            'jam_masuk' => $lembur->jam_masuk,
            'jam_keluar' => $lembur->jam_keluar,
            'approved_by' => $lembur->approved_by,
            'notes' => $lembur->notes,
            'created_at' => $lembur->created_at,
            'updated_at' => $lembur->updated_at,
        ];
        
        return response()->json($statusHistory);
    }
    public function index()
    {
        date_default_timezone_set(config('app.timezone'));
        $user_login = auth()->user()->id;
        $tanggal = "";
        $tglskrg = date("Y-m-d");
        $tglkmrn = date('Y-m-d', strtotime('-1 days'));
        $lembur = Lembur::where('user_id', $user_login)->where('tanggal', $tglkmrn)->get();
        if($lembur->count() > 0) {
            foreach($lembur as $l) {
                $jam_keluar = $l->jam_keluar;
            }
        } else {
            $jam_keluar = "-";
        }
        if($jam_keluar == null){
            $tanggal = $tglkmrn;
        } else {
            $tanggal = $tglskrg;
        }

        if (auth()->user()->is_admin == 'admin') {
            return view('lembur.index', [
                'title' => 'Absen Lembur',
                'lembur' => Lembur::where('user_id', $user_login)->where('tanggal', $tanggal)->get()
            ]);
        } else {
            return view('lembur.indexuser', [
                'title' => 'Absen Lembur',
                'lembur' => Lembur::where('user_id', $user_login)->where('tanggal', $tanggal)->first()
            ]);
        }

    }

    public function distance($lat1, $lon1, $lat2, $lon2, $unit)
    {
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

    public function masuk(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));

        $lat_kantor = auth()->user()->Lokasi->lat_kantor;
        $long_kantor = auth()->user()->Lokasi->long_kantor;
        $radius = auth()->user()->Lokasi->radius;
        $nama_lokasi = auth()->user()->Lokasi->nama_lokasi;

        $request["jarak_masuk"] = $this->distance($request["lat_masuk"], $request["long_masuk"], $lat_kantor, $long_kantor, "K") * 1000;

        if($request["jarak_masuk"] > $radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius ' . $nama_lokasi);
            return redirect('/lembur');
        } else {
            $foto_jam_masuk = $request["foto_jam_masuk"];

            $image_parts = explode(";base64,", $foto_jam_masuk);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_masuk_lembur/' . uniqid() . '.png';

            Storage::put($fileName, $image_base64);

            $request["foto_jam_masuk"] = $fileName;

            $validatedData = $request->validate([
                'user_id' => 'required',
                'tanggal' => 'required',
                'jam_masuk' => 'required',
                'foto_jam_masuk' => 'required',
                'lat_masuk' => 'required',
                'long_masuk' => 'required',
                'jarak_masuk' => 'required',
                'status' => 'required'
            ]);

            $validatedData['lokasi_id'] = auth()->user()->lokasi_id;

            Lembur::create($validatedData);

            $request->session()->flash('success', 'Berhasil Masuk Lembur');

            return redirect('/lembur');
        }

    }

    public function pulang(Request $request, $id)
    {
        date_default_timezone_set(config('app.timezone'));

        $lat_kantor = auth()->user()->Lokasi->lat_kantor;
        $long_kantor = auth()->user()->Lokasi->long_kantor;
        $radius = auth()->user()->Lokasi->radius;
        $nama_lokasi = auth()->user()->Lokasi->nama_lokasi;

        $request["jarak_keluar"] = $this->distance($request["lat_keluar"], $request["long_keluar"], $lat_kantor, $long_kantor, "K") * 1000;

        if($request["jarak_keluar"] > $radius) {
            Alert::error('Diluar Jangkauan', 'Lokasi Anda Diluar Radius ' . $nama_lokasi);
            return redirect('/lembur');
        } else {
            $foto_jam_keluar = $request["foto_jam_keluar"];

            $image_parts = explode(";base64,", $foto_jam_keluar);

            $image_base64 = base64_decode($image_parts[1]);
            $fileName = 'foto_jam_keluar_lembur/' . uniqid() . '.png';

            Storage::put($fileName, $image_base64);

            $request["foto_jam_keluar"] = $fileName;

            $lembur = Lembur::find($id);

            $jam_masuk = $lembur->jam_masuk;
            $time_masuk = strtotime($jam_masuk);
            $time_keluar = strtotime($request["jam_keluar"]);

            $diff = $time_keluar - $time_masuk;

            $request["total_lembur"] = $diff;

            $validatedData = $request->validate([
                'jam_keluar' => 'required',
                'lat_keluar' => 'required',
                'long_keluar' => 'required',
                'jarak_keluar' => 'required',
                'foto_jam_keluar' => 'required',
                'total_lembur' => 'required'
            ]);

            // Pastikan status tetap "Pending" saat pulang lembur untuk menunggu approval
            $validatedData['status'] = 'Pending';

            $lembur->update($validatedData);

            $user_roles = User::whereHas('roles', function ($query) {
                $query->where('name', 'admin')
                    ->orWhere('name', 'hrd')
                    ->orWhere('name', 'general_manager');
            });

            $kepala_cabang = User::whereHas('roles', function ($query) {
                $query->where('name', 'kepala_cabang');
            })->where('lokasi_id', auth()->user()->lokasi_id);

            $users = $user_roles->union($kepala_cabang)->get();

            foreach ($users as $user) {
                $type = 'Approval';
                $notif = 'Pengajuan Lembur Dari ' . auth()->user()->name . ' Butuh Approval Anda';
                $url = url('/data-lembur?user_id='.$lembur->user_id.'&mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal);

                $user->messages = [
                    'user_id'   =>  auth()->user()->id,
                    'from'   =>  auth()->user()->name,
                    'message'   =>  $notif,
                    'action'   =>  '/data-lembur?user_id='.$user->id.'&mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal
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


            return redirect('/lembur')->with('success', 'Berhasil Pulang Lembur');
        }

    }

    public function dataLembur(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));

        $user = User::find(auth()->user()->id);
        $user->update([
            'is_admin' => 'admin'
        ]);

        $tglskrg = date('Y-m-d');

        $user_id = request()->input('user_id');
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $data_lembur = Lembur::when(auth()->user()->hasRole('kepala_cabang'), function ($query) {
            return $query->where('lokasi_id', auth()->user()->lokasi_id);
        })
        ->when($user_id, function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        })
        ->when(!$mulai && !$akhir, function ($query) use ($tglskrg) {
            return $query->where('tanggal', $tglskrg);
        })
        ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
            return $query->whereBetween('tanggal', [$mulai, $akhir]);
        })
        ->orderBy('tanggal', 'ASC')
        ->orderBy('id', 'DESC');

        return view('lembur.datalembur', [
            'title' => 'Data Lembur',
            'user' => User::select('id', 'name')->get(),
            'data_lembur' => $data_lembur->paginate(10)->withQueryString()
        ]);
    }

    public function myLembur(Request $request)
    {
        date_default_timezone_set(config('app.timezone'));
        $tglskrg = date('Y-m-d');

        $user_id = request()->input('user_id');
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $data_lembur = Lembur::when(auth()->user()->hasRole('kepala_cabang'), function ($query) {
            return $query->where('lokasi_id', auth()->user()->lokasi_id);
        })
        ->when($user_id, function ($query) use ($user_id) {
            return $query->where('user_id', $user_id);
        })
        ->when(!$mulai && !$akhir, function ($query) use ($tglskrg) {
            return $query->where('tanggal', $tglskrg);
        })
        ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
            return $query->whereBetween('tanggal', [$mulai, $akhir]);
        })
        ->when(auth()->user()->is_admin == 'user', function ($query) {
            return $query->where('user_id', auth()->user()->id);
        })
        ->orderBy('tanggal', 'ASC')
        ->orderBy('id', 'DESC');

        return view('lembur.mylemburuser', [
            'title' => 'My Lembur',
            'data_lembur' => $data_lembur->paginate(10)->withQueryString()
        ]);
    }

    public function approval(Request $request, $id)
    {
        $lembur = Lembur::find($id);
        
        // Validasi bahwa lembur sudah selesai (jam_keluar tidak null)
        if (!$lembur || $lembur->jam_keluar === null) {
            Alert::error('Error', 'Lembur belum selesai atau tidak ditemukan');
            return redirect('/data-lembur');
        }
        
        // Validasi status untuk mencegah perubahan yang tidak diinginkan
        if (!$this->validateLemburStatus($lembur, $request['status'])) {
            Alert::error('Error', 'Status lembur tidak dapat diubah dari ' . $lembur->status . ' ke ' . $request['status']);
            return redirect('/data-lembur');
        }
        
        $validated = $request->validate([
            'status' => 'required',
            'notes' => 'nullable',
            'approved_by' => 'required',
        ]);

        // Log perubahan status untuk debugging
        \Log::info('Lembur Approval', [
            'lembur_id' => $id,
            'old_status' => $lembur->status,
            'new_status' => $validated['status'],
            'approved_by' => $validated['approved_by'],
            'user_id' => auth()->user()->id
        ]);

        if ($request['status'] == 'Approved') {
            $stat = 'Approve';
            $user = User::find($lembur->user_id);
            $type = 'Approved';
            $notif = 'Lembur Anda Telah Di Approve Oleh ' . auth()->user()->name;
            $url = url('/my-lembur?mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal);

            $user->messages = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  '/my-lembur?mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal
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
        } else {
            $stat = 'Reject';
            $user = User::find($lembur->user_id);
            $type = 'Rejected';
            $notif = 'Lembur Anda Telah Di Reject Oleh ' . auth()->user()->name;
            $url = url('/my-lembur?mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal);

            $user->messages = [
                'user_id'   =>  auth()->user()->id,
                'from'   =>  auth()->user()->name,
                'message'   =>  $notif,
                'action'   =>  '/my-lembur?mulai='.$lembur->tanggal.'&akhir='.$lembur->tanggal
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

        // Update dengan transaction untuk memastikan data konsisten
        DB::transaction(function () use ($lembur, $validated) {
            $lembur->update($validated);
        });
        
        return redirect('/data-lembur')->with('success', 'Berhasil ' . $stat . ' Lembur');
    }

    public function bulkApproval(Request $request)
    {
        $validated = $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'integer|exists:lemburs,id',
            'status' => 'required|in:Approved,Rejected',
            'notes' => 'nullable|string',
        ]);

        $lemburList = Lembur::whereIn('id', $validated['ids'])->get();
        $updatableIds = [];

        foreach ($lemburList as $lembur) {
            // hanya proses yang sudah selesai (jam_keluar ada) dan statusnya boleh diubah
            if ($lembur->jam_keluar !== null && $this->validateLemburStatus($lembur, $validated['status'])) {
                $updatableIds[] = $lembur->id;
            }
        }

        if (count($updatableIds) === 0) {
            return redirect('/data-lembur')->with('error', 'Tidak ada lembur valid untuk diubah.');
        }

        DB::transaction(function () use ($validated, $updatableIds) {
            Lembur::whereIn('id', $updatableIds)->update([
                'status' => $validated['status'],
                'notes' => $validated['notes'],
                'approved_by' => auth()->user()->id,
            ]);
        });

        return redirect('/data-lembur')->with('success', 'Berhasil mengubah ' . count($updatableIds) . ' lembur.');
    }
}
