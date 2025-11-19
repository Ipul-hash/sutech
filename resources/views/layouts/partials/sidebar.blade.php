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
        <div class="sidebar-btn-wrapper relative">
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
            <span class="sidebar-tooltip text-sm text-white">Dashboard</span>
        </div>

        {{-- TIKET --}}
        <div class="sidebar-btn-wrapper relative">
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
            <span class="sidebar-tooltip text-sm text-white">Tiket</span>
        </div>

        {{-- KELOLA USER (Admin Only) --}}
        @if ($isAdmin)
            <div class="sidebar-btn-wrapper relative">
                <a href="{{ route('admin.kelola-user.index') }}"
                   class="sidebar-btn p-3 rounded-xl {{ request()->routeIs('admin.kelola-user.*') ? 'bg-blue-500 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-800' }} transition-all duration-200 block">
                    <i class="fas fa-user-cog text-lg"></i>
                </a>
                <span class="sidebar-tooltip text-sm text-white">Kelola Akun</span>
            </div>
        @endif

    </nav>

    <!-- Logout -->
    <div class="mt-auto">
        <div class="sidebar-btn-wrapper relative">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="p-3 rounded-xl text-slate-400 hover:text-red-500 hover:bg-slate-800 transition-all duration-200">
                    <i class="fas fa-sign-out-alt text-lg"></i>
                </button>
            </form>
            <span class="sidebar-tooltip text-sm text-white">Logout</span>
        </div>
    </div>

</aside>