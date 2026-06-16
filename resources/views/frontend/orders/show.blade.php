@extends('frontend.layouts.app', ['title' => 'Detail Pesanan'])

@section('content')
@php
    $orderStatus = $order->status;
    $paymentStatus = $order->payment?->status ?? 'pending';
    $latestRefund = $order->refundRequests->first();

    $statusColor = match ($paymentStatus) {
        'paid' => 'green',
        'waiting_verification' => 'blue',
        'rejected' => 'red',
        default => 'yellow',
    };

    $statusLabel = match ($paymentStatus) {
        'paid' => 'Pembayaran Berhasil',
        'waiting_verification' => 'Menunggu Verifikasi Admin',
        'rejected' => 'Pembayaran Ditolak',
        default => 'Menunggu Pembayaran',
    };
@endphp

<div>
    <section class="mx-auto max-w-5xl px-5 py-10">
        <div class="glass rounded-[2rem] p-8">
            <div class="flex flex-col justify-between gap-5 md:flex-row md:items-center">
                <div>
                    <h1 class="text-4xl font-black">Detail Pesanan</h1>

                    <p class="mt-2 text-slate-400">
                        Invoice: {{ $order->invoice_number }}
                    </p>

                    <div class="mt-4 flex flex-wrap gap-3">
                        <a
                            href="{{ route('orders.invoice.download', $order->invoice_number) }}"
                            class="rounded-xl bg-cyan-600 px-5 py-3 font-bold text-white hover:bg-cyan-500"
                        >
                            Download Invoice PDF
                        </a>
                    </div>
                </div>

                <div class="rounded-full border px-5 py-2 font-bold uppercase
                    @if($statusColor === 'green') border-green-500/30 bg-green-500/10 text-green-300
                    @elseif($statusColor === 'blue') border-blue-500/30 bg-blue-500/10 text-blue-300
                    @elseif($statusColor === 'red') border-red-500/30 bg-red-500/10 text-red-300
                    @else border-yellow-500/30 bg-yellow-500/10 text-yellow-300
                    @endif
                ">
                    {{ $statusLabel }}
                </div>
            </div>

            <div class="mt-8 grid gap-5 md:grid-cols-2">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <h2 class="text-xl font-black">Customer</h2>

                    <div class="mt-4 space-y-2 text-slate-300">
                        <div>Nama: {{ $order->customer_name }}</div>
                        <div>Email: {{ $order->customer_email }}</div>
                        <div>Whatsapp: {{ $order->customer_whatsapp ?? '-' }}</div>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <h2 class="text-xl font-black">Payment</h2>

                    <div class="mt-4 space-y-2 text-slate-300">
                        <div>Metode: {{ strtoupper(str_replace('_', ' ', $order->payment?->payment_method ?? 'manual')) }}</div>
                        <div>Status Order: {{ ucfirst(str_replace('_', ' ', $orderStatus)) }}</div>
                        <div>Status Payment: {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}</div>
                        <div>Total: Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>

@if($paymentStatus !== 'paid')
    <div class="mt-8 rounded-2xl border border-purple-500/20 bg-purple-500/10 p-5">

        <h2 class="text-2xl font-black text-purple-300">
            Instruksi Pembayaran
        </h2>

        <div class="mt-4 space-y-2 text-slate-300">

            <div>Transfer sesuai nominal:</div>

            <div class="text-3xl font-black text-cyan-300">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </div>

            <div class="text-sm text-slate-400">
                Pilih salah satu metode pembayaran di bawah ini.
            </div>

        </div>

        <div class="mt-6 flex flex-col gap-4 md:flex-row">

            <a
                href="{{ route('orders.midtrans.pay', $order->invoice_number) }}"
                class="rounded-2xl bg-green-600 px-6 py-4 text-center font-black text-white hover:bg-green-500"
            >
                Bayar via Midtrans
            </a>

            <div class="rounded-2xl border border-cyan-500/20 bg-cyan-500/10 p-4 text-sm text-slate-300">

                <div class="font-bold text-cyan-300">
                    Transfer Manual
                </div>

                <div class="mt-2">
                    Jika tidak menggunakan Midtrans, lakukan transfer manual lalu upload bukti pembayaran di bagian bawah halaman ini.
                </div>

            </div>

        </div>

    </div>
@endif

            <div class="mt-8">
                <h2 class="text-2xl font-black">Item Pesanan</h2>

                <div class="mt-4 space-y-4">
                    @foreach($order->orderItems as $orderItem)
                        <div class="flex flex-col justify-between gap-4 rounded-2xl border border-white/10 bg-white/5 p-5 md:flex-row md:items-center">
                            <div>
                                <div class="font-bold">{{ $orderItem->item_name }}</div>
                                <div class="text-sm text-slate-400">
                                    Qty {{ $orderItem->quantity }} × Rp {{ number_format($orderItem->price, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="font-bold text-cyan-300">
                                Rp {{ number_format($orderItem->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($paymentStatus === 'paid' && $orderStatus === 'completed')
                <div class="mt-8 rounded-2xl border border-green-500/20 bg-green-500/10 p-5">
                    <h2 class="text-2xl font-black text-green-300">
                        Item Berhasil Dibeli
                    </h2>

                    <p class="mt-2 text-slate-300">
                        Pembayaran berhasil diverifikasi. Berikut akses item yang kamu beli.
                    </p>

                    <div class="mt-5 space-y-4">
                        @foreach($order->orderItems as $orderItem)
                            <div class="rounded-2xl border border-white/10 bg-black/30 p-5">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <div class="text-lg font-bold text-white">
                                            {{ $orderItem->item_name }}
                                        </div>

                                        <div class="text-sm text-slate-400">
                                            Qty {{ $orderItem->quantity }}
                                        </div>
                                    </div>

                                    <div class="rounded-full bg-green-500/10 px-4 py-2 text-sm font-bold text-green-300">
                                        Paid
                                    </div>
                                </div>

                                @if($orderItem->item?->requires_access_link)
                                    <div class="mt-4 rounded-xl border border-cyan-500/20 bg-cyan-500/10 p-4">
                                        <h4 class="font-bold text-cyan-300">
                                            Link Akses Delivery
                                        </h4>

                                        @if($orderItem->item->access_instruction)
                                            <p class="mt-2 text-sm text-slate-300">
                                                {{ $orderItem->item->access_instruction }}
                                            </p>
                                        @endif

                                        <a
                                            href="{{ $orderItem->item->access_link }}"
                                            target="_blank"
                                            class="mt-4 inline-block rounded-xl bg-cyan-600 px-5 py-3 font-bold text-white hover:bg-cyan-500"
                                        >
                                            Buka Link Akses
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-4 rounded-xl border border-yellow-500/20 bg-yellow-500/10 p-4">
                                        <div class="font-bold text-yellow-300">
                                            Delivery Manual
                                        </div>

                                        <div class="mt-2 text-sm text-slate-300">
                                            Item akan diproses manual oleh admin.
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($latestRefund)
                <div class="mt-8 rounded-2xl border p-5
                    @if($latestRefund->status === 'processed') border-green-500/20 bg-green-500/10
                    @elseif($latestRefund->status === 'rejected') border-red-500/20 bg-red-500/10
                    @else border-yellow-500/20 bg-yellow-500/10
                    @endif
                ">
                    <h2 class="text-2xl font-black
                        @if($latestRefund->status === 'processed') text-green-300
                        @elseif($latestRefund->status === 'rejected') text-red-300
                        @else text-yellow-300
                        @endif
                    ">
                        Status Refund
                    </h2>

                    @if($latestRefund->status === 'processed')
                        <p class="mt-3 text-green-300">
                            Refund berhasil diproses oleh admin. Silakan cek rekening atau e-wallet kamu.
                        </p>
                    @elseif($latestRefund->status === 'rejected')
                        <p class="mt-3 text-red-300">
                            Pengajuan refund ditolak oleh admin.
                        </p>
                    @else
                        <p class="mt-3 text-yellow-300">
                            Pengajuan refund sedang menunggu proses admin.
                        </p>
                    @endif

                    <div class="mt-4 space-y-2 text-slate-300">
                        <div>Metode: {{ strtoupper($latestRefund->refund_method) }}</div>
                        <div>Nama Akun: {{ $latestRefund->account_name }}</div>
                        <div>Nomor Akun: {{ $latestRefund->account_number }}</div>

                        @if($latestRefund->reason)
                            <div>Alasan: {{ $latestRefund->reason }}</div>
                        @endif

                        @if($latestRefund->admin_notes)
                            <div>Catatan Admin: {{ $latestRefund->admin_notes }}</div>
                        @endif

                        @if($latestRefund->processed_at)
                            <div>Diproses: {{ $latestRefund->processed_at->format('d M Y H:i') }}</div>
                        @endif
                    </div>
                </div>
            @endif

            @if($paymentStatus === 'rejected' && ! $latestRefund)
                <div class="mt-8 rounded-2xl border border-red-500/20 bg-red-500/10 p-5">
                    <h2 class="text-2xl font-black text-red-300">Pembayaran Ditolak</h2>

                    <p class="mt-2 text-slate-300">
                        Bukti pembayaran ditolak. Jika kamu sudah transfer, silakan isi data refund agar admin bisa melakukan pengembalian dana.
                    </p>

                    <form
                        action="{{ route('orders.refund.store', $order->invoice_number) }}"
                        method="POST"
                        class="mt-5 space-y-4"
                    >
                        @csrf

                        <select
                            name="refund_method"
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3"
                            required
                        >
                            <option value="">Pilih Metode Refund</option>
                            <option value="bank">Bank Transfer</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>

                        <input
                            type="text"
                            name="account_name"
                            placeholder="Nama pemilik rekening / e-wallet"
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3"
                            required
                        >

                        <input
                            type="text"
                            name="account_number"
                            placeholder="Nomor rekening / nomor e-wallet"
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3"
                            required
                        >

                        <textarea
                            name="reason"
                            placeholder="Alasan / catatan refund"
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3"
                        ></textarea>

                        <button class="rounded-2xl bg-red-600 px-6 py-4 font-black hover:bg-red-500">
                            Kirim Data Refund
                        </button>
                    </form>
                </div>
            @endif

            <div class="mt-8 rounded-2xl border p-5
                @if($statusColor === 'green') border-green-500/20 bg-green-500/10
                @elseif($statusColor === 'blue') border-blue-500/20 bg-blue-500/10
                @elseif($statusColor === 'red') border-red-500/20 bg-red-500/10
                @else border-yellow-500/20 bg-yellow-500/10
                @endif
            ">
                <h2 class="text-2xl font-black
                    @if($statusColor === 'green') text-green-300
                    @elseif($statusColor === 'blue') text-blue-300
                    @elseif($statusColor === 'red') text-red-300
                    @else text-yellow-300
                    @endif
                ">
                    Bukti Pembayaran
                </h2>

                @if($paymentStatus === 'paid')
                    <p class="mt-2 text-green-300">
                        Pembayaran kamu sudah diverifikasi. Pesanan selesai.
                    </p>
                @elseif($paymentStatus === 'waiting_verification')
                    <p class="mt-2 text-blue-300">
                        Bukti pembayaran sudah diupload. Mohon tunggu admin melakukan verifikasi.
                    </p>
                @elseif($paymentStatus === 'rejected')
                    <p class="mt-2 text-red-300">
                        Bukti pembayaran ditolak. Kamu bisa upload ulang bukti yang benar.
                    </p>
                @else
                    <p class="mt-2 text-slate-300">
                        Setelah transfer, upload bukti pembayaran agar admin bisa memproses pesanan.
                    </p>
                @endif

                @if($order->payment?->payment_proof)
                    <div class="mt-5">
                        <p class="mb-3 text-slate-300">Bukti pembayaran:</p>

                        <img
                            src="{{ asset('storage/' . $order->payment->payment_proof) }}"
                            class="max-h-72 rounded-2xl border border-white/10"
                            alt="Bukti Pembayaran"
                        >
                    </div>
                @endif

                @if(! in_array($paymentStatus, ['paid', 'waiting_verification']))
                    <form
                        action="{{ route('orders.upload-proof', $order->invoice_number) }}"
                        method="POST"
                        enctype="multipart/form-data"
                        class="mt-5 space-y-4"
                    >
                        @csrf

                        <input
                            type="file"
                            name="payment_proof"
                            accept="image/*"
                            class="w-full rounded-xl border border-white/10 bg-black/30 px-4 py-3"
                            required
                        >

                        <button class="rounded-2xl bg-purple-600 px-6 py-4 font-black neon hover:bg-purple-500">
                            Upload Bukti
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection