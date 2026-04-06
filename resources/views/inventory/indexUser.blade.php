@extends('templates.app')
@section('container')

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert alert-success mx-3 mt-3">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(session('warning'))
<div class="alert alert-warning mx-3 mt-3">
    <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger mx-3 mt-3">
    <i class="fas fa-times-circle"></i> {{ session('error') }}
</div>
@endif


<div class="card-secton transfer-section">
    <div class="tf-container">
        <div class="tf-balance-box">
            <form action="{{ url('/inventory') }}">
                <div class="row">
                    <div class="col-11">
                        <input type="text"
                               name="search"
                               placeholder="search.."
                               id="search"
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-1">
                        <button type="submit"
                                class="form-control btn"
                                style="border-radius:10px;width:40px">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="tf-spacing-20"></div>

<div class="ms-4 d-flex gap-2 flex-wrap">

<a href="{{ url('/inventory/download-template') }}"
class="btn btn-sm btn-success"
style="border-radius:10px">
<i class="fas fa-download"></i> Template
</a>

<button class="btn btn-sm btn-warning"
data-bs-toggle="modal"
data-bs-target="#modalImport"
style="border-radius:10px">
<i class="fas fa-file-excel"></i> Import
</button>

<a href="{{ url('/inventory/tambah') }}"
class="btn btn-sm btn-primary"
style="border-radius:10px">
+ Tambah
</a>

</div>

<div class="tf-spacing-20"></div>

<div class="transfer-content">
    <div class="tf-container">

        <table id="tablePayroll" class="table table-striped">

            <thead>
                <tr>
                    <th>No.</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>UoM</th>
                    <th>Description</th>
                    <th>Lokasi</th>
                    <th>Divisi / Jabatan</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            @if ($inventories->count() == 0)
                <tr>
                    <td colspan="9" class="text-center">Tidak Ada Data</td>
                </tr>
            @else

            @foreach ($inventories as $key => $inventory)

                <tr>

                    <td>
                        {{ ($inventories->currentpage()-1) * $inventories->perpage() + $key + 1 }}
                    </td>

                    <td>{{ $inventory->kode_barang ?? '-' }}</td>

                    <td>{{ $inventory->nama_barang ?? '-' }}</td>

                    <td>{{ $inventory->stok ?? '-' }}</td>

                    <td>{{ $inventory->uom ?? '-' }}</td>

                    <td>{!! $inventory->desc ? nl2br(e($inventory->desc)) : '-' !!}</td>

                    <td>{{ $inventory->lokasi->nama_lokasi ?? '-' }}</td>

                    <td>{{ $inventory->jabatan->nama_jabatan ?? '-' }}</td>

                    <td>
                        <div style="display:flex;gap:5px">

                            <a href="{{ url('/inventory/edit/'.$inventory->id) }}"
                               class="btn btn-sm btn-warning">
                               <i class="fa fa-edit"></i>
                            </a>

                        </div>
                    </td>

                </tr>

            @endforeach
            @endif

            </tbody>

        </table>

    </div>

    <div class="d-flex justify-content-end mr-4">
        {{ $inventories->links() }}
    </div>

</div>


{{-- MODAL IMPORT --}}
<div class="modal fade" id="modalImport" tabindex="-1">

<div class="modal-dialog">

<div class="modal-content">

<form action="{{ url('/inventory/import') }}"
method="POST"
enctype="multipart/form-data">

@csrf

<div class="modal-header">

<h5 class="modal-title">
<i class="fas fa-file-excel text-success"></i>
Import Data Inventory
</h5>

<button type="button"
class="btn-close"
data-bs-dismiss="modal"></button>

</div>


<div class="modal-body">

<div class="alert alert-info small">

<strong>Petunjuk:</strong>

<ol class="mb-0 mt-1">

<li>Download template terlebih dahulu</li>

<li>Isi mulai baris ke 4</li>

<li>Format file harus .xlsx / .xls</li>

</ol>

</div>


<label class="fw-bold">Pilih File Excel</label>

<input type="file"
class="form-control"
name="file"
id="file"
accept=".xlsx,.xls">

<div id="fileInfo"
class="text-muted mt-2 small d-none">

<i class="fas fa-file-excel text-success"></i>
<span id="fileName"></span>

</div>

</div>


<div class="modal-footer">

<a href="{{ url('/inventory/download-template') }}"
class="btn btn-success btn-sm">

<i class="fas fa-download"></i>
Template

</a>

<button type="button"
class="btn btn-secondary btn-sm"
data-bs-dismiss="modal">

Batal

</button>


<button type="submit"
class="btn btn-primary btn-sm"
id="btnImport">

<i class="fas fa-upload"></i>
Upload

</button>

</div>

</form>

</div>
</div>
</div>


<br>
<br>


@push('script')

<script>

document.getElementById('file').addEventListener('change', function(){

const fileInfo = document.getElementById('fileInfo')
const fileName = document.getElementById('fileName')

if(this.files.length > 0){

fileName.textContent = this.files[0].name
fileInfo.classList.remove('d-none')

}else{

fileInfo.classList.add('d-none')

}

})


document.querySelector('#modalImport form').addEventListener('submit', function(){

const btn = document.getElementById('btnImport')

btn.disabled = true

btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Importing...'

})

</script>

@endpush


@endsection