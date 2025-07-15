<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DataMahasiswa;

class DataMahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dataMahasiswa = [
            // === REAL DATA STUDENTS (2024) - Priority display ===
            [
                'nama' => 'Bunga Tri',
                'program_studi' => 'Teknik Informatika',
                'kip_status' => 'KIP + PKH', // 1. DTKS, 2. PPK
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Petani',
                'penghasilan_orang_tua' => 200000, // <250.000
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Cukup',
                'daya_listrik' => 900,
                'sumber_air' => 'Sumur Bor',
                'kendaraan' => 'Sepeda Motor',
                'kondisi_ekonomi' => 'Cukup', // CUKUP sesuai tabel
                'prestasi' => '60', // 60 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Bantuan Keluarga',
                'komitmen' => 'Sangat Berkomitmen', // TINGGI
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-08-01 00:00:00',
                'updated_at' => '2024-08-01 00:00:00'
            ],
            [
                'nama' => 'Putri Rukmana',
                'program_studi' => 'Pendidikan Matematika',
                'kip_status' => 'PKH', // 1. PPK
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Petani',
                'penghasilan_orang_tua' => 1000000,
                'jumlah_saudara' => 2,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Cukup',
                'daya_listrik' => 1300,
                'sumber_air' => 'PAM',
                'kendaraan' => 'Sepeda Motor',
                'kondisi_ekonomi' => 'Cukup', // CUKUP sesuai tabel
                'prestasi' => '80', // 80 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Bantuan Keluarga',
                'komitmen' => 'Sangat Berkomitmen', // TINGGI
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-08-15 00:00:00',
                'updated_at' => '2024-08-15 00:00:00'
            ],
            [
                'nama' => 'Delima Ayu Saraswati',
                'program_studi' => 'Teknik Elektro',
                'kip_status' => 'Lengkap KIP + PKH + KKS', // 1.DTKS, 2.KIP, 3.KKS, 4.PPK
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Karyawan', // PEGAWAI
                'penghasilan_orang_tua' => 250000,
                'jumlah_saudara' => 2,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Kurang', // RENDAH
                'daya_listrik' => 900,
                'sumber_air' => 'PAM',
                'kendaraan' => 'Sepeda Motor',
                'kondisi_ekonomi' => 'Sangat Sedikit', // sesuai tabel yang diberikan
                'prestasi' => '10', // 10 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Orang Tua',
                'komitmen' => 'Sangat Berkomitmen', // TINGGI
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung',
                'created_at' => '2024-08-05 00:00:00',
                'updated_at' => '2024-08-05 00:00:00'
            ],
            [
                'nama' => 'Endra Apri Setyawan',
                'program_studi' => 'Teknik Informatika',
                'kip_status' => 'KIP', // 1.KIP
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Tidak Bekerja',
                'penghasilan_orang_tua' => 1250000,
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Sangat Baik', // TINGGI
                'daya_listrik' => 1300,
                'sumber_air' => 'PAM',
                'kendaraan' => 'Sepeda Motor',
                'kondisi_ekonomi' => 'Sedikit', // SEDIKIT
                'prestasi' => '80', // 80 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Bantuan Keluarga',
                'komitmen' => 'Cukup Berkomitmen', // CUKUP
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-08-04 00:00:00',
                'updated_at' => '2024-08-04 00:00:00'
            ],
            [
                'nama' => 'Erika Indah',
                'program_studi' => 'Pendidikan Bahasa Indonesia',
                'kip_status' => 'Tidak Ada', // 0
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Tidak Bekerja',
                'penghasilan_orang_tua' => 200000, // <250.000
                'jumlah_saudara' => 4,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Sangat Baik', // TINGGI
                'daya_listrik' => 450,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Sangat Sedikit', // sesuai tabel yang diberikan
                'prestasi' => '10', // 10 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Cukup Berkomitmen', // CUKUP
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Kurang Mendukung',
                'created_at' => '2024-08-03 00:00:00',
                'updated_at' => '2024-08-03 00:00:00'
            ],
            [
                'nama' => 'Farel Lovian',
                'program_studi' => 'Teknik Elektro',
                'kip_status' => 'PKH', // 1.PPK
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Tidak Bekerja',
                'penghasilan_orang_tua' => 2500000,
                'jumlah_saudara' => 2,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Kurang', // RENDAH
                'daya_listrik' => 1300,
                'sumber_air' => 'PAM',
                'kendaraan' => 'Mobil',
                'kondisi_ekonomi' => 'Sedikit', // sesuai tabel yang diberikan
                'prestasi' => '10', // 10 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Orang Tua',
                'komitmen' => 'Sangat Berkomitmen', // TINGGI
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-08-01 00:00:00',
                'updated_at' => '2024-08-01 00:00:00'
            ],
            [
                'nama' => 'Silvia',
                'program_studi' => 'Teknik Informatika',
                'kip_status' => 'KIP + PKH + KKS', // 1.DTKS, 2.PPK, 3.KIP
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Petani',
                'penghasilan_orang_tua' => 250000, // rendah
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Kurang', // rendah
                'daya_listrik' => 900,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Sepeda Motor',
                'kondisi_ekonomi' => 'Sedikit', // sesuai tabel yang diberikan
                'prestasi' => '10', // 10 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Bantuan Keluarga',
                'komitmen' => 'Cukup Berkomitmen', // cukup
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-08-05 00:00:00',
                'updated_at' => '2024-08-05 00:00:00'
            ],
            [
                'nama' => 'Dian',
                'program_studi' => 'Pendidikan Matematika',
                'kip_status' => 'KIP + PKH', // 1.DTKS, 2.PPK
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Tidak Bekerja', // pengangguran
                'penghasilan_orang_tua' => 250000, // rendah
                'jumlah_saudara' => 4,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Sangat Baik', // sangat tinggi
                'daya_listrik' => 450,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Cukup', // sesuai tabel yang diberikan
                'prestasi' => '90', // 90 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Kurang Mendukung',
                'created_at' => '2024-08-06 00:00:00',
                'updated_at' => '2024-08-06 00:00:00'
            ],
            [
                'nama' => 'Shintia',
                'program_studi' => 'Teknik Elektro',
                'kip_status' => 'PKH', // 1.PPK
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'PNS',
                'penghasilan_orang_tua' => 4000000, // baik
                'jumlah_saudara' => 1,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Kurang', // rendah
                'daya_listrik' => 2200,
                'sumber_air' => 'PAM',
                'kendaraan' => 'Mobil',
                'kondisi_ekonomi' => 'Sangat Sedikit', // sesuai tabel yang diberikan
                'prestasi' => '10', // 10 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Orang Tua',
                'komitmen' => 'Berkomitmen',
                'fleksibilitas_jurusan' => 'Tidak',
                'rencana_mendaftar_lagi' => 'Ya',
                'support_orang_tua' => 'Sangat Mendukung',
                'created_at' => '2024-08-03 00:00:00',
                'updated_at' => '2024-08-03 00:00:00'
            ],
            [
                'nama' => 'Zakga',
                'program_studi' => 'Pendidikan Bahasa Indonesia',
                'kip_status' => 'KIP + KKS', // 1.DTKS, 2.KIP
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Karyawan', // swasta
                'penghasilan_orang_tua' => 250000,
                'jumlah_saudara' => 2,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Sangat Baik',
                'daya_listrik' => 900,
                'sumber_air' => 'PAM',
                'kendaraan' => 'Sepeda Motor',
                'kondisi_ekonomi' => 'Sedikit', // sesuai tabel yang diberikan
                'prestasi' => '10', // 10 score
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Bantuan Keluarga',
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Cukup Mendukung',
                'created_at' => '2024-08-02 00:00:00',
                'updated_at' => '2024-08-02 00:00:00'
            ],
            [
                'nama' => 'Sagia',
                'program_studi' => 'Teknik Industri',
                'kip_status' => 'Tidak Ada', // 0
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Tidak Bekerja', // pengangguran
                'penghasilan_orang_tua' => 2000000, // kurang
                'jumlah_saudara' => 4,
                'kepemilikan_rumah' => 'Sewa',
                'kondisi_rumah' => 'Baik', // tinggi
                'daya_listrik' => 1300,
                'sumber_air' => 'PAM',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Tidak Ada',
                'prestasi' => '90', // 90 score
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Kurang Mendukung',
                'created_at' => '2024-08-06 00:00:00',
                'updated_at' => '2024-08-06 00:00:00'
            ],
            [
                'nama' => 'Adriyan',
                'program_studi' => 'Teknik Informatika',
                'kip_status' => 'Lengkap KIP + PKH + KKS', // 1.PKH, 2.KIP, 3.KKS
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Petani',
                'penghasilan_orang_tua' => 250000,
                'jumlah_saudara' => 5,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Sangat Kurang', // sangat rendah
                'daya_listrik' => 450,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Sangat Sedikit',
                'prestasi' => '60', // 60 score
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung',
                'created_at' => '2024-08-05 00:00:00',
                'updated_at' => '2024-08-05 00:00:00'
            ],

            // === EXISTING SAMPLE DATA ===
            // Calon dengan kondisi sangat membutuhkan (prioritas tinggi KIP)
            [
                'nama' => 'Ahmad Syahril',
                'program_studi' => 'Pendidikan Matematika',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Ayah Meninggal',
                'pekerjaan_orang_tua' => 'Petani',
                'penghasilan_orang_tua' => 800000,
                'jumlah_saudara' => 4,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Kurang',
                'daya_listrik' => 450,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Sepeda',
                'kondisi_ekonomi' => 'Sangat Sedikit',
                'prestasi' => 'Juara 2 Olimpiade Matematika Tingkat Kabupaten',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung',
                'created_at' => '2024-07-20 08:30:00',
                'updated_at' => '2024-07-20 08:30:00'
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'program_studi' => 'Teknik Informatika',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Keduanya Meninggal',
                'pekerjaan_orang_tua' => 'Dirawat Nenek',
                'penghasilan_orang_tua' => 500000,
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Sangat Kurang',
                'daya_listrik' => 450,
                'sumber_air' => 'Air Hujan',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Sangat Sedikit',
                'prestasi' => 'Juara 1 Lomba Programming Tingkat Sekolah',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Kurang Mendukung',
                'created_at' => '2024-07-18 14:15:00',
                'updated_at' => '2024-07-18 14:15:00'
            ],
            [
                'nama' => 'Budi Santoso',
                'program_studi' => 'Teknik Elektro',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Buruh Harian',
                'penghasilan_orang_tua' => 1000000,
                'jumlah_saudara' => 5,
                'kepemilikan_rumah' => 'Sewa',
                'kondisi_rumah' => 'Kurang',
                'daya_listrik' => 900,
                'sumber_air' => 'Sumur Bor',
                'kendaraan' => 'Sepeda',
                'kondisi_ekonomi' => 'Sangat Sedikit',
                'prestasi' => 'Juara 3 Lomba Robot Tingkat Kabupaten',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-07-22 10:45:00',
                'updated_at' => '2024-07-22 10:45:00'
            ],
            [
                'nama' => 'Rina Wulandari',
                'program_studi' => 'Pendidikan Bahasa Indonesia',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Ibu Meninggal',
                'pekerjaan_orang_tua' => 'Tukang Becak',
                'penghasilan_orang_tua' => 700000,
                'jumlah_saudara' => 4,
                'kepemilikan_rumah' => 'Kontrak',
                'kondisi_rumah' => 'Kurang',
                'daya_listrik' => 450,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Sangat Sedikit',
                'prestasi' => 'Juara 1 Lomba Karya Tulis Ilmiah Tingkat Kabupaten',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung',
                'created_at' => '2024-07-25 16:20:00',
                'updated_at' => '2024-07-25 16:20:00'
            ],
            [
                'nama' => 'Dedi Kurniawan',
                'program_studi' => 'Teknik Industri',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Buruh Tani',
                'penghasilan_orang_tua' => 1200000,
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Cukup',
                'daya_listrik' => 900,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Motor',
                'kondisi_ekonomi' => 'Sangat Sedikit',
                'prestasi' => 'Juara 2 Lomba Karya Ilmiah Remaja',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-07-28 09:15:00',
                'updated_at' => '2024-07-28 09:15:00'
            ],
            [
                'nama' => 'Maya Sari',
                'program_studi' => 'Pendidikan Guru Sekolah Dasar',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Pedagang Kecil',
                'penghasilan_orang_tua' => 900000,
                'jumlah_saudara' => 6,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Sangat Kurang',
                'daya_listrik' => 450,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Sangat Sedikit',
                'prestasi' => 'Juara 1 Pidato Tingkat Kecamatan',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung',
                'created_at' => '2024-07-30 11:30:00',
                'updated_at' => '2024-07-30 11:30:00'
            ],
            [
                'nama' => 'Rizki Pratama',
                'program_studi' => 'Teknik Informatika',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Tukang Ojek',
                'penghasilan_orang_tua' => 1500000,
                'jumlah_saudara' => 2,
                'kepemilikan_rumah' => 'Sewa',
                'kondisi_rumah' => 'Cukup',
                'daya_listrik' => 900,
                'sumber_air' => 'PDAM',
                'kendaraan' => 'Motor',
                'kondisi_ekonomi' => 'Sedikit',
                'prestasi' => 'Juara 3 Lomba Programming Tingkat Provinsi',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Berkomitmen',
                'fleksibilitas_jurusan' => 'Tidak',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-06-15 13:45:00',
                'updated_at' => '2024-06-15 13:45:00'
            ],
            [
                'nama' => 'Fitri Handayani',
                'program_studi' => 'Pendidikan Fisika',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Petani',
                'penghasilan_orang_tua' => 1300000,
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Cukup',
                'daya_listrik' => 1300,
                'sumber_air' => 'Sumur Bor',
                'kendaraan' => 'Motor',
                'kondisi_ekonomi' => 'Sedikit',
                'prestasi' => 'Juara 2 Olimpiade Fisika Tingkat Kabupaten',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung',
                'created_at' => '2024-06-18 15:20:00',
                'updated_at' => '2024-06-18 15:20:00'
            ],
            // Tambah beberapa calon dengan kondisi ekonomi lebih baik (sebagai perbandingan)
            [
                'nama' => 'Andi Wijaya',
                'program_studi' => 'Teknik Elektro',
                'kip_status' => 'Tidak',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Wiraswasta',
                'penghasilan_orang_tua' => 3500000,
                'jumlah_saudara' => 2,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Baik',
                'daya_listrik' => 1300,
                'sumber_air' => 'PDAM',
                'kendaraan' => 'Motor',
                'kondisi_ekonomi' => 'Cukup',
                'prestasi' => 'Peserta Lomba Desain Grafis Tingkat Sekolah',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Bantuan keluarga',
                'komitmen' => 'Berkomitmen',
                'fleksibilitas_jurusan' => 'Tidak',
                'rencana_mendaftar_lagi' => 'Ya',
                'support_orang_tua' => 'Cukup Mendukung',
                'created_at' => '2024-06-20 08:10:00',
                'updated_at' => '2024-06-20 08:10:00'
            ],
            [
                'nama' => 'Linda Sari',
                'program_studi' => 'Pendidikan Bahasa Inggris',
                'kip_status' => 'Tidak',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'PNS',
                'penghasilan_orang_tua' => 4500000,
                'jumlah_saudara' => 1,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Baik',
                'daya_listrik' => 2200,
                'sumber_air' => 'PDAM',
                'kendaraan' => 'Mobil',
                'kondisi_ekonomi' => 'Sangat Banyak',
                'prestasi' => 'Ketua OSIS, Juara Debat Bahasa Inggris',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Biaya sendiri',
                'komitmen' => 'Cukup Berkomitmen',
                'fleksibilitas_jurusan' => 'Tidak',
                'rencana_mendaftar_lagi' => 'Ya',
                'support_orang_tua' => 'Mendukung',
                'created_at' => '2024-06-25 14:30:00',
                'updated_at' => '2024-06-25 14:30:00'
            ]
        ];

        foreach ($dataMahasiswa as $data) {
            DataMahasiswa::create($data);
        }

        $this->command->info('✅ Data Mahasiswa seeder completed successfully!');
    }
}
