@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

*{
    font-family:'Plus Jakarta Sans',sans-serif;
}

:root{
    --dash-blue:#3b4cca;
    --dash-blue-dk:#2d3db4;

    --slate-50:#f8fafc;
    --slate-100:#f1f5f9;
    --slate-200:#e2e8f0;
    --slate-300:#cbd5e1;
    --slate-500:#64748b;
    --slate-700:#334155;
}

/* ================= CARD ================= */
.form-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
}

/* ================= HEADER ================= */
.form-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px;
    border-bottom:1px solid var(--slate-200);
    background:linear-gradient(135deg,#fff,#f8fafc);
}

.form-title{
    font-size:20px;
    font-weight:700;
    color:var(--slate-700);
    display:flex;
    align-items:center;
    gap:8px;
}

/* ================= BUTTON ================= */
.primary-btn{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:white;
    border:none;
    padding:8px 16px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    box-shadow:0 4px 14px rgba(59,76,202,0.25);
    text-decoration:none;
    transition:.2s;
}

.primary-btn:hover{
    transform:translateY(-1px);
    color:white;
}

/* ================= FILTER ================= */
.filter-box{
    padding:20px;
    border-bottom:1px solid var(--slate-200);
}

.filter-box .row{
    row-gap:10px;
}

.form-control{
    border-radius:10px;
    font-size:14px;
    height:40px;
    border:1px solid var(--slate-200);
}

.form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 0.15rem rgba(59,76,202,0.15);
}

/* tombol search */
.filter-box button{
    width:40px;
    height:40px;
    border-radius:10px;
    display:flex;
    align-items:center;
    justify-content:center;
}

/* ================= TABLE ================= */
.table{
    margin-bottom:0;
    border-collapse:separate;
    border-spacing:0;
}

.table thead{
    background:var(--slate-100);
}

.table th{
    font-size:13px;
    font-weight:600;
    color:var(--slate-700);
}

.table td{
    font-size:14px;
    color:var(--slate-700);
    vertical-align:middle;
    padding:12px 10px;
}

/* hover */
.table tbody tr:hover{
    background:var(--slate-50);
}

/* sticky */
.sticky-left,
.sticky-left-2{
    position:sticky;
    background:#fff;
    z-index:5;
}

/* ================= DROPDOWN ================= */
#floatingDropdown{
    position:fixed;
    z-index:3000 !important;
    min-width:180px;
    border-radius:12px;
    overflow:hidden;
}

/* ================= BADGE ================= */
.badge-status{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:600;
}

.approved{background:#dcfce7;color:#166534;}
.rejected{background:#fee2e2;color:#991b1b;}
.pending{background:#fef3c7;color:#92400e;}

/* ================= MODAL FIX ================= */
.modal{
    z-index:4000 !important;
}

.modal-backdrop{
    z-index:3990 !important;
}

.modal-dialog{
    max-width:520px;
    margin:1.75rem auto;
}

.modal-content{
    border-radius:18px;
    border:none;
    box-shadow:0 20px 60px rgba(0,0,0,.15);
}

.modal-header{
    padding:18px 22px;
    border-bottom:1px solid var(--slate-200);
    background:linear-gradient(135deg,#fff,#f8fafc);
}

.modal-body{
    padding:24px;
}

.modal-body .form-control{
    height:42px;
    border-radius:10px;
}

.modal-body textarea.form-control{
    height:auto;
    min-height:90px;
}

.modal-body .mb-3{
    margin-bottom:18px;
}

.modal-footer{
    padding:16px 22px;
    border-top:1px solid var(--slate-200);
}

/* ================= STATUS BOX ================= */
.status-box{
    display:flex;
    gap:10px;
    align-items:center;
    background:linear-gradient(135deg,#f8fafc,#f1f5f9);
    border:1px solid var(--slate-200);
    padding:10px 12px;
    border-radius:10px;
    margin-bottom:14px;
}

/* ================= DATEPICKER ================= */
.daterangepicker{
    z-index:5000 !important;
    border-radius:12px !important;
}

/* ================= RESPONSIVE ================= */
@media(max-width:768px){
    .form-header{
        flex-direction:column;
        align-items:flex-start;
        gap:10px;
    }

    .filter-box .col-md-4,
    .filter-box .col-md-3,
    .filter-box .col-md-2,
    .filter-box .col-md-1{
        width:100%;
    }
}

/* ================= ACTION BUTTON (NEW CLEAN STYLE) ================= */

.action-dropdown-btn {
    width: 34px;
    height: 34px;
    border-radius: 8px;
    border: 1px solid var(--slate-200);
    background: #fff;
    color: var(--slate-500);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: .2s;
    box-shadow: 0 2px 6px rgba(0,0,0,0.04);
}

.action-dropdown-btn:hover {
    background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk));
    color: #fff;
    border-color: var(--dash-blue);
    transform: translateY(-1px);
}

/* disabled state (APPROVED) */
.action-dropdown-btn:disabled,
.action-disabled {
    opacity: .4;
    cursor: not-allowed;
    pointer-events:none !important;
    filter: grayscale(1);
}

/* dropdown menu look konsisten dengan modal */
.dropdown-menu {
    border-radius: 12px;
    border: 1px solid var(--slate-200);
    padding: 6px 0;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    background: #fff;
}

/* item lebih clean */
.dropdown-item {
    font-size: 13px;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: .2s;
    color: var(--slate-700);
}

.dropdown-item:hover {
    background: var(--slate-50);
}

/* danger hover */
.dropdown-item.text-danger:hover {
    background: #fee2e2;
    color: #b91c1c;
}

/* ================= MODAL POLISH ================= */

.modal-content{
    border-radius: 16px;
    border: none;
    box-shadow: 0 25px 70px rgba(0,0,0,.12);
    overflow: hidden;
}

/* header lebih clean */
.modal-header{
    background: linear-gradient(135deg,#fff,#f8fafc);
    border-bottom: 1px solid var(--slate-200);
    padding: 18px 22px;
}

.modal-title{
    font-size: 16px;
    font-weight: 700;
    color: var(--slate-700);
}

/* body spacing */
.modal-body{
    padding: 22px;
}

/* label konsisten */
.modal-body label{
    font-size: 13px;
    font-weight: 600;
    color: var(--slate-700);
    margin-bottom: 6px;
}

/* input modal lebih modern */
.modal-body .form-control{
    border-radius: 10px;
    border: 1px solid var(--slate-200);
    font-size: 13px;
    height: 42px;
    transition: .2s;
}

.modal-body .form-control:focus{
    border-color: var(--dash-blue);
    box-shadow: 0 0 0 3px rgba(59,76,202,.12);
}

/* textarea */
textarea.form-control{
    min-height: 90px;
    height: auto;
    resize: none;
}

/* footer */
.modal-footer{
    border-top: 1px solid var(--slate-200);
    padding: 14px 22px;
}

/* tombol modal */
.modal-btn {
    border-radius: 10px;
    font-size: 13px;
    font-weight: 600;
    padding: 8px 14px;
    border: none;
    transition: .2s;
}

.modal-btn-cancel{
    background: #f1f5f9;
    color: #475569;
}

.modal-btn-cancel:hover{
    background: #e2e8f0;
}

.modal-btn-save{
    background: linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color: #fff;
    box-shadow: 0 6px 18px rgba(59,76,202,.25);
}

.modal-btn-save:hover{
    transform: translateY(-1px);
}
</style>

<br>

<div class="row">
<div class="col-md-12">

<div class="card form-card">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-user-minus" style="color:#3b4cca"></i>
            {{ $title }}
        </div>

        <a href="{{ url('/exit/tambah') }}" class="primary-btn">
            + Tambah
        </a>
    </div>

    {{-- FILTER --}}
    <div class="filter-box">
        <form action="{{ url('/exit') }}">
            <div class="row g-2 align-items-end">

                <div class="col-md-4">
                    <input type="text" class="form-control" name="nama"
                        placeholder="Nama Pegawai"
                        value="{{ request('nama') }}">
                </div>


                <div class="col-md-2">
                    <input type="date" class="form-control" name="mulai"
                        value="{{ request('mulai') }}">
                </div>

                <div class="col-md-2">
                    <input type="date" class="form-control" name="akhir"
                        value="{{ request('akhir') }}">
                </div>

                <div class="col-md-1 text-center">
                    <button type="submit" style="background:none;border:none;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>

            </div>
        </form>
    </div>

    {{-- FLOAT DROPDOWN --}}
    <div id="floatingDropdown" class="dropdown-menu shadow-sm" style="display:none;"></div>

    {{-- TABLE --}}
    <div class="table-responsive">

        <table class="table">
            <thead>
                <tr>
                    <th class="text-center sticky-left">No.</th>
                    <th class="text-center sticky-left-2">Nama Pegawai</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Jenis</th>
                    <th class="text-center">Alasan</th>
                    <th class="text-center">File</th>
                    <th class="text-center">User Approval</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Note</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($pegawai_keluars as $key => $pegawai_keluar)
                <tr>
                    <td class="text-center sticky-left">
                        {{ ($pegawai_keluars->currentpage()-1)*$pegawai_keluars->perpage()+$key+1 }}.
                    </td>

                    <td class="text-center sticky-left-2">
                        {{ $pegawai_keluar->user->name ?? '-' }}
                    </td>

                    <td class="text-center">{{ $pegawai_keluar->tanggal ?? '-' }}</td>
                    <td class="text-center">{{ $pegawai_keluar->jenis ?? '-' }}</td>
                    <td>{!! $pegawai_keluar->alasan ?? '-' !!}</td>

                    <td class="text-center">
                        @if($pegawai_keluar->file)
                            <a href="{{ url('/storage/'.$pegawai_keluar->file) }}"><i class="fa fa-download"></i></a>
                        @else - @endif
                    </td>

                    <td class="text-center">{{ $pegawai_keluar->approvedBy->name ?? '-' }}</td>

                    <td class="text-center">
                        <span class="badge-status {{ strtolower($pegawai_keluar->status) }}">
                            {{ $pegawai_keluar->status }}
                        </span>
                    </td>

                    <td>{!! $pegawai_keluar->notes ?? '-' !!}</td>

                    <td class="text-center">
                        <div class="dropdown">
                            <button class="action-dropdown-btn {{ $pegawai_keluar->status != 'PENDING' ? 'action-disabled' : '' }}"
    @if($pegawai_keluar->status != 'PENDING') disabled @endif
    onclick="toggleDropdown(this, event)">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>

                            <ul class="dropdown-menu-template" style="display:none">
                                <li><a class="dropdown-item" href="#">Edit</a></li>
                                <li><a class="dropdown-item text-danger" href="#">Hapus</a></li>
                                <li><button class="dropdown-item" onclick="openModal('exampleModal{{ $pegawai_keluar->id }}')">Approval</button></li>
                            </ul>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- MODAL --}}
        @foreach($pegawai_keluars as $pegawai_keluar)
        <div class="modal fade" id="exampleModal{{ $pegawai_keluar->id }}">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-check-circle"></i> Approval</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <form action="{{ url('/exit/approval/'.$pegawai_keluar->id) }}" method="POST">
                        @csrf
                        <div class="modal-body">

                            <div class="status-box">
                                <i class="fas fa-user"></i>
                                <div>
                                    <div style="font-weight:600;">
                                        {{ $pegawai_keluar->user->name ?? '-' }}
                                    </div>
                                    <small>Silakan pilih keputusan</small>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label>Status</label>
                                <select name="status" class="form-control">
                                    <option value="">Pilih</option>
                                    <option value="APPROVED">Approve</option>
                                    <option value="REJECTED">Reject</option>
                                </select>
                            </div>

                            <div>
                                <label>Catatan</label>
                                <textarea name="notes" class="form-control"></textarea>
                            </div>

                            <input type="hidden" name="approved_by" value="{{ auth()->user()->id }}">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="modal-btn modal-btn-cancel" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="modal-btn modal-btn-save">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        @endforeach

    </div>

    <div class="d-flex justify-content-end px-3 pb-3">
        {{ $pegawai_keluars->links() }}
    </div>

</div>

</div>
</div>

<script>
function toggleDropdown(btn,event){
event.stopPropagation();
const floating=document.getElementById('floatingDropdown');
const menu=btn.closest('td').querySelector('.dropdown-menu-template');

floating.innerHTML=menu.innerHTML;
floating.style.display='block';

const rect=btn.getBoundingClientRect();
floating.style.left=rect.right-180+'px';
floating.style.top=rect.bottom+'px';
}

document.addEventListener('click',()=>{
document.getElementById('floatingDropdown').style.display='none';
});

function openModal(id){
new bootstrap.Modal(document.getElementById(id)).show();
}
</script>

@endsection