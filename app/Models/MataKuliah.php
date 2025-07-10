<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    protected $table = 'mata_kuliah'; // Tentukan nama tabel secara eksplisit
    protected $fillable = ['nama_mata_kuliah', 'ikon', 'color'];

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }
}