@extends('templates.dashboard')
@section('isi')

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
* { font-family: 'Plus Jakarta Sans', sans-serif; }
:root {
    --dash-blue: #3b4cca;
    --dash-blue-dk: #2d3db4;
    --slate-50: #f8fafc;
    --slate-100: #f1f5f9;
    --slate-200: #e2e8f0;
    --slate-700: #334155;
}
.form-card { border-radius: 16px; border: 1px solid var(--slate-200); box-shadow: 0 6px 20px rgba(0,0,0,0.06); overflow: hidden; background: #fff; }
.form-header { display: flex; justify-content: space-between; align-items: center; padding: 18px 20px; border-bottom: 1px solid var(--slate-200); background: linear-gradient(135deg, #fff, #f8fafc); }
.form-title { font-size: 18px; font-weight: 700; color: var(--slate-700); }
.primary-btn { background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk)); color: #fff; border: none; padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: .2s; display: inline-flex; align-items: center; gap: 6px; }
.primary-btn:hover { transform: translateY(-1px); color: #fff; }
.success-btn { background: linear-gradient(135deg, #16a34a, #15803d); color: #fff; border: none; padding: 8px 14px; border-radius: 10px; font-size: 13px; font-weight: 600; text-decoration: none; transition: .2s; display: inline-flex; align-items: center; gap: 6px; }
.success-btn:hover { transform: translateY(-1px); color: #fff; }
.filter-box { padding: 16px 20px; border-bottom: 1px solid var(--slate-200); }
.form-control { border-radius: 10px; height: 40px; border: 1px solid var(--slate-200); font-size: 14px; }
.form-control:focus { border-color: var(--dash-blue); box-shadow: 0 0 0 .15rem rgba(59,76,202,.15); }
.table { margin-bottom: 0; }
.table thead { background: var(--slate-100); }
.table th { font-size: 13px; font-weight: 600; color: var(--slate-700); }
.table td { font-size: 14px; color: var(--slate-700); vertical-align: middle; }
.action { display: flex; gap: 10px; list-style: none; padding: 0; margin: 0; }
.action-dropdown-btn { width: 34px; height: 34px; border-radius: 8px; border: 1px solid var(--slate-200); background: #fff; color: var(--slate-700); display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: .2s; box-shadow: 0 2px 6px rgba(0,0,0,0.04); }
.action-dropdown-btn:hover { background: linear-gradient(135deg, var(--dash-blue), var(--dash-blue-dk)); color: #fff; border-color: var(--dash-blue); transform: translateY(-1px); }
.dropdown-menu-template { position: absolute; z-index: 3000; min-width: 190px; border-radius: 12px; overflow: hidden; background: #fff; box-shadow: 0 12px 30px rgba(0,0,0,0.08); border: 1px solid var(--slate-200); }
.dropdown-item { font-size: 13px; padding: 9px 14px; display: flex; align-items: center; gap: 8px; color: var(--slate-700); transition: .2s; text-decoration: none; }
.dropdown-item:hover { background: var(--slate-50); }
.dropdown-item.text-danger:hover { background: #fee2e2; color: #b91c1c; }
.badge-kontrak { display: inline-block; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; }
.badge-pkwt { background: #dbeafe; color: #1d4ed8; }
.badge-pkwtt { background: #dcfce7; color: #15803d; }
.badge-thl { background: #fef3c7; color: #d97706; }
</style>

<div class="row">
<div class="col-md-12"><br>
<div class="card form-card">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-title">
            <i class="fas fa-file-contract" style="color:#3b4cca"></i>&nbsp;{{ $title }}
        </div>
        <div class="d-flex gap-2">
            <a href="{{ url('/kontrak/export') }}{{ $_GET ? '?'.$_SERVER['QUERY_STRING'] : '' }}" class="success-btn">
                <i class="fas fa-file-export"></i> Export
            </a>
            <a href="{{ url('/kontrak/tambah') }}" class="primary-btn">
                <i class="fas fa-plus"></i> Tambah
            </a>
        </div>
    </div>

    {{-- FILTER --}}
    <div class="filter-box">
        <form action="{{ url('/kontrak') }}">
            <div class="row g-2 align-items-center">
                <div class="col-md-4">
                    <input type="text" name="nama" class="form-control" placeholder="Nama Pegawai..." value="{{ request('nama') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="mulai" class="form-control" placeholder="Tanggal Mulai" value="{{ request('mulai') }}">
                </div>
                <div class="col-md-3">
                    <input type="date" name="akhir" class="form-control" placeholder="Tanggal Akhir" value="{{ request('akhir') }}">
                </div>
                <div class="col-md-1">
                    <button type="submit" class="primary-btn" style="width:40px;height:40px;padding:0;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- TABLE --}}
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th class="text-center" style="width:50px">No.</th>
                    <th>Nama Pegawai</th>
                    <th class="text-center">Tanggal</th>
                    <th class="text-center">Jenis Kontrak</th>
                    <th class="text-center">Tanggal Mulai</th>
                    <th class="text-center">Tanggal Selesai</th>
                    <th>Keterangan</th>
                    <th class="text-center">File</th>
                    <th class="text-center" style="width:70px">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if (count($kontraks) <= 0)
                    <tr>
                        <td colspan="9" class="text-center py-4" style="color:#94a3b8">
                            <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Tidak Ada Data
                        </td>
                    </tr>
                @else
                    @foreach ($kontraks as $key => $kontrak)
                        <tr>
                            <td class="text-center">{{ ($kontraks->currentpage() - 1) * $kontraks->perpage() + $key + 1 }}.</td>
                            <td><strong>{{ $kontrak->user->name ?? '-' }}</strong></td>
                            <td class="text-center">
                                @if ($kontrak->tanggal)
                                    @php Carbon\Carbon::setLocale('id'); @endphp
                                    {{ Carbon\Carbon::parse($kontrak->tanggal)->translatedFormat('d F Y') }}
                                @else - @endif
                            </td>
                            <td class="text-center">
                                @if ($kontrak->jenis_kontrak == 'Perjanjian Kerja Waktu Tertentu (PKWT)')
                                    <span class="badge-kontrak badge-pkwt">PKWT</span>
                                @elseif ($kontrak->jenis_kontrak == 'Perjanjian Kerja Waktu Tidak Tertentu (PKWTT)')
                                    <span class="badge-kontrak badge-pkwtt">PKWTT</span>
                                @elseif ($kontrak->jenis_kontrak == 'Tenaga Harian Lepas (THL)')
                                    <span class="badge-kontrak badge-thl">THL</span>
                                @else
                                    {{ $kontrak->jenis_kontrak ?? '-' }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($kontrak->tanggal_mulai)
                                    {{ Carbon\Carbon::parse($kontrak->tanggal_mulai)->translatedFormat('d F Y') }}
                                @else - @endif
                            </td>
                            <td class="text-center">
                                @if ($kontrak->tanggal_selesai)
                                    {{ Carbon\Carbon::parse($kontrak->tanggal_selesai)->translatedFormat('d F Y') }}
                                @else <span style="color:#94a3b8">—</span> @endif
                            </td>
                            <td style="max-width:200px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {!! $kontrak->keterangan ? nl2br(e($kontrak->keterangan)) : '-' !!}
                            </td>
                            <td class="text-center">
                                @if ($kontrak->kontrak_file_path)
                                    <a href="{{ url('/storage/'.$kontrak->kontrak_file_path) }}" style="font-size:12px;color:var(--dash-blue)">
                                        <i class="fa fa-download"></i> {{ Str::limit($kontrak->kontrak_file_name, 20) }}
                                    </a>
                                @else - @endif
                            </td>
                            <td class="text-center">
                                <div class="dropdown">
                                    <button class="action-dropdown-btn" type="button" onclick="toggleDropdown(this, event)">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                    <ul class="dropdown-menu-template" style="display:none">
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/kontrak/edit/'.$kontrak->id) }}">
                                                <i class="fas fa-edit"></i> Edit
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ url('/kontrak/draft/'.$kontrak->id) }}" target="_blank">
                                                <i class="fas fa-file-word" style="color:#2563eb"></i> Generate Kontrak
                                            </a>
                                        </li>
                                        <li>
                                            <form action="{{ url('/kontrak/delete/'.$kontrak->id) }}" method="post">
                                                @method('delete')
                                                @csrf
                                                <button class="dropdown-item text-danger border-0 w-100 text-start" onclick="return confirm('Hapus kontrak ini?')">
                                                    <i class="fas fa-trash"></i> Delete
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

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-end px-3 pb-3 pt-2">
        {{ $kontraks->links() }}
    </div>

</div>
</div>
</div>

<script>
function toggleDropdown(btn, event) {
    event.stopPropagation();
    const menu = btn.closest('td').querySelector('.dropdown-menu-template');
    document.querySelectorAll('.dropdown-menu-template').forEach(el => el.style.display = 'none');
    const rect = btn.getBoundingClientRect();
    menu.style.display = 'block';
    menu.style.position = 'fixed';
    menu.style.left = (rect.right - 190) + 'px';
    menu.style.top = rect.bottom + 'px';
}
document.addEventListener('click', () => {
    document.querySelectorAll('.dropdown-menu-template').forEach(el => el.style.display = 'none');
});
$(document).ready(function () {
    $('#mulai').change(function () {
        $('#akhir').val($(this).val());
    });
});
</script>

@endsection