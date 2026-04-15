@extends('templates.dashboard')

@section('isi')
<div class="row">
    <div class="col-md-12 m project-list">
        <div class="card">
            <div class="row">
                <div class="col-md-6 p-0 d-flex mt-2">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="col-md-6 p-0 text-end">
                    <a href="{{ url('/shift') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <form method="post" class="p-4" action="{{ url('/shift') }}">
                @csrf

                {{-- Nama Shift --}}
                <div class="form-group mb-3">
                    <label for="nama_shift" class="form-label">Nama Shift</label>
                    <input type="text"
                        class="form-control @error('nama_shift') is-invalid @enderror"
                        id="nama_shift"
                        name="nama_shift"
                        value="{{ old('nama_shift') }}"
                        required>

                    @error('nama_shift')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>


                {{-- Jam Masuk & Jam Keluar --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="jam_masuk" class="form-label">Jam Masuk</label>
                        <input type="time"
                            class="form-control @error('jam_masuk') is-invalid @enderror"
                            id="jam_masuk"
                            name="jam_masuk"
                            value="{{ old('jam_masuk') }}"
                            required>

                        @error('jam_masuk')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jam_keluar" class="form-label">Jam Keluar</label>
                        <input type="time"
                            class="form-control @error('jam_keluar') is-invalid @enderror"
                            id="jam_keluar"
                            name="jam_keluar"
                            value="{{ old('jam_keluar') }}"
                            required>

                        @error('jam_keluar')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>


                {{-- Jam Istirahat --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="jam_mulai_istirahat" class="form-label">Jam Mulai Istirahat</label>
                        <input type="time"
                            class="form-control @error('jam_mulai_istirahat') is-invalid @enderror"
                            id="jam_mulai_istirahat"
                            name="jam_mulai_istirahat"
                            value="{{ old('jam_mulai_istirahat') }}">

                        @error('jam_mulai_istirahat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="jam_selesai_istirahat" class="form-label">Jam Selesai Istirahat</label>
                        <input type="time"
                            class="form-control @error('jam_selesai_istirahat') is-invalid @enderror"
                            id="jam_selesai_istirahat"
                            name="jam_selesai_istirahat"
                            value="{{ old('jam_selesai_istirahat') }}">

                        @error('jam_selesai_istirahat')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                </div>


                {{-- Button --}}
                <button type="submit" class="btn btn-primary float-end">
                    Submit
                </button>

            </form>
        </div>
    </div>
</div>
@endsection