<!-- resources/views/filament/admin/pages/bobot-ahp.blade.php -->
<x-filament-panels::page>

    <div>
        @php
        $kriteria = \App\Models\Kriteria::orderBy('kode')->get();
        $jumlahKriteria = $kriteria->count();
        @endphp

        <div class="space-y-6">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Perhitungan Bobot AHP</h1>
                <p class="text-gray-600">Analytical Hierarchy Process untuk Sistem Beasiswa KIP Kuliah</p>
            </div>

            <!-- Panduan Skala AHP -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    Panduan Skala AHP
                </h3>

                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span
                            class="w-8 h-8 !bg-blue-600 !text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 shadow-md border border-blue-700">1</span>
                        <span class="text-sm text-gray-700 dark:text-white">Sama penting</span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span
                            class="w-8 h-8 !bg-green-600 !text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 shadow-md border border-green-700">3</span>
                        <span class="text-sm text-gray-700 dark:text-white">Sedikit lebih penting</span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span
                            class="w-8 h-8 !bg-yellow-600 !text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 shadow-md border border-yellow-700">5</span>
                        <span class="text-sm text-gray-700 dark:text-white">Cukup penting</span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span
                            class="w-8 h-8 !bg-orange-600 !text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 shadow-md border border-orange-700">7</span>
                        <span class="text-sm text-gray-700 dark:text-white">Sangat penting</span>
                    </div>
                    <div class="flex items-center p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span
                            class="w-8 h-8 !bg-red-600 !text-white rounded-full flex items-center justify-center text-sm font-bold mr-3 shadow-md border border-red-700">9</span>
                        <span class="text-sm text-gray-700 dark:text-white">Mutlak lebih penting</span>
                    </div>
                </div>

                <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <p class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Petunjuk:</strong> Nilai 2, 4, 6, 8 adalah nilai tengah antara dua penilaian yang
                        berdekatan.
                        Isi hanya bagian segitiga atas matrix, bagian bawah akan otomatis terisi dengan nilai kebalikan.
                    </p>
                </div>
            </div>

            <!-- Matrix Perbandingan Berpasangan -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div
                    class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 00-2 2v0m16 0a2 2 0 00-2-2m-2 0H7m10 0v0a2 2 0 00-2-2H9a2 2 0 00-2 2v0m8 0V9">
                                    </path>
                                </svg>
                            </div>
                            Matrix Perbandingan Berpasangan ({{ $jumlahKriteria }}×{{ $jumlahKriteria }})
                        </h3>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b-2 border-gray-100 dark:border-gray-600">
                                    <th
                                        class="text-left py-4 px-3 font-semibold text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700">
                                        Kriteria</th>
                                    @foreach($kriteria as $k)
                                    <th
                                        class="text-center py-4 px-3 font-semibold text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 min-w-[120px]">
                                        <div class="flex flex-col items-center">
                                            <div
                                                class="w-10 h-10 !bg-blue-600 !text-black rounded-full flex items-center justify-center text-sm font-bold mb-2 shadow-md border border-blue-700">
                                                {{ $k->kode }}
                                            </div>
                                            <div
                                                class="text-xs text-gray-600 dark:text-gray-400 font-normal text-center">
                                                {{ Str::limit($k->nama, 15) }}
                                            </div>
                                        </div>
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100 dark:divide-gray-600"> @foreach($kriteria as $i =>
                                $kriteriaI)
                                <tr class="hover:bg-gray-50">
                                    <td class="py-4 px-3 font-semibold bg-gray-50 dark:bg-gray-700">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 !bg-blue-600 !text-black rounded-full flex items-center justify-center text-sm font-bold mr-3 shadow-md border border-blue-700">
                                                {{ $kriteriaI->kode }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-900 dark:text-gray-100">
                                                    {{ $kriteriaI->nama }}
                                                </div>
                                                <div class="text-xs text-gray-600 dark:text-gray-400">
                                                    {{ Str::limit($kriteriaI->nama, 20) }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    @foreach($kriteria as $j => $kriteriaJ)
                                    <td class="py-4 px-3 text-center">
                                        @if($i === $j)
                                        <!-- Diagonal = 1 -->
                                        <div
                                            class="w-16 h-16 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-black rounded-xl flex items-center justify-center text-sm font-semibold mx-auto border border-gray-300 dark:border-gray-500 shadow-sm">
                                            1
                                        </div>
                                        @elseif($i < $j) <!-- Upper triangle - Input -->
                                            <input type="number" min="1" max="9" step="1"
                                                class="w-16 h-16 text-center text-lg font-semibold border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-200 hover:border-blue-400 transition-all duration-200 mx-auto bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 shadow-sm placeholder-gray-400 dark:placeholder-gray-500"
                                                wire:model.lazy="data.matriks_{{ $kriteriaI->id }}_{{ $kriteriaJ->id }}"
                                                wire:change="updateMatrix({{ $i }}, {{ $j }}, $event.target.value)"
                                                placeholder="?"
                                                title="Bandingkan {{ $kriteriaI->nama }} vs {{ $kriteriaJ->nama }}" />
                                            @else
                                            <!-- Lower triangle - Auto calculated -->
                                            @php
                                            $upperValue = $data["matriks_{$kriteriaJ->id}_{$kriteriaI->id}"] ?? 1;
                                            $lowerValue = $upperValue > 0 ? round(1 / $upperValue, 3) : 1;
                                            @endphp
                                            <div
                                                class="w-16 h-16 bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-black rounded-xl flex items-center justify-center text-sm font-semibold mx-auto border border-gray-300 dark:border-gray-500 shadow-sm">
                                                {{ $lowerValue }}
                                            </div>
                                            @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Hasil Perhitungan -->
            @if($bobotResults)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4 flex items-center">
                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Hasil Perhitungan Bobot AHP
                </h3>

                <!-- Consistency Ratio -->
                <div
                    class="mb-6 p-4 rounded-lg {{ $consistencyRatio <= 0.1 ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                    <div class="flex items-center justify-between">
                        <div>
                            <span
                                class="text-sm font-medium {{ $consistencyRatio <= 0.1 ? 'text-green-800' : 'text-red-800' }}">
                                Consistency Ratio (CR):
                            </span>
                            <span
                                class="ml-2 text-lg font-bold {{ $consistencyRatio <= 0.1 ? 'text-green-600' : 'text-red-600' }}">
                                {{ round($consistencyRatio, 4) }}
                            </span>
                        </div>
                        <div
                            class="px-3 py-1 rounded-full text-xs font-bold {{ $consistencyRatio <= 0.1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $consistencyRatio <= 0.1 ? 'KONSISTEN' : 'TIDAK KONSISTEN' }}
                        </div>
                    </div>
                    <p class="text-sm {{ $consistencyRatio <= 0.1 ? 'text-green-700' : 'text-red-700' }} mt-2">
                        @if($consistencyRatio <= 0.1) Matrix perbandingan sudah konsisten dan dapat digunakan. @else
                            Matrix perbandingan tidak konsisten. Silakan periksa kembali nilai perbandingan. @endif </p>
                </div>

                <!-- Tabel Hasil -->
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                <th class="text-left py-4 px-6 font-semibold text-gray-900 dark:text-gray-100">Kode</th>
                                <th class="text-left py-4 px-6 font-semibold text-gray-900 dark:text-gray-100">Nama
                                    Kriteria</th>
                                <th class="text-center py-4 px-6 font-semibold text-gray-900 dark:text-gray-100">Bobot
                                </th>
                                <th class="text-center py-4 px-6 font-semibold text-gray-900 dark:text-gray-100">
                                    Persentase</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-600"> @foreach($kriteria as $index =>
                            $k)
                            <tr class="hover:bg-gray-50">
                                <td class="py-4 px-6">
                                    <div
                                        class="w-10 h-10 bg-blue-500 text-black rounded-full flex items-center justify-center font-bold">
                                        {{ $k->kode }}
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $k->nama }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="font-mono text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        @if($k->bobot && $k->bobot > 0)
                                        {{ number_format((float)$k->bobot, 6) }}
                                        @elseif(isset($bobotResults[$k->kode]))
                                        {{ number_format((float)$bobotResults[$k->kode], 6) }}
                                        @else
                                        -
                                        @endif
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @php
                                    $bobotValue = $k->bobot && $k->bobot > 0 ? (float)$k->bobot :
                                    (float)($bobotResults[$k->kode] ?? 0);
                                    @endphp
                                    @if($bobotValue > 0)
                                    <div class="flex items-center justify-center">
                                        <div class="w-20 bg-gray-200 rounded-full h-2 mr-3">
                                            <div class="bg-blue-500 h-2 rounded-full"
                                                x-data="{ width: {{ number_format((float)$bobotValue * 100, 1) }} }"
                                                :style="'width: ' + width + '%'"></div>
                                        </div>
                                        <span
                                            class="font-bold text-blue-600">{{ number_format((float)$bobotValue * 100, 2) }}%</span>
                                    </div>
                                    @else
                                    -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Detail Perhitungan AHP Step-by-Step -->
            <div class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 text-center mb-8 border-t pt-8">
                    Detail Perhitungan AHP Step-by-Step
                </h2>

                <!-- Step 1: Matrix Perbandingan Berpasangan -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <div
                                class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-4 text-black font-bold">
                                1.
                            </div>
                            Matrix Perbandingan Berpasangan
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            Matrix yang diisi berdasarkan perbandingan kepentingan antar kriteria menggunakan skala
                            Saaty 1-9
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-200 dark:border-gray-600">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100">
                                            Kriteria
                                        </th>
                                        @foreach($kriteria as $k)
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center">
                                            {{ $k->kode }}
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriteria as $i => $kriteriaI)
                                    <tr>
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            {{ $kriteriaI->kode }}
                                        </td>
                                        @foreach($kriteria as $j => $kriteriaJ)
                                        @php
                                        if($i === $j) {
                                        $cellValue = 1;
                                        } elseif($i < $j) { $cellValue=$data["matriks_{$kriteriaI->
                                            id}_{$kriteriaJ->id}"] ?? 1;
                                            } else {
                                            $upperValue = $data["matriks_{$kriteriaJ->id}_{$kriteriaI->id}"] ?? 1;
                                            $cellValue = $upperValue > 0 ? round(1 / $upperValue, 6) : 1;
                                            }
                                            @endphp
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center text-gray-900 dark:text-gray-100">
                                                {{ number_format($cellValue, 3) }}
                                            </td>
                                            @endforeach
                                    </tr>
                                    @endforeach

                                    <!-- Baris Jumlah Kolom -->
                                    <tr class="bg-gray-100 dark:bg-gray-600">
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100">
                                            Jumlah
                                        </td>
                                        @foreach($kriteria as $j => $kriteriaJ)
                                        @php
                                        $columnSum = 0;
                                        foreach($kriteria as $i => $kriteriaI) {
                                        if($i === $j) {
                                        $columnSum += 1;
                                        } elseif($i < $j) { $columnSum +=$data["matriks_{$kriteriaI->
                                            id}_{$kriteriaJ->id}"] ?? 1;
                                            } else {
                                            $upperValue = $data["matriks_{$kriteriaJ->id}_{$kriteriaI->id}"] ?? 1;
                                            $columnSum += $upperValue > 0 ? round(1 / $upperValue, 6) : 1;
                                            }
                                            }
                                            @endphp
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center text-gray-900 dark:text-gray-100 font-semibold">
                                                {{ number_format($columnSum, 3) }}
                                            </td>
                                            @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Contoh Perhitungan Jumlah Kolom -->
                        <div class="mt-4">
                            <div
                                class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg border border-green-200 dark:border-green-700">
                                <p class="text-sm font-semibold text-green-800 dark:text-green-200 mb-2">📘 Contoh
                                    Perhitungan Jumlah Kolom C1:</p>
                                <div class="text-xs text-green-700 dark:text-green-300 space-y-1">
                                    @php
                                    $columnSum = 0;
                                    $calculations = [];
                                    foreach($kriteria as $i => $kriteriaI) {
                                    if($i === 0) {
                                    $cellValue = 1;
                                    } elseif($i < 0) { $cellValue=$data["matriks_{$kriteriaI->id}_{$kriteria[0]->id}"]
                                        ?? 1;
                                        } else {
                                        $upperValue = $data["matriks_{$kriteria[0]->id}_{$kriteriaI->id}"] ?? 1;
                                        $cellValue = $upperValue > 0 ? round(1 / $upperValue, 3) : 1;
                                        }
                                        $columnSum += $cellValue;
                                        $calculations[] = number_format($cellValue, 3);
                                        }
                                        @endphp
                                        <p>• Jumlah C1 = {{ implode(' + ', $calculations) }} =
                                            <strong>{{ number_format($columnSum, 3) }}</strong>
                                        </p>
                                        <p>• Nilai ini digunakan untuk normalisasi setiap elemen di kolom C1</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($bobotResults && isset($matrixNormalisasi))
                <!-- Step 2: Matrix Normalisasi -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <div
                                class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-4 text-black font-bold">
                                2.
                            </div>
                            Matrix Normalisasi
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            Setiap elemen matrix dibagi dengan jumlah kolom untuk mendapatkan matrix ternormalisasi
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Rumus Normalisasi:</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    n<sub>ij</sub> = a<sub>ij</sub> / Σa<sub>ij</sub>
                                </code>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    dimana n<sub>ij</sub> = elemen ternormalisasi, a<sub>ij</sub> = elemen matrix asli
                                </p>
                            </div>
                            <div class="bg-blue-100 dark:bg-blue-700 p-3 rounded-lg mt-2">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    Prioritas<sub>i</sub> = Jumlah<sub>i</sub> / n (rata-rata baris)
                                </code>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    dimana n = {{ count($kriteria) }} (jumlah kriteria)
                                </p>
                            </div>

                            <!-- Contoh Perhitungan Normalisasi -->
                            <div
                                class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg mt-3 border border-green-200 dark:border-green-700">
                                <p class="text-sm font-semibold text-green-800 dark:text-green-200 mb-2">📘 Contoh
                                    Perhitungan Normalisasi (C1 vs C1):</p>
                                <div class="text-xs text-green-700 dark:text-green-300 space-y-1">
                                    @php
                                    // Calculate actual column sum for C1
                                    $c1ColumnSum = 0;
                                    foreach($kriteria as $i => $kriteriaI) {
                                    if($i === 0) {
                                    $cellValue = 1;
                                    } elseif($i < 0) { $cellValue=$data["matriks_{$kriteriaI->id}_{$kriteria[0]->id}"]
                                        ?? 1;
                                        } else {
                                        $upperValue = $data["matriks_{$kriteria[0]->id}_{$kriteriaI->id}"] ?? 1;
                                        $cellValue = $upperValue > 0 ? round(1 / $upperValue, 6) : 1;
                                        }
                                        $c1ColumnSum += $cellValue;
                                        }
                                        $normalizedValue = 1 / $c1ColumnSum;
                                        @endphp
                                        <p>• a<sub>11</sub> = 1.000 (nilai C1 vs C1 dari matrix perbandingan)</p>
                                        <p>• Σa<sub>1j</sub> = {{ number_format($c1ColumnSum, 3) }} (jumlah kolom C1)
                                        </p>
                                        <p>• n<sub>11</sub> = 1.000 ÷ {{ number_format($c1ColumnSum, 3) }} =
                                            <strong>{{ number_format($normalizedValue, 4) }}</strong>
                                        </p>
                                </div>
                            </div>

                            <!-- Contoh Perhitungan Prioritas -->
                            <div
                                class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg mt-2 border border-orange-200 dark:border-orange-700">
                                <p class="text-sm font-semibold text-orange-800 dark:text-orange-200 mb-2">📘 Contoh
                                    Perhitungan Prioritas (C1):</p>
                                <div class="text-xs text-orange-700 dark:text-orange-300 space-y-1">
                                    @if(isset($matrixNormalisasi[0]))
                                    @php
                                    $rowSum = array_sum($matrixNormalisasi[0]);
                                    $priority = $rowSum / count($kriteria);
                                    $rowValues = array_map(function($val) { return number_format($val, 4); },
                                    $matrixNormalisasi[0]);
                                    @endphp
                                    <p>• Jumlah baris C1 = {{ implode(' + ', $rowValues) }} =
                                        {{ number_format($rowSum, 4) }}
                                    </p>
                                    <p>• Prioritas C1 = {{ number_format($rowSum, 4) }} ÷ {{ count($kriteria) }} =
                                        <strong>{{ number_format($priority, 4) }}</strong>
                                    </p>
                                    @else
                                    <p>• Data normalisasi belum tersedia</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-200 dark:border-gray-600">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100">
                                            Kriteria
                                        </th>
                                        @foreach($kriteria as $k)
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center">
                                            {{ $k->kode }}
                                        </th>
                                        @endforeach
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center bg-yellow-50 dark:bg-yellow-900/30">
                                            Jumlah
                                        </th>
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center bg-blue-50 dark:bg-blue-900/30">
                                            Prioritas
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriteria as $i => $kriteriaI)
                                    <tr>
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            {{ $kriteriaI->kode }}
                                        </td>
                                        @php
                                        $rowSum = 0;
                                        @endphp
                                        @foreach($kriteria as $j => $kriteriaJ)
                                        @php
                                        $normalizedValue = $matrixNormalisasi[$i][$j] ?? 0;
                                        $rowSum += $normalizedValue;
                                        @endphp
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center text-gray-900 dark:text-gray-100">
                                            @if(isset($matrixNormalisasi[$i][$j]))
                                            {{ number_format($matrixNormalisasi[$i][$j], 4) }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                        @endforeach
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-yellow-50 dark:bg-yellow-900/30 font-semibold text-yellow-700 dark:text-yellow-300">
                                            {{ number_format($rowSum, 4) }}
                                        </td>
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-blue-50 dark:bg-blue-900/30 font-bold text-blue-600">
                                            @php
                                            $bobotValue = $kriteriaI->bobot && $kriteriaI->bobot > 0 ?
                                            (float)$kriteriaI->bobot
                                            : (float)($bobotResults[$kriteriaI->kode] ?? 0);
                                            @endphp
                                            @if($bobotValue > 0)
                                            {{ number_format($bobotValue, 4) }}
                                            @else
                                            -
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach

                                    <!-- Baris Total -->
                                    <tr class="bg-gray-100 dark:bg-gray-600">
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100">
                                            Total
                                        </td>
                                        @foreach($kriteria as $j => $kriteriaJ)
                                        @php
                                        $columnSum = 0;
                                        foreach($kriteria as $i => $kriteriaI) {
                                        $columnSum += $matrixNormalisasi[$i][$j] ?? 0;
                                        }
                                        @endphp
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center text-gray-900 dark:text-gray-100 font-semibold">
                                            {{ number_format($columnSum, 4) }}
                                        </td>
                                        @endforeach
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-yellow-50 dark:bg-yellow-900/30 font-semibold">
                                            @php
                                            $totalSum = 0;
                                            foreach($kriteria as $i => $kriteriaI) {
                                            foreach($kriteria as $j => $kriteriaJ) {
                                            $totalSum += $matrixNormalisasi[$i][$j] ?? 0;
                                            }
                                            }
                                            @endphp
                                            {{ number_format($totalSum, 4) }}
                                        </td>
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-blue-50 dark:bg-blue-900/30 font-bold text-blue-600">
                                            @php
                                            $totalBobot = 0;
                                            foreach($kriteria as $k) {
                                            $bobotValue = $k->bobot && $k->bobot > 0 ? (float)$k->bobot :
                                            (float)($bobotResults[$k->kode] ?? 0);
                                            $totalBobot += $bobotValue;
                                            }
                                            @endphp
                                            {{ number_format((float)$totalBobot, 4) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Matrix Penjumlahan (Weighted Sum) -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-yellow-50 to-yellow-100 dark:from-yellow-900/30 dark:to-yellow-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <div
                                class="w-10 h-10 bg-yellow-600 rounded-lg flex items-center justify-center mr-4 text-black font-bold">
                                3.
                            </div>
                            Matrix Penjumlahan (Weighted Sum Vector)
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            Mengalikan matrix perbandingan dengan vektor bobot untuk menghitung λmax
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="mb-4">
                            <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-2">Rumus Weighted Sum:</h4>
                            <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg">
                                <code class="text-sm text-gray-800 dark:text-gray-200">
                                    WS<sub>i</sub> = Σ(a<sub>ij</sub> × w<sub>j</sub>)
                                </code>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                    dimana WS<sub>i</sub> = weighted sum baris ke-i, w<sub>j</sub> = bobot kriteria ke-j
                                </p>
                            </div>

                            <!-- Contoh Perhitungan Weighted Sum -->
                            <div
                                class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg mt-3 border border-green-200 dark:border-green-700">
                                <p class="text-sm font-semibold text-green-800 dark:text-green-200 mb-2">📘 Contoh
                                    Perhitungan Weighted Sum (C1):</p>
                                <div class="text-xs text-green-700 dark:text-green-300 space-y-1">
                                    @php
                                    // Calculate weighted sum for first criteria (C1)
                                    $wsCalculations = [];
                                    $wsValues = [];
                                    $totalWS = 0;

                                    foreach($kriteria as $j => $kriteriaJ) {
                                    // Get matrix value for C1 row
                                    if(0 === $j) {
                                    $matrixValue = 1;
                                    } elseif(0 < $j) { $matrixValue=$data["matriks_{$kriteria[0]->id}_{$kriteriaJ->id}"]
                                        ?? 1;
                                        } else {
                                        $upperValue = $data["matriks_{$kriteriaJ->id}_{$kriteria[0]->id}"] ?? 1;
                                        $matrixValue = $upperValue > 0 ? round(1 / $upperValue, 6) : 1;
                                        }

                                        // Get weight
                                        $weight = $kriteriaJ->bobot && $kriteriaJ->bobot > 0 ? (float)$kriteriaJ->bobot
                                        :
                                        (float)($bobotResults[$kriteriaJ->kode] ?? 0);

                                        $calculation = $matrixValue * $weight;
                                        $totalWS += $calculation;

                                        $wsCalculations[] = "(" . number_format($matrixValue, 3) . " × " .
                                        number_format($weight, 4) . ")";
                                        $wsValues[] = number_format($calculation, 4);
                                        }
                                        @endphp
                                        <p>• WS<sub>1</sub> = {{ implode(' + ', $wsCalculations) }}</p>
                                        <p>• WS<sub>1</sub> = {{ implode(' + ', $wsValues) }} =
                                            <strong>{{ number_format($totalWS, 4) }}</strong>
                                        </p>
                                </div>
                            </div>

                            <!-- Contoh Perhitungan Lambda -->
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 p-3 rounded-lg mt-2 border border-blue-200 dark:border-blue-700">
                                <p class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-2">📘 Contoh
                                    Perhitungan λ (C1):</p>
                                <div class="text-xs text-blue-700 dark:text-blue-300 space-y-1">
                                    @php
                                    $firstWeight = $kriteria[0]->bobot && $kriteria[0]->bobot > 0 ?
                                    (float)$kriteria[0]->bobot
                                    : (float)($bobotResults[$kriteria[0]->kode] ?? 0);
                                    $lambda = $firstWeight > 0 ? $totalWS / $firstWeight : 0;
                                    @endphp
                                    <p>• λ<sub>1</sub> = WS<sub>1</sub> ÷ w<sub>1</sub></p>
                                    <p>• λ<sub>1</sub> = {{ number_format($totalWS, 4) }} ÷
                                        {{ number_format($firstWeight, 4) }} =
                                        <strong>{{ number_format($lambda, 4) }}</strong>
                                    </p>
                                </div>
                            </div>
                        </div>

                        @if(isset($weightedSum))
                        <div class="overflow-x-auto">
                            <table class="w-full border border-gray-200 dark:border-gray-600">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100">
                                            Kriteria
                                        </th>
                                        @foreach($kriteria as $k)
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center">
                                            {{ $k->kode }}
                                        </th>
                                        @endforeach
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center bg-yellow-50 dark:bg-yellow-900/30">
                                            Weighted Sum
                                        </th>
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center bg-green-50 dark:bg-green-900/30">
                                            Bobot (w)
                                        </th>
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100 text-center bg-blue-50 dark:bg-blue-900/30">
                                            λ = WS/w
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($kriteria as $i => $kriteriaI)
                                    <tr>
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                            {{ $kriteriaI->kode }}
                                        </td>
                                        @foreach($kriteria as $j => $kriteriaJ)
                                        @php
                                        if($i === $j) {
                                        $cellValue = 1;
                                        } elseif($i < $j) { $cellValue=$data["matriks_{$kriteriaI->
                                            id}_{$kriteriaJ->id}"] ?? 1;
                                            } else {
                                            $upperValue = $data["matriks_{$kriteriaJ->id}_{$kriteriaI->id}"] ?? 1;
                                            $cellValue = $upperValue > 0 ? round(1 / $upperValue, 6) : 1;
                                            }
                                            @endphp
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center text-gray-900 dark:text-gray-100">
                                                {{ number_format($cellValue, 3) }}
                                            </td>
                                            @endforeach
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-yellow-50 dark:bg-yellow-900/30 font-semibold text-yellow-700 dark:text-yellow-300">
                                                @if(isset($weightedSum[$i]))
                                                {{ number_format($weightedSum[$i], 4) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-green-50 dark:bg-green-900/30 font-semibold text-green-700 dark:text-green-300">
                                                @php
                                                $bobotValue = $kriteriaI->bobot && $kriteriaI->bobot > 0 ?
                                                (float)$kriteriaI->bobot
                                                : (float)($bobotResults[$kriteriaI->kode] ?? 0);
                                                @endphp
                                                @if($bobotValue > 0)
                                                {{ number_format($bobotValue, 4) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-blue-50 dark:bg-blue-900/30 font-bold text-blue-600">
                                                @if(isset($weightedSum[$i]) && $bobotValue > 0)
                                                {{ number_format((float)$weightedSum[$i] / $bobotValue, 4) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                    </tr>
                                    @endforeach

                                    <!-- Baris Total -->
                                    <tr class="bg-gray-100 dark:bg-gray-600">
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-3 px-4 font-semibold text-gray-900 dark:text-gray-100">
                                            Total
                                        </td>
                                        @foreach($kriteria as $j => $kriteriaJ)
                                        @php
                                        $columnSum = 0;
                                        foreach($kriteria as $i => $kriteriaI) {
                                        if($i === $j) {
                                        $columnSum += 1;
                                        } elseif($i < $j) { $columnSum +=$data["matriks_{$kriteriaI->
                                            id}_{$kriteriaJ->id}"] ?? 1;
                                            } else {
                                            $upperValue = $data["matriks_{$kriteriaJ->id}_{$kriteriaI->id}"] ?? 1;
                                            $columnSum += $upperValue > 0 ? round(1 / $upperValue, 6) : 1;
                                            }
                                            }
                                            @endphp
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center text-gray-900 dark:text-gray-100 font-semibold">
                                                {{ number_format($columnSum, 3) }}
                                            </td>
                                            @endforeach
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-yellow-50 dark:bg-yellow-900/30 font-semibold text-yellow-700 dark:text-yellow-300">
                                                @php
                                                $totalWS = 0;
                                                foreach($weightedSum as $ws) {
                                                $totalWS += $ws;
                                                }
                                                @endphp
                                                {{ number_format($totalWS, 4) }}
                                            </td>
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-green-50 dark:bg-green-900/30 font-semibold text-green-700 dark:text-green-300">
                                                @php
                                                $totalBobot = 0;
                                                foreach($kriteria as $k) {
                                                $bobotValue = $k->bobot && $k->bobot > 0 ? (float)$k->bobot :
                                                (float)($bobotResults[$k->kode] ?? 0);
                                                $totalBobot += $bobotValue;
                                                }
                                                @endphp
                                                {{ number_format((float)$totalBobot, 4) }}
                                            </td>
                                            <td
                                                class="border border-gray-200 dark:border-gray-600 py-3 px-4 text-center bg-blue-50 dark:bg-blue-900/30 font-bold text-blue-600">
                                                @if(isset($lambdaMax))
                                                {{ number_format($lambdaMax, 4) }}
                                                @else
                                                -
                                                @endif
                                            </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        @if(isset($lambdaMax))
                        <div class="mt-4 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <div class="flex items-center justify-between">
                                <span class="font-semibold text-blue-800 dark:text-blue-200">λmax (Lambda Max):</span>
                                <span class="text-xl font-bold text-blue-600">{{ number_format($lambdaMax, 4) }}</span>
                            </div>
                            <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">
                                λmax = rata-rata dari kolom λ = WS/w
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Step 4: Perhitungan Consistency Ratio -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div
                        class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-900/20 px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                            <div
                                class="w-10 h-10 bg-red-600 rounded-lg flex items-center justify-center mr-4 text-black font-bold">
                                4.
                            </div>
                            Perhitungan Consistency Ratio (CR)
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                            Mengukur konsistensi penilaian dalam matrix perbandingan berpasangan
                        </p>
                    </div>

                    <div class="p-6">
                        <div class="grid md:grid-cols-2 gap-6">
                            <!-- Rumus dan Perhitungan -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Rumus Perhitungan:</h4>

                                <div class="space-y-4">
                                    <!-- CI Formula -->
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                        <div class="font-medium text-gray-900 dark:text-gray-100 mb-2">1. Consistency
                                            Index (CI):</div>
                                        <code class="text-sm text-gray-800 dark:text-gray-200">
                                            CI = (λmax - n) / (n - 1)
                                        </code>
                                        <div class="mt-2 text-sm">
                                            <div class="text-gray-600 dark:text-gray-400">dimana:</div>
                                            <div class="text-gray-800 dark:text-gray-200">n = jumlah kriteria =
                                                {{ count($kriteria) }}
                                            </div>
                                            @if(isset($lambdaMax))
                                            <div class="text-gray-800 dark:text-gray-200">λmax =
                                                {{ number_format($lambdaMax, 4) }}
                                            </div>
                                            <div class="font-bold text-blue-600">CI =
                                                {{ number_format($consistencyIndex ?? 0, 4) }}
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Contoh Perhitungan CI -->
                                        @if(isset($lambdaMax) && isset($consistencyIndex))
                                        <div
                                            class="bg-green-50 dark:bg-green-900/20 p-3 rounded-lg mt-3 border border-green-200 dark:border-green-700">
                                            <p class="text-sm font-semibold text-green-800 dark:text-green-200 mb-2">📘
                                                Contoh Perhitungan CI:</p>
                                            <div class="text-xs text-green-700 dark:text-green-300 space-y-1">
                                                <p>• CI = ({{ number_format($lambdaMax, 4) }} - {{ count($kriteria) }})
                                                    ÷ ({{ count($kriteria) }} - 1)</p>
                                                <p>• CI = {{ number_format($lambdaMax - count($kriteria), 4) }} ÷
                                                    {{ count($kriteria) - 1 }} =
                                                    <strong>{{ number_format($consistencyIndex, 4) }}</strong>
                                                </p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <!-- CR Formula -->
                                    <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                        <div class="font-medium text-gray-900 dark:text-gray-100 mb-2">2. Consistency
                                            Ratio (CR):</div>
                                        <code class="text-sm text-gray-800 dark:text-gray-200">
                                            CR = CI / RI
                                        </code>
                                        <div class="mt-2 text-sm">
                                            <div class="text-gray-600 dark:text-gray-400">dimana:</div>
                                            <div class="text-gray-800 dark:text-gray-200">RI = Random Index =
                                                {{ $randomIndex ?? '-' }}
                                            </div>
                                            @if(isset($consistencyIndex) && isset($randomIndex))
                                            <div class="font-bold text-red-600">CR =
                                                {{ number_format($consistencyRatio, 4) }}
                                            </div>
                                            @endif
                                        </div>

                                        <!-- Contoh Perhitungan CR -->
                                        @if(isset($consistencyIndex) && isset($randomIndex) && isset($consistencyRatio))
                                        <div
                                            class="bg-orange-50 dark:bg-orange-900/20 p-3 rounded-lg mt-3 border border-orange-200 dark:border-orange-700">
                                            <p class="text-sm font-semibold text-orange-800 dark:text-orange-200 mb-2">
                                                📘 Contoh Perhitungan CR:</p>
                                            <div class="text-xs text-orange-700 dark:text-orange-300 space-y-1">
                                                <p>• CR = {{ number_format($consistencyIndex, 4) }} ÷
                                                    {{ number_format($randomIndex, 2) }}
                                                </p>
                                                <p>• CR = <strong>{{ number_format($consistencyRatio, 4) }}</strong></p>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Tabel Random Index -->
                            <div>
                                <h4 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Tabel Random Index (RI):
                                </h4>

                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm border border-gray-200 dark:border-gray-600">
                                        <thead>
                                            <tr class="bg-gray-50 dark:bg-gray-700">
                                                <th
                                                    class="border border-gray-200 dark:border-gray-600 py-2 px-3 font-semibold text-gray-900 dark:text-gray-100">
                                                    n</th>
                                                <th
                                                    class="border border-gray-200 dark:border-gray-600 py-2 px-3 font-semibold text-gray-900 dark:text-gray-100">
                                                    RI</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                            $riValues = [1 => 0, 2 => 0, 3 => 0.58, 4 => 0.9, 5 => 1.12, 6 => 1.24, 7 =>
                                            1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49, 11 => 1.51, 12 => 1.48, 13 => 1.56,
                                            14 => 1.57, 15 => 1.59];
                                            @endphp
                                            @for($n = 1; $n <= 15; $n++) <tr
                                                class="{{ $n == count($kriteria) ? 'bg-yellow-50 dark:bg-yellow-900/20 font-bold' : '' }}">
                                                <td
                                                    class="border border-gray-200 dark:border-gray-600 py-2 px-3 text-center text-gray-900 dark:text-gray-100">
                                                    {{ $n }}
                                                </td>
                                                <td
                                                    class="border border-gray-200 dark:border-gray-600 py-2 px-3 text-center text-gray-900 dark:text-gray-100">
                                                    {{ $riValues[$n] }}
                                                </td>
                                                </tr>
                                                @endfor
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                                    <p><strong>Highlighted:</strong> n = {{ count($kriteria) }}, RI =
                                        {{ $randomIndex ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Hasil dan Interpretasi -->
                        <div
                            class="mt-6 p-4 rounded-lg {{ $consistencyRatio <= 0.1 ? 'bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700' : 'bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700' }}">
                            <div class="flex items-center justify-between mb-4">
                                <h4
                                    class="font-bold {{ $consistencyRatio <= 0.1 ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200' }}">
                                    Hasil Consistency Ratio:
                                </h4>
                                <div class="flex items-center space-x-3">
                                    <span
                                        class="text-2xl font-bold {{ $consistencyRatio <= 0.1 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($consistencyRatio, 4) }}
                                    </span>
                                    <div
                                        class="px-3 py-1 rounded-full text-xs font-bold {{ $consistencyRatio <= 0.1 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $consistencyRatio <= 0.1 ? 'KONSISTEN' : 'TIDAK KONSISTEN' }}
                                    </div>
                                </div>
                            </div>

                            <div
                                class="text-sm {{ $consistencyRatio <= 0.1 ? 'text-green-700 dark:text-green-300' : 'text-red-700 dark:text-red-300' }}">
                                <p class="mb-2"><strong>Interpretasi:</strong></p>
                                <ul class="list-disc ml-5 space-y-1">
                                    @if($consistencyRatio <= 0.1) <li>CR ≤ 0.1: Matrix perbandingan
                                        <strong>KONSISTEN</strong></li>
                                        <li>Penilaian perbandingan sudah tepat dan dapat diterima</li>
                                        <li>Bobot yang dihasilkan dapat digunakan untuk pengambilan keputusan</li>
                                        @else
                                        <li>CR > 0.1: Matrix perbandingan <strong>TIDAK KONSISTEN</strong></li>
                                        <li>Perlu meninjau kembali nilai perbandingan yang telah diinput</li>
                                        <li>Disarankan untuk merevisi matrix perbandingan</li>
                                        @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            @endif
            @endif
        </div>
    </div>
</x-filament-panels::page>