<div class="bg-white rounded-lg shadow-lg border border-gray-200">
    <div class="px-4 py-3 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
        <div class="flex items-center">
            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 17h5l-5 5-5-5h5v-8H9l5-5 5 5h-5v8z"></path>
            </svg>
            <h3 class="text-lg font-medium text-gray-900">Update Terbaru</h3>
            @if($hasUpdates)
            <span
                class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                {{ $recentUpdates->count() }} update baru
            </span>
            @endif
        </div>
    </div>

    <div class="p-4">
        @if($hasUpdates)
        <div class="space-y-3">
            @foreach($recentUpdates as $update)
            <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">
                        {{ $update->calonMahasiswa->nama ?? 'Kandidat' }}
                    </p>
                    <p class="text-sm text-gray-500">
                        Skor: {{ number_format($update->skor, 4) }} | Rank: #{{ $update->rank }}
                    </p>
                    <p class="text-xs text-gray-400">
                        {{ $update->updated_at->diffForHumans() }}
                    </p>
                </div>
            </div>
            @endforeach
        </div>

        @if($latestUpdate)
        <div class="mt-4 pt-3 border-t border-gray-200">
            <p class="text-xs text-gray-500">
                Update terakhir: {{ $latestUpdate->updated_at->format('d M Y, H:i') }}
            </p>
        </div>
        @endif
        @else
        <div class="text-center py-6">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5a2 2 0 012-2h12a2 2 0 012 2z">
                </path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada update</h3>
            <p class="mt-1 text-sm text-gray-500">
                Tidak ada update ranking dalam 7 hari terakhir.
            </p>
        </div>
        @endif
    </div>
</div>