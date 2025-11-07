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
    <section class="w-full lg:px-12 mt-8 space-y-6">

        <!-- Full Box: Toko -->
        <div
            class="card bg-white shadow-md rounded-xl border border-soft p-6 flex flex-col lg:flex-row items-start lg:items-center gap-6">
            <!-- Gambar di kiri -->
            <div class="flex-shrink-0">
                <img src="{{ cloudinary_url($shop->img_path ?? 'productDefault_nawcx4') }}"
                    class="w-24 h-24 rounded-full object-cover border-2 border-blue-300" alt="Foto toko">
            </div>

            <!-- Konten di kanan -->
            <div class="flex-1 space-y-2">
                <h2 class="text-2xl font-bold text-primary">{{ $shop->name }}</h2>
                <p class="text-sm text-gray-700 max-w-3xl">{{ $shop->description }}</p>
                <p class="text-sm text-gray-600 flex items-center gap-1">
                    Kota/Kabupaten: <span class="font-medium">{{ $shop->city }}</span>
                </p>
            </div>

            <!-- Tombol Edit -->
            <label for="modal_edit_toko" class="btn btn-gradient-warning whitespace-nowrap self-start lg:self-center">
                Edit Toko
            </label>
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
                                                x-html="`<i class='fas fa-star text-yellow-400 mr-1'></i> ${Number(product?.rating?.rating ?? 0).toFixed(1)}`">
                                            </span>

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
                                <!-- Tombol Edit, muncul kalau belum soft delete -->
                                <button class="btn btn-gradient-warning btn-xs" x-show="!selected.deleted_at"
                                    @click="$store.productForm.openEdit(selected)">
                                    Edit
                                </button>

                                <label for="delete_product" class="btn btn-gradient-error btn-xs " x-show="!selected.deleted_at"
                                    @click="confirmDelete(selected.id, selected.name)">Hapus</label>
                               

                                <!-- Tombol Restore, muncul kalau sudah soft delete -->
                                <label class="btn btn-gradient-warning btn-xs" x-show="selected.deleted_at"
                                    @click="restoreProduct(selected.id)">
                                    Restore
                                </label>

                                <!-- Tombol Hapus Permanen, muncul kalau sudah soft delete -->
                                <label class="btn btn-gradient-error btn-xs" x-show="selected.deleted_at"
                                    @click="forceDeleteProduct(selected.id)">
                                    Hapus Permanen
                                </label>

                            </div>
                        </div>

                        <!-- Gambar -->
                        <div x-data="{ preview: null, type: null }">
                            <div class="flex overflow-x-auto gap-2 mb-4 pb-2">
                                <template x-if="selected.media && selected.media.length">
                                    <template x-for="item in selected.media" :key="item.id">
                                        <div class="flex-shrink-0 w-32 h-32 relative">
                                            <!-- IMAGE -->
                                            <template x-if="item.file_type.startsWith('image')">
                                                <img :src="'https://res.cloudinary.com/dpujlyn9x/image/upload/' + item.file_path"
                                                    class="w-full h-full object-cover rounded-xl border cursor-pointer"
                                                    alt="Gambar Produk"
                                                    @click="preview = 'https://res.cloudinary.com/dpujlyn9x/image/upload/' + item.file_path ; type = 'image'">
                                            </template>

                                            <!-- VIDEO -->
                                            <template x-if="item.file_type.startsWith('video')">
                                                <div class="relative w-full h-full cursor-pointer"
                                                    @click="preview = 'https://res.cloudinary.com/dpujlyn9x/video/upload/' + item.file_path; type = 'video'">

                                                    <!-- Thumbnail dari frame pertama video -->
                                                    <img :src="'https://res.cloudinary.com/dpujlyn9x/video/upload/so_0/' + item
                                                        .file_path + '.jpg'"
                                                        alt="Thumbnail Video"
                                                        class="w-full h-full object-cover rounded-xl border" />

                                                    <!-- Overlay ikon play -->
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
                            <p><strong>Kategori:</strong>
                                <span x-text="selected.categories?.map(c => c.name).join(', ') || '-'"></span>
                            </p>
                            <template x-if="selected.variants && selected.variants.length">
                                <div>
                                    <p class="font-semibold text-sm mb-1">Varian & Harga:</p>
                                    <ul class="text-sm list-disc list-inside">
                                        <template x-for="variant in selected.variants" :key="variant.id">
                                            <li>
                                                <span x-text="variant.name"></span>: Rp
                                                <span x-text="Number(variant.price).toLocaleString('id-ID')"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </div>
                            </template>
                            <p><strong>Penjualan:</strong> <span x-text="selected.sold || 0"></span> kali</p>
                            <p>
                                <strong>Rating:</strong>
                                <i class="fas fa-star text-yellow-400"></i>
                                <span x-text="Number(selected.rating?.rating || 0).toFixed(1)"></span>
                                dari
                                <span x-text="selected.rating?.rating_count || 0"></span> ulasan
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
                            <a :href="'/staff-only/reviews/' + selected.slug" class="btn btn-sm btn-outline w-full">
                                Lihat Semua Ulasan (<span x-text="selected.rating.rating_count || 0"></span>)
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
                    '{{ route('products.update', ['product' => '__ID__']) }}'.replace('__ID__', $store.productForm
                        .formData.id) :
                    '{{ route('shops.products.store', $shop->id) }}'"
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
                    <div class="flex items-center">
                        <input type="text" name="weight" placeholder="Berat"
                            class="input input-bordered w-full rounded-r-none"
                            x-model="$store.productForm.formData.weight" required>
                        <span class="px-3 py-2 bg-gray-200 text-gray-700 rounded-r-md border border-l-0 border-gray-300">
                            g
                        </span>
                    </div>


                    <textarea name="description" placeholder="Deskripsi" class="textarea textarea-bordered w-full h-32 resize-none"
                        x-model="$store.productForm.formData.description"></textarea>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-6">

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

                    <!-- Variasi Produk -->
                    <template x-for="(variant, index) in $store.productForm.formData.variants" :key="index">
                        <div class="border rounded-xl p-4 bg-gray-50 space-y-3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <!-- Nama & Harga -->
                                <input type="text" :name="`variants[${index}][name]`" x-model="variant.name"
                                    class="input input-bordered w-full" placeholder="Nama Varian" required>

                                <input type="number" :name="`variants[${index}][price]`" x-model="variant.price"
                                    class="input input-bordered w-full" placeholder="Harga Varian" required>
                                <input type="hidden" :name="`variants[${index}][variant_id]`" x-model="variant.id">
                            </div>
                            <!-- Pilih Gambar dari Media -->
                            <select class="select select-bordered w-full max-w-xs text-sm"
                                :name="`variants[${index}][media_id]`" x-model="variant.media_id">

                                <!-- Ini hanya ditampilkan saat belum ada pilihan -->
                                <option disabled value="">Pilih Gambar</option>

                                <!-- Tampilkan yang sedang aktif saat edit -->
                                <template x-if="$store.productForm.mode === 'edit' && variant.media_id">
                                    <option :value="variant.media_id">
                                        📌 Saat Ini:
                                        <span
                                            x-text="$store.productForm.mediaOptions.find(m => m.id == variant.media_id)?.name || 'Media tidak ditemukan'">
                                        </span>
                                    </option>
                                </template>

                                <!-- Filter media hanya gambar -->
                                <template
                                    x-for="media in $store.productForm.mediaOptions.filter(m => m.type.startsWith('image'))"
                                    :key="media.id">
                                    <option :value="media.id" x-text="media.name"></option>
                                </template>


                            </select>


                            <!-- Tombol Hapus -->
                            <div class="text-right">
                                <button type="button"
                                    @click="
                                        if (variant.id) {
                                            // Tambahkan hidden input 'deleted_variants[]'
                                            $refs.deletedVariants.insertAdjacentHTML(
                                                'beforeend', 
                                                `<input type='hidden' name='deleted_variants[]' value='${variant.id}'>`
                                            );
                                        }
                                        $store.productForm.formData.variants.splice(index, 1); // Hapus dari UI
                                    "
                                    class="btn btn-sm btn-gradient-error">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </template>

                    <!-- Tombol Tambah Varian -->
                    <button type="button"
                        @click="$store.productForm.formData.variants.push({ name: '', price: '', media_id: null ,variant_id : null})"
                        class="btn btn-sm btn-outline mt-2">+ Tambah Variasi</button>
                </div>
                <div x-ref="deletedVariants"></div>
                <!-- Tombol Aksi -->
                <div class="col-span-1 md:col-span-2 flex justify-end gap-2 pt-4 border-t mt-4">
                    <label for="modal_produk" class="btn  btn-gradient-neutral"
                        @click="$store.productForm.resetForm()">Batal</label>
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
                mediaOptions: [],
                formData: {
                    name: '',
                    variants: [],
                    stock: '',
                    categories: [],
                    description: '',
                    weight: '',
                },

                openAdd() {
                    this.mode = 'add';
                    this.formData = {
                        name: '',
                        variants: [],
                        stock: '',
                        categories: [],
                        description: '',
                        weight: '',
                    };

                    document.getElementById('modal_produk').checked = true;
                },

                openEdit(product) {
                    this.mode = 'edit';
                    this.formData = {
                        id: product.id,
                        name: product.name,
                        variants: product.variants || [],
                        stock: product.stock,
                        categories: product.categories?.map(c => c.id) || [],
                        description: product.description || '',
                        weight: product.weight || '',
                    };

                    this.formData.variants = (product.variants || []).map(variant => ({
                        ...variant,
                        media_id: variant.product_media_id
                    }));


                    this.mediaOptions = (product.media || []).map(media => ({
                        id: media.id,
                        name: media.original_name,
                        type: media.file_type,
                    }));

                    this.selected = {
                        ...product,
                        media: product.media || []
                    };

                    window.existingMedia = (product.media || []).map(item => ({
                        ...item
                    }));
                    window.deletedMediaIds = [];
                    window.selectedFiles = [];


                    syncMediaOptions();

                    renderFileList();
                    rebuildHiddenInputs();
                    document.getElementById('modal_produk').checked = true;
                },

                resetForm() {
                    this.mode = 'add';
                    this.mediaOptions = [];
                    this.formData = {
                        name: '',
                        variants: [],
                        stock: '',
                        categories: [],
                        description: '',
                        weight: '',
                    };
                }
            });
        });
    </script>

    <script>
        let selectedFiles = [];
        let removedMedia = [];
        window.existingMedia = [];
        window.deletedMediaIds = [];

        function addFiles(event) {
            const inputFiles = Array.from(event.target.files);
            selectedFiles = [...selectedFiles, ...inputFiles]; // simpan ke selectedFiles saja
            syncMediaOptions(); // ini akan rebuild mediaOptions dari existingMedia + selectedFiles
            renderFileList();
            rebuildHiddenInputs();
            event.target.value = '';
        }



        function removeFile(index) {
            selectedFiles.splice(index, 1);
            renderFileList();
            rebuildHiddenInputs();
            syncMediaOptions();
        }


        function removeExistingMedia(index) {
            const removed = window.existingMedia.splice(index, 1)[0]; // ambil & hapus
            if (removed && removed.id) {
                window.deletedMediaIds.push(removed.id); // simpan ID-nya
            }
            renderFileList();
            rebuildHiddenInputs();
            syncMediaOptions();
        }



        function renderFileList() {
            const listEl = document.getElementById('fileList');
            const countEl = document.getElementById('fileCount');
            listEl.innerHTML = '';

            const totalFiles = selectedFiles.length + existingMedia.length;
            countEl.textContent = `${totalFiles} file dipilih`;

            // 📁 Media lama
            existingMedia.forEach((item, index) => {
                const isVideo = item.file_type.startsWith('video');
                const li = document.createElement('li');
                li.className = 'relative border rounded-md shadow overflow-hidden group';

                const content = isVideo ?
                    `<img src="https://res.cloudinary.com/dpujlyn9x/video/upload/so_0/${item.file_path}.jpg" 
             alt="Thumbnail Video" 
             class="w-full h-24 object-cover" />` :
                    `<img src="https://res.cloudinary.com/dpujlyn9x/image/upload/${item.file_path}"
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
            hiddenInputsContainer.innerHTML = '';

            // Simpan semua media baru berdasarkan mediaOptions
            (Alpine.store('productForm').mediaOptions || []).forEach((media) => {
                if (!(media.file instanceof File)) return;
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(media.file);

                const input = document.createElement('input');
                input.type = 'file';
                input.name = `media[${media.id}]`;
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

        function syncMediaOptions() {
            const store = Alpine.store('productForm');
            const newOptions = [];

            // Media lama
            (window.existingMedia || []).forEach(item => {
                newOptions.push({
                    id: item.id,
                    name: item.original_name,
                    type: item.file_type?.startsWith('image') ? item.file_type : item.file_type || '',
                });
            });

            // Media baru
            (selectedFiles || []).forEach((file, i) => {
                newOptions.push({
                    id: file.lastModified + '_' + i,
                    name: file.name,
                    type: file.type || '',
                    file: file
                });
            });

            store.mediaOptions = [...newOptions];

            // Hapus media_id di variant kalau tidak valid
            const validIds = newOptions.map(m => m.id);
            store.formData.variants.forEach(v => {
                if (!validIds.includes(v.media_id)) {
                    v.media_id = null;
                }
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
                    <label for="delete_product" class="btn  btn-gradient-neutral">Batal</label>
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
    <!-- MODAL HAPUS PRODUK (PERMANEN) -->
<input type="checkbox" id="force_delete_product" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Hapus Permanen Produk</h3>
        <p class="py-4">Yakin ingin <strong>menghapus permanen</strong> produk <span id="forceProductName">Produk</span>?</p>
        <div class="modal-action">
            <form method="POST" id="forceDeleteProductForm">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-gradient-error">Ya, Hapus Permanen</button>
                <label for="force_delete_product" class="btn btn-gradient-neutral">Batal</label>
            </form>
        </div>
    </div>
</div>

<!-- MODAL RESTORE PRODUK -->
<input type="checkbox" id="restore_product" class="modal-toggle" />
<div class="modal">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Restore Produk</h3>
        <p class="py-4">Apakah ingin <strong>mengembalikan</strong> produk <span id="restoreProductName">Produk</span>?</p>
        <div class="modal-action">
            <form method="POST" id="restoreProductForm">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-gradient-warning">Ya, Restore</button>
                <label for="restore_product" class="btn btn-gradient-neutral">Batal</label>
            </form>
        </div>
    </div>
</div>

<script>
    // Hapus Permanen
    function forceDelete(id, name) {
        document.getElementById('forceProductName').textContent = name;
        const form = document.getElementById('forceDeleteProductForm');
        form.action = `{{ route('products.forceDelete', ':id') }}`.replace(':id', id);
        document.getElementById('force_delete_product').checked = true;
    }

    // Restore
    function restoreProduct(id, name) {
        document.getElementById('restoreProductName').textContent = name;
        const form = document.getElementById('restoreProductForm');
        form.action = `{{ route('products.restore', ':id') }}`.replace(':id', id);
        document.getElementById('restore_product').checked = true;
    }
</script>


    <script>
        function produkFilter(products, categories) {
            return {
                products: products,
                kategoriList: categories,
                selected: null,
                search: '',
                selectedCategories: [],
                get filteredProducts() {
                    return this.products.filter(p => {
                        const cocokNama = p.name.toLowerCase().includes(this.search.toLowerCase());

                        const kategoriProduk = (p.categories || []).map(c => c.name.toLowerCase());

                        const cocokKategori = this.selectedCategories.length === 0 ||
                            this.selectedCategories.every(kat =>
                                kategoriProduk.includes(kat.toLowerCase())
                            );

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
            <form action="{{ route('shops.update', $shop->id) }}" method="POST" class="space-y-4"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-4 text-center">
                    <img id="preview" src="{{ cloudinary_url($shop->img_path ?? 'productDefault_nawcx4') }}"
                        class="w-24 h-24 rounded-full mx-auto object-cover border-2 border-blue-300 mb-2" alt="Foto toko">
                    <input type="file" name="img_path" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-2 file:py-1 file:px-3 file:rounded file:border-0 file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200"
                        onchange="previewImage(event)">
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-sm text-gray-700">Video toko</label>
                    <input type="text" name="video_path" value="{{ old('video_path', $shop->video_path) }}"
                        class="input input-bordered w-full">
                </div>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-sm text-gray-700">Nama Toko</label>
                    <input type="text" name="name" value="{{ old('name', $shop->name) }}"
                        class="input input-bordered w-full" required>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-sm text-gray-700">Deskripsi</label>
                    <textarea name="description" rows="4" class="textarea textarea-bordered w-full" required>{{ old('description', $shop->description) }}</textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-sm text-gray-700">Kode Pos</label>
                    <input type="text" name="zipcode" value="{{ old('zipcode', $shop->zipcode) }}"
                        class="input input-bordered w-full" required>
                </div>

                <div class="modal-action">
                    <label for="modal_edit_toko" class="btn  btn-gradient-neutral">Batal</label>
                    <button type="submit" class="btn btn-gradient-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>
    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                const preview = document.getElementById('preview');
                preview.src = reader.result;
            };

            reader.readAsDataURL(input.files[0]);
        }
    </script>
@endsection
