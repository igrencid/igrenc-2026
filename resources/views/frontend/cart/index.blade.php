@extends('frontend.layouts.app', ['title' => 'Keranjang'])

@section('content')
<section class="mx-auto max-w-7xl px-5 py-12">
    <div class="mb-10 text-center">
        <h1 class="text-5xl font-black">
            Keranjang <span class="gradient-text">Belanja</span>
        </h1>

        <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-400">
            Periksa kembali item sebelum lanjut ke checkout
        </p>
    </div>

    @if(count($cart))
        <div class="grid gap-8 lg:grid-cols-3">
            <div class="space-y-5 lg:col-span-2">
                @foreach($cart as $row)
                    <div class="glass flex flex-col gap-5 rounded-3xl p-5 md:flex-row md:items-center">
                        <div class="h-28 w-full overflow-hidden rounded-2xl bg-gradient-to-br from-purple-700 via-slate-900 to-cyan-700 md:w-32">
                            @if(!empty($row['image']))
                                <img
                                    src="{{ asset('storage/' . $row['image']) }}"
                                    alt="{{ $row['name'] }}"
                                    class="h-full w-full object-cover"
                                >
                            @endif
                        </div>

                        <div class="flex-1">
                            <h2 class="text-2xl font-black">
                                {{ $row['name'] }}
                            </h2>

                            <p class="mt-1 text-sm text-slate-400">
                                {{ $row['game'] ?? 'Game' }}
                                @if(!empty($row['category']))
                                    • {{ $row['category'] }}
                                @endif
                            </p>

                            <div class="mt-3 font-black text-cyan-300">
                                Rp {{ number_format($row['price'], 0, ',', '.') }}
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-3">
                            <form action="{{ route('cart.update', $row['id']) }}" method="POST" class="flex gap-2">
                                @csrf

                                <input
                                    type="number"
                                    name="quantity"
                                    value="{{ $row['quantity'] }}"
                                    min="1"
                                    max="{{ $row['stock'] ?? 99 }}"
                                    class="w-20 rounded-xl border border-white/10 bg-black/30 px-3 py-3 text-center font-bold text-white outline-none"
                                >

                                <button class="rounded-xl bg-white/10 px-4 py-3 font-bold hover:bg-white/20">
                                    Update
                                </button>
                            </form>

                            <form action="{{ route('cart.remove', $row['id']) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="rounded-xl bg-red-600 px-4 py-3 font-bold hover:bg-red-500">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="glass h-fit rounded-3xl p-6">
                <h2 class="text-3xl font-black">
                    Ringkasan
                </h2>

                <div class="mt-6 space-y-4 text-slate-300">
                    <div class="flex justify-between">
                        <span>Total Item</span>
                        <span class="font-bold text-white">
                            {{ collect($cart)->sum('quantity') }}
                        </span>
                    </div>

                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-bold text-cyan-300">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <div class="mt-6 border-t border-white/10 pt-6">
                    <div class="flex items-center justify-between text-2xl font-black">
                        <span>Total</span>
                        <span class="text-cyan-300">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>
                </div>

                <a
                    href="{{ route('checkout.index') }}"
                    class="neon mt-8 block rounded-2xl bg-purple-600 px-6 py-4 text-center text-lg font-black hover:bg-purple-500"
                >
                    Lanjut Checkout
                </a>

                <a
                    href="{{ route('items.index') }}"
                    class="mt-4 block rounded-2xl border border-white/10 px-6 py-4 text-center font-bold hover:bg-white/10"
                >
                    Belanja Lagi
                </a>
            </div>
        </div>
    @else
        <div class="mx-auto max-w-3xl">
            <div class="glass rounded-[2rem] p-12 text-center">
                <h2 class="text-4xl font-black">
                    Keranjang Kosong
                </h2>

                <p class="mx-auto mt-4 max-w-xl text-slate-400">
                    Belum ada item yang masuk ke keranjang. Pilih item game favoritmu terlebih dahulu
                </p>

                <a
                    href="{{ route('items.index') }}"
                    class="neon mt-8 inline-flex rounded-2xl bg-purple-600 px-8 py-4 font-black hover:bg-purple-500"
                >
                    Lihat Semua Item
                </a>
            </div>
        </div>
    @endif
</section>
@endsection