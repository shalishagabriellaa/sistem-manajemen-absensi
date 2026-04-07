@extends('templates.dashboard')
@section('isi')
    <div class="row">
        <div class="col-12 project-list">
    <div class="card">
        <div class="card-body">

            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

                <!-- Title -->
                <h4 class="mb-0 fw-semibold">
                    {{ $title }}
                </h4>

                <!-- Buttons -->
                <div class="d-flex flex-wrap gap-2">

                    <a href="{{ url('/inventory/download-template') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-download me-1"></i> Template
                    </a>

                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalImport">
                        <i class="fas fa-file-excel me-1"></i> Import
                    </button>

                    <a href="{{ url('/inventory/export') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-export me-1"></i> Export
                    </a>

                    <a href="{{ url('/inventory/tambah') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Tambah
                    </a>

                </div>

            </div>

        </div>
    </div>
</div>
        </div>

        <div class="col-md-12">
            <div class="card">

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mx-3 mt-3" role="alert">
                        <i class="fas fa-check-circle"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show mx-3 mt-3" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mx-3 mt-3" role="alert">
                        <i class="fas fa-times-circle"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="card-header">
                    <form action="{{ url('/inventory') }}">
                        <div class="row mb-2">
                            <div class="col-6">
                                <input type="text" class="form-control" name="search" placeholder="Search..." value="{{ request('search') }}">
                            </div>
                            <div class="col-3">
                                <button type="submit" class="border-0 mt-3" style="background-color: transparent;">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center">No.</th>
                                    <th style="min-width: 180px;" class="text-center">Kode Barang</th>
                                    <th style="min-width: 180px;" class="text-center">Jenis Barang</th>
                                    <th style="min-width: 180px;" class="text-center">Merek</th>
                                    <th style="min-width: 250px;" class="text-center">Nama Barang</th>
                                    <th style="min-width: 100px;" class="text-center">Stok</th>
                                    <th style="min-width: 100px;" class="text-center">UoM</th>
                                    <th style="min-width: 400px;" class="text-center">Description</th>
                                    <th style="min-width: 200px;" class="text-center">Lokasi</th>
                                    <th style="min-width: 200px;" class="text-center">Divisi / Jabatan</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($inventories) <= 0)
                                    <tr>
                                        <td colspan="11" class="text-center">Tidak Ada Data</td>
                                    </tr>
                                @else
                                    @foreach ($inventories as $key => $inventory)
                                        <tr>
                                            <td class="text-center">{{ ($inventories->currentpage() - 1) * $inventories->perpage() + $key + 1 }}.</td>
                                            <td class="text-center">{{ $inventory->kode_barang ?? '-' }}</td>
                                            <td class="text-center">{{ $inventory->jenis_barang ?? '-' }}</td>
                                            <td class="text-center">{{ $inventory->merek ?? '-' }}</td>
                                            <td class="text-center">{{ $inventory->nama_barang ?? '-' }}</td>
                                            <td class="text-center">{{ $inventory->stok ?? '-' }}</td>
                                            <td class="text-center">{{ $inventory->uom ?? '-' }}</td>
                                            <td>{!! $inventory->desc ? nl2br(e($inventory->desc)) : '-' !!}</td>
                                            <td class="text-center">{{ $inventory->lokasi->nama_lokasi ?? '-' }}</td>
                                            <td class="text-center">{{ $inventory->jabatan->nama_jabatan ?? '-' }}</td>
                                            <td class="text-center">
                                                <a href="{{ url('/inventory/edit/'.$inventory->id) }}">
                                                    <i class="fa fa-solid fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $inventories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Import Excel --}}
    <div class="modal fade" id="modalImport" tabindex="-1" aria-labelledby="modalImportLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('/inventory/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalImportLabel">
                            <i class="fas fa-file-excel text-success"></i> Import Data Inventory dari Excel
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <strong><i class="fas fa-info-circle"></i> Petunjuk pengisian template:</strong>
                            <ol class="mb-0 mt-1">
                                <li>Download template, isi mulai dari <strong>baris ke-4</strong> (baris 3 = header).</li>
                                <li>Kolom <strong>Kode Barang</strong> boleh dikosongkan, akan di-generate otomatis.</li>
                                <li>Kolom <strong>Lokasi</strong> dan <strong>Divisi / Jabatan</strong> harus sama persis dengan data di sistem.</li>
                                <li>Format file: <strong>.xlsx</strong> atau <strong>.xls</strong>, maksimal 5MB.</li>
                            </ol>
                        </div>

                        {{-- Preview kolom template --}}
                        <div class="mb-3 p-2 border rounded bg-light small">
                            <strong>Kolom template:</strong>
                            <span class="badge bg-secondary me-1">Kode Barang</span>
                            <span class="badge bg-secondary me-1">Jenis Barang</span>
                            <span class="badge bg-secondary me-1">Merek</span>
                            <span class="badge bg-secondary me-1">Nama Barang</span>
                            <span class="badge bg-secondary me-1">Stok</span>
                            <span class="badge bg-secondary me-1">UoM</span>
                            <span class="badge bg-secondary me-1">Description</span>
                            <span class="badge bg-secondary me-1">Lokasi</span>
                            <span class="badge bg-secondary me-1">Divisi / Jabatan</span>
                        </div>

                        <div class="mb-3">
                            <label for="file" class="form-label fw-bold">Pilih File Excel</label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="file" name="file" accept=".xlsx,.xls">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="fileInfo" class="text-muted mt-1 small d-none">
                                <i class="fas fa-file-excel text-success"></i> <span id="fileName"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('/inventory/download-template') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btnImport">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br>

    @push('scripts')
    <script>
        document.getElementById('file').addEventListener('change', function () {
            const fileInfo = document.getElementById('fileInfo');
            const fileName = document.getElementById('fileName');
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileInfo.classList.remove('d-none');
            } else {
                fileInfo.classList.add('d-none');
            }
        });

        document.querySelector('#modalImport form').addEventListener('submit', function () {
            const btn = document.getElementById('btnImport');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengimport...';
        });
    </script>
    @endpush
@endsection
