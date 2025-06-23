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

        .kriteria-header {
            background-color: #e3f2fd;
            padding: 8px;
            margin-bottom: 15px;
            border-radius: 4px;
            font-size: 9px;
        }

        .mahasiswa-item {
            margin-bottom: 20px;
            border: 1px solid #ddd;
            padding: 12px;
            page-break-inside: avoid;
        }

        .mahasiswa-header {
            background-color: #007bff;
            color: white;
            padding: 6px 10px;
            margin: -12px -12px 10px -12px;
            font-weight: bold;
            font-size: 11px;
        }

        .kriteria-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .kriteria-table th,
        .kriteria-table td {
            border: 1px solid #ddd;
            padding: 4px 6px;
            text-align: left;
        }

        .kriteria-table th {
            background-color: #f8f9fa;
            font-weight: bold;
            font-size: 8px;
        }

        .kriteria-table td:last-child {
            text-align: center;
            font-weight: bold;
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
        <h1>CALON MAHASISWA PENERIMA BEASISWA - BULK EXPORT</h1>
        <p>Sistem Pendukung Keputusan Beasiswa KIP</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <strong>Total Data: {{ $total }} calon mahasiswa</strong>
    </div>

    <div class="kriteria-header">
        <strong>Kriteria yang digunakan:</strong>
        @foreach($kriteria as $k)
        {{ $k->kode }} ({{ $k->nama }}){{ !$loop->last ? ', ' : '' }}
        @endforeach
    </div>

    @foreach($mahasiswaData as $data)
    <div class="mahasiswa-item">
        <div class="mahasiswa-header">
            {{ $data['mahasiswa']->nama }} (NIM: {{ $data['mahasiswa']->nim }})
            @if($data['mahasiswa']->dataMahasiswa)
            - Data dari Master: {{ $data['mahasiswa']->dataMahasiswa->nama }}
            @endif
        </div>

        <table class="kriteria-table">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Kriteria</th>
                    <th>Tipe</th>
                    <th>Nilai Asli</th>
                    <th>Nilai Terformat</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['kriteriaValues'] as $kv)
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
    @endforeach

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem SPK Beasiswa KIP</p>
        <p>Data kriteria diambil secara dinamis sesuai konfigurasi sistem</p>
    </div>
</body>

</html>