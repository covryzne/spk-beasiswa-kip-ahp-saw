<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }

        .content {
            margin-bottom: 20px;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background-color: #f8f9fa;
            padding: 8px 12px;
            border-left: 4px solid #007bff;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 8px 12px;
            border-bottom: 1px solid #eee;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 200px;
            font-weight: bold;
            background-color: #f8f9fa;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA MAHASISWA</h1>
        <p>Sistem Pendukung Keputusan Beasiswa KIP</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">Informasi Personal</div>
            <table class="info-table">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>{{ $mahasiswa->nama }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>{{ $mahasiswa->program_studi }}</td>
                </tr>
            </table>
        </div>
        <div class="section">
            <div class="section-title">Data Wawancara - Status & Keluarga</div>
            <table class="info-table">
                <tr>
                    <td>Status KIP/DTKS/PKH/KKS/PPK</td>
                    <td>{{ $mahasiswa->kip_status }}</td>
                </tr>
                <tr>
                    <td>Status Orang Tua</td>
                    <td>{{ $mahasiswa->orang_tua_status }}</td>
                </tr>
                <tr>
                    <td>Pekerjaan Orang Tua</td>
                    <td>{{ $mahasiswa->pekerjaan_orang_tua ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Penghasilan Orang Tua per Bulan</td>
                    <td>Rp {{ number_format($mahasiswa->penghasilan_orang_tua, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Jumlah Saudara Kandung</td>
                    <td>{{ $mahasiswa->jumlah_saudara }} orang</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Data Wawancara - Kondisi Rumah & Fasilitas</div>
            <table class="info-table">
                <tr>
                    <td>Status Kepemilikan Rumah</td>
                    <td>{{ $mahasiswa->kepemilikan_rumah ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Kondisi Fisik Rumah</td>
                    <td>{{ $mahasiswa->kondisi_rumah ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Daya Listrik</td>
                    <td>{{ $mahasiswa->daya_listrik }} Watt</td>
                </tr>
                <tr>
                    <td>Sumber Air</td>
                    <td>{{ $mahasiswa->sumber_air ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Kendaraan</td>
                    <td>{{ $mahasiswa->kendaraan ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Data Wawancara - Ekonomi & Prestasi</div>
            <table class="info-table">
                <tr>
                    <td>Kondisi Ekonomi</td>
                    <td>{{ $mahasiswa->kondisi_ekonomi ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Prestasi yang Pernah Diraih</td>
                    <td>{{ $mahasiswa->prestasi ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Status Pekerjaan Saat Ini</td>
                    <td>{{ $mahasiswa->status_bekerja ?: '-' }}</td>
                </tr>
            </table>
        </div>

        <div class="section">
            <div class="section-title">Data Wawancara - Status Pendaftaran</div>
            <table class="info-table">
                <tr>
                    <td>Status Daftar Ulang</td>
                    <td>{{ $mahasiswa->status_daftar_ulang ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Sumber Biaya Daftar Ulang</td>
                    <td>{{ $mahasiswa->sumber_biaya_daftar_ulang ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Tingkat Komitmen</td>
                    <td>{{ $mahasiswa->komitmen ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Bersedia di Jurusan Lain</td>
                    <td>{{ $mahasiswa->fleksibilitas_jurusan ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Rencana Mendaftar Lagi Tahun Depan</td>
                    <td>{{ $mahasiswa->rencana_mendaftar_lagi ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Tingkat Dukungan Orang Tua</td>
                    <td>{{ $mahasiswa->support_orang_tua ?: '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem SPK Beasiswa KIP</p>
    </div>
</body>

</html>