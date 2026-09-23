<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('informants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->unique()->constrained()->cascadeOnDelete();
            $table->string('kode'); // kode/inisial informan
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->string('unit')->nullable();
            $table->string('pengalaman_penggunaan')->nullable(); // ex: "2 tahun"
            $table->string('frekuensi_penggunaan')->nullable(); // ex: "Setiap hari"
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('informants');
    }
};
