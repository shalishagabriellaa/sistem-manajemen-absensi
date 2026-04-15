{{-- 
    Gunakan file ini untuk TAMBAH (action: /kontrak/store, method: POST)
    dan EDIT (action: /kontrak/update/{id}, method: PUT)
    Bedanya hanya di bagian @if isset($kontrak) 
--}}
@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root {
    --dash-blue: #3b4cca;
    --dash-blue-dk: #2d3db4;
    --slate-50: #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-700: #334155;
}
.form-card { border-radius: 16px; border: 1px solid var(--slate-200); box-shadow: 0 6px 20px rgba(0,0,0,0.06); overflow: hidden; background: #fff; }
.form-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 20px; border-bottom: 1px solid var(--slate-200); background: linear-gradient(135deg, #fff, #f8fafc); }
.form-title { font-size: 18px; font-weight: 700; color: var(--slate-700); }
.primary-btn { background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk)); color: #fff; border: none; padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: .2s; }
.primary-btn:hover { transform: translateY(-1px); color: #fff; }
.danger-btn { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border: none; padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: .2s; }
.danger-btn:hover { transform: translateY(-1px); color: #fff; }
.form-body { padding: 24px; }
.form-group { margin-bottom: 20px; }
.form-label { font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: 6px; display: block; }
.form-control { border-radius: 10px; height: 42px; border: 1px solid var(--slate-200); font-size: 14px; background: #fff; }
.form-control:focus { border-color: var(--dash-blue); box-shadow: 0 0 0 .15rem rgba(59,76,202,.15); }
textarea.form-control { height: auto; }
.section-divider { border: none; border-top: 1px solid var(--slate-200); margin: 24px 0; }
.file-preview { font-size: 12px; color: var(--dash-blue); margin-top: 6px; }
.submit-bar { padding: 16px 24px; border-top: 1px solid var(--slate-200); background: var(--slate-50); display: flex; justify-content: flex-end; gap: 10px; }
</style>

<div class="row">
<div class="col-md-12"><br>
<div class="card form-card">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-file-contract" style="color:#3b4cca"></i>&nbsp;{{ $title }}
        </div>
        <a href="{{ url('/kontrak') }}" class="danger-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    {{-- FORM --}}
    @isset($kontrak)
        <form method="post" action="{{ url('/kontrak/update/'.$kontrak->id) }}" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="post" action="{{ url('/kontrak/store') }}" enctype="multipart/form-data">
    @endisset
    @csrf

    <div class="form-body">
        <div class="row">
            {{-- Tanggal Surat --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Tanggal Surat</label>
                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                           name="tanggal"
                           value="{{ old('tanggal', isset($kontrak) ? $kontrak->tanggal : date('Y-m-d')) }}">
                    @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Pegawai --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Nama Pegawai</label>
                    <select class="form-control selectpicker @error('user_id') is-invalid @enderror"
                            name="user_id" data-live-search="true">
                        <option value="">-- Pilih Pegawai --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}"
                                {{ old('user_id', isset($kontrak) ? $kontrak->user_id : '') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Jenis Kontrak --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Jenis Kontrak</label>
                    <select name="jenis_kontrak" id="jenis_kontrak"
                            class="form-control selectpicker @error('jenis_kontrak') is-invalid @enderror"
                            data-live-search="true">
                        <option value="">-- Pilih Jenis Kontrak --</option>
                        @foreach([
                            'Perjanjian Kerja Waktu Tertentu (PKWT)',
                            'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)',
                            'Tenaga Harian Lepas (THL)'
                        ] as $jk)
                            <option value="{{ $jk }}"
                                {{ old('jenis_kontrak', isset($kontrak) ? $kontrak->jenis_kontrak : '') == $jk ? 'selected' : '' }}>
                                {{ $jk }}
                            </option>
                        @endforeach
                    </select>
                    @error('jenis_kontrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- No. Surat (opsional, untuk referensi manual) --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">No. Surat <span style="color:#94a3b8;font-weight:400">(Opsional — diisi saat generate dokumen)</span></label>
                    <input type="text" class="form-control @error('no_surat') is-invalid @enderror"
                           name="no_surat" placeholder="Contoh: 001/PKWT/HRD/IV/2026"
                           value="{{ old('no_surat', isset($kontrak) ? $kontrak->no_surat : '') }}">
                    @error('no_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Tanggal Mulai --}}
            <div class="col-md-6">
                <div class="form-group">
                    <label class="form-label">Tanggal Mulai Kontrak</label>
                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                           name="tanggal_mulai"
                           value="{{ old('tanggal_mulai', isset($kontrak) ? $kontrak->tanggal_mulai : '') }}">
                    @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Tanggal Selesai --}}
            <div class="col-md-6" id="tanggalSelesai">
                <div class="form-group">
                    <label class="form-label">Tanggal Selesai Kontrak</label>
                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                           name="tanggal_selesai"
                           value="{{ old('tanggal_selesai', isset($kontrak) ? $kontrak->tanggal_selesai : '') }}">
                    @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <hr class="section-divider">

        {{-- Keterangan --}}
        <div class="form-group">
            <label class="form-label">Keterangan / Lingkup Pekerjaan</label>
            <textarea name="keterangan" rows="5"
                      class="form-control @error('keterangan') is-invalid @enderror"
                      placeholder="Deskripsikan lingkup/keterangan pekerjaan...">{{ old('keterangan', isset($kontrak) ? $kontrak->keterangan : '') }}</textarea>
            @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        {{-- Upload File --}}
        <div class="form-group">
            <label class="form-label">Upload File Kontrak <span style="color:#94a3b8;font-weight:400">(PDF/DOCX, opsional)</span></label>
            <input type="file" class="form-control @error('kontrak_file_path') is-invalid @enderror"
                   name="kontrak_file_path">
            @error('kontrak_file_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
            @isset($kontrak)
                @if ($kontrak->kontrak_file_path)
                    <div class="file-preview mt-1">
                        <i class="fa fa-file"></i>
                        <a href="{{ url('/storage/'.$kontrak->kontrak_file_path) }}" target="_blank">
                            {{ $kontrak->kontrak_file_name }}
                        </a>
                        <span style="color:#94a3b8"> — Upload file baru untuk mengganti</span>
                    </div>
                @endif
            @endisset
        </div>
    </div>

    <div class="submit-bar">
        <a href="{{ url('/kontrak') }}" class="danger-btn">Batal</a>
        <button type="submit" class="primary-btn">
            <i class="fas fa-save"></i> Simpan
        </button>
    </div>
    </form>

</div>
</div>
</div>

@push('script')
<script>
function toggleSelesai() {
    const jk = $('#jenis_kontrak').val();
    if (jk === 'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)') {
        $('#tanggalSelesai').hide();
        $('#tanggalSelesai input').val('');
    } else {
        $('#tanggalSelesai').show();
    }
}
$(document).ready(function () {
    toggleSelesai();
    $('body').on('change', '#jenis_kontrak', function () {
        toggleSelesai();
    });
});
</script>
@endpush

@endsection