@extends('templates.dashboard')
@section('isi')

@push('style')
<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

* {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

:root {
    --dash-blue: #3b4cca;
    --dash-blue-dk: #2d3db4;
    --dash-blue-lt: #5c6ed4;

    --slate-50: #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-300: #cbd5e1;
    --slate-500: #64748b;
    --slate-700: #334155;
}

/* CARD */
.form-card {
    border-radius: 16px;
    border: 1px solid var(--slate-200);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}

/* HEADER */
.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--slate-200);
    background: linear-gradient(135deg, #fff, #f8fafc);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--slate-700);
    display: flex;
    align-items: center;
    gap: 8px;
}

.form-title small {
    font-size: 13px;
    font-weight: 400;
    color: var(--slate-500);
}

/* ACTION BUTTON */
.action-btn {
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    border: 1px solid var(--slate-200);
    background: white;
    color: var(--slate-700);
    transition: .2s;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.action-btn:hover {
    background: var(--dash-blue);
    color: white;
    border-color: var(--dash-blue);
    text-decoration: none;
}

/* FORM CONTAINER */
.form-container {
    padding: 24px;
}

/* SUBMIT BUTTON */
.submit-btn {
    background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk));
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(59,76,202,0.25);
    transition: all .2s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59,76,202,0.35);
    color: white;
}

.submit-btn:disabled {
    opacity: 0.65;
    transform: none;
    cursor: not-allowed;
}

/* SECONDARY BUTTON */
.secondary-btn {
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    border: 1px solid var(--slate-200);
    background: white;
    color: var(--slate-700);
    transition: all .2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.secondary-btn:hover {
    background: var(--slate-100);
    color: var(--slate-700);
    text-decoration: none;
}

/* DANGER BUTTON */
.danger-btn {
    background: linear-gradient(135deg, #dc3545, #c82333);
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(220,53,69,0.25);
    transition: all .2s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.danger-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(220,53,69,0.35);
    color: white;
    text-decoration: none;
}

.danger-btn:disabled {
    opacity: 0.65;
    transform: none;
    cursor: not-allowed;
}

/* WARNING BUTTON */
.warning-btn {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border: none;
    padding: 7px 14px;
    border-radius: 8px;
    font-weight: 600;
    font-size: 13px;
    box-shadow: 0 4px 10px rgba(245,158,11,0.25);
    transition: all .2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.warning-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 14px rgba(245,158,11,0.35);
    color: white;
}

.warning-btn:disabled {
    opacity: 0.65;
    transform: none;
    cursor: not-allowed;
}

/* FORM CONTROLS */
.form-control {
    border-radius: 8px;
    font-size: 14px;
    border: 1px solid var(--slate-200);
}

.form-control:focus {
    border-color: var(--dash-blue);
    box-shadow: 0 0 0 0.15rem rgba(59,76,202,0.15);
}

.form-group label,
.form-label {
    font-weight: 600;
    font-size: 14px;
    color: var(--slate-700);
    margin-bottom: 6px;
    display: block;
}

/* SECTION HEADER (sub-card header) */
.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--slate-200);
    background: var(--slate-50);
}

.section-title {
    font-size: 15px;
    font-weight: 700;
    color: var(--slate-700);
    display: flex;
    align-items: center;
    gap: 8px;
    margin: 0;
}

/* MODE TOGGLE */
.mode-toggle {
    display: flex;
    gap: 6px;
    background: var(--slate-100);
    border-radius: 10px;
    padding: 4px;
}

.mode-toggle .btn-check + label {
    padding: 6px 14px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    border: none;
    background: transparent;
    color: var(--slate-500);
    cursor: pointer;
    transition: all .2s;
    margin: 0;
}

.mode-toggle .btn-check:checked + label {
    background: white;
    color: var(--dash-blue);
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* CALENDAR */
.calendar-grid {
    background: white;
    border-radius: 12px;
    border: 1px solid var(--slate-200);
    overflow: hidden;
    margin: 20px 0;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: var(--slate-50);
    border-bottom: 1px solid var(--slate-200);
}

.day-label {
    padding: 12px 8px;
    text-align: center;
    font-weight: 600;
    color: var(--slate-500);
    font-size: 13px;
}

.calendar-body {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: var(--slate-200);
}

.calendar-day {
    background: white;
    min-height: 140px;
    padding: 8px;
    position: relative;
    display: flex;
    flex-direction: column;
}

.calendar-day.empty {
    background: var(--slate-50);
}

.calendar-day.today {
    background: #e3f2fd;
    border: 2px solid #2196f3;
}

.calendar-day.weekend {
    background: #fff9c4;
}

.day-number {
    font-size: 15px;
    font-weight: 700;
    color: var(--slate-700);
    margin-bottom: 8px;
}

.calendar-day.today .day-number {
    color: #2196f3;
}

.day-selector {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.status-text {
    font-size: 9px;
    line-height: 1;
    margin-top: 2px;
}

.selected-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 2px;
    margin-top: 2px;
}

.selected-indicator i { font-size: 11px; }
.selected-indicator small { font-size: 9px; line-height: 1; }

.selectable-day {
    transition: all .2s ease;
}

.selectable-day:hover {
    background: rgba(59, 76, 202, 0.07) !important;
    transform: scale(1.02);
}

.selectable-day:active { transform: scale(0.98); }

.calendar-day.selected {
    background: rgba(25, 135, 84, 0.1) !important;
    border: 2px solid #198754 !important;
}

.calendar-day.scheduled {
    border: 2px solid #28a745 !important;
}

.calendar-day.shift-color-0 { background: rgba(108,117,125,.15) !important; border-color: #6c757d !important; }
.calendar-day.shift-color-1 { background: rgba(40,167,69,.15) !important; border-color: #28a745 !important; }
.calendar-day.shift-color-2 { background: rgba(0,123,255,.15) !important; border-color: #007bff !important; }
.calendar-day.shift-color-3 { background: rgba(255,193,7,.15) !important; border-color: #ffc107 !important; }
.calendar-day.shift-color-4 { background: rgba(220,53,69,.15) !important; border-color: #dc3545 !important; }
.calendar-day.shift-color-5 { background: rgba(111,66,193,.15) !important; border-color: #6f42c1 !important; }
.calendar-day.shift-color-6 { background: rgba(253,126,20,.15) !important; border-color: #fd7e14 !important; }
.calendar-day.shift-color-7 { background: rgba(32,201,151,.15) !important; border-color: #20c997 !important; }

.calendar-day.has-shift:hover { box-shadow: 0 4px 12px rgba(0,0,0,0.15); }

.delete-shift-icon {
    transition: all .2s ease;
    font-size: 12px;
}

.delete-shift-icon:hover {
    transform: scale(1.2);
    color: #b02a37 !important;
}

.calendar-day.selected .day-number {
    color: #198754 !important;
    font-weight: 700;
}

/* BULK SELECTION CARD */
#bulk-shift-selection .bulk-card {
    border: 2px solid var(--dash-blue);
    border-radius: 12px;
    overflow: hidden;
}

#bulk-shift-selection .bulk-card-header {
    background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk));
    color: white;
    padding: 12px 20px;
    font-size: 14px;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
}

#bulk-shift-selection .bulk-card-body {
    padding: 20px;
    background: white;
}

.selected-dates-summary {
    background: var(--slate-50);
    border-radius: 8px;
    border-left: 4px solid #198754;
    padding: 10px 14px;
    margin-top: 12px;
}

/* TABLE */
#mytable thead th {
    font-size: 13px;
    font-weight: 700;
    color: var(--slate-700);
    background: var(--slate-50);
    border-bottom: 2px solid var(--slate-200);
    padding: 12px 16px;
}

#mytable tbody td {
    font-size: 14px;
    color: var(--slate-700);
    padding: 12px 16px;
    vertical-align: middle;
}

.holiday-shift-row {
    background-color: #fff5f5 !important;
    border-left: 4px solid #dc3545;
}

.holiday-shift-row:hover { background-color: #ffeaea !important; }

.holiday-badge {
    background-color: #dc3545 !important;
    color: white !important;
    font-size: 12px;
    font-weight: 600;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-block;
}

/* MODAL */
.modal-header {
    background: linear-gradient(135deg, #fff, var(--slate-50));
    border-bottom: 1px solid var(--slate-200);
    padding: 20px 24px;
}

.modal-title {
    font-size: 16px;
    font-weight: 700;
    color: var(--slate-700);
}

.modal-body { padding: 24px; }
.modal-footer {
    padding: 16px 24px;
    border-top: 1px solid var(--slate-200);
    background: var(--slate-50);
}

.modal-content {
    border-radius: 16px;
    border: 1px solid var(--slate-200);
    box-shadow: 0 20px 60px rgba(0,0,0,0.12);
    overflow: hidden;
}

/* INFO BOX */
.info-box {
    background: var(--slate-50);
    border: 1px solid var(--slate-200);
    border-left: 4px solid var(--dash-blue);
    border-radius: 10px;
    padding: 14px 18px;
    font-size: 14px;
    color: var(--slate-700);
}

.info-box ol {
    margin: 8px 0 0 0;
    padding-left: 20px;
}

.info-box li {
    margin-bottom: 4px;
    font-size: 13px;
}

/* FILTER FORM */
.filter-form .form-label {
    font-size: 13px;
    font-weight: 600;
    color: var(--slate-700);
    margin-bottom: 4px;
}

.filter-form .form-control {
    font-size: 13px;
    padding: 7px 12px;
}

/* PAGE SECTION DIVIDER */
.page-section {
    margin-bottom: 24px;
}

/* RESPONSIVE */
@media (max-width: 768px) {
    .calendar-day { min-height: 100px; padding: 6px; }
    .day-number { font-size: 13px; }
    .form-header { flex-direction: column; align-items: flex-start; gap: 12px; }
    .mode-toggle { width: 100%; }
}
</style>
@endpush

<div class="row g-4 mt-1">

    {{-- HEADER PAGE CARD --}}
    <div class="col-12">
        <div class="card form-card">
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-calendar-alt" style="color:#3b4cca"></i>
                    {{ $title }}
                    <!--@if(auth()->check())-->
                    <!--    <small class="ms-2">-->
                    <!--        Login sebagai: <strong>{{ auth()->user()->name }}</strong> |-->
                    <!--        Role: <strong>{{ auth()->user()->roles->pluck('name')->join(', ') }}</strong>-->
                    <!--        @if(auth()->user()->hasRole('admin'))-->
                    <!--            <span class="badge badge-success ms-1">✓ Admin</span>-->
                    <!--        @else-->
                    <!--            <span class="badge badge-warning ms-1">⚠ Limited</span>-->
                    <!--        @endif-->
                    <!--    </small>-->
                    <!--@endif-->
                </div>
                <a href="{{ url('/pegawai') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>
        </div>
    </div>

    {{-- SCHEDULING FORM CARD --}}
    <div class="col-12">
        <div class="card form-card">
            <div class="section-header">
                <h5 class="section-title">
                    <i class="fas fa-clock" style="color:#3b4cca"></i>
                    Penjadwalan Shift {{ $karyawan->name }}
                </h5>
                {{-- MODE TOGGLE --}}
                <div class="mode-toggle">
                    <input type="radio" class="btn-check" name="scheduling-mode" id="mode-range" autocomplete="off" checked>
                    <label for="mode-range">
                        <i class="fas fa-calendar-week me-1"></i> Range Tanggal
                    </label>

                    <input type="radio" class="btn-check" name="scheduling-mode" id="mode-calendar" autocomplete="off">
                    <label for="mode-calendar">
                        <i class="fas fa-calendar me-1"></i> Kalender Bulanan
                    </label>
                </div>
            </div>

            <div class="form-container">

                {{-- ======= RANGE DATE MODE ======= --}}
                <div id="range-mode" class="scheduling-mode">
                    <form method="post" action="{{ url('/pegawai/shift/proses-tambah-shift') }}" id="range-form">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $karyawan->id }}">

                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="shift_id">Shift</label>
                                    <select class="form-control selectpicker @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id" data-live-search="true">
                                        <option value="">Pilih Shift</option>
                                        @foreach ($shift as $s)
                                            <option value="{{ $s->id }}" {{ old('shift_id') == $s->id ? 'selected' : '' }}>
                                                {{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('shift_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="tanggal_akhir">Tanggal Akhir</label>
                                    <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                                    @error('tanggal_akhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input name="lock_location" class="form-check-input lock_location" type="checkbox" value="1" id="lock_location_range">
                                    <label class="form-check-label" for="lock_location_range" style="font-weight:500; font-size:14px; color:var(--slate-700);">
                                        Lock Location
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Hari Libur</label>
                                    <div class="card" style="border-radius:12px; border:1px solid var(--slate-200);">
                                        <div class="card-body p-3">
                                            <div class="row row-cols-2 row-cols-md-4 g-2">
                                                @php
                                                    $daysOfWeek = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
                                                @endphp
                                                @foreach ($daysOfWeek as $englishDay => $indonesianDay)
                                                    <div class="col">
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input holiday-day" type="checkbox" value="{{ $englishDay }}" id="holiday_range_{{ $englishDay }}" name="holiday_days[]">
                                                            <label class="form-check-label" for="holiday_range_{{ $englishDay }}" style="font-size:13px; font-weight:600; color:var(--slate-700);">
                                                                {{ $indonesianDay }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-2">
                                                <small class="text-muted" style="font-size:12px;">
                                                    <i class="fas fa-info-circle"></i> Hari yang dicentang akan dijadwalkan sebagai hari libur
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger mb-3" style="border-radius:10px; border-left:4px solid #dc3545;">
                                <strong>Error:</strong>
                                <ul class="mb-0 mt-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger mb-3" style="border-radius:10px; border-left:4px solid #dc3545;">
                                <strong>Error:</strong> {{ session('error') }}
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success mb-3" style="border-radius:10px; border-left:4px solid #198754;">
                                <strong>Success:</strong> {{ session('success') }}
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2 mt-2">
                            <a href="{{ url('/pegawai') }}" class="secondary-btn">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="submit-btn" id="range-submit-btn">
                                <i class="fas fa-save"></i> Simpan Shift Range
                            </button>
                        </div>
                    </form>
                </div>

                {{-- ======= CALENDAR MODE ======= --}}
                <div id="calendar-mode" class="scheduling-mode" style="display: none;">
                    <form method="post" action="{{ url('/pegawai/shift/proses-tambah-shift-bulk') }}" id="calendar-form">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                        <input type="hidden" name="bulan" id="form-bulan" value="{{ date('m') }}">
                        <input type="hidden" name="tahun" id="form-tahun" value="{{ date('Y') }}">

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h6 class="mb-0" style="font-weight:700; color:var(--slate-700);">Pilih Bulan & Tahun:</h6>
                            <div class="d-flex gap-2">
                                <select id="bulan" class="form-control" style="min-width: 140px; font-size:14px;">
                                    @php
                                        $namaBulan = [
                                            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                        ];
                                    @endphp
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>{{ $namaBulan[$i] }}</option>
                                    @endfor
                                </select>
                                <select id="tahun" class="form-control" style="min-width: 100px; font-size:14px;">
                                    @for($i = date('Y') - 1; $i <= date('Y') + 5; $i++)
                                        <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        {{-- Shift Selection Modal --}}
                        <div class="modal fade" id="shiftSelectionModal" tabindex="-1" aria-labelledby="shiftSelectionModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="shiftSelectionModalLabel">
                                            <i class="fas fa-calendar-day me-2" style="color:#3b4cca"></i>
                                            Pilih Shift untuk Tanggal <span id="selected-date-text"></span>
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="shift-select-modal" class="form-label fw-bold">
                                                <i class="fas fa-clock me-1" style="color:#3b4cca"></i> Pilih Shift
                                            </label>
                                            <select class="form-control" id="shift-select-modal">
                                                <option value="">Tidak Ada Shift</option>
                                                @foreach ($shift as $s)
                                                    <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-bold">
                                                <i class="fas fa-map-marker-alt me-1" style="color:#3b4cca"></i> Lock Location
                                            </label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" value="1" id="lock-location-modal">
                                                <label class="form-check-label" for="lock-location-modal" style="font-size:14px; color:var(--slate-700);">
                                                    <strong>Kunci lokasi absensi</strong> untuk tanggal ini
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                        <button type="button" class="btn btn-primary" id="confirm-shift-selection">Konfirmasi</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Calendar Grid --}}
                        <div class="calendar-grid">
                            <div class="calendar-header">
                                <div class="day-label">Senin</div>
                                <div class="day-label">Selasa</div>
                                <div class="day-label">Rabu</div>
                                <div class="day-label">Kamis</div>
                                <div class="day-label">Jumat</div>
                                <div class="day-label">Sabtu</div>
                                <div class="day-label">Minggu</div>
                            </div>
                            <div class="calendar-body" id="calendar-body"></div>
                        </div>

                        {{-- Shift Config Panel --}}
                        <div id="shift-config-panel" class="mt-4" style="display: none;">
                            <div class="info-box mb-0">
                                <i class="fas fa-info-circle me-1" style="color:#3b4cca"></i>
                                <strong>Panel Konfigurasi Shift</strong><br>
                                <span style="font-size:13px; color:var(--slate-500);">Silakan pilih shift dan pengaturan lock location untuk tanggal yang dipilih.</span>
                            </div>
                        </div>

                        <div class="mt-4">
                            {{-- Bulk Shift Selection --}}
                            <div id="bulk-shift-selection" style="display: none;">
                                <div class="bulk-card mb-4">
                                    <div class="bulk-card-header">
                                        <i class="fas fa-cogs"></i>
                                        Konfigurasi Shift untuk Tanggal Terpilih
                                    </div>
                                    <div class="bulk-card-body">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-group mb-0">
                                                    <label for="bulk_shift_select">
                                                        <i class="fas fa-clock me-1" style="color:#3b4cca"></i> Pilih Shift
                                                    </label>
                                                    <select class="form-control" id="bulk_shift_select">
                                                        <option value="">Pilih Shift</option>
                                                        @foreach ($shift as $s)
                                                            <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group mb-0">
                                                    <label><i class="fas fa-map-marker-alt me-1" style="color:#3b4cca"></i> Lock Location</label>
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" value="1" id="bulk_lock_location">
                                                        <label class="form-check-label" for="bulk_lock_location" style="font-size:14px; color:var(--slate-700);">
                                                            <strong>Kunci lokasi absensi</strong> untuk semua tanggal terpilih
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="selected-dates-summary">
                                            <small style="font-size:13px; color:var(--slate-500);">
                                                <i class="fas fa-calendar-check me-1" style="color:#198754"></i>
                                                <span id="selected-count">0</span> tanggal dipilih — klik "Terapkan Shift" untuk menyimpan konfigurasi
                                            </small>
                                        </div>
                                        <div class="mt-3 d-flex gap-2">
                                            <button type="button" class="submit-btn" id="apply-shift-to-selected">
                                                <i class="fas fa-check"></i> Terapkan Shift
                                            </button>
                                            <button type="button" class="secondary-btn" id="clear-selection">
                                                <i class="fas fa-times"></i> Bersihkan Pilihan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="info-box mb-4">
                                <i class="fas fa-info-circle me-1" style="color:#3b4cca"></i>
                                <strong>Cara Penggunaan:</strong>
                                <ol class="mb-0 mt-2">
                                    <li>Klik tanggal yang ingin dijadwalkan (akan berwarna hijau)</li>
                                    <li>Pilih shift dan lock location di panel konfigurasi</li>
                                    <li>Klik "Terapkan Shift" — tanggal akan berubah warna sesuai shift</li>
                                    <li>Klik icon ❌ di shift untuk menghapus shift langsung</li>
                                    <li>Klik "Simpan" untuk menyimpan semua jadwal</li>
                                    <li><strong>Untuk Range Tanggal:</strong> Pilih shift utama, centang hari libur, dan sistem akan otomatis jadwalkan hari libur dengan shift "Libur"</li>
                                </ol>
                            </div>

                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ url('/pegawai') }}" class="secondary-btn">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="submit-btn" id="submit-shifts" disabled>
                                    <i class="fas fa-save"></i> Simpan Semua Shift
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- SHIFT TABLE CARD --}}
    <div class="col-12">
        <div class="card form-card">
            <div class="section-header">
                <h5 class="section-title">
                    <i class="fas fa-table" style="color:#3b4cca"></i>
                    Jadwal Shift — {{ $karyawan->name }}
                </h5>
            </div>

            <div class="form-container">

                {{-- FILTER FORM --}}
                <div class="mb-4 pb-4" style="border-bottom:1px solid var(--slate-200);">
                    <form action="{{ url('/pegawai/shift/'.$karyawan->id) }}" method="GET" class="filter-form row g-3 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tampilkan</label>
                            <select name="per_page" class="form-control">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                                <option value="200" {{ request('per_page', 10) == 200 ? 'selected' : '' }}>200</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="submit-btn w-100">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ url('/pegawai/shift/'.$karyawan->id) }}" class="secondary-btn w-100 justify-content-center">
                                <i class="fas fa-times"></i> Reset
                            </a>
                        </div>
                    </form>
                </div>

                {{-- BULK ACTION BUTTONS --}}
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="info-box py-2 px-3 d-inline-flex align-items-center gap-2" style="border-left-color:#ffc107;">
                        <!--<i class="fas fa-info-circle" style="color:#f59e0b; font-size:14px;"></i>-->
                        <small style="font-size:13px;">
                            <!--<strong>Legenda:</strong>-->
                            <span class="holiday-badge ms-2">Libur</span>
                            <span style="color:var(--slate-500); margin-left:6px;">= Hari libur dengan background merah</span>
                        </small>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="button" class="warning-btn" id="open-bulk-edit" disabled>
                            <i class="fas fa-edit"></i> Edit Massal
                        </button>
                        <form id="bulk-delete-form" method="post" action="{{ url('/pegawai/shift/bulk-delete') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                            <span class="selected-ids-delete"></span>
                            <button type="button" class="danger-btn" id="bulk-delete-button" disabled>
                                <i class="fas fa-trash"></i> Hapus Terpilih
                            </button>
                        </form>
                    </div>
                </div>

                @if(request('tanggal_mulai') || request('tanggal_akhir') || request('per_page'))
                    <div class="info-box mb-3" style="border-left-color:#3b4cca;">
                        <small>
                            <i class="fas fa-filter me-1" style="color:#3b4cca"></i>
                            <strong>Filter Aktif:</strong>
                            @if(request('tanggal_mulai') && request('tanggal_akhir'))
                                Tanggal: {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}
                            @elseif(request('tanggal_mulai'))
                                Tanggal Mulai: {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }}
                            @elseif(request('tanggal_akhir'))
                                Tanggal Akhir: {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}
                            @endif
                            @if(request('per_page'))
                                | Menampilkan: {{ request('per_page') }} data per halaman
                            @endif
                            <a href="{{ url('/pegawai/shift/'.$karyawan->id) }}" class="float-end" style="color:#dc3545; font-size:13px; text-decoration:none;">
                                <i class="fas fa-times"></i> Hapus Filter
                            </a>
                        </small>
                    </div>
                @endif

                <div class="table-responsive">
                    <table id="mytable" class="table table-striped">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="select_all"></th>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Shift Pegawai</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shift_karyawan as $key => $sk)
                                @php
                                    $isHoliday = isset($sk->Shift) && strtolower($sk->Shift->nama_shift) === 'libur';
                                @endphp
                                <tr class="{{ $isHoliday ? 'holiday-shift-row' : '' }}">
                                    <td><input type="checkbox" class="row-checkbox" value="{{ $sk->id }}"></td>
                                    <td>{{ ($shift_karyawan->currentpage() - 1) * $shift_karyawan->perpage() + $key + 1 }}.</td>
                                    <td>{{ $sk->tanggal ?? '-' }}</td>
                                    <td>
                                        @if($isHoliday)
                                            <span class="holiday-badge">{{ $sk->Shift->nama_shift ?? '-' }}</span>
                                        @else
                                            {{ $sk->Shift->nama_shift ?? '-' }}
                                        @endif
                                    </td>
                                    <td>{{ $sk->Shift->jam_masuk ?? '-' }}</td>
                                    <td>{{ $sk->Shift->jam_keluar ?? '-' }}</td>
                                    <td>
                                        <ul class="action">
                                            <li class="edit">
                                                <a href="{{ url('/pegawai/edit-shift/'.$sk->id) }}"><i class="fa fa-solid fa-edit"></i></a>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

            <div class="d-flex justify-content-end me-4 mb-4">
                {{ $shift_karyawan->links() }}
            </div>
        </div>
    </div>

</div>

{{-- BULK EDIT MODAL --}}
<div class="modal fade" id="bulkEditModal" tabindex="-1" aria-labelledby="bulkEditModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkEditModalLabel">
                    <i class="fas fa-edit me-2" style="color:#3b4cca"></i> Edit Shift Massal
                </h5>
                <button type="button" class="btn-close bulk-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulk-edit-form" method="post" action="{{ url('/pegawai/shift/bulk-update') }}">
                @csrf
                <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                <div class="modal-body">
                    <div class="selected-ids-edit"></div>
                    <div class="form-group mb-3">
                        <label for="bulk_shift_id">Shift</label>
                        <select class="form-control selectpicker" id="bulk_shift_id" name="shift_id" data-live-search="true" required>
                            <option value="">Pilih Shift</option>
                            @foreach ($shift as $s)
                                <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-check mb-2">
                        <input name="lock_location" class="form-check-input" type="checkbox" value="1" id="bulk_lock_location_modal">
                        <label class="form-check-label" for="bulk_lock_location_modal" style="font-size:14px; color:var(--slate-700);">
                            Terapkan Lock Location
                        </label>
                    </div>
                    <small style="font-size:13px; color:var(--slate-500);">Perubahan akan diterapkan ke semua data yang dipilih.</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="secondary-btn bulk-modal-close" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function() {
    const shiftOptions = [
        {id: '', name: 'Tidak Ada Shift', color: '#6c757d'},
        @foreach ($shift as $index => $s)
        {id: '{{ $s->id }}', name: '{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}', color: '{{ ["#28a745", "#007bff", "#ffc107", "#dc3545", "#6f42c1", "#fd7e14", "#20c997", "#e83e8c"][$index % 8] }}'},
        @endforeach
    ];

    let selectedDatesData = {};

    $('input[name="scheduling-mode"]').change(function() {
        const selectedMode = $(this).attr('id');
        $('.scheduling-mode').removeClass('active').hide();
        if (selectedMode === 'mode-range') {
            $('#range-mode').addClass('active').show();
        } else if (selectedMode === 'mode-calendar') {
            $('#calendar-mode').addClass('active').show();
            if ($('#calendar-body').children().length === 0) {
                generateCalendar(parseInt($('#bulan').val()), parseInt($('#tahun').val()));
            }
        }
    });

    $('#range-mode').addClass('active').show();

    function generateCalendar(bulan, tahun) {
        const calendarBody = $('#calendar-body');
        calendarBody.empty();
        const daysInMonth = new Date(tahun, bulan, 0).getDate();
        const firstDayOfMonth = new Date(tahun, bulan - 1, 1).getDay();
        const adjustedFirstDay = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1;

        for (let i = 0; i < adjustedFirstDay; i++) {
            calendarBody.append('<div class="calendar-day empty"></div>');
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${tahun}-${String(bulan).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = dateStr === new Date().toISOString().split('T')[0];
            const dayOfWeek = new Date(tahun, bulan - 1, day).getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;
            const weekendClass = isWeekend ? 'weekend' : '';
            const isSelected = selectedDatesData[dateStr] !== undefined;
            const selectedData = selectedDatesData[dateStr];

            let shiftDisplay = '';
            if (isSelected && selectedData) {
                const shiftOption = shiftOptions.find(s => s.id == selectedData.shift_id);
                const shiftName = shiftOption ? shiftOption.name : 'Tidak Ada Shift';
                const lockIcon = selectedData.lock_location == 1 ? '<i class="fas fa-lock text-warning ms-1"></i>' : '';
                shiftDisplay = `<small class="text-primary fw-bold">${shiftName}${lockIcon}</small>`;
            }

            const dayHtml = `
                <div class="calendar-day ${isToday ? 'today' : ''} ${weekendClass} ${isSelected ? 'selected' : ''} selectable-day" data-date="${dateStr}" style="cursor: pointer;">
                    <div class="day-number">${day}</div>
                    <div class="day-selector">
                        <div class="selection-status" ${isSelected ? 'style="display: none;"' : ''}>
                            <small class="status-text text-muted">Klik untuk pilih</small>
                        </div>
                        <div class="selected-indicator" ${!isSelected ? 'style="display: none;"' : ''}>
                            <div class="selected-shift-info">${shiftDisplay}</div>
                            <small class="text-success fw-bold">Terjadwal</small>
                        </div>
                    </div>
                </div>
            `;
            calendarBody.append(dayHtml);
        }

        $('#form-bulan').val(bulan);
        $('#form-tahun').val(tahun);
    }

    $('#bulan, #tahun').change(function() {
        const bulan = parseInt($('#bulan').val());
        const tahun = parseInt($('#tahun').val());
        generateCalendar(bulan, tahun);
        updateSelectedDatesSummary();
    });

    function updateCalendarDisplay() {
        $('.selectable-day').each(function() {
            const calendarDay = $(this);
            const date = calendarDay.data('date');
            const daySelector = calendarDay.find('.day-selector');
            const selectedIndicator = daySelector.find('.selected-indicator');
            const statusText = daySelector.find('.status-text');
            const shiftInfo = selectedIndicator.find('.selected-shift-info');
            const dateData = selectedDatesData[date];

            calendarDay.removeClass('shift-color-0 shift-color-1 shift-color-2 shift-color-3 shift-color-4 shift-color-5 shift-color-6 shift-color-7');

            if (dateData && dateData.applied) {
                const shiftOption = shiftOptions.find(s => s.id == dateData.shift_id);
                const shiftColorIndex = shiftOptions.findIndex(s => s.id == dateData.shift_id);
                const colorClass = shiftColorIndex >= 0 ? `shift-color-${shiftColorIndex % 8}` : 'shift-color-0';
                calendarDay.addClass(`scheduled ${colorClass}`).removeClass('selected');
                statusText.hide();
                const shiftName = shiftOption ? shiftOption.name : 'Tidak Ada Shift';
                const lockIcon = dateData.lock_location == 1 ? ' 🔒' : '';
                const deleteIcon = '<i class="fas fa-times delete-shift-icon" style="color: #dc3545; cursor: pointer; position: absolute; top: 4px; right: 4px; z-index: 10;" title="Hapus shift"></i>';
                shiftInfo.html(`<small class="fw-bold">${shiftName}${lockIcon}${deleteIcon}</small>`);
                selectedIndicator.show();
                calendarDay.addClass('has-shift').css('cursor', 'pointer');
            } else if (dateData && !dateData.applied) {
                calendarDay.addClass('selected').removeClass('scheduled');
                statusText.hide();
                shiftInfo.html('<small class="text-primary fw-bold">Dipilih</small>');
                selectedIndicator.show();
                calendarDay.removeClass('has-shift').css('cursor', 'pointer');
            } else {
                calendarDay.removeClass('selected scheduled has-shift');
                selectedIndicator.hide();
                statusText.show();
                calendarDay.css('cursor', 'pointer');
            }
        });
    }

    function updateSelectedDatesSummary() {}

    $('#apply-shift-to-selected').on('click', function() {
        const selectedDates = $('.calendar-day.selected');
        const shiftId = $('#bulk_shift_select').val();
        const lockLocation = $('#bulk_lock_location').is(':checked') ? 1 : 0;
        if (selectedDates.length === 0) { alert('Pilih tanggal terlebih dahulu.'); return; }
        if (!shiftId) { alert('Pilih shift terlebih dahulu.'); $('#bulk_shift_select').focus(); return; }
        selectedDates.each(function() {
            const date = $(this).data('date');
            selectedDatesData[date] = { shift_id: shiftId, lock_location: lockLocation, applied: true };
        });
        $('#bulk_shift_select').val('');
        $('#bulk_lock_location').prop('checked', false);
        updateCalendarDisplay();
        updateBulkSelectionVisibility();
        updateSubmitButton();
    });

    $('#clear-selection').on('click', function() {
        $('.calendar-day.selected').each(function() {
            $(this).removeClass('selected');
            $(this).find('.status-text').show();
            $(this).find('.selected-indicator').hide();
        });
        updateBulkSelectionVisibility();
        updateSubmitButton();
    });

    $('body').on('click', '.remove-date', function() {
        const date = $(this).data('date');
        delete selectedDatesData[date];
        const calendarDay = $(`.selectable-day[data-date="${date}"]`);
        calendarDay.removeClass('selected');
        calendarDay.find('.status-text').show();
        calendarDay.find('.selected-indicator').hide();
        updateCalendarDisplay();
        updateSubmitButton();
    });

    $('body').on('click', '.selectable-day', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const calendarDay = $(this);
        const date = calendarDay.data('date');
        if (selectedDatesData[date] && selectedDatesData[date].applied) return;
        const isSelected = calendarDay.hasClass('selected');
        if (isSelected) {
            calendarDay.removeClass('selected');
            calendarDay.find('.status-text').show();
            calendarDay.find('.selected-indicator').hide();
        } else {
            calendarDay.addClass('selected');
            calendarDay.find('.status-text').hide();
            calendarDay.find('.selected-indicator').show();
        }
        updateBulkSelectionVisibility();
        updateSubmitButton();
    });

    $('body').on('click', '.delete-shift-icon', function(e) {
        e.preventDefault();
        e.stopPropagation();
        const calendarDay = $(this).closest('.selectable-day');
        const date = calendarDay.data('date');
        delete selectedDatesData[date];
        updateCalendarDisplay();
        updateSubmitButton();
    });

    function updateSubmitButton() {
        const selectedDates = Object.keys(selectedDatesData).filter(date => selectedDatesData[date].applied).length;
        const submitBtn = $('#submit-shifts');
        submitBtn.prop('disabled', selectedDates === 0);
        submitBtn.html(selectedDates === 0
            ? '<i class="fas fa-save"></i> Jadwalkan Shift Dahulu'
            : `<i class="fas fa-save"></i> Simpan ${selectedDates} Shift`
        );
    }

    function updateBulkSelectionVisibility() {
        const selectedCount = $('.calendar-day.selected').length;
        const bulkSelection = $('#bulk-shift-selection');
        const configPanel = $('#shift-config-panel');
        if (selectedCount > 0) {
            configPanel.show();
            bulkSelection.show();
            $('#selected-count').text(selectedCount);
            setTimeout(() => configPanel[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' }), 100);
        } else {
            configPanel.hide();
            bulkSelection.hide();
            $('#bulk_shift_select').val('');
            $('#bulk_lock_location').prop('checked', false);
        }
    }

    $('#calendar-form').submit(function(e) {
        e.preventDefault();
        const selectedDates = Object.keys(selectedDatesData);
        if (selectedDates.length === 0) { alert('Pilih tanggal yang ingin dijadwalkan terlebih dahulu.'); return false; }
        const formData = new FormData(this);
        formData.append('shifts_data', JSON.stringify(selectedDatesData));
        const submitBtn = $('#submit-shifts');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function() { alert(selectedDates.length + ' shift berhasil disimpan!'); location.reload(); },
            error: function(xhr) { alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Unknown error')); submitBtn.prop('disabled', false).html(originalText); }
        });
    });

    $('#range-form').submit(function(e) {
        const tanggalMulai = $('#tanggal_mulai').val();
        const tanggalAkhir = $('#tanggal_akhir').val();
        const shiftId = $('#shift_id').val();
        const holidayDays = $('.holiday-day:checked').map(function() { return $(this).val(); }).get();
        let holidayShiftId = '';
        @foreach ($shift as $s)
            @if(strtolower($s->nama_shift) === 'libur')
                holidayShiftId = '{{ $s->id }}';
            @endif
        @endforeach
        if (!shiftId) { e.preventDefault(); alert('Pilih shift terlebih dahulu.'); return false; }
        if (!tanggalMulai || !tanggalAkhir) { e.preventDefault(); alert('Pilih tanggal mulai dan tanggal akhir.'); return false; }
        if (new Date(tanggalMulai) > new Date(tanggalAkhir)) { e.preventDefault(); alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.'); return false; }
        if (holidayDays.length > 0 && !holidayShiftId) { e.preventDefault(); alert('Shift "Libur" tidak ditemukan. Silakan hubungi administrator untuk membuat shift libur terlebih dahulu.'); return false; }
        const submitBtn = $('#range-submit-btn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        setTimeout(() => { submitBtn.prop('disabled', false).html(originalText); }, 10000);
    });

    function collectSelectedIds() {
        var ids = [];
        $('.row-checkbox:checked').each(function () { ids.push($(this).val()); });
        return ids;
    }

    function syncBulkButtons() {
        var hasSelection = collectSelectedIds().length > 0;
        $('#open-bulk-edit').prop('disabled', !hasSelection);
        $('#bulk-delete-button').prop('disabled', !hasSelection);
    }

    function fillSelectedIds(containerSelector, ids) {
        var container = $(containerSelector);
        container.empty();
        ids.forEach(function (id) { container.append('<input type="hidden" name="ids[]" value="'+id+'">'); });
    }

    $('#select_all').on('change', function () {
        $('.row-checkbox').prop('checked', this.checked);
        syncBulkButtons();
    });

    $('body').on('change', '.row-checkbox', function () {
        var allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
        $('#select_all').prop('checked', allChecked);
        syncBulkButtons();
    });

    $('#open-bulk-edit').on('click', function () {
        var ids = collectSelectedIds();
        if (ids.length === 0) { alert('Pilih data terlebih dahulu.'); return; }
        fillSelectedIds('.selected-ids-edit', ids);
        $('#bulk_shift_id').val('').change();
        $('#bulk_lock_location_modal').prop('checked', false);
        $('#bulkEditModal').modal('show');
    });

    $('body').on('click', '.bulk-modal-close', function () { $('#bulkEditModal').modal('hide'); });

    $('#bulk-delete-button').on('click', function () {
        var ids = collectSelectedIds();
        if (ids.length === 0) { alert('Pilih data terlebih dahulu.'); return; }
        fillSelectedIds('.selected-ids-delete', ids);
        if (confirm('Hapus data yang dipilih?')) { $('#bulk-delete-form').submit(); }
    });

    syncBulkButtons();
    updateBulkSelectionVisibility();
    updateSubmitButton();
});
</script>
@endpush

@endsection