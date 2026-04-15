@extends('templates.dashboard')
@section('isi')
<div class="modern-dashboard">
  <div class="container-fluid px-3 px-md-4 py-3">
    <!-- Modern Header with Clean Design -->
    <div class="dashboard-header mb-5">
      <div class="glass-card p-4">
        <div class="row align-items-center">
          <div class="col-lg-8">
            <div class="header-content">
              <div class="welcome-badge mb-3">
                <span class="badge-icon">👋</span>
                <span>Selamat datang kembali</span>
              </div>
              <h1 class="dashboard-title mb-2">Dashboard Overview</h1>
              <p class="dashboard-subtitle">Pantau aktivitas dan performa tim Anda secara real-time</p>
              <div class="mt-2">
              <span class="live-badge">
                <span class="live-dot"></span>
                Live · Diperbarui: <span id="last-updated">--:--:--</span>
              </span>
            </div>
            </div>
          </div>
          <div class="col-lg-4 text-lg-end">
            <div class="date-time-card">
              <div class="current-time">{{ date('H:i') }}</div>
              <div class="current-date">{{ date('d') }} @php
                  $bulanIndo = [
                      'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
                      'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
                      'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
                  ];
                  echo $bulanIndo[date('F')];
                @endphp {{ date('Y') }}</div>
              <div class="current-day">@php
                  $hariIndo = [
                      'Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu',
                      'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu',
                      'Sunday' => 'Minggu'
                  ];
                  echo $hariIndo[date('l')];
                @endphp</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modern Stats Grid -->
    <div class="stats-grid mb-5">
      <div class="row g-4">
        <div class="col-6 col-lg-3">
          <div class="modern-stat-card stat-primary" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-background bg-gradient-primary"></div>
            <div class="stat-content">
              <div class="stat-icon">
                <i data-feather="users"></i>
              </div>
              <div class="stat-info">
                <div class="stat-label">Total Pegawai</div>
               <div class="stat-value" id="val-total-pegawai">{{ $jumlah_user }}</div>
                <div class="stat-trend" id="trend-pegawai">
                  <i data-feather="trending-up"></i>
                  <span id="trend-pegawai-text">+0% bulan ini</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="modern-stat-card stat-success" data-aos="fade-up" data-aos-delay="200">
            <div class="stat-background bg-gradient-success"></div>
            <div class="stat-content">
              <div class="stat-icon">
                <i data-feather="check-circle"></i>
              </div>
              <div class="stat-info">
                <div class="stat-label">Hadir Hari Ini</div>
                <<div class="stat-value" id="val-hadir">{{ $jumlah_masuk + $jumlah_izin_telat + $jumlah_izin_pulang_cepat }}</div>
                <div class="stat-trend positive" id="trend-hadir">
                  <i data-feather="arrow-up"></i>
                  <span id="hadir-persen">{{ round((($jumlah_masuk + $jumlah_izin_telat + $jumlah_izin_pulang_cepat) / max(1,$jumlah_user)) * 100) }}%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="modern-stat-card stat-danger" data-aos="fade-up" data-aos-delay="300">
            <div class="stat-background bg-gradient-danger"></div>
            <div class="stat-content">
              <div class="stat-icon">
                <i data-feather="user-x"></i>
              </div>
              <div class="stat-info">
                <div class="stat-label">Tidak Hadir</div>
                <div class="stat-value" id="val-tidak-hadir">{{ $jumlah_user - ($jumlah_masuk + $jumlah_izin_telat + $jumlah_izin_pulang_cepat + $jumlah_libur + $jumlah_cuti + $jumlah_izin_masuk + $jumlah_sakit) }}</div>
                <div class="stat-trend negative" id="trend-tidak-hadir">
                  <i data-feather="arrow-down"></i>
                  <span id="trend-tidak-hadir-text">-0% vs kemarin</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-6 col-lg-3">
          <div class="modern-stat-card stat-info" data-aos="fade-up" data-aos-delay="400">
            <div class="stat-background bg-gradient-info"></div>
            <div class="stat-content">
              <div class="stat-icon">
                <i data-feather="calendar"></i>
              </div>
              <div class="stat-info">
                <div class="stat-label">Libur</div>
                <div class="stat-value" id="val-libur">{{ $jumlah_libur }}</div>
                <div class="stat-trend">
                  <i data-feather="calendar"></i>
                  <span>Hari ini</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detailed Metrics -->
    <div class="metrics-section mb-5">
      <div class="section-header mb-4">
        <h2 class="section-title">Analisis Kehadiran</h2>
        <p class="section-subtitle">Detail statistik kehadiran karyawan hari ini</p>
      </div>
      
      <div class="row g-3">
        <div class="col-6 col-md-4 col-xl-2">
          <div class="metric-card metric-warning" data-aos="zoom-in" data-aos-delay="100">
            <div class="metric-icon">
              <i data-feather="clock"></i>
            </div>
            <div class="metric-value" id="val-lembur">{{ $jumlah_karyawan_lembur }}</div>
            <div class="metric-label">Lembur</div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
          <div class="metric-card metric-purple" data-aos="zoom-in" data-aos-delay="150">
            <div class="metric-icon">
              <i data-feather="heart"></i>
            </div>
            <div class="metric-value" id="val-cuti">{{ $jumlah_cuti }}</div>
            <div class="metric-label">Cuti</div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
          <div class="metric-card metric-danger" data-aos="zoom-in" data-aos-delay="200">
            <div class="metric-icon">
              <i data-feather="thermometer"></i>
            </div>
            <div class="metric-value" id="val-sakit">{{ $jumlah_sakit }}</div>
            <div class="metric-label">Sakit</div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
          <div class="metric-card metric-info" data-aos="zoom-in" data-aos-delay="250">
            <div class="metric-icon">
              <i data-feather="umbrella"></i>
            </div>
            <div class="metric-value" id="val-izin">{{ $jumlah_izin_masuk }}</div>  {{-- fix bug: was $jumlah_cuti --}}
            <div class="metric-label">Izin</div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
          <div class="metric-card metric-orange" data-aos="zoom-in" data-aos-delay="300">
            <div class="metric-icon">
              <i data-feather="clock"></i>
            </div>
            <div class="metric-value" id="val-izin-telat">{{ $jumlah_izin_telat }}</div>
            <div class="metric-label">Terlambat</div>
          </div>
        </div>

        <div class="col-6 col-md-4 col-xl-2">
          <div class="metric-card metric-blue" data-aos="zoom-in" data-aos-delay="350">
            <div class="metric-icon">
              <i data-feather="log-out"></i>
            </div>
            <div class="metric-value" id="val-izin-pulang-cepat">{{ $jumlah_izin_pulang_cepat }}</div>
            <div class="metric-label">Pulang Cepat</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Financial Dashboard -->
    <div class="financial-section mb-5">
      <div class="section-header mb-4">
        <h2 class="section-title">Financial Overview</h2>
        <p class="section-subtitle">Ringkasan keuangan untuk periode @php
            $bulanPenuhIndo = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
                'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
                'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            echo $bulanPenuhIndo[date('F')];
          @endphp {{ date('Y') }}</p>
      </div>
      
      <div class="row g-4">
        <div class="col-12 col-md-6 col-xl-4">
          <div class="financial-card financial-primary" data-aos="fade-right" data-aos-delay="100">
            <div class="financial-background">
              <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
              </div>
            </div>
            <div class="financial-content">
              <div class="financial-header">
                <div class="financial-icon">
                  <i data-feather="dollar-sign"></i>
                </div>
                <div class="financial-trend">
                  <i data-feather="trending-up"></i>
                </div>
              </div>
              <div class="financial-info">
                <div class="financial-label" >Total Payroll</div>
                <div class="financial-value" id="val-payroll" >Rp {{ number_format($payroll) }}</div>
                <div class="financial-description">Gaji keseluruhan bulan ini</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-6 col-xl-4">
          <div class="financial-card financial-secondary" data-aos="fade-up" data-aos-delay="200">
            <div class="financial-background">
              <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
              </div>
            </div>
            <div class="financial-content">
              <div class="financial-header">
                <div class="financial-icon">
                  <i data-feather="credit-card"></i>
                </div>
                <div class="financial-trend">
                  <i data-feather="minus-circle"></i>
                </div>
              </div>
              <div class="financial-info">
                <div class="financial-label">Total Kasbon</div>
                <div class="financial-value" id="val-kasbon">Rp {{ number_format($kasbon) }}</div>
                <div class="financial-description">Pinjaman karyawan aktif</div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-12 col-md-12 col-xl-4">
          <div class="financial-card financial-accent" data-aos="fade-left" data-aos-delay="300">
            <div class="financial-background">
              <div class="floating-shapes">
                <div class="shape shape-1"></div>
                <div class="shape shape-2"></div>
                <div class="shape shape-3"></div>
              </div>
            </div>
            <div class="financial-content">
              <div class="financial-header">
                <div class="financial-icon">
                  <i data-feather="refresh-cw"></i>
                </div>
                <div class="financial-trend">
                  <i data-feather="arrow-up-right"></i>
                </div>
              </div>
              <div class="financial-info">
                <div class="financial-label">Reimbursement</div>
                <div class="financial-value" id="val-reimbursement">Rp {{ number_format($reimbursement) }}</div>
                <div class="financial-description">Penggantian biaya operasional</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Attendance Chart Section -->
    <div class="chart-section mb-5">
      <div class="glass-card p-4">
        <div class="section-header mb-4">
          <h2 class="section-title">Grafik Kehadiran Bulanan</h2>
          <p class="section-subtitle">Statistik kehadiran karyawan selama bulan @php
            $bulanPenuhIndo = [
                'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret', 'April' => 'April',
                'May' => 'Mei', 'June' => 'Juni', 'July' => 'Juli', 'August' => 'Agustus',
                'September' => 'September', 'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
            ];
            echo $bulanPenuhIndo[date('F')];
          @endphp {{ date('Y') }}</p>
        </div>
        <div class="chart-container">
          <canvas id="attendanceChart" style="width: 100%; height: 100%;"></canvas>
        </div>
      </div>
    </div>

    <!-- Modern Calendar -->
    <div class="calendar-section">
      <div class="glass-card p-0 overflow-hidden">
        <div class="calendar-header p-4">
          <div class="row align-items-center">
            <div class="col-md-8">
              <h2 class="calendar-title mb-1">Kalender Aktivitas</h2>
              <p class="calendar-subtitle mb-0">Timeline kegiatan dan event terbaru</p>
            </div>
            <div class="col-md-4 text-md-end">
              <div class="calendar-controls">
                <span class="event-badge">
                  <i data-feather="calendar"></i>
                  <span>Event Langsung</span>
                </span>
              </div>
            </div>
          </div>
        </div>
        <div class="calendar-body p-4">
          <div id="external-events-list"></div>
          <div id="calendar"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

:root {
  --dash-blue:    #3b4cca;
  --dash-blue-dk: #2d3db4;
  --slate-50:  #f8fafc; --slate-100: #f1f5f9; --slate-200: #e2e8f0;
  --slate-400: #94a3b8; --slate-500: #64748b; --slate-600: #475569;
  --slate-700: #334155; --slate-800: #1e293b; --slate-900: #0f172a;
}

*, *::before, *::after { box-sizing: border-box; }

/* ── Base ── */
.modern-dashboard {
  background:
    radial-gradient(circle at 10% 10%, rgba(59,76,202,0.07), transparent 40%),
    radial-gradient(circle at 90% 90%, rgba(59,76,202,0.05), transparent 40%),
    linear-gradient(180deg, #f6f8ff 0%, #eef2ff 100%);
  min-height: 100vh;
  font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
}

.container-fluid { position: relative; z-index: 2; }

/* ── Glass Card ── */
.glass-card {
  background: rgba(255,255,255,0.97);
  border: 1px solid rgba(59,76,202,0.1);
  border-radius: 20px;
  box-shadow: 0 4px 24px rgba(59,76,202,0.08), 0 1px 4px rgba(0,0,0,0.04);
}

/* ── Header ── */
.welcome-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(59,76,202,0.08); color: var(--dash-blue-dk);
  padding: 7px 15px; border-radius: 50px;
  font-size: 0.82rem; font-weight: 600;
  border: 1px solid rgba(59,76,202,0.18);
}
.badge-icon { font-size: 1rem; }

.dashboard-title {
  font-size: 2.25rem; font-weight: 800; letter-spacing: -0.03em;
  color: var(--slate-900);
  background: linear-gradient(135deg, #0f172a 0%, #334155 100%);
  -webkit-background-clip: text; -webkit-text-fill-color: transparent;
  background-clip: text;
}
.dashboard-subtitle { color: var(--slate-500); font-size: 1rem; font-weight: 400; }

.date-time-card { text-align: right; }
.current-time   { font-size: 2rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; }
.current-date   { font-size: 0.875rem; color: var(--slate-500); font-weight: 500; margin-top: 3px; }
.current-day    { font-size: 0.75rem; color: var(--slate-400); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; }

/* ── Live Badge ── */
.live-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(16,185,129,0.08); color: #065f46;
  padding: 5px 13px; border-radius: 50px;
  font-size: 0.78rem; font-weight: 600;
  border: 1px solid rgba(16,185,129,0.2);
}
.live-dot {
  width: 7px; height: 7px; background: #10b981; border-radius: 50%;
  animation: blink 1.5s infinite;
}
@keyframes blink {
  0%, 100% { opacity: 1; transform: scale(1); }
  50%       { opacity: 0.35; transform: scale(0.7); }
}

/* ── Stat Cards ── */
.modern-stat-card {
  position: relative; background: #fff;
  border: 1px solid rgba(59,76,202,0.1);
  border-radius: 16px; padding: 22px 22px 18px;
  overflow: hidden; transition: transform 0.25s, box-shadow 0.25s;
  cursor: pointer;
  box-shadow: 0 1px 4px rgba(59,76,202,0.06);
}
.modern-stat-card:hover { transform: translateY(-3px); }
.stat-primary:hover  { box-shadow: 0 12px 28px rgba(59,76,202,0.22); }
.stat-success:hover  { box-shadow: 0 12px 28px rgba(16,185,129,0.22); }
.stat-danger:hover   { box-shadow: 0 12px 28px rgba(239,68,68,0.22);  }
.stat-info:hover     { box-shadow: 0 12px 28px rgba(6,182,212,0.22);  }

.stat-background {
  position: absolute; top: 0; right: 0;
  width: 70px; height: 70px; border-radius: 50%; opacity: 0.1;
  transform: translate(30%, -30%); transition: all 0.3s;
}
.modern-stat-card:hover .stat-background { transform: translate(20%,-20%) scale(1.1); opacity: 0.15; }

.bg-gradient-primary { background: #3b4cca; }
.bg-gradient-success { background: #10b981; }
.bg-gradient-danger  { background: #ef4444; }
.bg-gradient-info    { background: #06b6d4; }

.stat-icon {
  width: 44px; height: 44px; border-radius: 11px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 14px;
}
.stat-primary .stat-icon { background: rgba(59,76,202,0.1); border: 1px solid rgba(59,76,202,0.18); }
.stat-success .stat-icon { background: rgba(16,185,129,0.1); border: 1px solid rgba(16,185,129,0.18); }
.stat-danger  .stat-icon { background: rgba(239,68,68,0.1);  border: 1px solid rgba(239,68,68,0.18);  }
.stat-info    .stat-icon { background: rgba(6,182,212,0.1);  border: 1px solid rgba(6,182,212,0.18);  }
.stat-icon i  { width: 22px; height: 22px; }

.stat-label { color: var(--slate-500); font-size: 0.82rem; font-weight: 500; margin-bottom: 6px; }
.stat-value { color: var(--slate-900); font-size: 1.9rem; font-weight: 800; letter-spacing: -0.02em; line-height: 1; margin-bottom: 7px; }
.stat-trend { display: flex; align-items: center; gap: 4px; font-size: 0.75rem; color: var(--slate-400); font-weight: 600; }
.stat-trend.positive { color: #059669; }
.stat-trend.negative { color: #dc2626; }
.stat-trend i { width: 13px; height: 13px; }

/* ── Section Header ── */
.section-header { text-align: center; }
.section-title  { font-size: 1.5rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.025em; margin-bottom: 6px; }
.section-subtitle { color: var(--slate-500); font-size: 0.9rem; }

/* ── Metric Cards ── */
.metric-card {
  background: #fff; border: 1px solid rgba(59,76,202,0.1);
  border-radius: 12px; padding: 18px 14px; text-align: center;
  transition: transform 0.2s, box-shadow 0.2s;
  cursor: pointer; position: relative; overflow: hidden;
  box-shadow: 0 1px 4px rgba(59,76,202,0.05);
}
.metric-card::before {
  content: ''; position: absolute; top: 0; left: 0; right: 0;
  height: 3px; border-radius: 3px 3px 0 0; opacity: 0.85;
}
.metric-card:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0,0,0,0.1); }

.metric-warning::before { background: #f59e0b; }
.metric-purple::before  { background: #8b5cf6; }
.metric-danger::before  { background: #ef4444; }
.metric-info::before    { background: #06b6d4; }
.metric-orange::before  { background: #f97316; }
.metric-blue::before    { background: #3b4cca; }

.metric-icon {
  width: 38px; height: 38px; border-radius: 10px; margin: 0 auto 10px;
  display: flex; align-items: center; justify-content: center;
}
.metric-warning .metric-icon { background: rgba(245,158,11,0.1); color: #f59e0b; }
.metric-purple  .metric-icon { background: rgba(139,92,246,0.1);  color: #8b5cf6; }
.metric-danger  .metric-icon { background: rgba(239,68,68,0.1);   color: #ef4444; }
.metric-info    .metric-icon { background: rgba(6,182,212,0.1);   color: #06b6d4; }
.metric-orange  .metric-icon { background: rgba(249,115,22,0.1);  color: #f97316; }
.metric-blue    .metric-icon { background: rgba(59,76,202,0.1);   color: #3b4cca; }
.metric-icon i  { width: 19px; height: 19px; }

.metric-value { font-size: 1.45rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; }
.metric-label { font-size: 0.78rem; color: var(--slate-500); font-weight: 500; margin-top: 3px; }

/* ── Financial Cards ── */
.financial-card {
  position: relative; border-radius: 20px; padding: 28px;
  overflow: hidden; transition: transform 0.25s, box-shadow 0.25s;
  cursor: pointer; min-height: 240px; color: #fff;
}
.financial-card:hover { transform: translateY(-3px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
.financial-primary   { background: linear-gradient(135deg, #3b4cca 0%, #2d3db4 100%); }
.financial-secondary { background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); }
.financial-accent    { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }

.financial-background { position: absolute; inset: 0; overflow: hidden; }
.floating-shapes { position: absolute; width: 100%; height: 100%; }
.shape { position: absolute; border-radius: 50%; background: rgba(255,255,255,0.12); animation: float 6s ease-in-out infinite; }
.shape-1 { width: 90px;  height: 90px;  top: 15%; right: 8%;   animation-delay: 0s;  }
.shape-2 { width: 130px; height: 130px; bottom: 15%; left: -25%; animation-delay: 2s;  }
.shape-3 { width: 65px;  height: 65px;  top: 55%; right: -12%;  animation-delay: 4s;  }
@keyframes float {
  0%, 100% { transform: translateY(0) rotate(0); }
  50%       { transform: translateY(-18px) rotate(180deg); }
}

.financial-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; }
.financial-header  { display: flex; justify-content: space-between; align-items: center; margin-bottom: 22px; }
.financial-icon {
  width: 52px; height: 52px; background: rgba(255,255,255,0.22);
  border-radius: 13px; display: flex; align-items: center; justify-content: center;
  backdrop-filter: blur(8px); border: 1px solid rgba(255,255,255,0.28);
}
.financial-icon i  { color: #fff; width: 26px; height: 26px; }
.financial-trend i { color: rgba(255,255,255,0.8); width: 22px; height: 22px; }
.financial-info    { flex: 1; display: flex; flex-direction: column; justify-content: flex-end; }
.financial-label   { color: rgba(255,255,255,0.88); font-size: 0.875rem; font-weight: 500; margin-bottom: 7px; }
.financial-value   { color: #fff; font-size: 1.65rem; font-weight: 800; letter-spacing: -0.025em; line-height: 1.1; margin-bottom: 7px; }
.financial-description { color: rgba(255,255,255,0.72); font-size: 0.8rem; }

/* ── Chart Section ── */
.chart-container {
  position: relative; height: 260px; padding: 14px;
  background: rgba(59,76,202,0.02); border-radius: 12px;
  border: 1px solid rgba(59,76,202,0.07);
}

/* ── Calendar ── */
.calendar-header {
  background: rgba(248,250,252,0.9);
  border-bottom: 1px solid rgba(59,76,202,0.1);
  border-radius: 20px 20px 0 0;
}
.calendar-title    { color: var(--slate-900); font-size: 1.2rem; font-weight: 800; letter-spacing: -0.02em; }
.calendar-subtitle { color: var(--slate-500); font-size: 0.85rem; }
.event-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(59,76,202,0.08); color: var(--dash-blue-dk);
  padding: 6px 14px; border-radius: 50px;
  font-size: 0.78rem; font-weight: 600;
  border: 1px solid rgba(59,76,202,0.18);
}
.event-badge i { width: 15px; height: 15px; }
.calendar-body { background: rgba(255,255,255,0.5); }

/* FullCalendar overrides */
.fc-toolbar-title { color: var(--slate-900) !important; font-size: 1.25rem !important; font-weight: 800 !important; letter-spacing: -0.02em !important; }
.fc-button {
  background: #fff !important; border: 1px solid rgba(59,76,202,0.2) !important;
  color: var(--dash-blue) !important; border-radius: 8px !important;
  padding: 7px 15px !important; font-weight: 600 !important;
  font-family: 'Plus Jakarta Sans', sans-serif !important;
  transition: all 0.2s !important;
}
.fc-button:hover { background: rgba(59,76,202,0.06) !important; border-color: var(--dash-blue) !important; transform: translateY(-1px); }
.fc-button-active { background: var(--dash-blue) !important; border-color: var(--dash-blue) !important; color: #fff !important; }
.fc-day-today    { background: rgba(59,76,202,0.05) !important; }
.fc-col-header-cell { background: rgba(59,76,202,0.04) !important; color: var(--dash-blue) !important; font-weight: 700 !important; border-color: rgba(59,76,202,0.1) !important; }
.fc-daygrid-day-number { color: var(--slate-700) !important; font-weight: 600 !important; }
.fc-event { border-radius: 6px !important; border: none !important; font-weight: 600 !important; font-size: 0.78rem !important; padding: 2px 6px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }

/* ── Responsive ── */
@media (max-width: 768px) {
  .dashboard-title { font-size: 1.75rem; }
  .section-title   { font-size: 1.25rem; }
  .financial-value { font-size: 1.3rem; }
  .stat-value      { font-size: 1.5rem; }
  .current-time    { font-size: 1.5rem; }
  .date-time-card  { text-align: left; margin-top: 1rem; }
  .financial-card  { min-height: 190px; padding: 22px; }
  .chart-container { height: 210px; }
}
@media (max-width: 576px) {
  .dashboard-title { font-size: 1.5rem; }
  .financial-value { font-size: 1.1rem; }
  .stat-value      { font-size: 1.25rem; }
  .metric-value    { font-size: 1.2rem; }
}

/* ── Realtime flash ── */
.rt-updating { opacity: 0.3; transition: opacity 0.15s; }
.rt-flash    { color: var(--dash-blue) !important; transition: color 0.7s ease; }
</style>

@push('script')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>

<script>
AOS.init({ duration: 800, easing: 'ease-out-cubic', once: true, offset: 100 });

// ─────────────────────────────────────────────
// CHART — inisialisasi sekali, update via ref
// ─────────────────────────────────────────────
let attendanceChart = null;

document.addEventListener("DOMContentLoaded", function () {

  @if(isset($chart_data))
  const ctx = document.getElementById('attendanceChart');
  if (ctx) {
    attendanceChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($chart_data['labels']) !!},
        datasets: [
          { label: 'Hadir',        data: {!! json_encode($chart_data['hadir']) !!},       borderColor: '#10b981', backgroundColor: 'rgba(16,185,129,0.08)', borderWidth: 2, fill: false, tension: 0.4, pointRadius: 3, pointHoverRadius: 5 },
          { label: 'Tidak Hadir',  data: {!! json_encode($chart_data['tidak_hadir']) !!},  borderColor: '#ef4444', backgroundColor: 'rgba(239,68,68,0.08)',   borderWidth: 2, fill: false, tension: 0.4, pointRadius: 3, pointHoverRadius: 5 },
          { label: 'Libur',        data: {!! json_encode($chart_data['libur']) !!},        borderColor: '#06b6d4', backgroundColor: 'rgba(6,182,212,0.08)',    borderWidth: 2, fill: false, tension: 0.4, pointRadius: 3, pointHoverRadius: 5 },
          { label: 'Cuti',         data: {!! json_encode($chart_data['cuti']) !!},         borderColor: '#8b5cf6', backgroundColor: 'rgba(139,92,246,0.08)',   borderWidth: 2, fill: false, tension: 0.4, pointRadius: 3, pointHoverRadius: 5 },
          { label: 'Sakit',        data: {!! json_encode($chart_data['sakit']) !!},        borderColor: '#f59e0b', backgroundColor: 'rgba(245,158,11,0.08)',   borderWidth: 2, fill: false, tension: 0.4, pointRadius: 3, pointHoverRadius: 5 },
          { label: 'Izin',         data: {!! json_encode($chart_data['izin']) !!},         borderColor: '#f97316', backgroundColor: 'rgba(249,115,22,0.08)',   borderWidth: 2, fill: false, tension: 0.4, pointRadius: 3, pointHoverRadius: 5 },
        ]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
          legend: { display: true, position: 'top', labels: { usePointStyle: true, padding: 15, font: { size: 12, weight: '500' } } },
          tooltip: { mode: 'index', intersect: false, backgroundColor: 'rgba(255,255,255,0.95)', titleColor: '#1f2937', bodyColor: '#374151', borderColor: '#e5e7eb', borderWidth: 1, padding: 12, boxPadding: 6 }
        },
        scales: {
          x: { grid: { color: 'rgba(229,231,235,0.5)', drawBorder: false }, ticks: { font: { size: 11 }, color: '#6b7280' } },
          y: { beginAtZero: true, grid: { color: 'rgba(229,231,235,0.5)', drawBorder: false }, ticks: { font: { size: 11 }, color: '#6b7280', stepSize: 1 } }
        },
        interaction: { mode: 'nearest', axis: 'x', intersect: false }
      }
    });
  }
  @endif

  // ── Calendar ────────────────────────────────
  var date = new Date();
  var y = date.getFullYear();

  var containerEl = document.getElementById("external-events-list");
  if (containerEl) {
    new FullCalendar.Draggable(containerEl, {
      itemSelector: ".fc-event",
      eventData: function (eventEl) { return { title: eventEl.innerText.trim() }; }
    });
  }

  var calendarEl = document.getElementById("calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: "prev,next today", center: "title",
      right: window.innerWidth > 768 ? "dayGridMonth,timeGridWeek,timeGridDay,listWeek" : "dayGridMonth,listWeek"
    },
    initialView: "dayGridMonth", navLinks: true, editable: true, selectable: true,
    nowIndicator: true, height: 'auto',
    aspectRatio: window.innerWidth > 768 ? 1.8 : 1.2,
    locale: 'id',
    buttonText: { today: 'Hari Ini', month: 'Bulan', week: 'Minggu', day: 'Hari', list: 'Agenda' },
    events: [
      @php
        $tahun_skrg  = date('Y');
        $bulan_skrg  = date('m');
        $jmlh_bulan  = cal_days_in_month(CAL_GREGORIAN, $bulan_skrg, $tahun_skrg);
        $tgl_mulai   = date('1945-01-01');
        $tgl_akhir   = date('Y-m-' . $jmlh_bulan);

        $data_user             = App\Models\User::select('name','tgl_lahir')->whereBetween('tgl_lahir', [$tgl_mulai, $tgl_akhir])->get();
        $data_sakit            = App\Models\MappingShift::where('status_absen','Sakit')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_cuti             = App\Models\MappingShift::where('status_absen','Cuti')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_izin_masuk       = App\Models\MappingShift::where('status_absen','Izin Masuk')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_izin_telat       = App\Models\MappingShift::where('status_absen','Izin Telat')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_izin_pulang_cepat = App\Models\MappingShift::where('status_absen','Izin Pulang Cepat')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
      @endphp

      @foreach($data_user as $du)
        @php $p = explode("-", $du->tgl_lahir) @endphp
        { title: '🎂 {{ $du->name }}', start: new Date(y, {{ $p[1]-1 }}, {{ $p[2] }}), allDay: true, backgroundColor: '#f59e0b', borderColor: '#f59e0b', textColor: '#000' },
      @endforeach
      @foreach($data_sakit as $ds)
        @php $p = explode("-", $ds->tanggal) @endphp
        { title: '🤒 {{ $ds->User->name }}', start: new Date({{ $p[0] }}, {{ $p[1]-1 }}, {{ $p[2] }}), allDay: true, backgroundColor: '#ef4444', borderColor: '#ef4444' },
      @endforeach
      @foreach($data_cuti as $dc)
        @php $p = explode("-", $dc->tanggal) @endphp
        { title: '🏖️ {{ $dc->User->name }}', start: new Date({{ $p[0] }}, {{ $p[1]-1 }}, {{ $p[2] }}), allDay: true, backgroundColor: '#8b5cf6', borderColor: '#8b5cf6' },
      @endforeach
      @foreach($data_izin_masuk as $dim)
        @php $p = explode("-", $dim->tanggal) @endphp
        { title: '📝 {{ $dim->User->name }}', start: new Date({{ $p[0] }}, {{ $p[1]-1 }}, {{ $p[2] }}), allDay: true, backgroundColor: '#06b6d4', borderColor: '#06b6d4' },
      @endforeach
      @foreach($data_izin_telat as $dit)
        @php $p = explode("-", $dit->tanggal) @endphp
        { title: '⏰ {{ $dit->User->name }}', start: new Date({{ $p[0] }}, {{ $p[1]-1 }}, {{ $p[2] }}), allDay: true, backgroundColor: '#f97316', borderColor: '#f97316' },
      @endforeach
      @foreach($data_izin_pulang_cepat as $dipc)
        @php $p = explode("-", $dipc->tanggal) @endphp
        { title: '🏃 {{ $dipc->User->name }}', start: new Date({{ $p[0] }}, {{ $p[1]-1 }}, {{ $p[2] }}), allDay: true, backgroundColor: '#10b981', borderColor: '#10b981' },
      @endforeach
    ],
    droppable: true,
    drop: function (arg) {
      if (document.getElementById("drop-remove")?.checked) {
        arg.draggedEl.parentNode.removeChild(arg.draggedEl);
      }
    }
  });

  window.addEventListener('resize', function () {
    calendar.setOption('aspectRatio', window.innerWidth > 768 ? 1.8 : 1.2);
    calendar.setOption('headerToolbar', {
      left: "prev,next today", center: "title",
      right: window.innerWidth > 768 ? "dayGridMonth,timeGridWeek,timeGridDay,listWeek" : "dayGridMonth,listWeek"
    });
  });

  calendar.render();

  // ─────────────────────────────────────────────
  // REALTIME POLLING
  // ─────────────────────────────────────────────
  const INTERVAL_MS = 30000; // 30 detik

  function setEl(id, val) {
    const el = document.getElementById(id);
    if (!el || el.textContent == val) return;
    el.classList.add('rt-updating');
    setTimeout(() => {
      el.textContent = val;
      el.classList.remove('rt-updating');
      el.classList.add('rt-flash');
      setTimeout(() => el.classList.remove('rt-flash'), 700);
    }, 150);
  }

  function setTrend(wrapperId, textId, value, suffix) {
    const wrapper = document.getElementById(wrapperId);
    const span    = document.getElementById(textId);
    if (!wrapper || !span) return;
    wrapper.classList.remove('positive', 'negative');
    if (value > 0) {
      wrapper.classList.add('positive');
      span.textContent = `+${value}% ${suffix}`;
    } else if (value < 0) {
      wrapper.classList.add('negative');
      span.textContent = `${value}% ${suffix}`;
    } else {
      span.textContent = `Stabil ${suffix}`;
    }
  }

  function updateClock() {
    const now = new Date();
    const hh  = String(now.getHours()).padStart(2,'0');
    const mm  = String(now.getMinutes()).padStart(2,'0');
    const ss  = String(now.getSeconds()).padStart(2,'0');
    const el  = document.querySelector('.current-time');
    if (el) el.textContent = `${hh}:${mm}:${ss}`;
  }

  function updateChart(data) {
    if (!attendanceChart || !data) return;
    attendanceChart.data.labels           = data.labels;
    attendanceChart.data.datasets[0].data = data.hadir;
    attendanceChart.data.datasets[1].data = data.tidak_hadir;
    attendanceChart.data.datasets[2].data = data.libur;
    attendanceChart.data.datasets[3].data = data.cuti;
    attendanceChart.data.datasets[4].data = data.sakit;
    attendanceChart.data.datasets[5].data = data.izin;
    attendanceChart.update('none');
  }

  async function fetchStats() {
    try {
      const res  = await fetch('{{ route("dashboard.realtime") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!res.ok) throw new Error(res.status);
      const d = await res.json();

      // Stat cards
      setEl('val-total-pegawai', d.jumlah_user);
      setEl('val-hadir',         d.hadir);
      setEl('val-tidak-hadir',   d.tidak_hadir);
      setEl('val-libur',         d.libur);
      setEl('hadir-persen',      d.hadir_persen + '%');

      // Trends
      setTrend('trend-hadir',        'hadir-persen',           d.trend_hadir,       'vs kemarin');
      setTrend('trend-tidak-hadir',  'trend-tidak-hadir-text', d.trend_tidak_hadir, 'vs kemarin');
      setTrend('trend-pegawai',      'trend-pegawai-text',     d.trend_user,        'bulan ini');

      // Metric cards
      setEl('val-lembur',            d.lembur);
      setEl('val-cuti',              d.cuti);
      setEl('val-sakit',             d.sakit);
      setEl('val-izin',              d.izin);
      setEl('val-izin-telat',        d.izin_telat);
      setEl('val-izin-pulang-cepat', d.izin_pulang_cepat);

      // Financial
      setEl('val-payroll',        d.payroll_format);
      setEl('val-kasbon',         d.kasbon_format);
      setEl('val-reimbursement',  d.reimbursement_format);

      // Chart & timestamp
      updateChart(d.chart_data);
      setEl('last-updated', d.last_updated);

    } catch (err) {
      console.warn('[Dashboard] Polling gagal:', err);
    }
  }

  // Jam update tiap detik, data tiap 30 detik
  setInterval(updateClock, 1000);
  setInterval(fetchStats, INTERVAL_MS);
  setTimeout(fetchStats, 3000); // fetch pertama 3 detik setelah load
});
</script>

<style>
  /* Live badge */
  .live-badge {
    display: inline-flex; align-items: center; gap: 8px;
    background: rgba(16,185,129,0.1); color: #065f46;
    padding: 5px 14px; border-radius: 50px;
    font-size: 0.8rem; font-weight: 500;
    border: 1px solid rgba(16,185,129,0.25);
  }
  .live-dot {
    width: 8px; height: 8px; background: #10b981;
    border-radius: 50%; flex-shrink: 0;
    animation: blink 1.5s infinite;
  }
  @keyframes blink {
    0%,100% { opacity:1; transform:scale(1); }
    50%      { opacity:0.4; transform:scale(0.7); }
  }

  /* Animasi update nilai */
  .rt-updating { opacity: 0.3; transition: opacity 0.15s; }
  .rt-flash    { color: #10b981 !important; transition: color 0.7s ease; }

  @keyframes fadeInUp {
    from { opacity:0; transform:translateY(30px); }
    to   { opacity:1; transform:translateY(0); }
  }
</style>
@endpush

<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
@endsection
