<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('observations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->text('tindakan')->nullable();
            $table->text('perilaku')->nullable();
            $table->text('reaksi')->nullable();
            $table->text('kesulitan')->nullable();
            $table->text('kebingungan')->nullable();
            $table->text('strategi_pengguna')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('observations');
    }
};
