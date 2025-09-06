@extends('layouts.dashboard')
@section('title', 'Pengaturan Kontak & Pembayaran')
@php
    $title = 'Pengaturan';
    $breadcrumb = [['label' => 'Manajemen'], ['label' => 'Pengaturan']];
@endphp

@section('content')
    <section class="w-full lg:px-12 mt-8">
        <div class="card bg-white shadow-md rounded-xl border border-soft">
            <div class="card-body">
                <h2 class="text-2xl font-bold text-primary mb-6">Kontak & Pembayaran</h2>

                <form action="{{ route('settings.store') }}" method="POST" class="space-y-8">
                    @csrf

                    <!-- WhatsApp -->
                    <div>
                        <label class="font-semibold text-gray-700 mb-2 block">Nomor WhatsApp </label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp', $whatsapp) }}"
                            class="input input-bordered w-full" placeholder="6281234567890">
                    </div>

                    <!-- Bank Accounts -->
                    <div x-data="bankHandler()">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold text-gray-700">Rekening Bank</h3>
                            <button type="button" @click="addBank" class="btn btn-sm btn-gradient-primary">+
                                Tambah</button>
                        </div>
                        <table class="table w-full text-sm">
                            <thead>
                                <tr>
                                    <th>Nama Bank</th>
                                    <th>Nomor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(bank,index) in banks" :key="index">
                                    <tr>
                                        <td><input type="text" :name="'bank_accounts[' + index + '][name]'"
                                                x-model="bank.name" class="input input-bordered w-full"></td>
                                        <td><input type="text" :name="'bank_accounts[' + index + '][number]'"
                                                x-model="bank.number" class="input input-bordered w-full"></td>
                                        <td><button type="button" @click="removeBank(index)"
                                                class="btn btn-gradient-error btn-xs">Hapus</button></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Ewallets -->
                    <div x-data="ewalletHandler()">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold text-gray-700">E-Wallet</h3>
                            <button type="button" @click="addEwallet" class="btn btn-sm btn-gradient-primary">+
                                Tambah</button>
                        </div>
                        <table class="table w-full text-sm">
                            <thead>
                                <tr>
                                    <th>Provider</th>
                                    <th>Nomor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(wallet,index) in wallets" :key="index">
                                    <tr>
                                        <td><input type="text" :name="'ewallets[' + index + '][provider]'"
                                                x-model="wallet.provider" class="input input-bordered w-full"></td>
                                        <td><input type="text" :name="'ewallets[' + index + '][number]'"
                                                x-model="wallet.number" class="input input-bordered w-full"></td>
                                        <td><button type="button" @click="removeEwallet(index)"
                                                class="btn btn-gradient-error btn-xs">Hapus</button></td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>

                    <!-- Resi Sources -->
                    <div x-data="resiSourceHandler()">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="font-semibold text-gray-700">Asal Resi</h3>
                            <button type="button" @click="addSource" class="btn btn-sm btn-gradient-primary">+
                                Tambah</button>
                        </div>
                        <table class="table w-full text-sm">
                            <thead>
                                <tr>
                                    <th>Nama Kurir / Provider</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="(source,index) in sources" :key="index">
                                    <tr>
                                        <td>
                                            <input type="hidden" :name="'resi_sources['+index+'][id]'" x-model="source.id">
                                            <input type="text" :name="'resi_sources['+index+'][name]'" x-model="source.name" class="input input-bordered w-full">
                                        </td>
                                        <td>
                                            <button type="button" @click="removeSource(index)" class="btn btn-gradient-error btn-xs">Hapus</button>
                                        </td>
                                    </tr>
                                </template>

                            </tbody>
                        </table>
                    </div>



                    <div class="text-right">
                        <button type="submit" class="btn btn-gradient-success">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function bankHandler() {
            return {
                banks: @json($bankAccounts),
                addBank() {
                    this.banks.push({
                        name: '',
                        number: ''
                    });
                },
                removeBank(index) {
                    this.banks.splice(index, 1);
                }
            }
        }

        function ewalletHandler() {
            return {
                wallets: @json($ewallets),
                addEwallet() {
                    this.wallets.push({
                        provider: '',
                        number: ''
                    });
                },
                removeEwallet(index) {
                    this.wallets.splice(index, 1);
                }
            }
        }
    </script>
    <script>
        function resiSourceHandler() {
            return {

                sources: @json(old('resi_sources', $resiSources)),

                addSource() {
                    this.sources.push({
                        name: ''
                    });
                },

                removeSource(index) {
                    this.sources.splice(index, 1);
                }
            }
        }
    </script>


@endsection
