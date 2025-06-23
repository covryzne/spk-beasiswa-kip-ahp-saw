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
        Schema::create('data_mahasiswa', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('program_studi');

            // 18 Pertanyaan Wawancara
            $table->enum('kip_status', ['Ya', 'Tidak'])->comment('Apakah memiliki KIP/DTKS/PKH/KKS/PPK/Panti');
            $table->enum('orang_tua_status', ['Masih Ada', 'Ayah Meninggal', 'Ibu Meninggal', 'Keduanya Meninggal'])->comment('Status orang tua');
            $table->string('pekerjaan_orang_tua')->comment('Pekerjaan orang tua');
            $table->decimal('penghasilan_orang_tua', 12, 2)->comment('Penghasilan orang tua per bulan');
            $table->integer('jumlah_saudara')->comment('Jumlah saudara kandung');
            $table->enum('kepemilikan_rumah', ['Milik Sendiri', 'Sewa', 'Kontrak', 'Menumpang', 'Lainnya'])->comment('Status kepemilikan rumah');
            $table->enum('kondisi_rumah', ['Sangat Baik', 'Baik', 'Cukup', 'Kurang', 'Sangat Kurang'])->comment('Kondisi fisik rumah');
            $table->integer('daya_listrik')->comment('Daya listrik (Watt)');
            $table->enum('sumber_air', ['PDAM', 'Sumur Bor', 'Sumur Gali', 'Air Hujan', 'Sungai/Mata Air'])->comment('Sumber air yang digunakan');
            $table->enum('kendaraan', ['Motor', 'Mobil', 'Sepeda', 'Tidak Ada'])->comment('Kendaraan yang dimiliki');
            $table->enum('kondisi_ekonomi', ['Surplus', 'Cukup', 'Defisit', 'Berhutang'])->comment('Kondisi ekonomi keluarga');
            $table->text('prestasi')->nullable()->comment('Prestasi yang pernah diraih');
            $table->enum('status_bekerja', ['Bekerja', 'Tidak Bekerja'])->comment('Status pekerjaan saat ini');
            $table->enum('status_daftar_ulang', ['Sudah', 'Belum'])->comment('Status daftar ulang');
            $table->text('sumber_biaya_daftar_ulang')->nullable()->comment('Sumber biaya daftar ulang jika sudah');
            $table->enum('komitmen', ['Sangat Berkomitmen', 'Berkomitmen', 'Cukup Berkomitmen'])->comment('Tingkat komitmen jika diterima');
            $table->enum('fleksibilitas_jurusan', ['Ya', 'Tidak'])->comment('Bersedia di jurusan lain jika tidak diterima pilihan 1');
            $table->enum('rencana_mendaftar_lagi', ['Ya', 'Tidak'])->comment('Rencana mendaftar lagi tahun depan');
            $table->enum('support_orang_tua', ['Sangat Mendukung', 'Mendukung', 'Cukup Mendukung', 'Kurang Mendukung'])->comment('Tingkat dukungan orang tua');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_mahasiswa');
    }
};
