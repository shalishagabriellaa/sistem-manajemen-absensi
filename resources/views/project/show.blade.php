@extends('templates.dashboard')
@section('isi')
<div class="row">
    <div class="col-md-12">
        <div class="card p-3 mb-3">
            <div class="row">
                <div class="col-md-6 d-flex align-items-center">
                    <h4 class="mb-0">{{ $title }}</h4>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ url('/project') }}" class="btn btn-danger btn-sm">Back</a>
                </div>
            </div>
        </div>
    </div>

    {{-- Info Project --}}
    <div class="col-md-12">
        <div class="card p-4 mb-3">
            <h5 class="mb-3">Informasi Project</h5>
            <div class="row">
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><th width="160">PIC</th><td>: {{ $project->user->name ?? '-' }}</td></tr>
                        <tr><th>Jenis Project</th><td>: {{ $project->jenis_project }}</td></tr>
                        <tr><th>Status</th><td>:
                            <span class="badge bg-{{ $project->status == 'Active' ? 'success' : ($project->status == 'Selesai' ? 'secondary' : 'danger') }}">
                                {{ $project->status }}
                            </span>
                        </td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-borderless">
                        <tr><th width="160">No. PO</th><td>: {{ $project->no_po }}</td></tr>
                        <tr><th>Nama PO</th><td>: {{ $project->nama_po }}</td></tr>
                        <tr><th>Tanggal PO</th><td>: {{ $project->tanggal_po }}</td></tr>
                        <tr><th>Nilai PO</th><td>: Rp {{ number_format($project->nilai_po, 0, ',', '.') }}</td></tr>
                        <tr><th>No. Kontrak</th><td>: {{ $project->no_kontrak }}</td></tr>
                        <tr><th>Nama Kontrak</th><td>: {{ $project->nama_kontrak }}</td></tr>
                        <tr><th>Tanggal Kontrak</th><td>: {{ $project->tanggal_kontrak }}</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Reimbursement terkait --}}
    <div class="col-md-12">
        <div class="card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Reimbursement Terkait</h5>
                <a href="{{ url('/reimbursement/tambah?project_id='.$project->id) }}" class="btn btn-sm btn-primary">+ Tambah</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Event</th>
                            <th>Kategori</th>
                            <th>Total</th>
                            <th>Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->reimbursements as $key => $r)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $r->tanggal }}</td>
                            <td>{{ $r->user->name ?? '-' }}</td>
                            <td>{{ $r->event }}</td>
                            <td>{{ $r->kategori->name ?? '-' }}</td>
                            <td>Rp {{ number_format($r->total, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($r->sisa, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $r->status == 'Approved' ? 'success' : ($r->status == 'Rejected' ? 'danger' : 'warning') }}">
                                    {{ $r->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">Belum ada reimbursement</td></tr>
                        @endforelse
                    </tbody>
                    @if($project->reimbursements->count() > 0)
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end">Total Reimbursement:</td>
                            <td>Rp {{ number_format($project->reimbursements->sum('total'), 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>

    {{-- Budgeting terkait --}}
    <div class="col-md-12">
        <div class="card p-4 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Budgeting Terkait</h5>
                <a href="{{ url('/budgeting/tambah?project_id='.$project->id) }}" class="btn btn-sm btn-primary">+ Tambah</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Event</th>
                            <th>Kategori</th>
                            <th>Total</th>
                            <th>Sisa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($project->budgetings as $key => $b)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $b->tanggal }}</td>
                            <td>{{ $b->user->name ?? '-' }}</td>
                            <td>{{ $b->event }}</td>
                            <td>{{ $b->kategori->name ?? '-' }}</td>
                            <td>Rp {{ number_format($b->total, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($b->sisa, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge bg-{{ $b->status == 'Approved' ? 'success' : ($b->status == 'Rejected' ? 'danger' : 'warning') }}">
                                    {{ $b->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="text-center">Belum ada budgeting</td></tr>
                        @endforelse
                    </tbody>
                    @if($project->budgetings->count() > 0)
                    <tfoot>
                        <tr class="fw-bold">
                            <td colspan="5" class="text-end">Total Budgeting:</td>
                            <td>Rp {{ number_format($project->budgetings->sum('total'), 0, ',', '.') }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection