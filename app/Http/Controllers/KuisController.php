<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Soal;
use App\Models\Kuis;
use App\Models\KuisJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

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

        if ($soal->isEmpty()) {
            return redirect()->route('kuis.index')->with('error', 'Tidak ada soal untuk mata kuliah ini.');
        }

        $kuis = Kuis::create([
            'user_id' => auth()->id(),
            'mata_kuliah_id' => $mataKuliahId,
            'tanggal_mulai' => now(),
        ]);

        // Simpan daftar soal ke sesi untuk urutan konsisten
        Session::put("kuis_{$kuis->id}_soal", $soal->pluck('id')->toArray());
        Session::put("kuis_{$kuis->id}_jawaban", []);

        return redirect()->route('kuis.soal', ['kuisId' => $kuis->id, 'index' => 0]);
    }

    public function soal($kuisId, $index)
    {
        $kuis = Kuis::findOrFail($kuisId);
        $soalIds = Session::get("kuis_{$kuisId}_soal", []);
        
        if (empty($soalIds) || $index < 0 || $index >= count($soalIds)) {
            return redirect()->route('kuis.index')->with('error', 'Soal tidak valid.');
        }

        $soal = Soal::findOrFail($soalIds[$index]);
        $totalSoal = count($soalIds);
        $jawaban = Session::get("kuis_{$kuisId}_jawaban", []);

        return view('lembar_soal', compact('kuis', 'soal', 'index', 'totalSoal', 'jawaban'));
    }

    public function simpanJawaban(Request $request, $kuisId, $index)
    {
        $request->validate([
            'jawaban' => 'required|in:A,B,C,D',
        ]);

        $soalIds = Session::get("kuis_{$kuisId}_soal", []);
        if ($index < 0 || $index >= count($soalIds)) {
            return redirect()->route('kuis.index')->with('error', 'Soal tidak valid.');
        }

        // Simpan jawaban ke sesi
        $jawaban = Session::get("kuis_{$kuisId}_jawaban", []);
        $jawaban[$soalIds[$index]] = $request->jawaban;
        Session::put("kuis_{$kuisId}_jawaban", $jawaban);

        $nextIndex = $index + 1;
        if ($nextIndex >= count($soalIds)) {
            return redirect()->route('kuis.submit', $kuisId);
        }

        return redirect()->route('kuis.soal', ['kuisId' => $kuisId, 'index' => $nextIndex]);
    }

    public function submit($kuisId)
    {
        $kuis = Kuis::findOrFail($kuisId);
        $jawaban = Session::get("kuis_{$kuisId}_jawaban", []);
        $soalIds = Session::get("kuis_{$kuisId}_soal", []);
        $skor = 0;

        foreach ($soalIds as $soalId) {
            if (!isset($jawaban[$soalId])) {
                continue; // Lewati jika belum dijawab
            }

            $soal = Soal::find($soalId);
            $benar = $soal->jawaban_benar === $jawaban[$soalId];

            if ($benar) $skor++;

            KuisJawaban::create([
                'kuis_id' => $kuis->id,
                'soal_id' => $soalId,
                'jawaban_user' => $jawaban[$soalId],
                'benar_salah' => $benar,
            ]);
        }

        $kuis->update([
            'tanggal_selesai' => now(),
            'skor' => $skor,
        ]);

        // Bersihkan sesi
        Session::forget("kuis_{$kuisId}_soal");
        Session::forget("kuis_{$kuisId}_jawaban");

        return redirect()->route('kuis.hasil', $kuis->id);
    }

    public function hasil($kuisId)
    {
        $kuis = Kuis::with('jawaban.soal')->findOrFail($kuisId);
        return view('kuis.hasil', compact('kuis'));
    }
}