@extends('templates.app')
@section('container')
    <div class="card-secton transfer-section">
        <div class="tf-container">
            <div class="tf-balance-box">
                <form method="post" class="tf-form p-2" action="{{ url('/inventory/update/'.$inventory->id) }}">
                    @csrf
                    @method('PUT')

                    <div class="group-input">
                        <label for="kode_barang">Kode Barang</label>
                        <input type="text"
                               class="@error('kode_barang') is-invalid @enderror"
                               id="kode_barang" name="kode_barang"
                               value="{{ old('kode_barang', $inventory->kode_barang) }}">
                        @error('kode_barang')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col group-input">
                            <label for="jenis_barang">Jenis Barang <span class="text-muted small">(Opsional)</span></label>
                            <select class="@error('jenis_barang') is-invalid @enderror"
                                    id="jenis_barang" name="jenis_barang">
                                <option value="">-- Pilih Jenis --</option>
                                @foreach(['STB', 'ONT', 'Remote', 'Dropcore', 'Precon', 'Rate Score'] as $jenis)
                                    <option value="{{ $jenis }}"
                                        {{ old('jenis_barang', $inventory->jenis_barang) == $jenis ? 'selected' : '' }}>
                                        {{ $jenis }}
                                    </option>
                                @endforeach
                            </select>
                            @error('jenis_barang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col group-input">
                            <label for="merek">Merek <span class="text-muted small">(Opsional)</span></label>
                            <input type="text"
                                   class="@error('merek') is-invalid @enderror"
                                   id="merek" name="merek"
                                   placeholder="Huawei, ZTE..."
                                   value="{{ old('merek', $inventory->merek) }}">
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
                               value="{{ old('nama_barang', $inventory->nama_barang) }}">
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
                                   value="{{ old('stok', $inventory->stok) }}">
                            @error('stok')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col group-input">
                            <label for="uom">UoM</label>
                            <input type="text"
                                   class="@error('uom') is-invalid @enderror"
                                   id="uom" name="uom"
                                   placeholder="pcs, unit, box..."
                                   value="{{ old('uom', $inventory->uom) }}">
                            @error('uom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="group-input">
                        <label for="desc">Description <span class="text-muted small">(Opsional)</span></label>
                        <textarea name="desc" id="desc"
                                  class="@error('desc') is-invalid @enderror"
                                  rows="4">{{ old('desc', $inventory->desc) }}</textarea>
                        @error('desc')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col group-input">
                            <label style="z-index: 1000" for="lokasi_id">Lokasi</label>
                            <select class="@error('lokasi_id') is-invalid @enderror"
                                    id="lokasi_id" name="lokasi_id">
                                <option value="">-- Pilih Lokasi --</option>
                                @foreach ($lokasi as $lok)
                                    <option value="{{ $lok->id }}"
                                        {{ old('lokasi_id', $inventory->lokasi_id) == $lok->id ? 'selected' : '' }}>
                                        {{ $lok->nama_lokasi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('lokasi_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col group-input">
                            <label for="jabatan">Divisi / Jabatan</label>
                            <input type="text" name="jabatan" id="jabatan"
                                   value="{{ old('jabatan', auth()->user()->jabatan->nama_jabatan ?? '') }}" readonly>
                            <input type="hidden" name="jabatan_id" id="jabatan_id"
                                   value="{{ old('jabatan_id', auth()->user()->jabatan_id) }}" readonly>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary float-right">Update</button>
                </form>
            </div>
        </div>
    </div>
    <br><br><br><br>

    @push('script')
    <script>
        $('#jenis_barang, #lokasi_id').select2();
    </script>
    @endpush
@endsection
