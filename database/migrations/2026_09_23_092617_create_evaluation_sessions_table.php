<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->unique()->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->time('waktu')->nullable();
            $table->string('durasi')->nullable(); // ex: "45 menit"
            $table->text('tujuan')->nullable();
            $table->text('konteks')->nullable();
            $table->string('lingkungan')->nullable();
            $table->string('perangkat')->nullable();
            $table->text('kondisi_penggunaan')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_sessions');
    }
};
