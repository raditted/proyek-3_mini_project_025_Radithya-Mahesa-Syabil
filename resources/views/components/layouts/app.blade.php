<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MBG Pelaporan' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-base-200">

{{-- navbar --}}
<div class="navbar bg-base-100 shadow-sm px-4">
    <div class="flex-1">
        <a href="{{ url('/') }}" class="text-lg font-bold">MBG Pelaporan</a>
    </div>
    <div class="flex-none gap-2">
        <span class="text-sm text-base-content/60 hidden sm:block">
            {{ auth()->user()->name }}
            <span class="badge badge-sm ml-1">{{ auth()->user()->role->value }}</span>
        </span>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-sm btn-ghost">Keluar</button>
        </form>
    </div>
</div>

{{-- sidebar sama content --}}
<div class="flex min-h-[calc(100vh-4rem)]">

    {{-- sidebar --}}
    <aside class="w-56 bg-base-100 shadow-sm hidden md:block">
        <ul class="menu p-4 gap-1">
            @if(auth()->user()->isPelapor())
                <li><a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">Laporan Saya</a></li>
                <li><a href="{{ route('reports.create') }}" class="{{ request()->routeIs('reports.create') ? 'active' : '' }}">Buat Laporan</a></li>
            @endif

            @if(auth()->user()->isPemantau())
                <li><a href="{{ route('review.reports.index') }}" class="{{ request()->routeIs('review.*') ? 'active' : '' }}">Tinjau Laporan</a></li>
                <li><a href="{{ route('recap.index') }}" class="{{ request()->routeIs('recap.*') ? 'active' : '' }}">Rekap</a></li>
            @endif

            @if(auth()->user()->isKoordinator())
                <li><a href="{{ route('schools.index') }}" class="{{ request()->routeIs('schools.*') ? 'active' : '' }}">Sekolah</a></li>
                <li><a href="{{ route('distributions.index') }}" class="{{ request()->routeIs('distributions.*') ? 'active' : '' }}">Distribusi</a></li>
                <li><a href="{{ route('review.reports.index') }}" class="{{ request()->routeIs('review.*') ? 'active' : '' }}">Semua Laporan</a></li>
                <li><a href="{{ route('recap.index') }}" class="{{ request()->routeIs('recap.*') ? 'active' : '' }}">Rekap</a></li>
            @endif
        </ul>
    </aside>

    {{-- main content --}}
    <main class="flex-1 p-6">
        {{-- flash messages --}}
        @if(session('success'))
            <div class="alert alert-success mb-4">
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error mb-4">
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot }}
    </main>
</div>

</body>
</html>