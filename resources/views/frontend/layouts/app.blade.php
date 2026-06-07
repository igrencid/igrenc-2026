<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'GameVault Market' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>

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
                Game<span class="text-purple-400">Vault</span>
            </a>

            <div class="hidden items-center gap-8 md:flex">
                <a href="{{ route('home') }}" class="font-medium hover:text-purple-400">Home</a>
                <a href="{{ route('home') }}#games" class="font-medium hover:text-purple-400">Games</a>
                <a href="{{ route('items.index') }}" class="font-medium hover:text-purple-400">Items</a>
                <a href="{{ route('orders.track') }}" class="font-medium hover:text-purple-400">Cek Pesanan</a>
                <a href="{{ route('home') }}#how" class="font-medium hover:text-purple-400">Cara Kerja</a>
            </div>

            <a href="{{ route('cart.index') }}" class="rounded-xl bg-purple-600 px-4 py-2 font-bold hover:bg-purple-500">
                Cart {{ count(session('cart', [])) }}
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
        <div class="mx-auto flex max-w-7xl flex-col gap-3 px-5 text-sm text-slate-400 md:flex-row md:items-center md:justify-between">
            <div>© {{ date('Y') }} GameVault Market. Marketplace item game digital.</div>
            <div>Landing Page → Game → Item → Cart → Checkout</div>
        </div>
    </footer>
</body>
</html>