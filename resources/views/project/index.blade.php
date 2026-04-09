@extends('templates.dashboard')
@section('isi')
<div class="row">

    {{-- Header --}}
    <div class="col-md-12 project-list">
        <div class="card">
            <div class="row align-items-center">
                <div class="col-md-6 mt-2 p-0 d-flex">
                    <h4>{{ $title }}</h4>
                </div>
                <div class="col-md-6 p-0 text-end">
                    <a href="{{ url('/project/tambah') }}" class="btn btn-primary btn-sm">+ Tambah Project</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="col-md-12">
        <div class="card">

            {{-- Filter --}}
            <div class="card-header">
                <form method="GET" action="{{ url('/project') }}">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Tanggal PO Dari</label>
                            <input type="date" name="mulai" class="form-control form-control-sm"
                                   value="{{ request('mulai') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small mb-1">Tanggal PO Sampai</label>
                            <input type="date" name="akhir" class="form-control form-control-sm"
                                   value="{{ request('akhir') }}">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-secondary btn-sm me-1">Filter</button>
                            <a href="{{ url('/project') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped table-hover align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>PIC</th>
                                <th>Jenis Project</th>
                                <th>No. PO</th>
                                <th>Nama PO</th>
                                <th>Nilai PO</th>
                                <th>No. Kontrak</th>
                                <th>Nama Kontrak</th>
                                <th>Tgl PO</th>
                                <th>Tgl Kontrak</th>
                                <th>Status</th>
                                <th>Reimb.</th>
                                <th>Budget</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($project as $key => $p)
                            <tr>
                                <td>{{ $project->firstItem() + $key }}</td>
                                <td>{{ $p->user->name ?? '-' }}</td>
                                <td>{{ $p->jenis_project }}</td>
                                <td>{{ $p->no_po }}</td>
                                <td>{{ $p->nama_po }}</td>
                                <td>Rp {{ number_format($p->nilai_po, 0, ',', '.') }}</td>
                                <td>{{ $p->no_kontrak }}</td>
                                <td>{{ $p->nama_kontrak }}</td>
                                <td>{{ $p->tanggal_po }}</td>
                                <td>{{ $p->tanggal_kontrak }}</td>
                                <td>
                                    <span class="badge bg-{{ $p->status == 'Active' ? 'success' : 'secondary' }}">
                                        {{ $p->status }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $p->reimbursements->count() }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $p->budgetings->count() }}</span>
                                </td>
                                <td>
                                    <a href="{{ url('/project/show/'.$p->id) }}" class="btn btn-sm btn-info" title="Detail"
                                    data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="{{ url('/project/edit/'.$p->id) }}" class="btn btn-sm btn-warning" title="Edit"
                                    data-bs-toggle="tooltip" data-bs-placement="top">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    <a href="{{ url('/project/delete/'.$p->id) }}" class="btn btn-sm btn-danger" title="Hapus"
                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                    onclick="return confirm('Yakin hapus project ini?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="14" class="text-center text-muted py-4">Tidak ada data project</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    {{ $project->links() }}
                </div>

            </div>
        </div>
    </div>

</div>

@push('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipEls = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipEls.forEach(function (el) {
            new bootstrap.Tooltip(el);
        });
    });
</script>
@endpush

@endsection