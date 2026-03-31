@extends('templates.dashboard')
@section('isi')
    <div class="row">
        <div class="col-md-12 project-list">
            <div class="card">
                <div class="row">
                    <div class="col-md-6 mt-2 p-0 d-flex">
                        <h4>{{ $title }}</h4>
                    </div>
                    <div class="col-md-6 p-0">
                        <a class="btn btn-primary btn-sm" href="{{ url('/data-cuti/tambah') }}">+ Tambah</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row gy-2 align-items-center">
                        <div class="col-md-8">
                    <form action="{{ url('/data-cuti') }}">
                            <div class="row">
                                <div class="col-3">
                                    <select name="user_id" id="user_id" class="form-control selectpicker" data-live-search="true">
                                        <option value=""selected>Pilih Pegawai</option>
                                        @foreach($users as $u)
                                            @if(request('user_id') == $u->id)
                                                <option value="{{ $u->id }}"selected>{{ $u->name }}</option>
                                            @else
                                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-3">
                                    <input type="datetime" class="form-control" name="mulai" placeholder="Tanggal Mulai" id="mulai" value="{{ request('mulai') }}">
                                </div>
                                <div class="col-3">
                                    <input type="datetime" class="form-control" name="akhir" placeholder="Tanggal Akhir" id="akhir" value="{{ request('akhir') }}">
                                </div>
                                <div class="col-3">
                                    <button type="submit" id="search"class="border-0 mt-3" style="background-color: transparent;"><i class="fas fa-search"></i></button>
                                </div>
                            </div>
                    </form>
                        </div>
                        <div class="col-md-4 text-end">
                            <button type="button" class="btn btn-primary btn-sm" id="bulk-approve-btn" disabled>Ubah Status Terpilih</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mytable" class="table table-striped">
                            <thead>
                                <tr>
                                        <th>
                                            <input type="checkbox" id="select-all">
                                        </th>
                                    <th>No.</th>
                                    <th>Nama Pegawai</th>
                                    <th>Lokasi Pegawai</th>
                                    <th>Nama Cuti</th>
                                    <th>Tanggal</th>
                                    <th>Alasan Cuti</th>
                                    <th>Foto Cuti</th>
                                    <th>Status Cuti</th>
                                    <th>User Approval</th>
                                    <th>Catatan</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_cuti as $key => $dc)
                                <tr>
                                        <td><input type="checkbox" class="row-checkbox" value="{{ $dc->id }}" @if($dc->status_cuti === 'Diterima') disabled @endif></td>
                                    <td>{{ ($data_cuti->currentpage() - 1) * $data_cuti->perpage() + $key + 1 }}.</td>
                                    <td>{{ $dc->User->name ?? '-' }}</td>
                                    <td>{{ $dc->lokasi->nama_lokasi ?? '-' }}</td>
                                    <td>{{ $dc->nama_cuti ?? '-' }}</td>
                                    <td>{{ $dc->tanggal ?? '-' }}</td>
                                    <td>{{ $dc->alasan_cuti ?? '-' }}</td>
                                    <td>
                                        @if ($dc->foto_cuti)
                                            <img src="{{ url('storage/'.$dc->foto_cuti) }}" style="width: 70px" alt="">
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if($dc->status_cuti == "Diterima")
                                            <span class="badge badge-success">{{ $dc->status_cuti ?? '-' }}</span>
                                        @elseif($dc->status_cuti == "Ditolak")
                                            <span class="badge badge-danger">{{ $dc->status_cuti ?? '-' }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $dc->status_cuti ?? '-' }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $dc->ua->name ?? '-' }}</td>
                                    <td>{{ $dc->catatan ?? '-' }}</td>
                                    <td>
                                        <ul class="action">
                                            @if($dc->status_cuti == "Diterima")
                                                <li class="me-2">
                                                    <span class="badge badge-success">Sudah Approve</span>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="{{ url('/data-cuti/edit/'.$dc->id) }}"><i style="color: blue" class="fas fa-edit"></i></a>
                                                </li>

                                                <li class="delete">
                                                    <form action="{{ url('/data-cuti/delete/'.$dc->id) }}" method="post" class="d-inline">
                                                        @method('delete')
                                                        @csrf
                                                        <button class="border-0" style="background-color: transparent" onClick="return confirm('Are You Sure')"><i class="fas fa-trash"></i></button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mr-4">
                        {{ $data_cuti->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bulk Approval -->
    <div class="modal fade" id="bulkApprovalModal" tabindex="-1" aria-labelledby="bulkApprovalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkApprovalModalLabel">Ubah Status Cuti Terpilih</h5>
                    <button type="button" class="close bulk-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bulk-approval-form" method="post" action="{{ url('/data-cuti/bulk-update') }}">
                    @csrf
                    <div class="selected-ids-container"></div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bulk_status">Status</label>
                            <select name="status_cuti" id="bulk_status" class="form-control selectpicker" data-live-search="true" required>
                                <option value="">Pilih Status</option>
                                <option value="Diterima">Terima</option>
                                <option value="Ditolak">Tolak</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bulk_catatan" class="col-form-label">Catatan:</label>
                            <textarea class="form-control" id="bulk_catatan" name="catatan" rows="3" placeholder="Catatan (opsional)"></textarea>
                        </div>
                        <small class="text-muted">Perubahan akan diterapkan ke semua data yang dipilih.</small>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary bulk-modal-close" data-dismiss="modal" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@push('style')
<style>
    /* Hilangkan ikon panah sorting di kolom checkbox agar mudah diklik */
    #mytable thead th:first-child {
        width: 50px;
        position: relative;
        text-align: center;
    }
    #mytable thead th:first-child:before,
    #mytable thead th:first-child:after {
        display: none !important;
    }

    /* Pastikan checkbox dapat diklik */
    #mytable tbody td:first-child {
        text-align: center;
        vertical-align: middle;
    }

    #mytable tbody td:first-child input[type="checkbox"] {
        margin: 0;
        cursor: pointer;
        position: relative;
        z-index: 10;
    }

    /* Pastikan header checkbox juga dapat diklik */
    #mytable thead th:first-child input[type="checkbox"] {
        cursor: pointer;
        position: relative;
        z-index: 10;
        display: block;
        margin: 0 auto;
    }
</style>
@endpush

    @push('script')
        <script>
function collectSelectedIds() {
    var ids = [];
    $('.row-checkbox:checked:not(:disabled)').each(function () {
        var val = $(this).val();
        if (val) {
            ids.push(val);
        }
    });
    return ids;
}

function syncBulkButton() {
    var ids = collectSelectedIds();
    var hasSelection = ids.length > 0;
    var button = $('#bulk-approve-btn');

    if (hasSelection) {
        button.prop('disabled', false);
        button.removeAttr('disabled');
        button.removeClass('disabled');
        button.css({
            'cursor': 'pointer',
            'opacity': '1',
            'pointer-events': 'auto'
        });
    } else {
        button.prop('disabled', true);
        button.attr('disabled', 'disabled');
        button.addClass('disabled');
        button.css({
            'cursor': 'not-allowed',
            'opacity': '0.6',
            'pointer-events': 'none'
        });
    }
}

function fillSelectedIds(containerSelector, ids) {
    var container = $(containerSelector);
    container.empty();
    ids.forEach(function (id) {
        container.append('<input type="hidden" name="selected_cuti[]" value="'+id+'">');
    });
}

            $(document).ready(function() {
                $('#mulai').change(function(){
                    var mulai = $(this).val();
                $('#akhir').val(mulai);
                });

    // Select all checkbox handler
    $('#select-all').on('change', function () {
        var isChecked = $(this).is(':checked');
        $('.row-checkbox:not(:disabled)').prop('checked', isChecked).trigger('change');
        syncBulkButton();
    });

    // Individual checkbox handler - use direct binding and event delegation
    $('body').on('change', '.row-checkbox', function () {
        var totalCheckboxes = $('.row-checkbox:not(:disabled)').length;
        var checkedCheckboxes = $('.row-checkbox:checked:not(:disabled)').length;
        $('#select-all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
        syncBulkButton();
    });

    // Bulk approval button handler - open modal
    $('#bulk-approve-btn').on('click', function (e) {
        e.preventDefault();

        // Check if button is disabled
        if ($(this).prop('disabled')) {
            return false;
        }

        var ids = collectSelectedIds();
        if (ids.length === 0) {
            alert('Pilih data cuti yang akan diubah.');
            return false;
        }

        // Fill selected IDs into modal
        fillSelectedIds('.selected-ids-container', ids);

        // Reset form
        $('#bulk_status').val('').change();
        $('#bulk_catatan').val('');

        // Show modal
        $('#bulkApprovalModal').modal('show');

        // Refresh selectpicker after modal is shown
        $('#bulkApprovalModal').on('shown.bs.modal', function () {
            if ($('#bulk_status').hasClass('selectpicker')) {
                $('#bulk_status').selectpicker('refresh');
            }
        });
    });

    // Close modal handlers
    function hideBulkModal() {
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            var modalElement = document.getElementById('bulkApprovalModal');
            var modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            } else {
                var newModalInstance = new bootstrap.Modal(modalElement);
                newModalInstance.hide();
            }
        } else {
            $('#bulkApprovalModal').modal('hide');
        }
    }

    $('.bulk-modal-close').on('click', function() {
        hideBulkModal();
    });

    // Initialize button state immediately and after a delay
    syncBulkButton();
    setTimeout(function() {
        syncBulkButton();
    }, 500);

    // Also sync on any checkbox click (as backup)
    $('body').on('click', '.row-checkbox, #select_all', function() {
        setTimeout(function() {
            syncBulkButton();
        }, 50);
    });
            });
        </script>
    @endpush
@endsection
