<?php

namespace App\Http\Controllers;
use App\Models\MataKuliah;
use App\Models\Soal;
use App\Models\Kuis;
use App\Models\KuisJawaban;

use Illuminate\Http\Request;    

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard', [
            'totalMataKuliah' => MataKuliah::count(),
            'totalSoal' => Soal::count(),
            'totalKuisSelesai' => Kuis::whereNotNull('tanggal_selesai')->count(),
            'mataKuliah' => MataKuliah::withCount('soal')->get(),
        ]);
    }
}
