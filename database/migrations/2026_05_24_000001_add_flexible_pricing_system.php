<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add tipe_tarif to jasa & make tarif_per_jam nullable
        Schema::table('jasa', function (Blueprint $table) {
            $table->enum('tipe_tarif', ['per_jam', 'per_pengerjaan', 'paket'])
                  ->default('per_jam')
                  ->after('tarif_per_jam');
            $table->decimal('tarif_per_jam', 12, 2)->nullable()->change();
        });

        // 2. Create paket_harga table for tier-based pricing
        Schema::create('paket_harga', function (Blueprint $table) {
            $table->bigIncrements('id_paket');
            $table->unsignedBigInteger('id_jasa');
            $table->string('nama_paket', 100);
            $table->decimal('harga', 12, 2);
            $table->text('deskripsi')->nullable();
            $table->tinyInteger('urutan')->default(1)->unsigned();
            $table->timestamps();

            $table->foreign('id_jasa')
                  ->references('id_jasa')
                  ->on('jasa')
                  ->onDelete('cascade');
        });

        // 3. Add paket & estimasi fields to pesanan_jasa, make jam nullable
        Schema::table('pesanan_jasa', function (Blueprint $table) {
            $table->unsignedBigInteger('id_paket')->nullable()->after('id_jasa');
            $table->unsignedSmallInteger('estimasi_hari')->nullable()->after('id_paket');
            $table->time('jam_mulai')->nullable()->change();
            $table->time('jam_selesai')->nullable()->change();

            $table->foreign('id_paket')
                  ->references('id_paket')
                  ->on('paket_harga')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan_jasa', function (Blueprint $table) {
            $table->dropForeign(['id_paket']);
            $table->dropColumn(['id_paket', 'estimasi_hari']);
            $table->time('jam_mulai')->nullable(false)->change();
            $table->time('jam_selesai')->nullable(false)->change();
        });

        Schema::dropIfExists('paket_harga');

        Schema::table('jasa', function (Blueprint $table) {
            $table->dropColumn('tipe_tarif');
            $table->decimal('tarif_per_jam', 12, 2)->nullable(false)->change();
        });
    }
};
