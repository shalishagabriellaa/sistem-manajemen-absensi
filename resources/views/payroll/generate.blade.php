@extends('templates.dashboard')
@section('isi')
<div class="row">
    <div class="col-md-12 project-list">
        <div class="card">
            <div class="row">
                <div class="col-md-6 mt-2 p-0 d-flex">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="col-md-6 p-0">
                    <a href="{{ url('/payroll') }}" class="btn btn-secondary">← Kembali</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url('/payroll/generate') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Karyawan</label>
                        <select name="user_id" class="form-control selectpicker" data-live-search="true" required>
                            <option value="">-- Pilih Karyawan --</option>
                            @foreach($data_user as $u)
                                <option value="{{ $u->id }}" {{ old('user_id') == $u->id ? 'selected' : '' }}>
                                    {{ $u->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Mulai Periode</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                value="{{ old('tanggal_mulai', date('Y-m-01')) }}" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Tanggal Akhir Periode</label>
                            <input type="date" name="tanggal_akhir" class="form-control"
                                value="{{ old('tanggal_akhir', date('Y-m-t')) }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Bayar Kasbon</label>
                        <small class="text-muted d-block mb-1">
                            Kosongkan = lunasi semua. Isi angka jika ingin cicil.
                        </small>
                        <input type="number" name="bayar_kasbon" class="form-control"
                            value="{{ old('bayar_kasbon', 0) }}" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Total THR <small class="text-muted">(isi jika periode ini ada THR)</small></label>
                        <input type="number" name="total_thr" class="form-control"
                            value="{{ old('total_thr', 0) }}" min="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Loss <small class="text-muted">(potongan lain-lain)</small></label>
                        <input type="number" name="loss" class="form-control"
                            value="{{ old('loss', 0) }}" min="0">
                    </div>

                    <button type="submit" class="btn btn-success px-4">
                        ⚡ Generate Slip Gaji
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection