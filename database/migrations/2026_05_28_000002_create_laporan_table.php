<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->bigIncrements('id_laporan');

            // Who reports
            $table->unsignedBigInteger('id_pelapor');
            $table->foreign('id_pelapor')->references('id_pengguna')->on('pengguna')->onDelete('cascade');

            // Who is reported (must be penyedia)
            $table->unsignedBigInteger('id_penyedia');
            $table->foreign('id_penyedia')->references('id_pengguna')->on('pengguna')->onDelete('cascade');

            // Optional order context
            $table->unsignedBigInteger('id_pesanan')->nullable();
            $table->foreign('id_pesanan')->references('id_pesanan')->on('pesanan_jasa')->onDelete('set null');

            $table->string('alasan', 100);          // e.g. "Penipuan", "Tidak Profesional", etc.
            $table->text('detail_laporan');
            $table->string('bukti_foto', 255)->nullable();

            $table->enum('status', ['baru', 'ditinjau', 'ditindaklanjuti', 'ditolak'])->default('baru');
            $table->text('catatan_admin')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
