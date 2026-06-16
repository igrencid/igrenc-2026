@extends('frontend.layouts.app', ['title' => $item->name])

@section('content')
@php
    $isPromo = $item->is_promo
        && $item->promo_price
        && (! $item->promo_ends_at || $item->promo_ends_at->isFuture());
@endphp

<section class="mx-auto grid max-w-7xl gap-8 px-5 py-10 lg:grid-cols-2">
    <div class="glass rounded-[2rem] p-5">
        <div class="aspect-square overflow-hidden rounded-[1.5rem] bg-gradient-to-br from-purple-700 via-slate-900 to-cyan-700">
            @if($item->image)
                <img
                    src="{{ asset('storage/' . $item->image) }}"
                    alt="{{ $item->name }}"
                    class="h-full w-full object-cover"
                >
            @endif
        </div>
    </div>

    <div>
        <div class="text-sm text-purple-300">
            {{ $item->game?->name ?? 'Game' }}

            @if($item->category)
                • {{ $item->category->name }}
            @endif
        </div>

        <h1 class="mt-3 text-5xl font-black">
            {{ $item->name }}
        </h1>

        <div class="mt-5 flex flex-wrap gap-3">
            <div class="rounded-full border border-purple-500/30 bg-purple-500/10 px-4 py-2 text-sm font-bold uppercase text-purple-300">
                {{ $item->rarity ?: 'Normal' }}
            </div>

            @if($item->is_featured)
                <div class="rounded-full border border-yellow-500/30 bg-yellow-500/10 px-4 py-2 text-sm font-bold text-yellow-300">
                    Featured
                </div>
            @endif

            @if($isPromo)
                <div class="rounded-full border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm font-bold text-red-300">
                    Promo
                </div>
            @endif
        </div>

        <div class="mt-6">
            @if($isPromo)
                <div class="flex flex-wrap items-end gap-4">
                    <div class="text-4xl font-black text-cyan-300">
                        Rp {{ number_format($item->final_price, 0, ',', '.') }}
                    </div>

                    <div class="text-xl font-bold text-slate-500 line-through">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </div>
                </div>

                @if($item->promo_ends_at)
                    <div class="mt-2 text-sm text-yellow-300">
                        Promo berakhir {{ $item->promo_ends_at->format('d M Y H:i') }}
                    </div>
                @endif
            @else
                <div class="text-4xl font-black text-cyan-300">
                    Rp {{ number_format($item->price, 0, ',', '.') }}
                </div>
            @endif
        </div>

        <p class="mt-6 text-slate-300">
            {{ $item->description ?: 'Item game digital premium dengan proses transaksi cepat dan aman.' }}
        </p>

        <div class="mt-6 text-slate-400">
            Stok tersedia:
            <span class="font-bold {{ $item->stock > 0 ? 'text-green-300' : 'text-red-300' }}">
                {{ $item->stock }}
            </span>
        </div>

        <div class="mt-8">
            <div class="mb-4">
                <label class="mb-2 block text-sm font-bold text-slate-300">
                    Jumlah
                </label>

                <input
                    id="quantityInput"
                    type="number"
                    value="1"
                    min="1"
                    max="{{ $item->stock }}"
                    class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
                    @disabled(! $item->is_active || $item->stock < 1)
                >
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <form action="{{ route('cart.add', $item) }}" method="POST">
                    @csrf

                    <input
                        type="hidden"
                        name="quantity"
                        value="1"
                        data-quantity-target
                    >

                    <button
                        type="submit"
                        @disabled(! $item->is_active || $item->stock < 1)
                        class="w-full rounded-2xl bg-purple-600 px-6 py-4 text-lg font-black neon hover:bg-purple-500 disabled:cursor-not-allowed disabled:bg-slate-700 disabled:text-slate-400"
                    >
                        {{ $item->is_active && $item->stock > 0 ? 'Tambah Keranjang' : 'Item Tidak Tersedia' }}
                    </button>
                </form>

                <form action="{{ route('cart.buy-now', $item) }}" method="POST">
                    @csrf

                    <input
                        type="hidden"
                        name="quantity"
                        value="1"
                        data-quantity-target
                    >

                    <button
                        type="submit"
                        @disabled(! $item->is_active || $item->stock < 1)
                        class="w-full rounded-2xl bg-green-600 px-6 py-4 text-lg font-black text-white hover:bg-green-500 disabled:cursor-not-allowed disabled:bg-slate-700 disabled:text-slate-400"
                    >
                        Beli Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

@if($relatedItems->count())
<section class="mx-auto max-w-7xl px-5 py-10">
    <h2 class="text-3xl font-black">
        Item Terkait
    </h2>

    <div class="mt-6 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @foreach($relatedItems as $relatedItem)
            @include('frontend.items.partials.card', ['item' => $relatedItem])
        @endforeach
    </div>
</section>
@endif

<script>
    const quantityInput = document.getElementById('quantityInput');

    const quantityTargets = document.querySelectorAll('[data-quantity-target]');

    function syncQuantity() {
        quantityTargets.forEach((target) => {
            target.value = quantityInput.value || 1;
        });
    }

    syncQuantity();

    quantityInput?.addEventListener('input', syncQuantity);
</script>
@endsection