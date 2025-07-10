<?php

namespace App\Http\Controllers;
use App\Models\MataKuliah;
use App\Models\Soal;
use App\Models\Kuis;
use App\Models\KuisJawaban;

use Illuminate\Http\Request;

class KuisController extends Controller
{
    public function index()
    {
        $mataKuliah = MataKuliah::withCount('soal')->get();
        return view('kuis.index', compact('mataKuliah'));
    }

    public function mulai($mataKuliahId)
    {
        $soal = Soal::where('mata_kuliah_id', $mataKuliahId)->inRandomOrder()->get();

        $kuis = Kuis::create([
            'user_id' => auth()->id(),
            'mata_kuliah_id' => $mataKuliahId,
            'tanggal_mulai' => now(),
        ]);

        return view('kuis.lembar_soal', compact('soal', 'kuis'));
    }

    public function submit(Request $request, $kuisId)
    {
        $kuis = Kuis::findOrFail($kuisId);
        $skor = 0;

        foreach ($request->jawaban as $soalId => $jawabanUser) {
            $soal = Soal::find($soalId);
            $benar = $soal->jawaban_benar === $jawabanUser;

            if ($benar) $skor++;

            KuisJawaban::create([
                'kuis_id' => $kuis->id,
                'soal_id' => $soalId,
                'jawaban_user' => $jawabanUser,
                'benar_salah' => $benar,
            ]);
        }

        $kuis->update([
            'tanggal_selesai' => now(),
            'skor' => $skor,
        ]);

        return redirect()->route('kuis.hasil', $kuis->id);
    }

    public function hasil($kuisId)
    {
        $kuis = Kuis::with('jawaban.soal')->findOrFail($kuisId);
        return view('kuis.hasil', compact('kuis'));
    }
}
