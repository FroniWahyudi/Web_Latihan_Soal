<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class MataKuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $subjects = MataKuliah::all();
        Log::info('MataKuliahController::index - Subjects count: ' . $subjects->count());
        return view('dashboard', compact('subjects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('mata_kuliah.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_mata_kuliah' => 'required|string|max:255',
            'ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Maksimal 2MB
            'color' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('ikon')) {
            $path = $request->file('ikon')->store('icons', 'public');
            $validated['ikon'] = $path;
        }

        MataKuliah::create($validated);

        return redirect()->route('dashboard')->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.show', compact('mataKuliah'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MataKuliah $mataKuliah)
    {
        return view('mata_kuliah.edit', compact('mataKuliah'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MataKuliah $mataKuliah)
    {
        $validated = $request->validate([
            'nama_mata_kuliah' => 'required|string|max:255',
            'ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'color' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('ikon')) {
            // Hapus ikon lama jika ada
            if ($mataKuliah->ikon) {
                Storage::disk('public')->delete($mataKuliah->ikon);
            }
            $path = $request->file('ikon')->store('icons', 'public');
            $validated['ikon'] = $path;
        }

        $mataKuliah->update($validated);

        return redirect()->route('dashboard')->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MataKuliah $mataKuliah)
    {
        // Hapus soal terkait
        $mataKuliah->soal()->delete();
        // Hapus ikon jika ada
        if ($mataKuliah->ikon) {
            Storage::disk('public')->delete($mataKuliah->ikon);
        }
        $mataKuliah->delete();

        return redirect()->route('dashboard')->with('success', 'Mata kuliah berhasil dihapus!');
    }
}