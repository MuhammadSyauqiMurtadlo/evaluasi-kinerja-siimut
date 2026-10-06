<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('response_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('response_id')->constrained('responses')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->enum('jawaban', ['ya', 'tidak']);
            $table->unsignedTinyInteger('frekuensi')->nullable(); // 1-4, diisi hanya jika jawaban = ya
            $table->unsignedTinyInteger('dampak')->nullable();    // 1-4, diisi hanya jika jawaban = ya
            $table->timestamps();

            $table->unique(['response_id', 'question_id']); // 1 responden hanya boleh 1 jawaban per pertanyaan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('response_answers');
    }
};
