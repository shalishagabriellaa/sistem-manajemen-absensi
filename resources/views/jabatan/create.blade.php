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
    flex-wrap:wrap;
    gap:12px;
}
.karyawan-title{
    font-size:20px;
    font-weight:700;
    color:var(--slate-700);
}
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
.action-btn.danger{
    background:#fff0f0;
    color:#b91c1c;
    border-color:#fca5a5;
}
.action-btn.danger:hover{
    background:linear-gradient(135deg,#dc2626,#b91c1c);
    color:#fff;
    border-color:#dc2626;
}
.form-card{ padding:28px; background:#fff; }
.form-section-label{
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.6px;
    color:var(--slate-500);
    margin-bottom:16px;
    padding-bottom:8px;
    border-bottom:1px solid var(--slate-100);
}
.form-group{ margin-bottom:20px; }
.form-group label{
    font-size:13px;
    font-weight:600;
    color:var(--slate-700);
    margin-bottom:6px;
    display:block;
}
.form-group .form-control{
    border-radius:8px;
    border:1px solid var(--slate-200);
    font-size:13px;
    font-family:'Plus Jakarta Sans',sans-serif;
    color:var(--slate-700);
    background:#fff;
    padding:9px 12px;
    height:auto;
    transition:.2s;
}
.form-group .form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 3px rgba(59,76,202,.1);
    outline:none;
}
.form-group .form-control.is-invalid{ border-color:#f87171; }
.form-footer{
    padding:16px 28px;
    border-top:1px solid var(--slate-100);
    background:var(--slate-50);
    display:flex;
    justify-content:flex-end;
    gap:8px;
}
.submit-btn{
    padding:9px 24px;
    font-size:13px;
    font-weight:600;
    border-radius:8px;
    border:none;
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    gap:6px;
    transition:.2s;
}
.submit-btn:hover{ opacity:.9; }

/* BUTTON */
.primary-btn{
    background:#f1f5f9;   /* default abu */
    color:#475569;
    border:none;
    padding:8px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    transition:.2s;
}

.primary-btn:hover{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
    transform:translateY(-1px);
}
</style>

<div class="container-fluid">
<br>
<div class="card karyawan-card">

    {{-- Header --}}
    <div class="karyawan-header">
        <div class="karyawan-title">
            <i class="fas fa-sitemap" style="color:#3b4cca;margin-right:8px;"></i>
            {{ $title }}
        </div>
        <div>
            <a href="{{ url('/jabatan') }}" class="primary-btn">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form method="post" action="{{ url('/jabatan/insert') }}">
        @csrf
        <div class="form-card">

            <div class="form-section-label">Informasi Divisi</div>

            <div class="form-group">
                <label for="nama_jabatan">
                    <i class="fas fa-layer-group" style="color:var(--dash-blue);margin-right:5px;"></i>Nama Divisi
                </label>
                <input type="text"
                       class="form-control @error('nama_jabatan') is-invalid @enderror"
                       id="nama_jabatan" name="nama_jabatan"
                       autofocus value="{{ old('nama_jabatan') }}">
                @error('nama_jabatan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="manager">
                    <i class="fas fa-user-tie" style="color:var(--dash-blue);margin-right:5px;"></i>Manager
                </label>
                <select class="form-control selectpicker @error('manager') is-invalid @enderror"
                        id="manager" name="manager" data-live-search="true">
                    <option value="">Pilih Manager</option>
                    @foreach ($users as $du)
                        <option value="{{ $du->id }}" {{ old('manager') == $du->id ? 'selected' : '' }}>
                            {{ $du->name }}
                        </option>
                    @endforeach
                </select>
                @error('manager')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="form-footer">
            <a href="{{ url('/jabatan') }}" class="action-btn danger">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>

</div>
</div>

@endsection