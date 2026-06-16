@php
    $isPromo = $item->is_promo
        && $item->promo_price
        && (! $item->promo_ends_at || $item->promo_ends_at->isFuture());

    $discount = $isPromo && $item->price > 0
        ? round((($item->price - $item->promo_price) / $item->price) * 100)
        : 0;
@endphp

<div class="glass card-hover overflow-hidden rounded-2xl border border-white/10">
    <a href="{{ route('items.show', $item->slug) }}" class="relative block">
        @if($isPromo)
            <div class="absolute left-3 top-3 z-20">
                <span class="rounded-full bg-red-500 px-3 py-1 text-xs font-bold text-white shadow-lg">
                    FLASH SALE
                </span>
            </div>
        @endif

        @if($item->is_featured)
            <div class="absolute right-3 top-3 z-20">
                <span class="rounded-full bg-yellow-500 px-3 py-1 text-xs font-bold text-black shadow-lg">
                    FAVORIT
                </span>
            </div>
        @endif

        <div class="h-52 overflow-hidden bg-gradient-to-br from-purple-700 via-slate-900 to-cyan-700">
            @if($item->image)
                <img
                    src="{{ asset('storage/' . $item->image) }}"
                    alt="{{ $item->name }}"
                    class="h-full w-full object-cover transition duration-500 hover:scale-110"
                >
            @endif
        </div>
    </a>

    <div class="p-5">
        <div class="mb-2 flex items-center justify-between">
            <span class="rounded-full bg-purple-500/10 px-3 py-1 text-xs font-bold text-purple-300">
                {{ $item->rarity ?: 'Normal' }}
            </span>

            @if($item->stock > 0)
                <span class="text-xs font-bold text-green-400">
                    {{ $item->stock }} Stock
                </span>
            @else
                <span class="text-xs font-bold text-red-400">
                    Sold Out
                </span>
            @endif
        </div>

        <a href="{{ route('items.show', $item->slug) }}">
            <h3 class="line-clamp-2 text-lg font-black hover:text-purple-300">
                {{ $item->name }}
            </h3>
        </a>

        <div class="mt-1 text-sm text-slate-400">
            {{ $item->game?->name }}

            @if($item->category)
                • {{ $item->category->name }}
            @endif
        </div>

        <div class="mt-4">
            @if($isPromo)
                <div class="flex items-center gap-2">
                    <span class="rounded bg-red-500 px-2 py-1 text-xs font-bold text-white">
                        -{{ $discount }}%
                    </span>

                    <span class="text-sm text-slate-500 line-through">
                        Rp {{ number_format($item->price, 0, ',', '.') }}
                    </span>
                </div>

                <div class="mt-1 text-2xl font-black text-green-400">
                    Rp {{ number_format($item->promo_price, 0, ',', '.') }}
                </div>

                @if($item->promo_ends_at)
                    <div
                        class="mt-3 rounded-xl border border-red-500/20 bg-red-500/10 px-3 py-2 text-xs font-bold text-red-300"
                        data-countdown="{{ $item->promo_ends_at->toIso8601String() }}"
                    >
                        Flash sale berakhir dalam:
                        <span>Loading...</span>
                    </div>
                @endif
            @else
                <div class="text-2xl font-black text-cyan-300">
                    Rp {{ number_format($item->price, 0, ',', '.') }}
                </div>
            @endif
        </div>

        <form
            action="{{ route('cart.add', $item) }}"
            method="POST"
            class="mt-5"
        >
            @csrf

            <input
                type="hidden"
                name="quantity"
                value="1"
            >

            <button
                type="submit"
                @disabled(!$item->is_active || $item->stock < 1)
                class="w-full rounded-xl bg-purple-600 px-4 py-3 font-bold transition hover:bg-purple-500 disabled:cursor-not-allowed disabled:bg-slate-700 disabled:text-slate-400"
            >
                {{ $item->stock > 0 && $item->is_active ? 'Tambah ke Keranjang' : 'Stok Habis' }}
            </button>
        </form>
    </div>
</div>

@once
    <script>
        function initPromoCountdowns() {
            document.querySelectorAll('[data-countdown]').forEach((element) => {
                if (element.dataset.initialized === 'true') {
                    return;
                }

                element.dataset.initialized = 'true';

                const targetDate = new Date(element.dataset.countdown).getTime();
                const output = element.querySelector('span');

                function updateCountdown() {
                    const now = new Date().getTime();
                    const distance = targetDate - now;

                    if (distance <= 0) {
                        output.textContent = 'Promo berakhir';
                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor((distance / (1000 * 60 * 60)) % 24);
                    const minutes = Math.floor((distance / (1000 * 60)) % 60);
                    const seconds = Math.floor((distance / 1000) % 60);

                    output.textContent = `${days}h ${hours}j ${minutes}m ${seconds}d`;
                }

                updateCountdown();

                setInterval(updateCountdown, 1000);
            });
        }

        document.addEventListener('DOMContentLoaded', initPromoCountdowns);
    </script>
@endonce