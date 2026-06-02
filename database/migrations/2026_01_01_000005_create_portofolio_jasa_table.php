<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('portofolio_jasa', function (Blueprint $table) {
            $table->bigIncrements('id_portofolio');
            $table->unsignedBigInteger('id_penyedia');
            $table->string('judul_proyek', 150);
            $table->text('deskripsi_proyek')->nullable();
            $table->string('foto_proyek', 255);
            $table->timestamps();

            $table->foreign('id_penyedia')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('portofolio_jasa');
    }
};
