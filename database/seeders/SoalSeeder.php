<?php

namespace Database\Seeders;

use App\Models\Soal;
use App\Models\MataKuliah;
use Illuminate\Database\Seeder;

class SoalSeeder extends Seeder
{
    public function run(): void
    {
        $mataKuliahIds = MataKuliah::pluck('id')->toArray();

        foreach ($mataKuliahIds as $mataKuliahId) {
            Soal::create([
                'mata_kuliah_id' => $mataKuliahId,
                'pertanyaan' => 'Dalam Matematika Diskrit, apa itu himpunan?',
                'pilihan_a' => 'Kumpulan bilangan bulat',
                'pilihan_b' => 'Kumpulan objek tertentu yang terdefinisi dengan jelas',
                'pilihan_c' => 'Kumpulan fungsi matematika',
                'pilihan_d' => 'Kumpulan variabel acak',
                'jawaban_benar' => 'B',
            ]);

            Soal::create([
                'mata_kuliah_id' => $mataKuliahId,
                'pertanyaan' => 'Dalam Pemrograman Web, tag HTML apa yang digunakan untuk membuat tautan?',
                'pilihan_a' => '<link>',
                'pilihan_b' => '<a>',
                'pilihan_c' => '<href>',
                'pilihan_d' => '<url>',
                'jawaban_benar' => 'B',
            ]);

            Soal::create([
                'mata_kuliah_id' => $mataKuliahId,
                'pertanyaan' => 'Dalam Basis Data, apa fungsi utama perintah SQL SELECT?',
                'pilihan_a' => 'Menghapus data',
                'pilihan_b' => 'Menyisipkan data',
                'pilihan_c' => 'Mengambil data',
                'pilihan_d' => 'Memperbarui data',
                'jawaban_benar' => 'C',
            ]);
        }
    }
}