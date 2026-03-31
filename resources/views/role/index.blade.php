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
                        <!-- Role management disabled - no add/edit allowed -->
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <form action="{{ url('/role') }}">
                        <div class="row mb-2">
                            <div class="col-11">
                                <input type="text" class="form-control" name="search" placeholder="search.." id="search" value="{{ request('search') }}">
                            </div>
                            <div class="col-1">
                                <button type="submit" id="search"class="border-0 mt-3" style="background-color: transparent;"><i class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive" style="border-radius: 10px">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th class="text-center" style="position: sticky; left: 0; background-color: rgb(215, 215, 215); z-index: 2;">No.</th>
                                    <th style="position: sticky; left: 40px; background-color: rgb(215, 215, 215); z-index: 2; min-width: 230px;" class="text-center">Nama Role</th>
                                    <th style="min-width: 170px; background-color:rgb(243, 243, 243);" class="text-center">Guard</centh>
                                    <th class="text-center" style="position: sticky; right: 0; background-color: rgb(215, 215, 215); z-index: 2;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (count($roles) <= 0)
                                    <tr>
                                        <td colspan="10" class="text-center">Tidak Ada Data</td>
                                    </tr>
                                @else
                                    @foreach ($roles as $key => $role)
                                        <tr>
                                            <td class="text-center" style="position: sticky; left: 0; background-color: rgb(235, 235, 235); z-index: 1;">{{ ($roles->currentpage() - 1) * $roles->perpage() + $key + 1 }}.</td>
                                            <td class="text-center" style="position: sticky; left: 40px; background-color: rgb(235, 235, 235); z-index: 1;">{{ $role->name ?? '-' }}</td>
                                            <td class="text-center">{{ $role->guard_name ?? '-' }}</td>
                                            <td class="text-center" style="position: sticky; right: 0; background-color: rgb(235, 235, 235); z-index: 1;">
                                                <!-- Edit and Delete disabled -->
                                                <span class="text-muted" title="Edit role dinonaktifkan" style="cursor: not-allowed; opacity: 0.5;"><i class="fa fa-solid fa-edit"></i></span>
                                                <span class="text-muted ms-2" title="Hapus role dinonaktifkan" style="cursor: not-allowed; opacity: 0.5;"><i class="fa fa-solid fa-trash"></i></span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        {{ $roles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
@endsection
