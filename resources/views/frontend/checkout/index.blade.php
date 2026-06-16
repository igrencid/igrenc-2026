@extends('frontend.layouts.app', ['title' => 'Checkout'])

@section('content')
<section class="mx-auto max-w-7xl px-5 py-12">
    <div class="mb-10 text-center">
        <h1 class="text-5xl font-black">
            Checkout <span class="gradient-text">Pesanan</span>
        </h1>

        <p class="mx-auto mt-4 max-w-3xl text-lg text-slate-400">
            Lengkapi semua data pembeli dengan benar sebelum membuat pesanan
        </p>
    </div>

    <div class="grid gap-8 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <form action="{{ route('checkout.store') }}" method="POST" class="glass rounded-[2rem] p-8">
                @csrf

                <h2 class="text-3xl font-black">
                    Data Pembeli
                </h2>

                <p class="mt-2 text-slate-400">
                    Semua data di bawah ini wajib diisi untuk proses pesanan dan pengiriman invoice
                </p>

                <div class="mt-8 grid gap-5">
                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-300">
                            Nama Lengkap <span class="text-red-400">*</span>
                        </label>

                        <input
                            name="customer_name"
                            value="{{ old('customer_name') }}"
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-2xl border border-purple-500/20 bg-[#0b1020] px-5 py-4 text-white placeholder:text-slate-500 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
                            required
                        >

                        @error('customer_name')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-300">
                            Email Aktif <span class="text-red-400">*</span>
                        </label>

                        <input
                            name="customer_email"
                            type="email"
                            value="{{ old('customer_email') }}"
                            placeholder="Masukkan email aktif"
                            class="w-full rounded-2xl border border-purple-500/20 bg-[#0b1020] px-5 py-4 text-white placeholder:text-slate-500 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
                            required
                        >

                        @error('customer_email')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-300">
                            Nomor WhatsApp Aktif <span class="text-red-400">*</span>
                        </label>

                        <input
                            name="customer_whatsapp"
                            value="{{ old('customer_whatsapp') }}"
                            placeholder="Contoh: 085812345678"
                            class="w-full rounded-2xl border border-purple-500/20 bg-[#0b1020] px-5 py-4 text-white placeholder:text-slate-500 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
                            required
                        >

                        @error('customer_whatsapp')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-bold text-slate-300">
                            Catatan / ID Game / Nickname / Server <span class="text-red-400">*</span>
                        </label>

                        <textarea
                            name="notes"
                            rows="5"
                            placeholder="Contoh: ID Game, nickname, server, atau instruksi tambahan"
                            class="w-full rounded-2xl border border-purple-500/20 bg-[#0b1020] px-5 py-4 text-white placeholder:text-slate-500 outline-none transition focus:border-purple-400 focus:ring-2 focus:ring-purple-500/30"
                            required
                        >{{ old('notes') }}</textarea>

                        @error('notes')
                            <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-8 rounded-3xl border border-cyan-500/20 bg-cyan-500/10 p-6">
                    <h3 class="text-xl font-black text-cyan-300">
                        Pembayaran Otomatis
                    </h3>

                    <p class="mt-3 text-sm leading-6 text-slate-300">
                        Setelah pesanan dibuat, kamu akan diarahkan ke halaman detail pesanan untuk melanjutkan pembayaran. Sistem mendukung QRIS, GoPay, ShopeePay, DANA, Virtual Account, dan kartu kredit
                    </p>

                    <div class="mt-5 grid gap-3 md:grid-cols-2">
                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="font-bold text-white">
                                Verifikasi Otomatis
                            </div>

                            <p class="mt-2 text-sm text-slate-400">
                                Status pesanan berubah otomatis setelah pembayaran berhasil
                            </p>
                        </div>

                        <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                            <div class="font-bold text-white">
                                Invoice Email
                            </div>

                            <p class="mt-2 text-sm text-slate-400">
                                Invoice dapat dikirim ke email setelah pembayaran berhasil
                            </p>
                        </div>
                    </div>
                </div>

                <button
                    type="submit"
                    class="neon mt-8 w-full rounded-2xl bg-purple-600 px-6 py-4 text-lg font-black text-white transition hover:scale-[1.01] hover:bg-purple-500"
                >
                    Buat Pesanan
                </button>
            </form>
        </div>

        <div>
            <div class="glass sticky top-28 rounded-[2rem] p-6">
                <h2 class="text-3xl font-black">
                    Ringkasan
                </h2>

                <div class="mt-6 space-y-5">
                    @foreach($cart as $row)
                        <div class="flex gap-4 border-b border-white/10 pb-5">
                            <div class="h-20 w-20 shrink-0 overflow-hidden rounded-2xl bg-gradient-to-br from-purple-700 via-slate-900 to-cyan-700">
                                @if(!empty($row['image']))
                                    <img
                                        src="{{ asset('storage/' . $row['image']) }}"
                                        alt="{{ $row['name'] }}"
                                        class="h-full w-full object-cover"
                                    >
                                @endif
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="line-clamp-1 font-black">
                                    {{ $row['name'] }}
                                </div>

                                <div class="mt-1 text-sm text-slate-400">
                                    Qty {{ $row['quantity'] }}
                                </div>

                                <div class="mt-2 font-bold text-cyan-300">
                                    Rp {{ number_format($row['price'] * $row['quantity'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 space-y-4">
                    <div class="flex justify-between text-slate-300">
                        <span>Total Item</span>
                        <span class="font-bold text-white">
                            {{ collect($cart)->sum('quantity') }}
                        </span>
                    </div>

                    <div class="flex justify-between text-slate-300">
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

                <div class="mt-6 rounded-2xl border border-green-500/20 bg-green-500/10 p-4 text-sm leading-6 text-green-200">
                    Pembayaran diproses secara otomatis
                </div>

                <a
                    href="{{ route('cart.index') }}"
                    class="mt-4 block rounded-2xl border border-white/10 px-6 py-4 text-center font-bold text-white transition hover:bg-white/10"
                >
                    Kembali ke Keranjang
                </a>
            </div>
        </div>
    </div>
</section>
@endsection