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

/* ── Card utama ── */
.karyawan-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
}

/* ── Header ── */
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

/* ── Tombol aksi header ── */
.action-group{
    display:flex;
    gap:8px;
    flex-wrap:wrap;
}

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

.action-btn.warning{
    background:#fff8e1;
    color:#b45309;
    border-color:#fcd34d;
}
.action-btn.warning:hover{
    background:#fcd34d;
    color:#78350f;
    border-color:#fcd34d;
}

.action-btn.success{
    background:#f0fdf4;
    color:#166534;
    border-color:#86efac;
}
.action-btn.success:hover{
    background:#86efac;
    color:#14532d;
    border-color:#86efac;
}

/* ── Filter bar ── */
.filter-bar{
    padding:16px 24px;
    background:var(--slate-50);
    border-bottom:1px solid var(--slate-200);
    display:flex;
    gap:10px;
    flex-wrap:wrap;
    align-items:center;
}

.filter-bar .form-control,
.filter-bar .selectpicker{
    border-radius:8px;
    border:1px solid var(--slate-200);
    font-size:13px;
    font-family:'Plus Jakarta Sans',sans-serif;
    color:var(--slate-700);
    background:#fff;
    height:38px;
    padding:0 12px;
    outline:none;
    transition:.2s;
}

.filter-bar .form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 3px rgba(59,76,202,.1);
}

.search-btn{
    width:38px;
    height:38px;
    border-radius:8px;
    border:none;
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    cursor:pointer;
    transition:.2s;
    flex-shrink:0;
}
.search-btn:hover{ opacity:.85; }

/* ── Tabel ── */
.table-wrap{
    padding:0;
    overflow-x:auto;
    scrollbar-width:thin;
    scrollbar-color:var(--dash-blue) var(--slate-100);
}

.table-wrap::-webkit-scrollbar{ height:8px; }
.table-wrap::-webkit-scrollbar-track{ background:var(--slate-100); }
.table-wrap::-webkit-scrollbar-thumb{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    border-radius:10px;
}

.karyawan-table tbody td{
    padding:12px 14px;
    vertical-align:middle;
    white-space:nowrap;
    color:var(--slate-700);
    text-align:center;
}

.karyawan-table tbody td.sticky-left-1{
    text-align:left;
}

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
    background:var(--slate-200);
    color:var(--slate-700);
}
.karyawan-table thead th.sticky-left-1{
    position:sticky; left:50px; z-index:3;
    background:var(--slate-200);
    color:var(--slate-700);
    min-width:280px;
}
.karyawan-table thead th.sticky-right{
    position:sticky; right:0; z-index:3;
    background:var(--slate-200);
    color:var(--slate-700);
    width:120px; min-width:120px;
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
}

.karyawan-table tbody td.sticky-left-0{
    position:sticky; left:0; z-index:1;
    background:#ebebeb;
    text-align:center;
    font-weight:600;
    box-shadow:2px 0 5px rgba(0,0,0,.06);
}
.karyawan-table tbody td.sticky-left-1{
    position:sticky; left:50px; z-index:1;
    background:#ebebeb;
    font-weight:600;
    box-shadow:2px 0 5px rgba(0,0,0,.06);
}
.karyawan-table tbody td.sticky-right{
    position:sticky; right:0; z-index:1;
    background:#ebebeb;
    box-shadow:-2px 0 5px rgba(0,0,0,.06);
}

/* ── Badge role ── */
.role-badge{
    display:inline-block;
    padding:3px 10px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
    background:rgba(192,218,254,.85);
    color:#152f76;
    margin:2px 0;
}

/* ── Status badge ── */
.status-badge{
    display:inline-block;
    padding:3px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}
.status-aktif{ background:#dcfce7; color:#166534; }
.status-nonaktif{ background:#fee2e2; color:#991b1b; }

/* ── Action icons ── */
.action-list{
    display:flex;
    gap:6px;
    align-items:center;
    list-style:none;
    margin:0; padding:0;
    justify-content:center;
}
.action-list li a,
.action-list li button{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:30px; height:30px;
    border-radius:7px;
    background:var(--slate-100);
    border:1px solid var(--slate-200);
    color:var(--slate-500);
    font-size:13px;
    text-decoration:none;
    transition:.2s;
    cursor:pointer;
}
.action-list li a:hover,
.action-list li button:hover{
    background:var(--dash-blue);
    color:#fff;
    border-color:var(--dash-blue);
}
.action-list li.delete a:hover,
.action-list li.delete button:hover{
    background:#ef4444;
    border-color:#ef4444;
    color:#fff;
}

/* ── Foto avatar ── */
.avatar-img{
    width:46px; height:46px;
    border-radius:50%;
    object-fit:cover;
    border:2px solid var(--slate-200);
    cursor:pointer;
    transition:.2s;
}
.avatar-img:hover{ border-color:var(--dash-blue); transform:scale(1.05); }

/* ── QR btn ── */
.qr-btn{
    display:inline-flex;
    align-items:center;
    gap:6px;
    padding:5px 12px;
    border-radius:8px;
    font-size:12px;
    font-weight:600;
    background:var(--slate-100);
    border:1px solid var(--slate-200);
    color:var(--slate-700);
    text-decoration:none;
    transition:.2s;
}
.qr-btn:hover{
    background:var(--dash-blue);
    color:#fff;
    border-color:var(--dash-blue);
}

/* ── Pagination area ── */
.pagination-area{
    display:flex;
    justify-content:flex-end;
    padding:16px 24px;
    border-top:1px solid var(--slate-100);
}

/* ── Modal import ── */
.modal-content{ border-radius:14px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.15); }
.modal-header{ border-bottom:1px solid var(--slate-200); padding:20px 24px; }
.modal-title{ font-weight:700; color:var(--slate-700); }
.modal-footer{ border-top:1px solid var(--slate-200); padding:16px 24px; }

/* ── Foto preview modal ── */
#previewFoto{ width:100%; border-radius:10px; }

/* ── Scroll indicator ── */
@media print{
    .table-wrap{ overflow-x:visible; }
    .karyawan-table{ min-width:auto; width:100%; }
}

.action-dropdown-btn {
    width: 32px; height: 32px;
    border-radius: 8px;
    border: 1px solid var(--slate-200);
    background: var(--slate-100);
    color: var(--slate-500);
    display: inline-flex; align-items: center; justify-content: center;
    cursor: pointer; transition: .2s;
}
.action-dropdown-btn:hover {
    background: var(--dash-blue);
    color: #fff;
    border-color: var(--dash-blue);
}
.dropdown-item { padding: 8px 16px; display:flex; align-items:center; }
.dropdown-item:hover { background: var(--slate-50); }
.dropdown-item.text-danger:hover { background: #fff5f5; }

/* Pastikan dropdown tidak ter-clip oleh sticky column */
.table-wrap {
    overflow-x: auto;
    overflow-y: visible !important; /* <-- ini kuncinya */
}

/* Atau jika overflow-y visible merusak scroll horizontal,
   pakai pendekatan ini sebagai gantinya: */
.dropdown-menu {
    position: fixed !important;
    z-index: 9999 !important;
}

/* Dropdown menu */
.dropdown-menu {
    border-radius: 10px;
    border: 1px solid var(--slate-200);
    padding: 6px 0;
}

/* item */
.dropdown-item{
    font-size:13px;
    padding:8px 16px;
    display:flex;
    align-items:center;
    gap:8px;
    transition:all .2s ease;
}

/* hover normal */
.dropdown-item:hover{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
}

.dropdown-item:hover i{
    color:#fff !important;
}

/* Hover khusus tombol nonaktifkan */
.dropdown-item.text-danger:hover{
    background:#fee2e2;
    color:#b91c1c;
}

.dropdown-item.text-danger:hover i{
    color:#b91c1c !important;
}
</style>

<div class="container-fluid">

<br>
    {{-- ── CARD UTAMA ── --}}
    <div class="card karyawan-card">

        {{-- Header --}}
        <div class="karyawan-header">
            <div class="karyawan-title">
                <i class="fas fa-users" style="color:#3b4cca;margin-right:8px;"></i>
                {{ $title }}
            </div>

            <div class="action-group">
                <a href="{{ url('/pegawai/tambah-pegawai') }}" class="action-btn primary">
                    <i class="fas fa-plus"></i> Tambah
                </a>

                <button class="action-btn warning" type="button"
                    data-bs-toggle="modal" data-bs-target="#importModal">
                    <i class="fa fa-table"></i> Import
                </button>

                <a href="{{ url('/pegawai/export') }}{{ $_GET ? '?'.$_SERVER['QUERY_STRING'] : '' }}"
                   class="action-btn success">
                    <i class="fa fa-file-excel"></i> Export
                </a>
            </div>
        </div>

        {{-- Filter bar --}}
        <div class="filter-bar">
            <form action="{{ url('/pegawai') }}" class="d-flex gap-2 align-items-center w-100" style="margin:0">
                <select name="jabatan_id" id="jabatan_id" class="form-control" style="width:200px; flex-shrink:0;">
                    <option value="" selected>Semua Divisi</option>
                    @foreach($jabatan as $j)
                        <option value="{{ $j->id }}" {{ request('jabatan_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
        
                <div class="d-flex align-items-center gap-2" style="flex:1; background:#fff; border:1px solid var(--slate-200); border-radius:8px; padding:0 12px; height:38px;">
                    <i class="fas fa-search" style="color:var(--slate-300); font-size:13px;"></i>
                    <input type="text" name="search" placeholder="Cari pegawai..."
                           value="{{ request('search') }}"
                           style="border:none; outline:none; flex:1; font-size:13px; font-family:'Plus Jakarta Sans',sans-serif; color:var(--slate-700); background:transparent;">
                </div>
        
                <button type="submit" class="action-btn primary" style="height:38px; padding:0 16px; flex-shrink:0;">
                    Cari
                </button>
            </form>
        </div>
        
        <!-- Floating dropdown, di luar tabel -->
<div id="floatingDropdown" class="dropdown-menu shadow-sm"
     style="position:fixed; z-index:9999; display:none; border-radius:10px; border:1px solid var(--slate-200); font-size:13px; min-width:190px;">
</div>

        {{-- Tabel --}}
        <div class="table-wrap">
            <table class="karyawan-table">
                <thead>
                    <tr>
                        <th class="sticky-left-0">No.</th>
                        <th class="sticky-left-1">Nama</th>
                        <th style="min-width:100px;">Foto</th>
                        <th style="min-width:150px;">Username</th>
                        <th style="min-width:180px;">Lokasi</th>
                        <th style="min-width:180px;">Divisi</th>
                        <th style="min-width:200px;">Role</th>
                        <th style="min-width:150px;">Dashboard</th>
                        <th style="min-width:200px;">Masa Berlaku</th>
                        <!--<th style="min-width:160px;">Kartu</th>-->
                        <th class="sticky-right">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($data_user) <= 0)
                        <tr>
                            <td colspan="11" class="text-center" style="padding:40px; color:var(--slate-500);">
                                <i class="fas fa-inbox" style="font-size:32px; display:block; margin-bottom:8px; opacity:.4;"></i>
                                Tidak Ada Data
                            </td>
                        </tr>
                    @else
                        @foreach($data_user as $key => $du)
                            <tr>
                                <td class="sticky-left-0">
                                    {{ ($data_user->currentpage() - 1) * $data_user->perpage() + $key + 1 }}.
                                </td>

                                <td class="sticky-left-1">{{ $du->name }}</td>

                                <td class="text-center">
                                    @if($du->foto_karyawan == null)
                                        <img class="avatar-img" style="cursor:default;"
                                             src="{{ url('assets/img/foto_default.jpg') }}"
                                             alt="{{ $du->name ?? '-' }}">
                                    @else
                                        <img class="avatar-img"
                                             src="{{ asset($du->foto_karyawan) }}"
                                             alt="{{ $du->name ?? '-' }}"
                                             data-bs-toggle="modal"
                                             data-bs-target="#fotoModal"
                                             onclick="showImage('{{ asset($du->foto_karyawan) }}')">
                                    @endif
                                </td>

                                <td class="text-center">{{ $du->username ?? '-' }}</td>
                                <td>{{ $du->Lokasi->nama_lokasi ?? '-' }}</td>
                                <td>{{ $du->Jabatan->nama_jabatan ?? '-' }}</td>

                                <td class="text-center">
                                    @if(count($du->roles) > 0)
                                        @foreach($du->roles as $role)
                                            <span class="role-badge">{{ $role->name ?? '-' }}</span><br>
                                        @endforeach
                                    @else
                                        <span style="color:var(--slate-300)">—</span>
                                    @endif
                                </td>

                                <td class="text-center">{{ $du->is_admin ?? '-' }}</td>

                                <td class="text-center">
                                    @if($du->masa_berlaku)
                                        @php
                                            Carbon\Carbon::setLocale('id');
                                            $masa_berlaku = Carbon\Carbon::createFromFormat('Y-m-d', $du->masa_berlaku);
                                            $new_masa_berlaku = $masa_berlaku->translatedFormat('d F Y');
                                            $expired = $du->masa_berlaku <= date('Y-m-d');
                                        @endphp
                                        <span class="status-badge {{ $expired ? 'status-nonaktif' : 'status-aktif' }}">
                                            {{ $new_masa_berlaku }}
                                        </span><br>
                                        <span class="status-badge mt-1 {{ $expired ? 'status-nonaktif' : 'status-aktif' }}">
                                            {{ $expired ? 'Non-Aktif' : 'Aktif' }}
                                        </span>
                                    @else
                                        <span style="font-size:26px;">♾️</span><br>
                                        <span class="status-badge status-aktif mt-1">Aktif</span>
                                    @endif
                                </td>

                                <!--<td class="text-center">-->
                                <!--    <a href="{{ url('/pegawai/qrcode/'.$du->id) }}" class="qr-btn">-->
                                <!--        <i class="fas fa-qrcode"></i> Qrcode-->
                                <!--    </a>-->
                                <!--</td>-->

                                <td class="sticky-right text-center">
                                    <div class="dropdown">
                                        <button class="action-dropdown-btn" onclick="toggleDropdown(this, event)">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu-template" style="display:none;">
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/pegawai/detail/'.$du->id) }}">
                                                    <i class="icon-pencil-alt me-2 text-primary"></i> Edit Pegawai
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/pegawai/edit-password/'.$du->id) }}">
                                                    <i class="fa fa-key me-2 text-primary"></i> Ganti Password
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/pegawai/shift/'.$du->id) }}">
                                                    <i class="fa fa-clock me-2" style="color:coral"></i> Mapping Shift
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/pegawai/dinas-luar/'.$du->id) }}">
                                                    <i class="fa fa-route me-2" style="color:rgb(43,198,203)"></i> Dinas Luar
                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ url('/pegawai/kontrak/'.$du->id) }}">
                                                    <i data-feather="trending-up" class="me-2" style="width:14px; color:var(--slate-500)"></i> Kontrak Kerja
                                                </a>
                                            </li>
                                            <li>
                                                @if($du->foto_face_recognition)
                                                    <a class="dropdown-item" href="javascript:void(0)" onclick="confirmRekamUlang({{ $du->id }})">
                                                        <i class="fa fa-camera-retro me-2" style="color:green"></i> Rekam Ulang Wajah
                                                    </a>
                                                @else
                                                    <a class="dropdown-item" href="{{ url('/pegawai/face/'.$du->id) }}">
                                                        <i class="fa fa-camera me-2"></i> Rekam Wajah
                                                    </a>
                                                @endif
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                {{-- Nonaktifkan user (ganti tombol hapus) --}}
                                                <a class="dropdown-item text-danger" href="javascript:void(0)"
                                                   onclick="confirmNonaktif({{ $du->id }}, '{{ $du->name }}')">
                                                    <i class="fa fa-ban me-2"></i> Nonaktifkan
                                                </a>
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

        {{-- Pagination --}}
        <div class="pagination-area">
            {{ $data_user->links() }}
        </div>

    </div>{{-- /karyawan-card --}}
</div>

{{-- ── MODAL IMPORT ── --}}
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">
                    <i class="fa fa-table me-2" style="color:var(--dash-blue);"></i> Import Users
                </h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ url('/pegawai/import') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body" style="padding:24px;">
                    @csrf
                    <div class="form-group">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label for="file_excel" class="mb-0" style="font-weight:600; color:var(--slate-700); font-size:14px;">File Excel</label>
                            <a href="{{ asset('DataUser/userimport.xlsx') }}"
                               class="action-btn success" style="font-size:12px; padding:5px 10px;"
                               download>
                                <i class="fa fa-download"></i> Download Template
                            </a>
                        </div>
                        <input type="file" name="file_excel" id="file_excel"
                               class="form-control @error('file_excel') is-invalid @enderror"
                               accept=".xlsx,.xls"
                               style="border-radius:8px; border-color:var(--slate-200); font-size:13px;">
                        @error('file_excel')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted mt-1 d-block">Format file: .xlsx atau .xls</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="action-btn" type="button" data-bs-dismiss="modal">Tutup</button>
                    <button class="action-btn primary" type="submit">
                        <i class="fa fa-upload"></i> Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL PREVIEW FOTO ── --}}
<div class="modal fade" id="fotoModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center" style="border-radius:14px; border:none;">
            <div class="modal-body" style="padding:16px;">
                <img id="previewFoto" src="" style="width:100%; border-radius:10px;">
            </div>
        </div>
    </div>
</div>

@push('script')
<script>
$(document).ready(function(){

    window.confirmRekamUlang = function(userId){
        Swal.fire({
            title:'Rekam Ulang Face Recognition?',
            text:'Face Recognition sudah pernah direkam. Data sebelumnya akan diganti jika Anda melanjutkan.',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#3b4cca',
            cancelButtonColor:'#ef4444',
            confirmButtonText:'Ya, Rekam Ulang',
            cancelButtonText:'Batal'
        }).then(result=>{
            if(result.isConfirmed) window.location.href='/pegawai/face/'+userId;
        });
    };

    window.confirmNonaktif = function(userId, userName) {
        Swal.fire({
            title: 'Nonaktifkan Pengguna?',
            html: `<p>Akun <strong>${userName}</strong> akan dinonaktifkan.<br><small class="text-muted">Pengguna tidak dapat login hingga diaktifkan kembali.</small></p>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fa fa-ban"></i> Nonaktifkan',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/pegawai/nonaktif/${userId}`;
                const c = document.createElement('input');
                c.type = 'hidden'; c.name = '_token'; c.value = '{{ csrf_token() }}';
                form.appendChild(c);
                document.body.appendChild(form);
                form.submit();
            }
        });
    };

    $('.table-wrap').on('scroll',function(){
        const sl=$(this).scrollLeft();
        $(this).toggleClass('scrolled', sl>0);
        const sw=$(this)[0].scrollWidth, cw=$(this)[0].clientWidth;
        $(this).toggleClass('scrolled-full', sl>=sw-cw-1);
    });
});

function showImage(src){
    document.getElementById('previewFoto').src=src;
}

let currentDropdownContent = null;

window.toggleDropdown = function(btn, event) {
    event.stopPropagation();
    const floating = document.getElementById('floatingDropdown');
    const menu = btn.closest('td').querySelector('.dropdown-menu-template');
    
    // Kalau sudah terbuka dari tombol yang sama, tutup
    if (floating.style.display === 'block' && currentDropdownContent === menu) {
        floating.style.display = 'none';
        currentDropdownContent = null;
        return;
    }

    // Isi konten dari template tersembunyi
    floating.innerHTML = menu.innerHTML;
    currentDropdownContent = menu;

    // Hitung posisi relatif terhadap tombol
    const rect = btn.getBoundingClientRect();
    floating.style.display = 'block';

    const dropW = 190;
    const dropH = floating.offsetHeight;
    let left = rect.right - dropW;
    let top = rect.bottom + 4;

    // Jangan sampai keluar viewport bawah
    if (top + dropH > window.innerHeight) {
        top = rect.top - dropH - 4;
    }

    floating.style.left = left + 'px';
    floating.style.top = top + 'px';

    // Re-init feather icons kalau ada
    if (window.feather) feather.replace();
};

// Tutup dropdown kalau klik di luar
document.addEventListener('click', function() {
    const floating = document.getElementById('floatingDropdown');
    floating.style.display = 'none';
    currentDropdownContent = null;
});
</script>
@endpush

@endsection