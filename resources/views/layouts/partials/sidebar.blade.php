@php
    $user = auth()->user();
    $isAdmin = $user->hasRole('admin');
    $isAgent = $user->hasRole('agent');
@endphp

<aside class="w-20 bg-slate-950 border-r border-slate-800 flex flex-col items-center py-6">

    <!-- Logo -->
    <div class="text-2xl font-bold text-blue-500 mb-8">
        <i class="fas fa-headset"></i>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 flex flex-col space-y-4">

        {{-- DASHBOARD --}}
        <div class="sidebar-btn-wrapper relative group">
            @if ($isAdmin)
                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('admin.dashboard') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-home text-lg"></i>
                </a>
            @elseif ($isAgent)
                <a href="{{ route('agent.dashboard') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('agent.dashboard') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-home text-lg"></i>
                </a>
            @else
                <a href="{{ route('user.dashboard') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('user.dashboard') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-home text-lg"></i>
                </a>
            @endif
            <span class="sidebar-tooltip absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-2 bg-slate-800 text-white text-sm rounded-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">Dashboard</span>
        </div>

        {{-- TIKET --}}
        <div class="sidebar-btn-wrapper relative group">
            @if ($isAdmin)
                <a href="{{ route('admin.tiket.index') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('admin.tiket.*') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-ticket-alt text-lg"></i>
                </a>
            @elseif ($isAgent)
                <a href="{{ route('agent.tiket.index') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('agent.tiket.*') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-ticket-alt text-lg"></i>
                </a>
            @else
                <a href="{{ route('user.tiket.index') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('user.tiket.*') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-ticket-alt text-lg"></i>
                </a>
            @endif
            <span class="sidebar-tooltip absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-2 bg-slate-800 text-white text-sm rounded-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">Tiket</span>
        </div>

        {{-- MONITORING JARINGAN --}}
        @if ($isAdmin || $isAgent)
            <div class="sidebar-btn-wrapper relative group">
                <a href="{{ route('admin.monitoring.index') }}"
                class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('monitoring.*') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-network-wired text-lg"></i>
                </a>
                <span class="sidebar-tooltip absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-2 bg-slate-800 text-white text-sm rounded-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">
                    Monitoring
                </span>
            </div>
        @endif

        {{-- KELOLA USER (Admin Only) --}}
        @if ($isAdmin)
            <div class="sidebar-btn-wrapper relative group">
                <a href="{{ route('admin.kelola-user.index') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('admin.kelola-user.*') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-user-cog text-lg"></i>
                </a>
                <span class="sidebar-tooltip absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-2 bg-slate-800 text-white text-sm rounded-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">Kelola Akun</span>
            </div>
        @endif

    </nav>

    <!-- Logout -->
    <div class="mt-auto">
        <div class="sidebar-btn-wrapper relative group">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-3 rounded-xl text-slate-400 hover:text-red-500 hover:bg-slate-800 transition-all duration-200">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </button>
            </form>
            <span class="sidebar-tooltip absolute left-full ml-2 top-1/2 -translate-y-1/2 px-3 py-2 bg-slate-800 text-white text-sm rounded-lg whitespace-nowrap opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 pointer-events-none">Logout</span>
        </div>
    </div>

</aside>