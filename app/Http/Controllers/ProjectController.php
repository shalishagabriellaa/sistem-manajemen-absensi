<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $title = 'Project';
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $project = Project::with(['user', 'reimbursements', 'budgetings'])
            ->when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
                $query->whereBetween('tanggal_po', [$mulai, $akhir]);
            })
            ->when(auth()->user()->is_admin == 'user', function ($query) {
                $query->where('user_id', auth()->user()->id);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate(10)
            ->withQueryString();

        return view('project.index', compact('title', 'project'));
    }

    public function tambah()
    {
        $title = 'Tambah Project';
        $user = User::orderBy('name', 'ASC')->get();
        return view('project.tambah', compact('title', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'         => 'required',
            'jenis_project'   => 'required',
            'tanggal_po'      => 'required|date',
            'tanggal_kontrak' => 'required|date',
            'no_po'           => 'required',
            'nama_po'         => 'required',
            'nilai_po'        => 'required',
            'no_kontrak'      => 'required',
            'nama_kontrak'    => 'required',
            'status'          => 'required',
        ]);

        $validated['nilai_po'] = str_replace(',', '', $validated['nilai_po']);

        Project::create($validated);

        return redirect('/project')->with('success', 'Project Berhasil Disimpan');
    }

    public function edit($id)
    {
        $title = 'Edit Project';
        $project = Project::findOrFail($id);
        $user = User::orderBy('name', 'ASC')->get();
        return view('project.edit', compact('title', 'project', 'user'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $validated = $request->validate([
            'user_id'         => 'required',
            'jenis_project'   => 'required',
            'tanggal_po'      => 'required|date',
            'tanggal_kontrak' => 'required|date',
            'no_po'           => 'required',
            'nama_po'         => 'required',
            'nilai_po'        => 'required',
            'no_kontrak'      => 'required',
            'nama_kontrak'    => 'required',
            'status'          => 'required',
        ]);

        $validated['nilai_po'] = str_replace(',', '', $validated['nilai_po']);

        $project->update($validated);

        return redirect('/project')->with('success', 'Project Berhasil Diupdate');
    }

    public function delete($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect('/project')->with('success', 'Project Berhasil Dihapus');
    }

    public function show($id)
    {
        $title = 'Detail Project';
        $project = Project::with(['user', 'reimbursements.user', 'reimbursements.kategori', 'budgetings.user', 'budgetings.kategori'])->findOrFail($id);
        return view('project.show', compact('title', 'project'));
    }

    public function getProjects()
    {
        return Project::where('status', 'Active')->orderBy('nama_po')->get(['id', 'nama_po', 'no_po']);
    }
}