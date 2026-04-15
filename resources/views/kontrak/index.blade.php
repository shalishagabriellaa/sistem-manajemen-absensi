@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

:root{
    --dash-blue:#3b4cca;
    --dash-blue-dk:#2d3db4;
    --dash-blue-lt:#5c6ed4;
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
.action-btn.success{
    background:#f0fdf4;
    color:#166534;
    border-color:#86efac;
}
.action-btn.success:hover{
    background:linear-gradient(135deg,#16a34a,#15803d);
    color:#fff;
    border-color:#16a34a;
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
.badge-kontrak{
    display:inline-block;
    padding:3px 10px;
    border-radius:20px;
    font-size:11px;
    font-weight:600;
}
.badge-pkwt{ background:#dbeafe; color:#1d4ed8; }
.badge-pkwtt{ background:#dcfce7; color:#15803d; }
.badge-thl{ background:#fef3c7; color:#d97706; }

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
            <i class="fas fa-file-contract" style="color:#3b4cca;margin-right:8px;"></i>
            {{ $title }}
        </div>
        <div class="action-group">
            <a href="{{ url('/kontrak/export') }}{{ $_GET ? '?'.$_SERVER['QUERY_STRING'] : '' }}" class="action-btn success">
                <i class="fas fa-file-export"></i> Export
            </a>
            <a href="{{ url('/kontrak/tambah') }}" class="action-btn primary">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-bar">
        <form action="{{ url('/kontrak') }}" class="d-flex gap-2 align-items-center w-100" style="margin:0">
            <div class="d-flex align-items-center gap-2" style="flex:1; background:#fff; border:1px solid var(--slate-200); border-radius:8px; padding:0 12px; height:38px;">
                <i class="fas fa-search" style="color:var(--slate-300); font-size:13px;"></i>
                <input type="text" name="nama" placeholder="Cari nama pegawai..."
                       value="{{ request('nama') }}"
                       style="border:none; outline:none; flex:1; font-size:13px; font-family:'Plus Jakarta Sans',sans-serif; color:var(--slate-700); background:transparent;">
            </div>
            <input type="date" name="mulai" class="form-control" style="width:160px;" value="{{ request('mulai') }}" placeholder="Mulai">
            <input type="date" name="akhir" class="form-control" style="width:160px;" value="{{ request('akhir') }}" placeholder="Akhir">
            <button type="submit" class="action-btn primary" style="height:38px; padding:0 16px; flex-shrink:0;">
                Cari
            </button>
        </form>
    </div>

    {{-- Floating Dropdown --}}
    <div id="floatingDropdown" class="dropdown-menu shadow-sm"
         style="position:fixed; z-index:9999; display:none; border-radius:10px; border:1px solid var(--slate-200); font-size:13px; min-width:200px;">
    </div>

    {{-- Tabel --}}
    <div class="table-wrap">
        <table class="karyawan-table" style="width:100%;">
            <thead>
                <tr>
                    <th class="sticky-left-0">No.</th>
                    <th class="sticky-left-1">Nama Pegawai</th>
                    <th style="min-width:130px;">Tanggal</th>
                    <th style="min-width:100px;">Jenis Kontrak</th>
                    <th style="min-width:130px;">Tgl Mulai</th>
                    <th style="min-width:130px;">Tgl Selesai</th>
                    <th style="min-width:200px;">Keterangan</th>
                    <th style="min-width:150px;">File</th>
                    <th class="sticky-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if(count($kontraks) <= 0)
                    <tr>
                        <td colspan="9" style="padding:40px; color:var(--slate-500);">
                            <i class="fas fa-inbox" style="font-size:32px; display:block; margin-bottom:8px; opacity:.4;"></i>
                            Tidak Ada Data
                        </td>
                    </tr>
                @else
                    @foreach($kontraks as $key => $kontrak)
                        <tr>
                            <td class="sticky-left-0">{{ ($kontraks->currentpage()-1)*$kontraks->perpage()+$key+1 }}.</td>
                            <td class="sticky-left-1">{{ $kontrak->user->name ?? '-' }}</td>
                            <td>
                                @if($kontrak->tanggal)
                                    @php Carbon\Carbon::setLocale('id'); @endphp
                                    {{ Carbon\Carbon::parse($kontrak->tanggal)->translatedFormat('d F Y') }}
                                @else — @endif
                            </td>
                            <td>
                                @if($kontrak->jenis_kontrak == 'Perjanjian Kerja Waktu Tertentu (PKWT)')
                                    <span class="badge-kontrak badge-pkwt">PKWT</span>
                                @elseif($kontrak->jenis_kontrak == 'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)')
                                    <span class="badge-kontrak badge-pkwtt">PKWTT</span>
                                @elseif($kontrak->jenis_kontrak == 'Tenaga Harian Lepas (THL)')
                                    <span class="badge-kontrak badge-thl">THL</span>
                                @else {{ $kontrak->jenis_kontrak ?? '-' }} @endif
                            </td>
                            <td>
                                @if($kontrak->tanggal_mulai) {{ Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') }}
                                @else — @endif
                            </td>
                            <td>
                                @if($kontrak->tanggal_selesai) {{ Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') }}
                                @else <span style="color:#94a3b8">—</span> @endif
                            </td>
                            <td style="text-align:left; max-width:220px; overflow:hidden; text-overflow:ellipsis;">
                                {!! $kontrak->keterangan ? nl2br(e(Str::limit($kontrak->keterangan, 60))) : '-' !!}
                            </td>
                            <td>
                                @if($kontrak->kontrak_file_path)
                                    <a href="{{ url('/storage/'.$kontrak->kontrak_file_path) }}" style="font-size:12px;color:var(--dash-blue)">
                                        <i class="fa fa-download"></i> {{ Str::limit($kontrak->kontrak_file_name, 18) }}
                                    </a>
                                @else — @endif
                            </td>
                            <td class="sticky-right">
                                <div class="dropdown">
                                    <button class="action-dropdown-btn" onclick="toggleDropdown(this, event)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu-template" style="display:none;">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/kontrak/edit/'.$kontrak->id) }}">
                                                <i class="fas fa-edit" style="color:var(--dash-blue)"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/kontrak/draft/'.$kontrak->id) }}" target="_blank">
                                                <i class="fas fa-file-word" style="color:#2563eb"></i> Generate Kontrak
                                            </a>
                                        </li>
                                        <!--<li><hr class="dropdown-divider"></li>-->
                                        <!--<li>-->
                                        <!--    <form action="{{ url('/kontrak/delete/'.$kontrak->id) }}" method="post">-->
                                        <!--        @method('delete')-->
                                        <!--        @csrf-->
                                        <!--        <button class="dropdown-item text-danger border-0 w-100 text-start"-->
                                        <!--                onclick="return confirm('Hapus kontrak ini?')">-->
                                        <!--            <i class="fas fa-trash"></i> Hapus-->
                                        <!--        </button>-->
                                        <!--    </form>-->
                                        <!--</li>-->
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
        {{ $kontraks->links() }}
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

    const dropW = 200;
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