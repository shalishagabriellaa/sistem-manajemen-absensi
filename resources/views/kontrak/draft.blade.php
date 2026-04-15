{{-- resources/views/kontrak/draft.blade.php --}}
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

.preview-box{
    background:var(--slate-50);
    border:1px solid var(--slate-200);
    border-radius:12px;
    padding:20px 24px;
    margin-bottom:24px;
}
.preview-title{
    font-size:11px;
    font-weight:700;
    text-transform:uppercase;
    letter-spacing:.6px;
    color:var(--slate-500);
    margin-bottom:14px;
    padding-bottom:8px;
    border-bottom:1px dashed var(--slate-200);
}
.info-row{
    display:flex;
    gap:10px;
    padding:7px 0;
    border-bottom:1px solid var(--slate-100);
    font-size:13px;
}
.info-row:last-child{ border-bottom:none; }
.info-label{ font-weight:600; color:var(--slate-500); min-width:190px; }
.info-value{ color:var(--slate-700); }

.alert-info-custom{
    background:#eff6ff;
    border:1px solid #bfdbfe;
    border-radius:10px;
    padding:12px 16px;
    font-size:13px;
    color:#1d4ed8;
    margin-bottom:24px;
    display:flex;
    align-items:flex-start;
    gap:10px;
}
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
.submit-bar{
    padding:16px 28px;
    border-top:1px solid var(--slate-200);
    background:var(--slate-50);
    display:flex;
    justify-content:flex-end;
    gap:10px;
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

</style>

<div class="container-fluid">
<br>
<div class="card karyawan-card">

    <div class="karyawan-header">
        <div class="karyawan-title">
            <i class="fas fa-file-word" style="color:#2563eb; margin-right:8px;"></i>
            Generate Dokumen Kontrak
        </div>
        <a href="{{ url('/kontrak') }}" class="primary-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-body">

        <div class="alert-info-custom">
            <i class="fas fa-info-circle" style="margin-top:2px; flex-shrink:0;"></i>
            <span>Data pegawai sudah otomatis terisi dari database. Lengkapi <strong>Nomor Surat</strong> dan detail perusahaan di bawah, lalu klik <strong>Generate &amp; Download .docx</strong>.</span>
        </div>

        {{-- Preview Data Pegawai --}}
        <div class="preview-box">
            <div class="preview-title"><i class="fas fa-user"></i>&nbsp; Data Pegawai (dari Database)</div>
            <div class="info-row"><span class="info-label">Nama Pegawai</span><span class="info-value">{{ $kontrak->user->name ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Jabatan</span><span class="info-value">{{ $kontrak->user->jabatan->nama_jabatan ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Alamat</span><span class="info-value">{{ $kontrak->user->alamat ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">No. KTP</span><span class="info-value">{{ $kontrak->user->ktp ?? '-' }}</span></div>
        </div>

        {{-- Preview Detail Kontrak --}}
        <div class="preview-box">
            <div class="preview-title"><i class="fas fa-file-contract"></i>&nbsp; Detail Kontrak</div>
            @php Carbon\Carbon::setLocale('id'); @endphp
            <div class="info-row"><span class="info-label">Jenis Kontrak</span><span class="info-value">{{ $kontrak->jenis_kontrak ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Tanggal Surat</span><span class="info-value">{{ $kontrak->tanggal ? Carbon\Carbon::parse($kontrak->tanggal)->translatedFormat('d F Y') : '-' }}</span></div>
            <div class="info-row"><span class="info-label">Tanggal Mulai</span><span class="info-value">{{ $kontrak->tanggal_mulai ? Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') : '-' }}</span></div>
            <div class="info-row"><span class="info-label">Tanggal Selesai</span><span class="info-value">{{ $kontrak->tanggal_selesai ? Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') : '— (Tidak Tertentu)' }}</span></div>
            <div class="info-row"><span class="info-label">Keterangan / Lingkup Kerja</span><span class="info-value">{{ $kontrak->keterangan ?? '-' }}</span></div>
        </div>

        {{-- Form Generate --}}
        <form action="{{ url('/kontrak/generate/'.$kontrak->id) }}" method="GET" target="_blank">

            <div class="form-section-title"><i class="fas fa-building"></i>&nbsp; Data Perusahaan &amp; Nomor Surat</div>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Nomor Surat <span style="color:#ef4444">*</span></label>
                    <input type="text" name="no_surat" class="form-control"
                           placeholder="Contoh: 001/PKWT/HRD/IV/2026"
                           value="{{ $kontrak->no_surat ?? '' }}" required>
                    <small style="color:var(--slate-500);font-size:12px;">Sesuaikan dengan format penomoran perusahaan.</small>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Nama Perusahaan / Pihak Pertama</label>
                    <input type="text" name="nama_perusahaan" class="form-control"
                           value="{{ config('app.nama_perusahaan', 'PT. NAMA PERUSAHAAN') }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Alamat Perusahaan</label>
                    <input type="text" name="alamat_perusahaan" class="form-control"
                           value="{{ config('app.alamat_perusahaan', 'Jl. Alamat Perusahaan No.1') }}">
                </div>
            </div>
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Nama Direktur / Pihak Pertama</label>
                    <input type="text" name="nama_direktur" class="form-control"
                           value="{{ config('app.nama_direktur', 'NAMA DIREKTUR') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Jabatan Direktur</label>
                    <input type="text" name="jabatan_direktur" class="form-control"
                           value="{{ config('app.jabatan_direktur', 'DIREKTUR') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Kota</label>
                    <input type="text" name="kota" class="form-control"
                           value="{{ config('app.kota', 'Kota') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Nama Pengadilan Negeri</label>
                    <input type="text" name="pengadilan" class="form-control"
                           value="{{ config('app.pengadilan', 'Pengadilan Negeri') }}">
                </div>
            </div>

            <div class="submit-bar" style="margin: 28px -28px -28px; border-radius: 0 0 16px 16px;">
                <a href="{{ url('/kontrak') }}" class="action-btn danger">Batal</a>
                <button type="submit" class="action-btn primary">
                    <i class="fas fa-file-download"></i> Generate &amp; Download .docx
                </button>
            </div>
        </form>

    </div>
</div>
</div>

@endsection