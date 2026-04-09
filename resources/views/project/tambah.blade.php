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
                    <a href="{{ url('/project') }}" class="btn btn-danger btn-sm ms-2">Back</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card">
            <form method="POST" class="p-4" action="{{ url('/project/store') }}">
                @csrf

                <div class="form-group mb-3">
                    <label for="user_id">PIC (Penanggung Jawab)</label>
                    <select class="form-control @error('user_id') is-invalid @enderror" id="user_id" name="user_id">
                        <option value="">-- Pilih --</option>
                        @foreach ($user as $us)
                            <option value="{{ $us->id }}" {{ old('user_id') == $us->id ? 'selected' : '' }}>{{ $us->name }}</option>
                        @endforeach
                    </select>
                    @error('user_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="form-group mb-3">
                    <label for="jenis_project">Jenis Project</label>
                    <select class="form-control @error('jenis_project') is-invalid @enderror" id="jenis_project" name="jenis_project">
                        <option value="">-- Pilih --</option>
                        <option value="Internal" {{ old('jenis_project') == 'Internal' ? 'selected' : '' }}>Internal</option>
                        <option value="Eksternal" {{ old('jenis_project') == 'Eksternal' ? 'selected' : '' }}>Eksternal</option>
                        <option value="Pemerintah" {{ old('jenis_project') == 'Pemerintah' ? 'selected' : '' }}>Pemerintah</option>
                        <option value="Swasta" {{ old('jenis_project') == 'Swasta' ? 'selected' : '' }}>Swasta</option>
                    </select>
                    @error('jenis_project')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tanggal_po">Tanggal PO</label>
                            <input type="date" class="form-control @error('tanggal_po') is-invalid @enderror"
                                   id="tanggal_po" name="tanggal_po" value="{{ old('tanggal_po') }}">
                            @error('tanggal_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="tanggal_kontrak">Tanggal Kontrak</label>
                            <input type="date" class="form-control @error('tanggal_kontrak') is-invalid @enderror"
                                   id="tanggal_kontrak" name="tanggal_kontrak" value="{{ old('tanggal_kontrak') }}">
                            @error('tanggal_kontrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_po">No. PO</label>
                            <input type="text" class="form-control @error('no_po') is-invalid @enderror"
                                   id="no_po" name="no_po" value="{{ old('no_po') }}">
                            @error('no_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_po">Nama PO</label>
                            <input type="text" class="form-control @error('nama_po') is-invalid @enderror"
                                   id="nama_po" name="nama_po" value="{{ old('nama_po') }}">
                            @error('nama_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="nilai_po">Nilai PO</label>
                    <input type="text" class="form-control money @error('nilai_po') is-invalid @enderror"
                           id="nilai_po" name="nilai_po" value="{{ old('nilai_po') }}">
                    @error('nilai_po')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="no_kontrak">No. Kontrak</label>
                            <input type="text" class="form-control @error('no_kontrak') is-invalid @enderror"
                                   id="no_kontrak" name="no_kontrak" value="{{ old('no_kontrak') }}">
                            @error('no_kontrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="nama_kontrak">Nama Kontrak</label>
                            <input type="text" class="form-control @error('nama_kontrak') is-invalid @enderror"
                                   id="nama_kontrak" name="nama_kontrak" value="{{ old('nama_kontrak') }}">
                            @error('nama_kontrak')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mb-3">
                    <label for="status">Status</label>
                    <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                        <option value="Active" {{ old('status', 'Active') == 'Active' ? 'selected' : '' }}>Active</option>
                        <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="Selesai" {{ old('status') == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</div>

@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
    $(document).ready(function(){
        $('.money').mask('000,000,000,000,000', { reverse: true });
        $('#user_id').select2();
    });
</script>
@endpush
@endsection