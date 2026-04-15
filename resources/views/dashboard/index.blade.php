@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap');

:root {
  --dash-blue: #3b4cca;
  --dash-blue-dk: #2d3db4;
  --slate-50: #f8fafc; --slate-100: #f1f5f9; --slate-200: #e2e8f0;
  --slate-400: #94a3b8; --slate-500: #64748b; --slate-600: #475569;
  --slate-700: #334155; --slate-800: #1e293b; --slate-900: #0f172a;
}

*, *::before, *::after { box-sizing: border-box; }

/* ── Page base ── */
.modern-dashboard {
  background: #f1f5f9;
  min-height: 100vh;
  font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
  padding: 24px 28px;
  display: flex;
  flex-direction: column;
  gap: 20px;
}

/* ── Card base ── */
.dash-card {
  background: #fff;
  border: 1px solid rgba(59,76,202,0.1);
  border-radius: 20px;
  box-shadow: 0 2px 12px rgba(59,76,202,0.06);
}

/* ── HEADER CARD ── */
.header-card {
  padding: 28px 32px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 16px;
}

.welcome-badge {
  display: inline-flex; align-items: center; gap: 8px;
  background: rgba(59,76,202,0.07); color: var(--dash-blue-dk);
  padding: 6px 14px; border-radius: 50px;
  font-size: 0.8rem; font-weight: 600;
  border: 1px solid rgba(59,76,202,0.15);
  margin-bottom: 12px;
}

.dashboard-title {
  font-size: 2rem; font-weight: 800; letter-spacing: -0.03em;
  color: var(--slate-900); margin-bottom: 5px;
}
.dashboard-subtitle { color: var(--slate-500); font-size: 0.95rem; }

.live-badge {
  display: inline-flex; align-items: center; gap: 7px;
  background: rgba(16,185,129,0.08); color: #065f46;
  padding: 5px 13px; border-radius: 50px;
  font-size: 0.78rem; font-weight: 600;
  border: 1px solid rgba(16,185,129,0.2);
  margin-top: 10px;
}
.live-dot {
  width: 7px; height: 7px; background: #10b981; border-radius: 50%;
  animation: blink 1.5s infinite;
}
@keyframes blink { 0%,100%{opacity:1;transform:scale(1)} 50%{opacity:.35;transform:scale(.7)} }

.date-time-block { text-align: right; }
.current-time { font-size: 2.2rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; }
.current-date { font-size: 0.875rem; color: var(--slate-500); font-weight: 500; margin-top: 3px; }
.current-day { font-size: 0.72rem; color: var(--slate-400); font-weight: 600; letter-spacing: 0.08em; text-transform: uppercase; }

/* ── SECTION LABEL ── */
.section-label {
  font-size: 0.7rem; font-weight: 700; color: var(--slate-400);
  text-transform: uppercase; letter-spacing: 0.1em; margin-bottom: 16px;
}

/* ── STAT CARD ── */
.stats-card { padding: 24px 28px; }

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
}

.stat-item {
  border-radius: 12px; padding: 16px;
  border: 1px solid transparent;
  cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;
}
.stat-item:hover { transform: translateY(-2px); }
.si-blue   { background: rgba(59,76,202,0.05);  border-color: rgba(59,76,202,0.12); }
.si-green  { background: rgba(16,185,129,0.05); border-color: rgba(16,185,129,0.12); }
.si-red    { background: rgba(239,68,68,0.05);  border-color: rgba(239,68,68,0.12); }
.si-cyan   { background: rgba(6,182,212,0.05);  border-color: rgba(6,182,212,0.12); }
.si-blue:hover  { box-shadow: 0 8px 24px rgba(59,76,202,0.15); }
.si-green:hover { box-shadow: 0 8px 24px rgba(16,185,129,0.15); }
.si-red:hover   { box-shadow: 0 8px 24px rgba(239,68,68,0.15); }
.si-cyan:hover  { box-shadow: 0 8px 24px rgba(6,182,212,0.15); }

.stat-icon-wrap {
  width: 40px; height: 40px; border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 12px;
}
.stat-icon-wrap i { width: 20px; height: 20px; }
.ic-blue   { background: rgba(59,76,202,0.1);  color: #3b4cca; border: 1px solid rgba(59,76,202,0.15); }
.ic-green  { background: rgba(16,185,129,0.1); color: #10b981; border: 1px solid rgba(16,185,129,0.15); }
.ic-red    { background: rgba(239,68,68,0.1);  color: #ef4444; border: 1px solid rgba(239,68,68,0.15); }
.ic-cyan   { background: rgba(6,182,212,0.1);  color: #06b6d4; border: 1px solid rgba(6,182,212,0.15); }

.stat-lbl { color: var(--slate-500); font-size: 0.78rem; font-weight: 500; margin-bottom: 4px; }
.stat-val { color: var(--slate-900); font-size: 1.85rem; font-weight: 800; letter-spacing: -0.02em; line-height: 1; margin-bottom: 6px; }
.stat-trend { font-size: 0.72rem; color: var(--slate-400); font-weight: 600; display: flex; align-items: center; gap: 3px; }
.stat-trend i { width: 12px; height: 12px; }
.stat-trend.pos { color: #059669; }
.stat-trend.neg { color: #dc2626; }

/* ── METRICS CARD ── */
.metrics-card { padding: 24px 28px; }

.metrics-grid {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: 10px;
}

.metric-item {
  border-radius: 10px; padding: 14px 10px;
  text-align: center; cursor: pointer;
  border: 1px solid transparent;
  position: relative; overflow: hidden;
  transition: transform 0.2s, box-shadow 0.2s;
}
.metric-item::before {
  content: ''; position: absolute;
  top: 0; left: 0; right: 0; height: 3px;
}
.metric-item:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(0,0,0,0.08); }

.mi-amber  { background: rgba(245,158,11,0.05);  border-color: rgba(245,158,11,0.15); }
.mi-purple { background: rgba(139,92,246,0.05);  border-color: rgba(139,92,246,0.15); }
.mi-red    { background: rgba(239,68,68,0.05);   border-color: rgba(239,68,68,0.15); }
.mi-cyan   { background: rgba(6,182,212,0.05);   border-color: rgba(6,182,212,0.15); }
.mi-orange { background: rgba(249,115,22,0.05);  border-color: rgba(249,115,22,0.15); }
.mi-blue   { background: rgba(59,76,202,0.05);   border-color: rgba(59,76,202,0.15); }

.mi-amber::before  { background: #f59e0b; }
.mi-purple::before { background: #8b5cf6; }
.mi-red::before    { background: #ef4444; }
.mi-cyan::before   { background: #06b6d4; }
.mi-orange::before { background: #f97316; }
.mi-blue::before   { background: #3b4cca; }

.metric-icon-wrap {
  width: 36px; height: 36px; border-radius: 9px;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 8px;
}
.metric-icon-wrap i { width: 17px; height: 17px; }
.mico-amber  { background: rgba(245,158,11,0.1);  color: #f59e0b; }
.mico-purple { background: rgba(139,92,246,0.1);  color: #8b5cf6; }
.mico-red    { background: rgba(239,68,68,0.1);   color: #ef4444; }
.mico-cyan   { background: rgba(6,182,212,0.1);   color: #06b6d4; }
.mico-orange { background: rgba(249,115,22,0.1);  color: #f97316; }
.mico-blue   { background: rgba(59,76,202,0.1);   color: #3b4cca; }

.metric-val { font-size: 1.4rem; font-weight: 800; color: var(--slate-900); letter-spacing: -0.02em; }
.metric-lbl { font-size: 0.72rem; color: var(--slate-500); font-weight: 500; margin-top: 3px; }

/* ── FINANCIAL GRID ── */
.financial-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}

.fin-card {
  border-radius: 20px; padding: 26px;
  overflow: hidden; position: relative;
  min-height: 210px; cursor: pointer; color: #fff;
  transition: transform 0.25s, box-shadow 0.25s;
}
.fin-card:hover { transform: translateY(-3px); box-shadow: 0 18px 40px rgba(0,0,0,0.18); }
.fin-blue  { background: linear-gradient(135deg, #3b4cca 0%, #2d3db4 100%); }
.fin-pink  { background: linear-gradient(135deg, #ec4899 0%, #be185d 100%); }
.fin-teal  { background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); }

.fin-blob {
  position: absolute; border-radius: 50%;
  background: rgba(255,255,255,0.1); pointer-events: none;
  animation: float 6s ease-in-out infinite;
}
.fin-blob-1 { width: 90px;  height: 90px;  top: -20px; right: -20px; animation-delay: 0s; }
.fin-blob-2 { width: 140px; height: 140px; bottom: -40px; left: -40px; animation-delay: 2s; }
.fin-blob-3 { width: 65px;  height: 65px;  top: 55%; right: -12%; animation-delay: 4s; }
@keyframes float { 0%,100%{transform:translateY(0)} 50%{transform:translateY(-14px)} }

.fin-content { position: relative; z-index: 2; height: 100%; display: flex; flex-direction: column; }
.fin-icon-wrap {
  width: 50px; height: 50px;
  background: rgba(255,255,255,0.2);
  border-radius: 13px; display: flex; align-items: center; justify-content: center;
  border: 1px solid rgba(255,255,255,0.25);
}
.fin-icon-wrap i { color: #fff; width: 24px; height: 24px; }
.fin-lbl  { color: rgba(255,255,255,0.88); font-size: 0.85rem; font-weight: 500; margin-top: auto; margin-bottom: 6px; padding-top: 20px; }
.fin-val  { color: #fff; font-size: 1.55rem; font-weight: 800; letter-spacing: -0.025em; line-height: 1.1; margin-bottom: 5px; }
.fin-desc { color: rgba(255,255,255,0.7); font-size: 0.78rem; }

/* ── CHART + CALENDAR ROW ── */
.bottom-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.chart-card, .calendar-card { padding: 24px 28px; }

.card-title { font-size: 1rem; font-weight: 700; color: var(--slate-900); letter-spacing: -0.02em; margin-bottom: 3px; }
.card-sub   { font-size: 0.82rem; color: var(--slate-500); margin-bottom: 16px; }

.chart-inner {
  position: relative; height: 260px; padding: 10px;
  background: rgba(59,76,202,0.02);
  border-radius: 12px;
  border: 1px solid rgba(59,76,202,0.07);
}

/* ── CALENDAR HEADER inside card ── */
.cal-card-header {
  display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px;
}
.event-badge {
  display: inline-flex; align-items: center; gap: 6px;
  background: rgba(59,76,202,0.07); color: var(--dash-blue-dk);
  padding: 5px 12px; border-radius: 50px;
  font-size: 0.75rem; font-weight: 600;
  border: 1px solid rgba(59,76,202,0.15);
}
.event-badge i { width: 13px; height: 13px; }

/* FullCalendar overrides */
.fc-toolbar-title { color: var(--slate-900) !important; font-size: 1rem !important; font-weight: 800 !important; letter-spacing: -0.02em !important; }
.fc-button {
  background: #fff !important; border: 1px solid rgba(59,76,202,0.2) !important;
  color: var(--dash-blue) !important; border-radius: 8px !important;
  padding: 5px 12px !important; font-weight: 600 !important; font-size: 0.78rem !important;
  font-family: 'Plus Jakarta Sans', sans-serif !important; transition: all 0.2s !important;
}
.fc-button:hover { background: rgba(59,76,202,0.06) !important; border-color: var(--dash-blue) !important; transform: translateY(-1px); }
.fc-button-active { background: var(--dash-blue) !important; border-color: var(--dash-blue) !important; color: #fff !important; }
.fc-day-today { background: rgba(59,76,202,0.05) !important; }
.fc-col-header-cell { background: rgba(59,76,202,0.03) !important; color: var(--dash-blue) !important; font-weight: 700 !important; border-color: rgba(59,76,202,0.08) !important; }
.fc-daygrid-day-number { color: var(--slate-700) !important; font-weight: 600 !important; }
.fc-event { border-radius: 5px !important; border: none !important; font-weight: 600 !important; font-size: 0.73rem !important; padding: 2px 5px !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }

/* ── Realtime ── */
.rt-updating { opacity: 0.3; transition: opacity 0.15s; }
.rt-flash    { color: var(--dash-blue) !important; transition: color 0.7s ease; }

/* ── Responsive ── */
@media (max-width: 1024px) {
  .stats-grid    { grid-template-columns: repeat(2, 1fr); }
  .metrics-grid  { grid-template-columns: repeat(3, 1fr); }
  .financial-grid { grid-template-columns: repeat(2, 1fr); }
  .bottom-row    { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
  .modern-dashboard { padding: 16px; gap: 14px; }
  .header-card { padding: 20px; }
  .stats-card, .metrics-card, .chart-card, .calendar-card { padding: 18px; }
  .stats-grid    { grid-template-columns: repeat(2, 1fr); }
  .metrics-grid  { grid-template-columns: repeat(3, 1fr); }
  .financial-grid { grid-template-columns: 1fr; }
  .dashboard-title { font-size: 1.5rem; }
  .fin-val { font-size: 1.25rem; }
}
</style>

<div class="modern-dashboard">

  {{-- ═══════════════════════════════════
       1. HEADER CARD
  ═══════════════════════════════════ --}}
  <div class="dash-card header-card">
    <div>
      <div class="welcome-badge">
        <span>👋</span>
        <span>Selamat datang kembali</span>
      </div>
      <h1 class="dashboard-title">Dashboard Overview</h1>
      <p class="dashboard-subtitle">Pantau aktivitas dan performa tim Anda secara real-time</p>
      <div class="live-badge">
        <span class="live-dot"></span>
        Live · Diperbarui: <span id="last-updated">--:--:--</span>
      </div>
    </div>
    <div class="date-time-block">
      <div class="current-time">{{ date('H:i') }}</div>
      <div class="current-date">
        {{ date('d') }}
        @php
          $bulanIndo = ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'];
          echo $bulanIndo[date('F')];
        @endphp
        {{ date('Y') }}
      </div>
      <div class="current-day">
        @php
          $hariIndo = ['Monday'=>'Senin','Tuesday'=>'Selasa','Wednesday'=>'Rabu','Thursday'=>'Kamis','Friday'=>'Jumat','Saturday'=>'Sabtu','Sunday'=>'Minggu'];
          echo $hariIndo[date('l')];
        @endphp
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════
       2. STAT CARDS
  ═══════════════════════════════════ --}}
  <div class="dash-card stats-card">
    <div class="section-label">Ringkasan Kehadiran</div>
    <div class="stats-grid">
      <div class="stat-item si-blue" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-icon-wrap ic-blue"><i data-feather="users"></i></div>
        <div class="stat-lbl">Total Pegawai</div>
        <div class="stat-val" id="val-total-pegawai">{{ $jumlah_user }}</div>
        <div class="stat-trend" id="trend-pegawai"><i data-feather="trending-up"></i><span id="trend-pegawai-text">+0% bulan ini</span></div>
      </div>
      <div class="stat-item si-green" data-aos="fade-up" data-aos-delay="200">
        <div class="stat-icon-wrap ic-green"><i data-feather="check-circle"></i></div>
        <div class="stat-lbl">Hadir Hari Ini</div>
        <div class="stat-val" id="val-hadir">{{ $jumlah_masuk + $jumlah_izin_telat + $jumlah_izin_pulang_cepat }}</div>
        <div class="stat-trend pos" id="trend-hadir"><i data-feather="arrow-up"></i><span id="hadir-persen">{{ round((($jumlah_masuk + $jumlah_izin_telat + $jumlah_izin_pulang_cepat) / max(1,$jumlah_user)) * 100) }}%</span></div>
      </div>
      <div class="stat-item si-red" data-aos="fade-up" data-aos-delay="300">
        <div class="stat-icon-wrap ic-red"><i data-feather="user-x"></i></div>
        <div class="stat-lbl">Tidak Hadir</div>
        <div class="stat-val" id="val-tidak-hadir">{{ $jumlah_user - ($jumlah_masuk + $jumlah_izin_telat + $jumlah_izin_pulang_cepat + $jumlah_libur + $jumlah_cuti + $jumlah_izin_masuk + $jumlah_sakit) }}</div>
        <div class="stat-trend neg" id="trend-tidak-hadir"><i data-feather="arrow-down"></i><span id="trend-tidak-hadir-text">-0% vs kemarin</span></div>
      </div>
      <div class="stat-item si-cyan" data-aos="fade-up" data-aos-delay="400">
        <div class="stat-icon-wrap ic-cyan"><i data-feather="calendar"></i></div>
        <div class="stat-lbl">Libur</div>
        <div class="stat-val" id="val-libur">{{ $jumlah_libur }}</div>
        <div class="stat-trend"><i data-feather="calendar"></i><span>Hari ini</span></div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════
       3. METRICS CARD
  ═══════════════════════════════════ --}}
  <div class="dash-card metrics-card">
    <div class="section-label">Analisis Kehadiran</div>
    <div class="metrics-grid">
      <div class="metric-item mi-amber" data-aos="zoom-in" data-aos-delay="100">
        <div class="metric-icon-wrap mico-amber"><i data-feather="clock"></i></div>
        <div class="metric-val" id="val-lembur">{{ $jumlah_karyawan_lembur }}</div>
        <div class="metric-lbl">Lembur</div>
      </div>
      <div class="metric-item mi-purple" data-aos="zoom-in" data-aos-delay="150">
        <div class="metric-icon-wrap mico-purple"><i data-feather="heart"></i></div>
        <div class="metric-val" id="val-cuti">{{ $jumlah_cuti }}</div>
        <div class="metric-lbl">Cuti</div>
      </div>
      <div class="metric-item mi-red" data-aos="zoom-in" data-aos-delay="200">
        <div class="metric-icon-wrap mico-red"><i data-feather="thermometer"></i></div>
        <div class="metric-val" id="val-sakit">{{ $jumlah_sakit }}</div>
        <div class="metric-lbl">Sakit</div>
      </div>
      <div class="metric-item mi-cyan" data-aos="zoom-in" data-aos-delay="250">
        <div class="metric-icon-wrap mico-cyan"><i data-feather="umbrella"></i></div>
        <div class="metric-val" id="val-izin">{{ $jumlah_izin_masuk }}</div>
        <div class="metric-lbl">Izin</div>
      </div>
      <div class="metric-item mi-orange" data-aos="zoom-in" data-aos-delay="300">
        <div class="metric-icon-wrap mico-orange"><i data-feather="clock"></i></div>
        <div class="metric-val" id="val-izin-telat">{{ $jumlah_izin_telat }}</div>
        <div class="metric-lbl">Terlambat</div>
      </div>
      <div class="metric-item mi-blue" data-aos="zoom-in" data-aos-delay="350">
        <div class="metric-icon-wrap mico-blue"><i data-feather="log-out"></i></div>
        <div class="metric-val" id="val-izin-pulang-cepat">{{ $jumlah_izin_pulang_cepat }}</div>
        <div class="metric-lbl">Pulang Cepat</div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════
       4. FINANCIAL CARDS (masing-masing card sendiri)
  ═══════════════════════════════════ --}}
  <div class="financial-grid">
    <div class="fin-card fin-blue" data-aos="fade-right" data-aos-delay="100">
      <div class="fin-blob fin-blob-1"></div>
      <div class="fin-blob fin-blob-2"></div>
      <div class="fin-blob fin-blob-3"></div>
      <div class="fin-content">
        <div class="fin-icon-wrap"><i data-feather="dollar-sign"></i></div>
        <div class="fin-lbl">Total Payroll</div>
        <div class="fin-val" id="val-payroll">Rp {{ number_format($payroll) }}</div>
        <div class="fin-desc">Gaji keseluruhan bulan ini</div>
      </div>
    </div>
    <div class="fin-card fin-pink" data-aos="fade-up" data-aos-delay="200">
      <div class="fin-blob fin-blob-1"></div>
      <div class="fin-blob fin-blob-2"></div>
      <div class="fin-blob fin-blob-3"></div>
      <div class="fin-content">
        <div class="fin-icon-wrap"><i data-feather="credit-card"></i></div>
        <div class="fin-lbl">Total Kasbon</div>
        <div class="fin-val" id="val-kasbon">Rp {{ number_format($kasbon) }}</div>
        <div class="fin-desc">Pinjaman karyawan aktif</div>
      </div>
    </div>
    <div class="fin-card fin-teal" data-aos="fade-left" data-aos-delay="300">
      <div class="fin-blob fin-blob-1"></div>
      <div class="fin-blob fin-blob-2"></div>
      <div class="fin-blob fin-blob-3"></div>
      <div class="fin-content">
        <div class="fin-icon-wrap"><i data-feather="refresh-cw"></i></div>
        <div class="fin-lbl">Reimbursement</div>
        <div class="fin-val" id="val-reimbursement">Rp {{ number_format($reimbursement) }}</div>
        <div class="fin-desc">Penggantian biaya operasional</div>
      </div>
    </div>
  </div>

  {{-- ═══════════════════════════════════
       5. CHART + CALENDAR (side by side)
  ═══════════════════════════════════ --}}
  <div class="bottom-row">
    <div class="dash-card chart-card">
      <div class="card-title">Grafik Kehadiran Bulanan</div>
      <div class="card-sub">Statistik kehadiran karyawan
        @php
          $bulanPenuhIndo = ['January'=>'Januari','February'=>'Februari','March'=>'Maret','April'=>'April','May'=>'Mei','June'=>'Juni','July'=>'Juli','August'=>'Agustus','September'=>'September','October'=>'Oktober','November'=>'November','December'=>'Desember'];
          echo $bulanPenuhIndo[date('F')];
        @endphp
        {{ date('Y') }}
      </div>
      <div class="chart-inner">
        <canvas id="attendanceChart" style="width:100%;height:100%"></canvas>
      </div>
    </div>

    <div class="dash-card calendar-card">
      <div class="cal-card-header">
        <div>
          <div class="card-title">Kalender Aktivitas</div>
          <div class="card-sub" style="margin-bottom:0">Timeline kegiatan dan event terbaru</div>
        </div>
        <div class="event-badge"><i data-feather="calendar"></i><span>Event Langsung</span></div>
      </div>
      <div id="external-events-list"></div>
      <div id="calendar"></div>
    </div>
  </div>

</div>{{-- /.modern-dashboard --}}

@push('script')
<script src="https://unpkg.com/aos@next/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

<script>
AOS.init({ duration: 700, easing: 'ease-out-cubic', once: true, offset: 80 });

let attendanceChart = null;

document.addEventListener("DOMContentLoaded", function () {

  // ─── Chart ───────────────────────────────
  @if(isset($chart_data))
  const ctx = document.getElementById('attendanceChart');
  if (ctx) {
    attendanceChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: {!! json_encode($chart_data['labels']) !!},
        datasets: [
          { label:'Hadir',       data:{!! json_encode($chart_data['hadir']) !!},      borderColor:'#10b981', backgroundColor:'rgba(16,185,129,0.07)', borderWidth:2, fill:false, tension:0.4, pointRadius:3, pointHoverRadius:5 },
          { label:'Tidak Hadir', data:{!! json_encode($chart_data['tidak_hadir']) !!}, borderColor:'#ef4444', backgroundColor:'rgba(239,68,68,0.07)',  borderWidth:2, fill:false, tension:0.4, pointRadius:3, pointHoverRadius:5 },
          { label:'Libur',       data:{!! json_encode($chart_data['libur']) !!},       borderColor:'#06b6d4', backgroundColor:'rgba(6,182,212,0.07)',   borderWidth:2, fill:false, tension:0.4, pointRadius:3, pointHoverRadius:5 },
          { label:'Cuti',        data:{!! json_encode($chart_data['cuti']) !!},        borderColor:'#8b5cf6', backgroundColor:'rgba(139,92,246,0.07)',  borderWidth:2, fill:false, tension:0.4, pointRadius:3, pointHoverRadius:5 },
          { label:'Sakit',       data:{!! json_encode($chart_data['sakit']) !!},       borderColor:'#f59e0b', backgroundColor:'rgba(245,158,11,0.07)',  borderWidth:2, fill:false, tension:0.4, pointRadius:3, pointHoverRadius:5 },
          { label:'Izin',        data:{!! json_encode($chart_data['izin']) !!},        borderColor:'#f97316', backgroundColor:'rgba(249,115,22,0.07)',  borderWidth:2, fill:false, tension:0.4, pointRadius:3, pointHoverRadius:5 },
        ]
      },
      options: {
        responsive:true, maintainAspectRatio:false,
        plugins: {
          legend:{ display:true, position:'top', labels:{ usePointStyle:true, padding:14, font:{ size:11, weight:'500' } } },
          tooltip:{ mode:'index', intersect:false, backgroundColor:'rgba(255,255,255,0.96)', titleColor:'#1f2937', bodyColor:'#374151', borderColor:'#e5e7eb', borderWidth:1, padding:10, boxPadding:5 }
        },
        scales: {
          x:{ grid:{ color:'rgba(229,231,235,0.5)', drawBorder:false }, ticks:{ font:{ size:10 }, color:'#6b7280' } },
          y:{ beginAtZero:true, grid:{ color:'rgba(229,231,235,0.5)', drawBorder:false }, ticks:{ font:{ size:10 }, color:'#6b7280', stepSize:1 } }
        },
        interaction:{ mode:'nearest', axis:'x', intersect:false }
      }
    });
  }
  @endif

  // ─── Calendar ────────────────────────────
  var date = new Date();
  var y = date.getFullYear();

  var containerEl = document.getElementById("external-events-list");
  if (containerEl) {
    new FullCalendar.Draggable(containerEl, {
      itemSelector: ".fc-event",
      eventData: function(eventEl){ return { title: eventEl.innerText.trim() }; }
    });
  }

  var calendarEl = document.getElementById("calendar");
  var calendar = new FullCalendar.Calendar(calendarEl, {
    headerToolbar: {
      left: "prev,next today", center: "title",
      right: window.innerWidth > 768 ? "dayGridMonth,timeGridWeek,listWeek" : "dayGridMonth,listWeek"
    },
    initialView: "dayGridMonth", navLinks:true, editable:true, selectable:true,
    nowIndicator:true, height:'auto', locale:'id',
    buttonText:{ today:'Hari Ini', month:'Bulan', week:'Minggu', day:'Hari', list:'Agenda' },
    events: [
      @php
        $tahun_skrg  = date('Y');
        $bulan_skrg  = date('m');
        $jmlh_bulan  = cal_days_in_month(CAL_GREGORIAN, $bulan_skrg, $tahun_skrg);
        $tgl_mulai   = date('1945-01-01');
        $tgl_akhir   = date('Y-m-' . $jmlh_bulan);
        $data_user              = App\Models\User::select('name','tgl_lahir')->whereBetween('tgl_lahir', [$tgl_mulai, $tgl_akhir])->get();
        $data_sakit             = App\Models\MappingShift::where('status_absen','Sakit')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_cuti              = App\Models\MappingShift::where('status_absen','Cuti')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_izin_masuk        = App\Models\MappingShift::where('status_absen','Izin Masuk')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_izin_telat        = App\Models\MappingShift::where('status_absen','Izin Telat')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
        $data_izin_pulang_cepat = App\Models\MappingShift::where('status_absen','Izin Pulang Cepat')->whereBetween('tanggal', [$tgl_mulai, $tgl_akhir])->get();
      @endphp
      @foreach($data_user as $du)
        @php $p = explode("-", $du->tgl_lahir) @endphp
        { title:'🎂 {{ $du->name }}', start:new Date(y,{{ $p[1]-1 }},{{ $p[2] }}), allDay:true, backgroundColor:'#f59e0b', borderColor:'#f59e0b', textColor:'#000' },
      @endforeach
      @foreach($data_sakit as $ds)
        @php $p = explode("-", $ds->tanggal) @endphp
        { title:'🤒 {{ $ds->User->name }}', start:new Date({{ $p[0] }},{{ $p[1]-1 }},{{ $p[2] }}), allDay:true, backgroundColor:'#ef4444', borderColor:'#ef4444' },
      @endforeach
      @foreach($data_cuti as $dc)
        @php $p = explode("-", $dc->tanggal) @endphp
        { title:'🏖️ {{ $dc->User->name }}', start:new Date({{ $p[0] }},{{ $p[1]-1 }},{{ $p[2] }}), allDay:true, backgroundColor:'#8b5cf6', borderColor:'#8b5cf6' },
      @endforeach
      @foreach($data_izin_masuk as $dim)
        @php $p = explode("-", $dim->tanggal) @endphp
        { title:'📝 {{ $dim->User->name }}', start:new Date({{ $p[0] }},{{ $p[1]-1 }},{{ $p[2] }}), allDay:true, backgroundColor:'#06b6d4', borderColor:'#06b6d4' },
      @endforeach
      @foreach($data_izin_telat as $dit)
        @php $p = explode("-", $dit->tanggal) @endphp
        { title:'⏰ {{ $dit->User->name }}', start:new Date({{ $p[0] }},{{ $p[1]-1 }},{{ $p[2] }}), allDay:true, backgroundColor:'#f97316', borderColor:'#f97316' },
      @endforeach
      @foreach($data_izin_pulang_cepat as $dipc)
        @php $p = explode("-", $dipc->tanggal) @endphp
        { title:'🏃 {{ $dipc->User->name }}', start:new Date({{ $p[0] }},{{ $p[1]-1 }},{{ $p[2] }}), allDay:true, backgroundColor:'#10b981', borderColor:'#10b981' },
      @endforeach
    ],
    droppable:true,
    drop:function(arg){
      if(document.getElementById("drop-remove")?.checked) arg.draggedEl.parentNode.removeChild(arg.draggedEl);
    }
  });

  window.addEventListener('resize', function(){
    calendar.setOption('headerToolbar',{
      left:"prev,next today", center:"title",
      right:window.innerWidth>768?"dayGridMonth,timeGridWeek,listWeek":"dayGridMonth,listWeek"
    });
  });

  calendar.render();

  // ─── Realtime Polling ────────────────────
  const INTERVAL_MS = 30000;

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
    wrapper.classList.remove('pos','neg');
    if (value > 0)      { wrapper.classList.add('pos'); span.textContent = `+${value}% ${suffix}`; }
    else if (value < 0) { wrapper.classList.add('neg'); span.textContent = `${value}% ${suffix}`; }
    else                { span.textContent = `Stabil ${suffix}`; }
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
      const res = await fetch('{{ route("dashboard.realtime") }}', {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      if (!res.ok) throw new Error(res.status);
      const d = await res.json();

      setEl('val-total-pegawai', d.jumlah_user);
      setEl('val-hadir',         d.hadir);
      setEl('val-tidak-hadir',   d.tidak_hadir);
      setEl('val-libur',         d.libur);
      setEl('hadir-persen',      d.hadir_persen + '%');

      setTrend('trend-hadir',       'hadir-persen',           d.trend_hadir,       'vs kemarin');
      setTrend('trend-tidak-hadir', 'trend-tidak-hadir-text', d.trend_tidak_hadir, 'vs kemarin');
      setTrend('trend-pegawai',     'trend-pegawai-text',     d.trend_user,        'bulan ini');

      setEl('val-lembur',            d.lembur);
      setEl('val-cuti',              d.cuti);
      setEl('val-sakit',             d.sakit);
      setEl('val-izin',              d.izin);
      setEl('val-izin-telat',        d.izin_telat);
      setEl('val-izin-pulang-cepat', d.izin_pulang_cepat);

      setEl('val-payroll',       d.payroll_format);
      setEl('val-kasbon',        d.kasbon_format);
      setEl('val-reimbursement', d.reimbursement_format);

      updateChart(d.chart_data);
      setEl('last-updated', d.last_updated);

    } catch (err) {
      console.warn('[Dashboard] Polling gagal:', err);
    }
  }

  setInterval(updateClock, 1000);
  setInterval(fetchStats, INTERVAL_MS);
  setTimeout(fetchStats, 3000);
});
</script>
@endpush

@endsection