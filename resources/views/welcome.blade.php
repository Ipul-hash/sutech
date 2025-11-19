<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Helpdesk - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background: #0f172a;
        }
        
        .gradient-border {
            position: relative;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: rgba(30, 41, 59, 0.5);
        }

        .ai-chat-panel {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .ai-chat-panel.active {
            transform: translateX(0);
        }

        .chat-message {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .typing-indicator span {
            animation: typing 1.4s infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        /* Hide scrollbar but keep functionality */
        .hide-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        
        .hide-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        
        .hide-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.2);
            border-radius: 10px;
        }
        
        .hide-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(148, 163, 184, 0.3);
        }
        
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.2) transparent;
        }

        /* Sidebar tooltip */
        .sidebar-tooltip {
            position: absolute;
            left: 100%;
            margin-left: 15px;
            padding: 8px 12px;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s ease;
            z-index: 100;
        }

        .sidebar-btn-wrapper:hover .sidebar-tooltip {
            opacity: 1;
        }

        .sidebar-tooltip::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #334155;
        }
    </style>
</head>
<body class="bg-slate-900 text-white">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-20 bg-slate-950 border-r border-slate-800 flex flex-col items-center py-6">
            <!-- Logo -->
            <div class="text-2xl font-bold text-blue-500 mb-8">
                <i class="fas fa-headset"></i>
            </div>
            
            <!-- Main Navigation -->
            <nav class="flex-1 flex flex-col space-y-4">
                <div class="sidebar-btn-wrapper relative">
                    <button class="sidebar-btn p-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200">
                        <i class="fas fa-ticket-alt text-lg"></i>
                    </button>
                    <span class="sidebar-tooltip text-sm text-white">Tiket</span>
                </div>
                
                <div class="sidebar-btn-wrapper relative">
                    <button class="sidebar-btn p-3 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 transition-all duration-200">
                        <i class="fas fa-user-cog text-lg"></i>
                    </button>
                    <span class="sidebar-tooltip text-sm text-white">Kelola Akun</span>
                </div>
            </nav>
            
            <!-- Bottom Actions -->
            <div class="mt-auto">
                <div class="sidebar-btn-wrapper relative">
                    <button class="p-3 rounded-xl text-slate-400 hover:text-red-500 hover:bg-slate-800 transition-all duration-200">
                        <i class="fas fa-sign-out-alt text-lg"></i>
                    </button>
                    <span class="sidebar-tooltip text-sm text-white">Logout</span>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-auto hide-scrollbar">
            <!-- Header -->
            <header class="gradient-border p-6 flex justify-between items-center sticky top-0 z-10">
                <div>
                    <h1 class="text-2xl font-bold">Hai, Saiful Rahman</h1>
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
                        <span class="font-semibold">SR</span>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="p-6 space-y-6">
                <!-- Statistik Utama (Top Summary Cards) -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Statistik Utama</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Total Tiket -->
                        <div class="gradient-border rounded-2xl p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-slate-400 uppercase mb-2">Total Tiket</p>
                                    <h3 class="text-3xl font-bold mb-2">198</h3>
                                    <p class="text-xs text-slate-400">
                                        <span class="text-green-500"><i class="fas fa-arrow-up"></i> 15%</span> dari bulan lalu
                                    </p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-ticket-alt text-blue-500 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Tiket Terbuka -->
                        <div class="gradient-border rounded-2xl p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-slate-400 uppercase mb-2">Tiket Terbuka</p>
                                    <h3 class="text-3xl font-bold mb-2">24</h3>
                                    <p class="text-xs text-slate-400">
                                        <span class="text-yellow-500"><i class="fas fa-arrow-up"></i> 12%</span> dari minggu lalu
                                    </p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                                    <i class="fas fa-folder-open text-yellow-500 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Dalam Progress -->
                        <div class="gradient-border rounded-2xl p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-slate-400 uppercase mb-2">Dalam Progress</p>
                                    <h3 class="text-3xl font-bold mb-2">18</h3>
                                    <p class="text-xs text-slate-400">
                                        <span class="text-blue-500"><i class="fas fa-arrow-up"></i> 8%</span> dari minggu lalu
                                    </p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-spinner text-blue-500 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Selesai -->
                        <div class="gradient-border rounded-2xl p-6 card-hover">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-xs text-slate-400 uppercase mb-2">Selesai</p>
                                    <h3 class="text-3xl font-bold mb-2">156</h3>
                                    <p class="text-xs text-slate-400">
                                        <span class="text-green-500"><i class="fas fa-arrow-up"></i> 24%</span> dari minggu lalu
                                    </p>
                                </div>
                                <div class="w-14 h-14 rounded-xl bg-green-500/20 flex items-center justify-center">
                                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grafik Section -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Grafik Performa</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Trend Tiket (Line Chart) -->
                        <div class="lg:col-span-2 gradient-border rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-6">
                                <div>
                                    <h3 class="font-semibold">Trend Tiket</h3>
                                    <p class="text-xs text-slate-400 mt-1">Data 6 bulan terakhir</p>
                                </div>
                                <select class="bg-slate-800 rounded-lg px-3 py-1.5 text-sm border border-slate-700">
                                    <option>6 Bulan</option>
                                    <option>3 Bulan</option>
                                    <option>1 Bulan</option>
                                </select>
                            </div>
                            <canvas id="trendChart" class="w-full" height="250"></canvas>
                        </div>

                        <!-- Top Permasalahan (Pie Chart) -->
                        <div class="gradient-border rounded-2xl p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="font-semibold">Top Permasalahan</h3>
                            </div>
                            <div class="flex items-center justify-center mb-4">
                                <div class="relative w-40 h-40">
                                    <canvas id="pieChart" width="160" height="160"></canvas>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full bg-red-500"></div>
                                        <span class="text-xs">Server</span>
                                    </div>
                                    <span class="text-xs font-semibold">35%</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full bg-orange-500"></div>
                                        <span class="text-xs">Bug</span>
                                    </div>
                                    <span class="text-xs font-semibold">28%</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                                        <span class="text-xs">Account</span>
                                    </div>
                                    <span class="text-xs font-semibold">22%</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full bg-purple-500"></div>
                                        <span class="text-xs">Performance</span>
                                    </div>
                                    <span class="text-xs font-semibold">10%</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                                        <span class="text-xs">Lainnya</span>
                                    </div>
                                    <span class="text-xs font-semibold">5%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Tiket Terbaru -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Tiket Terbaru</h2>
                    <div class="gradient-border rounded-2xl overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-slate-800/50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">ID Tiket</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Judul</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Kategori</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Prioritas</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Dibuat</th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-800">
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-blue-400">#TKT-1234</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-lg bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-exclamation-circle text-red-500 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium">Server Down - Production</p>
                                                    <p class="text-xs text-slate-400">Website tidak bisa diakses</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-300">Server & Infrastructure</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-red-500/20 text-red-500 rounded text-xs font-medium">High</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-500 rounded text-xs font-medium">Open</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-400">5 menit lalu</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="text-blue-500 hover:text-blue-400 text-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-blue-400">#TKT-1233</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-lg bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-bug text-orange-500 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium">Bug di Halaman Checkout</p>
                                                    <p class="text-xs text-slate-400">Tombol pembayaran error</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-300">Bug & Error</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-orange-500/20 text-orange-500 rounded text-xs font-medium">Medium</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-blue-500/20 text-blue-500 rounded text-xs font-medium">Progress</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-400">15 menit lalu</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="text-blue-500 hover:text-blue-400 text-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-blue-400">#TKT-1232</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-lg bg-blue-500/20 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-key text-blue-500 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium">Reset Password Request</p>
                                                    <p class="text-xs text-slate-400">User tidak bisa login</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-300">Account & Access</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-blue-500/20 text-blue-500 rounded text-xs font-medium">Low</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-blue-500/20 text-blue-500 rounded text-xs font-medium">Progress</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-400">30 menit lalu</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="text-blue-500 hover:text-blue-400 text-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-blue-400">#TKT-1231</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-lg bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-database text-purple-500 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium">Database Performance Issue</p>
                                                    <p class="text-xs text-slate-400">Query lambat >5 detik</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-300">Performance</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-orange-500/20 text-orange-500 rounded text-xs font-medium">Medium</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-yellow-500/20 text-yellow-500 rounded text-xs font-medium">Open</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-400">1 jam lalu</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="text-blue-500 hover:text-blue-400 text-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr class="table-row">
                                        <td class="px-6 py-4">
                                            <span class="text-sm font-mono text-blue-400">#TKT-1230</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 rounded-lg bg-green-500/20 flex items-center justify-center flex-shrink-0">
                                                    <i class="fas fa-envelope text-green-500 text-sm"></i>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-medium">Email Notification Not Working</p>
                                                    <p class="text-xs text-slate-400">Notifikasi tidak terkirim</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-300">Lainnya</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-blue-500/20 text-blue-500 rounded text-xs font-medium">Low</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 bg-green-500/20 text-green-500 rounded text-xs font-medium">Resolved</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="text-sm text-slate-400">2 jam lalu</span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <button class="text-blue-500 hover:text-blue-400 text-sm">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 bg-slate-800/30 border-t border-slate-800 flex items-center justify-between">
                            <p class="text-sm text-slate-400">Menampilkan 5 dari 198 tiket</p>
                            <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
                                Lihat Semua Tiket
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Tiket Butuh Aksi -->
                <div>
                    <h2 class="text-lg font-semibold mb-4">Tiket Butuh Aksi</h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <!-- Urgent Ticket 1 -->
                        <div class="gradient-border rounded-2xl p-6 border-l-4 border-red-500">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-red-500/20 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-exclamation-triangle text-red-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-xs font-mono text-blue-400">#TKT-1234</span>
                                            <span class="px-2 py-0.5 bg-red-500/20 text-red-500 rounded text-xs font-medium">Critical</span>
                                        </div>
                                        <h3 class="font-semibold mb-1">Server Down - Production</h3>
                                        <p class="text-sm text-slate-400 mb-3">Website utama tidak bisa diakses sejak 10 menit yang lalu. Banyak user komplain.</p>
                                        <div class="flex items-center space-x-4 text-xs text-slate-400">
                                            <span><i class="far fa-clock mr-1"></i>5 menit lalu</span>
                                            <span><i class="fas fa-user mr-1"></i>John Doe (Customer)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-xs font-semibold">
                                        UN
                                    </div>
                                    <span class="text-sm text-slate-400">Unassigned</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
                                        Ambil Tiket
                                    </button>
                                    <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Urgent Ticket 2 -->
                        <div class="gradient-border rounded-2xl p-6 border-l-4 border-orange-500">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-orange-500/20 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-clock text-orange-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-xs font-mono text-blue-400">#TKT-1228</span>
                                            <span class="px-2 py-0.5 bg-orange-500/20 text-orange-500 rounded text-xs font-medium">High</span>
                                        </div>
                                        <h3 class="font-semibold mb-1">API Response Timeout</h3>
                                        <p class="text-sm text-slate-400 mb-3">Payment gateway API tidak merespons dalam 30 detik. Transaksi gagal.</p>
                                        <div class="flex items-center space-x-4 text-xs text-slate-400">
                                            <span><i class="far fa-clock mr-1"></i>45 menit lalu</span>
                                            <span><i class="fas fa-user mr-1"></i>Sarah Chen (Merchant)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center text-xs font-semibold">
                                        AS
                                    </div>
                                    <span class="text-sm text-slate-400">Anna Smith</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
                                        Update Status
                                    </button>
                                    <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Waiting Response Ticket -->
                        <div class="gradient-border rounded-2xl p-6 border-l-4 border-yellow-500">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-comment-dots text-yellow-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-xs font-mono text-blue-400">#TKT-1225</span>
                                            <span class="px-2 py-0.5 bg-yellow-500/20 text-yellow-500 rounded text-xs font-medium">Waiting Response</span>
                                        </div>
                                        <h3 class="font-semibold mb-1">Feature Request - Dark Mode</h3>
                                        <p class="text-sm text-slate-400 mb-3">Menunggu klarifikasi dari customer terkait spesifikasi detail fitur.</p>
                                        <div class="flex items-center space-x-4 text-xs text-slate-400">
                                            <span><i class="far fa-clock mr-1"></i>2 jam lalu</span>
                                            <span><i class="fas fa-user mr-1"></i>Mike Wilson (Enterprise)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-yellow-500 to-orange-500 flex items-center justify-center text-xs font-semibold">
                                        MJ
                                    </div>
                                    <span class="text-sm text-slate-400">Mike Johnson</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
                                        Follow Up
                                    </button>
                                    <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Escalated Ticket -->
                        <div class="gradient-border rounded-2xl p-6 border-l-4 border-purple-500">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start space-x-4">
                                    <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center flex-shrink-0">
                                        <i class="fas fa-arrow-up text-purple-500 text-xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center space-x-2 mb-1">
                                            <span class="text-xs font-mono text-blue-400">#TKT-1220</span>
                                            <span class="px-2 py-0.5 bg-purple-500/20 text-purple-500 rounded text-xs font-medium">Escalated</span>
                                        </div>
                                        <h3 class="font-semibold mb-1">Data Loss After Update</h3>
                                        <p class="text-sm text-slate-400 mb-3">Customer kehilangan data setelah update sistem. Sudah di-eskalasi ke tim senior.</p>
                                        <div class="flex items-center space-x-4 text-xs text-slate-400">
                                            <span><i class="far fa-clock mr-1"></i>3 jam lalu</span>
                                            <span><i class="fas fa-user mr-1"></i>Tech Corp Inc</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-xs font-semibold">
                                        JD
                                    </div>
                                    <span class="text-sm text-slate-400">John Doe (Senior)</span>
                                </div>
                                <div class="flex space-x-2">
                                    <button class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
                                        View Progress
                                    </button>
                                    <button class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg text-sm font-medium transition-colors">
                                        Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- AI Chat Panel -->
        <div id="aiChatPanel" class="ai-chat-panel fixed right-0 top-0 h-full w-96 bg-slate-950 border-l border-slate-800 shadow-2xl z-50 flex flex-col">
            <!-- Chat Header -->
            <div class="gradient-border p-4 flex items-center justify-between border-b border-slate-800">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center">
                        <i class="fas fa-robot text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">AI Assistant</h3>
                        <div class="flex items-center space-x-1">
                            <span class="w-2 h-2 bg-green-400 rounded-full"></span>
                            <span class="text-xs text-slate-400">Online</span>
                        </div>
                    </div>
                </div>
                <button id="closeChatPanel" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-slate-400 hover:text-white"></i>
                </button>
            </div>

            <!-- Quick Actions -->
            <div class="p-4 border-b border-slate-800">
                <p class="text-xs text-slate-400 mb-3">Quick Actions</p>
                <div class="grid grid-cols-2 gap-2">
                    <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                        <i class="fas fa-search text-purple-500 text-sm mb-1"></i>
                        <p class="text-xs font-medium">Cari Tiket</p>
                    </button>
                    <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                        <i class="fas fa-lightbulb text-yellow-500 text-sm mb-1"></i>
                        <p class="text-xs font-medium">Saran Solusi</p>
                    </button>
                    <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                        <i class="fas fa-chart-bar text-blue-500 text-sm mb-1"></i>
                        <p class="text-xs font-medium">Analisis Data</p>
                    </button>
                    <button class="quick-action-btn p-3 bg-slate-800/50 hover:bg-slate-800 rounded-lg text-left transition-colors">
                        <i class="fas fa-brain text-pink-500 text-sm mb-1"></i>
                        <p class="text-xs font-medium">Prediksi Trend</p>
                    </button>
                </div>
            </div>

            <!-- Chat Messages -->
            <div id="chatMessages" class="flex-1 overflow-y-auto p-4 space-y-4 hide-scrollbar">
                <!-- Welcome Message -->
                <div class="chat-message flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-800/50 rounded-2xl rounded-tl-none p-3">
                            <p class="text-sm">Halo! Saya AI Assistant Helpdesk. Saya bisa membantu Anda:</p>
                            <ul class="text-xs text-slate-400 mt-2 space-y-1">
                                <li>• Mencari dan menganalisis tiket</li>
                                <li>• Memberikan solusi otomatis</li>
                                <li>• Memprediksi permasalahan</li>
                                <li>• Membuat laporan instan</li>
                            </ul>
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
                    </div>
                </div>

                <!-- Example User Message -->
                <div class="chat-message flex items-start space-x-3 flex-row-reverse">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-semibold">SR</span>
                    </div>
                    <div class="flex-1 text-right">
                        <div class="bg-blue-500 rounded-2xl rounded-tr-none p-3 inline-block">
                            <p class="text-sm text-white">Tampilkan tiket priority high yang belum diselesaikan</p>
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
                    </div>
                </div>

                <!-- AI Response with Data -->
                <div class="chat-message flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-800/50 rounded-2xl rounded-tl-none p-3">
                            <p class="text-sm mb-3">Saya menemukan 3 tiket high priority yang masih open:</p>
                            
                            <!-- Mini Ticket Cards -->
                            <div class="space-y-2">
                                <div class="bg-slate-900/50 rounded-lg p-2 border-l-2 border-red-500">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-mono text-blue-400">#TKT-1234</span>
                                        <span class="text-xs px-2 py-0.5 bg-red-500/20 text-red-500 rounded">Critical</span>
                                    </div>
                                    <p class="text-xs font-medium mb-1">Server Down - Production</p>
                                    <p class="text-xs text-slate-400">5 menit lalu</p>
                                </div>
                                
                                <div class="bg-slate-900/50 rounded-lg p-2 border-l-2 border-orange-500">
                                    <div class="flex items-center justify-between mb-1">
                                        <span class="text-xs font-mono text-blue-400">#TKT-1228</span>
                                        <span class="text-xs px-2 py-0.5 bg-orange-500/20 text-orange-500 rounded">High</span>
                                    </div>
                                    <p class="text-xs font-medium mb-1">API Response Timeout</p>
                                    <p class="text-xs text-slate-400">45 menit lalu</p>
                                </div>
                            </div>

                            <button class="mt-3 text-xs text-purple-400 hover:text-purple-300">
                                <i class="fas fa-external-link-alt mr-1"></i>Lihat Detail Lengkap
                            </button>
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
                    </div>
                </div>
            </div>

            <!-- Chat Input -->
            <div class="p-4 border-t border-slate-800">
                <div class="flex items-center space-x-2">
                    <button class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                        <i class="fas fa-paperclip text-slate-400"></i>
                    </button>
                    <input 
                        type="text" 
                        id="chatInput"
                        placeholder="Ketik pesan atau pertanyaan..." 
                        class="flex-1 bg-slate-800 border border-slate-700 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500"
                    >
                    <button id="sendMessage" class="p-2 bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 rounded-lg transition-colors">
                        <i class="fas fa-paper-plane text-white"></i>
                    </button>
                </div>
                <div class="flex items-center justify-between mt-2">
                    <div class="flex items-center space-x-2">
                        <button class="text-xs text-slate-400 hover:text-white transition-colors">
                            <i class="fas fa-microphone mr-1"></i>Voice
                        </button>
                        <button class="text-xs text-slate-400 hover:text-white transition-colors">
                            <i class="fas fa-image mr-1"></i>Image
                        </button>
                    </div>
                    <span class="text-xs text-slate-500">Sutech AI</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        // AI Chat Panel Toggle
        const aiChatToggle = document.getElementById('aiChatToggle');
        const aiChatPanel = document.getElementById('aiChatPanel');
        const closeChatPanel = document.getElementById('closeChatPanel');
        const chatInput = document.getElementById('chatInput');
        const sendMessage = document.getElementById('sendMessage');
        const chatMessages = document.getElementById('chatMessages');

        aiChatToggle.addEventListener('click', () => {
            aiChatPanel.classList.add('active');
        });

        closeChatPanel.addEventListener('click', () => {
            aiChatPanel.classList.remove('active');
        });

        // Quick Actions
        document.querySelectorAll('.quick-action-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const actionText = this.querySelector('p').textContent;
                chatInput.value = actionText;
                chatInput.focus();
            });
        });

        // Send Message Function
        function sendChatMessage() {
            const message = chatInput.value.trim();
            if (message === '') return;

            // Add user message
            const userMessageHTML = `
                <div class="chat-message flex items-start space-x-3 flex-row-reverse">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-xs font-semibold">SR</span>
                    </div>
                    <div class="flex-1 text-right">
                        <div class="bg-blue-500 rounded-2xl rounded-tr-none p-3 inline-block">
                            <p class="text-sm text-white">${message}</p>
                        </div>
                        <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
                    </div>
                </div>
            `;
            chatMessages.insertAdjacentHTML('beforeend', userMessageHTML);

            // Clear input
            chatInput.value = '';

            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Show typing indicator
            const typingHTML = `
                <div id="typingIndicator" class="chat-message flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-robot text-white text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <div class="bg-slate-800/50 rounded-2xl rounded-tl-none p-3">
                            <div class="typing-indicator flex space-x-1">
                                <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                                <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                                <span class="w-2 h-2 bg-slate-400 rounded-full"></span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            chatMessages.insertAdjacentHTML('beforeend', typingHTML);
            chatMessages.scrollTop = chatMessages.scrollHeight;

            // Simulate AI response
            setTimeout(() => {
                document.getElementById('typingIndicator').remove();
                
                const aiResponseHTML = `
                    <div class="chat-message flex items-start space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-robot text-white text-xs"></i>
                        </div>
                        <div class="flex-1">
                            <div class="bg-slate-800/50 rounded-2xl rounded-tl-none p-3">
                                <p class="text-sm">Baik, saya sedang memproses permintaan Anda. Mohon tunggu sebentar...</p>
                            </div>
                            <span class="text-xs text-slate-500 mt-1 block">Baru saja</span>
                        </div>
                    </div>
                `;
                chatMessages.insertAdjacentHTML('beforeend', aiResponseHTML);
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1500);
        }

        sendMessage.addEventListener('click', sendChatMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendChatMessage();
            }
        });

        // Sidebar navigation
        document.querySelectorAll('.sidebar-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.sidebar-btn').forEach(b => {
                    b.classList.remove('active', 'bg-blue-500', 'text-white');
                    b.classList.add('text-slate-400');
                });
                this.classList.add('active', 'bg-blue-500', 'text-white');
                this.classList.remove('text-slate-400');
            });
        });

        // Trend Chart (Line Chart)
        const trendCanvas = document.getElementById('trendChart');
        const trendCtx = trendCanvas.getContext('2d');
        trendCanvas.width = trendCanvas.offsetWidth;
        trendCanvas.height = 250;

        const months = ['Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        const openData = [45, 38, 52, 48, 35, 24];
        const progressData = [25, 30, 28, 32, 25, 18];
        const resolvedData = [80, 95, 110, 125, 142, 156];

        const maxValue = Math.max(...openData, ...progressData, ...resolvedData);
        const chartHeight = trendCanvas.height - 60;
        const chartWidth = trendCanvas.width - 80;
        const startX = 40;
        const startY = 20;

        // Draw grid lines
        trendCtx.strokeStyle = '#1e293b';
        trendCtx.lineWidth = 1;
        for (let i = 0; i <= 5; i++) {
            const y = startY + (chartHeight / 5) * i;
            trendCtx.beginPath();
            trendCtx.moveTo(startX, y);
            trendCtx.lineTo(startX + chartWidth, y);
            trendCtx.stroke();
        }

        // Function to draw line
        function drawLine(data, color, label) {
            trendCtx.strokeStyle = color;
            trendCtx.lineWidth = 3;
            trendCtx.beginPath();

            data.forEach((value, index) => {
                const x = startX + (chartWidth / (data.length - 1)) * index;
                const y = startY + chartHeight - (value / maxValue) * chartHeight;
                
                if (index === 0) {
                    trendCtx.moveTo(x, y);
                } else {
                    trendCtx.lineTo(x, y);
                }
            });

            trendCtx.stroke();

            // Draw points
            data.forEach((value, index) => {
                const x = startX + (chartWidth / (data.length - 1)) * index;
                const y = startY + chartHeight - (value / maxValue) * chartHeight;
                
                trendCtx.fillStyle = color;
                trendCtx.beginPath();
                trendCtx.arc(x, y, 4, 0, Math.PI * 2);
                trendCtx.fill();
            });
        }

        // Draw lines
        drawLine(resolvedData, '#22c55e', 'Selesai');
        drawLine(openData, '#eab308', 'Open');
        drawLine(progressData, '#3b82f6', 'Progress');

        // Draw X-axis labels
        trendCtx.fillStyle = '#94a3b8';
        trendCtx.font = '12px Inter';
        trendCtx.textAlign = 'center';
        months.forEach((month, index) => {
            const x = startX + (chartWidth / (months.length - 1)) * index;
            trendCtx.fillText(month, x, trendCanvas.height - 10);
        });

        // Draw legend
        const legendY = 10;
        const legendItems = [
            { color: '#22c55e', label: 'Selesai' },
            { color: '#eab308', label: 'Open' },
            { color: '#3b82f6', label: 'Progress' }
        ];

        let legendX = trendCanvas.width - 250;
        legendItems.forEach(item => {
            trendCtx.fillStyle = item.color;
            trendCtx.fillRect(legendX, legendY, 12, 12);
            trendCtx.fillStyle = '#94a3b8';
            trendCtx.font = '12px Inter';
            trendCtx.textAlign = 'left';
            trendCtx.fillText(item.label, legendX + 18, legendY + 10);
            legendX += 80;
        });

        // Pie Chart
        const pieCanvas = document.getElementById('pieChart');
        const pieCtx = pieCanvas.getContext('2d');
        const centerX = 80;
        const centerY = 80;
        const radius = 60;

        const pieData = [
            { percentage: 35, color: '#ef4444', label: 'Server' },
            { percentage: 28, color: '#f97316', label: 'Bug' },
            { percentage: 22, color: '#3b82f6', label: 'Account' },
            { percentage: 10, color: '#a855f7', label: 'Performance' },
            { percentage: 5, color: '#22c55e', label: 'Lainnya' }
        ];

        let currentAngle = -Math.PI / 2;

        pieData.forEach(item => {
            const sliceAngle = (item.percentage / 100) * 2 * Math.PI;
            
            pieCtx.beginPath();
            pieCtx.moveTo(centerX, centerY);
            pieCtx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            pieCtx.closePath();
            pieCtx.fillStyle = item.color;
            pieCtx.fill();
            
            currentAngle += sliceAngle;
        });

        // Inner circle for donut effect
        pieCtx.beginPath();
        pieCtx.arc(centerX, centerY, 35, 0, 2 * Math.PI);
        pieCtx.fillStyle = '#0f172a';
        pieCtx.fill();
    </script>
</body>
</html>