<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg">
            <div class="px-6 py-8 text-white">
                <h1 class="text-2xl font-bold mb-2">🎓 Dashboard Monitoring Beasiswa KIP Kuliah</h1>
                <p class="text-blue-100">Pantau hasil seleksi calon mahasiswa penerima beasiswa KIP Kuliah untuk lulusan SMA/SMK sederajat secara real-time</p>
                <div class="mt-4 flex items-center text-blue-100 text-sm">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Last Update: {{ now()->format('d M Y H:i') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Stats with Progress -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Total Lulusan SMA</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\CalonMahasiswa::count() }}</p>
                    <p class="text-sm text-gray-500">Pendaftar beasiswa KIP</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Sudah Dievaluasi</h2>
                    <p class="text-3xl font-bold text-gray-900">{{ \App\Models\HasilSeleksi::count() }}</p>
                    <p class="text-sm text-gray-500">
                        {{ \App\Models\CalonMahasiswa::count() > 0 ? round((\App\Models\HasilSeleksi::count() / \App\Models\CalonMahasiswa::count()) * 100, 1) : 0 }}%
                        selesai
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Skor Tertinggi</h2>
                    <p class="text-3xl font-bold text-gray-900">
                        @if(\App\Models\HasilSeleksi::max('skor'))
                        {{ number_format(\App\Models\HasilSeleksi::max('skor'), 4) }}
                        @else
                        0.0000
                        @endif
                    </p>
                    <p class="text-sm text-gray-500">Peringkat #1</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-sm font-medium text-gray-600">Rata-rata Skor</h2>
                    <p class="text-3xl font-bold text-gray-900">
                        @if(\App\Models\HasilSeleksi::avg('skor'))
                        {{ number_format(\App\Models\HasilSeleksi::avg('skor'), 4) }}
                        @else
                        0.0000
                        @endif
                    </p>
                    <p class="text-sm text-gray-500">Seluruh kandidat</p>
                </div>
            </div>
        </div>
    </div>

    @if(\App\Models\HasilSeleksi::count() > 0)
    <!-- Search & Filter Section -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900">Ranking Kandidat</h3>
                <p class="text-sm text-gray-600">Daftar lengkap hasil seleksi beasiswa KIP Kuliah</p>
            </div>
            <div class="flex space-x-3 mt-4 sm:mt-0">
                <button onclick="searchCandidate()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Cari Kandidat
                </button>
                <button onclick="exportToPDF()"
                    class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    Download PDF
                </button>
            </div>
        </div>

        <!-- Search Input (Initially Hidden) -->
        <div id="searchForm" class="mb-4 hidden">
            <div class="max-w-md">
                <label for="search" class="sr-only">Cari nama kandidat</label>
                <div class="relative">
                    <input type="text" id="search" name="search"
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Masukkan nama kandidat..." onkeyup="filterTable()">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Table -->
        <div class="overflow-x-auto">
            <table id="candidateTable" class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ranking</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Nama Kandidat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Skor SAW</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Tanggal Seleksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach(\App\Models\HasilSeleksi::join('calon_mahasiswa', 'hasil_seleksi.calon_mahasiswa_id',
                    '=', 'calon_mahasiswa.id')->orderBy('hasil_seleksi.rank', 'asc')->get(['hasil_seleksi.*',
                    'calon_mahasiswa.nama']) as $hasil)
                    <tr class="candidate-row hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $hasil->rank <= 3 ? 'bg-yellow-100 text-yellow-800' : ($hasil->rank <= 10 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                #{{ $hasil->rank }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div
                                        class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span
                                            class="text-sm font-medium text-gray-700">{{ substr($hasil->nama, 0, 2) }}</span>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 candidate-name">{{ $hasil->nama }}
                                    </div>
                                    <div class="text-sm text-gray-500">ID: {{ $hasil->calon_mahasiswa_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900 font-mono">{{ number_format($hasil->skor, 4) }}</div>
                            <div class="w-full bg-gray-200 rounded-full h-2 mt-1">
                                @php
                                $maxSkor = \App\Models\HasilSeleksi::max('skor') ?: 1;
                                $progressWidth = round(($hasil->skor / $maxSkor) * 100, 2);
                                @endphp
                                <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                    x-data="{ width: {{ $progressWidth }} }" :style="`width: ${width}%`"></div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                            $totalKandidatDiterima = ceil(\App\Models\HasilSeleksi::count() * 0.4); // 40% diterima
                            @endphp
                            <span
                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $hasil->rank <= $totalKandidatDiterima ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $hasil->rank <= $totalKandidatDiterima ? 'LOLOS' : 'TIDAK LOLOS' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($hasil->tanggal_seleksi)->format('d M Y') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- No Results Message -->
            <div id="noResults" class="hidden text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6-4h6m2 5.291A7.962 7.962 0 0112 15c-2.34 0-4.47-.881-6.084-2.329C7.76 14.048 9.81 15 12 15s4.24-.952 6.084-2.329z">
                    </path>
                </svg>
                <p class="mt-2 text-sm text-gray-600">Tidak ada kandidat yang ditemukan</p>
            </div>
        </div>
    </div>
    @else
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
            </path>
        </svg>
        <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Hasil Seleksi</h3>
        <p class="mt-2 text-sm text-gray-600">Proses evaluasi kandidat beasiswa belum dilaksanakan.</p>
    </div>
    @endif

    <!-- Information Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-blue-900">Metode AHP</h4>
                </div>
                <p class="text-blue-700 text-sm leading-relaxed">
                    Analytical Hierarchy Process digunakan untuk menentukan bobot kepentingan setiap kriteria dalam
                    proses seleksi beasiswa.
                </p>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-green-900">Metode SAW</h4>
                </div>
                <p class="text-green-700 text-sm leading-relaxed">
                    Simple Additive Weighting digunakan untuk menghitung skor akhir dan menentukan ranking calon
                    penerima beasiswa.
                </p>
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript for Interactive Features -->
    <script>
        function searchCandidate() {
            const searchForm = document.getElementById('searchForm');
            if (searchForm.classList.contains('hidden')) {
                searchForm.classList.remove('hidden');
                document.getElementById('search').focus();
            } else {
                searchForm.classList.add('hidden');
                document.getElementById('search').value = '';
                filterTable();
            }
        }

        function filterTable() {
            const searchInput = document.getElementById('search').value.toLowerCase();
            const table = document.getElementById('candidateTable');
            const rows = table.getElementsByClassName('candidate-row');
            const noResults = document.getElementById('noResults');
            let visibleRows = 0;

            for (let i = 0; i < rows.length; i++) {
                const nameCell = rows[i].getElementsByClassName('candidate-name')[0];
                if (nameCell) {
                    const name = nameCell.textContent.toLowerCase();
                    if (name.includes(searchInput)) {
                        rows[i].style.display = '';
                        visibleRows++;
                    } else {
                        rows[i].style.display = 'none';
                    }
                }
            }

            if (visibleRows === 0 && searchInput !== '') {
                noResults.classList.remove('hidden');
            } else {
                noResults.classList.add('hidden');
            }
        }

        function exportToPDF() {
            // Placeholder for PDF export functionality
            alert('Fitur export PDF akan segera tersedia!');
            // TODO: Implement PDF export with libraries like jsPDF or server-side export
        }

        // Auto-refresh data every 5 minutes
        setInterval(function() {
            location.reload();
        }, 300000);
    </script>
</x-filament-panels::page>
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Menu Utama</h3>
        <p class="text-sm text-gray-600">Akses fitur utama sistem</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 gap-4">
            <a href="/user/hasil-seleksi"
                class="flex items-center p-6 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200 border border-blue-200">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                        </path>
                    </svg>
                </div>
                <div class="ml-6">
                    <h4 class="text-xl font-semibold text-gray-900">Hasil Seleksi Beasiswa</h4>
                    <p class="text-gray-600 mt-1">Lihat hasil perhitungan dan ranking calon penerima beasiswa KIP Kuliah
                    </p>
                    <div class="flex items-center mt-3 text-blue-600">
                        <span class="text-sm font-medium">Lihat Detail</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>

<!-- Information Section -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Informasi Sistem</h3>
        <p class="text-sm text-gray-600">Detail tentang metode yang digunakan</p>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-blue-50 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 3a3 3 0 000 6h10a3 3 0 100-6M4 9h16M7 13h10M7 17h4"></path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-blue-900">Metode AHP</h4>
                </div>
                <p class="text-blue-700 text-sm leading-relaxed">
                    Analytical Hierarchy Process untuk menentukan bobot kriteria: Penghasilan Orang Tua, Lokasi Tempat Tinggal, Hasil Tes Prestasi, Wawancara, dan Nilai Rapor SMA.
                </p>
            </div>

            <div class="bg-green-50 rounded-lg p-4">
                <div class="flex items-center mb-3">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="text-lg font-semibold text-green-900">Metode SAW</h4>
                </div>
                <p class="text-green-700 text-sm leading-relaxed">
                    Simple Additive Weighting untuk menghitung skor final lulusan SMA berdasarkan kriteria beasiswa KIP Kuliah dan menentukan ranking penerima.
                </p>
            </div>
        </div>
    </div>
</div>

@if(\App\Models\HasilSeleksi::count() > 0)
<!-- Latest Results Preview -->
<div class="bg-white rounded-lg shadow">
    <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Hasil Terbaru</h3>
        <p class="text-sm text-gray-600">5 kandidat dengan ranking tertinggi</p>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ranking
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor
                        Akhir</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach(\App\Models\HasilSeleksi::join('calon_mahasiswa', 'hasil_seleksi.calon_mahasiswa_id', '=',
                'calon_mahasiswa.id')->orderBy('skor', 'desc')->limit(5)->get(['hasil_seleksi.*',
                'calon_mahasiswa.nama']) as $index => $hasil)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                    {{ $index == 0 ? 'bg-yellow-100 text-yellow-800' : ($index < 3 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                            #{{ $index + 1 }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $hasil->nama }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($hasil->skor, 4) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
</div>
</x-filament-panels::page>