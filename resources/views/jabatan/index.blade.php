@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

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
* { font-family: 'Plus Jakarta Sans', sans-serif; }

.karyawan-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
}
.karyawan-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:20px 24px;
    border-bottom:1px solid var(--slate-200);
    background:#fff;
    flex-wrap:wrap;
    gap:12px;
}
.karyawan-title{
    font-size:20px;
    font-weight:700;
    color:var(--slate-700);
}
.action-group{ display:flex; gap:8px; flex-wrap:wrap; }
.action-btn{
    padding:7px 14px;
    font-size:13px;
    font-weight:600;
    border-radius:8px;
    border:1px solid var(--slate-200);
    background:#fff;
    color:var(--slate-700);
    text-decoration:none;
    transition:.2s;
    display:inline-flex;
    align-items:center;
    gap:6px;
    cursor:pointer;
}
.action-btn:hover,
.action-btn.primary{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
    border-color:var(--dash-blue);
}
.filter-bar{
    padding:16px 24px;
    background:var(--slate-50);
    border-bottom:1px solid var(--slate-200);
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}
.filter-bar .form-control{
    border-radius:8px;
    border:1px solid var(--slate-200);
    font-size:13px;
    font-family:'Plus Jakarta Sans',sans-serif;
    color:var(--slate-700);
    background:#fff;
    height:38px;
    padding:0 12px;
}
.filter-bar .form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 3px rgba(59,76,202,.1);
    outline:none;
}
.table-wrap{
    overflow-x:auto;
    overflow-y:visible !important;
    scrollbar-width:thin;
    scrollbar-color:var(--dash-blue) var(--slate-100);
}
.table-wrap::-webkit-scrollbar{ height:8px; }
.table-wrap::-webkit-scrollbar-track{ background:var(--slate-100); }
.table-wrap::-webkit-scrollbar-thumb{ background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk)); border-radius:10px; }

.karyawan-table thead th{
    background:var(--slate-100);
    font-weight:700;
    font-size:11.5px;
    text-transform:uppercase;
    letter-spacing:.5px;
    color:var(--slate-500);
    padding:12px 14px;
    white-space:nowrap;
    border-bottom:2px solid var(--slate-200);
    text-align:center;
}
.karyawan-table thead th.sticky-left-0{
    position:sticky; left:0; z-index:3;
    background:var(--slate-200); color:var(--slate-700);
}
.karyawan-table thead th.sticky-left-1{
    position:sticky; left:50px; z-index:3;
    background:var(--slate-200); color:var(--slate-700);
    min-width:200px;
}
.karyawan-table thead th.sticky-right{
    position:sticky; right:0; z-index:3;
    background:var(--slate-200); color:var(--slate-700);
    width:80px; min-width:80px;
}
.karyawan-table tbody tr{
    border-bottom:1px solid var(--slate-100);
    transition:.2s;
}
.karyawan-table tbody tr:hover{ background:#f8f9ff; }
.karyawan-table tbody td{
    padding:12px 14px;
    vertical-align:middle;
    white-space:nowrap;
    color:var(--slate-700);
    text-align:center;
    font-size:13px;
}
.karyawan-table tbody td.sticky-left-0{
    position:sticky; left:0; z-index:1;
    background:#ebebeb; font-weight:600;
    box-shadow:2px 0 5px rgba(0,0,0,.06);
}
.karyawan-table tbody td.sticky-left-1{
    position:sticky; left:50px; z-index:1;
    background:#ebebeb; font-weight:600;
    text-align:left;
    box-shadow:2px 0 5px rgba(0,0,0,.06);
}
.karyawan-table tbody td.sticky-right{
    position:sticky; right:0; z-index:1;
    background:#ebebeb;
    box-shadow:-2px 0 5px rgba(0,0,0,.06);
}
.badge-anggota{
    display:inline-block;
    padding:3px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:600;
    background:#fef3c7;
    color:#d97706;
    margin:2px;
}
.action-dropdown-btn{
    width:32px; height:32px;
    border-radius:8px;
    border:1px solid var(--slate-200);
    background:var(--slate-100);
    color:var(--slate-500);
    display:inline-flex; align-items:center; justify-content:center;
    cursor:pointer; transition:.2s;
}
.action-dropdown-btn:hover{
    background:var(--dash-blue);
    color:#fff;
    border-color:var(--dash-blue);
}
.dropdown-menu{
    border-radius:10px;
    border:1px solid var(--slate-200);
    padding:6px 0;
    position:fixed !important;
    z-index:9999 !important;
}
.dropdown-item{
    font-size:13px;
    padding:8px 16px;
    display:flex;
    align-items:center;
    gap:8px;
    transition:all .2s ease;
    color:var(--slate-700);
    text-decoration:none;
}
.dropdown-item:hover{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
}
.dropdown-item:hover i{ color:#fff !important; }
.dropdown-item.text-danger:hover{ background:#fee2e2; color:#b91c1c; }
.dropdown-item.text-danger:hover i{ color:#b91c1c !important; }

.pagination-area{
    display:flex;
    justify-content:flex-end;
    padding:16px 24px;
    border-top:1px solid var(--slate-100);
}
</style>

<div class="container-fluid">
<br>
<div class="card karyawan-card">

    {{-- Header --}}
    <div class="karyawan-header">
        <div class="karyawan-title">
            <i class="fas fa-sitemap" style="color:#3b4cca;margin-right:8px;"></i>
            {{ $title }}
        </div>
        <div class="action-group">
            <a href="{{ url('/jabatan/create') }}" class="action-btn primary">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form action="{{ url('/jabatan') }}" class="d-flex gap-2 align-items-center w-100" style="margin:0">
            <div class="d-flex align-items-center gap-2" style="flex:1; background:#fff; border:1px solid var(--slate-200); border-radius:8px; padding:0 12px; height:38px;">
                <i class="fas fa-search" style="color:var(--slate-300); font-size:13px;"></i>
                <input type="text" name="search" placeholder="Cari nama divisi..."
                       value="{{ request('search') }}"
                       style="border:none; outline:none; flex:1; font-size:13px; font-family:'Plus Jakarta Sans',sans-serif; color:var(--slate-700); background:transparent;">
            </div>
            <button type="submit" class="action-btn primary" style="height:38px; padding:0 16px; flex-shrink:0;">
                Cari
            </button>
        </form>
    </div>

    {{-- Floating Dropdown --}}
    <div id="floatingDropdown" class="dropdown-menu shadow-sm"
         style="position:fixed; z-index:9999; display:none; border-radius:10px; border:1px solid var(--slate-200); font-size:13px; min-width:180px;">
    </div>

    {{-- Tabel --}}
    <div class="table-wrap">
        <table class="karyawan-table" style="width:100%;">
            <thead>
                <tr>
                    <th class="sticky-left-0">No.</th>
                    <th class="sticky-left-1">Nama Divisi</th>
                    <th style="min-width:160px;">Manager</th>
                    <th style="min-width:220px;">Anggota</th>
                    <th class="sticky-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($data_jabatan) <= 0)
                    <tr>
                        <td colspan="5" style="padding:40px; color:var(--slate-500);">
                            <i class="fas fa-inbox" style="font-size:32px; display:block; margin-bottom:8px; opacity:.4;"></i>
                            Tidak Ada Data
                        </td>
                    </tr>
                @else
                    @foreach ($data_jabatan as $key => $dj)
                        <tr>
                            <td class="sticky-left-0">{{ ($data_jabatan->currentpage()-1)*$data_jabatan->perpage()+$key+1 }}.</td>
                            <td class="sticky-left-1">{{ $dj->nama_jabatan }}</td>
                            <td>{{ $dj->atasan($dj->manager)->name ?? '-' }}</td>
                            <td style="text-align:left; white-space:normal;">
                                @if(count($dj->anggota($dj->id, $dj->manager)) > 0)
                                    @foreach($dj->anggota($dj->id, $dj->manager) as $user)
                                        <span class="badge-anggota">{{ $user->name }}</span>
                                    @endforeach
                                @else
                                    -
                                @endif
                            </td>
                            <td class="sticky-right">
                                <div class="dropdown">
                                    <button class="action-dropdown-btn" onclick="toggleDropdown(this, event)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu-template" style="display:none;">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/jabatan/edit/'.$dj->id) }}">
                                                <i class="fas fa-edit" style="color:var(--dash-blue)"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ url('/jabatan/delete/'.$dj->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger border-0 w-100 text-start"
                                                        style="background:transparent;"
                                                        onclick="return confirm('Yakin ingin menghapus divisi ini?')">
                                                    <i class="fas fa-trash" style="color:#ef4444"></i> Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <div class="pagination-area">
        {{ $data_jabatan->links() }}
    </div>

</div>
</div>

@push('script')
<script>
let currentDropdownContent = null;

window.toggleDropdown = function(btn, event) {
    event.stopPropagation();
    const floating = document.getElementById('floatingDropdown');
    const menu = btn.closest('td').querySelector('.dropdown-menu-template');

    if (floating.style.display === 'block' && currentDropdownContent === menu) {
        floating.style.display = 'none';
        currentDropdownContent = null;
        return;
    }

    floating.innerHTML = menu.innerHTML;
    currentDropdownContent = menu;

    const rect = btn.getBoundingClientRect();
    floating.style.display = 'block';

    const dropW = 180;
    const dropH = floating.offsetHeight;
    let left = rect.right - dropW;
    let top = rect.bottom + 4;

    if (top + dropH > window.innerHeight) top = rect.top - dropH - 4;

    floating.style.left = left + 'px';
    floating.style.top = top + 'px';
};

document.addEventListener('click', function() {
    const floating = document.getElementById('floatingDropdown');
    floating.style.display = 'none';
    currentDropdownContent = null;
});
</script>
@endpush

@endsection