@extends('frontend.layouts.app', ['title' => 'Checkout'])

@section('content')
<section class="mx-auto grid max-w-6xl gap-8 px-5 py-10 lg:grid-cols-2">

    <div>
        <h1 class="text-4xl font-black">
            Checkout
        </h1>

        <p class="mt-2 text-slate-400">
            Lengkapi data pembeli, pilih metode pembayaran, lalu buat pesanan.
        </p>

        <form
            action="{{ route('checkout.store') }}"
            method="POST"
            class="glass mt-8 space-y-4 rounded-2xl p-6"
        >
            @csrf

            <input
                name="customer_name"
                value="{{ old('customer_name') }}"
                placeholder="Nama lengkap"
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
                required
            >

            <input
                name="customer_email"
                type="email"
                value="{{ old('customer_email') }}"
                placeholder="Email"
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
                required
            >

            <input
                name="customer_whatsapp"
                value="{{ old('customer_whatsapp') }}"
                placeholder="Nomor WhatsApp"
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
            >

            <select
                name="payment_method"
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
                required
            >
                <option value="">
                    Pilih Metode Pembayaran
                </option>

                <option
                    value="bank_transfer"
                    @selected(old('payment_method') === 'bank_transfer')
                >
                    Bank Transfer
                </option>

                <option
                    value="qris"
                    @selected(old('payment_method') === 'qris')
                >
                    QRIS
                </option>

                <option
                    value="ewallet"
                    @selected(old('payment_method') === 'ewallet')
                >
                    E-Wallet
                </option>
            </select>

            <div class="rounded-2xl border border-purple-500/20 bg-purple-500/10 p-4 text-sm text-slate-300">

                <div class="font-bold text-purple-300">
                    Alur Pembayaran
                </div>

                <ol class="mt-2 list-decimal space-y-1 pl-5">
                    <li>Buat pesanan terlebih dahulu.</li>

                    <li>Lakukan pembayaran sesuai metode yang dipilih.</li>

                    <li>Upload bukti pembayaran di halaman detail pesanan.</li>

                    <li>Admin akan approve atau reject pembayaran.</li>
                </ol>

            </div>

            <textarea
                name="notes"
                placeholder="Catatan / ID game / instruksi tambahan"
                class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3 outline-none"
            >{{ old('notes') }}</textarea>

            <button
                class="w-full rounded-2xl bg-purple-600 px-6 py-4 font-black neon hover:bg-purple-500"
            >
                Buat Pesanan
            </button>

        </form>
    </div>

    <div class="glass rounded-2xl p-6">

        <h2 class="text-2xl font-black">
            Ringkasan
        </h2>

        <div class="mt-5 space-y-4">

            @foreach($cart as $row)

                <div class="flex justify-between border-b border-white/10 pb-3">

                    <div>

                        <div class="font-bold">
                            {{ $row['name'] }}
                        </div>

                        <div class="text-sm text-slate-400">
                            Qty {{ $row['quantity'] }}
                        </div>

                    </div>

                    <div>
                        Rp {{ number_format($row['price'] * $row['quantity'], 0, ',', '.') }}
                    </div>

                </div>

            @endforeach

        </div>

        <div class="mt-6 flex justify-between text-2xl font-black">

            <span>Total</span>

            <span class="text-cyan-300">
                Rp {{ number_format($total, 0, ',', '.') }}
            </span>

        </div>

        <div class="mt-6 rounded-2xl border border-yellow-500/20 bg-yellow-500/10 p-4 text-sm text-yellow-200">

            Pesanan belum otomatis berhasil setelah checkout.

            Status akan berubah setelah user mengunggah bukti pembayaran dan admin memverifikasi pembayaran.

        </div>

    </div>

</section>
@endsection