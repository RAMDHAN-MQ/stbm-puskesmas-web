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
        Schema::create('kk', function (Blueprint $table) {

            $table->bigInteger('no_kk')->primary();

            $table->string('nama_kepala_kk')->nullable();

            $table->foreignId('wilayah_id')
                ->nullable()
                ->constrained('wilayah')
                ->nullOnDelete();

            $table->integer('rt')->nullable();
            $table->integer('rw')->nullable();

            $table->integer('jumlah_jiwa')->nullable();
            $table->integer('jumlah_jiwa_menetap')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kk');
    }
};
