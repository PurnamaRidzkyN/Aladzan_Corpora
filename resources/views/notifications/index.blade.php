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
                                    <a href="{{ $notif->data['link'] }}" class="btn btn-xs btn-primary">Lihat</a>
                                @endif
                                @if (is_null($notif->read_at))
                                    @php
                                        $role = auth('admin')->check() ? 'admin' : 'reseller';
                                    @endphp

                                    <button type="button" class="btn btn-xs btn-success"
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
                <div class="mt-4">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
