@extends('templates.app')
@section('container')

<style>
    .menu-tabs.tabs-food-news {
    display: flex;
    flex-wrap: nowrap;
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none; /* Firefox */
}

.menu-tabs.tabs-food-news::-webkit-scrollbar {
    display: none; /* Chrome/Safari */
}

.menu-tabs.tabs-food-news .nav-tab {
    flex-shrink: 0;
    font-size: 12px;   /* perkecil font kalau perlu */
    padding: 10px 110px; /* kurangi padding */
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
                    <div id="tab-gift-item-1 app-wrap" class="app-wrap active-content">
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
                                                            <img src="{{ url('/storage/'.auth()->user()->foto_karyawan) }}" alt="image">
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
                                                @error('name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input input-group">
                                                <input type="file" class="form-control @error('foto_karyawan') is-invalid @enderror" name="foto_karyawan" />
                                                <span class="input-group-text" id="basic-addon2">Foto</span>
                                                @error('foto_karyawan')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                                <input type="hidden" name="foto_karyawan_lama" value="{{ auth()->user()->foto_karyawan }}">
                                            </div>
                                            <div class="group-input">
                                                <label>Email</label>
                                                <input type="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email', auth()->user()->email) }}" />
                                                @error('email')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Nomor Handphone</label>
                                                <input type="text" class="@error('telepon') is-invalid @enderror" name="telepon" value="{{ old('telepon', auth()->user()->telepon) }}" />
                                                @error('telepon')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Username</label>
                                                <input type="text" readonly class="@error('username') is-invalid @enderror" name="username" value="{{ old('username', auth()->user()->username) }}" />
                                                @error('username')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Lokasi Kantor</label>
                                                <select name="lokasi_id" id="lokasi_id" class="@error('lokasi_id') is-invalid @enderror" disabled>
                                                    @foreach ($data_lokasi as $dl)
                                                        @if(old('lokasi_id', auth()->user()->lokasi_id) == $dl->id)
                                                        <option value="{{ $dl->id }}" selected>{{ $dl->nama_lokasi }}</option>
                                                        @else
                                                        <option value="{{ $dl->id }}">{{ $dl->nama_lokasi }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('lokasi_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label for="tgl_lahir">Tanggal Lahir</label>
                                                <input type="text" readonly class="@error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" value="{{ old('tgl_lahir', auth()->user()->tgl_lahir) }}">
                                                @error('tgl_lahir')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="alamat">Alamat</label>
                                                <textarea name="alamat" id="alamat" class="@error('alamat') is-invalid @enderror">{{ old('alamat', auth()->user()->alamat) }}</textarea>
                                                @error('alamat')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="tgl_join">Tanggal Masuk Perusahaan</label>
                                                <input type="text" readonly class="@error('tgl_join') is-invalid @enderror" id="tgl_join" name="tgl_join" value="{{ old('tgl_join', auth()->user()->tgl_join) }}" disabled>
                                                @error('tgl_join')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                @php
                                                    if (auth()->user()->tgl_join) {
                                                        $startDate = Carbon\Carbon::createFromFormat('Y-m-d', auth()->user()->tgl_join, config('app.timezone'));
                                                        $currentDate = Carbon\Carbon::now(config('app.timezone'));
                                                        if ($startDate->greaterThan($currentDate)) {
                                                            $masa_kerja = "0 Tahun, 0 Bulan, 0 Hari.";
                                                        } else {
                                                            $employmentDuration = $currentDate->diff($startDate);
                                                            $masa_kerja = "{$employmentDuration->y} Tahun, {$employmentDuration->m} Bulan, {$employmentDuration->d} Hari.";
                                                        }
                                                    } else {
                                                        $masa_kerja = '';
                                                    }
                                                @endphp
                                                <label for="masa_kerja">Masa Kerja</label>
                                                <input type="text" class="@error('masa_kerja') is-invalid @enderror" id="masa_kerja" name="masa_kerja" value="{{ old('masa_kerja', $masa_kerja) }}" disabled>
                                                @error('masa_kerja')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label for="gender">Gender</label>
                                                <input type="text" readonly class="@error('gender') is-invalid @enderror" id="gender" name="gender" value="{{ old('gender', auth()->user()->gender) }}">
                                                @error('gender')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <?php $is_admin = array(
                                                [
                                                    "is_admin" => "admin"
                                                ],
                                                [
                                                    "is_admin" => "user"
                                                ]);
                                                ?>
                                                <label for="is_admin">Level User</label>
                                                <select name="is_admin" id="is_admin" class="@error('is_admin') is-invalid @enderror" data-live-search="true" disabled>
                                                    <option value="">Pilih Level</option>
                                                    @foreach ($is_admin as $a)
                                                        @if(old('is_admin', auth()->user()->is_admin) == $a["is_admin"])
                                                        <option value="{{ $a["is_admin"] }}" selected>{{ $a["is_admin"] }}</option>
                                                        @else
                                                        <option value="{{ $a["is_admin"] }}">{{ $a["is_admin"] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('is_admin')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                @php
                                                    $sNikah = array(
                                                        [
                                                            "status" => "TK/0"
                                                        ],
                                                        [
                                                            "status" => "TK/1"
                                                        ],
                                                        [
                                                            "status" => "K/0"
                                                        ],
                                                        [
                                                            "status" => "TK/2"
                                                        ],
                                                        [
                                                            "status" => "K/1"
                                                        ],
                                                        [
                                                            "status" => "TK/3"
                                                        ],
                                                        [
                                                            "status" => "K/2"
                                                        ],
                                                        [
                                                            "status" => "K/3"
                                                        ],
                                                    );
                                                @endphp
                                                <label for="status_nikah" style="z-index: 10">Status Pernikahan</label>
                                                <select name="status_nikah" id="status_nikah" class="@error('status_nikah') is-invalid @enderror" data-live-search="true">
                                                    <option value="">Pilih Status Pernikahan</option>
                                                    @foreach ($sNikah as $s)
                                                        @if(old('status_nikah', auth()->user()->status_nikah) == $s["status"])
                                                            <option value="{{ $s["status"] }}" selected>{{ $s["status"] }}</option>
                                                        @else
                                                            <option value="{{ $s["status"] }}">{{ $s["status"] }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('status_nikah')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="jabatan_id">Divisi</label>
                                                <select name="jabatan_id" id="jabatan_id" class="@error('jabatan_id') is-invalid @enderror" data-live-search="true" disabled>
                                                    <option value="">Pilih Divisi</option>
                                                    @foreach ($data_jabatan as $dj)
                                                        @if(old('jabatan_id', auth()->user()->jabatan_id) == $dj->id)
                                                        <option value="{{ $dj->id }}" selected>{{ $dj->nama_jabatan }}</option>
                                                        @else
                                                        <option value="{{ $dj->id }}">{{ $dj->nama_jabatan }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                                @error('jabatan_id')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="ktp">Nomor KTP</label>
                                                <input type="number" readonly class="@error('ktp') is-invalid @enderror" id="ktp" name="ktp" value="{{ old('ktp', auth()->user()->ktp) }}">
                                                @error('ktp')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="kartu_keluarga">Nomor Kartu Keluarga</label>
                                                <input type="number" readonly class="@error('kartu_keluarga') is-invalid @enderror" id="kartu_keluarga" name="kartu_keluarga" value="{{ old('kartu_keluarga', auth()->user()->kartu_keluarga) }}">
                                                @error('kartu_keluarga')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="bpjs_kesehatan">Nomor BPJS Kesehatan</label>
                                                <input type="number" readonly class="@error('bpjs_kesehatan') is-invalid @enderror" id="bpjs_kesehatan" name="bpjs_kesehatan" value="{{ old('bpjs_kesehatan', auth()->user()->bpjs_kesehatan) }}">
                                                @error('bpjs_kesehatan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="bpjs_ketenagakerjaan">Nomor BPJS Ketenagakerjaan</label>
                                                <input type="number" readonly class="@error('bpjs_ketenagakerjaan') is-invalid @enderror" id="bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan" value="{{ old('bpjs_ketenagakerjaan', auth()->user()->bpjs_ketenagakerjaan) }}">
                                                @error('bpjs_ketenagakerjaan')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="npwp">Nomor NPWP</label>
                                                <input type="number" readonly class="@error('npwp') is-invalid @enderror" id="npwp" name="npwp" value="{{ old('npwp', auth()->user()->npwp) }}">
                                                @error('npwp')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="sim">Nomor SIM</label>
                                                <input type="number" readonly class="@error('sim') is-invalid @enderror" id="sim" name="sim" value="{{ old('sim', auth()->user()->sim) }}">
                                                @error('sim')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label for="no_pkwt">Nomor PKWT</label>
                                                <input type="number" class="@error('no_pkwt') is-invalid @enderror" id="no_pkwt" name="no_pkwt" value="{{ old('no_pkwt', auth()->user()->no_pkwt) }}" disabled>
                                                @error('no_pkwt')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label for="no_kontrak">Nomor Kontrak</label>
                                                <input type="number" class="@error('no_kontrak') is-invalid @enderror" id="no_kontrak" name="no_kontrak" value="{{ old('no_kontrak', auth()->user()->no_kontrak) }}" disabled>
                                                @error('no_kontrak')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label for="tanggal_mulai_pkwt">Tanggal Mulai PKWT</label>
                                                <input type="datetime" class="@error('tanggal_mulai_pkwt') is-invalid @enderror" id="tanggal_mulai_pkwt" name="tanggal_mulai_pkwt" value="{{ old('tanggal_mulai_pkwt', auth()->user()->tanggal_mulai_pkwt) }}" disabled>
                                                @error('tanggal_mulai_pkwt')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label for="tanggal_berakhir_pkwt">Tanggal Berakhir PKWT</label>
                                                <input type="datetime" class="@error('tanggal_berakhir_pkwt') is-invalid @enderror" id="tanggal_berakhir_pkwt" name="tanggal_berakhir_pkwt" value="{{ old('tanggal_berakhir_pkwt', auth()->user()->tanggal_berakhir_pkwt) }}" disabled>
                                                @error('tanggal_berakhir_pkwt')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label for="rekening">Nomor Rekening</label>
                                                <input type="number" class="@error('rekening') is-invalid @enderror" id="rekening" name="rekening" value="{{ old('rekening', auth()->user()->rekening) }}">
                                                @error('rekening')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label for="nama_rekening">Nama Pemilik Rekening</label>
                                                <input type="text" class="@error('nama_rekening') is-invalid @enderror" id="nama_rekening" name="nama_rekening" value="{{ old('nama_rekening', auth()->user()->nama_rekening) }}">
                                                @error('nama_rekening')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>

                                            <button type="submit" class="tf-btn accent large">Save</button>
                                        </div>
                                        <br>
                                        <br>
                                        <br>
                                        <br>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>

                    {{-- TAB KONTRAK --}}
                    <div id="tab-gift-item-kontrak app-wrap" class="app-wrap">
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
                                                            <img src="{{ url('/storage/'.auth()->user()->foto_karyawan) }}" alt="image">
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
                                                <div class="card mb-3" style="border-radius: 10px; border: 1px solid #e0e0e0;">
                                                    <div class="card-body">
                                                        <div class="group-input">
                                                            <label>Jenis Kontrak</label>
                                                            <input type="text" value="{{ $kontrak->jenis_kontrak ?? '-' }}" readonly />
                                                        </div>
                                                        <div class="group-input">
                                                            <label>Tanggal Kontrak</label>
                                                            <input type="text" value="@if($kontrak->tanggal){{ \Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tanggal)->translatedFormat('d F Y') }}@else-@endif" readonly />
                                                        </div>
                                                        <div class="group-input">
                                                            <label>Tanggal Mulai</label>
                                                            <input type="text" value="@if($kontrak->tanggal_mulai){{ \Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tanggal_mulai)->translatedFormat('d F Y') }}@else-@endif" readonly />
                                                        </div>
                                                        <div class="group-input">
                                                            <label>Tanggal Selesai</label>
                                                            <input type="text" value="@if($kontrak->tanggal_selesai){{ \Carbon\Carbon::createFromFormat('Y-m-d', $kontrak->tanggal_selesai)->translatedFormat('d F Y') }}@else-@endif" readonly />
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
                                                            <a href="{{ url('/storage/'.$kontrak->kontrak_file_path) }}" class="btn btn-sm btn-outline-primary mt-1" target="_blank">
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

                    {{-- TAB SLIP GAJI --}}

                    <div id="tab-gift-item-slip app-wrap" class="app-wrap">
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

                                        <div class="card mb-3" style="
                                            border:none;
                                            border-radius:14px;
                                            box-shadow:0 4px 14px rgba(0,0,0,0.08);
                                        ">

                                            <div class="card-body p-3">

                                                {{-- HEADER --}}
                                                <div class="d-flex justify-content-between align-items-start mb-2">

                                                    <div>
                                                        <div style="
                                                            font-size:14px;
                                                            font-weight:600;
                                                            color:#003366;
                                                        ">
                                                            {{ $namaBulan[$pay->bulan] ?? $pay->bulan }} {{ $pay->tahun }}
                                                        </div>

                                                        <small class="text-muted">
                                                            No Slip: {{ $pay->no_gaji }}
                                                        </small>
                                                    </div>

                                                    <a href="{{ url('/payroll/'.$pay->id.'/download') }}"
                                                    target="_blank"
                                                    style="
                                                    background:#003366;
                                                    color:#fff;
                                                    border-radius:8px;
                                                    padding:6px 12px;
                                                    font-size:12px;
                                                    text-decoration:none;
                                                    ">

                                                        <i class="fa fa-download"></i> PDF
                                                    </a>

                                                </div>

                                                {{-- PERIODE --}}
                                                <div style="
                                                    font-size:12px;
                                                    color:#777;
                                                    margin-bottom:10px;
                                                ">
                                                    Periode {{ $pay->tanggal_mulai }} - {{ $pay->tanggal_akhir }}
                                                    &nbsp; • &nbsp;
                                                    Kehadiran {{ $pay->persentase_kehadiran }}%
                                                </div>

                                                <hr style="margin:6px 0;">

                                                {{-- DETAIL GAJI --}}
                                                <div style="font-size:12px">

                                                    <div class="d-flex justify-content-between py-1">
                                                        <span>Gaji Pokok</span>
                                                        <span>Rp {{ number_format($pay->gaji_pokok) }}</span>
                                                    </div>

                                                    <div class="d-flex justify-content-between py-1">
                                                        <span>Total Pendapatan</span>
                                                        <span style="color:#1ba97f; font-weight:600;">
                                                            Rp {{ number_format($pay->total_penjumlahan) }}
                                                        </span>
                                                    </div>

                                                    <div class="d-flex justify-content-between py-1">
                                                        <span>Total Potongan</span>
                                                        <span style="color:#e53935;">
                                                            Rp {{ number_format($pay->total_pengurangan) }}
                                                        </span>
                                                    </div>

                                                </div>

                                                {{-- TOTAL GAJI --}}
                                                <div style="
                                                    margin-top:12px;
                                                    background:linear-gradient(135deg,#003366,#0056b3);
                                                    color:white;
                                                    border-radius:10px;
                                                    padding:12px;
                                                    text-align:center;
                                                ">

                                                    <div style="
                                                        font-size:11px;
                                                        letter-spacing:1px;
                                                        opacity:0.8;
                                                    ">
                                                        GAJI DITERIMA
                                                    </div>

                                                    <div style="
                                                        font-size:18px;
                                                        font-weight:700;
                                                        margin-top:2px;
                                                    ">
                                                        Rp {{ number_format($pay->grand_total) }}
                                                    </div>

                                                </div>

                                            </div>
                                        </div>

                                        @endforeach

                                    @else

                                        <div class="text-center py-5">

                                            <i class="fa fa-file-invoice-dollar fa-3x text-muted mb-3"></i>

                                            <p class="text-muted">
                                                Belum ada data slip gaji.
                                            </p>

                                        </div>

                                    @endif

                                </div>

                            </ul>
                        </div>
                    </div>

</div>


                    <div id="tab-gift-item-2 app-wrap" class="app-wrap">
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
                                                            <img src="{{ url('/storage/'.auth()->user()->foto_karyawan) }}" alt="image">
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
                                            <h3>Cuti & Izin</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Cuti</label>
                                                <input type="number" class="@error('izin_cuti') is-invalid @enderror" name="izin_cuti" value="{{ old('izin_cuti', auth()->user()->izin_cuti) }}" readonly />
                                                @error('izin_cuti')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Izin Masuk</label>
                                                <input type="number" class="@error('izin_lainnya') is-invalid @enderror" name="izin_lainnya" value="{{ old('izin_lainnya', auth()->user()->izin_lainnya) }}" readonly />
                                                @error('izin_lainnya')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Izin Telat</label>
                                                <input type="number" class="@error('izin_telat') is-invalid @enderror" name="izin_telat" value="{{ old('izin_telat', auth()->user()->izin_telat) }}" readonly />
                                                @error('izin_telat')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Izin Pulang Cepat</label>
                                                <input type="number" class="@error('izin_pulang_cepat') is-invalid @enderror" name="izin_pulang_cepat" value="{{ old('izin_pulang_cepat', auth()->user()->izin_pulang_cepat) }}" readonly />
                                                @error('izin_pulang_cepat')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="tab-gift-item-3 app-wrap" class="app-wrap">
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
                                                            <img src="{{ url('/storage/'.auth()->user()->foto_karyawan) }}" alt="image">
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
                                            <h3>Penjumlahan Gaji</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Gaji Pokok</label>
                                                <input type="text" class="money @error('gaji_pokok') is-invalid @enderror" name="gaji_pokok" value="{{ old('gaji_pokok', auth()->user()->gaji_pokok) }}" readonly />
                                                @error('gaji_pokok')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Makan & Transport</label>
                                                <input type="text" class="money @error('makan_transport') is-invalid @enderror" name="makan_transport" value="{{ old('makan_transport', (auth()->user()->tunjangan_makan ?? 0) + (auth()->user()->tunjangan_transport ?? 0)) }}" readonly />
                                                @error('makan_transport')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Lembur</label>
                                                <input type="text" class="money @error('lembur') is-invalid @enderror" name="lembur" value="{{ old('lembur', auth()->user()->lembur) }}" readonly />
                                                @error('lembur')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>100% Kehadiran</label>
                                                <input type="text" class="money @error('kehadiran') is-invalid @enderror" name="kehadiran" value="{{ old('kehadiran', auth()->user()->kehadiran) }}" readonly />
                                                @error('kehadiran')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>THR</label>
                                                <input type="text" class="money @error('thr') is-invalid @enderror" name="thr" value="{{ old('thr', auth()->user()->thr) }}" readonly />
                                                @error('thr')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <h3>Bonus Bulan Berjalan</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Bonus Pribadi</label>
                                                <input type="text" class="money @error('bonus_pribadi') is-invalid @enderror" name="bonus_pribadi" value="{{ old('bonus_pribadi', auth()->user()->bonus_pribadi) }}" readonly />
                                                @error('bonus_pribadi')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Bonus Team</label>
                                                <input type="text" class="money @error('bonus_team') is-invalid @enderror" name="bonus_team" value="{{ old('bonus_team', auth()->user()->bonus_team) }}" readonly />
                                                @error('bonus_team')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <div class="group-input">
                                                <label>Bonus Jackpot</label>
                                                <input type="text" class="money @error('bonus_jackpot') is-invalid @enderror" name="bonus_jackpot" value="{{ old('bonus_jackpot', auth()->user()->bonus_jackpot) }}" readonly />
                                                @error('bonus_jackpot')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                        </div>
                                    </form>
                                    <br>
                                    <br>
                                    <br>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="tab-gift-item-4 app-wrap" class="app-wrap">
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
                                                            <img src="{{ url('/storage/'.auth()->user()->foto_karyawan) }}" alt="image">
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
                                            <h3>Pengurangan Gaji</h3>
                                            <br>
                                            <div class="group-input">
                                                <label>Izin</label>
                                                <input type="text" class="money @error('izin') is-invalid @enderror" name="izin" value="{{ old('izin', auth()->user()->izin) }}" readonly />
                                                @error('izin')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Terlambat</label>
                                                <input type="text" class="money @error('terlambat') is-invalid @enderror" name="terlambat" value="{{ old('terlambat', auth()->user()->terlambat) }}" readonly />
                                                @error('terlambat')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Mangkir</label>
                                                <input type="text" class="money @error('mangkir') is-invalid @enderror" name="mangkir" value="{{ old('mangkir', auth()->user()->mangkir) }}" readonly />
                                                @error('mangkir')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="group-input">
                                                <label>Saldo Kasbon</label>
                                                <input type="text" class="money @error('saldo_kasbon') is-invalid @enderror" name="saldo_kasbon" value="{{ old('saldo_kasbon', auth()->user()->saldo_kasbon) }}" readonly />
                                                @error('saldo_kasbon')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
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
            $('.money').mask('000,000,000,000,000', {
                reverse: true
            });
            $('#status_nikah').select2();

            function toggleSlip(id) {
                var el = document.getElementById(id);
                if (el.style.display === 'none') {
                    el.style.display = 'block';
                } else {
                    el.style.display = 'none';
                }
            }
        </script>
    @endpush
@endsection

