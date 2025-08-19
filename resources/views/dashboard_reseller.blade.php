@extends('layouts.dashboard')

@section('title', 'Dashboard Reseller')

@php
    $title = 'Dashboard Reseller';
    $breadcrumb = [['label' => 'Dashboard Reseller']];
@endphp

@section('content')
    <div class="max-w-7xl mx-auto p-6 space-y-10">

        <!-- Summary Cards -->
        <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-gradient-to-r from-blue-500 to-blue-700 shadow-lg text-white rounded-xl p-6 flex flex-col justify-center items-center">
                <h2 class="text-lg font-medium">Total Order</h2>
                <p class="text-3xl font-bold">{{ $totalPembelian }}</p>
            </div>

            <div
                class="bg-gradient-to-r from-green-500 to-green-700 shadow-lg text-white rounded-xl p-6 flex flex-col justify-center items-center">
                <h2 class="text-lg font-medium">Total Belanja</h2>
                <p class="text-3xl font-bold">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</p>
            </div>

            <div
                class="bg-gradient-to-r from-purple-500 to-purple-700 shadow-lg text-white rounded-xl p-6 flex flex-col justify-center items-center">
                <h2 class="text-lg font-medium">Jumlah Toko Dibeli</h2>
                <p class="text-3xl font-bold">{{ $jumlahToko }}</p>
            </div>

            <div
                class="bg-gradient-to-r from-yellow-400 to-yellow-600 shadow-lg text-white rounded-xl p-6 flex flex-col justify-center items-center">
                <h2 class="text-lg font-medium">Produk Unik Dibeli</h2>
                <p class="text-3xl font-bold">{{ $jumlahProdukUnik }}</p>
            </div>
        </section>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            <!-- Rata-rata Rating -->
            <div
                class="bg-gradient-to-r from-red-400 to-red-600 shadow-lg text-white rounded-2xl p-6 flex flex-col justify-center items-center">
                <h2 class="text-lg font-medium mb-4">Rata-rata Rating Produk Dibeli</h2>
                <p class="text-5xl font-bold">{{ number_format($avgRatingBought, 2) }} ★</p>
            </div>
            <!-- Top Produk -->
            <div class="bg-white shadow-lg rounded-2xl p-6">
                <h3 class="text-lg font-semibold mb-4">🔥 Top Produk Dibeli</h3>
                <ul class="space-y-3">
                    @foreach ($topProducts as $product)
                        <li class="flex justify-between items-center p-3 bg-gray-50 rounded-lg shadow-sm">
                            <span class="font-medium text-gray-800">{{ $product->name }}</span>
                            <span class="text-green-600 font-bold">{{ $product->total_beli }}x</span>
                            <span class="text-yellow-500 font-medium">{{ number_format($product->avg_rating, 1) }} ★</span>
                        </li>
                    @endforeach
                </ul>
            </div>


            <!-- Tambahan: Bisa ditaruh info lain di sini -->
            <div class="bg-white shadow-md rounded-2xl p-6">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Distribusi Top Produk</h2>
                <div class="w-full h-64">
                    <canvas id="topProductChart"></canvas>
                </div>
            </div>
        </section>

        <!-- Charts Row (di bawah semua) -->
        <!-- Belanja per Bulan -->
        <div class="bg-white shadow-md rounded-2xl p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700">Belanja per Bulan</h2>
            <div class="h-64">
                <canvas id="salesChart"></canvas>
            </div>
        </div>




    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sales per Month
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
                    maintainAspectRatio: false
                }
            });

            // Distribusi Top Products
            new Chart(document.getElementById('topProductChart'), {
                type: 'pie',
                data: {
                    labels: @json($topProducts->pluck('name')),
                    datasets: [{
                        data: @json($topProducts->pluck('total_beli')),
                        backgroundColor: ['#34D399', '#10B981', '#059669', '#6EE7B7', '#A7F3D0']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 20,
                                padding: 15
                            }
                        }
                    }
                }
            });
        });
    </script>

@endsection
