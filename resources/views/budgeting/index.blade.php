@extends('templates.dashboard')
@section('isi')

<div class="row">

    {{-- HEADER --}}<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <div class="col-md-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $title }}</h4>
                <a href="{{ url('/budgeting/tambah') }}" class="btn btn-success btn-sm">
                    + Tambah Budget
                </a>
            </div>
        </div>
    </div>


    {{-- FILTER --}}
    <div class="col-md-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ url('/budgeting') }}" class="row g-2 align-items-end">

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date" name="mulai" class="form-control"
                            value="{{ request('mulai') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="akhir" class="form-control"
                            value="{{ request('akhir') }}">
                    </div>

                    <div class="col-md-4">
                        <button class="btn btn-primary btn-sm">
                            Filter
                        </button>

                        <a href="{{ url('/budgeting') }}" class="btn btn-outline-secondary btn-sm">
                            Reset
                        </a>
                    </div>

                </form>
            </div>
        </div>
    </div>


    {{-- ALERT --}}
    @if(session('success'))
    <div class="col-md-12">
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    </div>
    @endif


    {{-- TABLE --}}
    <div class="col-md-12">
        <div class="card shadow-sm">
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-hover align-middle">

                        <thead class="table-light">
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
                                <th width="230">Aksi</th>
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

                            <td class="fw-semibold text-success">
                                Rp {{ number_format($b->total,0,',','.') }}
                            </td>

                            <td class="fw-semibold text-danger">
                                Rp {{ number_format($b->sisa,0,',','.') }}
                            </td>


                            {{-- STATUS --}}
                            <td>
                                @if($b->status == 'Approved')
                                    <span class="badge bg-success">Approved</span>
                                @elseif($b->status == 'Rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark">Pending</span>
                                @endif
                            </td>


                            {{-- FILE --}}
                            <td>
                                @if($b->file_path)
                                <a href="{{ asset('storage/'.$b->file_path) }}"
                                   target="_blank"
                                   class="btn btn-sm btn-outline-info">
                                   Lihat
                                </a>
                                @endif
                            </td>


                            {{-- AKSI --}}
                            <td>

                                <div class="d-flex gap-1 flex-wrap">

<a href="{{ url('/budgeting/edit/'.$b->id) }}"
   class="btn btn-sm btn-outline-warning"
   title="Edit Data">
   <i class="bi bi-pencil"></i>
</a>

<a href="{{ url('/budgeting/delete/'.$b->id) }}"
   class="btn btn-sm btn-outline-danger"
   onclick="return confirm('Yakin hapus?')"
   title="Hapus Data">
   <i class="bi bi-trash"></i>
</a>

                                </div>

                            </td>

                        </tr>

                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">
                                Tidak ada data
                            </td>
                        </tr>
                        @endforelse

                        </tbody>

                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="mt-3">
                    {{ $budgeting->links() }}
                </div>

            </div>
        </div>
    </div>

</div>

@endsection