<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jasa', function (Blueprint $table) {
            $table->bigIncrements('id_jasa');
            $table->unsignedBigInteger('id_penyedia');
            $table->string('nama_jasa', 150);
            $table->string('kategori_jasa', 100);
            $table->text('deskripsi_jasa');
            $table->decimal('tarif_per_jam', 12, 2);
            $table->string('foto_jasa', 255)->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamps();

            $table->foreign('id_penyedia')
                ->references('id_pengguna')
                ->on('pengguna')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jasa');
    }
};