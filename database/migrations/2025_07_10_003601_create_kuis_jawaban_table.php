<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kuis_jawaban', function (Blueprint $table) {
    $table->id();
    $table->foreignId('kuis_id')->constrained('kuis')->onDelete('cascade');
    $table->foreignId('soal_id')->constrained('soal')->onDelete('cascade');
    $table->char('jawaban_user', 1); // A, B, C, atau D
    $table->boolean('benar_salah');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuis_jawaban');
    }
};
