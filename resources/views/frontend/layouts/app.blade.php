<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'IgrencGame' }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />

    <style>
        html { scroll-behavior: smooth; }
        body { background:#050816; color:white; }

        [x-cloak] {
            display: none !important;
        }

        .glass {
            background:rgba(15,23,42,.72);
            border:1px solid rgba(255,255,255,.08);
            backdrop-filter:blur(18px);
        }

        .neon {
            box-shadow:0 0 40px rgba(147,51,234,.35);
        }

        .gradient-text {
            background:linear-gradient(90deg,#a855f7,#22d3ee);
            -webkit-background-clip:text;
            color:transparent;
        }

        .card-hover {
            transition:.25s ease;
        }

        .card-hover:hover {
            transform:translateY(-6px);
            box-shadow:0 0 35px rgba(168,85,247,.35);
            border-color:rgba(168,85,247,.6);
        }
    </style>
</head>

<body
    x-data="{ sidebarOpen: false }"
    class="min-h-screen overflow-x-hidden"
>
    <div class="fixed inset-0 -z-10">
        <div class="absolute top-0 left-1/4 h-72 w-72 rounded-full bg-purple-700/30 blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 h-72 w-72 rounded-full bg-cyan-500/20 blur-[120px]"></div>
    </div>

    <nav class="sticky top-0 z-40 border-b border-white/10 bg-[#050816]/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">

            <div class="flex items-center gap-4">
                <button
                    type="button"
                    @click="sidebarOpen = true"
                    class="flex h-12 w-12 items-center justify-center rounded-2xl border border-white/10 bg-white/5 text-white transition hover:border-purple-500/40 hover:bg-purple-600/20"
                    aria-label="Buka menu"
                >
                    <i class="fa-solid fa-bars text-xl"></i>
                </button>

                <a href="{{ route('home') }}" class="text-xl font-black">
                    Igrenc<span class="text-purple-400">Game</span>
                </a>
            </div>

            <div class="hidden items-center gap-8 md:flex">
                <a
                    href="{{ route('home') }}"
                    class="font-bold text-white transition hover:text-purple-400"
                >
                    Home
                </a>

                <a
                    href="{{ route('home') }}#games"
                    class="font-bold text-white transition hover:text-purple-400"
                >
                    Games
                </a>

                <a
                    href="{{ route('items.index') }}"
                    class="font-bold text-white transition hover:text-purple-400"
                >
                    Items
                </a>

                <a
                    href="{{ route('promo.index') }}"
                    class="font-bold text-white transition hover:text-purple-400"
                >
                    Promo
                </a>
            </div>

            <a
                href="{{ route('cart.index') }}"
                class="relative flex h-12 w-12 items-center justify-center rounded-2xl bg-purple-600 transition duration-300 hover:scale-105 hover:bg-purple-500"
                aria-label="Keranjang"
            >
                <i class="fa-solid fa-cart-shopping text-xl text-white"></i>

                @if(count(session('cart', [])) > 0)
                    <span class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs font-black text-white">
                        {{ count(session('cart', [])) }}
                    </span>
                @endif
            </a>
        </div>
    </nav>

    <div
        x-cloak
        x-show="sidebarOpen"
        x-transition.opacity
        class="fixed inset-0 z-50 bg-black/70 backdrop-blur-sm"
    >
        <aside
            x-show="sidebarOpen"
            @click.outside="sidebarOpen = false"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="h-full w-[330px] max-w-[88vw] overflow-y-auto border-r border-white/10 bg-[#050816]/95 p-5 shadow-2xl backdrop-blur-xl"
        >
            <div class="flex items-center justify-between">
                <a
                    href="{{ route('home') }}"
                    class="text-2xl font-black"
                    @click="sidebarOpen = false"
                >
                    Igrenc<span class="text-purple-400">Game</span>
                </a>

                <button
                    type="button"
                    @click="sidebarOpen = false"
                    class="flex h-10 w-10 items-center justify-center rounded-xl bg-white/10 transition hover:bg-white/20"
                    aria-label="Tutup menu"
                >
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <div class="mt-8 space-y-2">
                <a
                    href="{{ route('home') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 font-bold text-slate-300 transition hover:bg-purple-600/20 hover:text-white"
                >
                    <i class="fa-solid fa-house w-5 text-purple-300"></i>
                    Home
                </a>

                <a
                    href="{{ route('home') }}#games"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 font-bold text-slate-300 transition hover:bg-purple-600/20 hover:text-white"
                >
                    <i class="fa-solid fa-gamepad w-5 text-purple-300"></i>
                    Games
                </a>

                <a
                    href="{{ route('items.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 font-bold text-slate-300 transition hover:bg-purple-600/20 hover:text-white"
                >
                    <i class="fa-solid fa-bag-shopping w-5 text-purple-300"></i>
                    Items
                </a>

                <a
                    href="{{ route('promo.index') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 font-bold text-slate-300 transition hover:bg-purple-600/20 hover:text-white"
                >
                    <i class="fa-solid fa-tags w-5 text-purple-300"></i>
                    Promo
                </a>

                <a
                    href="{{ route('orders.track') }}"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 font-bold text-slate-300 transition hover:bg-purple-600/20 hover:text-white"
                >
                    <i class="fa-solid fa-receipt w-5 text-purple-300"></i>
                    Cek Pesanan
                </a>

                <a
                    href="{{ route('home') }}#how"
                    @click="sidebarOpen = false"
                    class="flex items-center gap-3 rounded-2xl px-4 py-3 font-bold text-slate-300 transition hover:bg-purple-600/20 hover:text-white"
                >
                    <i class="fa-solid fa-list-check w-5 text-purple-300"></i>
                    Cara Pembelian
                </a>
            </div>

            <div class="mt-8 rounded-3xl border border-green-500/20 bg-green-500/10 p-5">
                <div class="text-sm font-bold text-green-300">
                    Customer Service
                </div>

                <div class="mt-2 text-xl font-black text-white">
                    Butuh Bantuan?
                </div>

                <p class="mt-3 text-sm leading-6 text-slate-300">
                    Hubungi CS jika pembayaran belum berubah, invoice belum masuk, atau item belum diterima
                </p>

                <div class="mt-5 rounded-2xl border border-green-500/20 bg-black/20 p-4">
                    <div class="text-sm text-slate-400">
                        WhatsApp CS
                    </div>

                    <div class="mt-1 font-black text-green-300">
                        0858-1329-5317
                    </div>
                </div>

                <a
                    href="https://wa.me/6285813295317"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="mt-5 flex items-center justify-center gap-3 rounded-2xl bg-green-500 px-5 py-4 font-black text-white transition hover:bg-green-400"
                >
                    <i class="fa-brands fa-whatsapp text-xl"></i>
                    Hubungi WhatsApp
                </a>
            </div>
        </aside>
    </div>

    @if(session('success'))
        <div class="mx-auto mt-5 max-w-7xl px-5">
            <div class="rounded-xl border border-green-500/30 bg-green-500/10 p-4 text-green-300">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if(session('warning'))
        <div class="mx-auto mt-5 max-w-7xl px-5">
            <div class="rounded-xl border border-yellow-500/30 bg-yellow-500/10 p-4 text-yellow-300">
                {{ session('warning') }}
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mx-auto mt-5 max-w-7xl px-5">
            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-red-300">
                {{ session('error') }}
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="mt-20 border-t border-white/10 py-8">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-5 text-sm text-slate-400 md:flex-row md:items-center md:justify-between">
            <div>
                © {{ date('Y') }} IgrencGame
            </div>

            <div class="flex flex-col gap-3 md:items-end">
                <div class="flex flex-wrap items-center gap-6 text-white">
                    <span id="liveDate" class="text-slate-300"></span>

                    <a
                        href="{{ route('items.index') }}"
                        class="text-white transition hover:text-purple-400"
                        title="Search"
                    >
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </a>

                    <a
                        href="https://www.facebook.com/share/1E6igMrptE"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-white transition hover:text-blue-400"
                        title="Facebook"
                    >
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a
                        href="https://instagram.com/igrenc.id"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-white transition hover:text-pink-400"
                        title="Instagram"
                    >
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a
                        href="https://wa.me/6285813295317"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-white transition hover:text-green-400"
                        title="WhatsApp"
                    >
                        <i class="fa-brands fa-whatsapp"></i>
                    </a>

                    <a
                        href="https://www.youtube.com/@tn.cycber"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="text-white transition hover:text-red-500"
                        title="YouTube"
                    >
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                </div>

                <div class="text-slate-500">
                    Cepat • Aman • Terpercaya Game Marketplace
                </div>
            </div>
        </div>
    </footer>

    <script>
        function updateLiveDate() {
            const now = new Date();

            const options = {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric',
                timeZone: 'Asia/Jakarta'
            };

            const target = document.getElementById('liveDate');

            if (target) {
                target.textContent = now.toLocaleDateString('en-US', options);
            }
        }

        updateLiveDate();

        setInterval(updateLiveDate, 1000);
    </script>
</body>
</html>