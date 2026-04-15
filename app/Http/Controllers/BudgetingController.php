<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;
use App\Models\Budgeting;
use App\Models\BudgetingsItem;

class BudgetingController extends Controller
{
    public function index()
    {
        $title = 'Budgeting';
        $mulai = request()->input('mulai');
        $akhir = request()->input('akhir');

        $budgeting = Budgeting::when($mulai && $akhir, function ($query) use ($mulai, $akhir) {
                        $query->whereBetween('tanggal', [$mulai, $akhir]);
                    })
                    ->when(auth()->user()->is_admin == 'user', function ($query) {
                        $query->where('user_id', auth()->user()->id)
                            ->orWhereHas('items', function ($query) {
                                $query->where('user_id', auth()->user()->id);
                            });
                    })
                    ->orderByRaw("
                        CASE 
                            WHEN status = 'Pending' THEN 1
                            WHEN status = 'Rejected' THEN 2
                            WHEN status = 'Approved' THEN 3
                        END
                    ")
                    ->orderBy('tanggal', 'DESC')
                                        ->paginate(10)
                                        ->withQueryString();

        if (auth()->user()->is_admin == 'admin') {
            return view('budgeting.index', compact('title', 'budgeting'));
        } else {
            return view('budgeting.indexUser', compact('title', 'budgeting'));
        }
    }

    public function tambah()
    {
        $title = 'Budgeting';
        $project = \App\Models\Project::where('status', 'Active')->orderBy('nama_po')->get();
        $user = User::orderBy('name', 'ASC')->get();
        $kategori = Kategori::orderBy('name', 'ASC')->where('active', 1)->get();

        if (auth()->user()->is_admin == 'admin') {
            return view('budgeting.tambah', compact('project','title', 'user', 'kategori'));
        } else {
            return view('budgeting.tambahUser', compact('project','title', 'user', 'kategori'));
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable',   // <-- tambahkan ini
            'tanggal'    => 'required',
            'user_id'    => 'required',
            'event'      => 'required',
            'kategori_id'=> 'required',
            'status'     => 'required',
            'jumlah'     => 'required',
            'file_path'  => 'required',
            'qty'        => 'required',
            'total'      => 'required',
            'sisa'       => 'required',
        ]);

        $validated['jumlah'] = str_replace(',', '', $validated['jumlah']);
        $validated['total']  = str_replace(',', '', $validated['total']);
        $validated['sisa']   = str_replace(',', '', $validated['sisa']);

        if ($request->file('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('budgeting_files');
            $validated['file_name'] = $request->file('file_path')->getClientOriginalName();
        }

        $budgeting = Budgeting::create($validated);

        $user_id_item = $request->input('user_id_item', []);
        $fee = $request->input('fee', []);

        for ($i = 0; $i < count($user_id_item); $i++) {
            BudgetingsItem::create([
                'budgeting_id' => $budgeting->id,
                'user_id'      => $user_id_item[$i],
                'fee'          => $fee[$i] ? str_replace(',', '', $fee[$i]) : 0,
            ]);
        }

        return redirect('/budgeting')->with('success', 'Data Berhasil Disimpan');
    }

    public function edit($id)
    {
        $budgeting = Budgeting::find($id);
        $title = 'Budgeting';
        $user = User::orderBy('name', 'ASC')->get();
        $kategori = Kategori::orderBy('name', 'ASC')->where('active', 1)->get();

        if (auth()->user()->is_admin == 'admin') {
            return view('budgeting.edit', compact('title', 'user', 'kategori', 'budgeting'));
        } else {
            return view('budgeting.editUser', compact('title', 'user', 'kategori', 'budgeting'));
        }
    }

    public function update(Request $request, $id)
    {
        $budgeting = Budgeting::find($id);

        $validated = $request->validate([
            'tanggal'    => 'required',
            'user_id'    => 'required',
            'event'      => 'required',
            'kategori_id'=> 'required',
            'status'     => 'required',
            'jumlah'     => 'required',
            'file_path'  => 'nullable',
            'qty'        => 'required',
            'total'      => 'required',
            'sisa'       => 'required',
        ]);

        $validated['jumlah'] = str_replace(',', '', $validated['jumlah']);
        $validated['total']  = str_replace(',', '', $validated['total']);
        $validated['sisa']   = str_replace(',', '', $validated['sisa']);

        if ($request->file('file_path')) {
            $validated['file_path'] = $request->file('file_path')->store('budgeting_files');
            $validated['file_name'] = $request->file('file_path')->getClientOriginalName();
        }

        $budgeting->update($validated);

        $user_id_item = $request->input('user_id_item', []);
        $fee = $request->input('fee', []);

        BudgetingsItem::where('budgeting_id', $budgeting->id)->delete();
        for ($i = 0; $i < count($user_id_item); $i++) {
            BudgetingsItem::create([
                'budgeting_id' => $budgeting->id,
                'user_id'      => $user_id_item[$i],
                'fee'          => $fee[$i] ? str_replace(',', '', $fee[$i]) : 0,
            ]);
        }

        return redirect('/budgeting')->with('success', 'Data Berhasil Diupdate');
    }

  
    public function approval(Request $request, $id)
    {
        $budgeting = Budgeting::find($id);
        $validated = $request->validate([
            'status'           => 'required',
            'jumlah_disetujui' => 'nullable|numeric',
            'alasan'           => 'nullable|string',
        ]);
        $budgeting->update($validated);
        return redirect('/budgeting')->with('success', 'Status Berhasil Diupdate');
        }

    public function delete($id)
    {
        $budgeting = Budgeting::find($id);
        $budgeting->delete();
        return redirect('/budgeting')->with('success', 'Data Berhasil Didelete');
    }

    public function getKategori(Request $request)
    {
        return Kategori::find($request->kategori_id);
    }

    public function show($id)
{
    $title = 'Detail Budgeting';
    $budgeting = Budgeting::with(['user', 'kategori', 'project', 'items.user'])->findOrFail($id);
    return view('budgeting.show', compact('title', 'budgeting'));
}

    
}