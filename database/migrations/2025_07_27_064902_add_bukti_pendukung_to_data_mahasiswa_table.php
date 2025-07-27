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
        Schema::table('data_mahasiswa', function (Blueprint $table) {
            // Bukti pendukung untuk setiap field data mahasiswa
            $table->string('bukti_kip_status')->nullable()->after('kip_status');
            $table->string('bukti_orang_tua_status')->nullable()->after('orang_tua_status');
            $table->string('bukti_pekerjaan_orang_tua')->nullable()->after('pekerjaan_orang_tua');
            $table->string('bukti_penghasilan_orang_tua')->nullable()->after('penghasilan_orang_tua');
            $table->string('bukti_jumlah_saudara')->nullable()->after('jumlah_saudara');
            $table->string('bukti_kepemilikan_rumah')->nullable()->after('kepemilikan_rumah');
            $table->string('bukti_kondisi_rumah')->nullable()->after('kondisi_rumah');
            $table->string('bukti_daya_listrik')->nullable()->after('daya_listrik');
            $table->string('bukti_sumber_air')->nullable()->after('sumber_air');
            $table->string('bukti_kendaraan')->nullable()->after('kendaraan');
            $table->string('bukti_kondisi_ekonomi')->nullable()->after('kondisi_ekonomi');
            $table->string('bukti_prestasi')->nullable()->after('prestasi');
            $table->string('bukti_status_bekerja')->nullable()->after('status_bekerja');
            $table->string('bukti_status_daftar_ulang')->nullable()->after('status_daftar_ulang');
            $table->string('bukti_sumber_biaya_daftar_ulang')->nullable()->after('sumber_biaya_daftar_ulang');
            $table->string('bukti_komitmen')->nullable()->after('komitmen');
            $table->string('bukti_fleksibilitas_jurusan')->nullable()->after('fleksibilitas_jurusan');
            $table->string('bukti_rencana_mendaftar_lagi')->nullable()->after('rencana_mendaftar_lagi');
            $table->string('bukti_support_orang_tua')->nullable()->after('support_orang_tua');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_mahasiswa', function (Blueprint $table) {
            // Drop semua kolom bukti pendukung
            $table->dropColumn([
                'bukti_kip_status',
                'bukti_orang_tua_status',
                'bukti_pekerjaan_orang_tua',
                'bukti_penghasilan_orang_tua',
                'bukti_jumlah_saudara',
                'bukti_kepemilikan_rumah',
                'bukti_kondisi_rumah',
                'bukti_daya_listrik',
                'bukti_sumber_air',
                'bukti_kendaraan',
                'bukti_kondisi_ekonomi',
                'bukti_prestasi',
                'bukti_status_bekerja',
                'bukti_status_daftar_ulang',
                'bukti_sumber_biaya_daftar_ulang',
                'bukti_komitmen',
                'bukti_fleksibilitas_jurusan',
                'bukti_rencana_mendaftar_lagi',
                'bukti_support_orang_tua'
            ]);
        });
    }
};
