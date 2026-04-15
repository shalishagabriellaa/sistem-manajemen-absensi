@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

* {
    font-family: 'Plus Jakarta Sans', sans-serif;
}

:root {
    --dash-blue: #3b4cca;
    --dash-blue-dk: #2d3db4;
    --dash-blue-lt: #5c6ed4;

    --slate-50: #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-300: #cbd5e1;
    --slate-500: #64748b;
    --slate-700: #334155;
}

.form-card {
    border-radius: 16px;
    border: 1px solid var(--slate-200);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}

.form-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid var(--slate-200);
    background: linear-gradient(135deg, #fff, #f8fafc);
}

.form-title {
    font-size: 20px;
    font-weight: 700;
    color: var(--slate-700);
    display: flex;
    align-items: center;
    gap: 8px;
}

.action-btn {
    padding: 7px 14px;
    font-size: 13px;
    font-weight: 600;
    border-radius: 8px;
    border: 1px solid var(--slate-200);
    background: white;
    color: var(--slate-700);
    transition: .2s;
    text-decoration: none;
}

.action-btn:hover {
    background: var(--dash-blue);
    color: white;
    border-color: var(--dash-blue);
}

.form-container {
    padding: 20px;
}

.form-control {
    border-radius: 8px;
    font-size: 14px;
    height: 38px;
    border: 1px solid var(--slate-200);
}

.form-control:focus {
    border-color: var(--dash-blue);
    box-shadow: 0 0 0 0.15rem rgba(59,76,202,0.15);
}

.form-group label {
    font-weight: 600;
    font-size: 14px;
    color: var(--slate-700);
    margin-bottom: 6px;
}

.submit-btn {
    background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk));
    color: white;
    border: none;
    padding: 10px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    box-shadow: 0 4px 14px rgba(59,76,202,0.25);
    transition: all .2s ease;
}

.submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(59,76,202,0.35);
}

/* SELECT */
.bootstrap-select .dropdown-toggle {
    border-radius: 8px;
    font-size: 14px;
    height: 38px;
    border: 1px solid var(--slate-200);
}

/* TABLE */
.table-card {
    border-radius: 16px;
    border: 1px solid var(--slate-200);
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    overflow: hidden;
}

.table-header {
    padding: 20px;
    border-bottom: 1px solid var(--slate-200);
    background: linear-gradient(135deg, #fff, #f8fafc);
}

.employee-name {
    font-size: 18px;
    font-weight: 700;
    color: var(--slate-700);
    text-align: center;
    margin-bottom: 14px;
}

.search-input {
    border-radius: 8px;
    font-size: 14px;
    height: 36px;
    border: 1px solid var(--slate-200);
}

.search-btn {
    background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk));
    color: white;
    border: none;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 14px;
    transition: all .2s ease;
}

.search-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59,76,202,0.3);
}

.table thead th {
    background: var(--slate-100);
    color: var(--slate-700);
    font-weight: 600;
    font-size: 13px;
    border-bottom: 1px solid var(--slate-200);
    white-space: nowrap;
}

.table tbody td {
    font-size: 13px;
    color: var(--slate-700);
    vertical-align: middle;
}

.table-actions {
    display: flex;
    gap: 6px;
    list-style: none;
    padding: 0;
    margin: 0;
}

.table-actions li a,
.table-actions li button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 7px;
    border: 1px solid var(--slate-200);
    background: white;
    color: var(--slate-700);
    font-size: 13px;
    transition: .2s;
    text-decoration: none;
    cursor: pointer;
}

.table-actions li.edit a:hover {
    background: var(--dash-blue);
    color: white;
    border-color: var(--dash-blue);
}

.table-actions li.delete button:hover {
    background: #ef4444;
    color: white;
    border-color: #ef4444;
}
</style>

<br>

<div class="row g-3">

    {{-- FORM TAMBAH --}}
    <div class="col-md-4">
        <div class="card form-card">

            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-plus-circle" style="color:#3b4cca"></i>
                    Dinas Luar
                </div>
                <a href="{{ url('/pegawai') }}" class="action-btn">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
            </div>

            <div class="form-container">
                <form method="post" action="{{ url('/pegawai/dinas-luar/proses-tambah-shift') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label for="shift_id">Shift</label>
                        <select class="form-control selectpicker @error('shift_id') is-invalid @enderror"
                                id="shift_id" name="shift_id" data-live-search="true">
                            <option value="">Pilih Shift</option>
                            @foreach ($shift as $s)
                                <option value="{{ $s->id }}" {{ old('shift_id') == $s->id ? 'selected' : '' }}>
                                    {{ $s->nama_shift . " (" . $s->jam_masuk . " - " . $s->jam_keluar . ")" }}
                                </option>
                            @endforeach
                        </select>
                        @error('shift_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="tanggal_mulai">Tanggal Mulai</label>
                        <input type="datetime" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                               id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}">
                        @error('tanggal_mulai')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="datetime" class="form-control @error('tanggal_akhir') is-invalid @enderror"
                               id="tanggal_akhir" name="tanggal_akhir" value="{{ old('tanggal_akhir') }}">
                        @error('tanggal_akhir')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <input type="hidden" name="tanggal">
                    <input type="hidden" name="status_absen">
                    <input type="hidden" name="user_id" value="{{ $karyawan->id }}">

                    <button type="submit" class="submit-btn">
                        <i class="fas fa-save"></i> Submit
                    </button>
                </form>
            </div>

        </div>
    </div>

    {{-- TABEL DATA --}}
    <div class="col-md-8">
        <div class="card table-card">

            <div class="table-header">
                <!--<div class="employee-name">-->
                <!--    <i class="fas fa-user" style="color:#3b4cca; font-size:16px;"></i>-->
                <!--    {{ $karyawan->name }}-->
                <!--</div>-->
                <form action="{{ url('/pegawai/dinas-luar/'.$karyawan->id) }}">
                    <div class="row g-2 align-items-center">
                        <div class="col-auto">
                            <input type="datetime" class="form-control search-input" 
                                   name="tanggal" placeholder="Cari Tanggal..."
                                   id="tanggal" value="{{ request('tanggal') }}">
                        </div>
                        <div class="col-auto">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i> Cari
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped mb-0" id="mytable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tanggal</th>
                                <th>Shift Pegawai</th>
                                <th>Jam Masuk</th>
                                <th>Jam Keluar</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dinas_luar as $key => $dl)
                                <tr>
                                    <td>{{ ($dinas_luar->currentpage() - 1) * $dinas_luar->perpage() + $key + 1 }}.</td>
                                    <td>{{ $dl->tanggal }}</td>
                                    <td>{{ $dl->Shift->nama_shift }}</td>
                                    <td>{{ $dl->Shift->jam_masuk }}</td>
                                    <td>{{ $dl->Shift->jam_keluar }}</td>
                                    <td>
                                        <ul class="table-actions">
                                            <li class="edit">
                                                <a href="{{ url('/pegawai/edit-dinas/'.$dl->id) }}" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                            </li>
                                            <li class="delete">
                                                <form action="{{ url('/pegawai/delete-dinas/'.$dl->id) }}"
                                                      method="post" class="d-inline">
                                                    @method('delete')
                                                    @csrf
                                                    <input type="hidden" name="user_id" value="{{ $karyawan->id }}">
                                                    <button title="Hapus"
                                                            onclick="return confirm('Are You Sure?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="d-flex justify-content-end p-3">
                {{ $dinas_luar->links() }}
            </div>

        </div>
    </div>

</div>

@endsection