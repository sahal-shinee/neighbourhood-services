<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->index('peran');
            $table->index('status_verifikasi');
            $table->index('is_aktif');
            $table->index(['peran', 'status_verifikasi']);
            $table->index(['peran', 'is_aktif']);
        });

        Schema::table('jasa', function (Blueprint $table) {
            $table->index('id_penyedia');
            $table->index('kategori_jasa');
            $table->index('is_aktif');
            $table->index(['is_aktif', 'kategori_jasa']);
        });

        Schema::table('pesanan_jasa', function (Blueprint $table) {
            $table->index('id_pelanggan');
            $table->index('id_jasa');
            $table->index('status_pesanan');
            $table->index('tanggal_booking');
            $table->index(['id_jasa', 'status_pesanan']);
        });

        Schema::table('laporan', function (Blueprint $table) {
            $table->index('id_pelapor');
            $table->index('id_penyedia');
            $table->index('status');
            $table->index(['status', 'updated_at']);
        });

        Schema::table('ulasan_jasa', function (Blueprint $table) {
            $table->index('id_pesanan');
        });
    }

    public function down(): void
    {
        Schema::table('pengguna', function (Blueprint $table) {
            $table->dropIndex(['peran']);
            $table->dropIndex(['status_verifikasi']);
            $table->dropIndex(['is_aktif']);
            $table->dropIndex(['peran', 'status_verifikasi']);
            $table->dropIndex(['peran', 'is_aktif']);
        });

        Schema::table('jasa', function (Blueprint $table) {
            $table->dropIndex(['id_penyedia']);
            $table->dropIndex(['kategori_jasa']);
            $table->dropIndex(['is_aktif']);
            $table->dropIndex(['is_aktif', 'kategori_jasa']);
        });

        Schema::table('pesanan_jasa', function (Blueprint $table) {
            $table->dropIndex(['id_pelanggan']);
            $table->dropIndex(['id_jasa']);
            $table->dropIndex(['status_pesanan']);
            $table->dropIndex(['tanggal_booking']);
            $table->dropIndex(['id_jasa', 'status_pesanan']);
        });

        Schema::table('laporan', function (Blueprint $table) {
            $table->dropIndex(['id_pelapor']);
            $table->dropIndex(['id_penyedia']);
            $table->dropIndex(['status']);
            $table->dropIndex(['status', 'updated_at']);
        });

        Schema::table('ulasan_jasa', function (Blueprint $table) {
            $table->dropIndex(['id_pesanan']);
        });
    }
};
