@extends('layouts.dashboard')
@section('title', 'Produk Toko ' . $shop->name)
@php
    $title = 'Produk Toko ' . $shop->name;
    $breadcrumb = [
        ['label' => 'Manajemen Produk'],
        ['label' => 'List Toko', 'url' => route('shops.index')],
        ['label' => $shop->name],
    ];
@endphp
@section('content')
@if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
            class="fixed inset-x-0 top-10 mx-auto max-w-md 
           rounded-lg shadow-md px-6 py-3 flex items-center space-x-3
           text-green-900
           bg-gradient-to-r from-green-100 via-green-50 to-green-100"
            x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0 text-green-600" fill="none"
                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
            </svg>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    <section class="w-full lg:px-12 mt-8 space-y-6">

        <!-- Full Box: Toko -->
        <div
            class="card bg-white shadow-md rounded-xl border border-soft p-6 flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-primary mb-1">{{ $shop->name }}</h2>
                <p class="text-sm text-gray-700 max-w-3xl">
                    {{ $shop->description }}
                </p>
            </div>
            <label for="modal_edit_toko" class="btn btn-gradient-warning self-start lg:self-auto">Edit Toko</label>
        </div>


        <div x-data="produkFilter({{ $products->toJson() }}, {{ json_encode($categories) }})" class="grid grid-cols-1 lg:grid-cols-2 gap-6">

            <!-- LEFT: List Produk -->
            <div class="overflow-y-auto pr-1 max-h-[calc(100vh-150px)]">

                <div class="card bg-white shadow-md rounded-xl border border-soft p-4">
                    <div class="mb-4 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-primary">Produk</h3>
                        <button class="btn btn-sm btn-gradient-primary" @click="$store.productForm.openAdd()">+ Tambah
                            Produk</button>
                    </div>
                    <!-- Search -->
                    <div class="form-control mb-4">
                        <input type="text" placeholder="Cari produk..." class="input input-bordered w-full text-sm"
                            x-model="search" />
                    </div>

                    <!-- Filter Kategori -->
                    <div class="mb-4" x-data="{ searchKategori: '' }">
                        <p class="font-semibold text-sm mb-2">Filter Kategori</p>

                        <!-- Input search -->
                        <input type="text" x-model="searchKategori" placeholder="Cari kategori..."
                            class="input input-sm input-bordered w-full mb-2" />

                        <!-- List Kategori Filtered -->
                        <div class="border rounded-md p-2 max-h-40 overflow-y-auto grid grid-cols-2 gap-2">
                            <template
                                x-for="kategori in kategoriList.filter(k => k.name.toLowerCase().includes(searchKategori.toLowerCase()))"
                                :key="kategori.id">
                                <label
                                    class="cursor-pointer flex items-center gap-2 bg-gray-100 px-3 py-2 rounded-md text-sm">
                                    <input type="checkbox" class="checkbox checkbox-sm checkbox-primary"
                                        :value="kategori.name" x-model="selectedCategories">
                                    <span x-text="kategori.name"></span>
                                </label>
                            </template>
                        </div>
                    </div>


                    <!-- Daftar Produk -->
                    <template x-if="filteredProducts.length > 0">
                        <ul class="divide-y">
                            <template x-for="product in filteredProducts" :key="product.id">
                                <li>
                                    <a href="#" @click.prevent="selected = product"
                                        class="block p-3 hover:bg-accent-light rounded-lg transition">
                                        <div class="flex justify-between items-center">
                                            <span class="font-medium" x-text="product.name"></span>
                                            <span class="text-sm text-gray-500"
                                                x-text="'Rp' + Number(product.price).toLocaleString('id-ID')"></span>
                                        </div>
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </template>

                    <!-- Jika kosong -->
                    <div x-show="filteredProducts.length === 0" class="text-center text-gray-500 text-sm py-6">
                        <i class="fa-solid fa-box-open text-2xl mb-2 text-gray-400"></i><br>
                        Tidak ada produk ditemukan.
                    </div>

                </div>
            </div>

            <!-- RIGHT: Detail Produk -->
            <div
                class="bg-white rounded-xl shadow-md border border-soft p-6 sticky top-20 overflow-hidden flex flex-col max-h-[calc(100vh-150px)]">
                <!-- Jika belum ada yang dipilih -->
                <div x-show="!selected" class="text-center text-gray-500 text-sm my-auto">
                    <i class="fa-solid fa-box-open text-2xl mb-2 text-gray-400"></i><br>
                    Pilih produk untuk melihat detailnya.
                </div>

                <!-- Jika ada yang dipilih -->
                <template x-if="selected">
                    <div class="flex flex-col h-full">
                        <!-- Header & Aksi -->
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-primary">Detail Produk</h3>
                            <div class="space-x-2">
                                <button class="btn btn-gradient-warning btn-xs"
                                    @click="$store.productForm.openEdit(selected)">Edit</button>
<label for="delete_product" class="btn btn-gradient-error btn-xs text-white" @click="confirmDelete(selected.id, selected.name)">Hapus</label>                            </div>
                        </div>

                        <!-- Gambar -->
                        <div x-data="{ preview: null, type: null }">
                            <div class="flex overflow-x-auto gap-2 mb-4 pb-2">
                                <template x-if="selected.media && selected.media.length">
                                    <template x-for="item in selected.media" :key="item.id">
                                        <div class="flex-shrink-0 w-32 h-32 relative">
                                            <template x-if="item.file_type.startsWith('image/')">
                                                <img :src="'https://drive.google.com/thumbnail?id=' + item.file_path + '&sz=w320'"
                                                    class="w-full h-full object-cover rounded-xl border cursor-pointer"
                                                    alt="Gambar Produk"
                                                    @click="preview = 'https://drive.google.com/thumbnail?id=' + item.file_path + '&sz=w1024'; type = 'image'">
                                            </template>
                                            <template x-if="item.file_type.startsWith('video/')">
                                                <div class="relative w-full h-full cursor-pointer"
                                                    @click="preview = 'https://drive.google.com/uc?id=' + item.file_path; type = 'video'">
                                                    <video
                                                        class="w-full h-full object-cover rounded-xl border pointer-events-none">
                                                        <source :src="'https://drive.google.com/uc?id=' + item.file_path"
                                                            :type="item.file_type">
                                                    </video>
                                                    <div
                                                        class="absolute inset-0 bg-black bg-opacity-30 flex items-center justify-center text-white text-xl font-bold">
                                                        ▶
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </template>
                                </template>
                                <template x-if="!selected.media || selected.media.length === 0">
                                    <img src="https://placehold.co/150x100?text=No+Media"
                                        class="rounded-xl border w-32 h-32" alt="No Media">
                                </template>
                            </div>


                            <!-- Modal Preview -->
                            <template x-if="preview">
                                <div class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center z-50">
                                    <div class="relative max-w-3xl w-full p-4">
                                        <template x-if="type === 'image'">
                                            <img :src="preview"
                                                class="w-full max-h-[80vh] object-contain rounded shadow-lg" />

                                        </template>
                                        <template x-if="type === 'video'">
                                            <video :src="preview" controls autoplay
                                                class="w-full h-auto rounded shadow-lg"></video>
                                        </template>
                                        <button @click="preview = null"
                                            class="absolute top-2 right-2 bg-white text-black px-3 py-1 rounded-full shadow">
                                            ✕
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>


                        <!-- Info -->
                        <div class="text-sm space-y-1 mb-4">
                            <p><strong>Nama:</strong> <span x-text="selected.name"></span></p>
                            <p><strong>Kategori:</strong> <span x-text="selected.category?.name || '-'"></span></p>
                            <p><strong>Harga:</strong> Rp<span
                                    x-text="Number(selected.price).toLocaleString('id-ID')"></span></p>
                            <p><strong>Penjualan:</strong> <span x-text="selected.sold || 0"></span> kali</p>
                            <p><strong>Rating:</strong> ⭐ <span x-text="Number(selected.rating).toFixed(1)"></span> dari
                                <span x-text="selected.reviews_count || 0"></span> ulasan
                            </p>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <p class="font-semibold text-sm mb-1">Deskripsi</p>
                            <div class="bg-gray-50 border rounded-md p-2 text-sm max-h-24 overflow-y-auto"
                                x-text="selected.description || 'Tidak ada deskripsi.'"></div>
                        </div>

                        <!-- Tombol Ulasan -->
                        <div class="mt-auto">
                            <a :href="'/produt/' + selected.id + '/ulasan'" class="btn btn-sm btn-outline w-full">
                                Lihat Semua Ulasan (<span x-text="selected.reviews_count || 0"></span>)
                            </a>
                        </div>
                    </div>
                </template>
            </div>
        </div>

    </section>
    <input type="checkbox" id="modal_produk" class="modal-toggle" />
    <div class="modal" x-data>
        <div class="modal-box w-full max-w-5xl max-h-[90vh] overflow-y-auto">
            <h3 class="font-bold text-xl text-primary mb-4"
                x-text="$store.productForm.mode === 'edit' ? 'Edit Produk' : 'Tambah Produk'"></h3>

            <form x-on:submit="rebuildHiddenInputs()"
                
                :action="$store.productForm.mode === 'edit' ?
                    ('/admin/products/' + $store.productForm.formData.id) :
                    '/admin/shops/{{ $shop->id }}/products'"
                method="POST" enctype="multipart/form-data" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf

                <!-- Override method jika mode edit -->
                <template x-if="$store.productForm.mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>
                <input type="hidden" name="product_id" :value="$store.productForm.formData.id"
                    x-show="$store.productForm.mode === 'edit'">
                <input type="text" name="shop_id" value="{{ $shop->id }}" hidden>
                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <input type="text" name="name" placeholder="Nama Produk" class="input input-bordered w-full"
                        x-model="$store.productForm.formData.name" required>

                    <input type="number" name="price" placeholder="Harga (Rp)" class="input input-bordered w-full"
                        x-model="$store.productForm.formData.price" required>

                    <!-- Kategori -->
                    <div>
                        <p class="font-semibold text-sm mb-2">Kategori</p>
                        <div class="border rounded-md p-2 max-h-40 overflow-y-auto grid grid-cols-2 gap-2">
                            @foreach ($categories as $kategori)
                                <label
                                    class="cursor-pointer flex items-center gap-2 bg-gray-100 px-3 py-2 rounded-md text-sm">
                                    <input type="checkbox" name="categories[]" value="{{ $kategori->id }}"
                                        class="checkbox checkbox-sm checkbox-primary"
                                        :checked="$store.productForm.formData.categories.includes({{ $kategori->id }})"
                                        @change="
                                        $event.target.checked
                                            ? $store.productForm.formData.categories.push({{ $kategori->id }})
                                            : $store.productForm.formData.categories.splice($store.productForm.formData.categories.indexOf({{ $kategori->id }}), 1)
                                    ">
                                    <span>{{ $kategori->name }}</span>
                                </label>
                            @endforeach

                        </div>
                    </div>

                    <textarea name="description" placeholder="Deskripsi" class="textarea textarea-bordered w-full h-32 resize-none"
                        x-model="$store.productForm.formData.description"></textarea>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <!-- Media Produk -->
                     <div class="space-y-2">
        <div class="flex items-center justify-between">
            <label class="font-semibold text-sm">Media Produk</label>
            <label for="mediaInput" class="btn btn-sm btn-outline-primary">+ Pilih File</label>
        </div>

        <div id="realFileInputs">
            <input id="mediaInput" type="file" class="hidden" multiple accept="image/*,video/*"
                onchange="addFiles(event)">
            <div id="hiddenInputsContainer"></div>
        </div>

        <p id="fileCount" class="text-xs text-gray-500">0 file dipilih</p>
        <ul id="fileList"
            class="grid grid-cols-2 gap-2 text-sm text-gray-800 max-h-48 overflow-y-auto pr-1">
        </ul>
    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="col-span-1 md:col-span-2 flex justify-end gap-2 pt-4 border-t mt-4">
                    <label for="modal_produk" class="btn" @click="$store.productForm.resetForm()">Batal</label>
                    <button type="submit" class="btn btn-gradient-primary"
                        x-text="$store.productForm.mode === 'edit' ? 'Perbarui' : 'Simpan'"></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('productForm', {
                mode: 'add',
                formData: {
                    name: '',
                    price: '',
                    stock: '',
                    categories: [],
                    description: '',
                },

                openAdd() {
                    this.mode = 'add';
                    this.formData = {
                        name: '',
                        price: '',
                        stock: '',
                        categories: [],
                        description: '',
                    };
                    // Isi media lama

                    document.getElementById('modal_produk').checked = true;
                },

                openEdit(product) {
                    this.mode = 'edit';
                    this.formData = {
                        id: product.id,
                        name: product.name,
                        price: product.price,
                        stock: product.stock,
                        categories: product.categories?.map(c => c.id) || [],
                        description: product.description || '',
                    };
                    this.selected = {
                        ...product,
                        media: product.media || []
                    };

                    window.existingMedia = (product.media || []).map(item => ({
                        ...item
                    }));
                    window.deletedMediaIds = [];
                    window.selectedFiles = [];
                    renderFileList();
                    rebuildHiddenInputs();

                    document.getElementById('modal_produk').checked = true;
                },

                resetForm() {
                    this.mode = 'add';
                    this.formData = {
                        name: '',
                        price: '',
                        stock: '',
                        categories: [],
                        description: '',
                    };
                }
            });
        });
    </script>

    <script>
        let selectedFiles = [];
        let removedMedia = [];
        window.existingMedia = []; // jangan lupa inisialisasi di luar
        window.deletedMediaIds = [];

        function addFiles(event) {
            const inputFiles = Array.from(event.target.files);
            selectedFiles = selectedFiles.concat(inputFiles);
            renderFileList();
            rebuildHiddenInputs();

            event.target.value = ''; // Reset agar bisa upload file yang sama lagi
        }

        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderFileList();
            rebuildHiddenInputs();
        }


        function removeExistingMedia(index) {
            const removed = window.existingMedia.splice(index, 1)[0]; // ambil & hapus
            if (removed && removed.id) {
                window.deletedMediaIds.push(removed.id); // simpan ID-nya
            }
            renderFileList(); // render ulang UI
            rebuildHiddenInputs(); // rebuild input hidden agar data ikut ke backend
        }



        function renderFileList() {
            const listEl = document.getElementById('fileList');
            const countEl = document.getElementById('fileCount');
            listEl.innerHTML = '';

            const totalFiles = selectedFiles.length + existingMedia.length;
            countEl.textContent = `${totalFiles} file dipilih`;

            // 📁 Media lama
            existingMedia.forEach((item, index) => {
                const isVideo = item.file_type.startsWith('video/');
                const li = document.createElement('li');
                li.className = 'relative border rounded-md shadow overflow-hidden group';

                const content = isVideo ?
                    `<video class="w-full h-24 object-cover" muted>
                    <source src="https://drive.google.com/uc?id=${item.file_path}" type="${item.file_type}">
               </video>` :
                    `<img src="https://drive.google.com/thumbnail?id=${item.file_path}&sz=w320"
                    class="w-full h-24 object-cover" alt="${item.original_name}">`;

                li.innerHTML = `
            ${content}
            <div class="absolute bottom-0 bg-black bg-opacity-50 text-white text-xs px-2 py-1 truncate">
                ${isVideo ? '🎥' : '🖼️'} ${item.original_name}
            </div>
            <button type="button"
                class="absolute top-1 right-1 bg-white text-red-600 text-xs px-2 py-1 rounded shadow hidden group-hover:block"
                onclick="removeExistingMedia(${index})">✕</button>
        `;

                listEl.appendChild(li);
            });

            // 📁 Media baru
            selectedFiles.forEach((file, index) => {
                const isVideo = file.type.startsWith('video/');
                const fileURL = URL.createObjectURL(file);
                const li = document.createElement('li');
                li.className = 'relative border rounded-md shadow overflow-hidden group';

                const content = isVideo ?
                    `<video class="w-full h-24 object-cover" muted>
                    <source src="${fileURL}" type="${file.type}">
               </video>` :
                    `<img src="${fileURL}" class="w-full h-24 object-cover" alt="${file.name}">`;

                li.innerHTML = `
            ${content}
            <div class="absolute bottom-0 bg-black bg-opacity-50 text-white text-xs px-2 py-1 truncate">
                ${isVideo ? '🎥' : '🖼️'} ${file.name}
            </div>
            <button type="button"
                class="absolute top-1 right-1 bg-white text-red-600 text-xs px-2 py-1 rounded shadow hidden group-hover:block"
                onclick="removeFile(${index})">✕</button>
        `;

                listEl.appendChild(li);
            });
        }


       function rebuildHiddenInputs() {
    const container = document.getElementById('realFileInputs');
    const hiddenInputsContainer = document.getElementById('hiddenInputsContainer');
    hiddenInputsContainer.innerHTML = ''; // Clear sebelumnya

    // Tambah file baru
    selectedFiles.forEach((file) => {
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);

        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'media[]';
        input.files = dataTransfer.files;
        input.hidden = true;
        hiddenInputsContainer.appendChild(input);
    });

    // Tambah input deleted_media[]
    (window.deletedMediaIds || []).forEach((id) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'deleted_media[]';
        input.value = id;
        hiddenInputsContainer.appendChild(input);
    });
}

    </script>


    <!-- MODAL HAPUS PRODUK -->
 <input type="checkbox" id="delete_product" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Konfirmasi Hapus</h3>
        <p class="py-4">Yakin ingin menghapus produk <strong id="productNameToDelete">Produk</strong>?</p>
        <div class="modal-action">
            <form method="POST" id="deleteProductForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-gradient-error">Ya, Hapus</button>
                <label for="delete_product" class="btn">Batal</label>
            </form>
        </div>
    </div>
</div>
<script>
    function confirmDelete(id, name) {
        document.getElementById('productNameToDelete').textContent = name;

        // Ganti action form dengan route yang benar
        const form = document.getElementById('deleteProductForm');
        form.action = `{{ route('products.destroy', ':id') }}`.replace(':id', id);
    }
</script>

    <script>
        function produkFilter(products, categories) {
            console.log(products);
            return {
                products: products,
                kategoriList: categories,
                selected: null,
                search: '',
                selectedCategories: [],
                get filteredProducts() {
                    return this.products.filter(p => {
                        const cocokNama = p.name.toLowerCase().includes(this.search.toLowerCase());

                        // ambil semua nama kategori dari produk ini, lowercase-kan
                        const kategoriProduk = (p.categories || []).map(c => c.name.toLowerCase());

                        // jika tidak ada yang dicentang, semua cocok
                        const cocokKategori = this.selectedCategories.length === 0 ||
                            kategoriProduk.some(kat => this.selectedCategories.map(c => c.toLowerCase())
                                .includes(kat));

                        return cocokNama && cocokKategori;
                    });
                }


            }
        }
    </script>
<input type="checkbox" id="modal_edit_toko" class="modal-toggle" {{ old('editing') ? 'checked' : '' }} />
<div class="modal">
    <div class="modal-box w-full max-w-md">
        <h3 class="font-bold text-lg mb-2 text-primary">Edit Toko</h3>
        <form action="{{ route('shops.update', $shop->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <input type="text" name="name" value="{{ old('name', $shop->name) }}"
                class="input input-bordered w-full" required>
            <textarea name="description" rows="4"
                class="textarea textarea-bordered w-full" required>{{ old('description', $shop->description) }}</textarea>

            <div class="modal-action">
                <label for="modal_edit_toko" class="btn">Batal</label>
                <button type="submit" class="btn btn-gradient-primary">Perbarui</button>
            </div>
        </form>
    </div>
</div>

@endsection
