@extends('frontend.layouts.app', ['title' => 'Detail Pesanan'])

@section('content')

@php

    $orderStatus = $order->status;

    $paymentStatus = $order->payment?->status ?? 'pending';

    $isPaid = $paymentStatus === 'paid' || $orderStatus === 'completed';

    $isFailed = in_array($paymentStatus, ['failed', 'deny', 'cancel', 'failure']);

    $isExpired = in_array($paymentStatus, ['expired', 'expire']);

    $statusColor = match (true) {

        $isPaid => 'green',

        $isFailed => 'red',

        $isExpired => 'gray',

        default => 'yellow',

    };

    $statusLabel = match (true) {

        $isPaid => 'Pembayaran Berhasil',

        $isFailed => 'Pembayaran Gagal',

        $isExpired => 'Pembayaran Kedaluwarsa',

        default => 'Menunggu Pembayaran',

    };

@endphp

<section class="mx-auto max-w-7xl px-5 py-12">

    <div class="mb-12 text-center">

        <h1 class="text-5xl font-black">

            Detail

            <span class="gradient-text">

                Pesanan

            </span>

        </h1>

        <p class="mx-auto mt-5 max-w-3xl text-lg text-slate-400">

            Pantau status pembayaran dan akses item yang telah dibeli

        </p>

    </div>


    <div class="glass rounded-[2rem] p-8">

        <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">

            <div>

                <div class="text-sm text-slate-400">

                    Nomor Invoice

                </div>

                <div class="mt-2 text-2xl font-black">

                    {{ $order->invoice_number }}

                </div>

            </div>


            <div class="rounded-full border px-6 py-3 font-bold

                @if($statusColor === 'green')

                    border-green-500/30 bg-green-500/10 text-green-300

                @elseif($statusColor === 'red')

                    border-red-500/30 bg-red-500/10 text-red-300

                @elseif($statusColor === 'gray')

                    border-slate-500/30 bg-slate-500/10 text-slate-300

                @else

                    border-yellow-500/30 bg-yellow-500/10 text-yellow-300

                @endif

            ">

                {{ $statusLabel }}

            </div>

        </div>



        <div class="mt-10 grid gap-6 lg:grid-cols-2">

            <div class="glass rounded-3xl p-6">

                <h2 class="text-2xl font-black">

                    Customer

                </h2>

                <div class="mt-6 space-y-4">

                    <div>

                        <div class="text-sm text-slate-400">

                            Nama

                        </div>

                        <div class="font-bold">

                            {{ $order->customer_name }}

                        </div>

                    </div>

                    <div>

                        <div class="text-sm text-slate-400">

                            Email

                        </div>

                        <div class="font-bold">

                            {{ $order->customer_email }}

                        </div>

                    </div>

                    <div>

                        <div class="text-sm text-slate-400">

                            WhatsApp

                        </div>

                        <div class="font-bold">

                            {{ $order->customer_whatsapp ?? '-' }}

                        </div>

                    </div>

                </div>

            </div>



            <div class="glass rounded-3xl p-6">

                <h2 class="text-2xl font-black">

                    Pembayaran

                </h2>

                <div class="mt-6 space-y-4">

                    <div>

                        <div class="text-sm text-slate-400">

                            Metode

                        </div>

                        <div class="font-bold">

                            {{ strtoupper(str_replace('_',' ', $order->payment?->payment_method ?? 'midtrans')) }}

                        </div>

                    </div>

                    <div>

                        <div class="text-sm text-slate-400">

                            Status

                        </div>

                        <div class="font-bold">

                            {{ $statusLabel }}

                        </div>

                    </div>

                    <div>

                        <div class="text-sm text-slate-400">

                            Total Pembayaran

                        </div>

                        <div class="text-3xl font-black text-cyan-300">

                            Rp {{ number_format($order->total_price,0,',','.') }}

                        </div>

                    </div>

                </div>

            </div>

        </div>



        @if(!$isPaid)

        <div class="mt-10 rounded-3xl border border-cyan-500/20 bg-cyan-500/10 p-8">

            <h2 class="text-3xl font-black text-cyan-300">

                Pembayaran

            </h2>

            <p class="mt-4 text-slate-300">

                Mendukung QRIS, GoPay, ShopeePay, DANA, Virtual Account, dan kartu kredit

            </p>

            <div class="mt-6 text-5xl font-black text-cyan-300">

                Rp {{ number_format($order->total_price,0,',','.') }}

            </div>

            <a

                href="{{ route('orders.midtrans.pay', $order->invoice_number) }}"

                class="neon mt-8 inline-flex rounded-2xl bg-green-600 px-8 py-4 font-black text-white hover:bg-green-500"

            >

                Bayar

            </a>

        </div>

        @endif



        <div class="mt-10">

            <h2 class="text-3xl font-black">

                Item Pesanan

            </h2>

            <div class="mt-6 space-y-5">

                @foreach($order->orderItems as $orderItem)

                <div class="glass flex flex-col justify-between gap-5 rounded-3xl p-6 md:flex-row md:items-center">

                    <div>

                        <div class="text-2xl font-black">

                            {{ $orderItem->item_name }}

                        </div>

                        <div class="mt-2 text-slate-400">

                            Qty {{ $orderItem->quantity }}

                        </div>

                    </div>

                    <div class="text-3xl font-black text-cyan-300">

                        Rp {{ number_format($orderItem->subtotal,0,',','.') }}

                    </div>

                </div>

                @endforeach

            </div>

        </div>



        @if($isPaid)

        <div class="mt-10 rounded-3xl border border-green-500/20 bg-green-500/10 p-8">

            <h2 class="text-3xl font-black text-green-300">

                Akses Item

            </h2>

            <p class="mt-3 text-slate-300">

                Item sudah tersedia dan siap digunakan

            </p>

            <div class="mt-8 flex flex-wrap gap-4">

                <a

                    href="{{ route('orders.invoice.download', $order->invoice_number) }}"

                    class="rounded-2xl bg-cyan-600 px-6 py-4 font-black text-white hover:bg-cyan-500"

                >

                    Download Invoice

                </a>

                <a

                    href="{{ route('items.index') }}"

                    class="rounded-2xl bg-purple-600 px-6 py-4 font-black text-white hover:bg-purple-500"

                >

                    Belanja Lagi

                </a>

            </div>

        </div>

        @endif

    </div>

</section>

@endsection