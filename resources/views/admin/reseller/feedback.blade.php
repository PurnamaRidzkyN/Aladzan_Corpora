@extends('layouts.dashboard')
@section('title', 'Feedback Web')
@php
    $title = 'Feedback Web';
    $breadcrumb = [ ['label' => 'Feedback Web'] ];
@endphp
@section('content')
<div class="card bg-white shadow-md rounded-xl border border-soft">
    <div class="card-body">

        <!-- Header -->
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-2xl font-bold text-primary">Feedback & Rating Web</h2>
        </div>

        <!-- Filter Rating -->
        <form method="GET" action="{{ route('reseller.feedback') }}" class="w-full mb-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-2">
                <select name="rating" class="select select-bordered w-full" onchange="this.form.submit()">
                    <option value="">Semua Rating</option>
                    @for ($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                            {{ $i }} Bintang
                        </option>
                    @endfor
                </select>
            </div>
        </form>

        <!-- Table Feedback -->
        <div class="overflow-x-auto">
            <table class="table w-full text-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reseller </th>
                        <th>Rating</th>
                        <th>Komentar</th>
                        <th>Dikirim</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($feedbacks as $index => $fb)
                        <tr class="hover:bg-accent-light transition">
                            <td>{{ $feedbacks->firstItem() + $index }}</td>
                            <td>{{ $fb->reseller_id }}</td>
                            <td>
                                @for ($s = 1; $s <= 5; $s++)
                                    <span class="{{ $s <= $fb->rating ? 'text-yellow-400' : 'text-gray-300' }}">★</span>
                                @endfor
                            </td>
                            <td>{{ $fb->comment ?? '-' }}</td>
                            <td>{{ $fb->created_at->format('d M Y H:i') }}</td>
                         
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Belum ada feedback.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $feedbacks->links() }}
        </div>
    </div>
</div>
@endsection
