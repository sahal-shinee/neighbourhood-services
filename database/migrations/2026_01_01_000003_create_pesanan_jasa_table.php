<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pesanan_jasa', function (Blueprint $table) {
            $table->bigIncrements('id_pesanan');
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_jasa');
            $table->date('tanggal_booking');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status_pesanan', ['menunggu', 'disetujui', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->text('catatan_tambahan')->nullable();
            $table->timestamps();

            $table->foreign('id_pelanggan')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade');

            $table->foreign('id_jasa')
                ->references('id_jasa')
                ->on('jasa')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pesanan_jasa');
    }
};
