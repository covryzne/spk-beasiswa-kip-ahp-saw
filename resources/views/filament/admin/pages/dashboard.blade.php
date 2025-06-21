<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Stats Overview -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Total Kriteria</h2>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\Kriteria::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Calon Mahasiswa</h2>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\CalonMahasiswa::count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-yellow-100 rounded-lg">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Hasil Seleksi</h2>
                        <p class="text-2xl font-semibold text-gray-900">{{ \App\Models\HasilSeleksi::count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Aksi Cepat</h3>
                <p class="text-sm text-gray-600">Lakukan tindakan yang sering digunakan</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="/admin/kriteria"
                        class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors duration-200">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Kelola Kriteria</p>
                            <p class="text-xs text-gray-500">Setup kriteria seleksi</p>
                        </div>
                    </a>

                    <a href="/admin/matriks-ahp"
                        class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors duration-200">
                        <div class="p-2 bg-purple-100 rounded-lg">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 3a3 3 0 000 6h10a3 3 0 100-6M4 9h16M7 13h10M7 17h4"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Matriks AHP</p>
                            <p class="text-xs text-gray-500">Input perbandingan kriteria</p>
                        </div>
                    </a>

                    <a href="/admin/calon-mahasiswa"
                        class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors duration-200">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Data Mahasiswa</p>
                            <p class="text-xs text-gray-500">Kelola data calon</p>
                        </div>
                    </a>

                    <a href="/admin/perhitungan-saw"
                        class="flex items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition-colors duration-200">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                </path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">Proses Seleksi</p>
                            <p class="text-xs text-gray-500">Hitung ranking kandidat</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Status System -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Status Sistem</h3>
                <p class="text-sm text-gray-600">Ringkasan kondisi sistem SPK</p>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-blue-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Setup Kriteria</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\Kriteria::count() }} kriteria terdaftar
                                </p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ \App\Models\Kriteria::count() > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ \App\Models\Kriteria::count() > 0 ? 'Siap' : 'Belum Setup' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-purple-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 3a3 3 0 000 6h10a3 3 0 100-6M4 9h16M7 13h10M7 17h4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Perhitungan AHP</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\PerhitunganAhp::count() }} perhitungan
                                    tersimpan</p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ \App\Models\PerhitunganAhp::count() > 0 ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ \App\Models\PerhitunganAhp::count() > 0 ? 'Tersedia' : 'Perlu Dihitung' }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex items-center">
                            <div class="p-2 bg-green-100 rounded-lg mr-3">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Data Calon Mahasiswa</p>
                                <p class="text-xs text-gray-500">{{ \App\Models\CalonMahasiswa::count() }} calon
                                    mahasiswa</p>
                            </div>
                        </div>
                        <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ \App\Models\CalonMahasiswa::count() > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ \App\Models\CalonMahasiswa::count() > 0 ? 'Ada Data' : 'Belum Ada' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>