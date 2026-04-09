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
                    <a href="{{ url('/project/tambah') }}" class="btn btn-primary btn-sm">+ Tambah Project</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card p-3">
            <form method="GET" action="{{ url('/project') }}" class="row g-2 mb-3">
                <div class="col-md-3">
                    <label class="form-label small">Tanggal PO Dari</label>
                    <input type="date" name="mulai" class="form-control form-control-sm" value="{{ request('mulai') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label small">Tanggal PO Sampai</label>
                    <input type="date" name="akhir" class="form-control form-control-sm" value="{{ request('akhir') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-secondary btn-sm me-1">Filter</button>
                    <a href="{{ url('/project') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                </div>
            </form>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
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
                            <th>Reimbursement</th>
                            <th>Budgeting</th>
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
                                <a href="{{ url('/project/show/'.$p->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ url('/project/edit/'.$p->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <a href="{{ url('/project/delete/'.$p->id) }}" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin hapus project ini?')">Hapus</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="14" class="text-center">Tidak ada data project</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $project->links() }}
        </div>
    </div>
</div>
@endsection