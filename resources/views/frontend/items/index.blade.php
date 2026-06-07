@extends('frontend.layouts.app', ['title' => 'Katalog Item'])

@section('content')
<section class="mx-auto max-w-7xl px-5 py-12">
    <div class="mb-8">
        <h1 class="text-4xl font-black">
            Katalog <span class="gradient-text">Item Game</span>
        </h1>

        <p class="mt-2 text-slate-400">
            Temukan item game terbaik dengan harga kompetitif.
        </p>
    </div>

    <form method="GET" action="{{ route('items.index') }}" class="glass rounded-2xl p-5">
        <div class="grid gap-4 md:grid-cols-5">
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari item..."
                class="rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
            >

            <select name="game" class="rounded-xl border border-white/10 bg-black/30 px-4 py-3">
                <option value="">Semua Game</option>
                @foreach($games as $game)
                    <option value="{{ $game->slug }}" @selected(request('game') === $game->slug)>
                        {{ $game->name }}
                    </option>
                @endforeach
            </select>

            <select name="category" class="rounded-xl border border-white/10 bg-black/30 px-4 py-3">
                <option value="">Semua Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="rounded-xl bg-purple-600 px-4 py-3 font-bold hover:bg-purple-500">
                Filter
            </button>

            <a href="{{ route('items.index') }}" class="rounded-xl border border-white/10 px-4 py-3 text-center font-bold hover:bg-white/10">
                Reset
            </a>
        </div>
    </form>

    <div class="mt-6 flex items-center justify-between text-sm text-slate-400">
        <div>
            Menampilkan <b class="text-white">{{ $items->count() }}</b>
            dari <b class="text-white">{{ $items->total() }}</b> item
        </div>

        @if(request()->filled('search') || request()->filled('game') || request()->filled('category'))
            <div class="rounded-full bg-purple-500/10 px-4 py-2 text-purple-300">
                Filter Aktif
            </div>
        @endif
    </div>

    <div class="mt-8 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
        @forelse($items as $item)
            @include('frontend.items.partials.card', ['item' => $item])
        @empty
            <div class="glass col-span-full rounded-2xl p-10 text-center">
                <h3 class="text-2xl font-black">Item Tidak Ditemukan</h3>
                <p class="mt-2 text-slate-400">Coba reset filter atau tambah item aktif dari admin.</p>
            </div>
        @endforelse
    </div>

    @if($items->hasPages())
        <div class="mt-10">
            {{ $items->links() }}
        </div>
    @endif
</section>
@endsection