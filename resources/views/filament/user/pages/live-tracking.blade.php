<x-filament-panels::page>
    <div class="space-y-6"
        x-data="{ 
             isLive: true,
             lastUpdate: new Date().toLocaleTimeString(),
             
             startLiveUpdates() {
                 setInterval(() => {
                     if (this.isLive) {
                         this.lastUpdate = new Date().toLocaleTimeString();
                         $wire.refreshLiveData();
                     }
                 }, 10000); // Update setiap 10 detik
             }
         }"
        x-init="startLiveUpdates()"
        x-on:live-data-updated.window="$dispatch('notify', { message: 'Data tracking diperbarui!', type: 'success' })">

        <!-- Live Status Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 rounded-lg shadow-lg">
            <div class="px-6 py-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold mb-2">🔴 Live Tracking Seleksi</h1>
                        <p class="text-red-100">Pantau proses seleksi beasiswa secara real-time</p>
                    </div>
                    <div class="text-right">
                        <div class="flex items-center mb-2">
                            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse mr-2"></div>
                            <span class="text-sm" x-text="isLive ? 'LIVE' : 'OFFLINE'"></span>
                        </div>
                        <p class="text-sm text-red-100" x-text="'Last Update: ' + lastUpdate"></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Top Candidates Live -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">🏆 Top 5 Kandidat</h3>
                            <span class="text-xs text-gray-500">Real-time ranking</span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if(count($topCandidates) > 0)
                        <div class="space-y-4">
                            @foreach($topCandidates as $index => $candidate)
                            <div class="flex items-center p-4 bg-gray-50 rounded-lg transition-all duration-300 hover:bg-gray-100">
                                <div class="flex-shrink-0">
                                    @if($index == 0)
                                    <div class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <span class="text-xl">🥇</span>
                                    </div>
                                    @elseif($index == 1)
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <span class="text-xl">🥈</span>
                                    </div>
                                    @elseif($index == 2)
                                    <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                                        <span class="text-xl">🥉</span>
                                    </div>
                                    @else
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <span class="text-lg font-bold text-blue-600">#{{ $index + 1 }}</span>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-900">{{ $candidate['nama'] }}</p>
                                    <p class="text-xs text-gray-500">ID: {{ $candidate['calon_mahasiswa_id'] }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-lg font-bold text-gray-900">{{ number_format($candidate['skor'], 4) }}</p>
                                    <p class="text-xs text-gray-500">Skor SAW</p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada data ranking</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Activity Feed -->
            <div class="space-y-6">
                <!-- Recent Updates -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">📝 Update Terkini</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($recentUpdates as $update)
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    @if($update['type'] == 'success')
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                    @elseif($update['type'] == 'info')
                                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    @else
                                    <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                                <div class="ml-3 flex-1">
                                    <p class="text-sm text-gray-900">{{ $update['message'] }}</p>
                                    <div class="flex items-center justify-between mt-1">
                                        <p class="text-xs text-gray-500">{{ $update['time'] }}</p>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $update['count'] }} item
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- System Activity -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">⚡ Aktivitas Sistem</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Total Sessions</span>
                                <span class="text-sm font-medium text-gray-900">{{ $systemActivity['total_sessions'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Active Evaluators</span>
                                <span class="text-sm font-medium text-green-600">{{ $systemActivity['active_evaluators'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">Pending Evaluations</span>
                                <span class="text-sm font-medium text-orange-600">{{ $systemActivity['pending_evaluations'] }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-600">System Load</span>
                                <div class="flex items-center">
                                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                            x-data="{ load: {{ $systemActivity['system_load'] }} }"
                                            :style="`width: ${load}%`"></div>
                                    </div>
                                    <span class="text-sm font-medium text-gray-900">{{ $systemActivity['system_load'] }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Live Controls -->
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-medium text-gray-900">Live Tracking Controls</h3>
                    <p class="text-sm text-gray-600">Kelola pengaturan monitoring real-time</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <input type="checkbox"
                            id="liveToggle"
                            x-model="isLive"
                            class="h-4 w-4 text-red-600 focus:ring-red-500 border-gray-300 rounded">
                        <label for="liveToggle" class="ml-2 text-sm text-gray-700">Live Mode</label>
                    </div>
                    <button x-on:click="$wire.refreshLiveData()"
                        class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Refresh Data
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>