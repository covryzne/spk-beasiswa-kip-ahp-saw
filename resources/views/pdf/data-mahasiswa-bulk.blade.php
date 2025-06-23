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
            margin: 3px 0 0 0;
            color: #666;
            font-size: 9px;
        }

        .summary {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .mahasiswa-item {
            margin-bottom: 25px;
            border: 1px solid #ddd;
            padding: 15px;
            page-break-inside: avoid;
        }

        .mahasiswa-header {
            background-color: #007bff;
            color: white;
            padding: 8px 12px;
            margin: -15px -15px 15px -15px;
            font-weight: bold;
            font-size: 11px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-section {
            margin-bottom: 10px;
        }

        .info-title {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            font-size: 9px;
            text-transform: uppercase;
        }

        .info-table {
            width: 100%;
            font-size: 9px;
        }

        .info-table td {
            padding: 3px 8px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 100px;
            font-weight: bold;
            color: #666;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>DATA MAHASISWA - BULK EXPORT</h1>
        <p>Sistem Pendukung Keputusan Beasiswa KIP</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <strong>Total Data: {{ $total }} mahasiswa</strong>
    </div>

    @foreach($mahasiswas as $mahasiswa)
    <div class="mahasiswa-item">
        <div class="mahasiswa-header">
            {{ $mahasiswa->nama }} (NIM: {{ $mahasiswa->nim }})
        </div>

        <div class="info-grid">
            <div>
                <div class="info-section">
                    <div class="info-title">Informasi Personal</div>
                    <table class="info-table">
                        <tr>
                            <td>Email</td>
                            <td>{{ $mahasiswa->email }}</td>
                        </tr>
                        <tr>
                            <td>No. HP</td>
                            <td>{{ $mahasiswa->no_hp }}</td>
                        </tr>
                        <tr>
                            <td>JK</td>
                            <td>{{ $mahasiswa->jenis_kelamin }}</td>
                        </tr>
                        <tr>
                            <td>Tgl Lahir</td>
                            <td>{{ $mahasiswa->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('d/m/Y') : '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-section">
                    <div class="info-title">Akademik</div>
                    <table class="info-table">
                        <tr>
                            <td>IPK</td>
                            <td>{{ number_format($mahasiswa->ipk, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Jurusan</td>
                            <td>{{ $mahasiswa->jurusan ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Semester</td>
                            <td>{{ $mahasiswa->semester ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <div class="info-section">
                    <div class="info-title">Keluarga & Ekonomi</div>
                    <table class="info-table">
                        <tr>
                            <td>Penghasilan</td>
                            <td>Rp {{ number_format($mahasiswa->penghasilan_ortu, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td>Tanggungan</td>
                            <td>{{ $mahasiswa->jumlah_tanggungan }} orang</td>
                        </tr>
                        <tr>
                            <td>Kerja Ayah</td>
                            <td>{{ $mahasiswa->pekerjaan_ayah ?: '-' }}</td>
                        </tr>
                        <tr>
                            <td>Kerja Ibu</td>
                            <td>{{ $mahasiswa->pekerjaan_ibu ?: '-' }}</td>
                        </tr>
                    </table>
                </div>

                <div class="info-section">
                    <div class="info-title">Prestasi & Aktivitas</div>
                    <table class="info-table">
                        <tr>
                            <td>Prestasi</td>
                            <td>{{ $mahasiswa->prestasi }} prestasi</td>
                        </tr>
                        <tr>
                            <td>Organisasi</td>
                            <td>{{ $mahasiswa->organisasi ?: '-' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem SPK Beasiswa KIP</p>
    </div>
</body>

</html>