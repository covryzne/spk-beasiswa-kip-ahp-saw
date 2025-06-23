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
            [
                'nama' => 'Ahmad Syahril',
                'program_studi' => 'Teknik Informatika',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Petani',
                'penghasilan_orang_tua' => 1500000,
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Cukup',
                'daya_listrik' => 900,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Sepeda',
                'kondisi_ekonomi' => 'Defisit',
                'prestasi' => 'Juara 2 Olimpiade Matematika Tingkat Kabupaten',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung'
            ],
            [
                'nama' => 'Siti Nurhaliza',
                'program_studi' => 'Sistem Informasi',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Ayah Meninggal',
                'pekerjaan_orang_tua' => 'Buruh Tani',
                'penghasilan_orang_tua' => 800000,
                'jumlah_saudara' => 4,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Kurang',
                'daya_listrik' => 450,
                'sumber_air' => 'Sumur Gali',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Berhutang',
                'prestasi' => 'Siswa Berprestasi Tingkat Sekolah',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung'
            ],
            [
                'nama' => 'Budi Santoso',
                'program_studi' => 'Teknik Mesin',
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
                'support_orang_tua' => 'Cukup Mendukung'
            ],
            [
                'nama' => 'Rina Wulandari',
                'program_studi' => 'Akuntansi',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Buruh Harian',
                'penghasilan_orang_tua' => 1200000,
                'jumlah_saudara' => 5,
                'kepemilikan_rumah' => 'Sewa',
                'kondisi_rumah' => 'Kurang',
                'daya_listrik' => 900,
                'sumber_air' => 'Sumur Bor',
                'kendaraan' => 'Motor',
                'kondisi_ekonomi' => 'Defisit',
                'prestasi' => 'Juara 1 Lomba Karya Tulis Ilmiah',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung'
            ],
            [
                'nama' => 'Dedi Kurniawan',
                'program_studi' => 'Manajemen',
                'kip_status' => 'Tidak',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'PNS',
                'penghasilan_orang_tua' => 4500000,
                'jumlah_saudara' => 1,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Sangat Baik',
                'daya_listrik' => 2200,
                'sumber_air' => 'PDAM',
                'kendaraan' => 'Mobil',
                'kondisi_ekonomi' => 'Surplus',
                'prestasi' => 'Ketua OSIS, Juara Debat Bahasa Inggris',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Biaya sendiri',
                'komitmen' => 'Cukup Berkomitmen',
                'fleksibilitas_jurusan' => 'Tidak',
                'rencana_mendaftar_lagi' => 'Ya',
                'support_orang_tua' => 'Mendukung'
            ],
            [
                'nama' => 'Maya Sari',
                'program_studi' => 'Psikologi',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Keduanya Meninggal',
                'pekerjaan_orang_tua' => 'Dirawat Nenek',
                'penghasilan_orang_tua' => 500000,
                'jumlah_saudara' => 2,
                'kepemilikan_rumah' => 'Menumpang',
                'kondisi_rumah' => 'Sangat Kurang',
                'daya_listrik' => 450,
                'sumber_air' => 'Air Hujan',
                'kendaraan' => 'Tidak Ada',
                'kondisi_ekonomi' => 'Berhutang',
                'prestasi' => 'Juara 1 Pidato Tingkat Kecamatan',
                'status_bekerja' => 'Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Kurang Mendukung'
            ],
            [
                'nama' => 'Rizki Pratama',
                'program_studi' => 'Teknik Elektro',
                'kip_status' => 'Ya',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Tukang Ojek',
                'penghasilan_orang_tua' => 1800000,
                'jumlah_saudara' => 3,
                'kepemilikan_rumah' => 'Kontrak',
                'kondisi_rumah' => 'Cukup',
                'daya_listrik' => 1300,
                'sumber_air' => 'PDAM',
                'kendaraan' => 'Motor',
                'kondisi_ekonomi' => 'Cukup',
                'prestasi' => 'Juara 3 Lomba Robot Tingkat Provinsi',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Belum',
                'sumber_biaya_daftar_ulang' => null,
                'komitmen' => 'Berkomitmen',
                'fleksibilitas_jurusan' => 'Ya',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Mendukung'
            ],
            [
                'nama' => 'Fitri Handayani',
                'program_studi' => 'Kedokteran',
                'kip_status' => 'Tidak',
                'orang_tua_status' => 'Masih Ada',
                'pekerjaan_orang_tua' => 'Dokter',
                'penghasilan_orang_tua' => 15000000,
                'jumlah_saudara' => 1,
                'kepemilikan_rumah' => 'Milik Sendiri',
                'kondisi_rumah' => 'Sangat Baik',
                'daya_listrik' => 4400,
                'sumber_air' => 'PDAM',
                'kendaraan' => 'Mobil',
                'kondisi_ekonomi' => 'Surplus',
                'prestasi' => 'Juara 1 Olimpiade Biologi Nasional',
                'status_bekerja' => 'Tidak Bekerja',
                'status_daftar_ulang' => 'Sudah',
                'sumber_biaya_daftar_ulang' => 'Biaya sendiri',
                'komitmen' => 'Sangat Berkomitmen',
                'fleksibilitas_jurusan' => 'Tidak',
                'rencana_mendaftar_lagi' => 'Tidak',
                'support_orang_tua' => 'Sangat Mendukung'
            ]
        ];

        foreach ($dataMahasiswa as $data) {
            DataMahasiswa::create($data);
        }

        $this->command->info('✅ Data Mahasiswa seeder completed successfully!');
    }
}
