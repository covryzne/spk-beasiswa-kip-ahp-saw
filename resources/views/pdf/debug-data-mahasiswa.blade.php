<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DEBUG - Data Mahasiswa</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .debug {
            background-color: #f8f9fa;
            padding: 15px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="debug">
        <h3>DEBUG INFO</h3>
        <p><strong>Title:</strong> {{ $title ?? 'NOT SET' }}</p>
        <p><strong>Generated At:</strong> {{ $generated_at ?? 'NOT SET' }}</p>
        <p><strong>Mahasiswa Object:</strong> {{ $mahasiswa ? 'EXISTS' : 'NULL' }}</p>

        @if($mahasiswa)
        <p><strong>Mahasiswa ID:</strong> {{ $mahasiswa->id ?? 'NULL' }}</p>
        <p><strong>Mahasiswa Nama:</strong> {{ $mahasiswa->nama ?? 'NULL' }}</p>
        <p><strong>Program Studi:</strong> {{ $mahasiswa->program_studi ?? 'NULL' }}</p>
        <p><strong>KIP Status:</strong> {{ $mahasiswa->kip_status ?? 'NULL' }}</p>
        <p><strong>Penghasilan:</strong> {{ $mahasiswa->penghasilan_orang_tua ?? 'NULL' }}</p>

        <h4>All Data:</h4>
        <pre style="font-size: 10px;">{{ print_r($mahasiswa->toArray(), true) }}</pre>
        @else
        <p style="color: red;">MAHASISWA OBJECT IS NULL!</p>
        @endif
    </div>
</body>

</html>