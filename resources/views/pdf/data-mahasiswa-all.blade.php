<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 16px;
            color: #333;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #666;
        }

        .summary {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }

        .mahasiswa-item {
            margin-bottom: 30px;
            page-break-inside: avoid;
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
        }

        .mahasiswa-header {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            margin: -15px -15px 15px -15px;
            border-radius: 5px 5px 0 0;
            font-weight: bold;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            width: 200px;
            font-weight: bold;
            color: #333;
        }

        .info-value {
            flex: 1;
            color: #666;
        }

        .section-divider {
            border-top: 1px solid #eee;
            margin: 15px 0 10px 0;
            padding-top: 10px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        @media print {
            .mahasiswa-item {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA MAHASISWA - SEMUA DATA</h1>
        <p>Sistem Pendukung Keputusan Beasiswa KIP</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <strong>Total Data: {{ $total }} Mahasiswa</strong>
    </div>

    @foreach($mahasiswas as $index => $mahasiswa)
    <div class="mahasiswa-item">
        <div class="mahasiswa-header">
            {{ $index + 1 }}. {{ $mahasiswa->nama }} - {{ $mahasiswa->program_studi }}
        </div>
        <div class="info-row">
            <div class="info-label">Status KIP:</div>
            <div class="info-value">{{ $mahasiswa->kip_status }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Penghasilan Orang Tua:</div>
            <div class="info-value">Rp {{ number_format($mahasiswa->penghasilan_orang_tua, 0, ',', '.') }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Status Orang Tua:</div>
            <div class="info-value">{{ $mahasiswa->orang_tua_status }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Pekerjaan Orang Tua:</div>
            <div class="info-value">{{ $mahasiswa->pekerjaan_orang_tua ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Jumlah Saudara:</div>
            <div class="info-value">{{ $mahasiswa->jumlah_saudara }} orang</div>
        </div>

        <div class="section-divider"></div>

        <div class="info-row">
            <div class="info-label">Status Kepemilikan Rumah:</div>
            <div class="info-value">{{ $mahasiswa->kepemilikan_rumah ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Kondisi Rumah:</div>
            <div class="info-value">{{ $mahasiswa->kondisi_rumah ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Daya Listrik:</div>
            <div class="info-value">{{ $mahasiswa->daya_listrik }} Watt</div>
        </div>

        <div class="info-row">
            <div class="info-label">Sumber Air:</div>
            <div class="info-value">{{ $mahasiswa->sumber_air ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Kendaraan:</div>
            <div class="info-value">{{ $mahasiswa->kendaraan ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Kondisi Ekonomi:</div>
            <div class="info-value">{{ $mahasiswa->kondisi_ekonomi ?: '-' }}</div>
        </div>

        <div class="section-divider"></div>

        <div class="info-row">
            <div class="info-label">Prestasi:</div>
            <div class="info-value">{{ $mahasiswa->prestasi ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Status Bekerja:</div>
            <div class="info-value">{{ $mahasiswa->status_bekerja ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Status Daftar Ulang:</div>
            <div class="info-value">{{ $mahasiswa->status_daftar_ulang ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Sumber Biaya Daftar Ulang:</div>
            <div class="info-value">{{ $mahasiswa->sumber_biaya_daftar_ulang ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Tingkat Komitmen:</div>
            <div class="info-value">{{ $mahasiswa->komitmen ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Bersedia di Jurusan Lain:</div>
            <div class="info-value">{{ $mahasiswa->fleksibilitas_jurusan ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Rencana Mendaftar Lagi:</div>
            <div class="info-value">{{ $mahasiswa->rencana_mendaftar_lagi ?: '-' }}</div>
        </div>

        <div class="info-row">
            <div class="info-label">Dukungan Orang Tua:</div>
            <div class="info-value">{{ $mahasiswa->support_orang_tua ?: '-' }}</div>
        </div>
    </div>
    @endforeach

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem SPK Beasiswa KIP</p>
    </div>
</body>

</html>