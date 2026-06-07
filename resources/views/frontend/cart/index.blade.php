@extends('frontend.layouts.app', ['title' => 'Keranjang'])

@section('content')
<section class="mx-auto max-w-5xl px-5 py-10">
    <h1 class="text-4xl font-black">Keranjang</h1>

    <div class="mt-8 space-y-4">
        @forelse($cart as $row)
            <div class="glass flex flex-col justify-between gap-4 rounded-2xl p-5 md:flex-row md:items-center">
                <div>
                    <div class="text-xl font-black">{{ $row['name'] }}</div>
                    <div class="text-sm text-slate-400">{{ $row['game'] }}</div>
                    <div class="mt-2 font-bold text-cyan-300">
                        Rp {{ number_format($row['price'], 0, ',', '.') }}
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <form action="{{ route('cart.update', $row['id']) }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="number" name="quantity" value="{{ $row['quantity'] }}" min="1" class="w-20 rounded-xl bg-black/30 px-3 py-2">
                        <button class="rounded-xl bg-white/10 px-4 py-2">Update</button>
                    </form>

                    <form action="{{ route('cart.remove', $row['id']) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="rounded-xl bg-red-600 px-4 py-2">Hapus</button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass rounded-2xl p-8 text-slate-400">Keranjang kosong.</div>
        @endforelse
    </div>

    @if(count($cart))
        <div class="glass mt-8 rounded-2xl p-6">
            <div class="flex justify-between text-2xl font-black">
                <span>Total</span>
                <span class="text-cyan-300">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>

            <a href="{{ route('checkout.index') }}" class="mt-6 block rounded-2xl bg-purple-600 px-6 py-4 text-center font-black neon">
                Lanjut Checkout
            </a>
        </div>
    @endif
</section>
@endsection