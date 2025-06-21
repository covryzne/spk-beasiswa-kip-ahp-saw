<div>
    <x-filament::section>
        <x-slot name="heading">
            Status Perhitungan AHP
        </x-slot>

        <div class="space-y-4">
            @if($isCalculated && $ahpResults)
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="text-sm font-medium text-green-800">Status</div>
                    <div class="text-lg font-bold text-green-900">
                        @if($ahpResults['is_consistent'])
                        ✅ Konsisten
                        @else
                        ⚠️ Tidak Konsisten
                        @endif
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="text-sm font-medium text-blue-800">Lambda Max</div>
                    <div class="text-lg font-bold text-blue-900">{{ round($ahpResults['lambda_max'], 6) }}</div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="text-sm font-medium text-yellow-800">CI (Consistency Index)</div>
                    <div class="text-lg font-bold text-yellow-900">{{ round($ahpResults['ci'], 6) }}</div>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <div class="text-sm font-medium text-purple-800">CR (Consistency Ratio)</div>
                    <div class="text-lg font-bold text-purple-900">
                        {{ round($ahpResults['cr'], 6) }}
                        @if($ahpResults['cr'] < 0.1)
                            <span class="text-green-600 text-sm">(< 0.1)</span>
                                @else
                                <span class="text-red-600 text-sm">(≥ 0.1)</span>
                                @endif
                    </div>
                </div>
            </div>
            @else
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="text-center">
                    <div class="text-gray-500">Perhitungan AHP belum dilakukan</div>
                    <div class="text-sm text-gray-400 mt-1">Klik tombol "Hitung AHP" untuk memulai perhitungan</div>
                </div>
            </div>
            @endif
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Bobot Kriteria
        </x-slot>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kriteria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bobot</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Persentase</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($kriteria as $k)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $k['kode'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $k['nama'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $k['jenis'] === 'Cost' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                {{ $k['jenis'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($k['bobot'])
                            {{ round($k['bobot'], 6) }}
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($k['bobot'])
                            {{ round($k['bobot'] * 100, 2) }}%
                            @else
                            <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section>
        <x-slot name="heading">
            Matriks Perbandingan Berpasangan
        </x-slot>

        <div class="overflow-x-auto">
            @if(count($kriteria) > 0)
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-3 py-2 text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                        @foreach($kriteria as $k)
                        <th class="border border-gray-300 px-3 py-2 text-xs font-medium text-gray-500 uppercase">{{ $k['kode'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteria as $i => $k1)
                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="border border-gray-300 px-3 py-2 font-medium text-gray-900">{{ $k1['kode'] }}</td>
                        @foreach($kriteria as $j => $k2)
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            @if($i === $j)
                            <span class="text-blue-600 font-bold">1</span>
                            @elseif(isset($matrix[$i][$j]) && $matrix[$i][$j] !== null)
                            <span class="text-green-600">{{ $matrix[$i][$j] }}</span>
                            @else
                            <span class="text-red-400">-</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <div class="text-center py-8">
                <div class="text-gray-500">Belum ada kriteria yang didefinisikan</div>
                <div class="text-sm text-gray-400 mt-1">Tambahkan kriteria terlebih dahulu</div>
            </div>
            @endif
        </div>

        @if(count($kriteria) > 0)
        <div class="mt-4 text-sm text-gray-600">
            <p><strong>Keterangan:</strong></p>
            <ul class="list-disc list-inside space-y-1">
                <li><span class="text-blue-600 font-bold">Biru</span>: Diagonal utama (nilai 1)</li>
                <li><span class="text-green-600">Hijau</span>: Sudah diinput</li>
                <li><span class="text-red-400">Merah</span>: Belum diinput</li>
            </ul>
            <p class="mt-2"><strong>Catatan:</strong> Pastikan semua perbandingan berpasangan sudah diinput sebelum melakukan perhitungan AHP.</p>
        </div>
        @endif
    </x-filament::section>

    @if($isCalculated && $ahpResults && isset($ahpResults['normalized_matrix']))
    <x-filament::section>
        <x-slot name="heading">
            Matriks Ternormalisasi
        </x-slot>

        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="border border-gray-300 px-3 py-2 text-xs font-medium text-gray-500 uppercase">Kriteria</th>
                        @foreach($kriteria as $k)
                        <th class="border border-gray-300 px-3 py-2 text-xs font-medium text-gray-500 uppercase">{{ $k['kode'] }}</th>
                        @endforeach
                        <th class="border border-gray-300 px-3 py-2 text-xs font-medium text-gray-500 uppercase">Eigen Vector</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteria as $i => $k1)
                    <tr class="{{ $i % 2 === 0 ? 'bg-white' : 'bg-gray-50' }}">
                        <td class="border border-gray-300 px-3 py-2 font-medium text-gray-900">{{ $k1['kode'] }}</td>
                        @foreach($kriteria as $j => $k2)
                        <td class="border border-gray-300 px-3 py-2 text-center">
                            {{ round($ahpResults['normalized_matrix'][$i][$j], 4) }}
                        </td>
                        @endforeach
                        <td class="border border-gray-300 px-3 py-2 text-center font-bold text-blue-600">
                            {{ round($ahpResults['weights'][$i], 6) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </x-filament::section>
    @endif
</div>