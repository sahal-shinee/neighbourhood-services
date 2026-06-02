<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('favorit_jasa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pelanggan');
            $table->unsignedBigInteger('id_jasa');
            $table->timestamps();

            $table->foreign('id_pelanggan')->references('id_pengguna')->on('pengguna')->onDelete('cascade');
            $table->foreign('id_jasa')->references('id_jasa')->on('jasa')->onDelete('cascade');

            // Satu pelanggan hanya bisa favorit satu jasa satu kali
            $table->unique(['id_pelanggan', 'id_jasa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('favorit_jasa');
    }
};
