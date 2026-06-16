@extends('frontend.layouts.app', ['title' => 'Promo'])

@section('content')

<section class="mx-auto max-w-7xl px-5 py-12">

    <div class="mb-14 text-center">

        <h1 class="text-5xl font-black">

            Promo

            <span class="gradient-text">

                Spesial Hari Ini

            </span>

        </h1>

        <p class="mx-auto mt-5 max-w-3xl text-lg text-slate-400">

            Nikmati berbagai promo item game pilihan dengan harga lebih hemat sebelum penawaran berakhir

        </p>

    </div>


    <div class="mb-10 flex flex-wrap items-center justify-between gap-5">

        <div>

            <h2 class="text-3xl font-black">

                Promo Aktif

            </h2>

            <p class="mt-3 text-slate-400">

                Menampilkan

                <span class="font-black text-white">

                    {{ $items->total() }}

                </span>

                item yang sedang mendapatkan potongan harga

            </p>

        </div>


        <a
            href="{{ route('items.index') }}"
            class="rounded-2xl bg-purple-600 px-6 py-3 font-bold text-white transition duration-300 hover:scale-105 hover:bg-purple-500 neon"
        >

            Lihat Semua Item

        </a>

    </div>


    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

        @forelse($items as $item)

            @include(
                'frontend.items.partials.card',
                ['item' => $item]
            )

        @empty

            <div class="glass col-span-full rounded-3xl p-16 text-center">

                <h2 class="text-4xl font-black">

                    Belum Ada Promo Aktif

                </h2>

                <p class="mt-4 text-slate-400">

                    Saat ini belum ada item yang sedang diskon

                </p>

            </div>

        @endforelse

    </div>


    @if($items->hasPages())

        <div class="mt-12">

            {{ $items->links() }}

        </div>

    @endif

</section>

@endsection