@extends('layouts.app')
@section('title', 'Feedback')

@section('content')
<div class="max-w-3xl mx-auto my-8 p-6 bg-white rounded-xl shadow-md" x-data="{ rating: 0, hover: 0, comment: '' }">
    <h2 class="text-2xl font-semibold text-gray-800 text-center mb-4">Beri Nilai & Kritik untuk Web Kami</h2>

    <!-- Rating Bintang -->
    <div class="flex justify-center mb-2 space-x-2">
        <template x-for="star in 5" :key="star">
            <i class="fa fa-star cursor-pointer text-3xl transition-transform duration-200"
                :class="{
                    'text-yellow-400 scale-125': star <= (hover || rating),
                    'text-gray-300': star > (hover || rating)
                }"
                @click="rating = star" @mouseover="hover = star" @mouseleave="hover = 0">
            </i>
        </template>
    </div>
    <div class="text-center text-gray-700 mb-4 font-medium" x-text="
        rating == 1 ? 'Sangat Buruk' :
        rating == 2 ? 'Buruk' :
        rating == 3 ? 'Cukup' :
        rating == 4 ? 'Baik' :
        rating == 5 ? 'Sangat Baik' : 'Pilih ratingmu'
    "></div>

    <!-- Form Kritik & Saran -->
    <form method="POST" action="{{ route('web-rating.store') }}">
        @csrf
        <input type="hidden" name="rating" :value="rating">

        <textarea name="comment" placeholder="Tulis kritik dan saranmu di sini..."
            x-model="comment"
            class="w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:outline-none mb-4 resize-none"
            rows="5"></textarea>

        <div class="flex justify-end gap-3">
            <button type="submit" class="px-4 py-2 rounded-lg bg-blue-500 text-white hover:bg-blue-600 transition">
                Kirim Masukan
            </button>
        </div>
    </form>
</div>


@endsection
