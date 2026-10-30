<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Officer Management') · OMS</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/lucide@0.469.0/dist/umd/lucide.min.js"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    @auth
        <div data-sidebar-shell>
            <button type="button" data-sidebar-overlay aria-label="Close navigation"
                class="fixed inset-0 z-30 hidden bg-slate-950/60 lg:hidden"></button>
            <aside data-sidebar
                class="fixed inset-y-0 left-0 z-40 flex w-80 -translate-x-full flex-col bg-[#102a4c] text-white shadow-xl transition-transform duration-200 lg:translate-x-0">
                <div class="flex items-center justify-between border-b border-white/10 px-6 py-5">
                    <a href="{{ route('overview') }}"
                        class="flex items-center gap-2 text-lg font-semibold text-white">
                        <i data-lucide="shield-check" class="h-6 w-6 text-sky-300"></i> Officer Management
                    </a>
                    <button type="button" data-sidebar-close aria-label="Close navigation"
                        class="rounded-lg p-2 text-slate-200 hover:bg-white/10 lg:hidden">
                        <i data-lucide="x" class="h-5 w-5"></i>
                    </button>
                </div>

                <nav class="flex-1 space-y-1 px-4 py-6 text-base font-medium">
                    <p class="px-3 pb-2 text-xs font-semibold uppercase tracking-wider text-sky-200/70">Management</p>
                    <a href="{{ route('overview') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 {{ request()->routeIs('overview') ? 'bg-white/15 text-white' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="h-5 w-5"></i> Overview
                    </a>
                    <a href="{{ route('officers.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 {{ request()->routeIs('officers.*') ? 'bg-white/15 text-white' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="users" class="h-5 w-5"></i> Officers
                    </a>
                    <a href="{{ route('departments.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 {{ request()->routeIs('departments.*') ? 'bg-white/15 text-white' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="building-2" class="h-5 w-5"></i> Departments
                    </a>
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 rounded-lg px-3 py-2.5 {{ request()->routeIs('profile.*') ? 'bg-white/15 text-white' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                        <i data-lucide="user-round" class="h-5 w-5"></i> Profile
                    </a>
                </nav>

                <div class="border-t border-white/10 p-4">
                    <div class="mb-3 flex items-center gap-3 rounded-lg bg-white/10 px-3 py-2.5">
                        <div
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-sky-200 font-semibold text-[#102a4c]">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium text-white">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-sky-100/70">{{ ucfirst(auth()->user()->role) }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-slate-200 hover:bg-white/10 hover:text-white">
                            <i data-lucide="log-out" class="h-5 w-5"></i> Log out
                        </button>
                    </form>
                </div>
            </aside>

            <div class="lg:pl-80">
                <header class="sticky top-0 z-20 border-b border-slate-200 bg-white/95 backdrop-blur lg:hidden">
                    <div class="flex items-center justify-between px-4 py-3">
                        <button type="button" data-sidebar-open aria-label="Open navigation"
                            class="rounded-lg p-2 text-slate-700 hover:bg-slate-100">
                            <i data-lucide="menu" class="h-6 w-6"></i>
                        </button>
                        <a href="{{ route('officers.index') }}"
                            class="flex items-center gap-2 font-semibold text-[#102a4c]">
                            <i data-lucide="shield-check" class="h-5 w-5"></i> Officer Management
                        </a>
                        <span class="w-10"></span>
                    </div>
                </header>

                <main class="px-2 py-6 sm:px-4 sm:py-8">
                    @if (session('success'))
                        <div
                            class="mb-4 flex items-center gap-2 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                            <i data-lucide="circle-check" class="h-4 w-4"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div
                            class="mb-4 flex items-center gap-2 rounded-lg border border-slate-300 bg-slate-100 px-4 py-3 text-sm text-slate-800">
                            <i data-lucide="circle-alert" class="h-4 w-4"></i> {{ session('error') }}
                        </div>
                    @endif
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="mx-auto max-w-6xl px-2 py-8 sm:px-4">
            @if (session('success'))
                <div
                    class="mb-4 flex items-center gap-2 rounded-lg border border-indigo-200 bg-indigo-50 px-4 py-3 text-sm text-indigo-700">
                    <i data-lucide="circle-check" class="h-4 w-4"></i> {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </main>
    @endauth

    <script>lucide.createIcons();</script>
</body>

</html>