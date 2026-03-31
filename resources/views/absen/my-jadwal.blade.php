@extends('templates.app')
@section('container')
<div class="card-secton">
    <div class="tf-container">
        <div class="tf-balance-box">
            <div class="header-section">
                <h4 class="text-center title-mobile">Jadwal Shift Bulan {{ date('F Y', strtotime($tahun . '-' . $bulan . '-01')) }}</h4>
                <div class="month-year-selector">
                    @php
                        $namaBulan = [
                            1 => 'Januari',
                            2 => 'Februari',
                            3 => 'Maret',
                            4 => 'April',
                            5 => 'Mei',
                            6 => 'Juni',
                            7 => 'Juli',
                            8 => 'Agustus',
                            9 => 'September',
                            10 => 'Oktober',
                            11 => 'November',
                            12 => 'Desember'
                        ];
                    @endphp
                    <select id="bulan" class="form-control" style="min-width: 120px; max-width: 150px;">
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $i == $bulan ? 'selected' : '' }}>
                                {{ $namaBulan[$i] }}
                            </option>
                        @endfor
                    </select>
                    <select id="tahun" class="form-control" style="min-width: 90px; max-width: 110px;">
                        @for($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                            <option value="{{ $i }}" {{ $i == $tahun ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="calendar-grid">
                @php
                    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun);
                    $firstDayOfMonth = date('N', strtotime("$tahun-$bulan-01")); // 1 = Monday, 7 = Sunday
                @endphp

                <!-- Calendar Header -->
                <div class="calendar-header">
                    <div class="day-label">Sen</div>
                    <div class="day-label">Sel</div>
                    <div class="day-label">Rab</div>
                    <div class="day-label">Kam</div>
                    <div class="day-label">Jum</div>
                    <div class="day-label">Sab</div>
                    <div class="day-label">Min</div>
                </div>

                <!-- Calendar Grid -->
                <div class="calendar-body">
                    @php
                        // Add empty cells for days before the first day of the month
                        for ($i = 1; $i < $firstDayOfMonth; $i++) {
                            echo '<div class="calendar-day empty"></div>';
                        }
                    @endphp

                    @for($day = 1; $day <= $daysInMonth; $day++)
                        @php
                            $dateKey = sprintf('%04d-%02d-%02d', $tahun, $bulan, $day);
                            $shift = $jadwal->get($dateKey);
                            $isToday = $dateKey === date('Y-m-d');
                            $isWeekend = in_array(date('N', strtotime($dateKey)), [6, 7]); // Saturday = 6, Sunday = 7
                        @endphp

                        @php
                            $isLibur = $shift && $shift->Shift && strtolower($shift->Shift->nama_shift) === 'libur';
                        @endphp
                        <div class="calendar-day {{ $isToday ? 'today' : '' }} {{ $isWeekend ? 'weekend' : '' }} {{ $isLibur ? 'libur' : '' }}"
                             data-date="{{ sprintf('%04d-%02d-%02d', $tahun, $bulan, $day) }}"
                             data-has-shift="{{ $shift ? 'true' : 'false' }}"
                             @if($shift && $shift->Shift)
                             data-shift-name="{{ $shift->Shift->nama_shift ?? 'Shift' }}"
                             data-jam-masuk="{{ date('H:i', strtotime($shift->Shift->jam_masuk)) }}"
                             data-jam-keluar="{{ date('H:i', strtotime($shift->Shift->jam_keluar)) }}"
                             data-lock-location="{{ $shift->lock_location }}"
                             @endif
                             style="cursor: pointer;">
                            <div class="day-number">{{ $day }}</div>

                            @if($shift && $shift->Shift)
                                <div class="shift-info">
                                    <div class="shift-time">
                                        <strong>{{ $shift->Shift->nama_shift ?? 'Shift' }}</strong><br>
                                        {{ date('H:i', strtotime($shift->Shift->jam_masuk)) }} - {{ date('H:i', strtotime($shift->Shift->jam_keluar)) }}
                                    </div>
                                    <div class="location-status">
                                        @if($shift->lock_location == 1)
                                            <span class="badge badge-success badge-sm">🔒 Lock</span>
                                        @else
                                            <span class="badge badge-warning badge-sm">🔓 Bebas</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <div class="no-shift">-</div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>

            <!-- Shift Detail Modal -->
            <div class="modal fade" id="shiftDetailModal" tabindex="-1" aria-labelledby="shiftDetailModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="shiftDetailModalLabel">Detail Shift - <span id="modal-date"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div id="shift-details">
                                <div class="text-center">
                                    <div class="mb-3">
                                        <i class="fas fa-calendar-day fa-3x text-primary"></i>
                                    </div>
                                    <p class="text-muted">Tidak ada jadwal shift pada tanggal ini</p>
                                </div>
                            </div>
                            <div id="shift-info" style="display: none;">
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h6 class="card-title" id="shift-name"></h6>
                                                <div class="row mt-3">
                                                    <div class="col-6">
                                                        <div class="border rounded p-2">
                                                            <small class="text-muted">Jam Masuk</small>
                                                            <br>
                                                            <strong class="text-success" id="jam-masuk"></strong>
                                                        </div>
                                                    </div>
                                                    <div class="col-6">
                                                        <div class="border rounded p-2">
                                                            <small class="text-muted">Jam Keluar</small>
                                                            <br>
                                                            <strong class="text-danger" id="jam-keluar"></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-body text-center">
                                                <h6 class="card-title">Lokasi Absensi</h6>
                                                <div class="mt-2" id="location-status">
                                                    <!-- Status will be populated by JavaScript -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="legend-section">
                        <h6 class="mb-2">Keterangan:</h6>
                        <div class="d-flex flex-wrap gap-2">
                            <div class="legend-item">
                                <span class="badge badge-success badge-sm">🔒 Lock</span>
                                <small>Wajib absen di lokasi kantor</small>
                            </div>
                            <div class="legend-item">
                                <span class="badge badge-warning badge-sm">🔓 Bebas</span>
                                <small>Dapat absen dari lokasi manapun</small>
                            </div>
                                <div class="legend-item">
                                    <span class="badge badge-danger badge-sm">🏖️ Libur</span>
                                    <small>Hari libur - tidak perlu absen</small>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('style')
<style>
/* Header section styling */
.header-section {
    margin-bottom: 20px;
}

.title-mobile {
    margin-bottom: 15px;
}

/* Desktop layout - bulan dan tahun di kanan */
@media (min-width: 769px) {
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .title-mobile {
        margin-bottom: 0;
        margin-left: 15px;
    }

    .month-year-selector {
        display: flex;
        gap: 8px;
        align-items: center;
    }
}

/* Mobile layout - bulan dan tahun di tengah bawah */
@media (max-width: 768px) {
    .header-section {
        text-align: center;
    }

    .title-mobile {
        margin-bottom: 15px;
        font-size: 18px;
    }

    .month-year-selector {
        display: flex;
        justify-content: center;
        gap: 10px;
        align-items: center;
    }

    .month-year-selector .form-control {
        flex: 1;
        max-width: 140px;
        font-size: 14px;
    }
}
.calendar-grid {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden; /* Prevent any scrolling */
}

/* Calendar content sizing */
.calendar-grid .calendar-header,
.calendar-grid .calendar-body {
    width: 100%;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.day-label {
    padding: 8px 4px;
    text-align: center;
    font-weight: 600;
    color: #495057;
    font-size: 12px;
}

.calendar-body {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #dee2e6;
}

.calendar-day {
    background: white;
    min-height: 100px;
    padding: 6px;
    position: relative;
    transition: all 0.2s ease;
    display: flex;
    flex-direction: column;
}

.calendar-day:hover {
    background: #f8f9fa;
    box-shadow: inset 0 0 4px rgba(0,0,0,0.1);
    transform: translateY(-1px);
}

.calendar-day.empty {
    background: #f8f9fa;
}

.calendar-day.today {
    background: #e3f2fd;
    border: 2px solid #2196f3;
}

.calendar-day.weekend {
    background: #fff9c4;
}

.calendar-day.libur {
    background: #ffebee;
    border-left: 4px solid #f44336;
}

.calendar-day.libur .shift-time {
    color: #d32f2f;
}

.calendar-day.libur .location-status .badge {
    background-color: #f44336 !important;
    color: white !important;
}

.day-number {
    font-size: 14px;
    font-weight: 600;
    color: #495057;
    margin-bottom: 2px;
}

.calendar-day.today .day-number {
    color: #2196f3;
}

.shift-info {
    flex: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    margin: 2px 0;
}

.shift-time {
    font-size: 10px;
    color: #495057;
    line-height: 1.2;
    text-align: center;
    margin-bottom: 2px;
}

.location-status {
    display: flex;
    justify-content: center;
}

.location-status .badge {
    font-size: 9px;
    white-space: nowrap;
}

.badge-sm {
    font-size: 9px;
    padding: 1px 3px;
    border-radius: 2px;
}

.badge-lg {
    font-size: 14px;
    padding: 8px 16px;
    border-radius: 6px;
}

/* Ensure badge text is visible */
.badge-success {
    color: white !important;
    background-color: #28a745 !important;
}

.badge-warning {
    color: #212529 !important;
    background-color: #ffc107 !important;
}

.badge-danger {
    color: white !important;
    background-color: #dc3545 !important;
}

.no-shift {
    font-size: 12px;
    color: #adb5bd;
    text-align: center;
    margin-top: 10px;
}

/* Legend styles */
.legend-section h6 {
    margin-bottom: 8px;
    color: #495057;
}

.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
}

.legend-item small {
    color: #6c757d;
    font-size: 12px;
}

/* Action buttons */
.action-buttons {
    margin-top: 15px;
}

/* Modal improvements */
.modal-header .btn-close {
    margin: 0;
}

/* Ensure modal buttons are clickable */
.modal-footer .btn {
    cursor: pointer;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .calendar-day {
        min-height: 90px;
        padding: 4px;
    }

    .day-number {
        font-size: 12px;
    }

    .shift-time {
        font-size: 9px;
    }

    .location-status .badge {
        font-size: 8px;
    }

    .day-label {
        padding: 6px 2px;
        font-size: 11px;
    }

    .legend-section {
        width: 100%;
        margin-top: 15px;
        text-align: center;
    }

    .legend-section .d-flex {
        justify-content: center;
    }

    /* PWA specific adjustments */
    .calendar-grid {
        margin: 0 -10px; /* Reduce margin for PWA */
        padding: 0 10px;
    }
}

/* PWA specific viewport handling */
@media screen and (display-mode: standalone) {
    .calendar-grid {
        margin: 0 -5px;
        padding: 0 5px;
        border-radius: 4px;
    }

    .calendar-day {
        min-height: 85px;
    }
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    $('#bulan, #tahun').change(function() {
        var bulan = $('#bulan').val();
        var tahun = $('#tahun').val();
        window.location.href = '{{ url("/my-jadwal") }}?bulan=' + bulan + '&tahun=' + tahun;
    });

    // Handle calendar day clicks
    $('.calendar-day').on('click touchstart', function(e) {
        e.preventDefault(); // Prevent default touch behavior
        e.stopPropagation(); // Prevent event bubbling

        var date = $(this).data('date');
        var hasShift = $(this).data('has-shift');

        // Format date for display
        var dateObj = new Date(date);
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        var formattedDate = dateObj.toLocaleDateString('id-ID', options);

        $('#modal-date').text(formattedDate);

        if (hasShift) {
            var shiftName = $(this).data('shift-name');
            var jamMasuk = $(this).data('jam-masuk');
            var jamKeluar = $(this).data('jam-keluar');
            var lockLocation = $(this).data('lock-location');
            var isLibur = shiftName.toLowerCase() === 'libur';

            // Show shift information
            $('#shift-name').text(shiftName);
            $('#jam-masuk').text(isLibur ? 'Libur' : jamMasuk);
            $('#jam-keluar').text(isLibur ? 'Libur' : jamKeluar);

            // Show location status
            if (isLibur) {
                $('#location-status').html('<span class="badge badge-danger badge-lg"><i class="fas fa-calendar-times"></i> Hari Libur</span><br><small class="text-muted">Tidak perlu absen</small>');
            } else if (lockLocation == 1) {
                $('#location-status').html('<span class="badge badge-success badge-lg"><i class="fas fa-lock"></i> Lock Lokasi</span><br><small class="text-muted">Wajib absen di lokasi kantor</small>');
            } else {
                $('#location-status').html('<span class="badge badge-warning badge-lg"><i class="fas fa-unlock"></i> Bebas Lokasi</span><br><small class="text-muted">Dapat absen dari lokasi manapun</small>');
            }

            $('#shift-details').hide();
            $('#shift-info').show();
        } else {
            // Show no shift message
            $('#shift-info').hide();
            $('#shift-details').show();
        }

        // Show modal
        var modal = new bootstrap.Modal(document.getElementById('shiftDetailModal'));
        modal.show();
    });

    // Prevent scrolling when touching calendar days
    $('.calendar-day').on('touchmove', function(e) {
        e.stopPropagation();
    });
});
</script>
@endpush
@endsection
