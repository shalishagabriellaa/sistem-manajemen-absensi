<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $title = 'Role';
        $search = request()->input('search');

        $roles = Role::when($search, function ($query) use ($search) {
            return $query->where('name', 'LIKE', '%'.$search.'%');
        })
        ->orderBy('name', 'ASC')
        ->paginate(10)
        ->withQueryString();

        return view('role.index', compact(
            'title',
            'roles',
        ));
    }

    public function tambah()
    {
        return redirect('/role')->with('error', 'Fitur tambah role telah dinonaktifkan untuk menjaga konsistensi sistem.');
    }

    public function store(Request $request)
    {
        return redirect('/role')->with('error', 'Fitur tambah role telah dinonaktifkan untuk menjaga konsistensi sistem.');
    }

    public function edit($id)
    {
        return redirect('/role')->with('error', 'Fitur edit role telah dinonaktifkan untuk menjaga konsistensi sistem.');
    }

    public function update(Request $request, $id)
    {
        return redirect('/role')->with('error', 'Fitur edit role telah dinonaktifkan untuk menjaga konsistensi sistem.');
    }

    public function delete($id)
    {
        return redirect('/role')->with('error', 'Fitur hapus role telah dinonaktifkan untuk menjaga konsistensi sistem.');
    }
}
