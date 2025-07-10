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
        dd($subjects); // Debug
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
        'nama_mata_kuliah' => 'required|string|max:255|unique:mata_kuliah,nama_mata_kuliah',
        'ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'color' => 'nullable|string|max:255',
    ], [
        'nama_mata_kuliah.unique' => 'Nama mata kuliah sudah ada.',
    ]);

    if ($request->hasFile('ikon')) {
        $path = $request->file('ikon')->store('icons', 'public');
        $validated['ikon'] = $path;
    }

    MataKuliah::create($validated);
    $subjects = MataKuliah::all();
    return view('dashboard', compact('subjects'))->with('success', 'Mata kuliah berhasil ditambahkan!');
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
        'nama_mata_kuliah' => 'required|string|max:255|unique:mata_kuliah,nama_mata_kuliah,' . $mataKuliah->id,
        'ikon' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'color' => 'nullable|string|max:255',
    ], [
        'nama_mata_kuliah.unique' => 'Nama mata kuliah sudah ada.',
    ]);

    if ($request->hasFile('ikon')) {
        if ($mataKuliah->ikon) {
            Storage::disk('public')->delete($mataKuliah->ikon);
        }
        $path = $request->file('ikon')->store('icons', 'public');
        $validated['ikon'] = $path;
    }

    $mataKuliah->update($validated);
    $subjects = MataKuliah::all();
    return view('dashboard', compact('subjects'))->with('success', 'Mata kuliah berhasil diperbarui!');
}

public function destroy(MataKuliah $mataKuliah)
{
    $mataKuliah->soal()->delete();
    if ($mataKuliah->ikon) {
        Storage::disk('public')->delete($mataKuliah->ikon);
    }
    $mataKuliah->delete();
    $subjects = MataKuliah::all();
    return view('dashboard', compact('subjects'))->with('success', 'Mata kuliah berhasil dihapus!');
}
}