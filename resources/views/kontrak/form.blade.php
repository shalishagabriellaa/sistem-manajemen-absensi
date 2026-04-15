{{-- resources/views/kontrak/form.blade.php --}}
{{-- Digunakan untuk TAMBAH (action: /kontrak/store, method: POST)  --}}
{{-- dan EDIT (action: /kontrak/update/{id}, method: PUT)            --}}
@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root{
    --dash-blue:#3b4cca;
    --dash-blue-dk:#2d3db4;
    --slate-50:#f8fafc;
    --slate-100:#f1f5f9;
    --slate-200:#e2e8f0;
    --slate-500:#64748b;
    --slate-700:#334155;
}
* { font-family: 'Plus Jakarta Sans', sans-serif; }

.karyawan-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
}
.karyawan-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 24px;
    border-bottom:1px solid var(--slate-200);
    background:#fff;
}
.karyawan-title{ font-size:20px; font-weight:700; color:var(--slate-700); }

.action-btn{
    padding:7px 14px;
    font-size:13px;
    font-weight:600;
    border-radius:8px;
    border:1px solid var(--slate-200);
    background:#fff;
    color:var(--slate-700);
    text-decoration:none;
    transition:.2s;
    display:inline-flex;
    align-items:center;
    gap:6px;
    cursor:pointer;
}
.action-btn.primary{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff; border-color:var(--dash-blue);
}
.action-btn.danger{
    background:linear-gradient(135deg,#ef4444,#dc2626);
    color:#fff; border-color:#ef4444;
}
.action-btn:hover{ opacity:.88; }

.form-body{ padding:28px; }
.form-section-title{
    font-size:12px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.6px;
    color:var(--slate-500);
    margin-bottom:16px;
    padding-bottom:8px;
    border-bottom:1px solid var(--slate-100);
}
.form-label{ font-size:13px; font-weight:600; color:var(--slate-700); margin-bottom:6px; display:block; }
.form-control{
    border-radius:8px;
    height:40px;
    border:1px solid var(--slate-200);
    font-size:13px;
    font-family:'Plus Jakarta Sans',sans-serif;
    background:#fff;
    color:var(--slate-700);
    width:100%;
    padding:0 12px;
    transition:.2s;
}
.form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 3px rgba(59,76,202,.1);
    outline:none;
}
textarea.form-control{ height:auto; padding:10px 12px; }
.form-control.is-invalid{ border-color:#ef4444; }
.invalid-feedback{ color:#ef4444; font-size:12px; margin-top:4px; }

.submit-bar{
    padding:16px 28px;
    border-top:1px solid var(--slate-200);
    background:var(--slate-50);
    display:flex;
    justify-content:flex-end;
    gap:10px;
}

.file-hint{ font-size:12px; color:var(--slate-500); margin-top:6px; }
.file-hint a{ color:var(--dash-blue); text-decoration:none; }
</style>

<div class="container-fluid">
<br>
<div class="card karyawan-card">

    <div class="karyawan-header">
        <div class="karyawan-title">
            <i class="fas fa-file-contract" style="color:#3b4cca; margin-right:8px;"></i>
            {{ $title }}
        </div>
        <a href="{{ url('/kontrak') }}" class="action-btn danger">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    @isset($kontrak)
        <form method="post" action="{{ url('/kontrak/update/'.$kontrak->id) }}" enctype="multipart/form-data">
        @method('PUT')
    @else
        <form method="post" action="{{ url('/kontrak/store') }}" enctype="multipart/form-data">
    @endisset
    @csrf

    <div class="form-body">

        <div class="form-section-title"><i class="fas fa-calendar-alt"></i>&nbsp; Informasi Surat</div>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Tanggal Surat</label>
                <input type="date" name="tanggal" class="form-control @error('tanggal') is-invalid @enderror"
                       value="{{ old('tanggal', isset($kontrak) ? $kontrak->tanggal : date('Y-m-d')) }}">
                @error('tanggal')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">No. Surat <span style="color:var(--slate-500);font-weight:400">(Opsional)</span></label>
                <input type="text" name="no_surat" class="form-control @error('no_surat') is-invalid @enderror"
                       placeholder="Contoh: 001/PKWT/HRD/IV/2026"
                       value="{{ old('no_surat', isset($kontrak) ? $kontrak->no_surat : '') }}">
                @error('no_surat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Jenis Kontrak</label>
                <select name="jenis_kontrak" id="jenis_kontrak"
                        class="form-control @error('jenis_kontrak') is-invalid @enderror selectpicker"
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

        <div class="form-section-title"><i class="fas fa-user"></i>&nbsp; Data Pegawai</div>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Nama Pegawai</label>
                <select name="user_id" class="form-control selectpicker @error('user_id') is-invalid @enderror"
                        data-live-search="true">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}"
                            {{ old('user_id', isset($kontrak) ? $kontrak->user_id : '') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-calendar-check"></i>&nbsp; Periode Kontrak</div>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                       value="{{ old('tanggal_mulai', isset($kontrak) ? $kontrak->tanggal_mulai : '') }}">
                @error('tanggal_mulai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4" id="tanggalSelesai">
                <label class="form-label">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                       value="{{ old('tanggal_selesai', isset($kontrak) ? $kontrak->tanggal_selesai : '') }}">
                @error('tanggal_selesai')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="form-section-title"><i class="fas fa-align-left"></i>&nbsp; Keterangan & Dokumen</div>
        <div class="row g-3">
            <div class="col-md-8">
                <label class="form-label">Keterangan / Lingkup Pekerjaan</label>
                <textarea name="keterangan" rows="5"
                          class="form-control @error('keterangan') is-invalid @enderror"
                          placeholder="Deskripsikan lingkup/keterangan pekerjaan...">{{ old('keterangan', isset($kontrak) ? $kontrak->keterangan : '') }}</textarea>
                @error('keterangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Upload File Kontrak <span style="color:var(--slate-500);font-weight:400">(PDF/DOCX, opsional)</span></label>
                <input type="file" name="kontrak_file_path"
                       class="form-control @error('kontrak_file_path') is-invalid @enderror"
                       style="height:auto; padding:8px 12px;">
                @error('kontrak_file_path')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @isset($kontrak)
                    @if($kontrak->kontrak_file_path)
                        <div class="file-hint mt-2">
                            <i class="fa fa-file"></i>
                            <a href="{{ url('/storage/'.$kontrak->kontrak_file_path) }}" target="_blank">
                                {{ $kontrak->kontrak_file_name }}
                            </a>
                            — upload baru untuk mengganti
                        </div>
                    @endif
                @endisset
            </div>
        </div>

    </div>

    <div class="submit-bar">
        <a href="{{ url('/kontrak') }}" class="action-btn danger">Batal</a>
        <button type="submit" class="action-btn primary">
            <i class="fas fa-save"></i> Simpan
        </button>
    </div>
    </form>

</div>
</div>

@push('script')
<script>
function toggleSelesai() {
    const jk = document.getElementById('jenis_kontrak').value;
    const el = document.getElementById('tanggalSelesai');
    if (jk === 'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)') {
        el.style.display = 'none';
        el.querySelector('input').value = '';
    } else {
        el.style.display = '';
    }
}
document.addEventListener('DOMContentLoaded', function() {
    toggleSelesai();
    document.getElementById('jenis_kontrak').addEventListener('change', toggleSelesai);
});
</script>
@endpush

@endsection