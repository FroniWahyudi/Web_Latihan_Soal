<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMataKuliahTable extends Migration
{
    public function up()
    {
        Schema::create('mata_kuliah', function (Blueprint $table) {
            $table->id();
            $table->string('nama_mata_kuliah');
            $table->string('ikon')->nullable(); // Kolom ikon opsional
            $table->string('color')->nullable(); // Kolom color opsional
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('mata_kuliah');
    }
}