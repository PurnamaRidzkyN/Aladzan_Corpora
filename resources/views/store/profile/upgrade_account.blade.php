@extends('layouts.app')
@section('title', 'Upgrade Account')

@section('content')
<div class="flex items-center justify-center px-4 md:px-0">
    <div class="bg-white shadow-xl rounded-2xl p-6 md:p-8 max-w-3xl w-full border border-blue-100">
        <h1 class="text-2xl md:text-3xl font-bold text-center text-blue-700 mb-4 md:mb-6">Tingkatkan Paket Anda</h1>
        <p class="text-center text-gray-600 mb-4 text-sm md:text-base">
            Akun Anda saat ini menggunakan paket <strong>{{ $user->plan ? $user->plan->name : 'Belum Ada' }}</strong>
        </p>

        <!-- Input Kode Diskon -->
        <div class="mb-6 text-center">
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                <input id="kodeDiskon" type="text" placeholder="Masukkan kode diskon..."
                    class="w-full sm:w-64 px-4 py-2 border border-blue-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="button" onclick="cekDiskon()"
                    class="w-full sm:w-auto px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 shadow-sm font-medium transition">
                    Terapkan
                </button>
            </div>
            <p id="pesanDiskon" class="mt-2 text-sm hidden"></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($plans as $plan)
                @php
                    $isCurrent = $user->plan && $user->plan->id === $plan->id;
                @endphp
                <form method="POST" action="{{ route('upgrade.account.payment') }}" class="{{ $isCurrent ? 'bg-gray-100 opacity-70' : 'bg-white hover:shadow-lg hover:scale-[1.02] cursor-pointer' }} border rounded-xl p-4 md:p-6 transition">
                    @csrf
                    <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                    <input type="hidden" name="discount_code" id="discount_code_{{ $plan->id }}" value="">
                    <input type="hidden" name="final_price" id="final_price_{{ $plan->id }}" value="{{ $plan->price }}">

                    <div class="flex justify-between items-center mb-2">
                        <h2 class="text-lg md:text-xl font-bold {{ $isCurrent ? 'text-gray-500' : 'text-blue-700' }}">
                            {{ $plan->name }} {{ $isCurrent ? '(Aktif)' : '' }}
                        </h2>
                        <div class="text-right">
                            <span id="harga{{ $plan->id }}" class="text-sm md:text-base {{ $isCurrent ? 'bg-gray-300 text-gray-600' : 'bg-blue-600 text-white' }} px-3 py-1 rounded-full">
                                Rp {{ number_format($plan->price,0,',','.') }}
                            </span>
                            <span id="diskonInfo{{ $plan->id }}" class="block text-green-600 text-xs font-medium mt-1 hidden"></span>
                        </div>
                    </div>

                    <ul class="space-y-2 text-gray-600 text-sm mb-4">
                        @foreach(explode("\n", $plan->description ?? '') as $line)
                            <li>🚀 {{ $line }}</li>
                        @endforeach
                    </ul>

                    @unless($isCurrent)
                        <button type="submit"
                            class="w-full py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                            Upgrade ke {{ $plan->name }}
                        </button>
                    @endunless
                </form>
            @endforeach
        </div>

        <p class="text-center text-gray-500 text-xs mt-6">
            Anda dapat mengubah paket kapan saja di pengaturan akun.
        </p>

        <div class="text-center mt-4">
            <a href="/" class="inline-block text-blue-600 hover:underline text-sm font-medium">Lanjutkan ke Beranda</a>
        </div>
    </div>
</div>

<script>
function cekDiskon() {
    const kode = document.getElementById('kodeDiskon').value;
    const pesan = document.getElementById('pesanDiskon');

    @foreach($plans as $plan)
        const harga{{ $plan->id }} = document.getElementById('harga{{ $plan->id }}');
        const diskonInfo{{ $plan->id }} = document.getElementById('diskonInfo{{ $plan->id }}');
        const discountInput{{ $plan->id }} = document.getElementById('discount_code_{{ $plan->id }}');
        const finalPriceInput{{ $plan->id }} = document.getElementById('final_price_{{ $plan->id }}');
        const hargaNormal{{ $plan->id }} = {{ $plan->price }};
    @endforeach

    fetch(`/check-discount/${encodeURIComponent(kode)}`)
        .then(res => res.json())
        .then(data => {
            console.log('Response discount:', data);
            pesan.classList.remove('hidden', 'text-green-600', 'text-red-600');

            if(data.valid) {
                pesan.textContent = data.message;
                pesan.classList.add('text-green-600');

                @foreach($plans as $plan)
                    const hargaAkhir{{ $plan->id }} = data.is_percent
                        ? hargaNormal{{ $plan->id }} - (hargaNormal{{ $plan->id }} * data.amount / 100)
                        : hargaNormal{{ $plan->id }} - data.amount;

                    const hemat{{ $plan->id }} = hargaNormal{{ $plan->id }} - hargaAkhir{{ $plan->id }};

                    harga{{ $plan->id }}.textContent = `Rp ${hargaAkhir{{ $plan->id }}.toLocaleString('id-ID')}`;
                    diskonInfo{{ $plan->id }}.classList.remove('hidden');
                    diskonInfo{{ $plan->id }}.textContent = data.is_percent 
                        ? `Diskon ${data.amount}% (Hemat Rp ${hemat{{ $plan->id }}.toLocaleString('id-ID')})`
                        : `Potongan langsung Rp ${hemat{{ $plan->id }}.toLocaleString('id-ID')}`;

                    discountInput{{ $plan->id }}.value = kode;
                    finalPriceInput{{ $plan->id }}.value = hargaAkhir{{ $plan->id }};
                @endforeach

            } else {
                pesan.textContent = data.message;
                pesan.classList.add('text-red-600');

                @foreach($plans as $plan)
                    harga{{ $plan->id }}.textContent = `Rp ${hargaNormal{{ $plan->id }}.toLocaleString('id-ID')}`;
                    diskonInfo{{ $plan->id }}.classList.add('hidden');
                    discountInput{{ $plan->id }}.value = '';
                    finalPriceInput{{ $plan->id }}.value = hargaNormal{{ $plan->id }};
                @endforeach
            }
        })
        .catch(() => {
            pesan.textContent = 'Terjadi kesalahan saat memeriksa kode diskon';
            pesan.classList.remove('hidden');
            pesan.classList.add('text-red-600');
        });
}
</script>
@endsection
