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

        .kriteria-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .kriteria-table th,
        .kriteria-table td {
            border: 1px solid #ddd;
            padding: 8px 12px;
            text-align: left;
        }

        .kriteria-table th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        .kriteria-table td:last-child {
            text-align: center;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }

        .reference-info {
            background-color: #e3f2fd;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>CALON MAHASISWA PENERIMA BEASISWA</h1>
        <p>Sistem Pendukung Keputusan Beasiswa KIP</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <div class="content">
        <div class="section">
            <div class="section-title">Informasi Dasar</div>
            <table class="info-table">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>{{ $mahasiswa->nama }}</td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td>{{ $mahasiswa->nim }}</td>
                </tr>
                @if($dataMahasiswa)
                <tr>
                    <td>Status</td>
                    <td>Data diambil dari Master Data Mahasiswa</td>
                </tr>
                @endif
            </table>
        </div>

        @if($dataMahasiswa)
        <div class="section">
            <div class="section-title">Referensi Data Mahasiswa</div>
            <div class="reference-info">
                <strong>Data Master:</strong> {{ $dataMahasiswa->nama }} (NIM: {{ $dataMahasiswa->nim }})<br>
                <strong>Email:</strong> {{ $dataMahasiswa->email }}<br>
                <strong>IPK:</strong> {{ number_format($dataMahasiswa->ipk, 2) }}
            </div>
        </div>
        @endif

        <div class="section">
            <div class="section-title">Nilai Kriteria SPK</div>
            <table class="kriteria-table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Kriteria</th>
                        <th>Tipe</th>
                        <th>Nilai Asli</th>
                        <th>Nilai Terformat</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteriaValues as $kv)
                    <tr>
                        <td>{{ $kv['kriteria']->kode }}</td>
                        <td>{{ $kv['kriteria']->nama }}</td>
                        <td>{{ ucfirst($kv['kriteria']->type) }}</td>
                        <td>{{ $kv['raw_value'] ?? '-' }}</td>
                        <td>{{ $kv['formatted_value'] ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($dataMahasiswa)
        <div class="section">
            <div class="section-title">Detail Lengkap dari Master Data</div>
            <table class="info-table">
                <tr>
                    <td>Email</td>
                    <td>{{ $dataMahasiswa->email }}</td>
                </tr>
                <tr>
                    <td>No. HP</td>
                    <td>{{ $dataMahasiswa->no_hp }}</td>
                </tr>
                <tr>
                    <td>Jenis Kelamin</td>
                    <td>{{ $dataMahasiswa->jenis_kelamin }}</td>
                </tr>
                <tr>
                    <td>Jurusan</td>
                    <td>{{ $dataMahasiswa->jurusan ?: '-' }}</td>
                </tr>
                <tr>
                    <td>Semester</td>
                    <td>{{ $dataMahasiswa->semester ?: '-' }}</td>
                </tr>
                <tr>
                    <td>IPK</td>
                    <td>{{ number_format($dataMahasiswa->ipk, 2) }}</td>
                </tr>
                <tr>
                    <td>Penghasilan Orang Tua</td>
                    <td>Rp {{ number_format($dataMahasiswa->penghasilan_ortu, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td>Jumlah Tanggungan</td>
                    <td>{{ $dataMahasiswa->jumlah_tanggungan }} orang</td>
                </tr>
                <tr>
                    <td>Prestasi</td>
                    <td>{{ $dataMahasiswa->prestasi }} prestasi</td>
                </tr>
            </table>
        </div>
        @endif
    </div>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem SPK Beasiswa KIP</p>
        <p>Data kriteria diambil secara dinamis sesuai konfigurasi sistem</p>
    </div>
</body>

</html>