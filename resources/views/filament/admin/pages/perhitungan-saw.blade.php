<!-- resources/views/filament/admin/pages/proses-seleksi.blade.php -->
<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">Proses Seleksi SAW</h1>
            <p class="text-gray-600 dark:text-gray-400">Simple Additive Weighting untuk Sistem Beasiswa KIP Kuliah</p>
        </div>

        @if(!$isAhpReady)
        <!-- AHP Not Ready Warning -->
        <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-medium text-red-800 dark:text-red-200">Perhitungan AHP Belum Siap</h3>
                    <div class="mt-2 text-sm text-red-700 dark:text-red-300">
                        <p>Anda perlu melakukan perhitungan AHP terlebih dahulu untuk mendapatkan bobot kriteria sebelum
                            melanjutkan proses seleksi SAW.</p>
                    </div>
                </div>
            </div>
        </div>
        @else

        <!-- Prerequisites Check -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                <div class="w-8 h-8 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                Status Prasyarat
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div
                    class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-800 dark:text-green-200 font-medium">Bobot AHP Siap</span>
                    </div>
                    <div class="text-sm text-green-600 dark:text-green-400 mt-1">{{ count($bobotAhp) }} kriteria
                        tersedia</div>
                </div>

                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-blue-800 dark:text-blue-200 font-medium">Data Mahasiswa</span>
                    </div>
                    <div class="text-sm text-blue-600 dark:text-blue-400 mt-1">{{ count($calonMahasiswa) }} calon
                        tersedia</div>
                </div>

                <div
                    class="bg-{{ $isCalculated ? 'green' : 'gray' }}-50 dark:bg-{{ $isCalculated ? 'green' : 'gray' }}-900/20 border border-{{ $isCalculated ? 'green' : 'gray' }}-200 dark:border-{{ $isCalculated ? 'green' : 'gray' }}-800 rounded-lg p-4">
                    <div class="flex items-center">
                        @if($isCalculated)
                        <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-green-800 dark:text-green-200 font-medium">SAW Selesai</span>
                        @else
                        <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="text-gray-600 dark:text-gray-400 font-medium">Belum Dihitung</span>
                        @endif
                    </div>
                    <div
                        class="text-sm text-{{ $isCalculated ? 'green' : 'gray' }}-600 dark:text-{{ $isCalculated ? 'green' : 'gray' }}-400 mt-1">
                        {{ $isCalculated ? 'Perhitungan lengkap' : 'Siap untuk dihitung' }}
                    </div>
                </div>
            </div>
        </div>

        @if($isCalculated)
        <!-- Step-by-step Calculation -->
        <div class="space-y-6">

            <!-- Step 1: Bobot AHP -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <span
                            class="w-8 h-8 bg-blue-600 text-black rounded-full flex items-center justify-center text-lg font-bold mr-3">1.</span>
                        Bobot Kriteria (dari AHP)
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                        @foreach($bobotAhp as $kode => $bobot)
                        @php
                        $kriteriaItem = collect($kriteria)->firstWhere('kode', $kode);
                        $namaKriteria = $kriteriaItem ? $kriteriaItem['nama'] : $kode;
                        @endphp
                        <div class="text-center">
                            <div
                                class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
                                <div class="text-sm font-medium text-blue-600 dark:text-blue-400 mb-2">{{ $kode }}</div>
                                <div class="text-lg font-bold text-blue-900 dark:text-blue-100">{{ round($bobot, 4) }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ Str::limit($namaKriteria, 20) }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Step 2: Decision Matrix -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <span
                            class="w-8 h-8 bg-green-600 text-black rounded-full flex items-center justify-center text-lg font-bold mr-3">2.</span>
                        Matrix Keputusan
                    </h3>
                    <button type="button" wire:click="toggleStep('decision')"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        {{ $showDecisionMatrix ? 'Sembunyikan' : 'Tampilkan' }}
                    </button>
                </div>
                @if($showDecisionMatrix)
                <div class="p-6">
                    <!-- Explanation for decision matrix -->
                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-green-800 dark:text-green-200 mb-2">Matrix Keputusan (X):
                        </h4>
                        <div class="text-sm text-green-700 dark:text-green-300">
                            Matrix berisi nilai asli setiap alternatif untuk masing-masing kriteria. Nilai ini akan
                            dinormalisasi berdasarkan jenis kriteria (benefit/cost).
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200 dark:border-gray-600">
                                    <th
                                        class="text-left py-3 px-3 font-semibold text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700">
                                        Alternatif</th>
                                    @foreach($kriteria as $k)
                                    <th
                                        class="text-center py-3 px-3 font-semibold text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700">
                                        {{ $k['kode'] }} ({{ ucfirst($k['jenis']) }})
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($decisionMatrix as $kode => $values)
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="py-3 px-3 font-medium text-gray-900 dark:text-gray-100">{{ $kode }}</td>
                                    @foreach($kriteria as $k)
                                    <td class="py-3 px-3 text-center text-gray-700 dark:text-gray-300">
                                        @if($k['kode'] === 'C1')
                                        {{ number_format($values[$k['kode']] ?? 0) }}
                                        @else
                                        {{ $values[$k['kode']] ?? 0 }}
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Matrix Analysis -->
                    @if(!empty($decisionMatrix) && !empty($kriteria))
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">📊 Analisis Matrix per
                            Kriteria:</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($kriteria as $k)
                            @php
                            $kriteriaKode = $k['kode'];
                            $kriteriaJenis = $k['jenis'];
                            $kriterianama = $k['nama'];

                            // Ambil semua nilai untuk kriteria ini
                            $allValues = collect($decisionMatrix)->pluck($kriteriaKode)->filter()->toArray();
                            $maxValue = max($allValues);
                            $minValue = min($allValues);
                            $avgValue = array_sum($allValues) / count($allValues);
                            @endphp

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">
                                    {{ $kriteriaKode }} - {{ Str::limit($kriterianama, 25) }}
                                </h5>
                                <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                    <div class="flex justify-between">
                                        <span>Jenis:</span>
                                        <span
                                            class="font-medium {{ $kriteriaJenis === 'benefit' ? 'text-green-600' : 'text-red-600' }}">
                                            {{ ucfirst($kriteriaJenis) }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Min:</span>
                                        <span
                                            class="font-medium">{{ $kriteriaKode === 'C1' ? number_format($minValue) : $minValue }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Max:</span>
                                        <span
                                            class="font-medium">{{ $kriteriaKode === 'C1' ? number_format($maxValue) : $maxValue }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span>Rata-rata:</span>
                                        <span
                                            class="font-medium">{{ $kriteriaKode === 'C1' ? number_format($avgValue, 0) : round($avgValue, 2) }}</span>
                                    </div>
                                    @if($kriteriaJenis === 'benefit')
                                    <div class="text-xs text-green-600 dark:text-green-400 mt-2">
                                        ↑ Nilai tertinggi terbaik
                                    </div>
                                    @else
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-2">
                                        ↓ Nilai terendah terbaik
                                    </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Step 3: Normalization -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <span
                            class="w-8 h-8 bg-yellow-600 text-black rounded-full flex items-center justify-center text-lg font-bold mr-3">3.</span>
                        Matrix Normalisasi
                    </h3>
                    <button type="button" wire:click="toggleStep('normalization')"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        {{ $showNormalization ? 'Sembunyikan' : 'Tampilkan' }}
                    </button>
                </div>
                @if($showNormalization)
                <div class="p-6">
                    <!-- Formula explanation -->
                    <div
                        class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Formula Normalisasi:
                        </h4>
                        <div class="text-sm text-yellow-700 dark:text-yellow-300 space-y-1">
                            <div><strong>Benefit:</strong> Rij = Xij / max(Xij)</div>
                            <div><strong>Cost:</strong> Rij = min(Xij) / Xij</div>
                        </div>
                    </div>

                    <!-- Manual Calculation Example -->
                    @if(!empty($decisionMatrix) && !empty($kriteria))
                    <div
                        class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-blue-800 dark:text-blue-200 mb-3">📘 Contoh Perhitungan
                            Manual:</h4>

                        @php
                        // Ambil data pertama untuk contoh
                        $firstKriteria = collect($kriteria)->first();
                        $kriteriaKode = $firstKriteria['kode'];
                        $kriteriaJenis = $firstKriteria['jenis'];
                        $kriterianama = $firstKriteria['nama'];

                        // Ambil semua nilai untuk kriteria ini
                        $allValues = collect($decisionMatrix)->pluck($kriteriaKode)->filter()->toArray();

                        if ($kriteriaJenis === 'benefit') {
                        $maxValue = max($allValues);
                        $firstAlternative = collect($decisionMatrix)->keys()->first();
                        $firstValue = $decisionMatrix[$firstAlternative][$kriteriaKode] ?? 0;
                        $normalizedValue = $maxValue > 0 ? $firstValue / $maxValue : 0;
                        } else {
                        $minValue = min($allValues);
                        $firstAlternative = collect($decisionMatrix)->keys()->first();
                        $firstValue = $decisionMatrix[$firstAlternative][$kriteriaKode] ?? 0;
                        $normalizedValue = $firstValue > 0 && $minValue > 0 ? $minValue / $firstValue : 0;
                        }
                        @endphp

                        <div class="text-xs text-blue-700 dark:text-blue-300 space-y-2">
                            <p><strong>Untuk Kriteria {{ $kriteriaKode }} ({{ $kriterianama }}) -
                                    {{ ucfirst($kriteriaJenis) }}:</strong></p>
                            <p>• Nilai-nilai: {{ implode(', ', $allValues) }}</p>
                            @if($kriteriaJenis === 'benefit')
                            <p>• Max value = {{ $maxValue }}</p>
                            <p>• Normalisasi {{ $firstAlternative }}: R = {{ $firstValue }} ÷ {{ $maxValue }} =
                                <strong>{{ round($normalizedValue, 4) }}</strong>
                            </p>
                            @else
                            <p>• Min value = {{ $minValue }}</p>
                            <p>• Normalisasi {{ $firstAlternative }}: R = {{ $minValue }} ÷ {{ $firstValue }} =
                                <strong>{{ round($normalizedValue, 4) }}</strong>
                            </p>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b-2 border-gray-200 dark:border-gray-600">
                                    <th
                                        class="text-left py-3 px-3 font-semibold text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700">
                                        Alternatif</th>
                                    @foreach($kriteria as $index => $k)
                                    <th
                                        class="text-center py-3 px-3 font-semibold text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700">
                                        R{{ $index + 1 }} ({{ $k['kode'] }})
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($normalizedMatrix as $kode => $values)
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <td class="py-3 px-3 font-medium text-gray-900 dark:text-gray-100">{{ $kode }}</td>
                                    @foreach($kriteria as $k)
                                    <td class="py-3 px-3 text-center text-gray-700 dark:text-gray-300">
                                        {{ round($values[$k['kode']] ?? 0, 4) }}
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Detailed Calculation Examples -->
                    @if(!empty($decisionMatrix) && !empty($kriteria))
                    <div class="mt-6 space-y-4">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">📊 Contoh Perhitungan
                            Detil untuk Setiap Kriteria:</h4>

                        @foreach($kriteria as $k)
                        @php
                        $kriteriaKode = $k['kode'];
                        $kriteriaJenis = $k['jenis'];
                        $kriterianama = $k['nama'];

                        // Ambil semua nilai untuk kriteria ini
                        $allValues = collect($decisionMatrix)->pluck($kriteriaKode)->filter()->toArray();
                        $maxValue = max($allValues);
                        $minValue = min($allValues);
                        @endphp

                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <h5 class="font-medium text-gray-900 dark:text-gray-100 mb-2">
                                {{ $kriteriaKode }} - {{ $kriterianama }} ({{ ucfirst($kriteriaJenis) }})
                            </h5>

                            <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                <p>• Nilai asli: {{ implode(', ', $allValues) }}</p>
                                @if($kriteriaJenis === 'benefit')
                                <p>• Max value = {{ $maxValue }}</p>
                                <p>• Formula: Rij = Xij ÷ {{ $maxValue }}</p>
                                @else
                                <p>• Min value = {{ $minValue }}</p>
                                <p>• Formula: Rij = {{ $minValue }} ÷ Xij</p>
                                @endif

                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 mt-2">
                                    @foreach($decisionMatrix as $altKode => $altValues)
                                    @php
                                    $originalValue = $altValues[$kriteriaKode] ?? 0;
                                    if ($kriteriaJenis === 'benefit') {
                                    $normalizedValue = $maxValue > 0 ? $originalValue / $maxValue : 0;
                                    } else {
                                    $normalizedValue = $originalValue > 0 && $minValue > 0 ? $minValue / $originalValue
                                    : 0;
                                    }
                                    @endphp
                                    <div class="text-xs bg-white dark:bg-gray-600 p-2 rounded">
                                        <div class="font-medium">{{ $altKode }}:</div>
                                        @if($kriteriaJenis === 'benefit')
                                        <div>{{ $originalValue }} ÷ {{ $maxValue }} = {{ round($normalizedValue, 4) }}
                                        </div>
                                        @else
                                        <div>{{ $minValue }} ÷ {{ $originalValue }} = {{ round($normalizedValue, 4) }}
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif
            </div>

            <!-- Step 4: Final Scoring -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <span
                            class="w-8 h-8 bg-purple-600 text-black rounded-full flex items-center justify-center text-lg font-bold mr-3">4.</span>
                        Perhitungan Skor Akhir
                    </h3>
                    <button type="button" wire:click="toggleStep('scoring')"
                        class="text-sm text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200">
                        {{ $showScoring ? 'Sembunyikan' : 'Tampilkan' }}
                    </button>
                </div>
                @if($showScoring)
                <div class="p-6">
                    <!-- Formula explanation -->
                    <div
                        class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-purple-800 dark:text-purple-200 mb-2">Formula Skor Akhir:
                        </h4>
                        <div class="text-sm text-purple-700 dark:text-purple-300">
                            <strong>Vi = Σ(Wj × Rij)</strong> = W1×R1 + W2×R2 + W3×R3 + W4×R4 + W5×R5
                        </div>
                    </div>

                    <!-- Manual Calculation Example for First Alternative -->
                    @if(!empty($finalScores) && !empty($bobotAhp) && !empty($normalizedMatrix))
                    @php
                    $firstAlternative = collect($finalScores)->keys()->first();
                    $firstScore = $finalScores[$firstAlternative];
                    $firstNormalized = $normalizedMatrix[$firstAlternative] ?? [];
                    @endphp
                    <div
                        class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4 mb-6">
                        <h4 class="text-sm font-semibold text-green-800 dark:text-green-200 mb-3">📘 Contoh Perhitungan
                            Manual untuk {{ $firstAlternative }}:</h4>

                        <div class="text-xs text-green-700 dark:text-green-300 space-y-2">
                            <p><strong>Langkah perhitungan:</strong></p>
                            @foreach($bobotAhp as $kriteriaKode => $bobot)
                            @if(isset($firstNormalized[$kriteriaKode]))
                            @php
                            $rValue = $firstNormalized[$kriteriaKode];
                            $contribution = $bobot * $rValue;
                            @endphp
                            <p>• {{ $kriteriaKode }}: {{ round($bobot, 4) }} × {{ round($rValue, 4) }} =
                                {{ round($contribution, 4) }}
                            </p>
                            @endif
                            @endforeach

                            <div class="border-t border-green-300 pt-2 mt-3">
                                <p><strong>Total Skor {{ $firstAlternative }}:</strong></p>
                                <p class="text-sm">
                                    @foreach($bobotAhp as $kriteriaKode => $bobot)
                                    @if(isset($firstNormalized[$kriteriaKode]))
                                    {{ round($bobot, 4) }} ×
                                    {{ round($firstNormalized[$kriteriaKode], 4) }}{{ !$loop->last ? ' + ' : '' }}
                                    @endif
                                    @endforeach
                                    = <strong>{{ round($firstScore, 4) }}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="space-y-4">
                        @foreach($finalScores as $kode => $score)
                        @php
                        $normalizedValues = $normalizedMatrix[$kode] ?? [];
                        @endphp
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $kode }}</span>
                                <span
                                    class="text-lg font-bold text-purple-600 dark:text-purple-400">{{ round($score, 4) }}</span>
                            </div>
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                @foreach($bobotAhp as $kriteriaKode => $bobot)
                                @if(isset($normalizedValues[$kriteriaKode]))
                                {{ round($bobot, 4) }} ×
                                {{ round($normalizedValues[$kriteriaKode], 4) }}{{ !$loop->last ? ' + ' : '' }}
                                @endif
                                @endforeach
                            </div>

                            <!-- Detailed step breakdown -->
                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                                <div class="grid grid-cols-2 md:grid-cols-5 gap-2">
                                    @foreach($bobotAhp as $kriteriaKode => $bobot)
                                    @if(isset($normalizedValues[$kriteriaKode]))
                                    @php
                                    $contribution = $bobot * $normalizedValues[$kriteriaKode];
                                    @endphp
                                    <div class="bg-white dark:bg-gray-600 p-1 rounded text-center">
                                        <div class="font-medium">{{ $kriteriaKode }}</div>
                                        <div>{{ round($contribution, 4) }}</div>
                                    </div>
                                    @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Summary Calculation Table -->
                    <div class="mt-6">
                        <h4 class="text-sm font-semibold text-gray-800 dark:text-gray-200 mb-3">📊 Tabel Ringkasan
                            Perhitungan Skor:</h4>
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm border border-gray-200 dark:border-gray-600">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-2 px-3 font-semibold text-gray-900 dark:text-gray-100">
                                            Alternatif</th>
                                        @foreach($bobotAhp as $kriteriaKode => $bobot)
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-2 px-3 font-semibold text-gray-900 dark:text-gray-100 text-center">
                                            W{{ $loop->iteration }}×R{{ $loop->iteration }}<br>
                                            <span class="text-xs text-gray-500">({{ $kriteriaKode }})</span>
                                        </th>
                                        @endforeach
                                        <th
                                            class="border border-gray-200 dark:border-gray-600 py-2 px-3 font-semibold text-gray-900 dark:text-gray-100 bg-purple-50 dark:bg-purple-900/30">
                                            Skor Akhir</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($finalScores as $kode => $score)
                                    @php
                                    $normalizedValues = $normalizedMatrix[$kode] ?? [];
                                    @endphp
                                    <tr>
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-2 px-3 font-medium text-gray-900 dark:text-gray-100">
                                            {{ $kode }}
                                        </td>
                                        @foreach($bobotAhp as $kriteriaKode => $bobot)
                                        @if(isset($normalizedValues[$kriteriaKode]))
                                        @php
                                        $contribution = $bobot * $normalizedValues[$kriteriaKode];
                                        @endphp
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-2 px-3 text-center text-gray-700 dark:text-gray-300">
                                            {{ round($contribution, 4) }}
                                        </td>
                                        @else
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-2 px-3 text-center text-gray-700 dark:text-gray-300">
                                            0</td>
                                        @endif
                                        @endforeach
                                        <td
                                            class="border border-gray-200 dark:border-gray-600 py-2 px-3 text-center font-bold text-purple-600 dark:text-purple-400 bg-purple-50 dark:bg-purple-900/30">
                                            {{ round($score, 4) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Step 5: Ranking Preview -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                        <span
                            class="w-8 h-8 bg-green-600 text-black rounded-full flex items-center justify-center text-lg font-bold mr-3">5.</span>
                        Ranking Sementara
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach(collect($finalScores)->take(10) as $kode => $score)
                        <div
                            class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-3">
                                    <span
                                        class="w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                        {{ $loop->iteration }}
                                    </span>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ $this->getNamaLengkap($kode) }}
                                        </div>
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">{{ $kode }}</span> •
                                            <span
                                                class="text-blue-600 dark:text-blue-400">{{ $this->getProgramStudi($kode) }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-lg font-bold text-green-600 dark:text-green-400">
                                        {{ round($score, 4) }}
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Skor SAW
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Lihat ranking lengkap di menu <strong>"Hasil Seleksi"</strong>
                        </p>
                    </div>
                </div>
            </div>

        </div>
        @endif

        @endif
    </div>
</x-filament-panels::page>