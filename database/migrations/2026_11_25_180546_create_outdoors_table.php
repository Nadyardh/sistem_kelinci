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
        Schema::create('outdoors', function (Blueprint $table) {
            $table->id();
            $table->float('suhu');                 // Menyimpan suhu outdoor (°C), misal: 30.4
            $table->float('kelembapan');           // Menyimpan kelembapan outdoor (%), misal: 65
            $table->string('status_cuaca', 100);   // Deskripsi cuaca dari OpenWeather
            $table->timestamps();                  // Otomatis membuat created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('outdoors');
    }
};
