<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoalController extends Controller
{
    public function create()
    {
        $mataKuliah = MataKuliah::all();
        return view('soal.buat_soal', compact('mataKuliah'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mata_kuliah_id' => 'required_unless:mata_kuliah_id,custom',
            'custom_mata_kuliah' => 'required_if:mata_kuliah_id,custom|string|max:255',
            'questions' => 'required|string',
        ]);

        try {
            DB::beginTransaction();

            // Handle custom mata kuliah
            $mataKuliahId = $request->mata_kuliah_id;
            if ($request->mata_kuliah_id === 'custom') {
                $mataKuliah = MataKuliah::create([
                    'nama_mata_kuliah' => $request->custom_mata_kuliah,
                ]);
                $mataKuliahId = $mataKuliah->id;
            }

            // Parse questions
            $lines = array_filter(array_map('trim', explode("\n", $request->questions)));
            $questions = [];
            $currentQuestion = null;

            foreach ($lines as $line) {
                if (str_starts_with($line, 'Q:')) {
                    if ($currentQuestion && $currentQuestion['question'] && count($currentQuestion['options']) === 4) {
                        $questions[] = $currentQuestion;
                    }
                    $currentQuestion = [
                        'question' => trim(substr($line, 2)),
                        'options' => [],
                        'correct' => null,
                    ];
                } elseif (preg_match('/^[A-D]:/', $line)) {
                    if ($currentQuestion) {
                        $optionText = trim(substr($line, 2));
                        $isCorrect = str_contains($optionText, '(correct)');
                        $cleanText = str_replace('(correct)', '', $optionText);
                        $currentQuestion['options'][] = $cleanText;
                        if ($isCorrect) {
                            $currentQuestion['correct'] = count($currentQuestion['options']) - 1;
                        }
                    }
                }
            }

            if ($currentQuestion && $currentQuestion['question'] && count($currentQuestion['options']) === 4) {
                $questions[] = $currentQuestion;
            }

            // Validate questions
            if (empty($questions)) {
                return redirect()->back()->with('error', 'Tidak ada soal valid yang dimasukkan.');
            }

            foreach ($questions as $q) {
                if ($q['correct'] === null) {
                    return redirect()->back()->with('error', 'Setiap soal harus memiliki satu jawaban benar dengan tanda (correct).');
                }

                Soal::create([
                    'mata_kuliah_id' => $mataKuliahId,
                    'pertanyaan' => $q['question'],
                    'pilihan_a' => $q['options'][0],
                    'pilihan_b' => $q['options'][1],
                    'pilihan_c' => $q['options'][2],
                    'pilihan_d' => $q['options'][3],
                    'jawaban_benar' => chr(65 + $q['correct']), // Konversi 0->A, 1->B, dst.
                ]);
            }

            DB::commit();
            return redirect()->route('dashboard')->with('success', 'Soal berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menyimpan soal: ' . $e->getMessage());
        }
    }
}