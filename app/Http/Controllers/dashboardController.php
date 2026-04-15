<?php

namespace App\Http\Controllers;
use App\Models\Cuti;
use App\Models\User;
use App\Models\Berita;
use App\Models\Kasbon;
use App\Models\Lembur;
use App\Models\Payroll;
use App\Models\ResetCuti;
use App\Models\MappingShift;
use Illuminate\Http\Request;
use App\Models\Reimbursement;

class dashboardController extends Controller
{
    /**
     * Check if user has admin-level access
     */
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
    public function index()
    {
        date_default_timezone_set(config('app.timezone'));
        $tgl_skrg = date("Y-m-d");
        $tahun_skrg = date('Y');
        $bulan_skrg = date('m');
        $jmlh_bulan = cal_days_in_month(CAL_GREGORIAN,$bulan_skrg,$tahun_skrg);
        $tgl_mulai = date('Y-m-01');
        $tgl_akhir = date('Y-m-'.$jmlh_bulan);

        if($this->hasAdminAccess() && auth()->user()->is_admin == "admin"){
            // Data untuk grafik kehadiran bulanan
            $chartData = $this->getAttendanceChartData($tahun_skrg, $bulan_skrg);
            
            return view('dashboard.index', [
                'title' => 'Dashboard',
                'jumlah_user' => User::count(),
                'jumlah_masuk' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Masuk')->count(),
                'jumlah_libur' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Libur')->count(),
                'jumlah_cuti' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Cuti')->count(),
                'jumlah_sakit' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Sakit')->count(),
                'jumlah_izin_masuk' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Masuk')->count(),
                'jumlah_izin_telat' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Telat')->count(),
                'jumlah_izin_pulang_cepat' => MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Pulang Cepat')->count(),
                'jumlah_karyawan_lembur' => Lembur::where('tanggal', $tgl_skrg)->count(),
                'payroll' => Payroll::where('bulan', date('m'))->where('tahun', date('Y'))->sum('grand_total'),
                'kasbon' => Kasbon::whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->where('status', 'Acc')->sum('nominal'),
                'reimbursement' => Reimbursement::whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->where('status', 'Approved')->sum('total'),
                'chart_data' => $chartData
            ]);
        } else {
            $user_login = auth()->user()->id;
            $tanggal = "";
            $tglskrg = date('Y-m-d');
            $tglkmrn = date('Y-m-d', strtotime('-1 days'));
            $mapping_shift = MappingShift::where('user_id', $user_login)->where('tanggal', $tglkmrn)->get();
            if($mapping_shift->count() > 0) {
                foreach($mapping_shift as $mp) {
                    $jam_absen = $mp->jam_absen;
                    $jam_pulang = $mp->jam_pulang;
                }
            } else {
                $jam_absen = "-";
                $jam_pulang = "-";
            }
            if($jam_absen != null && $jam_pulang == null) {
                $tanggal = $tglkmrn;
            } else {
                $tanggal = $tglskrg;
            }

            $berita = Berita::where('tipe', 'Berita')->orderBy('id', 'DESC')->limit(4)->get();
            $informasi = Berita::where('tipe', 'Informasi')->orderBy('id', 'DESC')->limit(4)->get();
            return view('dashboard.indexUser', [
                'title' => 'Dashboard',
                'berita' => $berita,
                'informasi' => $informasi,
                'shift_karyawan' => MappingShift::where('user_id', $user_login)->where('tanggal', $tanggal)->first()
            ]);
        }
    }

    public function realtimeStats()
{
    if (!$this->hasAdminAccess()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    date_default_timezone_set(config('app.timezone'));

    $tgl_skrg    = date("Y-m-d");
    $tgl_kmrn    = date("Y-m-d", strtotime('-1 day'));
    $bulan_skrg  = date('m');
    $tahun_skrg  = date('Y');
    $jmlh_bulan  = cal_days_in_month(CAL_GREGORIAN, $bulan_skrg, $tahun_skrg);
    $tgl_mulai   = date('Y-m-01');
    $tgl_akhir   = date('Y-m-' . $jmlh_bulan);

    $jumlah_user = User::count();

    // ── Hari Ini ──────────────────────────────────────────────
    $masuk             = MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Masuk')->count();
    $izin_telat        = MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Telat')->count();
    $izin_pulang_cepat = MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Pulang Cepat')->count();
    $izin_masuk        = MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Izin Masuk')->count();
    $libur             = MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Libur')->count();
    $cuti              = MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Cuti')->count();
    $sakit             = MappingShift::where('tanggal', $tgl_skrg)->where('status_absen', 'Sakit')->count();
    $lembur            = Lembur::where('tanggal', $tgl_skrg)->count();

    $hadir_hari_ini = $masuk + $izin_telat + $izin_pulang_cepat;
    $tidak_hadir    = max(0, $jumlah_user - ($hadir_hari_ini + $libur + $cuti + $izin_masuk + $sakit));

    // ── Kemarin (untuk trend) ─────────────────────────────────
    $masuk_kmrn        = MappingShift::where('tanggal', $tgl_kmrn)->where('status_absen', 'Masuk')->count();
    $iz_telat_kmrn     = MappingShift::where('tanggal', $tgl_kmrn)->where('status_absen', 'Izin Telat')->count();
    $iz_pc_kmrn        = MappingShift::where('tanggal', $tgl_kmrn)->where('status_absen', 'Izin Pulang Cepat')->count();
    $iz_masuk_kmrn     = MappingShift::where('tanggal', $tgl_kmrn)->where('status_absen', 'Izin Masuk')->count();
    $libur_kmrn        = MappingShift::where('tanggal', $tgl_kmrn)->where('status_absen', 'Libur')->count();
    $cuti_kmrn         = MappingShift::where('tanggal', $tgl_kmrn)->where('status_absen', 'Cuti')->count();
    $sakit_kmrn        = MappingShift::where('tanggal', $tgl_kmrn)->where('status_absen', 'Sakit')->count();

    $hadir_kmrn     = $masuk_kmrn + $iz_telat_kmrn + $iz_pc_kmrn;
    $tdk_hadir_kmrn = max(0, $jumlah_user - ($hadir_kmrn + $libur_kmrn + $cuti_kmrn + $iz_masuk_kmrn + $sakit_kmrn));

    // ── Trend ─────────────────────────────────────────────────
    $trend_hadir = $hadir_kmrn > 0
        ? round((($hadir_hari_ini - $hadir_kmrn) / $hadir_kmrn) * 100, 1)
        : ($hadir_hari_ini > 0 ? 100 : 0);

    $trend_tidak_hadir = $tdk_hadir_kmrn > 0
        ? round((($tidak_hadir - $tdk_hadir_kmrn) / $tdk_hadir_kmrn) * 100, 1)
        : ($tidak_hadir > 0 ? 100 : 0);

    // Trend pegawai baru bulan ini vs bulan lalu
    $bulan_lalu       = date('m', strtotime('-1 month'));
    $tahun_bulan_lalu = date('Y', strtotime('-1 month'));
    $user_bln_lalu    = User::whereYear('created_at', $tahun_bulan_lalu)->whereMonth('created_at', $bulan_lalu)->count();
    $user_bln_ini     = User::whereYear('created_at', $tahun_skrg)->whereMonth('created_at', $bulan_skrg)->count();
    $trend_user       = $user_bln_lalu > 0
        ? round((($user_bln_ini - $user_bln_lalu) / $user_bln_lalu) * 100, 1)
        : ($user_bln_ini > 0 ? 100 : 0);

    // ── Financial (dari masing-masing controller-nya) ─────────
    $payroll       = Payroll::where('bulan', $bulan_skrg)->where('tahun', $tahun_skrg)->sum('grand_total');
    $kasbon        = Kasbon::whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->where('status', 'Acc')->sum('nominal');
    $reimbursement = Reimbursement::whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->where('status', 'Approved')->sum('total');

    // ── Chart ─────────────────────────────────────────────────
    $chartData = $this->getAttendanceChartData($tahun_skrg, $bulan_skrg);

    return response()->json([
        // Waktu
        'last_updated'          => date('H:i:s'),

        // Stat cards utama
        'jumlah_user'           => $jumlah_user,
        'hadir'                 => $hadir_hari_ini,
        'hadir_persen'          => $jumlah_user > 0 ? round(($hadir_hari_ini / $jumlah_user) * 100) : 0,
        'tidak_hadir'           => $tidak_hadir,
        'libur'                 => $libur,

        // Trend
        'trend_hadir'           => $trend_hadir,
        'trend_tidak_hadir'     => $trend_tidak_hadir,
        'trend_user'            => $trend_user,

        // Metric cards
        'lembur'                => $lembur,
        'cuti'                  => $cuti,
        'sakit'                 => $sakit,
        'izin'                  => $izin_masuk,
        'izin_telat'            => $izin_telat,
        'izin_pulang_cepat'     => $izin_pulang_cepat,

        // Financial
        'payroll_format'        => 'Rp ' . number_format($payroll, 0, ',', '.'),
        'kasbon_format'         => 'Rp ' . number_format($kasbon, 0, ',', '.'),
        'reimbursement_format'  => 'Rp ' . number_format($reimbursement, 0, ',', '.'),

        // Chart
        'chart_data'            => $chartData,
    ]);
}

    public function menu()
    {
        return view('dashboard.menu', [
            'title' => 'All Menu',
        ]);
    }

    /**
     * Get attendance chart data for the month
     */
    private function getAttendanceChartData($tahun, $bulan)
    {
        $jmlh_bulan = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
        $tgl_mulai = date('Y-m-01', strtotime("$tahun-$bulan-01"));
        $tgl_akhir = date('Y-m-' . $jmlh_bulan, strtotime("$tahun-$bulan-01"));
        
        // Generate array of dates in the month
        $dates = [];
        $hadir = [];
        $tidak_hadir = [];
        $libur = [];
        $cuti = [];
        $sakit = [];
        $izin = [];
        
        $total_user = User::count();
        
        for ($i = 1; $i <= $jmlh_bulan; $i++) {
            $date = date('Y-m-d', strtotime("$tahun-$bulan-$i"));
            $dates[] = date('d', strtotime($date));
            
            // Count each status for this date
            $masuk = MappingShift::where('tanggal', $date)->where('status_absen', 'Masuk')->count();
            $libur_count = MappingShift::where('tanggal', $date)->where('status_absen', 'Libur')->count();
            $cuti_count = MappingShift::where('tanggal', $date)->where('status_absen', 'Cuti')->count();
            $sakit_count = MappingShift::where('tanggal', $date)->where('status_absen', 'Sakit')->count();
            $izin_masuk = MappingShift::where('tanggal', $date)->where('status_absen', 'Izin Masuk')->count();
            $izin_telat = MappingShift::where('tanggal', $date)->where('status_absen', 'Izin Telat')->count();
            $izin_pulang_cepat = MappingShift::where('tanggal', $date)->where('status_absen', 'Izin Pulang Cepat')->count();
            $izin_total = $izin_masuk + $izin_telat + $izin_pulang_cepat;
            
            $hadir[] = $masuk;
            $libur[] = $libur_count;
            $cuti[] = $cuti_count;
            $sakit[] = $sakit_count;
            $izin[] = $izin_total;
            
            // Tidak hadir = total user - (hadir + libur + cuti + sakit + izin)
            $tidak_hadir[] = max(0, $total_user - ($masuk + $libur_count + $cuti_count + $sakit_count + $izin_total));
        }
        
        return [
            'labels' => $dates,
            'hadir' => $hadir,
            'tidak_hadir' => $tidak_hadir,
            'libur' => $libur,
            'cuti' => $cuti,
            'sakit' => $sakit,
            'izin' => $izin
        ];
    }
}
