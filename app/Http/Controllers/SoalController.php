<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SoalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index()
{
    $soals = Soal::with('mataKuliah')->get();
    $subjects = MataKuliah::all(); // Tambahkan ini untuk mengirim $subjects
    return view('dashboard', compact('soals', 'subjects'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mataKuliah = MataKuliah::all();
        return view('buat_soal', compact('mataKuliah'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'mata_kuliah_id' => 'required',
            'custom_mata_kuliah' => 'required_if:mata_kuliah_id,custom|string|max:255|unique:mata_kuliah,nama_mata_kuliah',
            'questions' => 'required|string',
        ], [
            'mata_kuliah_id.required' => 'Pilih mata kuliah atau masukkan nama kustom.',
            'custom_mata_kuliah.required_if' => 'Nama mata kuliah kustom wajib diisi saat memilih "Tulis Sendiri".',
            'custom_mata_kuliah.unique' => 'Nama mata kuliah kustom sudah ada.',
            'questions.required' => 'Soal tidak boleh kosong.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Tangani mata kuliah
        $mataKuliahId = $request->mata_kuliah_id;
        if ($mataKuliahId === 'custom') {
            // Buat mata kuliah baru
            $mataKuliah = MataKuliah::create([
                'nama_mata_kuliah' => $request->custom_mata_kuliah,
            ]);
            $mataKuliahId = $mataKuliah->id;
        } else {
            // Validasi bahwa mata_kuliah_id ada di database
            if (!MataKuliah::find($mataKuliahId)) {
                return redirect()->back()
                    ->withErrors(['mata_kuliah_id' => 'Mata kuliah yang dipilih tidak valid.'])
                    ->withInput();
            }
        }

        // Parse dan simpan soal
        $questions = $this->parseQuestions($request->questions);
        if (empty($questions)) {
            return redirect()->back()
                ->withErrors(['questions' => 'Tidak ada soal valid yang dapat diproses. Pastikan format soal benar.'])
                ->withInput();
        }

        foreach ($questions as $question) {
            if (count($question['options']) !== 4 || $question['correct'] === null) {
                return redirect()->back()
                    ->withErrors(['questions' => 'Format soal tidak valid. Pastikan setiap soal memiliki 4 pilihan dan satu jawaban benar ditandai (correct).'])
                    ->withInput();
            }

            Soal::create([
                'mata_kuliah_id' => $mataKuliahId,
                'pertanyaan' => $question['question'],
                'pilihan_a' => $question['options'][0],
                'pilihan_b' => $question['options'][1],
                'pilihan_c' => $question['options'][2],
                'pilihan_d' => $question['options'][3],
                'jawaban_benar' => $question['correct'],
            ]);
        }

        return redirect()->route('soal.index')->with('success', 'Soal berhasil disimpan!');
    }

    /**
     * Parse questions input into structured data.
     */
    private function parseQuestions($input)
    {
        $questions = [];
        $lines = explode("\n", $input);
        $currentQuestion = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            if (str_starts_with($line, 'Q:')) {
                if ($currentQuestion && count($currentQuestion['options']) === 4) {
                    $questions[] = $currentQuestion;
                }
                $currentQuestion = [
                    'question' => trim(substr($line, 2)),
                    'options' => [],
                    'correct' => null,
                ];
            } elseif (preg_match('/^[A-D]:/', $line)) {
                if ($currentQuestion) {
                    $option = trim(substr($line, 2));
                    $isCorrect = str_contains($option, '(correct)');
                    $cleanOption = str_replace('(correct)', '', $option);
                    $currentQuestion['options'][] = trim($cleanOption);
                    if ($isCorrect) {
                        $currentQuestion['correct'] = count($currentQuestion['options']) - 1;
                    }
                }
            }
        }

        if ($currentQuestion && count($currentQuestion['options']) === 4) {
            $questions[] = $currentQuestion;
        }

        return $questions;
    }
}