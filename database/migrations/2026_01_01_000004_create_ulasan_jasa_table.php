<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasan_jasa', function (Blueprint $table) {
            $table->bigIncrements('id_ulasan');
            $table->unsignedBigInteger('id_pesanan');
            $table->unsignedTinyInteger('rating');
            $table->text('komentar_ulasan')->nullable();
            $table->date('tanggal_ulasan');
            $table->timestamps();

            $table->foreign('id_pesanan')
                ->references('id_pesanan')
                ->on('pesanan_jasa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasan_jasa');
    }
};
