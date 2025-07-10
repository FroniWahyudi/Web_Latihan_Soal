<?php

namespace App\Http\Controllers;
use App\Models\Soal;
use App\Models\MataKuliah;
use App\Models\Kuis;
use App\Models\KuisJawaban; 


use Illuminate\Http\Request;

class SoalController extends Controller
{
    public function index()
    {
        return view('soal.index', [
            'soal' => Soal::with('mataKuliah')->get(),
        ]);
    }

    public function create()
    {
        return view('soal.create', [
            'mataKuliah' => MataKuliah::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required',
            'pertanyaan' => 'required',
            'pilihan_a' => 'required',
            'pilihan_b' => 'required',
            'pilihan_c' => 'required',
            'pilihan_d' => 'required',
            'jawaban_benar' => 'required|in:A,B,C,D',
        ]);

        Soal::create($request->all());

        return redirect()->route('soal.index')->with('success', 'Soal berhasil ditambahkan');
    }
}
