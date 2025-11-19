<header class="gradient-border p-6 flex justify-between items-center sticky top-0 z-10">
    <div>
        <h1 class="text-2xl font-bold">Hai, {{ auth()->user()->name ?? 'Saiful Rahman' }}</h1>
        <p class="text-slate-400 text-sm">Selamat datang kembali di dashboard Helpdesk</p>
    </div>
    
    <div class="flex items-center space-x-4">
        <!-- AI Assistant Button -->
        <button id="aiChatToggle" class="flex items-center space-x-2 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-lg transition-all duration-300 shadow-lg hover:shadow-purple-500/50">
            <i class="fas fa-robot text-white"></i>
            <span class="text-sm font-medium text-white">AI Assistant</span>
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
        </button>
        
        <button class="p-2 rounded-lg hover:bg-slate-800 relative">
            <i class="fas fa-search text-slate-400"></i>
        </button>
        
        <button class="p-2 rounded-lg hover:bg-slate-800 relative">
            <i class="fas fa-bell text-slate-400"></i>
            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
        </button>
        
        <button class="p-2 rounded-lg hover:bg-slate-800 relative">
            <i class="fas fa-envelope text-slate-400"></i>
            <span class="absolute top-0 right-0 bg-blue-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
        </button>
        
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center cursor-pointer">
            <span class="font-semibold">{{ strtoupper(substr(auth()->user()->name ?? 'SR', 0, 2)) }}</span>
        </div>
    </div>
</header>