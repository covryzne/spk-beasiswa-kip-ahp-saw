<x-filament-panels::page>
    <div class="space-y-6" x-data="{ 
        stats: @js($stats),
        autoRefresh: true,
        refreshInterval: null,
        
        startAutoRefresh() {
            if (this.autoRefresh) {
                this.refreshInterval = setInterval(() => {
                    this.refreshData();
                }, 30000); // Refresh setiap 30 detik
            }
        },
        
        stopAutoRefresh() {
            if (this.refreshInterval) {
                clearInterval(this.refreshInterval);
            }
        },
        
        refreshData() {
            $wire.refreshData();
        }
    }"
        x-init="startAutoRefresh()"
        x-on:stats-updated.window="stats = $event.detail[0]; $dispatch('notify', { message: 'Data berhasil diperbarui!', type: 'success' })">

        <!-- Header Controls -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Monitoring Real-time</h1>
                    <p class="text-sm text-gray-600 mt-1">Pantau progress seleksi beasiswa secara real-time</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input type="checkbox"
                            id="autoRefresh"
                            x-model="autoRefresh"
                            x-on:change="autoRefresh ? startAutoRefresh() : stopAutoRefresh()"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="autoRefresh" class="ml-2 text-sm text-gray-700">Auto Refresh</label>
                    </div>
                    <button x-on:click="refreshData()"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Manual
                    </button>
                </div>
            </div>
            <div class="mt-4 text-xs text-gray-500">
                Last Update: <span x-text="stats.last_update"></span>
            </div>
        </div>

        <!-- Real-time Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Kandidat -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-lg">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Total Kandidat</h2>
                        <p class="text-3xl font-bold text-gray-900" x-text="stats.total_kandidat"></p>
                        <p class="text-sm text-gray-500">Calon penerima beasiswa</p>
                    </div>
                </div>
            </div>

            <!-- Progress Evaluasi -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-lg">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Progress Evaluasi</h2>
                        <p class="text-3xl font-bold text-gray-900">
                            <span x-text="stats.persentase_selesai"></span>%
                        </p>
                        <p class="text-sm text-gray-500">
                            <span x-text="stats.sudah_dievaluasi"></span> dari <span x-text="stats.total_kandidat"></span> kandidat
                        </p>
                        <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                            <div class="bg-green-600 h-2 rounded-full transition-all duration-300"
                                :style="`width: ${stats.persentase_selesai}%`"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Skor Tertinggi -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-lg">
                        <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Skor Tertinggi</h2>
                        <p class="text-3xl font-bold text-gray-900" x-text="Number(stats.skor_tertinggi).toFixed(4)"></p>
                        <p class="text-sm text-gray-500">Ranking #1</p>
                    </div>
                </div>
            </div>

            <!-- Rata-rata Skor -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-100 rounded-lg">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <h2 class="text-sm font-medium text-gray-600">Rata-rata Skor</h2>
                        <p class="text-3xl font-bold text-gray-900" x-text="Number(stats.rata_rata).toFixed(4)"></p>
                        <p class="text-sm text-gray-500">Seluruh kandidat</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Sistem -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Sistem Online</p>
                        <p class="text-xs text-gray-500">Berjalan normal</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Auto Refresh</p>
                        <p class="text-xs text-gray-500" x-text="autoRefresh ? 'Aktif (30 detik)' : 'Tidak aktif'"></p>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Database</p>
                        <p class="text-xs text-gray-500">Terhubung</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Range Skor -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Range Skor</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-red-900">Skor Terendah</p>
                    <p class="text-2xl font-bold text-red-600" x-text="Number(stats.skor_terendah).toFixed(4)"></p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-blue-900">Rata-rata</p>
                    <p class="text-2xl font-bold text-blue-600" x-text="Number(stats.rata_rata).toFixed(4)"></p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm font-medium text-green-900">Skor Tertinggi</p>
                    <p class="text-2xl font-bold text-green-600" x-text="Number(stats.skor_tertinggi).toFixed(4)"></p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>