<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{
    public function run()
    {
        MataKuliah::create([
            'nama_mata_kuliah' => 'Matematika Diskrit',
            'ikon' => 'math-icon.png',
            'color' => 'from-blue-500 to-blue-600'
        ]);
        MataKuliah::create([
            'nama_mata_kuliah' => 'Pemrograman Web',
            'ikon' => 'web-icon.png',
            'color' => 'from-green-500 to-green-600'
        ]);
        MataKuliah::create([
            'nama_mata_kuliah' => 'Basis Data',
            'ikon' => 'database-icon.png',
            'color' => 'from-purple-500 to-purple-600'
        ]);
        // Tambahkan entri lain sesuai kebutuhan
    }
}