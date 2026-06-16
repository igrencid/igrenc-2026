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
        .glass { background:rgba(15,23,42,.72); border:1px solid rgba(255,255,255,.08); backdrop-filter:blur(18px); }
        .neon { box-shadow:0 0 40px rgba(147,51,234,.35); }
        .gradient-text {
            background:linear-gradient(90deg,#a855f7,#22d3ee);
            -webkit-background-clip:text;
            color:transparent;
        }
        .card-hover { transition:.25s ease; }
        .card-hover:hover {
            transform:translateY(-6px);
            box-shadow:0 0 35px rgba(168,85,247,.35);
            border-color:rgba(168,85,247,.6);
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden">
    <div class="fixed inset-0 -z-10">
        <div class="absolute top-0 left-1/4 h-72 w-72 rounded-full bg-purple-700/30 blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 h-72 w-72 rounded-full bg-cyan-500/20 blur-[120px]"></div>
    </div>

    <nav class="sticky top-0 z-50 border-b border-white/10 bg-[#050816]/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4">
            <a href="{{ route('home') }}" class="text-xl font-black">
                Igrenc<span class="text-purple-400">Game</span>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('home') }}" class="font-medium hover:text-purple-400">Home</a>
                <a href="{{ route('home') }}#games" class="font-medium hover:text-purple-400">Games</a>
                <a href="{{ route('items.index') }}" class="font-medium hover:text-purple-400">Items</a>
                <a href="{{ route('promo.index') }}" class="font-medium hover:text-purple-400">Promo</a>
                <a href="{{ route('orders.track') }}" class="font-medium hover:text-purple-400">Cek Pesanan</a>
                <a href="{{ route('home') }}#how" class="font-medium hover:text-purple-400">Cara Pembelian</a>
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