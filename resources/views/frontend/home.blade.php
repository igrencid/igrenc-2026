@extends('frontend.layouts.app', ['title' => 'GameVault Market'])

@section('content')
<section class="mx-auto grid max-w-7xl items-center gap-10 px-5 py-16 lg:grid-cols-2">
    <div>
        <div class="mb-5 inline-flex rounded-full border border-purple-500/30 bg-purple-500/10 px-4 py-2 text-sm font-bold text-purple-300">
            Marketplace Item Game Digital
        </div>

        <h1 class="text-5xl font-black leading-tight md:text-7xl">
            Temukan <span class="gradient-text">Item Game</span><br>
            Terbaik & Teraman
        </h1>

        <p class="mt-5 max-w-xl text-lg text-slate-300">
            Pilih game favoritmu, cari item dari database admin, masukkan ke keranjang, lalu checkout dengan mudah.
        </p>

        <div class="mt-8 flex flex-wrap gap-4">
            <a href="#games" class="rounded-2xl bg-purple-600 px-6 py-4 font-bold neon hover:bg-purple-500">
                Pilih Game
            </a>

            <a href="{{ route('items.index') }}" class="rounded-2xl border border-white/10 px-6 py-4 font-bold hover:bg-white/10">
                Lihat Semua Item
            </a>

            <a href="{{ route('cart.index') }}" class="rounded-2xl border border-purple-500/30 px-6 py-4 font-bold text-purple-300 hover:bg-purple-500/10">
                Keranjang
            </a>
        </div>

        <div class="mt-10 grid grid-cols-3 gap-4">
            <div class="glass rounded-2xl p-4">
                <div class="text-2xl font-black">{{ $games->count() }}</div>
                <div class="text-sm text-slate-400">Game Aktif</div>
            </div>

            <div class="glass rounded-2xl p-4">
                <div class="text-2xl font-black">{{ $latestItems->count() }}</div>
                <div class="text-sm text-slate-400">Item Terbaru</div>
            </div>

            <div class="glass rounded-2xl p-4">
                <div class="text-2xl font-black">{{ $featuredItems->count() }}</div>
                <div class="text-sm text-slate-400">Featured</div>
            </div>
        </div>
    </div>

    <div
        x-data="{
            index: 0,
            items: @js($featuredItems->values()),
            init() {
                if (this.items.length > 1) {
                    setInterval(() => {
                        this.index = (this.index + 1) % this.items.length;
                    }, 4000);
                }
            }
        }"
        class="glass neon overflow-hidden rounded-[2rem] p-6"
    >
        <template x-if="items.length > 0">
            <div class="relative h-[520px] overflow-hidden rounded-[1.5rem] bg-gradient-to-br from-purple-700 via-indigo-900 to-cyan-900">
                <img
                    :src="items[index].image ? '/storage/' + items[index].image : 'https://placehold.co/800x600/111827/ffffff?text=GameVault'"
                    class="absolute inset-0 h-full w-full scale-105 object-cover opacity-80 transition-all duration-700"
                    alt="Featured Item"
                >

                <div class="absolute inset-0 bg-gradient-to-t from-black via-black/35 to-transparent"></div>

                <div class="absolute right-4 top-4">
                    <span class="rounded-full bg-purple-600 px-4 py-2 text-xs font-bold">
                        FEATURED
                    </span>
                </div>

                <div class="absolute inset-x-0 bottom-0">
                    <div class="bg-gradient-to-t from-black via-black/80 to-transparent p-8">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <div class="text-sm font-semibold text-purple-300">
                                    Featured Item
                                </div>

                                <h2 class="mt-1 text-4xl font-black text-white" x-text="items[index].name"></h2>

                                <div class="mt-1 text-sm text-slate-300">
                                    <span x-text="items[index].game?.name ?? '-'"></span>
                                    <span> • </span>
                                    <span x-text="items[index].category?.name ?? '-'"></span>
                                </div>

                                <div class="mt-2">
                                    <template x-if="items[index].is_promo && items[index].promo_price">
                                        <div>
                                            <div class="text-sm text-red-400 line-through"
                                                x-text="'Rp ' + Number(items[index].price).toLocaleString('id-ID')">
                                            </div>
                                            <div class="text-2xl font-black text-green-400"
                                                x-text="'Rp ' + Number(items[index].promo_price).toLocaleString('id-ID')">
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="!(items[index].is_promo && items[index].promo_price)">
                                        <div class="text-2xl font-black text-cyan-300"
                                            x-text="'Rp ' + Number(items[index].price).toLocaleString('id-ID')">
                                        </div>
                                    </template>
                                </div>
                            </div>

                            <a
                                :href="'/items/' + items[index].slug"
                                class="shrink-0 rounded-xl bg-purple-600 px-5 py-3 font-bold hover:bg-purple-500"
                            >
                                Lihat Detail
                            </a>
                        </div>

                        <div class="mt-5 flex gap-2">
                            <template x-for="(item, i) in items" :key="i">
                                <button
                                    type="button"
                                    @click="index = i"
                                    class="h-2 rounded-full transition-all duration-500"
                                    :class="i === index ? 'w-12 bg-purple-500' : 'w-2 bg-white/30'"
                                ></button>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <template x-if="items.length === 0">
            <div class="flex h-[520px] items-center justify-center rounded-[1.5rem] bg-gradient-to-br from-purple-700 via-indigo-900 to-cyan-900">
                <div class="text-center">
                    <div class="text-2xl font-black">Belum Ada Featured Item</div>
                    <div class="mt-2 text-slate-300">Aktifkan Featured dari Admin Panel</div>
                </div>
            </div>
        </template>
    </div>
</section>

<section id="games" class="mx-auto max-w-7xl px-5 py-10">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black">Popular Games</h2>
            <p class="mt-2 text-slate-400">Pilih game favoritmu lalu cari item yang tersedia.</p>
        </div>

        <a href="{{ route('items.index') }}" class="text-purple-300 hover:text-purple-200">
            Lihat semua
        </a>
    </div>

    <div class="grid gap-5 md:grid-cols-3 lg:grid-cols-6">
        @forelse($games as $game)
            <a href="{{ route('items.index', ['game' => $game->slug]) }}" class="glass card-hover rounded-2xl p-4">
                <div class="h-28 overflow-hidden rounded-xl bg-gradient-to-br from-purple-700 to-cyan-700">
                    @if($game->image)
                        <img src="{{ asset('storage/' . $game->image) }}" alt="{{ $game->name }}" class="h-full w-full object-cover">
                    @endif
                </div>

                <div class="mt-4 font-bold">{{ $game->name }}</div>
                <div class="text-sm text-slate-400">{{ $game->items_count ?? 0 }} item</div>
            </a>
        @empty
            <div class="glass col-span-full rounded-2xl p-8 text-center text-slate-400">
                Belum ada game aktif. Tambahkan data dari admin panel.
            </div>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-10">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black">Featured Items</h2>
            <p class="mt-2 text-slate-400">Item pilihan yang diaktifkan dari admin panel.</p>
        </div>

        <a href="{{ route('items.index') }}" class="text-purple-300 hover:text-purple-200">
            Lihat semua
        </a>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($featuredItems as $item)
            @include('frontend.items.partials.card', ['item' => $item])
        @empty
            <div class="glass col-span-full rounded-2xl p-8 text-center text-slate-400">
                Belum ada featured item. Aktifkan field featured dari admin.
            </div>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-10">
    <div class="mb-6 flex items-center justify-between gap-4">
        <div>
            <h2 class="text-3xl font-black">Latest Items</h2>
            <p class="mt-2 text-slate-400">Item terbaru yang tersedia di database.</p>
        </div>

        <a href="{{ route('items.index') }}" class="text-purple-300 hover:text-purple-200">
            Lihat semua
        </a>
    </div>

    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($latestItems as $item)
            @include('frontend.items.partials.card', ['item' => $item])
        @empty
            <div class="glass col-span-full rounded-2xl p-8 text-center text-slate-400">
                Belum ada item aktif. Tambahkan item dari admin panel.
            </div>
        @endforelse
    </div>
</section>

<section class="mx-auto max-w-7xl px-5 py-10">
    <div class="glass overflow-hidden rounded-[2rem] border border-red-500/20 p-8">
        <div class="flex flex-col items-start justify-between gap-6 md:flex-row md:items-center">
            <div>
                <div class="mb-3 inline-flex rounded-full border border-red-500/30 bg-red-500/10 px-4 py-2 text-sm font-bold text-red-300">
                    Flash Sale
                </div>

                <h2 class="text-4xl font-black">Promo Item Game Hari Ini</h2>

                <p class="mt-3 max-w-xl text-slate-400">
                    Item yang sedang mendapatkan promo khusus dari admin.
                </p>
            </div>

            <a href="{{ route('items.index') }}" class="rounded-2xl bg-red-600 px-5 py-3 font-bold hover:bg-red-500">
                Cek Promo
            </a>
        </div>

        <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($promoItems as $item)
                @include('frontend.items.partials.card', ['item' => $item])
            @empty
                <div class="glass col-span-full rounded-2xl p-8 text-center text-slate-400">
                    Belum ada item promo.
                </div>
            @endforelse
        </div>
    </div>
</section>

<section id="how" class="mx-auto max-w-7xl px-5 py-16">
    <div class="glass rounded-[2rem] p-8">
        <h2 class="text-center text-3xl font-black">Cara Kerja</h2>

        <p class="mt-2 text-center text-slate-400">
            Flow transaksi: pilih game, pilih item, masukkan cart, checkout, lalu upload bukti pembayaran.
        </p>

        <div class="mt-8 grid gap-5 md:grid-cols-5">
            @foreach([
                ['title' => 'Pilih Game', 'text' => 'Cari game dari data admin.'],
                ['title' => 'Pilih Item', 'text' => 'Lihat item aktif dan stok tersedia.'],
                ['title' => 'Masuk Cart', 'text' => 'Tambahkan item ke keranjang.'],
                ['title' => 'Checkout', 'text' => 'Isi data pembeli dan buat order.'],
                ['title' => 'Upload Bukti', 'text' => 'Upload bukti pembayaran.'],
            ] as $step)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5 text-center">
                    <div class="mx-auto mb-4 flex h-12 w-12 items-center justify-center rounded-full bg-purple-600 font-black">
                        {{ $loop->iteration }}
                    </div>

                    <div class="font-bold">{{ $step['title'] }}</div>

                    <p class="mt-2 text-sm text-slate-400">
                        {{ $step['text'] }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endsection