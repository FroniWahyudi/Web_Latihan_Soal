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
            'skor' => 0,
        ]);

        // Simpan daftar soal ke sesi
        $soalIds = $soal->pluck('id')->toArray();
        Session::put("kuis_{$kuis->id}_soal", $soalIds);
        Session::put("kuis_{$kuis->id}_jawaban", []);

        Log::info('Kuis dimulai', ['kuis_id' => $kuis->id, 'mata_kuliah_id' => $mataKuliahId, 'soal_count' => count($soalIds)]);

        return redirect()->route('kuis.soal', ['kuisId' => $kuis->id, 'index' => 0]);
    }

    public function soal($kuisId, $index)
    {
        $kuis = Kuis::findOrFail($kuisId);
        $soalIds = Session::get("kuis_{$kuisId}_soal", []);
        
        if (empty($soalIds) || $index < 0 || $index >= count($soalIds)) {
            Log::warning('Soal tidak valid', ['kuis_id' => $kuisId, 'index' => $index]);
            return redirect()->route('kuis.hasil', $kuisId)->with('error', 'Soal tidak valid atau kuis telah selesai.');
        }

        $soal = Soal::findOrFail($soalIds[$index]);
        $totalSoal = count($soalIds);
        $jawaban = KuisJawaban::where('kuis_id', $kuisId)->get()->keyBy('soal_id')->toArray();

        return view('lembar_soal', compact('kuis', 'soal', 'index', 'totalSoal', 'jawaban'));
    }

    public function simpanJawaban(Request $request, $kuisId, $index)
    {
        try {
            $request->validate([
                'jawaban' => 'required|in:A,B,C,D',
            ]);

            $kuis = Kuis::findOrFail($kuisId);
            $soalIds = Session::get("kuis_{$kuisId}_soal", []);
            if ($index < 0 || $index >= count($soalIds)) {
                Log::warning('Index soal tidak valid', ['kuis_id' => $kuisId, 'index' => $index]);
                return response()->json(['error' => 'Soal tidak valid.'], 400);
            }

            $soal = Soal::findOrFail($soalIds[$index]);
            $isCorrect = $request->jawaban === $soal->jawaban_benar;

            // Simpan jawaban
            $jawaban = KuisJawaban::updateOrCreate(
                ['kuis_id' => $kuisId, 'soal_id' => $soal->id],
                ['jawaban_user' => $request->jawaban, 'benar_salah' => $isCorrect]
            );

            // Perbarui sesi jawaban
            $jawabanSesi = Session::get("kuis_{$kuisId}_jawaban", []);
            $jawabanSesi[$soal->id] = [
                'jawaban_user' => $request->jawaban,
                'benar_salah' => $isCorrect,
            ];
            Session::put("kuis_{$kuisId}_jawaban", $jawabanSesi);

            // Perbarui skor secara langsung
            $skor = KuisJawaban::where('kuis_id', $kuisId)->where('benar_salah', true)->count();
            $kuis->update(['skor' => $skor]);

            $nextIndex = $index + 1;
            $nextUrl = $nextIndex < count($soalIds)
                ? route('kuis.soal', ['kuisId' => $kuisId, 'index' => $nextIndex])
                : route('kuis.hasil', $kuisId);

            Log::info('Jawaban disimpan', [
                'kuis_id' => $kuisId,
                'soal_id' => $soal->id,
                'jawaban_user' => $request->jawaban,
                'benar_salah' => $isCorrect,
                'skor' => $skor,
                'next_url' => $nextUrl,
            ]);

            return response()->json([
                'is_correct' => $isCorrect,
                'correct_answer' => $soal->jawaban_benar,
                'next_url' => $nextUrl,
            ]);
        } catch (\Exception $e) {
            Log::error('Simpan jawaban gagal', ['kuis_id' => $kuisId, 'index' => $index, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menyimpan jawaban: ' . $e->getMessage()], 500);
        }
    }

    public function submit($kuisId)
    {
        try {
            $kuis = Kuis::findOrFail($kuisId);
            $jawabanKuis = KuisJawaban::where('kuis_id', $kuisId)->get();
            $totalSoal = count(Session::get("kuis_{$kuisId}_soal", []));

            if ($jawabanKuis->count() < $totalSoal) {
                Log::warning('Kuis belum selesai', ['kuis_id' => $kuisId, 'jawaban_count' => $jawabanKuis->count(), 'total_soal' => $totalSoal]);
                return response()->json(['error' => 'Harap jawab semua soal sebelum menyelesaikan kuis.'], 400);
            }

            $skor = $jawabanKuis->where('benar_salah', true)->count();
            $kuis->update([
                'tanggal_selesai' => now(),
                'skor' => $skor,
            ]);

            // Bersihkan sesi
            Session::forget("kuis_{$kuisId}_soal");
            Session::forget("kuis_{$kuisId}_jawaban");

            Log::info('Kuis selesai', ['kuis_id' => $kuisId, 'skor' => $skor, 'jawaban_count' => $jawabanKuis->count()]);

            return response()->json([
                'redirect_url' => route('kuis.hasil', $kuisId),
            ]);
        } catch (\Exception $e) {
            Log::error('Submit kuis gagal', ['kuis_id' => $kuisId, 'error' => $e->getMessage()]);
            return response()->json(['error' => 'Gagal menyelesaikan kuis: ' . $e->getMessage()], 500);
        }
    }

    public function hasil($kuisId)
    {
        $kuis = Kuis::with(['jawaban.soal', 'mataKuliah'])->findOrFail($kuisId);
        $mataKuliah = MataKuliah::all();

        // Proses data untuk view
        $data = [
            'kuis' => $kuis,
            'mataKuliah' => $mataKuliah,
            'skor' => $kuis->skor ?? 0,
            'incorrect' => $kuis->jawaban->count() - ($kuis->skor ?? 0),
            'persentase' => $kuis->jawaban->count() > 0 
                ? round(($kuis->skor ?? 0) / $kuis->jawaban->count() * 100, 1) 
                : 0,
            'isPerfect' => ($kuis->skor ?? 0) == $kuis->jawaban->count() && $kuis->jawaban->count() > 0,
            'correctCardClass' => ($kuis->skor ?? 0) > 0 ? 'pulse-green' : '',
            'incorrectCardClass' => ($kuis->jawaban->count() - ($kuis->skor ?? 0)) > 0 ? 'pulse-red' : '',
        ];

        Log::info('Menampilkan hasil kuis', [
            'kuis_id' => $kuisId,
            'skor' => $data['skor'],
            'jawaban_count' => $kuis->jawaban->count(),
            'persentase' => $data['persentase'],
            'is_perfect' => $data['isPerfect'],
            'correct_card_class' => $data['correctCardClass'],
            'incorrect_card_class' => $data['incorrectCardClass'],
        ]);

        return view('hasil', $data);
    }
}
