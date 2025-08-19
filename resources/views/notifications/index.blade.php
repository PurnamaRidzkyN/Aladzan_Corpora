@extends(auth('admin')->check() ? 'layouts.dashboard' : 'layouts.app')

@section('title', 'Notifikasi')


@section('content')
    <div class="card bg-white shadow-md rounded-xl border border-soft">
        <div class="card-body">
            <h2 class="text-2xl font-bold text-primary mb-4">🔔 Notifikasi</h2>

            @if ($notifications->isEmpty())
                <p class="text-gray-500 text-center py-6">Tidak ada notifikasi baru.</p>
            @else
                <ul class="divide-y divide-gray-200">
                    @foreach ($notifications as $notif)
                        <li
                            class="py-4 flex justify-between items-center {{ is_null($notif->read_at) ? 'bg-yellow-50' : '' }} px-3 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                                <p class="text-gray-600 text-sm">{{ $notif->data['message'] ?? '' }}</p>
                                <small class="text-gray-400 text-xs">{{ $notif->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="flex gap-2">
                                @if (!empty($notif->data['link']))
                                    @php
                                        $role = auth('admin')->check() ? 'admin' : 'reseller';
                                    @endphp
                                    <a href="{{ $notif->data['link'] }}" class="btn btn-xs btn-gradient-primary"
                                        @click.prevent="
                fetch('{{ route($role . '.notifications.read', $notif->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => {
                    window.location.href = '{{ $notif->data['link'] }}';
                });
           ">
                                        Lihat
                                    </a>
                                @endif

                                @if (is_null($notif->read_at))
                                    <button type="button" class="btn btn-xs btn-gradient-success"
                                        @click="
                fetch('{{ route($role . '.notifications.read', $notif->id) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).then(() => window.location.reload());
            ">
                                        Tandai Dibaca
                                    </button>
                                @endif
                            </div>

                        </li>
                    @endforeach
                </ul>

                <!-- Pagination -->
                <div class="flex justify-center mt-6 space-x-2">
    {{-- Tombol Previous --}}
    @if ($notifications->onFirstPage())
        <span class="btn-gradient-neutral">Prev</span>
    @else
        <a href="{{ $notifications->previousPageUrl() }}"
           class="btn btn-gradient-primary">
            Prev
        </a>
    @endif

    {{-- Nomor Halaman --}}
    @foreach ($notifications->getUrlRange(1, $notifications->lastPage()) as $page => $url)
        @if ($page == $notifications->currentPage())
            <span class="btn-gradient-neutral">{{ $page }}</span>
        @else
            <a href="{{ $url }}" class="btn-gradient-primary">{{ $page }}</a>
        @endif
    @endforeach

    {{-- Tombol Next --}}
    @if ($notifications->hasMorePages())
        <a href="{{ $notifications->nextPageUrl() }}"
           class="btn-gradient-primary">
            Next
        </a>
    @else
        <span class="btn-gradient-neutral">Next</span>
    @endif
</div>

            @endif
        </div>
    </div>
@endsection
