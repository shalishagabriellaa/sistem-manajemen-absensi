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
    --dash-blue-lt:#5c6ed4;

    --slate-50:#f8fafc;
    --slate-100:#f1f5f9;
    --slate-200:#e2e8f0;
    --slate-300:#cbd5e1;
    --slate-500:#64748b;
    --slate-700:#334155;
}

/* CARD STYLE */
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
    padding:20px;
    border-bottom:1px solid var(--slate-200);
    background:linear-gradient(135deg,#fff,#f8fafc);
}

.form-title{
    font-size:20px;
    font-weight:700;
    color:var(--slate-700);
    display:flex;
    align-items:center;
    gap:8px;
}

/* BACK BUTTON */
.action-btn{
    padding:7px 14px;
    font-size:13px;
    font-weight:600;
    border-radius:8px;
    border:1px solid var(--slate-200);
    background:white;
    color:var(--slate-700);
    transition:.2s;
    text-decoration:none;
}

.action-btn:hover{
    background:var(--dash-blue);
    color:white;
    border-color:var(--dash-blue);
}

/* FORM */
.form-container{
    padding:20px;
}

.form-control{
    border-radius:8px;
    font-size:14px;
    height:38px;
    border:1px solid var(--slate-200);
}

.form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 0.15rem rgba(59,76,202,0.15);
}

/* SUBMIT */
.submit-btn{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:white;
    border:none;
    padding:10px 24px;
    border-radius:10px;
    font-weight:600;
    font-size:14px;
    box-shadow:0 4px 14px rgba(59,76,202,0.25);
    transition:all .2s ease;
}

.submit-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(59,76,202,0.35);
}

.form-group label{
    font-weight:600;
    font-size:14px;
    color:var(--slate-700);
    margin-bottom:6px;
}
</style>

<br>

<div class="row">
    <div class="container-fluid">

        <div class="card form-card">

            {{-- HEADER --}}
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-key" style="color:#3b4cca"></i>
                    {{ $title }}
                </div>

                <a href="{{ url('/pegawai') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            {{-- FORM --}}
            <div class="form-container">

                <form method="post" action="{{ url('/pegawai/edit-password-proses/'.$karyawan->id) }}">
                    @method('put')
                    @csrf

                    <div class="form-group mb-3">
                        <label for="name">Nama</label>
                        <input type="text" class="form-control" value="{{ $karyawan->name }}" disabled id="name">
                    </div>

                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password">

                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Submit
                    </button>

                </form>

            </div>

        </div>

    </div>
</div>

@endsection