@extends('layouts.dashboard')
@section('title', 'Manajemen Produk')

@section('content')
    <section class="w-full lg:px-12 mt-8 space-y-6">

        <!-- Full Box: Toko -->
        <div
            class="card bg-white shadow-md rounded-xl border border-soft p-6 flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-primary mb-1">Toko Sakura</h2>
                <p class="text-sm text-gray-700 max-w-3xl">
                    Toko yang menjual berbagai makanan dan minuman khas Jepang, segar dan halal. Cocok untuk pecinta kuliner
                    Jepang!
                </p>
            </div>
            <a href="#" class="btn btn-sm btn-outline-primary self-start lg:self-auto">Edit Toko</a>
        </div>


        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">


            <!-- LEFT: List Produk (scroll) -->
            <div class="overflow-y-auto pr-1 max-h-[calc(100vh-150px)]">
                <div class="card bg-white shadow-md rounded-xl border border-soft p-4">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-primary">Produk</h3>
                        <label for="modal_produk" class="btn btn-sm btn-gradient-primary text-white">+ Tambah Produk</label>
                    </div>

                    <!-- Search -->
                    <div class="form-control mb-3">
                        <input type="text" placeholder="Cari produk..." class="input input-bordered w-full text-sm" />
                    </div>

                    <!-- Filter -->
                    <div class="form-control mb-4">
                        <select class="select select-bordered w-full text-sm">
                            <option disabled selected>Filter Kategori</option>
                            <option>Minuman</option>
                            <option>Makanan</option>
                        </select>
                    </div>

                    <!-- Daftar Produk -->
                    <ul class="divide-y">
                        @foreach ([['id' => 1, 'name' => 'Matcha Latte', 'price' => 20000], ['id' => 2, 'name' => 'Kue Mochi', 'price' => 15000], ['id' => 3, 'name' => 'Onigiri', 'price' => 12000]] as $product)
                            <li>
                                <a href="#" class="block p-3 hover:bg-accent-light rounded-lg transition">
                                    <div class="flex justify-between items-center">
                                        <span class="font-medium">{{ $product['name'] }}</span>
                                        <span
                                            class="text-sm text-gray-500">Rp{{ number_format($product['price'], 0, ',', '.') }}</span>
                                    </div>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <!-- RIGHT: Detail Produk -->
            <div
                class="bg-white rounded-xl shadow-md border border-soft p-6 sticky top-20 overflow-hidden flex flex-col max-h-[calc(100vh-150px)]">
                <!-- Header & Action -->
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-xl font-bold text-primary">Detail Produk</h3>
                    <div class="space-x-2">
                        <button class="btn btn-warning btn-xs text-white">Edit</button>
                        <label for="delete_product" class="btn btn-error btn-xs text-white">Hapus</label>
                    </div>
                </div>

                <!-- Gambar -->
                <div class="grid grid-cols-3 gap-2 mb-4">
                    <img src="https://placehold.co/150x100" class="rounded-xl border" alt="">
                    <img src="https://placehold.co/150x100" class="rounded-xl border" alt="">
                    <img src="https://placehold.co/150x100" class="rounded-xl border" alt="">
                </div>

                <!-- Info Produk -->
                <div class="text-sm space-y-1 mb-4">
                    <p><strong>Nama:</strong> Matcha Latte</p>
                    <p><strong>Kategori:</strong> Minuman</p>
                    <p><strong>Harga:</strong> Rp20.000</p>
                    <p><strong>Stok:</strong> 15 pcs</p>
                    <p><strong>Penjualan:</strong> 143 kali</p>
                    <p><strong>Rating:</strong> ⭐ 4.6 dari 10 ulasan</p>
                </div>

                <!-- Deskripsi -->
                <div class="mb-4">
                    <p class="font-semibold text-sm mb-1">Deskripsi</p>
                    <div class="bg-gray-50 border rounded-md p-2 text-sm max-h-24 overflow-y-auto">
                        Teh hijau Jepang dengan susu yang creamy dan menyegarkan. Disukai semua kalangan.
                    </div>
                </div>

                <!-- Tombol Ulasan -->
                <div class="mt-auto">
                    <a href="#" class="btn btn-sm btn-outline w-full">Lihat Semua Ulasan (10)</a>
                </div>
            </div>
        </div>
    </section>


<!-- MODAL TAMBAH PRODUK -->
<input type="checkbox" id="modal_produk" class="modal-toggle" />
<div class="modal">
    <div class="modal-box w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <h3 class="font-bold text-xl text-primary mb-4">Tambah Produk</h3>

        <form action="#" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf

            <!-- Kolom Kiri -->
            <div class="space-y-4">
                <input type="text" name="name" placeholder="Nama Produk" class="input input-bordered w-full" required>

                <input type="number" name="price" placeholder="Harga (Rp)" class="input input-bordered w-full" required>

                <input type="number" name="stock" placeholder="Stok" class="input input-bordered w-full" required>

                <select class="select select-bordered w-full" name="category" required>
                    <option disabled selected>Pilih Kategori</option>
                    <option>Minuman</option>
                    <option>Makanan</option>
                </select>
                <textarea name="description" placeholder="Deskripsi" class="textarea textarea-bordered w-full h-32 resize-none"></textarea>

            </div>

            <!-- Kolom Kanan -->
            <div class="space-y-4">

                <!-- Media Produk -->
<!-- Media Produk -->
<div class="space-y-2">
    <!-- Label + Tombol -->
    <div class="flex items-center justify-between">
        <label class="font-semibold text-sm">Media Produk</label>
        <label for="mediaInput" class="btn btn-sm btn-outline-primary">+ Pilih File</label>
    </div>

    <!-- Hidden Input -->
    <input 
        id="mediaInput" 
        type="file" 
        class="hidden" 
        multiple 
        accept="image/*,video/*"
        onchange="addFiles(event)"
    >

    <!-- Info jumlah file -->
    <p id="fileCount" class="text-xs text-gray-500">0 file dipilih</p>

    <!-- Daftar File -->
    <ul id="fileList" class="grid grid-cols-2 gap-2 text-sm text-gray-800 max-h-48 overflow-y-auto pr-1">
        <!-- file item akan muncul di sini -->
    </ul>
</div>


            </div>

            <!-- Tombol (penuh 2 kolom) -->
            <div class="col-span-1 md:col-span-2 flex justify-end gap-2 pt-4 border-t mt-4">
                <label for="modal_produk" class="btn">Batal</label>
                <button type="submit" class="btn btn-gradient-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
<script>
    let selectedFiles = [];

    function addFiles(event) {
        const inputFiles = Array.from(event.target.files);
        selectedFiles = selectedFiles.concat(inputFiles);
        renderFileList();
    }

    function renderFileList() {
        const listEl = document.getElementById('fileList');
        const countEl = document.getElementById('fileCount');
        listEl.innerHTML = '';
        countEl.textContent = `${selectedFiles.length} file dipilih`;

        selectedFiles.forEach((file, index) => {
            const isVideo = file.type.startsWith('video/');
            const icon = isVideo ? '🎥' : '🖼️';

            const li = document.createElement('li');
            li.className = 'flex justify-between items-center border border-gray-300 bg-white px-3 py-2 rounded-md shadow';

            li.innerHTML = `
                <span class="truncate w-[80%]">${icon} ${file.name}</span>
                <button type="button" class="text-red-500 text-xs hover:underline" onclick="removeFile(${index})">Hapus</button>
            `;

            listEl.appendChild(li);
        });
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        renderFileList();
    }

    // Untuk kirim data ke backend
    document.querySelector('form').addEventListener('submit', function (e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        selectedFiles.forEach(file => {
            formData.append('media[]', file);
        });

        fetch(form.action, {
            method: 'POST',
            body: formData,
        }).then(res => {
            if (res.ok) {
                alert('Produk berhasil ditambahkan!');
                selectedFiles = [];
                renderFileList();
                form.reset();
            } else {
                alert('Gagal mengunggah!');
            }
        });
    });
</script>




    <!-- MODAL HAPUS PRODUK -->
    <input type="checkbox" id="delete_product" class="modal-toggle" />
    <div class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
            <p class="py-4">Yakin ingin menghapus produk <strong>Matcha Latte</strong>?</p>
            <div class="modal-action">
                <button class="btn btn-gradient-error">Ya, Hapus</button>
                <label for="delete_product" class="btn">Batal</label>
            </div>
        </div>
    </div>
 @endsection
