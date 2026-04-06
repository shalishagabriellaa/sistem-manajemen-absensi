@extends('templates.dashboard')
@section('isi')
    <div class="row">
        <div class="col-md-12 m project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 p-0 d-flex mt-2">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">
                        <a href="{{ url('/pegawai') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Penjadwalan Shift {{ $karyawan->name }}</h5>
                            @if(auth()->check())
                                <small class="text-muted">
                                    Login sebagai: <strong>{{ auth()->user()->name }}</strong> |
                                    Role: <strong>{{ auth()->user()->roles->pluck('name')->join(', ') }}</strong>
                                    @if(auth()->user()->hasRole('admin'))
                                        <span class="badge badge-success">✓ Admin Access</span>
                                    @else
                                        <span class="badge badge-warning">⚠ Limited Access</span>
                                    @endif
                                </small>
                            @endif
                        </div>
                        <div class="d-flex gap-2">
                            <!-- Mode Toggle -->
                            <div class="btn-group btn-group-sm" role="group" style="gap: 8px; display: flex;">
                                <input type="radio" class="btn-check" name="scheduling-mode" id="mode-range" autocomplete="off" checked>
                                <label class="btn btn-outline-primary" for="mode-range" style="margin-right: 8px;">Range Tanggal</label>

                                <input type="radio" class="btn-check" name="scheduling-mode" id="mode-calendar" autocomplete="off">
                                <label class="btn btn-outline-primary" for="mode-calendar">Kalender Bulanan</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Range Date Mode -->
                    <div id="range-mode" class="scheduling-mode">
                        <form method="post" action="{{ url('/pegawai/shift/proses-tambah-shift') }}" id="range-form">
                        @csrf
                            <input type="hidden" name="user_id" value="{{ $karyawan->id }}">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                <label for="shift_id" class="float-left">Shift</label>
                                <select class="form-control selectpicker @error('shift_id') is-invalid @enderror" id="shift_id" name="shift_id" data-live-search="true">
                                    <option value="">Pilih Shift</option>
                                    @foreach ($shift as $s)
                                        @if(old('shift_id') == $s->id)
                                            <option value="{{ $s->id }}" selected>{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                        @else
                                            <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('shift_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                                    <div class="form-group mb-3">
                                <label for="tanggal_mulai" class="float-left">Tanggal Mulai</label>
                                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror" id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                                @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="tanggal_akhir" class="float-left">Tanggal Akhir</label>
                                        <input type="date" class="form-control @error('tanggal_akhir') is-invalid @enderror" id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                                        @error('tanggal_akhir')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                                    <div class="form-check mb-3">
                                        <input name="lock_location" class="form-check-input lock_location" type="checkbox" value="1" id="lock_location_range">
                                        <label class="form-check-label" for="lock_location_range">
                                            Lock Location
                                        </label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-4">
                                        <label class="float-left">Hari Libur</label>
                                        <div class="card">
                                            <div class="card-body p-3">
                                                <div class="row g-2">
                                                    <div class="col-12">
                                                        <div class="row row-cols-2 row-cols-md-4 g-2">
                                                            @php
                                                                $daysOfWeek = ['Monday' => 'Senin', 'Tuesday' => 'Selasa', 'Wednesday' => 'Rabu', 'Thursday' => 'Kamis', 'Friday' => 'Jumat', 'Saturday' => 'Sabtu', 'Sunday' => 'Minggu'];
                                                            @endphp
                                                            @foreach ($daysOfWeek as $englishDay => $indonesianDay)
                                                                <div class="col">
                                                                    <div class="form-check mb-2">
                                                                        <input class="form-check-input holiday-day" type="checkbox" value="{{ $englishDay }}" id="holiday_range_{{ $englishDay }}" name="holiday_days[]">
                                                                        <label class="form-check-label" for="holiday_range_{{ $englishDay }}">
                                                                            <span class="fw-semibold">{{ $indonesianDay }}</span>
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mt-2">
                                                    <small class="form-text text-muted">
                                                        <i class="fas fa-info-circle"></i> Hari yang dicentang akan dijadwalkan sebagai hari libur
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end">
                                @if ($errors->any())
                                    <div class="alert alert-danger mb-3">
                                        <strong>Error:</strong>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger mb-3">
                                        <strong>Error:</strong> {{ session('error') }}
                                    </div>
                                @endif

                                @if (session('success'))
                                    <div class="alert alert-success mb-3">
                                        <strong>Success:</strong> {{ session('success') }}
                                    </div>
                                @endif

                                <button type="submit" class="btn btn-success" id="range-submit-btn">
                                    <i class="fas fa-save"></i> Simpan Shift Range
                                </button>
                                <a href="{{ url('/pegawai') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                            </div>
                        </form>
                    </div>

                    <!-- Calendar Mode -->
                    <div id="calendar-mode" class="scheduling-mode" style="display: none;">
                        <form method="post" action="{{ url('/pegawai/shift/proses-tambah-shift-bulk') }}" id="calendar-form">
                            @csrf
                            <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                            <input type="hidden" name="bulan" id="form-bulan" value="{{ date('m') }}">
                            <input type="hidden" name="tahun" id="form-tahun" value="{{ date('Y') }}">

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">Pilih Bulan & Tahun:</h6>
                                    <div class="d-flex gap-2">
                                        <select id="bulan" class="form-control" style="min-width: 140px;">
                                            @php
                                                $namaBulan = [
                                                    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
                                                    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
                                                    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
                                                ];
                                            @endphp
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ $i == date('m') ? 'selected' : '' }}>
                                                    {{ $namaBulan[$i] }}
                                                </option>
                                            @endfor
                                        </select>
                                        <select id="tahun" class="form-control" style="min-width: 100px;">
                                            @for($i = date('Y') - 1; $i <= date('Y') + 5; $i++)
                                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Shift Selection Modal -->
                            <div class="modal fade" id="shiftSelectionModal" tabindex="-1" aria-labelledby="shiftSelectionModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="shiftSelectionModalLabel">Pilih Shift untuk Tanggal <span id="selected-date-text"></span></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="shift-select-modal" class="form-label fw-bold">
                                                    <i class="fas fa-clock"></i> Pilih Shift
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
                                                    <i class="fas fa-map-marker-alt"></i> Lock Location
                                                </label>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="lock-location-modal">
                                                    <label class="form-check-label" for="lock-location-modal">
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

                                <div class="calendar-body" id="calendar-body">
                                    <!-- Calendar will be populated by JavaScript -->
                                </div>
                            </div>

                            <!-- Shift Configuration Panel -->
                            <div id="shift-config-panel" class="mt-4" style="display: none;">
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Panel Konfigurasi Shift</strong><br>
                                    Silakan pilih shift dan pengaturan lock location untuk tanggal yang dipilih.
                                </div>
                            </div>

                            <div class="mt-4">
                                <!-- Bulk Shift Selection for Selected Dates -->
                                <div id="bulk-shift-selection" style="display: none;">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0"><i class="fas fa-cogs"></i> Konfigurasi Shift untuk Tanggal Terpilih</h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <label for="bulk_shift_select" class="form-label fw-bold">
                                                            <i class="fas fa-clock"></i> Pilih Shift
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
                                                    <div class="form-group mb-3">
                                                        <label class="form-label fw-bold">
                                                            <i class="fas fa-map-marker-alt"></i> Lock Location
                                                        </label>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" value="1" id="bulk_lock_location">
                                                            <label class="form-check-label" for="bulk_lock_location">
                                                                <strong>Kunci lokasi absensi</strong> untuk semua tanggal terpilih
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="selected-dates-summary mt-3 p-3 bg-light rounded">
                                                <small class="text-muted">
                                                    <i class="fas fa-calendar-check"></i>
                                                    <span id="selected-count">0</span> tanggal dipilih - klik "Terapkan Shift" untuk menyimpan konfigurasi
                                                </small>
                                            </div>
                                            <div class="mt-3">
                                                <button type="button" class="btn btn-success me-2" id="apply-shift-to-selected">
                                                    <i class="fas fa-check"></i> Terapkan Shift
                                                </button>
                                                <button type="button" class="btn btn-secondary" id="clear-selection">
                                                    <i class="fas fa-times"></i> Bersihkan Pilihan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="alert alert-info" role="alert">
                                    <i class="fas fa-info-circle"></i>
                                    <strong>Cara Penggunaan:</strong>
                                    <ol class="mb-0 mt-2">
                                        <li>Klik tanggal yang ingin dijadwalkan (akan berwarna hijau)</li>
                                        <li>Pilih shift dan lock location di panel konfigurasi</li>
                                        <li>Klik "Terapkan Shift" - tanggal akan berubah warna sesuai shift</li>
                                        <li>Klik icon ❌ di shift untuk menghapus shift langsung</li>
                                        <li>Klik "Simpan" untuk menyimpan semua jadwal</li>
                                        <li><strong>Untuk Range Tanggal:</strong> Pilih shift utama, centang hari libur, dan sistem akan otomatis jadwalkan hari libur dengan shift "Libur"</li>
                                    </ol>
                                </div>

                                <div class="row">
                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-success" id="submit-shifts" disabled>
                                            <i class="fas fa-save"></i> Simpan Semua Shift
                                        </button>
                                        <a href="{{ url('/pegawai') }}" class="btn btn-secondary ms-2">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                    </div>
                                </div>
                            </div>
                    </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <center>
                        <h3>{{ $karyawan->name }}</h3>
                    </center>
                    <hr>
                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <form action="{{ url('/pegawai/shift/'.$karyawan->id) }}" method="GET" class="filter-form row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Tanggal Mulai</label>
                                    <input type="date" class="form-control" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Tanggal Akhir</label>
                                    <input type="date" class="form-control" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Tampilkan</label>
                                    <select name="per_page" class="form-control">
                                        <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                                        <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                                        <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                                        <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
                                        <option value="200" {{ request('per_page', 10) == 200 ? 'selected' : '' }}>200</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-search"></i> Cari
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <a href="{{ url('/pegawai/shift/'.$karyawan->id) }}" class="btn btn-secondary w-100">
                                        <i class="fas fa-times"></i> Reset
                                    </a>
                                </div>
                            </form>
                            </div>
                        <div class="col-md-6 text-end">
                            <button type="button" class="btn btn-warning btn-sm me-2" id="open-bulk-edit" disabled>Edit Massal</button>
                            <form id="bulk-delete-form" method="post" action="{{ url('/pegawai/shift/bulk-delete') }}" class="d-inline">
                                @csrf
                                <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                                <span class="selected-ids-delete"></span>
                                <button type="button" class="btn btn-danger btn-sm" id="bulk-delete-button" disabled>Hapus Terpilih</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Legend for holiday shifts -->
                    <div class="alert alert-light border mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-info-circle text-primary me-2"></i>
                            <small class="mb-0">
                                <strong>Legenda:</strong>
                                <span class="badge badge-danger ms-2">
                                    Libur
                                </span>
                                <span class="text-muted">= Hari libur dengan background merah muda</span>
                            </small>
                        </div>
                    </div>

                    @if(request('tanggal_mulai') || request('tanggal_akhir') || request('per_page'))
                        <div class="alert alert-info mb-3">
                            <small>
                                <i class="fas fa-filter"></i>
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
                                <a href="{{ url('/pegawai/shift/'.$karyawan->id) }}" class="float-end text-decoration-none">
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
                                                <span class="badge badge-danger holiday-badge">
                                                    {{ $sk->Shift->nama_shift ?? '-' }}
                                                </span>
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
                                                <!-- <li class="delete">
                                                    <form action="{{ url('/pegawai/delete-shift/'.$sk->id) }}" method="post" class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                                                        <button class="border-0" style="background-color: transparent;"  onClick="return confirm('Are You Sure')"><i class="fa fa-solid fa-trash"></i></button>
                                                    </form>
                                                </li> -->
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
    <div class="modal fade" id="bulkEditModal" tabindex="-1" aria-labelledby="bulkEditModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkEditModalLabel">Edit Shift Massal</h5>
                    <button type="button" class="close bulk-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bulk-edit-form" method="post" action="{{ url('/pegawai/shift/bulk-update') }}">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                    <div class="modal-body">
                        <div class="selected-ids-edit"></div>
                        <div class="form-group mb-3">
                            <label for="bulk_shift_id" class="float-left">Shift</label>
                            <select class="form-control selectpicker" id="bulk_shift_id" name="shift_id" data-live-search="true" required>
                                <option value="">Pilih Shift</option>
                                @foreach ($shift as $s)
                                    <option value="{{ $s->id }}">{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-check">
                            <input name="lock_location" class="form-check-input" type="checkbox" value="1" id="bulk_lock_location">
                            <label class="form-check-label" for="bulk_lock_location">
                                Terapkan Lock Location
                            </label>
                        </div>
                        <small class="text-muted">Perubahan akan diterapkan ke semua data yang dipilih.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary bulk-modal-close" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('style')
<style>
.scheduling-mode {
    display: none;
}

.scheduling-mode.active {
    display: block;
}

.calendar-grid {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
    margin: 20px 0;
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: #f8f9fa;
    border-bottom: 1px solid #dee2e6;
}

.day-label {
    padding: 12px 8px;
    text-align: center;
    font-weight: 600;
    color: #495057;
    font-size: 14px;
}

.calendar-body {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 1px;
    background: #dee2e6;
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
    background: #f8f9fa;
}

.calendar-day.today {
    background: #e3f2fd;
    border: 2px solid #2196f3;
}

.calendar-day.weekend {
    background: #fff9c4;
}

.day-number {
    font-size: 16px;
    font-weight: 600;
    color: #495057;
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

.selection-status {
    text-align: center;
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

.selected-indicator i {
    font-size: 11px;
}

.selected-indicator small {
    font-size: 9px;
    line-height: 1;
}

/* Clickable calendar days */
.selectable-day {
    transition: all 0.2s ease;
}

.selectable-day:hover {
    background: rgba(59, 130, 246, 0.1) !important;
    transform: scale(1.02);
}

.selectable-day:active {
    transform: scale(0.98);
}

/* Calendar day selected state */
.calendar-day.selected {
    background: rgba(25, 135, 84, 0.1) !important;
    border: 2px solid #198754 !important;
}

/* Calendar day scheduled state (has applied shift) */
.calendar-day.scheduled {
    border: 2px solid #28a745 !important;
}

/* Different colors for each shift */
.calendar-day.shift-color-0 { background: rgba(108, 117, 125, 0.15) !important; border-color: #6c757d !important; }
.calendar-day.shift-color-1 { background: rgba(40, 167, 69, 0.15) !important; border-color: #28a745 !important; }
.calendar-day.shift-color-2 { background: rgba(0, 123, 255, 0.15) !important; border-color: #007bff !important; }
.calendar-day.shift-color-3 { background: rgba(255, 193, 7, 0.15) !important; border-color: #ffc107 !important; }
.calendar-day.shift-color-4 { background: rgba(220, 53, 69, 0.15) !important; border-color: #dc3545 !important; }
.calendar-day.shift-color-5 { background: rgba(111, 66, 193, 0.15) !important; border-color: #6f42c1 !important; }
.calendar-day.shift-color-6 { background: rgba(253, 126, 20, 0.15) !important; border-color: #fd7e14 !important; }
.calendar-day.shift-color-7 { background: rgba(32, 201, 151, 0.15) !important; border-color: #20c997 !important; }

/* Hover effect for shifts */
.calendar-day.has-shift:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Delete icon styling */
.delete-shift-icon {
    transition: all 0.2s ease;
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

/* Bulk selection card styling */
#bulk-shift-selection .card {
    border-width: 2px;
}

#bulk-shift-selection .card-header {
    border-bottom: none;
}

.selected-dates-summary {
    border-left: 4px solid #198754;
}

.day-status {
    margin-top: 4px;
    font-size: 10px;
}

.selected-shift-info {
    margin-bottom: 2px;
}

.day-status .badge {
    font-size: 9px;
    padding: 2px 4px;
}

/* Mode Toggle Styling */
.btn-group .btn {
    border-radius: 6px !important;
}

.btn-check:checked + .btn {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

/* Form Styling */
.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
    display: block;
}

/* Filter form styling */
.filter-form .form-label {
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.filter-form .form-control {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.filter-form .btn {
    font-size: 0.9rem;
    padding: 0.5rem 1rem;
    font-weight: 500;
}

/* Alert styling for active filters */
.alert-info {
    border-left: 4px solid #17a2b8;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .calendar-day {
        min-height: 120px;
        padding: 6px;
    }

    .day-number {
        font-size: 14px;
    }

    .shift-selector select {
        font-size: 10px;
    }

    .d-flex.gap-2 {
        flex-wrap: wrap;
        gap: 0.5rem !important;
    }

    /* Filter form responsive */
    .filter-form .col-md-3,
    .filter-form .col-md-2 {
        margin-bottom: 1rem;
    }

    .filter-form .form-label {
        font-size: 0.85rem;
    }

    .filter-form .form-control {
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
    }

    .filter-form .btn {
        font-size: 0.85rem;
        padding: 0.4rem 0.8rem;
    }

    /* Holiday Shift Styling */
    .holiday-shift-row {
        background-color: #fff5f5 !important;
        border-left: 4px solid #dc3545;
    }

    .holiday-shift-row:hover {
        background-color: #ffeaea !important;
    }

    .holiday-badge {
        background-color: #dc3545 !important;
        color: white !important;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 0.375rem 0.75rem;
        border-radius: 0.375rem;
    }
}
</style>
@endpush

    @push('script')
        <script>
$(document).ready(function() {
    // Shift options data with colors
    const shiftOptions = [
        {id: '', name: 'Tidak Ada Shift', color: '#6c757d'},
        @foreach ($shift as $index => $s)
        {id: '{{ $s->id }}', name: '{{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ") " }}', color: '{{ ["#28a745", "#007bff", "#ffc107", "#dc3545", "#6f42c1", "#fd7e14", "#20c997", "#e83e8c"][$index % 8] }}'},
        @endforeach
    ];

    // Store selected dates with their shift configurations
    let selectedDatesData = {};

    // Mode switching
    $('input[name="scheduling-mode"]').change(function() {
        const selectedMode = $(this).attr('id');

        // Hide all modes
        $('.scheduling-mode').removeClass('active').hide();

        // Show selected mode
        if (selectedMode === 'mode-range') {
            $('#range-mode').addClass('active').show();
        } else if (selectedMode === 'mode-calendar') {
            $('#calendar-mode').addClass('active').show();
            // Initialize calendar if not already done
            if ($('#calendar-body').children().length === 0) {
                generateCalendar(parseInt($('#bulan').val()), parseInt($('#tahun').val()));
            }
        }
    });

    // Initialize with range mode active
    $('#range-mode').addClass('active').show();

    // Generate calendar
    function generateCalendar(bulan, tahun) {
        const calendarBody = $('#calendar-body');
        calendarBody.empty();

        const daysInMonth = new Date(tahun, bulan, 0).getDate();
        const firstDayOfMonth = new Date(tahun, bulan - 1, 1).getDay();

        // Adjust for Monday start (0 = Sunday, 1 = Monday, etc.)
        const adjustedFirstDay = firstDayOfMonth === 0 ? 6 : firstDayOfMonth - 1;

        // Add empty cells for days before the first day
        for (let i = 0; i < adjustedFirstDay; i++) {
            calendarBody.append('<div class="calendar-day empty"></div>');
        }

        // Add days of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const dateStr = `${tahun}-${String(bulan).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
            const isToday = dateStr === new Date().toISOString().split('T')[0];
            const dayOfWeek = new Date(tahun, bulan - 1, day).getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6; // Sunday = 0, Saturday = 6

            let weekendClass = '';
            if (isWeekend) {
                weekendClass = 'weekend';
            }

            // Check if this date is already selected
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
                            <div class="selected-shift-info">
                                ${shiftDisplay}
                            </div>
                            <small class="text-success fw-bold">Terjadwal</small>
                        </div>
                    </div>
                </div>
            `;

            calendarBody.append(dayHtml);
        }

        // Update form hidden inputs
        $('#form-bulan').val(bulan);
        $('#form-tahun').val(tahun);
    }

    // Handle month/year change in calendar mode
    $('#bulan, #tahun').change(function() {
        const bulan = parseInt($('#bulan').val());
        const tahun = parseInt($('#tahun').val());
        generateCalendar(bulan, tahun);
        updateSelectedDatesSummary();
    });

    // Update calendar display after selections change
    function updateCalendarDisplay() {
        $('.selectable-day').each(function() {
            const calendarDay = $(this);
            const date = calendarDay.data('date');
            const daySelector = calendarDay.find('.day-selector');
            const selectedIndicator = daySelector.find('.selected-indicator');
            const statusText = daySelector.find('.status-text');
            const shiftInfo = selectedIndicator.find('.selected-shift-info');

            const dateData = selectedDatesData[date];

            // Remove all shift color classes first
            calendarDay.removeClass('shift-color-0 shift-color-1 shift-color-2 shift-color-3 shift-color-4 shift-color-5 shift-color-6 shift-color-7');

            if (dateData && dateData.applied) {
                // Date has applied shift - show as scheduled with shift color
                const shiftOption = shiftOptions.find(s => s.id == dateData.shift_id);
                const shiftColorIndex = shiftOptions.findIndex(s => s.id == dateData.shift_id);
                const colorClass = shiftColorIndex >= 0 ? `shift-color-${shiftColorIndex % 8}` : 'shift-color-0';

                calendarDay.addClass(`scheduled ${colorClass}`).removeClass('selected');
                statusText.hide();

                const shiftName = shiftOption ? shiftOption.name : 'Tidak Ada Shift';
                const lockIcon = dateData.lock_location == 1 ? ' 🔒' : '';
                const deleteIcon = '<i class="fas fa-times delete-shift-icon" style="color: #dc3545; cursor: pointer; position: absolute; top: 4px; right: 4px; z-index: 10;" title="Hapus shift"></i>';
                const shiftDisplay = `<small class="fw-bold">${shiftName}${lockIcon}${deleteIcon}</small>`;
                shiftInfo.html(shiftDisplay);
                selectedIndicator.show();

                // Make it clickable for edit/delete
                calendarDay.addClass('has-shift').css('cursor', 'pointer');
            } else if (dateData && !dateData.applied) {
                // Date is selected but not applied yet
                calendarDay.addClass('selected').removeClass('scheduled');
                statusText.hide();
                shiftInfo.html('<small class="text-primary fw-bold">Dipilih</small>');
                selectedIndicator.show();
                calendarDay.removeClass('has-shift').css('cursor', 'pointer');
            } else {
                // Date is not selected
                calendarDay.removeClass('selected').removeClass('scheduled').removeClass('has-shift');
                selectedIndicator.hide();
                statusText.show();
                calendarDay.css('cursor', 'pointer');
            }
        });
    }

    // Update selected dates summary (no longer used - everything is in calendar)
    function updateSelectedDatesSummary() {
        // No longer needed - all shifts are displayed directly in calendar
    }

    // Handle apply shift to selected dates
    $('#apply-shift-to-selected').on('click', function() {
        const selectedDates = $('.calendar-day.selected');
        const shiftId = $('#bulk_shift_select').val();
        const lockLocation = $('#bulk_lock_location').is(':checked') ? 1 : 0;

        // Validation
        if (selectedDates.length === 0) {
            alert('Pilih tanggal terlebih dahulu.');
            return;
        }

        if (!shiftId) {
            alert('Pilih shift terlebih dahulu.');
            $('#bulk_shift_select').focus();
            return;
        }

        // Apply shift to all selected dates
        selectedDates.each(function() {
            const date = $(this).data('date');
            selectedDatesData[date] = {
                shift_id: shiftId,
                lock_location: lockLocation,
                applied: true
            };
        });

        // Reset bulk selection controls
        $('#bulk_shift_select').val('');
        $('#bulk_lock_location').prop('checked', false);

        // Update displays
        updateCalendarDisplay();
        updateBulkSelectionVisibility();
        updateSubmitButton();
    });

    // Handle clear selection
    $('#clear-selection').on('click', function() {
        $('.calendar-day.selected').each(function() {
            const calendarDay = $(this);
            calendarDay.removeClass('selected');
            calendarDay.find('.status-text').show();
            calendarDay.find('.selected-indicator').hide();
        });

        updateBulkSelectionVisibility();
        updateSubmitButton();
    });

    // Handle remove date from summary (change shift)
    $('body').on('click', '.remove-date', function() {
        const date = $(this).data('date');

        // Remove from applied data
        delete selectedDatesData[date];

        // Re-enable the calendar day for selection
        const calendarDay = $(`.selectable-day[data-date="${date}"]`);
        calendarDay.removeClass('selected');
        calendarDay.find('.status-text').show();
        calendarDay.find('.selected-indicator').hide();

        updateCalendarDisplay();
        updateSubmitButton();
    });

    // Handle calendar day click - toggle selection
    $('body').on('click', '.selectable-day', function(e) {
        // Prevent event bubbling and default behavior
        e.preventDefault();
        e.stopPropagation();

        const calendarDay = $(this);
        const date = calendarDay.data('date');

        // If date already has shift assigned, don't allow reselection
        if (selectedDatesData[date] && selectedDatesData[date].applied) {
            return; // Do nothing - date already scheduled
        }

        // Toggle selection state for empty dates
        const isSelected = calendarDay.hasClass('selected');

        if (isSelected) {
            // Deselect
            calendarDay.removeClass('selected');
            calendarDay.find('.status-text').show();
            calendarDay.find('.selected-indicator').hide();
        } else {
            // Select
            calendarDay.addClass('selected');
            calendarDay.find('.status-text').hide();
            calendarDay.find('.selected-indicator').show();
        }

        updateBulkSelectionVisibility();
        updateSubmitButton();
    });

    // Handle delete shift icon click
    $('body').on('click', '.delete-shift-icon', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Find the parent calendar day
        const calendarDay = $(this).closest('.selectable-day');
        const date = calendarDay.data('date');

        // Delete shift directly without confirmation
        delete selectedDatesData[date];
        updateCalendarDisplay();
        updateSubmitButton();
    });

    // Update submit button state
    function updateSubmitButton() {
        const selectedDates = Object.keys(selectedDatesData).filter(date => selectedDatesData[date].applied).length;
        const submitBtn = $('#submit-shifts');

        // Enable button only if dates have applied shifts
        const shouldEnable = selectedDates > 0;
        submitBtn.prop('disabled', !shouldEnable);

        if (selectedDates === 0) {
            submitBtn.html('<i class="fas fa-save"></i> Jadwalkan Shift Dahulu');
        } else {
            submitBtn.html(`<i class="fas fa-save"></i> Simpan ${selectedDates} Shift`);
        }
    }

    // Show/hide bulk shift selection based on selected dates
    function updateBulkSelectionVisibility() {
        const selectedCount = $('.calendar-day.selected').length;
        const bulkSelection = $('#bulk-shift-selection');
        const configPanel = $('#shift-config-panel');

        if (selectedCount > 0) {
            configPanel.show();
            bulkSelection.show();
            $('#selected-count').text(selectedCount);

            // Scroll to the configuration panel
            setTimeout(() => {
                configPanel[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
        } else {
            configPanel.hide();
            bulkSelection.hide();
            // Reset bulk selections
            $('#bulk_shift_select').val('');
            $('#bulk_lock_location').prop('checked', false);
        }
    }

    // Calendar form submission - use individual shift selections
    $('#calendar-form').submit(function(e) {
        e.preventDefault();

        const selectedDates = Object.keys(selectedDatesData);

        // Validation
        if (selectedDates.length === 0) {
            alert('Pilih tanggal yang ingin dijadwalkan terlebih dahulu.');
            return false;
        }

        // Use the collected shifts data
        const shiftsData = selectedDatesData;

        // Add shifts data to form
        const formData = new FormData(this);
        formData.append('shifts_data', JSON.stringify(shiftsData));

        // Show loading state
        const submitBtn = $('#submit-shifts');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        // Submit form
        $.ajax({
            url: $(this).attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert(selectedDates.length + ' shift berhasil disimpan!');
                location.reload();
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Unknown error'));
                submitBtn.prop('disabled', false).html(originalText);
            }
        });
    });

    // Range form validation and enhancement
    $('#range-form').submit(function(e) {
        console.log('Range form submitted'); // Debug log

        const tanggalMulai = $('#tanggal_mulai').val();
        const tanggalAkhir = $('#tanggal_akhir').val();
        const shiftId = $('#shift_id').val();
        const userId = $('input[name="user_id"]').val();
        const csrfToken = $('input[name="_token"]').val();
        const holidayDays = $('.holiday-day:checked').map(function() { return $(this).val(); }).get();

        // Find the "Libur" shift ID
        let holidayShiftId = '';
        @foreach ($shift as $s)
            @if(strtolower($s->nama_shift) === 'libur')
                holidayShiftId = '{{ $s->id }}';
            @endif
        @endforeach

        console.log('Form data:', {
            tanggalMulai,
            tanggalAkhir,
            shiftId,
            holidayDays,
            holidayShiftId,
            userId,
            csrfToken: csrfToken ? 'Present' : 'Missing'
        }); // Debug log

        if (!shiftId) {
            e.preventDefault();
            alert('Pilih shift terlebih dahulu.');
            return false;
        }

        if (!tanggalMulai || !tanggalAkhir) {
            e.preventDefault();
            alert('Pilih tanggal mulai dan tanggal akhir.');
            return false;
        }

        if (new Date(tanggalMulai) > new Date(tanggalAkhir)) {
            e.preventDefault();
            alert('Tanggal mulai tidak boleh lebih besar dari tanggal akhir.');
            return false;
        }

        // Validate holiday days - if any days are selected as holiday, must have a holiday shift
        if (holidayDays.length > 0 && !holidayShiftId) {
            e.preventDefault();
            alert('Shift "Libur" tidak ditemukan. Silakan hubungi administrator untuk membuat shift libur terlebih dahulu.');
            return false;
        }

        // Show loading state
        const submitBtn = $('#range-submit-btn');
        const originalText = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        // Re-enable button after 10 seconds if no response (fallback)
        setTimeout(() => {
            submitBtn.prop('disabled', false).html(originalText);
            console.log('Form submit timeout - button re-enabled');
        }, 10000);
    });

    // Checkbox functionality for bulk operations
    function collectSelectedIds() {
        var ids = [];
        $('.row-checkbox:checked').each(function () {
            ids.push($(this).val());
        });
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
        ids.forEach(function (id) {
            container.append('<input type="hidden" name="ids[]" value="'+id+'">');
        });
    }

    // Select all checkbox functionality
    $('#select_all').on('change', function () {
        $('.row-checkbox').prop('checked', this.checked);
        syncBulkButtons();
    });

    // Individual row checkbox functionality
    $('body').on('change', '.row-checkbox', function () {
        var allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
        $('#select_all').prop('checked', allChecked);
        syncBulkButtons();
    });

    // Open bulk edit modal
    $('#open-bulk-edit').on('click', function () {
        var ids = collectSelectedIds();
        if (ids.length === 0) {
            alert('Pilih data terlebih dahulu.');
            return;
        }
        fillSelectedIds('.selected-ids-edit', ids);
        $('#bulk_shift_id').val('').change();
        $('#bulk_lock_location').prop('checked', false);
        $('#bulkEditModal').modal('show');
    });

    // Close bulk edit modal
    $('body').on('click', '.bulk-modal-close', function () {
        $('#bulkEditModal').modal('hide');
    });

    // Bulk delete functionality
    $('#bulk-delete-button').on('click', function () {
        var ids = collectSelectedIds();
        if (ids.length === 0) {
            alert('Pilih data terlebih dahulu.');
            return;
        }
        fillSelectedIds('.selected-ids-delete', ids);
        if (confirm('Hapus data yang dipilih?')) {
            $('#bulk-delete-form').submit();
        }
    });

    // Initialize bulk buttons state
    syncBulkButtons();

    // Initialize calendar components
    updateBulkSelectionVisibility();
    updateSubmitButton();
            });
        </script>
    @endpush
@endsection
