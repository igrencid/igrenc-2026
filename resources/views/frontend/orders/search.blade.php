@extends('frontend.layouts.app', ['title' => 'Cek Pesanan'])

@section('content')
<section class="mx-auto max-w-2xl px-5 py-16">
    <div class="glass rounded-[2rem] p-8">
        <h1 class="text-4xl font-black">Cek Pesanan</h1>
        <p class="mt-3 text-slate-400">
            Masukkan invoice dan email untuk melihat status pesanan.
        </p>

        <form action="{{ route('orders.search') }}" method="POST" class="mt-8 space-y-4">
            @csrf

            <input 
                name="invoice_number"
                placeholder="Contoh: GV-20260602123456789"
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
                required
            >

            <input 
                name="customer_email"
                type="email"
                placeholder="Email saat checkout"
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
                required
            >

            <button class="w-full rounded-2xl bg-purple-600 px-6 py-4 font-black neon hover:bg-purple-500">
                Cek Pesanan
            </button>
        </form>
    </div>
</section>
@endsection