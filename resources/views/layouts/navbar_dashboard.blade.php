<!-- Page Header -->
<div class="mb-8 px-6 pt-6">
  <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $title ?? 'Judul Halaman' }}</h1>

  @if (!empty($breadcrumb))
    <nav class="text-sm text-gray-500" aria-label="Breadcrumb">
      <ol class="flex flex-wrap items-center space-x-1">
        @foreach ($breadcrumb as $item)
          <li class="flex items-center space-x-1">
            @if (!$loop->last)
              <a href="{{ $item['url'] ?? '#' }}" class="text-blue-600 hover:underline">
                {{ $item['label'] }}
              </a>
              <span class="text-gray-400">/</span>
            @else
              <span class="text-gray-500">{{ $item['label'] }}</span>
            @endif
          </li>
        @endforeach
      </ol>
    </nav>
  @endif
</div>
