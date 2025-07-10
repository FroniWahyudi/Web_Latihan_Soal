<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table = 'mata_kuliah';

    protected $fillable = ['nama_mata_kuliah', 'ikon'];

    public function soal()
    {
        return $this->hasMany(Soal::class);
    }

    public function kuis()
    {
        return $this->hasMany(Kuis::class);
    }
}
