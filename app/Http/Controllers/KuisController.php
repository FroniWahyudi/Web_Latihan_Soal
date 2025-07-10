<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Soal;
use App\Models\Kuis;
use App\Models\KuisJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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
    try {
        $request->validate([
            'jawaban' => 'required|in:A,B,C,D',
        ]);

        $soalIds = Session::get("kuis_{$kuisId}_soal", []);
        if ($index < 0 || $index >= count($soalIds)) {
            return response()->json(['error' => 'Soal tidak valid.'], 400);
        }

        $soal = Soal::findOrFail($soalIds[$index]);
        $isCorrect = $request->jawaban === $soal->jawaban_benar;

        KuisJawaban::updateOrCreate(
            ['kuis_id' => $kuisId, 'soal_id' => $soal->id],
            ['jawaban_user' => $request->jawaban, 'benar_salah' => $isCorrect]
        );

        $nextIndex = $index + 1;
        $nextUrl = $nextIndex < count($soalIds)
            ? route('kuis.soal', ['kuisId' => $kuisId, 'index' => $nextIndex])
            : route('kuis.hasil', $kuisId); // Pastikan menggunakan kuis.hasil
        Log::info('Next URL generated: ' . $nextUrl);

        return response()->json([
            'is_correct' => $isCorrect,
            'correct_answer' => $soal->jawaban_benar,
            'next_url' => $nextUrl,
        ]);
    } catch (\Exception $e) {
        Log::error('Simpan jawaban gagal', ['kuisId' => $kuisId, 'index' => $index, 'error' => $e->getMessage()]);
        return response()->json(['error' => 'Gagal menyimpan jawaban: ' . $e->getMessage()], 500);
    }
}

 public function submit($kuisId)
{
    try {
        $kuis = Kuis::findOrFail($kuisId);
        $jawabanKuis = KuisJawaban::where('kuis_id', $kuisId)->get();
        $skor = $jawabanKuis->where('benar_salah', true)->count();

        $kuis->update([
            'tanggal_selesai' => now(),
            'skor' => $skor,
        ]);

        // Bersihkan sesi
        Session::forget("kuis_{$kuisId}_soal");
        Session::forget("kuis_{$kuisId}_jawaban");

        return response()->json([
            'redirect_url' => route('kuis.hasil', $kuisId),
        ]);
    } catch (\Exception $e) {
        Log::error('Submit kuis gagal', [
            'kuisId' => $kuisId,
            'error' => $e->getMessage(),
        ]);
        return response()->json([
            'error' => 'Gagal menyelesaikan kuis: ' . $e->getMessage(),
        ], 500);
    }
}
   public function hasil($kuisId)
{
    $kuis = Kuis::with('jawaban.soal')->findOrFail($kuisId);
    $mataKuliah = MataKuliah::all(); // Ambil semua mata kuliah
    return view('hasil', compact('kuis', 'mataKuliah'));
}
}