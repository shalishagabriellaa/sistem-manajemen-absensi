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
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row gy-2 align-items-center">
                        <div class="col-md-8">
                    <form action="{{ url('/data-lembur') }}">
                        <div class="row">
                            <div class="col-3">
                                <select name="user_id" id="user_id" class="form-control selectpicker" data-live-search="true">
                                    <option value=""selected>Pilih Pegawai</option>
                                    @foreach($user as $u)
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
                            <button type="button" class="btn btn-primary btn-sm" id="bulk-approval-button" disabled>Ubah Status Terpilih</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="mytable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select_all"></th>
                                    <th>No.</th>
                                    <th>Nama Pegawai</th>
                                    <th>Tanggal</th>
                                    <th>Jam Masuk</th>
                                    <th>Lokasi Masuk</th>
                                    <th>Foto Masuk</th>
                                    <th>Jam Pulang</th>
                                    <th>Lokasi Pulang</th>
                                    <th>Foto Pulang</th>
                                    <th>Total Lembur</th>
                                    <th>Notes</th>
                                    <th>User Approval</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data_lembur as $key => $dl)
                                <tr>
                                    <td><input type="checkbox" class="row-checkbox" value="{{ $dl->id }}" @if($dl->status !== 'Pending' || $dl->jam_keluar === null) disabled @endif></td>
                                    <td>{{ ($data_lembur->currentpage() - 1) * $data_lembur->perpage() + $key + 1 }}.</td>
                                    <td>{{ $dl->User->name }}</td>
                                    <td>{{ $dl->tanggal }}</td>
                                    <td>
                                        @php
                                            $jam_masuk = explode(" ", $dl->jam_masuk);
                                        @endphp
                                        <span class="badge badge-success">{{ $jam_masuk[1] }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $jarak_masuk = explode(".", $dl->jarak_masuk);
                                        @endphp
                                        <a href="{{ url('/maps/'.$dl->lat_masuk.'/'.$dl->long_masuk.'/'.$dl->user_id) }}" class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                                        <span class="badge badge-warning">{{ $jarak_masuk[0] }} Meter</span>
                                    </td>
                                    <td>
                                        <img src="{{ url('storage/' . $dl->foto_jam_masuk) }}" style="width: 60px">
                                    </td>
                                    <td>
                                        @if ($dl->jam_keluar == null)
                                            <span class="badge badge-warning">Belum Pulang Lembur</span>
                                        @else
                                            @php
                                                $jam_keluar = explode(" ", $dl->jam_keluar);
                                            @endphp
                                            <span class="badge badge-success">{{ $jam_keluar[1] }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dl->jam_keluar == null)
                                            <span class="badge badge-warning">Belum Pulang Lembur</span>
                                        @else
                                            @php
                                                $jarak_keluar = explode(".", $dl->jarak_keluar);
                                            @endphp
                                            <a href="{{ url('/maps/'.$dl->lat_keluar.'/'.$dl->long_keluar.'/'.$dl->user_id) }}" class="btn btn-sm btn-secondary" target="_blank">lihat</a>
                                            <span class="badge badge-warning">{{ $jarak_keluar[0] }} Meter</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($dl->jam_keluar == null)
                                            <span class="badge badge-warning">Belum Pulang Lembur</span>
                                        @else
                                            <img src="{{ url('storage/' . $dl->foto_jam_keluar) }}" style="width: 60px">
                                        @endif
                                    </td>
                                    <td>
                                        @if($dl->jam_keluar == null)
                                            <span class="badge badge-warning">Belum Pulang Lembur</span>
                                        @else
                                            @php
                                                $total_lembur = $dl->total_lembur;
                                                $jam   = floor($total_lembur / (60 * 60));
                                                $menit = $total_lembur - ( $jam * (60 * 60) );
                                                $menit2 = floor( $menit / 60 );
                                            @endphp
                                            <span class="badge badge-success">{{ $jam." Jam ".$menit2." Menit" }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $dl->notes }}</td>
                                    <td>{{ $dl->approvedBy ? $dl->approvedBy->name : '' }}</td>
                                    <td>
                                        @if($dl->status == 'Pending')
                                            <span class="badge badge-warning">{{ $dl->status }}</span>
                                        @elseif($dl->status == 'Rejected')
                                            <span class="badge badge-danger">{{ $dl->status }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $dl->status }}</span>
                                        @endif
                                        <br>
                                        <small class="text-muted">
                                            Updated: {{ $dl->updated_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        @if ($dl->status !== 'Approved' && $dl->jam_keluar !== null)
                                            <ul class="action">
                                                <li>
                                                    <button class="border-0" style="background-color: transparent" type="button" data-bs-toggle="modal" data-original-title="test" data-bs-target="#exampleModal"><i style="color:blue" class="fa fa-check-circle"></i></button>
                                                    <button class="border-0" style="background-color: transparent" type="button" onclick="checkStatus({{ $dl->id }})" title="Check Status"><i style="color:green" class="fa fa-info-circle"></i></button>

                                                    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="exampleModalLabel">Approval Lembur</h5>
                                                                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <form action="{{ url('/data-lembur/approval/'.$dl->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-body">
                                                                        <div class="form-group">
                                                                            @php
                                                                                $status = array(
                                                                                    [
                                                                                        "status" => "Approved",
                                                                                        "status_name" => "Approve"
                                                                                    ],
                                                                                    [
                                                                                        "status" => "Rejected",
                                                                                        "status_name" => "Reject"
                                                                                    ]
                                                                                );
                                                                            @endphp
                                                                            <label for="status">Status</label>
                                                                            <select name="status" id="status" class="form-control selectpicker" data-live-search="true">
                                                                                <option value="">Pilih Status</option>
                                                                                @foreach ($status as $s)
                                                                                    @if(old('status', $dl->status) == $s["status"])
                                                                                    <option value="{{ $s["status"] }}" selected>{{ $s["status_name"] }}</option>
                                                                                    @else
                                                                                    <option value="{{ $s["status"] }}">{{ $s["status_name"] }}</option>
                                                                                    @endif
                                                                                @endforeach
                                                                            </select>
                                                                            @error('status')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                        <div class="form-group">
                                                                            <label for="notes" class="col-form-label">Notes:</label>
                                                                            <textarea class="form-control" id="notes" name="notes">{{ old('notes') }}</textarea>
                                                                            @error('notes')
                                                                                <div class="invalid-feedback">
                                                                                    {{ $message }}
                                                                                </div>
                                                                            @enderror
                                                                        </div>
                                                                        <input type="hidden" name="approved_by" value="{{ auth()->user()->id }}">
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                                                                        <button class="btn btn-secondary" type="submit">Save changes</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                             </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end me-4 mt-4">
                        {{ $data_lembur->links() }}
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
                    <h5 class="modal-title" id="bulkApprovalModalLabel">Ubah Status Lembur Terpilih</h5>
                    <button type="button" class="close bulk-modal-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="bulk-approval-form" method="post" action="{{ url('/data-lembur/bulk-approval') }}">
                    @csrf
                    <div class="selected-ids-container"></div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="bulk_status">Status</label>
                            <select name="status" id="bulk_status" class="form-control selectpicker" data-live-search="true" required>
                                <option value="">Pilih Status</option>
                                <option value="Approved">Approve</option>
                                <option value="Rejected">Reject</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="bulk_notes" class="col-form-label">Catatan:</label>
                            <textarea class="form-control" id="bulk_notes" name="notes" rows="3" placeholder="Catatan (opsional)">{{ old('notes') }}</textarea>
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
@endsection

@push('style')
<style>
    /* Hilangkan ikon panah sorting di kolom checkbox agar mudah diklik */
    #mytable thead th:first-child {
        width: 50px;
    }
    #mytable thead th:first-child:before,
    #mytable thead th:first-child:after {
        display: none !important;
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
    var button = $('#bulk-approval-button');
    
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
        container.append('<input type="hidden" name="ids[]" value="'+id+'">');
    });
}

$(document).ready(function() {
    // Select all checkbox handler
    $('#select_all').on('change', function () {
        var isChecked = $(this).is(':checked');
        $('.row-checkbox:not(:disabled)').prop('checked', isChecked);
        syncBulkButton();
    });

    // Individual checkbox handler - use direct binding and event delegation
    $('body').on('change', '.row-checkbox', function () {
        var totalCheckboxes = $('.row-checkbox:not(:disabled)').length;
        var checkedCheckboxes = $('.row-checkbox:checked:not(:disabled)').length;
        $('#select_all').prop('checked', totalCheckboxes > 0 && totalCheckboxes === checkedCheckboxes);
        syncBulkButton();
    });

    // Bulk approval button handler - open modal
    $('#bulk-approval-button').on('click', function (e) {
        e.preventDefault();
        
        // Check if button is disabled
        if ($(this).prop('disabled')) {
            return false;
        }
        
        var ids = collectSelectedIds();
        if (ids.length === 0) {
            alert('Pilih data lembur yang akan diubah.');
            return false;
        }
        
        // Fill selected IDs into modal
        fillSelectedIds('.selected-ids-container', ids);
        
        // Reset form
        $('#bulk_status').val('').change();
        $('#bulk_notes').val('');
        
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

function checkStatus(lemburId) {
    fetch(`/lembur/status/${lemburId}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                alert('Error: ' + data.error);
            } else {
                alert(`Status Lembur ID ${lemburId}:\n` +
                      `Status: ${data.current_status}\n` +
                      `Jam Masuk: ${data.jam_masuk}\n` +
                      `Jam Keluar: ${data.jam_keluar || 'Belum pulang'}\n` +
                      `Approved By: ${data.approved_by || 'Belum di-approve'}\n` +
                      `Notes: ${data.notes || 'Tidak ada'}\n` +
                      `Created: ${data.created_at}\n` +
                      `Updated: ${data.updated_at}`);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memeriksa status lembur');
        });
}
</script>
@endpush
