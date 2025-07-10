<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuisJawaban extends Model
{
    use HasFactory;

    protected $table = 'kuis_jawaban';

    protected $fillable = ['kuis_id', 'soal_id', 'jawaban_user', 'benar_salah'];

    public function kuis()
    {
        return $this->belongsTo(Kuis::class);
    }

    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}
