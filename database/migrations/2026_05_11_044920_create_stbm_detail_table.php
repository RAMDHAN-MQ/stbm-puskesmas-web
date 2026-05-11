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
        Schema::create('stbm_detail', function (Blueprint $table) {

            $table->id();

            $table->foreignId('stbm_id')
                ->constrained('stbm')
                ->cascadeOnDelete();

            $table->foreignId('pertanyaan_id')
                ->nullable()
                ->constrained('pertanyaan')
                ->nullOnDelete();

            $table->enum('jawaban', ['ya', 'tidak'])->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stbm_detail');
    }
};
