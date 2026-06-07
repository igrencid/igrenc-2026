@extends('frontend.layouts.app', ['title' => 'Pesanan Berhasil'])

@section('content')
<section class="mx-auto max-w-3xl px-5 py-16">
    <div class="glass rounded-[2rem] p-8 text-center neon">
        <div class="text-5xl">✅</div>

        <h1 class="mt-5 text-4xl font-black">Pesanan Berhasil Dibuat</h1>

        <p class="mt-3 text-slate-300">Invoice:</p>

        <div class="mt-2 text-2xl font-black text-purple-300">
            {{ $order->invoice_number }}
        </div>

        <div class="mt-6 text-3xl font-black text-cyan-300">
            Rp {{ number_format($order->total_price, 0, ',', '.') }}
        </div>

        <p class="mt-5 text-slate-400">
            Status pesanan masih pending. Admin akan memproses setelah pembayaran dikonfirmasi.
        </p>

        <a href="{{ route('items.index') }}" class="mt-8 inline-block rounded-2xl bg-purple-600 px-6 py-4 font-black">
            Belanja Lagi
        </a>
    </div>
</section>
@endsection