<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'MBG Pelaporan' }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen bg-base-200">

{{-- sidebar sama content --}}
<div class="drawer md:drawer-open min-h-screen">
    <input id="app-drawer" type="checkbox" class="drawer-toggle" />

    <div class="drawer-content flex flex-col min-w-0">
        {{-- navbar --}}
        <header class="navbar bg-base-100 shadow-sm px-3 md:px-6 sticky top-0 z-30">
            <div class="flex-none md:hidden">
                <label for="app-drawer" aria-label="buka menu" class="btn btn-square btn-ghost btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="inline-block w-5 h-5 stroke-current">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </label>
            </div>

            <div class="flex-1 px-2">
                <a href="{{ url('/') }}" class="text-base md:text-xl font-bold tracking-tight">MBG Pelaporan</a>
            </div>

            <div class="flex-none flex items-center gap-2">
                <div class="hidden sm:flex flex-col items-end text-right">
                    <span class="text-xs md:text-sm font-semibold leading-tight">{{ auth()->user()->name }}</span>
                    <span class="badge badge-neutral badge-soft badge-xs mt-0.5">{{ auth()->user()->role->value }}</span>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="btn btn-soft btn-sm btn-error" title="Keluar">
                        <span class="hidden sm:inline">Keluar</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 sm:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </header>

        {{-- main content --}}
        <main class="flex-1 p-3 sm:p-4 md:p-6 max-w-7xl w-full mx-auto">
            {{-- flash messages --}}
            @if(session('success'))
                <div class="alert alert-success alert-soft mb-4 text-sm">
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error alert-soft mb-4 text-sm">
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>

    {{-- sidebar --}}
    <div class="drawer-side z-40">
        <label for="app-drawer" aria-label="tutup menu" class="drawer-overlay"></label>
        <aside class="bg-base-100 min-h-full w-64 p-4 flex flex-col justify-between shadow-sm">
            <div>
                <div class="px-2 py-3 mb-2 flex items-center justify-between border-b border-base-200">
                    <div class="flex items-center gap-2">
                        <span class="text-xs font-semibold uppercase tracking-wider text-base-content/60">MBG Pelaporan</span>
                    </div>
                    <label for="app-drawer" class="btn btn-xs btn-ghost btn-circle md:hidden" aria-label="tutup">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </label>
                </div>

                <div class="p-3 bg-base-200/60 rounded-box mb-4 sm:hidden">
                    <p class="font-bold text-sm text-base-content">{{ auth()->user()->name }}</p>
                    <span class="badge badge-soft badge-primary badge-xs mt-1">{{ auth()->user()->role->value }}</span>
                </div>

                <ul class="menu p-0 gap-1 w-full text-sm font-medium">
                    @if(auth()->user()->isPelapor())
                        <li>
                            <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.index') ? 'active' : '' }}">
                                Laporan Saya
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('reports.create') }}" class="{{ request()->routeIs('reports.create') ? 'active' : '' }}">
                                Buat Laporan
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isPemantau())
                        <li>
                            <a href="{{ route('review.reports.index') }}" class="{{ request()->routeIs('review.*') ? 'active' : '' }}">
                                Tinjau Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('recap.index') }}" class="{{ request()->routeIs('recap.*') ? 'active' : '' }}">
                                Rekap
                            </a>
                        </li>
                    @endif

                    @if(auth()->user()->isKoordinator())
                        <li>
                            <a href="{{ route('schools.index') }}" class="{{ request()->routeIs('schools.*') ? 'active' : '' }}">
                                Sekolah
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('distributions.index') }}" class="{{ request()->routeIs('distributions.*') ? 'active' : '' }}">
                                Distribusi
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('review.reports.index') }}" class="{{ request()->routeIs('review.*') ? 'active' : '' }}">
                                Semua Laporan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('recap.index') }}" class="{{ request()->routeIs('recap.*') ? 'active' : '' }}">
                                Rekap
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="pt-4 border-t border-base-200 text-xs text-base-content/40 px-2">
                MBG Pelaporan
            </div>
        </aside>
    </div>
</div>

</body>
</html>
