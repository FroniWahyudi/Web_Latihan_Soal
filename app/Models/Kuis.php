<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuis extends Model
{
    use HasFactory;

    protected $table = 'kuis';

    protected $fillable = [
        'user_id', 'mata_kuliah_id', 'tanggal_mulai', 'tanggal_selesai', 'skor'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mataKuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }

    public function jawaban()
    {
        return $this->hasMany(KuisJawaban::class);
    }
}
