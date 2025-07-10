<?php

namespace App\Http\Controllers;

use App\Models\Soal;
use App\Models\MataKuliah;
use App\Models\Kuis;
use App\Models\KuisJawaban;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoalController extends Controller
{
    public function index()
{
    return view('dashboard', [
        'soal' => Soal::with('mataKuliah')->get(),
        'subjects' => MataKuliah::all(), // Tambahkan ini
    ]);
}

    public function create()
    {
        return view('buat_soal', [ // Pastikan nama view adalah 'buat_soal'
            'mataKuliah' => MataKuliah::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mata_kuliah_id' => 'required|exists:mata_kuliah,id',
            'questions' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lines = array_filter(array_map('trim', explode("\n", $request->questions)));
        $questions = [];
        $currentQuestion = null;

        foreach ($lines as $line) {
            if (str_starts_with($line, 'Q:')) {
                if ($currentQuestion && $currentQuestion['pertanyaan'] && count($currentQuestion['pilihan']) === 4) {
                    $questions[] = $currentQuestion;
                }
                $currentQuestion = [
                    'pertanyaan' => trim(substr($line, 2)),
                    'pilihan' => [],
                    'jawaban_benar' => null,
                ];
            } elseif (preg_match('/^[A-D]:/', $line)) {
                if ($currentQuestion) {
                    $optionText = trim(substr($line, 2));
                    $isCorrect = str_contains($optionText, '(correct)');
                    $cleanText = trim(str_replace('(correct)', '', $optionText));
                    $optionLetter = substr($line, 0, 1);
                    $currentQuestion['pilihan'][$optionLetter] = $cleanText;
                    if ($isCorrect) {
                        $currentQuestion['jawaban_benar'] = $optionLetter;
                    }
                }
            }
        }

        if ($currentQuestion && $currentQuestion['pertanyaan'] && count($currentQuestion['pilihan']) === 4) {
            $questions[] = $currentQuestion;
        }

        $validQuestions = array_filter($questions, fn($q) => !is_null($q['jawaban_benar']));

        if (empty($validQuestions)) {
            return redirect()->back()->with('error', 'Tidak ada soal valid untuk disimpan!')->withInput();
        }

        foreach ($validQuestions as $q) {
            Soal::create([
                'mata_kuliah_id' => $request->mata_kuliah_id,
                'pertanyaan' => $q['pertanyaan'],
                'pilihan_a' => $q['pilihan']['A'],
                'pilihan_b' => $q['pilihan']['B'],
                'pilihan_c' => $q['pilihan']['C'],
                'pilihan_d' => $q['pilihan']['D'],
                'jawaban_benar' => $q['jawaban_benar'],
            ]);
        }

        return redirect()->back()->with('success', count($validQuestions) . ' soal berhasil disimpan ke database!');
    }
}