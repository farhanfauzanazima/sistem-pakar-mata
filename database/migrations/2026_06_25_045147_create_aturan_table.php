<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('aturan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penyakit_id')->constrained('penyakit')->onDelete('cascade');
            $table->foreignId('gejala_id')->constrained('gejala')->onDelete('cascade');
            $table->decimal('cf_pakar', 3, 2);
            $table->timestamps();

            $table->unique(['penyakit_id', 'gejala_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aturan');
    }
};