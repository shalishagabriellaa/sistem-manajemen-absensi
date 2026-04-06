@extends('templates.app')
@section('container')
    <div class="card-secton transfer-section">
        <div class="tf-container">
            <div class="tf-balance-box">
                <form method="post" class="tf-form p-2" action="{{ url('/inventory/store') }}">
                    @csrf

                    <div class="group-input">
                        <label for="kode_barang">Kode Barang</label>
                        <input type="text"
                               class="@error('kode_barang') is-invalid @enderror"
                               id="kode_barang" name="kode_barang"
                               value="{{ old('kode_barang', $kode_barang) }}">
                        @error('kode_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Jenis Barang & Merek --}}
                    <div class="row">
                        <div class="col group-input">
                            <label for="jenis_barang">Jenis Barang <span class="text-muted small">(Opsional)</span></label>
                            <br><select class="@error('jenis_barang') is-invalid @enderror"
                                    id="jenis_barang" name="jenis_barang">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach(['STB', 'ONT', 'Remote', 'Dropcore', 'Precon', 'Rate Score'] as $jenis)
                                    <option value="{{ $jenis }}" {{ old('jenis_barang') == $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col group-input">
                            <br><label for="merek">Merek <span class="text-muted small">(Opsional)</span></label>
                            <input type="text"
                                   class="@error('merek') is-invalid @enderror"
                                   id="merek" name="merek"
                                   placeholder=""
                                   value="{{ old('merek') }}">
                            @error('merek')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="group-input">
                        <label for="nama_barang">Nama Barang</label>
                        <input type="text"
                               class="@error('nama_barang') is-invalid @enderror"
                               id="nama_barang" name="nama_barang"
                               value="{{ old('nama_barang') }}">
                        @error('nama_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col group-input">
                            <label for="stok">Stok</label>
                            <input type="number" step="0.01"
                                   class="@error('stok') is-invalid @enderror"
                                   id="stok" name="stok"
                                   value="{{ old('stok') }}">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col group-input">
                            <label for="uom">UoM</label>
                            <input type="text"
                                   class="@error('uom') is-invalid @enderror"
                                   id="uom" name="uom"
                                   placeholder=""
                                   value="{{ old('uom') }}">
                            @error('uom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="group-input">
                        <label for="desc">Description <span class="text-muted small">(Opsional)</span></label>
                        <textarea name="desc" id="desc"
                                  class="@error('desc') is-invalid @enderror"
                                  rows="4">{{ old('desc') }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col group-input">
                            <br><label style="z-index: 1000" for="lokasi_id">Lokasi</label>
                            <select class="@error('lokasi_id') is-invalid @enderror"
                                    id="lokasi_id" name="lokasi_id">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($lokasi as $lok)
                                    <option value="{{ $lok->id }}" {{ old('lokasi_id') == $lok->id ? 'selected' : '' }}>
                                        {{ $lok->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="jabatan_id">Divisi / Jabatan</label>
                            <select class="form-control selectpicker @error('jabatan_id') is-invalid @enderror"
                                    id="jabatan_id" name="jabatan_id" data-live-search="true">
                                <option value="">-- Pilih Divisi / Jabatan --</option>
                                @foreach ($jabatan as $jab)
                                    <option value="{{ $jab->id }}" {{ old('jabatan_id') == $jab->id ? 'selected' : '' }}>
                                        {{ $jab->nama_jabatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jabatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-right">Submit</button>
                </form>
            </div>
        </div>
    </div>
    <br><br><br><br>

    @push('script')
    <script>
        // Init select2 untuk dropdown di template app
        $('#jenis_barang, #lokasi_id').select2();
    </script>
    @endpush
@endsection
