@extends('templates.dashboard')
@section('isi')
    <div class="row">
        <div class="col-md-12 m project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 p-0 d-flex mt-2">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">                    
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <form method="get" action="{{ url('/rekap-data/get-data') }}" class="p-4">
                    <div class="row">
                        <div class="col-md-3">
                    <div class="form-group">
                        <label for="mulai" class="float-start">Tanggal Mulai</label>
                        <input type="datetime" class="form-control @error('mulai') is-invalid @enderror" id="mulai" name="mulai" autofocus value="{{ old('mulai') }}">
                        @error('mulai')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                        </div>
                        <div class="col-md-3">
                    <div class="form-group">
                        <label for="akhir" class="float-start">Tanggal Akhir</label>
                        <input type="datetime" class="form-control @error('akhir') is-invalid @enderror" id="akhir" name="akhir" value="{{ old('akhir') }}">
                        @error('akhir')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="jabatan_id" class="float-start">Divisi</label>
                                <select class="form-control @error('jabatan_id') is-invalid @enderror" id="jabatan_id" name="jabatan_id">
                                    <option value="">Semua Divisi</option>
                                    @foreach($jabatan ?? [] as $j)
                                        <option value="{{ $j->id }}" {{ old('jabatan_id') == $j->id ? 'selected' : '' }}>
                                            {{ $j->nama_jabatan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('jabatan_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="float-start" style="visibility: hidden;">Submit</label>
                                <button type="submit" class="btn btn-primary w-100">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
