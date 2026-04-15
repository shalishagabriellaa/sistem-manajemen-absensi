@extends('templates.dashboard')
@section('isi')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

<div class="row">

    {{-- HEADER --}}
    <div class="col-md-12 mb-3">
        <div class="card shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h4 class="mb-0">{{ $title }}</h4>
                <a href="{{ url('/budgeting/tambah') }}" class="btn btn-success btn-sm">
                    <i class="bi bi-plus-lg"></i> Tambah Budget
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
                        <input type="date" name="mulai" class="form-control" value="{{ request('mulai') }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Tanggal Akhir</label>
                        <input type="date" name="akhir" class="form-control" value="{{ request('akhir') }}">
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-primary btn-sm">Filter</button>
                        <a href="{{ url('/budgeting') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ALERT --}}
    @if(session('success'))
    <div class="col-md-12">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
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
                                <th>Project</th>
                                <th>Event</th>
                                <th>Kategori</th>
                                <th>Total</th>
                                <th>Disetujui</th>
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

                            {{-- KOLOM PROJECT --}}
                            <td>
                                @if($b->project)
                                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle">
                                        {{ $b->project->nama_po }}
                                    </span>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td>{{ $b->event }}</td>
                            <td>{{ $b->kategori->name ?? '-' }}</td>

                            <td class="fw-semibold text-success">
                                Rp {{ number_format($b->total,0,',','.') }}
                            </td>

                            {{-- JUMLAH DISETUJUI --}}
                            <td>
                                @if($b->jumlah_disetujui !== null)
                                    <span class="fw-semibold text-primary">
                                        Rp {{ number_format($b->jumlah_disetujui,0,',','.') }}
                                    </span>
                                    @if($b->alasan)
                                        <br><small class="text-muted fst-italic">{{ $b->alasan }}</small>
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            <td class="fw-semibold text-danger">
                                Rp {{ number_format($b->sisa,0,',','.') }}
                            </td>

                            {{-- STATUS --}}
                            <td>
                                @if($b->status == 'Approved')
                                    <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Approved</span>
                                @elseif($b->status == 'Rejected')
                                    <span class="badge bg-danger"><i class="bi bi-x-circle me-1"></i>Rejected</span>
                                @else
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i>Pending</span>
                                @endif
                            </td>

                            {{-- FILE --}}
                            <td>
                                @if($b->file_path)
                                <a href="{{ asset('storage/'.$b->file_path) }}" target="_blank"
                                   class="btn btn-sm btn-outline-info" title="Lihat File">
                                   <i class="bi bi-paperclip"></i>
                                </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>

                            {{-- AKSI --}}
                            <td>
                                <div class="d-flex gap-1 flex-wrap">

                                    {{-- LIHAT --}}
                                    <a href="{{ url('/budgeting/show/'.$b->id) }}"
                                        class="btn btn-sm btn-outline-secondary"
                                        title="Lihat Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                    {{-- APPROVE --}}
                                    @if($b->status != 'Approved')
                                    <button type="button"
                                        class="btn btn-sm btn-outline-success btn-approve"
                                        title="Approve"
                                        data-id="{{ $b->id }}"
                                        data-total="{{ $b->total }}"
                                        data-event="{{ $b->event }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalApproval">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                    @endif

                                    {{-- REJECT --}}
                                    @if($b->status != 'Rejected')
                                    <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-reject"
                                        title="Reject"
                                        data-id="{{ $b->id }}"
                                        data-event="{{ $b->event }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalReject">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    @endif

                                    {{-- HAPUS --}}

                                </div>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">
                                <i class="bi bi-inbox fs-4 d-block mb-1"></i>
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


{{-- MODAL APPROVE --}}
<div class="modal fade" id="modalApproval" tabindex="-1" aria-labelledby="modalApprovalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="modalApprovalLabel">
                    <i class="bi bi-check-circle me-2"></i>Approval Budget
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formApproval" action="">
                @csrf
                <input type="hidden" name="status" value="Approved">
                <div class="modal-body">

                    <div class="alert alert-light border mb-3 p-2">
                        <small class="text-muted">Event</small>
                        <div class="fw-semibold" id="approveEventLabel">-</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Total Pengajuan</label>
                        <input type="text" class="form-control bg-light" id="approveTotalLabel" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            Jumlah Disetujui <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control money-modal" name="jumlah_disetujui"
                                   id="jumlahDisetujui" placeholder="0" required>
                        </div>
                        <div class="form-text text-muted">
                            Isi sesuai total jika disetujui penuh, atau kurang jika partial.
                        </div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold">Alasan / Catatan</label>
                        <textarea class="form-control" name="alasan" rows="3"
                                  placeholder="Misal: Disetujui sebagian karena anggaran terbatas..."></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="bi bi-check-lg me-1"></i>Konfirmasi Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- MODAL REJECT --}}
<div class="modal fade" id="modalReject" tabindex="-1" aria-labelledby="modalRejectLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="modalRejectLabel">
                    <i class="bi bi-x-circle me-2"></i>Reject Budget
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" id="formReject" action="">
                @csrf
                <input type="hidden" name="status" value="Rejected">
                <div class="modal-body">

                    <div class="alert alert-light border mb-3 p-2">
                        <small class="text-muted">Event</small>
                        <div class="fw-semibold" id="rejectEventLabel">-</div>
                    </div>

                    <div class="mb-1">
                        <label class="form-label fw-semibold">
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control" name="alasan" rows="3"
                                  placeholder="Tuliskan alasan penolakan..." required></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger btn-sm">
                        <i class="bi bi-x-lg me-1"></i>Konfirmasi Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
<script>
$(document).ready(function () {

    // Masking input jumlah di modal
    $('.money-modal').mask('000,000,000,000,000', { reverse: true });

    // Tombol Approve → isi modal
    $(document).on('click', '.btn-approve', function () {
        var id    = $(this).data('id');
        var total = $(this).data('total');
        var event = $(this).data('event');

        $('#formApproval').attr('action', '/budgeting/approval/' + id);
        $('#approveEventLabel').text(event);
        $('#approveTotalLabel').val('Rp ' + Number(total).toLocaleString('id-ID'));

        // Pre-fill jumlah disetujui dengan total penuh (bisa diubah user)
        var formatted = accounting.formatMoney(total, '', 0, ',', '.');
        $('#jumlahDisetujui').val(formatted);
        $('.money-modal').mask('000,000,000,000,000', { reverse: true });
    });

    // Tombol Reject → isi modal
    $(document).on('click', '.btn-reject', function () {
        var id    = $(this).data('id');
        var event = $(this).data('event');

        $('#formReject').attr('action', '/budgeting/approval/' + id);
        $('#rejectEventLabel').text(event);
        $('#formReject textarea[name="alasan"]').val('');
    });

    // Strip koma sebelum submit approval
    $('#formApproval').on('submit', function () {
        var raw = $('#jumlahDisetujui').val().replace(/,/g, '');
        $('#jumlahDisetujui').val(raw);
    });

});
</script>
@endpush

@endsection