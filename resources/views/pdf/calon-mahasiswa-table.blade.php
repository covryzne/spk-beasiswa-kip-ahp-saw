<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
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

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #28a745;
            color: white;
            padding: 8px 6px;
            border: 1px solid #ddd;
            font-weight: bold;
            text-align: center;
            font-size: 8px;
        }

        .data-table td {
            padding: 6px 4px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
        }

        .data-table tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        .data-table tr:hover {
            background-color: #e9ecef;
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
            .data-table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>CALON MAHASISWA - SEMUA DATA</h1>
        <p>Sistem Pendukung Keputusan Beasiswa KIP</p>
        <p>Digenerate pada: {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <strong>Total Data: {{ $total }} Calon Mahasiswa</strong>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama</th>
                <th>Program Studi</th>
                @foreach($kriteria as $k)
                <th>{{ $k->nama }}</th>
                @endforeach
                <th>Tanggal Daftar</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswaData as $index => $data)
            @php
            $mahasiswa = $data['mahasiswa'];
            $kriteriaValues = $data['kriteriaValues'];
            @endphp
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mahasiswa->kode }}</td>
                <td style="text-align: left;">{{ $mahasiswa->nama }}</td>
                <td style="text-align: left;">{{ $mahasiswa->dataMahasiswa ? $mahasiswa->dataMahasiswa->program_studi : '-' }}</td>
                @foreach($kriteriaValues as $kv)
                <td>{{ $kv['formatted_value'] }}</td>
                @endforeach
                <td>{{ $mahasiswa->created_at->format('d M Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini digenerate otomatis oleh sistem SPK Beasiswa KIP</p>
    </div>
</body>

</html>