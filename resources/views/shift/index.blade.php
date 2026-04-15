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
    --slate-700:#334155;
}

/* CARD */
.form-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
    background:#fff;
}

/* HEADER */
.form-header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:18px 20px;
    border-bottom:1px solid var(--slate-200);
    background:linear-gradient(135deg,#fff,#f8fafc);
}

.form-title{
    font-size:18px;
    font-weight:700;
    color:var(--slate-700);
}

/* BUTTON */
.primary-btn{
    background:linear-gradient(135deg,var(--dash-blue),var(--dash-blue-dk));
    color:#fff;
    border:none;
    padding:8px 14px;
    border-radius:10px;
    font-size:13px;
    font-weight:600;
    text-decoration:none;
    transition:.2s;
}

.primary-btn:hover{
    transform:translateY(-1px);
    color:#fff;
}

/* FILTER */
.filter-box{
    padding:16px 20px;
    border-bottom:1px solid var(--slate-200);
}

.form-control{
    border-radius:10px;
    height:40px;
    border:1px solid var(--slate-200);
    font-size:14px;
}

.form-control:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 .15rem rgba(59,76,202,.15);
}

/* TABLE */
.table{
    margin-bottom:0;
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
}

/* ACTION */
.action{
    display:flex;
    gap:10px;
    list-style:none;
    padding:0;
    margin:0;
}

.action li a,
.action li button{
    color:#334155;
    font-size:14px;
    transition:.2s;
}

.action li a:hover{
    color:var(--dash-blue);
}

.action .delete i{
    color:#dc2626;
}
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

.dropdown-menu-template{
    position: absolute;
    z-index: 3000;
    min-width: 180px;
    border-radius: 12px;
    overflow: hidden;
    background: #fff;
    box-shadow: 0 12px 30px rgba(0,0,0,0.08);
    border: 1px solid var(--slate-200);
}

.dropdown-item{
    font-size: 13px;
    padding: 9px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--slate-700);
    transition: .2s;
    text-decoration: none;
}

.dropdown-item:hover{
    background: var(--slate-50);
}

.dropdown-item.text-danger:hover{
    background: #fee2e2;
    color: #b91c1c;
}
</style>

<div class="row">
<div class="col-md-12">
<br>
<div class="card form-card">

    {{-- HEADER --}}
    <div class="form-header">
        <div class="form-title">
    <i class="fas fa-clock" style="color:#3b4cca"></i>
 &nbsp;    {{ $title }}
</div>

        <a href="{{ url('/shift/create') }}" class="primary-btn">
            + Tambah
        </a>
    </div>

    {{-- FILTER --}}
    <div class="filter-box">
        <form action="{{ url('/shift') }}">
            <div class="row g-2 align-items-center">

                <div class="col-md-4">
                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Search..."
                           value="{{ request('search') }}">
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
                    <th class="text-center">No.</th>
                    <th class="text-center">Nama Shift</th>
                    <th class="text-center">Jam Masuk</th>
                    <th class="text-center">Jam Keluar</th>
                    <th class="text-center">Jam Mulai Istirahat</th>
                    <th class="text-center">Jam Selesai Istirahat</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($shift as $key => $s)
                    <tr>
                        <td class="text-center">
                            {{ ($shift->currentpage() - 1) * $shift->perpage() + $key + 1 }}.
                        </td>

                        <td class="text-center">{{ $s->nama_shift ?? '-' }}</td>
                        <td class="text-center">{{ $s->jam_masuk ?? '-' }}</td>
                        <td class="text-center">{{ $s->jam_keluar ?? '-' }}</td>
                        <td class="text-center">{{ $s->jam_mulai_istirahat ?? '-' }}</td>
                        <td class="text-center">{{ $s->jam_selesai_istirahat ?? '-' }}</td>

                        <td class="text-center">
    <div class="dropdown">

        <button class="action-dropdown-btn"
                type="button"
                onclick="toggleDropdown(this, event)">
            <i class="fas fa-ellipsis-v"></i>
        </button>

        <ul class="dropdown-menu-template" style="display:none">

            @if($s->nama_shift == 'Libur')
                <li>
                    <span class="dropdown-item text-success">
                        <i class="fas fa-shield-alt"></i> Default Shift
                    </span>
                </li>
            @else
                <li>
                    <a class="dropdown-item" href="{{ url('/shift/'.$s->id.'/edit') }}">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </li>

                <li>
                    <form action="{{ url('/shift/'.$s->id) }}" method="post">
                        @method('delete')
                        @csrf
                        <button class="dropdown-item text-danger border-0 w-100 text-start"
                                onclick="return confirm('Are You Sure')">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </li>
            @endif

        </ul>
    </div>
</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-end px-3 pb-3">
        {{ $shift->links() }}
    </div>

</div>

</div>
</div>

<script>
function toggleDropdown(btn, event){
    event.stopPropagation();

    const menu = btn.closest('td').querySelector('.dropdown-menu-template');

    document.querySelectorAll('.dropdown-menu-template')
        .forEach(el => el.style.display = 'none');

    const rect = btn.getBoundingClientRect();

    menu.style.display = 'block';
    menu.style.position = 'fixed';
    menu.style.left = (rect.right - 180) + 'px';
    menu.style.top = rect.bottom + 'px';
}

document.addEventListener('click', function(){
    document.querySelectorAll('.dropdown-menu-template')
        .forEach(el => el.style.display = 'none');
});
</script>

@endsection