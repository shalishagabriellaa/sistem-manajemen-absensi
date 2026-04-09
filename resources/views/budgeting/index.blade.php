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
                    <a href="{{ url('/budgeting/tambah') }}" class="btn btn-primary btn-sm">+ Tambah</a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card p-3">
            <form method="GET" action="{{ url('/budgeting') }}" class="row g-2 mb-3">
                <div class="col-md-4">
                    <input type="date" name="mulai" class="form-control" value="{{ request('mulai') }}">
                </div>
                <div class="col-md-4">
                    <input type="date" name="akhir" class="form-control" value="{{ request('akhir') }}">
                </div>
                <div class="col-md-4">
                    <button class="btn btn-secondary btn-sm">Filter</button>
                    <a href="{{ url('/budgeting') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
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
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Event</th>
                            <th>Kategori</th>
                            <th>Total</th>
                            <th>Sisa</th>
                            <th>Status</th>
                            <th>File</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($budgeting as $key => $b)
                        <tr>
                            <td>{{ $budgeting->firstItem() + $key }}</td>
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
                            <td>
                                @if($b->file_path)
                                    <a href="{{ asset('storage/'.$b->file_path) }}" target="_blank" class="btn btn-sm btn-info">Lihat</a>
                                @endif
                            </td>
                            <td>
                                <a href="{{ url('/budgeting/edit/'.$b->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form method="POST" action="{{ url('/budgeting/approval/'.$b->id) }}" class="d-inline">
                                    @csrf
                                    <select name="status" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()">
                                        <option value="Pending" {{ $b->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Approved" {{ $b->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="Rejected" {{ $b->status == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                </form>
                                <a href="{{ url('/budgeting/delete/'.$b->id) }}" class="btn btn-sm btn-danger"
                                   onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="10" class="text-center">Tidak ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $budgeting->links() }}
        </div>
    </div>
</div>
@endsection