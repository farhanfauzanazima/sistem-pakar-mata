<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hasil_diagnosa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pasien_id')->constrained('pasien')->onDelete('cascade');
            $table->foreignId('penyakit_id')->constrained('penyakit')->onDelete('cascade');
            $table->decimal('cf_hasil', 5, 4);
            $table->integer('cf_persen');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hasil_diagnosa');
    }
};