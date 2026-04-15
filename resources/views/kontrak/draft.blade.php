{{-- resources/views/kontrak/draft.blade.php --}}
{{-- Halaman preview sebelum generate .docx --}}
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
.primary-btn { background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk)); color: #fff; border: none; padding: 8px 16px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: .2s; display: inline-flex; align-items: center; gap: 6px; }
.primary-btn:hover { transform: translateY(-1px); color: #fff; }
.danger-btn { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; border: none; padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: .2s; }
.danger-btn:hover { transform: translateY(-1px); color: #fff; }
.form-body { padding: 24px; }
.form-label { font-size: 13px; font-weight: 600; color: var(--slate-700); margin-bottom: 6px; display: block; }
.form-control { border-radius: 10px; height: 42px; border: 1px solid var(--slate-200); font-size: 14px; background: #fff; }
.form-control:focus { border-color: var(--dash-blue); box-shadow: 0 0 0 .15rem rgba(59,76,202,.15); }
.info-row { display: flex; gap: 6px; padding: 10px 0; border-bottom: 1px solid var(--slate-100); }
.info-label { font-size: 13px; font-weight: 600; color: #64748b; min-width: 200px; }
.info-value { font-size: 14px; color: var(--slate-700); }
.preview-box { background: var(--slate-50); border: 1px solid var(--slate-200); border-radius: 12px; padding: 20px 24px; margin-bottom: 24px; }
.preview-title { font-size: 13px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: .05em; margin-bottom: 12px; }
.submit-bar { padding: 16px 24px; border-top: 1px solid var(--slate-200); background: var(--slate-50); display: flex; justify-content: flex-end; gap: 10px; }
.alert-info-custom { background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px 16px; font-size: 13px; color: #1d4ed8; margin-bottom: 20px; }
</style>

<div class="row">
<div class="col-md-12"><br>
<div class="card form-card">

    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-file-word" style="color:#2563eb"></i>&nbsp;Generate Dokumen Kontrak
        </div>
        <a href="{{ url('/kontrak') }}" class="danger-btn">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>

    <div class="form-body">

        <div class="alert-info-custom">
            <i class="fas fa-info-circle"></i>
            Data pegawai sudah otomatis terisi dari database. Lengkapi <strong>Nomor Surat</strong> di bawah ini, lalu klik <strong>Generate .docx</strong> untuk mengunduh draf kontrak.
        </div>

        {{-- Preview Data --}}
        <div class="preview-box">
            <div class="preview-title"><i class="fas fa-user"></i>&nbsp; Data Pegawai (dari Database)</div>
            <div class="info-row"><span class="info-label">Nama Pegawai</span><span class="info-value">{{ $kontrak->user->name ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Jabatan</span><span class="info-value">{{ $kontrak->user->jabatan->nama_jabatan ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Alamat</span><span class="info-value">{{ $kontrak->user->alamat ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">No. KTP</span><span class="info-value">{{ $kontrak->user->ktp ?? '-' }}</span></div>
        </div>

        <div class="preview-box">
            <div class="preview-title"><i class="fas fa-file-contract"></i>&nbsp; Detail Kontrak</div>
            <div class="info-row"><span class="info-label">Jenis Kontrak</span><span class="info-value">{{ $kontrak->jenis_kontrak ?? '-' }}</span></div>
            <div class="info-row"><span class="info-label">Tanggal Surat</span><span class="info-value">
                @php Carbon\Carbon::setLocale('id'); @endphp
                {{ $kontrak->tanggal ? Carbon\Carbon::parse($kontrak->tanggal)->translatedFormat('d F Y') : '-' }}
            </span></div>
            <div class="info-row"><span class="info-label">Tanggal Mulai</span><span class="info-value">
                {{ $kontrak->tanggal_mulai ? Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') : '-' }}
            </span></div>
            <div class="info-row"><span class="info-label">Tanggal Selesai</span><span class="info-value">
                {{ $kontrak->tanggal_selesai ? Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') : '— (Tidak Tertentu)' }}
            </span></div>
            <div class="info-row"><span class="info-label">Keterangan / Lingkup Kerja</span><span class="info-value">{{ $kontrak->keterangan ?? '-' }}</span></div>
        </div>

        {{-- Input Nomor Surat --}}
        <form action="{{ url('/kontrak/generate/'.$kontrak->id) }}" method="GET" target="_blank">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-hashtag" style="color:var(--dash-blue)"></i>
                            Nomor Surat <span style="color:#ef4444">*</span>
                        </label>
                        <input type="text" name="no_surat" class="form-control"
                               placeholder="Contoh: 001/PKWT/HRD/IV/2026"
                               value="{{ $kontrak->no_surat ?? '' }}" required>
                        <small style="color:#94a3b8;font-size:12px">Nomor surat diisi manual sesuai format penomoran perusahaan Anda.</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Nama Perusahaan / Pihak Pertama</label>
                        <input type="text" name="nama_perusahaan" class="form-control"
                               value="{{ config('app.nama_perusahaan', 'PT. NAMA PERUSAHAAN') }}">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Nama Direktur / Pihak Pertama</label>
                        <input type="text" name="nama_direktur" class="form-control"
                               value="{{ config('app.nama_direktur', 'NAMA DIREKTUR') }}">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label">Jabatan Direktur</label>
                        <input type="text" name="jabatan_direktur" class="form-control"
                               value="{{ config('app.jabatan_direktur', 'DIREKTUR') }}">
                    </div>
                </div>
            </div>

            <div class="submit-bar" style="margin: 0 -24px -24px; border-radius: 0 0 16px 16px;">
                <a href="{{ url('/kontrak') }}" class="danger-btn">Batal</a>
                <button type="submit" class="primary-btn">
                    <i class="fas fa-file-download"></i> Generate & Download .docx
                </button>
            </div>
        </form>

    </div>
</div>
</div>
</div>

@endsection