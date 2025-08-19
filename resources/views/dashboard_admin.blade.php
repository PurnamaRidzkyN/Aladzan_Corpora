@extends('layouts.dashboard')

@section('title', 'Dashboard Admin')

@php
    $title = 'Dashboard Admin';
    $breadcrumb = [['label' => 'Dashboard Admin']];
@endphp

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="max-w-7xl mx-auto p-6 space-y-10">

        <!-- Summary Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Jumlah Reseller -->
            <div
                class="card bg-gradient-to-r from-blue-500 to-blue-700 shadow-lg text-white rounded-xl p-6 flex flex-col justify-center items-center">
                <h2 class="text-lg font-medium">Jumlah Reseller</h2>
                <p class="text-5xl font-bold">{{ $jumlahReseller }}</p>
            </div>
            <!-- Grafik Penjualan per Bulan -->
            <div class="card bg-white shadow-md rounded-xl p-6 col-span-1 sm:col-span-2 lg:col-span-2">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Penjualan per Bulan</h2>
                <div class="h-64">
                    <canvas id="salesChart" class="w-full h-full"></canvas>
                </div>
            </div>
        </section>

        <!-- Data Reseller dan Komposisi Produk -->
        <section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <!-- Pie Chart (Kotak) -->
            <div class="card bg-white shadow-lg rounded-2xl p-6 xl:col-span-1 flex flex-col border border-green-200">
                <!-- Judul -->
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-bold text-gray-800">🏆 Toko Terbaik</h2>
                    <span class="px-3 py-1 bg-green-100 text-green-700 text-sm rounded-full">
                        {{ number_format($bestShop->avg_rating, 2) }} ★
                    </span>
                </div>

                <!-- Nama toko -->
                <p class="text-xl font-semibold text-green-600 mb-6">{{ $bestShop->name }}</p>

                <!-- Pie Chart -->
                <div class="flex justify-center items-center flex-1">
                    <div class="w-64 h-64 relative">
                        <canvas id="topStoreProductChart"></canvas>
                    </div>
                </div>

                <!-- Keterangan produk -->
                <div class="mt-6 space-y-1">
                    @foreach ($productComposition as $product => $rating)
                        <p class="text-sm text-gray-600 flex justify-between">
                            <span>{{ $product }}</span>
                            <span class="font-medium">{{ $rating }} ★</span>
                        </p>
                    @endforeach
                </div>
            </div>


            <!-- Chart Panjang di sebelahnya -->
            <div class="card bg-white shadow-md rounded-xl p-6 xl:col-span-2">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Top 3 Reseller Berdasarkan Order</h2>
                <div class="h-56">
                    <canvas id="resellerChart"></canvas>
                </div>
            </div>
        </section>


        <!-- Rating Chart -->
        <section class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card bg-white shadow-md rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Rating Tertinggi per Toko</h2>
                <div class="h-56">
                    <canvas id="bestRatingBarChart"></canvas>
                </div>
            </div>
            <div class="card bg-white shadow-md rounded-xl p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Rating Terburuk per Toko</h2>
                <div class="h-56">
                    <canvas id="worstRatingBarChart"></canvas>
                </div>
            </div>
        </section>

        <section class="mt-12 space-y-6">
            <h2 class="text-3xl font-bold text-gray-800">🏆 Statistik Produk</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Produk Terlaris -->
                <div
                    class="bg-gradient-to-br from-green-50 to-green-100 shadow-lg rounded-2xl p-6 border border-green-200 hover:scale-105 transition-transform">
                    <h3 class="text-lg font-semibold text-green-700 mb-4 flex items-center gap-2 border-b pb-2">
                        <span class="text-2xl">🔥</span> Produk Terlaris
                    </h3>
                    <ul class="space-y-3">
                        @foreach ($topProducts as $product)
                            <li
                                class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm hover:bg-green-50">
                                <span class="font-medium text-gray-800">{{ $product->name }}</span>
                                <span class="text-green-600 font-bold">{{ $product->total_sold }}x</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Rating Tertinggi -->
                <div
                    class="bg-gradient-to-br from-yellow-50 to-yellow-100 shadow-lg rounded-2xl p-6 border border-yellow-200 hover:scale-105 transition-transform">
                    <h3 class="text-lg font-semibold text-yellow-700 mb-4 flex items-center gap-2 border-b pb-2">
                        <span class="text-2xl">⭐</span> Rating Tertinggi
                    </h3>
                    <ul class="space-y-3">
                        @foreach ($bestProducts as $product)
                            <li
                                class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm hover:bg-yellow-50">
                                <span class="font-medium text-gray-800">{{ $product->name }}</span>
                                <span class="text-yellow-600 font-bold">{{ number_format($product->avg_rating, 1) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Rating Terburuk -->
                <div
                    class="bg-gradient-to-br from-red-50 to-red-100 shadow-lg rounded-2xl p-6 border border-red-200 hover:scale-105 transition-transform">
                    <h3 class="text-lg font-semibold text-red-700 mb-4 flex items-center gap-2 border-b pb-2">
                        <span class="text-2xl">💔</span> Rating Terburuk
                    </h3>
                    <ul class="space-y-3">
                        @foreach ($worstProducts as $product)
                            <li class="flex justify-between items-center p-3 bg-white rounded-lg shadow-sm hover:bg-red-50">
                                <span class="font-medium text-gray-800">{{ $product->name }}</span>
                                <span class="text-red-600 font-bold">{{ number_format($product->avg_rating, 1) }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>

            </div>
        </section>

    </div>

    <script>
        // Penjualan per Bulan
        new Chart(document.getElementById('salesChart'), {
            type: 'bar',
            data: {
                labels: @json($bulan),
                datasets: [{
                    label: 'Penjualan',
                    data: @json($totalPenjualan),
                    backgroundColor: '#3B82F6',
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Top 3 Reseller
        new Chart(document.getElementById('resellerChart'), {
            type: 'bar',
            data: {
                labels: @json($resellerNames),
                datasets: [{
                    label: 'Jumlah Order',
                    data: @json($resellerOrders),
                    backgroundColor: '#60A5FA',
                    borderRadius: 6
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
    <script>
        new Chart(document.getElementById('topStoreProductChart'), {
            type: 'pie',
            data: {
                labels: @json($productComposition->keys()),
                datasets: [{
                    label: 'Komposisi Produk',
                    data: @json($productComposition->values()),
                    backgroundColor: ['#34D399', '#10B981', '#059669']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });


        new Chart(document.getElementById('bestRatingBarChart'), {
            type: 'bar',
            data: {
                labels: @json($bestRatingNames),
                datasets: [{
                    label: 'Rating Tertinggi',
                    data: @json($bestRatingValues),
                    backgroundColor: '#FBBF24'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        max: 5
                    }
                }
            }
        });

        new Chart(document.getElementById('worstRatingBarChart'), {
            type: 'bar',
            data: {
                labels: @json($worstRatingNames),
                datasets: [{
                    label: 'Rating Terburuk',
                    data: @json($worstRatingValues),
                    backgroundColor: '#EF4444'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        min: 0,
                        max: 5
                    }
                }
            }
        });
    </script>
@endsection
