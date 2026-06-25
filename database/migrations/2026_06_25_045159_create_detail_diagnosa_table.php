<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detail_diagnosa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hasil_diagnosa_id')->constrained('hasil_diagnosa')->onDelete('cascade');
            $table->foreignId('gejala_id')->constrained('gejala')->onDelete('cascade');
            $table->string('frekuensi'); // sangat_sering, sering, jarang, tidak_pernah
            $table->decimal('cf_user', 3, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detail_diagnosa');
    }
};