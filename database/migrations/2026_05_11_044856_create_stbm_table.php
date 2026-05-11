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
        Schema::create('stbm', function (Blueprint $table) {

            $table->id();

            $table->foreignId('pegawai_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->bigInteger('no_kk');

            $table->foreign('no_kk')
                ->references('no_kk')
                ->on('kk')
                ->cascadeOnDelete();

            $table->foreignId('wilayah_id')
                ->nullable()
                ->constrained('wilayah')
                ->nullOnDelete();

            $table->enum('status', ['selesai', 'proses'])
                ->default('proses');

            $table->string('pilar_1', 20)->nullable();
            $table->string('pilar_2', 20)->nullable();
            $table->string('pilar_3', 20)->nullable();
            $table->string('pilar_4', 20)->nullable();
            $table->string('pilar_5', 20)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stbm');
    }
};
