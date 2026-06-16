@extends('frontend.layouts.app', ['title' => 'Cek Pesanan'])

@section('content')
<section class="mx-auto max-w-7xl px-5 py-16">
    <div class="mx-auto max-w-3xl">
        <div class="mb-10 text-center">
            <h1 class="text-5xl font-black">
                Cek <span class="gradient-text">Pesanan</span>
            </h1>

            <p class="mx-auto mt-4 max-w-2xl text-lg text-slate-400">
                Masukkan invoice dan email checkout untuk melihat status pembayaran, invoice, dan akses item
            </p>
        </div>

        <div class="glass rounded-[2rem] p-8 shadow-2xl">
            <form method="POST" action="{{ route('orders.search') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-300">
                        Nomor Invoice
                    </label>

                    <input
                        type="text"
                        name="invoice_number"
                        value="{{ old('invoice_number') }}"
                        placeholder="Contoh: GV-20260602123456789"
                        class="w-full rounded-2xl border border-purple-500/20 bg-[#0b1020] px-5 py-4 text-white placeholder:text-slate-500 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
                        required
                    >

                    @error('invoice_number')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-bold text-slate-300">
                        Email Checkout
                    </label>

                    <input
                        type="email"
                        name="customer_email"
                        value="{{ old('customer_email') }}"
                        placeholder="Email yang digunakan saat checkout"
                        class="w-full rounded-2xl border border-purple-500/20 bg-[#0b1020] px-5 py-4 text-white placeholder:text-slate-500 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
                        required
                    >

                    @error('customer_email')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="w-full rounded-2xl bg-purple-600 px-6 py-4 text-lg font-black text-white transition hover:scale-[1.01] hover:bg-purple-500 neon"
                >
                    Cek Pesanan
                </button>
            </form>

            <div class="mt-8 grid gap-4 md:grid-cols-3">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center">
                    <div class="text-sm font-bold text-purple-300">Status</div>
                    <p class="mt-2 text-sm text-slate-400">Lihat status pembayaran</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center">
                    <div class="text-sm font-bold text-purple-300">Invoice</div>
                    <p class="mt-2 text-sm text-slate-400">Unduh invoice pesanan</p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-4 text-center">
                    <div class="text-sm font-bold text-purple-300">Akses Item</div>
                    <p class="mt-2 text-sm text-slate-400">Cek link akses jika tersedia</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection