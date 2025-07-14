<!-- Page Header -->
<div class="mb-6 px-6 pt-4">
  <h1 class="text-2xl font-bold text-gray-800 mb-1">{{ $title ?? 'Judul Halaman' }}</h1>

  @if (!empty($breadcrumb))
   <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
  <ol class="flex items-center space-x-2">
    @foreach ($breadcrumb as $item)
      <li class="flex items-center space-x-2">
        @if ($loop->last)
          <span class="text-gray-500">{{ $item['label'] }}</span> {{-- Halaman aktif, bukan link --}}
        @else
          <a href="{{ $item['url'] ?? '#' }}" class="text-blue-600 hover:underline">
            {{ $item['label'] }}
          </a>
          <span>/</span>
        @endif
      </li>
    @endforeach
  </ol>
</nav>

  @endif
</div>
