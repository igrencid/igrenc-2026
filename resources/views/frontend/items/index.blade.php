@extends('frontend.layouts.app', ['title' => 'Katalog Item'])

@section('content')
<section class="relative mx-auto max-w-7xl px-5 py-12">
    <div class="mb-10 text-center">
        <h1 class="text-5xl font-black">
            Marketplace <span class="gradient-text">Item Game</span>
        </h1>

        <p class="mt-4 text-lg text-slate-400">
            Temukan item favorit, promo terbaru, dan penawaran eksklusif dengan harga terbaik
        </p>
    </div>

    <form method="GET" action="{{ route('items.index') }}" class="glass relative z-40 rounded-3xl p-6">
        <div class="grid gap-4 md:grid-cols-4">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari item game..."
                class="w-full rounded-2xl border border-purple-500/20 bg-[#0b1020]/90 px-5 py-4 text-white placeholder:text-slate-500 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
            >

            <div x-data="{ open: false }" class="relative z-50">
                <input type="hidden" name="game" id="gameInput" value="{{ request('game') }}">

                <button
                    type="button"
                    @click="open = !open"
                    class="flex w-full items-center justify-between rounded-2xl border border-purple-500/40 bg-[#0b1020]/90 px-5 py-4 text-left font-semibold text-white outline-none transition hover:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
                >
                    <span id="selectedGame">
                        @if(request('game'))
                            {{ optional($games->firstWhere('slug', request('game')))->name ?? 'Semua Game' }}
                        @else
                            Semua Game
                        @endif
                    </span>

                    <span class="text-slate-400">▾</span>
                </button>

                <div
                    x-show="open"
                    @click.outside="open = false"
                    x-transition
                    class="absolute left-0 top-full z-[9999] mt-3 w-full overflow-hidden rounded-2xl border border-purple-500/30 bg-[#0b1020]/95 shadow-2xl backdrop-blur-xl"
                    style="display: none;"
                >
                    <button
                        type="button"
                        onclick="
                            document.getElementById('gameInput').value = '';
                            document.getElementById('selectedGame').innerText = 'Semua Game';
                        "
                        @click="open = false"
                        class="w-full px-5 py-4 text-left font-semibold text-white transition hover:bg-purple-600/70"
                    >
                        Semua Game
                    </button>

                    @foreach($games as $game)
                        <button
                            type="button"
                            onclick="
                                document.getElementById('gameInput').value = '{{ $game->slug }}';
                                document.getElementById('selectedGame').innerText = '{{ $game->name }}';
                            "
                            @click="open = false"
                            class="w-full px-5 py-4 text-left font-semibold text-white transition hover:bg-purple-600/70"
                        >
                            {{ $game->name }}
                        </button>
                    @endforeach
                </div>
            </div>

            <button
                type="submit"
                class="rounded-2xl bg-purple-600 px-5 py-4 font-black transition hover:bg-purple-500"
            >
                Cari
            </button>

            <a
                href="{{ route('items.index') }}"
                class="rounded-2xl border border-white/10 px-5 py-4 text-center font-black transition hover:bg-white/10"
            >
                Reset
            </a>
        </div>
    </form>

    <div class="relative z-10 mt-8 flex flex-wrap items-center justify-between gap-4 text-sm">
        <div class="text-slate-400">
            Menampilkan
            <span class="font-black text-white">{{ $items->count() }}</span>
            dari
            <span class="font-black text-white">{{ $items->total() }}</span>
            item.
        </div>

        @if(request()->filled('search') || request()->filled('game'))
            <div class="rounded-full bg-purple-500/10 px-4 py-2 font-bold text-purple-300">
                Filter Aktif
            </div>
        @endif
    </div>

    <div class="relative z-10 mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($items as $item)
            @include('frontend.items.partials.card', ['item' => $item])
        @empty
            <div class="glass col-span-full rounded-3xl p-10 text-center">
                <h3 class="text-3xl font-black">Item Tidak Ditemukan</h3>
                <p class="mt-3 text-slate-400">Coba gunakan kata kunci lain atau reset filter</p>
            </div>
        @endforelse
    </div>

    @if($items->hasPages())
        <div class="relative z-10 mt-12">
            {{ $items->links() }}
        </div>
    @endif
</section>
@endsection