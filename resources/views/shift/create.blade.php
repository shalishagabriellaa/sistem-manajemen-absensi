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

/* CARD STYLE CONSISTENT */
.form-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
}

/* HEADER */
.form-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 20px;
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

/* FORM STYLE */
.form-body{
    padding:22px;
}

.form-label{
    font-size:13px;
    font-weight:600;
    color:var(--slate-700);
    margin-bottom:6px;
}

.form-control{
    border-radius:10px;
    font-size:14px;
    height:42px;
    border:1px solid var(--slate-200);
    transition:.2s;
}

.form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 3px rgba(59,76,202,0.12);
}

/* BUTTON SUBMIT */
.primary-submit{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
    border:none;
    padding:8px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    float:right;
    transition:.2s;
    box-shadow:0 6px 18px rgba(59,76,202,.25);
}

.primary-submit:hover{
    transform:translateY(-1px);
}
</style>

<div class="row">
    <div class="col-md-12">
<br>
        {{-- CARD --}}
        <div class="card form-card">

            {{-- HEADER --}}
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-clock" style="color:#3b4cca"></i>
                    {{ $title }}
                </div>

                <a href="{{ url('/shift') }}" class="primary-btn">
                    ← Back
                </a>
            </div>

            {{-- FORM --}}
            <form method="post" action="{{ url('/shift') }}">
                @csrf

                <div class="form-body">

                    {{-- Nama Shift --}}
                    <div class="mb-3">
                        <label class="form-label">Nama Shift</label>
                        <input type="text"
                            class="form-control @error('nama_shift') is-invalid @enderror"
                            name="nama_shift"
                            value="{{ old('nama_shift') }}"
                            required>

                        @error('nama_shift')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jam --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Masuk</label>
                            <input type="time"
                                class="form-control @error('jam_masuk') is-invalid @enderror"
                                name="jam_masuk"
                                value="{{ old('jam_masuk') }}"
                                required>

                            @error('jam_masuk')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jam Keluar</label>
                            <input type="time"
                                class="form-control @error('jam_keluar') is-invalid @enderror"
                                name="jam_keluar"
                                value="{{ old('jam_keluar') }}"
                                required>

                            @error('jam_keluar')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- Istirahat --}}
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Jam Mulai Istirahat</label>
                            <input type="time"
                                class="form-control @error('jam_mulai_istirahat') is-invalid @enderror"
                                name="jam_mulai_istirahat"
                                value="{{ old('jam_mulai_istirahat') }}">

                            @error('jam_mulai_istirahat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Jam Selesai Istirahat</label>
                            <input type="time"
                                class="form-control @error('jam_selesai_istirahat') is-invalid @enderror"
                                name="jam_selesai_istirahat"
                                value="{{ old('jam_selesai_istirahat') }}">

                            @error('jam_selesai_istirahat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    {{-- BUTTON --}}
                    <button type="submit" class="primary-submit">
                        Submit
                    </button>

                </div>
            </form>
<br>
        </div>

    </div>
</div>

@endsection