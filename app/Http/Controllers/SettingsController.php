<?php
namespace App\Http\Controllers;
use App\Models\settings;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $title = 'Settings';
        $data = settings::first();
        return view('settings.index', compact('title', 'data'));
    }

    public function store(Request $request)
    {
        $settings = settings::first();

        $validated = $request->validate([
            'name'         => 'required',
            'logo'         => 'image|file|max:10240|nullable',
            'alamat'       => 'nullable',
            'phone'        => 'nullable',
            'whatsapp'     => 'nullable',
            'api_url'      => 'nullable',
            'api_whatsapp' => 'nullable',
            'email'        => 'nullable',
        ]);

        if ($request->hasFile('logo')) {
            $file     = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            $folder   = $_SERVER['DOCUMENT_ROOT'] . '/uploads/logo';

            if (!file_exists($folder)) {
                mkdir($folder, 0755, true);
            }

            $file->move($folder, $filename);
            $validated['logo'] = 'uploads/logo/' . $filename;
        }

        $settings->update($validated);
        return back()->with('success', 'Data Berhasil Ditambahkan');
    }
}