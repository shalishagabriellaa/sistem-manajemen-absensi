@extends('templates.app')
@section('container')

    {{-- Flash Messages --}}
    <div class="tf-container mt-2">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-times-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>

    {{-- Search --}}
    <div class="tf-container mt-3">
        <form action="{{ url('/inventory') }}">
            <div style="display: flex; gap: 8px; align-items: center; width: 100%;">
                <input type="text" name="search"
                    placeholder="Cari kode, nama barang..."
                    value="{{ request('search') }}"
                    style="width: calc(100% - 60px); padding: 8px 12px; border: 1px solid #ccc; border-radius: 8px; font-size: 14px; outline: none;">
                <button type="submit"
                        style="width: 48px; height: 38px; background: #0d6efd; border: none; border-radius: 8px; color: white; flex-shrink: 0; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
    </div>

    {{-- Action Buttons: 2x2 grid --}}
    <div class="tf-container mt-3">
        <div class="row g-2">
            <div class="col-6">
                <a href="{{ url('/inventory/download-template') }}"
                   class="btn btn-success btn-sm w-100">
                    <i class="fas fa-download me-1"></i> Template
                </a>
            </div>
            <div class="col-6">
                <button class="btn btn-warning btn-sm w-100"
                        data-bs-toggle="modal" data-bs-target="#modalImport">
                    <i class="fas fa-file-excel me-1"></i> Import
                </button>
            </div>
            <div class="col-6">
                <a href="{{ url('/inventory/export') }}"
                   class="btn btn-success btn-sm w-100">
                    <i class="fas fa-file-export me-1"></i> Export
                </a>
            </div>
            <div class="col-6">
                <a href="{{ url('/inventory/tambah') }}"
                   class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-plus me-1"></i> Tambah
                </a>
            </div>
        </div>
    </div>

    <div class="tf-spacing-20"></div>

    {{-- Table --}}
    <div class="tf-container mt-3">
        <div style="overflow-x: auto; -webkit-overflow-scrolling: touch;">
            <table class="table table-striped table-bordered align-middle" style="min-width: 900px;">
                <thead class="table-dark">
                    <tr>
                        <th class="text-center">No.</th>
                        <th style="min-width: 150px;">Kode Barang</th>
                        <th style="min-width: 150px;">Jenis Barang</th>
                        <th style="min-width: 150px;">Merek</th>
                        <th style="min-width: 180px;">Nama Barang</th>
                        <th class="text-center" style="min-width: 70px;">Stok</th>
                        <th class="text-center" style="min-width: 70px;">UoM</th>
                        <th style="min-width: 200px;">Description</th>
                        <th style="min-width: 120px;">Lokasi</th>
                        <th style="min-width: 160px;">Divisi / Jabatan</th>
                        <th class="text-center" style="min-width: 80px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($inventories as $key => $inventory)
                        <tr>
                            <td class="text-center">
                                {{ ($inventories->currentPage() - 1) * $inventories->perPage() + $key + 1 }}
                            </td>
                            <td>{{ $inventory->kode_barang ?? '-' }}</td>
                            <td>{{ $inventory->jenis_barang ?? '-' }}</td>
                            <td>{{ $inventory->merek ?? '-' }}</td>
                            <td>{{ $inventory->nama_barang ?? '-' }}</td>
                            <td class="text-center">{{ $inventory->stok ?? '-' }}</td>
                            <td class="text-center">{{ $inventory->uom ?? '-' }}</td>
                            <td>{!! $inventory->desc ? nl2br(e($inventory->desc)) : '-' !!}</td>
                            <td>{{ $inventory->lokasi->nama_lokasi ?? '-' }}</td>
                            <td>{{ $inventory->jabatan->nama_jabatan ?? '-' }}</td>
                            <td class="text-center">
                                <a href="{{ url('/inventory/edit/'.$inventory->id) }}"
                                class="btn btn-sm btn-warning">
                                    <i class="fa fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-4">
                                <i class="fas fa-box-open me-1"></i> Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-end mt-3 mb-3">
            {{ $inventories->links() }}
        </div>
    </div>

    {{-- Modal Import --}}
    <div class="modal fade" id="modalImport" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ url('/inventory/import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-file-excel text-success"></i> Import Data Inventory
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-info small">
                            <strong><i class="fas fa-info-circle"></i> Petunjuk:</strong>
                            <ol class="mb-0 mt-1">
                                <li>Download template, isi mulai dari <strong>baris ke-4</strong>.</li>
                                <li>Kolom <strong>Kode Barang</strong> boleh dikosongkan, akan di-generate otomatis.</li>
                                <li>Kolom <strong>Lokasi</strong> dan <strong>Divisi / Jabatan</strong> harus sama persis dengan data di sistem.</li>
                                <li>Format file: <strong>.xlsx</strong> atau <strong>.xls</strong>, maksimal 5MB.</li>
                            </ol>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Pilih File Excel</label>
                            <input type="file"
                                   class="form-control @error('file') is-invalid @enderror"
                                   id="fileUser" name="file" accept=".xlsx,.xls">
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="fileInfoUser" class="text-muted mt-1 small d-none">
                                <i class="fas fa-file-excel text-success"></i>
                                <span id="fileNameUser"></span>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ url('/inventory/download-template') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-download"></i> Download Template
                        </a>
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary btn-sm" id="btnImportUser">
                            <i class="fas fa-upload"></i> Upload & Import
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <br><br>

    @push('scripts')
    <script>
        document.getElementById('fileUser').addEventListener('change', function () {
            const fileInfo = document.getElementById('fileInfoUser');
            const fileName = document.getElementById('fileNameUser');
            if (this.files.length > 0) {
                fileName.textContent = this.files[0].name;
                fileInfo.classList.remove('d-none');
            } else {
                fileInfo.classList.add('d-none');
            }
        });

        document.querySelector('#modalImport form').addEventListener('submit', function () {
            const btn = document.getElementById('btnImportUser');
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengimport...';
        });
    </script>
    @endpush
@endsection