<div class="glass card-hover overflow-hidden rounded-2xl">
    <a href="{{ route('items.show', $item->slug) }}">
        <div class="h-48 bg-gradient-to-br from-purple-700 via-slate-900 to-cyan-700">
            @if($item->image)
                <img
                    src="{{ asset('storage/' . $item->image) }}"
                    alt="{{ $item->name }}"
                    class="h-full w-full object-cover"
                >
            @endif
        </div>
    </a>

    <div class="p-5">
        <div class="mb-2 flex items-center justify-between">
            <span class="text-xs font-bold uppercase text-purple-300">
                {{ $item->rarity ?: 'Normal' }}
            </span>

            @if($item->is_featured)
                <span class="rounded-full bg-yellow-500/10 px-2 py-1 text-xs font-bold text-yellow-300">
                    Featured
                </span>
            @endif
        </div>

        <a href="{{ route('items.show', $item->slug) }}">
            <h3 class="mt-1 text-lg font-black hover:text-purple-300">
                {{ $item->name }}
            </h3>
        </a>

        <div class="mt-1 text-sm text-slate-400">
            {{ $item->game?->name }}
            @if($item->category)
                • {{ $item->category->name }}
            @endif
        </div>

        <div class="mt-4 flex items-center justify-between">
            <div>
                <div class="text-xl font-black text-cyan-300">
                    Rp {{ number_format($item->price, 0, ',', '.') }}
                </div>
            </div>

            <div class="text-right">
                <div class="text-xs text-slate-500">
                    Stok
                </div>

                <div class="font-bold {{ $item->stock > 0 ? 'text-green-400' : 'text-red-400' }}">
                    {{ $item->stock }}
                </div>
            </div>
        </div>

        <form action="{{ route('cart.add', $item) }}" method="POST" class="mt-4">
            @csrf

            <input type="hidden" name="quantity" value="1">

            <button
                type="submit"
                @disabled(!$item->is_active || $item->stock < 1)
                class="w-full rounded-xl bg-purple-600 px-4 py-3 font-bold hover:bg-purple-500 disabled:bg-slate-700 disabled:text-slate-400 disabled:cursor-not-allowed"
            >
                {{ $item->stock > 0 && $item->is_active ? 'Tambah ke Cart' : 'Stok Habis' }}
            </button>
        </form>
    </div>
</div>