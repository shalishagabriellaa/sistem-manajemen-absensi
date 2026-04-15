@extends('templates.app')
@section('container')

<style>
    .menu-tabs.tabs-food-news {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        border-bottom: 2px solid #e0e0e0;
    }
    .menu-tabs.tabs-food-news::-webkit-scrollbar {
        display: none;
    }
    .menu-tabs.tabs-food-news .nav-tab {
        flex-shrink: 0;
        font-size: 13px;
        padding: 12px 18px;
        cursor: pointer;
        color: #6b7280;
        border-bottom: 2px solid transparent;
        margin-bottom: -2px;
    }
    .menu-tabs.tabs-food-news .nav-tab.active {
        color: #003366;
        font-weight: 600;
        border-bottom: 2px solid #003366;
    }
</style>

    <div id="app-wrap" class="style1">
        <div class="tf-container">
            <div class="tf-tab">
                <ul class="menu-tabs tabs-food-news">
                    <li class="nav-tab active">Informasi</li>
                    <li class="nav-tab">Kontrak</li>
                    <li class="nav-tab">Slip Gaji</li>
                    <li class="nav-tab">+ Gaji</li>
                    <li class="nav-tab">- Gaji</li>
                    <li class="nav-tab">Cuti</li>
                </ul>
                <div class="content-tab pt-tab-space mb-5">

                    {{-- TAB 1: INFORMASI --}}
                    <div class="app-wrap active-content">
                        <div class="bill-content">
                            <div class="tf-container">
                                <ul class="mb-5">
                                    <div class="card-secton transfer-section mt-2">
                                        <div class="tf-container">
                                            <div class="tf-balance-box">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="inner-left d-flex justify-content-between align-items-center">
                                                        @if(auth()->user()->foto_karyawan == null)
                                                            <img src="{{ url('assets/img/foto_default.jpg') }}" alt="image">
                                                        @else
                                                            <img src="{{ asset(auth()->user()->foto_karyawan) }}" alt="image">
                                                        @endif
                                                        <p class="fw_7 on_surface_color">{{ auth()->user()->name }}</p>
                                                    </div>
                                                    <p class="fw_7 on_surface_color">{{ auth()->user()->Jabatan->nama_jabatan }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tf-spacing-20"></div>
                                    <form class="tf-form" action="{{ url('/my-profile/update/'.auth()->user()->id) }}" enctype="multipart/form-data" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <div class="tf-container">
                                            <h3>Informasi Pegawai</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Nama Pegawai</label>
                                                <input type="text" readonly class="@error('name') is-invalid @enderror" name="name" value="{{ old('name', auth()->user()->name) }}" />
                                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input input-group">
                                                <input type="file" class="form-control @error('foto_karyawan') is-invalid @enderror" name="foto_karyawan" />
                                                <span class="input-group-text">Foto</span>
                                                @error('foto_karyawan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                <input type="hidden" name="foto_karyawan_lama" value="{{ auth()->user()->foto_karyawan }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Email</label>
                                                <input type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email', auth()->user()->email) }}" />
                                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor Handphone</label>
                                                <input type="text" class="@error('telepon') is-invalid @enderror" name="telepon" value="{{ old('telepon', auth()->user()->telepon) }}" />
                                                @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Username</label>
                                                <input type="text" readonly class="@error('username') is-invalid @enderror" name="username" value="{{ old('username', auth()->user()->username) }}" />
                                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Lokasi Kantor</label>
                                                <select name="lokasi_id" id="lokasi_id" class="@error('lokasi_id') is-invalid @enderror" disabled>
                                                    @foreach ($data_lokasi as $dl)
                                                        <option value="{{ $dl->id }}" {{ old('lokasi_id', auth()->user()->lokasi_id) == $dl->id ? 'selected' : '' }}>{{ $dl->nama_lokasi }}</option>
                                                    @endforeach
                                                </select>
                                                @error('lokasi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Tanggal Lahir</label>
                                                <input type="text" readonly class="@error('tgl_lahir') is-invalid @enderror" name="tgl_lahir" value="{{ old('tgl_lahir', auth()->user()->tgl_lahir) }}">
                                                @error('tgl_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Alamat</label>
                                                <textarea name="alamat" class="@error('alamat') is-invalid @enderror">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                                                @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Tanggal Masuk Perusahaan</label>
                                                <input type="text" readonly name="tgl_join" value="{{ old('tgl_join', auth()->user()->tgl_join) }}" disabled>
                                            </div>
                                            <div class="group-input">
                                                @php
                                                    if (auth()->user()->tgl_join) {
                                                        $startDate   = \Carbon\Carbon::createFromFormat('Y-m-d', auth()->user()->tgl_join, config('app.timezone'));
                                                        $currentDate = \Carbon\Carbon::now(config('app.timezone'));
                                                        if ($startDate->greaterThan($currentDate)) {
                                                            $masa_kerja = "0 Tahun, 0 Bulan, 0 Hari.";
                                                        } else {
                                                            $d = $currentDate->diff($startDate);
                                                            $masa_kerja = "{$d->y} Tahun, {$d->m} Bulan, {$d->d} Hari.";
                                                        }
                                                    } else {
                                                        $masa_kerja = '';
                                                    }
                                                @endphp
                                                <label>Masa Kerja</label>
                                                <input type="text" value="{{ $masa_kerja }}" disabled>
                                            </div>
                                            <div class="group-input">
                                                <label>Gender</label>
                                                <input type="text" readonly name="gender" value="{{ old('gender', auth()->user()->gender) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Level User</label>
                                                <select name="is_admin" disabled>
                                                    <option value="admin" {{ auth()->user()->is_admin == 'admin' ? 'selected' : '' }}>admin</option>
                                                    <option value="user" {{ auth()->user()->is_admin == 'user' ? 'selected' : '' }}>user</option>
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                @php
                                                    $sNikah = ['TK/0','TK/1','K/0','TK/2','K/1','TK/3','K/2','K/3'];
                                                @endphp
                                                <label>Status Pernikahan</label>
                                                <select name="status_nikah" class="@error('status_nikah') is-invalid @enderror">
                                                    <option value="">Pilih Status Pernikahan</option>
                                                    @foreach ($sNikah as $s)
                                                        <option value="{{ $s }}" {{ old('status_nikah', auth()->user()->status_nikah) == $s ? 'selected' : '' }}>{{ $s }}</option>
                                                    @endforeach
                                                </select>
                                                @error('status_nikah')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Divisi</label>
                                                <select name="jabatan_id" disabled>
                                                    <option value="">Pilih Divisi</option>
                                                    @foreach ($data_jabatan as $dj)
                                                        <option value="{{ $dj->id }}" {{ old('jabatan_id', auth()->user()->jabatan_id) == $dj->id ? 'selected' : '' }}>{{ $dj->nama_jabatan }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor KTP</label>
                                                <input type="number" readonly name="ktp" value="{{ old('ktp', auth()->user()->ktp) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor Kartu Keluarga</label>
                                                <input type="number" readonly name="kartu_keluarga" value="{{ old('kartu_keluarga', auth()->user()->kartu_keluarga) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor BPJS Kesehatan</label>
                                                <input type="number" readonly name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', auth()->user()->bpjs_kesehatan) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor BPJS Ketenagakerjaan</label>
                                                <input type="number" readonly name="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan', auth()->user()->bpjs_ketenagakerjaan) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor NPWP</label>
                                                <input type="number" readonly name="npwp" value="{{ old('npwp', auth()->user()->npwp) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor SIM</label>
                                                <input type="number" readonly name="sim" value="{{ old('sim', auth()->user()->sim) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor PKWT</label>
                                                <input type="number" name="no_pkwt" value="{{ old('no_pkwt', auth()->user()->no_pkwt) }}" disabled>
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor Kontrak</label>
                                                <input type="number" name="no_kontrak" value="{{ old('no_kontrak', auth()->user()->no_kontrak) }}" disabled>
                                            </div>
                                            <div class="group-input">
                                                <label>Tanggal Mulai PKWT</label>
                                                <input type="datetime" name="tanggal_mulai_pkwt" value="{{ old('tanggal_mulai_pkwt', auth()->user()->tanggal_mulai_pkwt) }}" disabled>
                                            </div>
                                            <div class="group-input">
                                                <label>Tanggal Berakhir PKWT</label>
                                                <input type="datetime" name="tanggal_berakhir_pkwt" value="{{ old('tanggal_berakhir_pkwt', auth()->user()->tanggal_berakhir_pkwt) }}" disabled>
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor Rekening</label>
                                                <input type="number" name="rekening" value="{{ old('rekening', auth()->user()->rekening) }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Nama Pemilik Rekening</label>
                                                <input type="text" name="nama_rekening" value="{{ old('nama_rekening', auth()->user()->nama_rekening) }}">
                                            </div>
                                            <button type="submit" class="tf-btn accent large">Save</button>
                                        </div>
                                        <br><br><br><br>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 2: KONTRAK --}}
                    <div class="app-wrap">
                        <div class="bill-content">
                            <div class="tf-container">
                                <ul class="mt-3 mb-5">
                                    <div class="card-secton transfer-section mt-2">
                                        <div class="tf-container">
                                            <div class="tf-balance-box">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="inner-left d-flex justify-content-between align-items-center">
                                                        @if(auth()->user()->foto_karyawan == null)
                                                            <img src="{{ url('assets/img/foto_default.jpg') }}" alt="image">
                                                        @else
                                                            <img src="{{ asset(auth()->user()->foto_karyawan) }}" alt="image">
                                                        @endif
                                                        <p class="fw_7 on_surface_color">{{ auth()->user()->name }}</p>
                                                    </div>
                                                    <p class="fw_7 on_surface_color">{{ auth()->user()->Jabatan->nama_jabatan }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tf-spacing-20"></div>
                                    <div class="tf-container">
                                        <h3>Kontrak Kerja</h3>
                                        <br>
                                        @if($kontraks->isEmpty())
                                            <p class="text-center text-muted">Belum ada data kontrak.</p>
                                        @else
                                            @foreach($kontraks as $kontrak)
                                                <div class="card mb-3" style="border-radius:10px; border:1px solid #e0e0e0;">
                                                    <div class="card-body">
                                                        <div class="group-input">
                                                            <label>Jenis Kontrak</label>
                                                            <input type="text" value="{{ $kontrak->jenis_kontrak ?? '-' }}" readonly />
                                                        </div>
                                                        <div class="group-input">
                                                            <label>Tanggal Kontrak</label>
                                                            <input type="text" value="{{ $kontrak->tanggal ? \Carbon\Carbon::parse($kontrak->tanggal)->translatedFormat('d F Y') : '-' }}" readonly />
                                                        </div>
                                                        <div class="group-input">
                                                            <label>Tanggal Mulai</label>
                                                            <input type="text" value="{{ $kontrak->tanggal_mulai ? \Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') : '-' }}" readonly />
                                                        </div>
                                                        <div class="group-input">
                                                            <label>Tanggal Selesai</label>
                                                            <input type="text" value="{{ $kontrak->tanggal_selesai ? \Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') : '-' }}" readonly />
                                                        </div>
                                                        @if($kontrak->keterangan)
                                                        <div class="group-input">
                                                            <label>Keterangan</label>
                                                            <textarea readonly style="resize:none;">{{ $kontrak->keterangan }}</textarea>
                                                        </div>
                                                        @endif
                                                        @if($kontrak->kontrak_file_path)
                                                        <div class="group-input">
                                                            <label>File Kontrak</label>
                                                            <a href="{{ asset($kontrak->kontrak_file_path) }}" class="btn btn-sm btn-outline-primary mt-1" target="_blank">
                                                                <i class="fa fa-download"></i> {{ $kontrak->kontrak_file_name }}
                                                            </a>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 3: SLIP GAJI --}}
                    <div class="app-wrap">
                        <div class="bill-content">
                            <div class="tf-container">
                                <ul class="mt-3 mb-5">
                                    <div class="tf-spacing-20"></div>
                                    <div class="tf-container">
                                        <h3>Slip Gaji</h3>
                                        <br>
                                        @php
                                            $namaBulan = ['','Januari','Februari','Maret','April','Mei','Juni',
                                                'Juli','Agustus','September','Oktober','November','Desember'];
                                        @endphp
                                        @if(isset($payrolls) && $payrolls->isNotEmpty())
                                            @foreach($payrolls as $pay)
                                                <div class="card mb-3" style="border:none; border-radius:14px; box-shadow:0 4px 14px rgba(0,0,0,0.08);">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <div>
                                                                <div style="font-size:14px; font-weight:600; color:#003366;">
                                                                    {{ $namaBulan[$pay->bulan] ?? $pay->bulan }} {{ $pay->tahun }}
                                                                </div>
                                                                <small class="text-muted">No Slip: {{ $pay->no_gaji }}</small>
                                                            </div>
                                                            <a href="{{ url('/payroll/'.$pay->id.'/download') }}" target="_blank"
                                                               style="background:#003366; color:#fff; border-radius:8px; padding:6px 12px; font-size:12px; text-decoration:none;">
                                                                <i class="fa fa-download"></i> PDF
                                                            </a>
                                                        </div>
                                                        <div style="font-size:12px; color:#777; margin-bottom:10px;">
                                                            Periode {{ $pay->tanggal_mulai }} - {{ $pay->tanggal_akhir }}
                                                            &nbsp;•&nbsp; Kehadiran {{ $pay->persentase_kehadiran }}%
                                                        </div>
                                                        <hr style="margin:6px 0;">
                                                        <div style="font-size:12px;">
                                                            <div class="d-flex justify-content-between py-1">
                                                                <span>Gaji Pokok</span>
                                                                <span>Rp {{ number_format($pay->gaji_pokok) }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between py-1">
                                                                <span>Total Pendapatan</span>
                                                                <span style="color:#1ba97f; font-weight:600;">Rp {{ number_format($pay->total_penjumlahan) }}</span>
                                                            </div>
                                                            <div class="d-flex justify-content-between py-1">
                                                                <span>Total Potongan</span>
                                                                <span style="color:#e53935;">Rp {{ number_format($pay->total_pengurangan) }}</span>
                                                            </div>
                                                        </div>
                                                        <div style="margin-top:12px; background:linear-gradient(135deg,#003366,#0056b3); color:white; border-radius:10px; padding:12px; text-align:center;">
                                                            <div style="font-size:11px; letter-spacing:1px; opacity:0.8;">GAJI DITERIMA</div>
                                                            <div style="font-size:18px; font-weight:700; margin-top:2px;">Rp {{ number_format($pay->grand_total) }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="text-center py-5">
                                                <i class="fa fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada data slip gaji.</p>
                                            </div>
                                        @endif
                                    </div>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 4: + GAJI (PENJUMLAHAN) --}}
                    <div class="app-wrap">
                        <div class="bill-content">
                            <div class="tf-container">
                                <ul class="mt-3 mb-5">
                                    <div class="tf-spacing-20"></div>
                                    <form class="tf-form" action="{{ url('/my-profile/update/'.auth()->user()->id) }}" enctype="multipart/form-data" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <div class="tf-container">
                                            <h3>Penjumlahan Gaji</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Gaji Pokok</label>
                                                <input type="text" class="money" name="gaji_pokok" value="{{ old('gaji_pokok', auth()->user()->gaji_pokok) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Makan & Transport</label>
                                                <input type="text" class="money" value="{{ old('makan_transport', (auth()->user()->tunjangan_makan ?? 0) + (auth()->user()->tunjangan_transport ?? 0)) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Lembur</label>
                                                <input type="text" class="money" name="lembur" value="{{ old('lembur', auth()->user()->lembur) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>100% Kehadiran</label>
                                                <input type="text" class="money" name="kehadiran" value="{{ old('kehadiran', auth()->user()->kehadiran) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>THR</label>
                                                <input type="text" class="money" name="thr" value="{{ old('thr', auth()->user()->thr) }}" readonly />
                                            </div>
                                            <h3>Bonus Bulan Berjalan</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Bonus Pribadi</label>
                                                <input type="text" class="money" name="bonus_pribadi" value="{{ old('bonus_pribadi', auth()->user()->bonus_pribadi) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Bonus Team</label>
                                                <input type="text" class="money" name="bonus_team" value="{{ old('bonus_team', auth()->user()->bonus_team) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Bonus Jackpot</label>
                                                <input type="text" class="money" name="bonus_jackpot" value="{{ old('bonus_jackpot', auth()->user()->bonus_jackpot) }}" readonly />
                                            </div>
                                        </div>
                                    </form>
                                    <br><br><br>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 5: - GAJI (PENGURANGAN) --}}
                    <div class="app-wrap">
                        <div class="bill-content">
                            <div class="tf-container">
                                <ul class="mt-3 mb-5">
                                    <div class="tf-spacing-20"></div>
                                    <form class="tf-form" action="{{ url('/my-profile/update/'.auth()->user()->id) }}" enctype="multipart/form-data" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <div class="tf-container">
                                            <h3>Pengurangan Gaji</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Izin</label>
                                                <input type="text" class="money" name="izin" value="{{ old('izin', auth()->user()->izin) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Terlambat</label>
                                                <input type="text" class="money" name="terlambat" value="{{ old('terlambat', auth()->user()->terlambat) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Mangkir</label>
                                                <input type="text" class="money" name="mangkir" value="{{ old('mangkir', auth()->user()->mangkir) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Saldo Kasbon</label>
                                                <input type="text" class="money" name="saldo_kasbon" value="{{ old('saldo_kasbon', auth()->user()->saldo_kasbon) }}" readonly />
                                            </div>
                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 6: CUTI --}}
                    <div class="app-wrap">
                        <div class="bill-content">
                            <div class="tf-container">
                                <ul class="mt-3 mb-5">
                                    <div class="tf-spacing-20"></div>
                                    <form class="tf-form" action="{{ url('/my-profile/update/'.auth()->user()->id) }}" enctype="multipart/form-data" method="POST">
                                        @method('PUT')
                                        @csrf
                                        <div class="tf-container">
                                            <h3>Cuti & Izin</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Cuti</label>
                                                <input type="number" name="izin_cuti" value="{{ old('izin_cuti', auth()->user()->izin_cuti) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Izin Masuk</label>
                                                <input type="number" name="izin_lainnya" value="{{ old('izin_lainnya', auth()->user()->izin_lainnya) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Izin Telat</label>
                                                <input type="number" name="izin_telat" value="{{ old('izin_telat', auth()->user()->izin_telat) }}" readonly />
                                            </div>
                                            <div class="group-input">
                                                <label>Izin Pulang Cepat</label>
                                                <input type="number" name="izin_pulang_cepat" value="{{ old('izin_pulang_cepat', auth()->user()->izin_pulang_cepat) }}" readonly />
                                            </div>
                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
        <script>
            $('.money').mask('000,000,000,000,000', { reverse: true });
            $('#status_nikah').select2();
        </script>
    @endpush
@endsection