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

.form-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
}

.form-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px;
    border-bottom:1px solid var(--slate-200);
}

.form-title{
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
    background:white;
    color:var(--slate-700);
    transition:.2s;
}

.action-btn:hover{
    background:var(--dash-blue);
    color:white;
    border-color:var(--dash-blue);
}

.submit-btn{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    border:none;
    border-radius:10px;
    padding:10px 22px;
    font-weight:600;
}

.submit-btn:hover{
    opacity:.9;
}

.form-section-title{
    font-size:16px;
    font-weight:700;
    color:var(--dash-blue);
    margin-top:20px;
}

.form-container {
    width: 100%;
}

.form-control,
.selectpicker{
border-radius:8px;
font-size:14px;
padding:8px 12px;
height:38px;
}

textarea.form-control{
min-height:70px;
}

.submit-btn{
background:linear-gradient(135deg,#3b4cca,#2d3db4);
color:white;
border:none;
padding:10px 26px;
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
</style>

<br>
    <div class="row">
        <div class="container-fluid">

<div class="card form-card">

<div class="form-header">

<div class="form-title">
<i class="fas fa-user-plus" style="color:#3b4cca;margin-right:6px;"></i>
{{ $title }}
</div>

<a href="{{ url('/pegawai') }}" class="action-btn">
<i class="fas fa-arrow-left"></i> Back
</a>

</div>
        <div class="p-4 form-container">
                
            @if ($errors->any())
            <div class="alert alert-danger" id="form-error">
                <b>Mohon perbaiki data berikut:</b>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>⚠️ {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

<form method="post" action="{{ url('/pegawai/tambah-pegawai-proses') }}" enctype="multipart/form-data">
    @csrf

    <div class="form-section-title mb-2">Data Pribadi</div>
    {{-- Row 1: Nama, Foto, Email --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="name">Nama Pegawai</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" required autofocus value="{{ old('name') }}">
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="foto_karyawan">Foto Pegawai</label>
            <input class="form-control @error('foto_karyawan') is-invalid @enderror" type="file" id="foto_karyawan" name="foto_karyawan">
            @error('foto_karyawan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="email">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" required value="{{ old('email') }}">
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 2: Telepon, Username, Password --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="telepon">Nomor Handphone</label>
            <input type="number" class="form-control @error('telepon') is-invalid @enderror" id="telepon" name="telepon" required value="{{ old('telepon') }}">
            @error('telepon')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="username">Username</label>
            <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" required value="{{ old('username') }}">
            @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="password">Password</label>
            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required value="{{ old('password') }}">
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 3: Lokasi, Tgl Lahir, Tgl Join --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="lokasi_id">Lokasi Kantor</label>
            <select name="lokasi_id" id="lokasi_id" class="form-control @error('lokasi_id') is-invalid @enderror selectpicker" data-live-search="true">
                <option value="">Pilih Lokasi Kantor</option>
                @foreach ($data_lokasi as $dl)
                    <option value="{{ $dl->id }}" {{ old('lokasi_id') == $dl->id ? 'selected' : '' }}>{{ $dl->nama_lokasi }}</option>
                @endforeach
            </select>
            @error('lokasi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="tgl_lahir">Tanggal Lahir</label>
            <input type="date" class="form-control @error('tgl_lahir') is-invalid @enderror" id="tgl_lahir" name="tgl_lahir" required value="{{ old('tgl_lahir') }}">
            @error('tgl_lahir')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="tgl_join">Tanggal Masuk Perusahaan</label>
            <input type="date" class="form-control @error('tgl_join') is-invalid @enderror" id="tgl_join" name="tgl_join" required value="{{ old('tgl_join') }}">
            @error('tgl_join')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 4: Masa Kerja, Role, Gender --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="masa_kerja">Masa Kerja</label>
            <input type="text" class="form-control @error('masa_kerja') is-invalid @enderror" id="masa_kerja" name="masa_kerja" required value="{{ old('masa_kerja') }}" disabled>
            @error('masa_kerja')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label>Role</label>
            <select class="form-control selectpicker @error('role') is-invalid @enderror" id="role" name="role[]" required multiple>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ (is_array(old('role')) && in_array($role->name, old('role'))) ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 mb-3">
            @php $gender = [['gender'=>'Laki-Laki'],['gender'=>'Perempuan']]; @endphp
            <label for="gender">Gender</label>
            <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror selectpicker" required data-live-search="true">
                <option value="">Pilih Gender</option>
                @foreach ($gender as $g)
                    <option value="{{ $g['gender'] }}" {{ old('gender') == $g['gender'] ? 'selected' : '' }}>{{ $g['gender'] }}</option>
                @endforeach
            </select>
            @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 5: Status Nikah, Dashboard, Status Pajak --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            @php
                $sNikah = [
                    ['status'=>'TK/0'],['status'=>'TK/1'],['status'=>'K/0'],
                    ['status'=>'TK/2'],['status'=>'K/1'],['status'=>'TK/3'],
                    ['status'=>'K/2'],['status'=>'K/3'],
                ];
            @endphp
            <label for="status_nikah">Status Pernikahan</label>
            <select name="status_nikah" id="status_nikah" class="form-control @error('status_nikah') is-invalid @enderror selectpicker" required data-live-search="true">
                <option value="">Pilih Status Pernikahan</option>
                @foreach ($sNikah as $s)
                    <option value="{{ $s['status'] }}" {{ old('status_nikah') == $s['status'] ? 'selected' : '' }}>{{ $s['status'] }}</option>
                @endforeach
            </select>
            @error('status_nikah')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            @php $is_admin = [['is_admin'=>'admin'],['is_admin'=>'user']]; @endphp
            <label for="is_admin">Dashboard</label>
            <select name="is_admin" id="is_admin" class="form-control @error('is_admin') is-invalid @enderror selectpicker" required data-live-search="true">
                <option value="">Pilih Dashboard</option>
                @foreach ($is_admin as $a)
                    <option value="{{ $a['is_admin'] }}" {{ old('is_admin') == $a['is_admin'] ? 'selected' : '' }}>{{ $a['is_admin'] }}</option>
                @endforeach
            </select>
            @error('is_admin')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="status_pajak_id">Status Pajak</label>
            <select name="status_pajak_id" id="status_pajak_id" class="form-control @error('status_pajak_id') is-invalid @enderror selectpicker" required data-live-search="true">
                <option value="">Pilih Status</option>
                @foreach ($status_pajak as $pajak)
                    <option value="{{ $pajak->id }}" {{ old('status_pajak_id') == $pajak->id ? 'selected' : '' }}>{{ $pajak->name }}</option>
                @endforeach
            </select>
            @error('status_pajak_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 6: Divisi, KTP, Kartu Keluarga --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="jabatan_id">Divisi</label>
            <select name="jabatan_id" id="jabatan_id" class="form-control @error('jabatan_id') is-invalid @enderror selectpicker" required data-live-search="true">
                <option value="">Pilih Divisi</option>
                @foreach ($data_jabatan as $dj)
                    <option value="{{ $dj->id }}" {{ old('jabatan_id') == $dj->id ? 'selected' : '' }}>{{ $dj->nama_jabatan }}</option>
                @endforeach
            </select>
            @error('jabatan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="ktp">Nomor KTP</label>
            <input type="number" class="form-control @error('ktp') is-invalid @enderror" id="ktp" name="ktp" required value="{{ old('ktp') }}">
            @error('ktp')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="kartu_keluarga">Nomor Kartu Keluarga</label>
            <input type="number" class="form-control @error('kartu_keluarga') is-invalid @enderror" id="kartu_keluarga" name="kartu_keluarga" required value="{{ old('kartu_keluarga') }}">
            @error('kartu_keluarga')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 7: BPJS Kesehatan, BPJS Ketenagakerjaan, NPWP --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="bpjs_kesehatan">Nomor BPJS Kesehatan</label>
            <input type="number" class="form-control @error('bpjs_kesehatan') is-invalid @enderror" id="bpjs_kesehatan" name="bpjs_kesehatan" required value="{{ old('bpjs_kesehatan') }}">
            @error('bpjs_kesehatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="bpjs_ketenagakerjaan">Nomor BPJS Ketenagakerjaan</label>
            <input type="number" class="form-control @error('bpjs_ketenagakerjaan') is-invalid @enderror" id="bpjs_ketenagakerjaan" name="bpjs_ketenagakerjaan" required value="{{ old('bpjs_ketenagakerjaan') }}">
            @error('bpjs_ketenagakerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="npwp">Nomor NPWP</label>
            <input type="number" class="form-control @error('npwp') is-invalid @enderror" id="npwp" name="npwp" required value="{{ old('npwp') }}">
            @error('npwp')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 8: SIM, No PKWT, No Kontrak --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="sim">Nomor SIM</label>
            <input type="number" class="form-control @error('sim') is-invalid @enderror" id="sim" name="sim" required value="{{ old('sim') }}">
            @error('sim')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="no_pkwt">Nomor PKWT</label>
            <input type="number" class="form-control @error('no_pkwt') is-invalid @enderror" id="no_pkwt" name="no_pkwt" required value="{{ old('no_pkwt') }}">
            @error('no_pkwt')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="no_kontrak">Nomor Kontrak</label>
            <input type="number" class="form-control @error('no_kontrak') is-invalid @enderror" id="no_kontrak" name="no_kontrak" required value="{{ old('no_kontrak') }}">
            @error('no_kontrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 9: Tgl Mulai PKWT, Tgl Berakhir PKWT, Masa Berlaku --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="tanggal_mulai_pkwt">Tanggal Mulai PKWT</label>
            <input type="date" class="form-control @error('tanggal_mulai_pkwt') is-invalid @enderror" id="tanggal_mulai_pkwt" name="tanggal_mulai_pkwt" required value="{{ old('tanggal_mulai_pkwt') }}">
            @error('tanggal_mulai_pkwt')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="tanggal_berakhir_pkwt">Tanggal Berakhir PKWT</label>
            <input type="date" class="form-control @error('tanggal_berakhir_pkwt') is-invalid @enderror" id="tanggal_berakhir_pkwt" name="tanggal_berakhir_pkwt" required value="{{ old('tanggal_berakhir_pkwt') }}">
            @error('tanggal_berakhir_pkwt')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="masa_berlaku">Masa Berlaku</label>
            <input type="date" class="form-control @error('masa_berlaku') is-invalid @enderror" id="masa_berlaku" name="masa_berlaku" required value="{{ old('masa_berlaku') }}">
            @error('masa_berlaku')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Row 10: Rekening, Nama Rekening -- lebar karena nama bisa panjang, tetap 2 col saja --}}
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="rekening">Nomor Rekening</label>
            <input type="number" class="form-control @error('rekening') is-invalid @enderror" id="rekening" name="rekening" required value="{{ old('rekening') }}">
            @error('rekening')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="nama_rekening">Nama Pemilik Rekening</label>
            <input type="text" class="form-control @error('nama_rekening') is-invalid @enderror" id="nama_rekening" name="nama_rekening" required value="{{ old('nama_rekening') }}">
            @error('nama_rekening')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Alamat — full width karena textarea --}}
    <div class="row">
        <div class="col-12 mb-3">
            <label for="alamat">Alamat</label>
            <textarea name="alamat" id="alamat" class="form-control @error('alamat') is-invalid @enderror" required>{{ old('alamat') }}</textarea>
            @error('alamat')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Section: Cuti & Izin --}}
    <div class="form-section-title mb-2">Cuti &amp; Izin</div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="izin_cuti">Cuti</label>
            <input type="number" class="form-control @error('izin_cuti') is-invalid @enderror" id="izin_cuti" name="izin_cuti" value="{{ old('izin_cuti') }}">
            @error('izin_cuti')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="izin_lainnya">Izin Masuk</label>
            <input type="number" class="form-control @error('izin_lainnya') is-invalid @enderror" id="izin_lainnya" name="izin_lainnya" value="{{ old('izin_lainnya') }}">
            @error('izin_lainnya')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-4 mb-3">
            <label for="izin_telat">Izin Telat</label>
            <input type="number" class="form-control @error('izin_telat') is-invalid @enderror" id="izin_telat" name="izin_telat" value="{{ old('izin_telat') }}">
            @error('izin_telat')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label for="izin_pulang_cepat">Izin Pulang Cepat</label>
            <input type="number" class="form-control @error('izin_pulang_cepat') is-invalid @enderror" id="izin_pulang_cepat" name="izin_pulang_cepat" value="{{ old('izin_pulang_cepat') }}">
            @error('izin_pulang_cepat')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
    </div>

    {{-- Section: Penjumlahan Gaji --}}
    <div class="form-section-title mb-2">Penjumlahan Gaji</div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Gaji Pokok</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('gaji_pokok') is-invalid @enderror" name="gaji_pokok" required value="{{ old('gaji_pokok') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('gaji_pokok')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Tunjangan Makan</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('tunjangan_makan') is-invalid @enderror" name="tunjangan_makan" value="{{ old('tunjangan_makan') }}">
                <div class="input-group-text"><span>/ Hari</span></div>
                @error('tunjangan_makan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Tunjangan Transport</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('tunjangan_transport') is-invalid @enderror" name="tunjangan_transport" value="{{ old('tunjangan_transport') }}">
                <div class="input-group-text"><span>/ Hari</span></div>
                @error('tunjangan_transport')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Tunjangan BPJS Kesehatan</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('tunjangan_bpjs_kesehatan') is-invalid @enderror" name="tunjangan_bpjs_kesehatan" required value="{{ old('tunjangan_bpjs_kesehatan') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('tunjangan_bpjs_kesehatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Tunjangan BPJS Ketenagakerjaan</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('tunjangan_bpjs_ketenagakerjaan') is-invalid @enderror" name="tunjangan_bpjs_ketenagakerjaan" required value="{{ old('tunjangan_bpjs_ketenagakerjaan') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('tunjangan_bpjs_ketenagakerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Lembur</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('lembur') is-invalid @enderror" name="lembur" value="{{ old('lembur') }}">
                <div class="input-group-text"><span>/ Jam</span></div>
                @error('lembur')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>100% Kehadiran</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('kehadiran') is-invalid @enderror" name="kehadiran" value="{{ old('kehadiran') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('kehadiran')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>THR</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('thr') is-invalid @enderror" name="thr" required value="{{ old('thr') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('thr')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Bonus Pribadi (KPI)</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('bonus_pribadi') is-invalid @enderror" name="bonus_pribadi" value="{{ old('bonus_pribadi') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('bonus_pribadi')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Bonus Team (KPI)</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('bonus_team') is-invalid @enderror" name="bonus_team" value="{{ old('bonus_team') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('bonus_team')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    {{-- Section: Pengurangan Gaji --}}
    <div class="form-section-title mb-2">Pengurangan Gaji</div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Izin</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('izin') is-invalid @enderror" name="izin" value="{{ old('izin') }}">
                <div class="input-group-text"><span>/ hari</span></div>
                @error('izin')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Terlambat</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('terlambat') is-invalid @enderror" name="terlambat" value="{{ old('terlambat') }}">
                <div class="input-group-text"><span>/ hari</span></div>
                @error('terlambat')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Mangkir</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('mangkir') is-invalid @enderror" name="mangkir" value="{{ old('mangkir') }}">
                <div class="input-group-text"><span>/ hari</span></div>
                @error('mangkir')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label>Saldo Kasbon</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('saldo_kasbon') is-invalid @enderror" name="saldo_kasbon" value="{{ old('saldo_kasbon') }}">
                <div class="input-group-text"><span>/ Tahun</span></div>
                @error('saldo_kasbon')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Potongan BPJS Kesehatan</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('potongan_bpjs_kesehatan') is-invalid @enderror" name="potongan_bpjs_kesehatan" required value="{{ old('potongan_bpjs_kesehatan') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('potongan_bpjs_kesehatan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <label>Potongan BPJS Ketenagakerjaan</label>
            <div class="input-group">
                <input type="text" class="form-control money @error('potongan_bpjs_ketenagakerjaan') is-invalid @enderror" name="potongan_bpjs_ketenagakerjaan" required value="{{ old('potongan_bpjs_ketenagakerjaan') }}">
                <div class="input-group-text"><span>/ Bulan</span></div>
                @error('potongan_bpjs_ketenagakerjaan')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="text-end mt-4">
        <button type="submit" class="submit-btn">
            <i class="fas fa-save"></i> Submit
        </button>
    </div>
</form>
            </div>
        </div>
    </div>

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
    $('.money').mask('000,000,000,000,000', { reverse: true });
 
    $('body').on('change', '#tgl_join', function () {
        let value = $('#tgl_join').val();
        if (!value) return;
        let tgl_join = new Date(value);
        let now_date = new Date(new Date().getTime() + 7 * 60 * 60 * 1000);
        let diffday  = Math.floor((now_date - tgl_join) / (1000 * 60 * 60 * 24));
        let year  = Math.max(0, Math.floor(diffday / 365));
        let month = Math.max(0, Math.floor((diffday % 365) / 30));
        let day   = Math.max(0, (diffday % 365) % 30);
        $('#masa_kerja').val(year + ' Tahun, ' + month + ' Bulan, ' + day + ' Hari.');
    });
 
    const fieldRules = [
        { name: 'name',                           label: 'Nama Pegawai',                   type: 'text' },
        { name: 'email',                          label: 'Email',                           type: 'email' },
        { name: 'telepon',                        label: 'Nomor Handphone',                 type: 'text' },
        { name: 'username',                       label: 'Username',                        type: 'text' },
        { name: 'password',                       label: 'Password',                        type: 'password', min: 6 },
        { name: 'lokasi_id',                      label: 'Lokasi Kantor',                   type: 'select' },
        { name: 'tgl_lahir',                      label: 'Tanggal Lahir',                   type: 'text' },
        { name: 'tgl_join',                       label: 'Tanggal Masuk Perusahaan',        type: 'text' },
        { name: 'masa_berlaku',                   label: 'Masa Berlaku',                    type: 'text' },
        { name: 'role[]',                         label: 'Role',                            type: 'multiselect' },
        { name: 'gender',                         label: 'Gender',                          type: 'select' },
        { name: 'status_nikah',                   label: 'Status Pernikahan',               type: 'select' },
        { name: 'is_admin',                       label: 'Dashboard',                       type: 'select' },
        { name: 'status_pajak_id',                label: 'Status Pajak',                    type: 'select' },
        { name: 'jabatan_id',                     label: 'Divisi',                          type: 'select' },
        { name: 'ktp',                            label: 'Nomor KTP',                       type: 'text' },
        { name: 'kartu_keluarga',                 label: 'Nomor Kartu Keluarga',            type: 'text' },
        { name: 'bpjs_kesehatan',                 label: 'Nomor BPJS Kesehatan',            type: 'text' },
        { name: 'bpjs_ketenagakerjaan',           label: 'Nomor BPJS Ketenagakerjaan',      type: 'text' },
        { name: 'npwp',                           label: 'Nomor NPWP',                      type: 'text' },
        { name: 'sim',                            label: 'Nomor SIM',                       type: 'text' },
        { name: 'no_pkwt',                        label: 'Nomor PKWT',                      type: 'text' },
        { name: 'no_kontrak',                     label: 'Nomor Kontrak',                   type: 'text' },
        { name: 'tanggal_mulai_pkwt',             label: 'Tanggal Mulai PKWT',              type: 'text' },
        { name: 'tanggal_berakhir_pkwt',          label: 'Tanggal Berakhir PKWT',           type: 'text' },
        { name: 'rekening',                       label: 'Nomor Rekening',                  type: 'text' },
        { name: 'nama_rekening',                  label: 'Nama Pemilik Rekening',           type: 'text' },
        { name: 'alamat',                         label: 'Alamat',                          type: 'textarea' },
        { name: 'gaji_pokok',                     label: 'Gaji Pokok',                      type: 'money' },
        { name: 'tunjangan_bpjs_kesehatan',       label: 'Tunjangan BPJS Kesehatan',        type: 'money' },
        { name: 'tunjangan_bpjs_ketenagakerjaan', label: 'Tunjangan BPJS Ketenagakerjaan',  type: 'money' },
        { name: 'thr',                            label: 'THR',                             type: 'money' },
        { name: 'potongan_bpjs_kesehatan',        label: 'Potongan BPJS Kesehatan',         type: 'money' },
        { name: 'potongan_bpjs_ketenagakerjaan',  label: 'Potongan BPJS Ketenagakerjaan',   type: 'money' },
    ];
 
    function getFieldValue(rule) {
        if (rule.type === 'multiselect') {
            const val = $('select[name="' + rule.name + '"]').val();
            return (val && val.length > 0) ? val : null;
        }
        return $('[name="' + rule.name + '"]').val();
    }
 
    function validateField(rule) {
        const val = getFieldValue(rule);
        const isEmpty = val === null || val === undefined || val.toString().trim() === '';
 
        if (isEmpty) return rule.label + ' wajib diisi';
 
        if (rule.type === 'email') {
            if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(val.trim()))
                return 'Format email tidak valid';
        }
 
        if (rule.type === 'password' && rule.min && val.length < rule.min)
            return rule.label + ' minimal ' + rule.min + ' karakter';
 
        return null;
    }
 
    function showFieldError(rule, message) {
        const el = $('[name="' + rule.name + '"]');
        if (!el.length) return;
 
        el.addClass('is-invalid');
 
        // Hapus feedback lama
        el.siblings('.js-feedback').remove();
        el.closest('.input-group').next('.js-feedback').remove();
 
        const html = '<div class="js-feedback" style="color:#dc3545;font-size:0.82em;margin-top:4px;">&#9888; ' + message + '</div>';
 
        if (el.closest('.input-group').length) {
            el.closest('.input-group').next('.js-feedback').remove();
            el.closest('.input-group').after(html);
        } else {
            el.after(html);
        }
    }
 
    function clearFieldError(rule) {
        const el = $('[name="' + rule.name + '"]');
        if (!el.length) return;
        el.removeClass('is-invalid');
        el.siblings('.js-feedback').remove();
        el.closest('.input-group').next('.js-feedback').remove();
    }
 
    // Real-time: blur + change
    fieldRules.forEach(function (rule) {
        $(document).on('blur change', '[name="' + rule.name + '"]', function () {
            const err = validateField(rule);
            err ? showFieldError(rule, err) : clearFieldError(rule);
        });
    });
 
    // Validasi saat submit
    $('form').on('submit', function (e) {
        let firstErrorEl = null;
        let hasError = false;
 
        fieldRules.forEach(function (rule) {
            const err = validateField(rule);
            if (err) {
                showFieldError(rule, err);
                hasError = true;
                if (!firstErrorEl) firstErrorEl = $('[name="' + rule.name + '"]')[0];
            } else {
                clearFieldError(rule);
            }
        });
 
        if (hasError) {
            e.preventDefault();
            if (firstErrorEl) firstErrorEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });
 
    // Scroll ke error Laravel kalau ada
    document.addEventListener('DOMContentLoaded', function () {
        const el = document.getElementById('form-error');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });
</script>
@endpush

</div>
</div>

@endsection