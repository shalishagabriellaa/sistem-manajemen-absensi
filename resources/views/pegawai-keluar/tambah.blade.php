@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

*{
    font-family:'Plus Jakarta Sans',sans-serif;
}

:root{
    --dash-blue:#3b4cca;
    --dash-blue-dk:#2d3db4;

    --slate-50:#f8fafc;
    --slate-100:#f1f5f9;
    --slate-200:#e2e8f0;
    --slate-700:#334155;
}

/* CARD */
.form-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
    background:#fff;
}

/* HEADER */
.form-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 22px;
    border-bottom:1px solid var(--slate-200);
    background:linear-gradient(135deg,#fff,#f8fafc);
}

.form-title{
    font-size:18px;
    font-weight:700;
    color:var(--slate-700);
    display:flex;
    align-items:center;
    gap:8px;
}

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

/* FORM BODY */
.form-body{
    padding:22px;
}

.form-group{
    margin-bottom:16px;
}

label{
    font-size:13px;
    font-weight:600;
    color:var(--slate-700);
    margin-bottom:6px;
}

/* INPUT STYLE */
.form-control{
    border-radius:10px;
    font-size:14px;
    border:1px solid var(--slate-200);
    height:42px;
}

.form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 3px rgba(59,76,202,.12);
}

/* TEXTAREA */
textarea.form-control{
    min-height:120px;
    height:auto;
}

/* FOOTER */
.form-footer{
    display:flex;
    justify-content:flex-end;
    gap:10px;
    padding:16px 22px;
    border-top:1px solid var(--slate-200);
}

/* BUTTON SECONDARY */
.btn-cancel{
    background:#f1f5f9;
    color:#475569;
    border:none;
    padding:8px 14px;
    border-radius:10px;
    font-weight:600;
    font-size:13px;
}

.btn-cancel:hover{
    background:#e2e8f0;
}

/* RESPONSIVE */
@media(max-width:768px){
    .form-header{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }
}
</style>

<div class="row">
    <div class="col-md-12">
<br>
        <div class="card form-card">

            {{-- HEADER --}}
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-user-minus" style="color:#3b4cca"></i>
                    {{ $title }}
                </div>

                <a href="{{ url('/exit') }}" class="primary-btn">
                    ← Back
                </a>
            </div>

            {{-- FORM --}}
            <form method="post" action="{{ url('/exit/store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-body">

                    <div class="row">

    {{-- NAMA PEGAWAI --}}
    <div class="col-md-6">
        <div class="form-group">
            <label>Nama Pegawai</label>
            <select class="form-control selectpicker @error('user_id') is-invalid @enderror"
                    name="user_id"
                    data-live-search="true">
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
    </div>

    {{-- JENIS --}}
    <div class="col-md-6">
        <div class="form-group">
            <label>Jenis Keberhentian</label>
            <select name="jenis"
                    class="form-control selectpicker @error('jenis') is-invalid @enderror"
                    data-live-search="true">
                <option value="">-- Pilih Jenis --</option>
                <option value="PHK" {{ old('jenis') == 'PHK' ? 'selected' : '' }}>PHK</option>
                <option value="Mengundurkan Diri" {{ old('jenis') == 'Mengundurkan Diri' ? 'selected' : '' }}>Mengundurkan Diri</option>
                <option value="Meninggal Dunia" {{ old('jenis') == 'Meninggal Dunia' ? 'selected' : '' }}>Meninggal Dunia</option>
                <option value="Pensiun" {{ old('jenis') == 'Pensiun' ? 'selected' : '' }}>Pensiun</option>
            </select>
            @error('jenis')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

</div>

                    {{-- ALASAN --}}
                    <div class="form-group">
                        <label>Alasan</label>
                        <textarea name="alasan" class="form-control @error('alasan') is-invalid @enderror">{{ old('alasan') }}</textarea>
                        @error('alasan')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- TANGGAL --}}
                    <div class="form-group">
                        <label>Tanggal</label>
                        <input type="datetime-local"
                               class="form-control @error('tanggal') is-invalid @enderror"
                               name="tanggal"
                               value="{{ old('tanggal') }}">
                        @error('tanggal')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- FILE --}}
                    <div class="form-group">
                        <label>File</label>
                        <input type="file"
                               class="form-control @error('pegawai_keluar_file_path') is-invalid @enderror"
                               name="pegawai_keluar_file_path">
                        @error('pegawai_keluar_file_path')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="form-footer">
                    <a href="{{ url('/exit') }}" class="btn-cancel">Batal</a>
                    <button type="submit" class="primary-btn">Submit</button>
                </div>

            </form>

        </div>

    </div>
</div>

@endsection