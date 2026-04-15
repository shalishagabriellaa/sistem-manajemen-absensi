@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root{
    --dash-blue:#3b4cca;
    --dash-blue-dk:#2d3db4;
    --dash-blue-lt:#5c6ed4;
    --slate-50:#f8fafc;
    --slate-100:#f1f5f9;
    --slate-200:#e2e8f0;
    --slate-300:#cbd5e1;
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
.action-btn:hover,
.action-btn.primary{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
    border-color:var(--dash-blue);
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

.form-card{
    padding:28px 28px;
    background:#fff;
}
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
.form-group .form-control.is-invalid{
    border-color:#f87171;
}
.form-group textarea.form-control{ resize:vertical; min-height:120px; }
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
            <i class="fas fa-file-contract" style="color:#3b4cca;margin-right:8px;"></i>
            {{ $title }}
        </div>
        <div>
            <a href="{{ url('/kontrak') }}" class="primary-btn">
                <i class="fas fa-arrow-left"></i> Back
            </a>
        </div>
    </div>

    {{-- Form --}}
    <form method="post" action="{{ url('/kontrak/store') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-card">

            <div class="form-section-label">Informasi Kontrak</div>

            <div class="form-group">
                <label for="tanggal"><i class="fas fa-calendar-alt" style="color:var(--dash-blue);margin-right:5px;"></i>Tanggal</label>
                <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                       id="tanggal" name="tanggal"
                       value="{{ old('tanggal', date('Y-m-d')) }}">
                @error('tanggal')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="user_id"><i class="fas fa-user" style="color:var(--dash-blue);margin-right:5px;"></i>Nama Pegawai</label>
                <select class="form-control selectpicker @error('user_id') is-invalid @enderror"
                        id="user_id" name="user_id" data-live-search="true">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="jenis_kontrak"><i class="fas fa-file-signature" style="color:var(--dash-blue);margin-right:5px;"></i>Jenis Kontrak</label>
                <select name="jenis_kontrak" id="jenis_kontrak"
                        class="form-control selectpicker @error('jenis_kontrak') is-invalid @enderror"
                        data-live-search="true">
                    <option value="">-- Pilih Jenis Kontrak --</option>
                    <option value="Perjanjian Kerja Waktu Tertentu (PKWT)"
                        {{ old('jenis_kontrak') == 'Perjanjian Kerja Waktu Tertentu (PKWT)' ? 'selected' : '' }}>
                        Perjanjian Kerja Waktu Tertentu (PKWT)
                    </option>
                    <option value="Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)"
                        {{ old('jenis_kontrak') == 'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)' ? 'selected' : '' }}>
                        Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)
                    </option>
                    <option value="Tenaga Harian Lepas (THL)"
                        {{ old('jenis_kontrak') == 'Tenaga Harian Lepas (THL)' ? 'selected' : '' }}>
                        Tenaga Harian Lepas (THL)
                    </option>
                </select>
                @error('jenis_kontrak')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label for="tanggal_mulai"><i class="fas fa-calendar-check" style="color:var(--dash-blue);margin-right:5px;"></i>Tanggal Mulai</label>
                        <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                               id="tanggal_mulai" name="tanggal_mulai"
                               value="{{ old('tanggal_mulai') }}">
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-6" id="tanggalSelesai">
                    <div class="form-group">
                        <label for="tanggal_selesai"><i class="fas fa-calendar-times" style="color:var(--dash-blue);margin-right:5px;"></i>Tanggal Selesai</label>
                        <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                               id="tanggal_selesai" name="tanggal_selesai"
                               value="{{ old('tanggal_selesai') }}">
                        @error('tanggal_selesai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="keterangan"><i class="fas fa-align-left" style="color:var(--dash-blue);margin-right:5px;"></i>Keterangan</label>
                <textarea name="keterangan" id="keterangan"
                          class="form-control @error('keterangan') is-invalid @enderror">{{ old('keterangan') }}</textarea>
                @error('keterangan')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="kontrak_file_path"><i class="fas fa-paperclip" style="color:var(--dash-blue);margin-right:5px;"></i>File Kontrak</label>
                <input type="file" class="form-control @error('kontrak_file_path') is-invalid @enderror"
                       id="kontrak_file_path" name="kontrak_file_path">
                @error('kontrak_file_path')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <div class="form-footer">
            <a href="{{ url('/kontrak') }}" class="action-btn danger">
                <i class="fas fa-times"></i> Batal
            </a>
            <button type="submit" class="submit-btn">
                <i class="fas fa-save"></i> Simpan
            </button>
        </div>
    </form>

</div>
</div>

@push('script')
<script>
    function toggleTanggalSelesai() {
        let jenis = $('#jenis_kontrak').val();
        if (jenis !== 'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)') {
            $('#tanggalSelesai').show();
        } else {
            $('#tanggalSelesai').hide();
            $('#tanggal_selesai').val('');
        }
    }
    toggleTanggalSelesai();
    $('body').on('change', '#jenis_kontrak', function() {
        toggleTanggalSelesai();
    });
</script>
@endpush
@endsection