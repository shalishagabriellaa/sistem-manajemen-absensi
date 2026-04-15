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

/* CARD */
.form-card{
    border-radius:16px;
    border:1px solid var(--slate-200);
    box-shadow:0 6px 20px rgba(0,0,0,0.06);
    overflow:hidden;
}

/* HEADER */
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

/* SEARCH */
.search-box{
    padding:20px;
    border-bottom:1px solid var(--slate-200);
}

.search-input{
    border-radius:8px;
    font-size:14px;
    height:38px;
    border:1px solid var(--slate-200);
}

.search-input:focus{
    border-color:var(--dash-blue);
    box-shadow:0 0 0 0.15rem rgba(59,76,202,0.15);
}

.search-btn{
    background:transparent;
    border:none;
    color:var(--slate-500);
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
    border-bottom:1px solid var(--slate-200);
}

.table td{
    font-size:14px;
    color:var(--slate-700);
    vertical-align:middle;
}

/* STICKY */
.sticky-left{
    position:sticky;
    left:0;
    background:#fff;
    z-index:2;
}

.sticky-left-2{
    position:sticky;
    left:50px;
    background:#fff;
    z-index:2;
}

.sticky-right{
    position:sticky;
    right:0;
    background:#fff;
    z-index:2;
}

/* DISABLED ACTION */
.action-disabled{
    cursor:not-allowed;
    opacity:.4;
    color:var(--slate-500);
    transition:.2s;
}

.action-disabled:hover{
    opacity:.6;
}

/* PAGINATION */
.pagination{
    margin-top:20px;
}
</style>

<br>

<div class="row">
    <div class="col-md-12">

        <div class="card form-card">

            {{-- HEADER --}}
            <div class="form-header">
                <div class="form-title">
                    <i class="fas fa-user-shield" style="color:#3b4cca"></i>
                    {{ $title }}
                </div>
            </div>

            {{-- SEARCH --}}
            <div class="search-box">
                <form action="{{ url('/role') }}">
                    <div class="row">
                        <div class="col-11">
                            <input type="text"
                                   class="form-control search-input"
                                   name="search"
                                   placeholder="Search role..."
                                   value="{{ request('search') }}">
                        </div>
                        <div class="col-1 text-center">
                            <button type="submit" class="search-btn mt-2">
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
                            <th class="text-center sticky-left">No.</th>
                            <th class="text-center sticky-left-2">Nama Role</th>
                            <th class="text-center">Guard</th>
                            <th class="text-center sticky-right">Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        @if (count($roles) <= 0)
                            <tr>
                                <td colspan="10" class="text-center text-muted">
                                    Tidak Ada Data
                                </td>
                            </tr>
                        @else
                            @foreach ($roles as $key => $role)
                                <tr>
                                    <td class="text-center sticky-left">
                                        {{ ($roles->currentpage() - 1) * $roles->perpage() + $key + 1 }}.
                                    </td>

                                    <td class="text-center sticky-left-2">
                                        {{ $role->name ?? '-' }}
                                    </td>

                                    <td class="text-center">
                                        {{ $role->guard_name ?? '-' }}
                                    </td>

                                    <td class="text-center sticky-right">

                                        <span class="action-disabled" title="Edit role dinonaktifkan">
                                            <i class="fa fa-edit"></i>
                                        </span>

                                        <span class="action-disabled ms-2" title="Hapus role dinonaktifkan">
                                            <i class="fa fa-trash"></i>
                                        </span>

                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>

            </div>

            {{-- PAGINATION --}}
            <div class="d-flex justify-content-end px-3 pb-3">
                {{ $roles->links() }}
            </div>

        </div>

    </div>
</div>

@endsection