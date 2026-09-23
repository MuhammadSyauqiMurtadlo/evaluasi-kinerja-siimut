<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('findings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('evaluation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('pain_point_id')->nullable()->constrained()->nullOnDelete();
            $table->string('judul');
            $table->text('deskripsi');
            $table->enum('kategori', [
                'navigation', 'interaction', 'information',
                'content', 'visual_ui', 'functionality',
            ]);
            $table->enum('severity', ['low', 'medium', 'high', 'critical']);
            $table->string('frequency')->nullable(); // ex: "3 dari 5 informan"
            $table->text('impact')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('findings');
    }
};
