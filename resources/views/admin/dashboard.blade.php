@extends('layouts.app')

@section('title', 'Dashboard - Helpdesk')

@section('content')
<div class="p-6 space-y-6">
    <!-- Statistik Utama -->
    <div>
        <h2 class="text-lg font-semibold mb-4">Statistik Utama</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Total Tiket -->
            <div class="gradient-border rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs text-slate-400 uppercase mb-2">Total Tiket</p>
                        <h3 class="text-3xl font-bold mb-2" id="stat-total">0</h3>
                        <p class="text-xs text-slate-400">
                            <span id="total-change" class="text-green-500"><i class="fas fa-arrow-up"></i> 0%</span> dari bulan lalu
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
                        <h3 class="text-3xl font-bold mb-2" id="stat-open">0</h3>
                        <p class="text-xs text-slate-400">
                            <span class="text-yellow-500"><i class="fas fa-circle"></i> Butuh perhatian</span>
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
                        <h3 class="text-3xl font-bold mb-2" id="stat-progress">0</h3>
                        <p class="text-xs text-slate-400">
                            <span class="text-blue-500"><i class="fas fa-spinner"></i> Sedang dikerjakan</span>
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
                        <h3 class="text-3xl font-bold mb-2" id="stat-resolved">0</h3>
                        <p class="text-xs text-slate-400">
                            <span class="text-green-500"><i class="fas fa-check-circle"></i> Terselesaikan</span>
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
            <!-- Trend Tiket -->
            <div class="lg:col-span-2 gradient-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="font-semibold text-lg">Trend Tiket</h3>
                        <p class="text-xs text-slate-400 mt-1" id="trendPeriodLabel">Data 7 hari terakhir</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <button onclick="changeTrendPeriod('today')" id="btn-today" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-800 hover:bg-slate-700 transition-colors border border-slate-700">
                            Hari Ini
                        </button>
                        <button onclick="changeTrendPeriod('week')" id="btn-week" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-500 hover:bg-blue-600 transition-colors">
                            7 Hari
                        </button>
                        <button onclick="changeTrendPeriod('month')" id="btn-month" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-800 hover:bg-slate-700 transition-colors border border-slate-700">
                            30 Hari
                        </button>
                    </div>
                </div>
                <canvas id="trendChart" class="w-full" height="280"></canvas>
            </div>

            <!-- Top Permasalahan -->
            <div class="gradient-border rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold">Top Kategori</h3>
                </div>
                <div class="flex items-center justify-center mb-4">
                    <div class="relative w-40 h-40">
                        <canvas id="pieChart" width="160" height="160"></canvas>
                    </div>
                </div>
                <div id="categoryLegend" class="space-y-2">
                    <!-- Diisi via JS -->
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
                        </tr>
                    </thead>
                    <tbody id="recentTicketsTable" class="divide-y divide-slate-800">
                        <tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-800/30 border-t border-slate-800 flex items-center justify-between">
                <p class="text-sm text-slate-400">Menampilkan <span id="recent-count">0</span> tiket terbaru</p>
                <a 
    href="{{ auth()->user()->hasRole('admin') 
        ? route('admin.tiket.index') 
        : route('user.tiket.index') }}"
    class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
    Lihat Semua Tiket
</a>

            </div>
        </div>
    </div>

    <!-- Tiket Butuh Aksi -->
    <div>
        <h2 class="text-lg font-semibold mb-4">Tiket Butuh Aksi</h2>
        <div id="urgentTicketsContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <!-- Diisi via JS -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    const API_DASHBOARD = '/api/dashboard/stats';
    const API_TREND = '/api/dashboard/trend';
    let currentPeriod = 'week';

    // Fetch dashboard data
    async function fetchDashboardData() {
        try {
            const res = await fetch(API_DASHBOARD);
            const result = await res.json();
            
            if (result.success) {
                const data = result.data;
                updateStats(data.stats);
                drawPieChart(data.top_categories);
                renderRecentTickets(data.recent_tickets);
                renderUrgentTickets(data.urgent_tickets);
            } else {
                console.error('Gagal memuat dashboard:', result.message);
            }
        } catch (err) {
            console.error('Error:', err);
            alert('❌ Gagal memuat data dashboard');
        }
    }

    // Fetch trend data
    async function fetchTrendData(period = 'week') {
        try {
            const res = await fetch(`${API_TREND}?period=${period}`);
            const result = await res.json();
            
            if (result.success) {
                drawTrendChart(result.data, period);
            }
        } catch (err) {
            console.error('Error fetching trend:', err);
        }
    }

    // Change trend period
    window.changeTrendPeriod = function(period) {
        currentPeriod = period;
        
        // Update button states
        ['today', 'week', 'month'].forEach(p => {
            const btn = document.getElementById(`btn-${p}`);
            if (p === period) {
                btn.className = 'px-3 py-1.5 text-xs font-medium rounded-lg bg-blue-500 hover:bg-blue-600 transition-colors';
            } else {
                btn.className = 'px-3 py-1.5 text-xs font-medium rounded-lg bg-slate-800 hover:bg-slate-700 transition-colors border border-slate-700';
            }
        });

        // Update label
        const labels = {
            today: 'Data hari ini (per jam)',
            week: 'Data 7 hari terakhir',
            month: 'Data 30 hari terakhir'
        };
        document.getElementById('trendPeriodLabel').textContent = labels[period];

        fetchTrendData(period);
    }

    // Update statistik
    function updateStats(stats) {
        document.getElementById('stat-total').textContent = stats.total;
        document.getElementById('stat-open').textContent = stats.open;
        document.getElementById('stat-progress').textContent = stats.in_progress;
        document.getElementById('stat-resolved').textContent = stats.resolved;
        
        const changeEl = document.getElementById('total-change');
        const change = stats.total_change;
        const icon = change >= 0 ? 'fa-arrow-up' : 'fa-arrow-down';
        const color = change >= 0 ? 'text-green-500' : 'text-red-500';
        changeEl.className = color;
        changeEl.innerHTML = `<i class="fas ${icon}"></i> ${Math.abs(change)}%`;
    }

    // Trend Chart - Modern Style
    function drawTrendChart(trendData, period) {
        const canvas = document.getElementById('trendChart');
        const ctx = canvas.getContext('2d');
        canvas.width = canvas.offsetWidth;
        canvas.height = 280;

        const labels = trendData.map(d => d.label);
        const openData = trendData.map(d => d.open);
        const progressData = trendData.map(d => d.progress);
        const resolvedData = trendData.map(d => d.resolved);

        const maxValue = Math.max(...openData, ...progressData, ...resolvedData) || 1;
        const chartHeight = canvas.height - 80;
        const chartWidth = canvas.width - 100;
        const startX = 60;
        const startY = 40;

        // Clear canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Background grid lines
        ctx.strokeStyle = '#1e293b';
        ctx.lineWidth = 1;
        const gridLines = 5;
        for (let i = 0; i <= gridLines; i++) {
            const y = startY + (chartHeight / gridLines) * i;
            ctx.beginPath();
            ctx.moveTo(startX, y);
            ctx.lineTo(startX + chartWidth, y);
            ctx.stroke();
            
            // Y-axis labels
            const value = Math.round(maxValue - (maxValue / gridLines) * i);
            ctx.fillStyle = '#64748b';
            ctx.font = '11px Inter, sans-serif';
            ctx.textAlign = 'right';
            ctx.fillText(value, startX - 10, y + 4);
        }

        // Draw line function with smooth curves
        function drawSmoothLine(data, color, label) {
            if (!data.length) return;

            ctx.strokeStyle = color;
            ctx.lineWidth = 3;
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';
            
            // Draw gradient under line
            const gradient = ctx.createLinearGradient(0, startY, 0, startY + chartHeight);
            gradient.addColorStop(0, color + '40');
            gradient.addColorStop(1, color + '00');
            
            ctx.beginPath();
            data.forEach((value, index) => {
                const x = startX + (chartWidth / (data.length - 1)) * index;
                const y = startY + chartHeight - (value / maxValue) * chartHeight;
                
                if (index === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            
            // Fill area under line
            const lastX = startX + chartWidth;
            const baseY = startY + chartHeight;
            ctx.lineTo(lastX, baseY);
            ctx.lineTo(startX, baseY);
            ctx.closePath();
            ctx.fillStyle = gradient;
            ctx.fill();

            // Draw line
            ctx.beginPath();
            data.forEach((value, index) => {
                const x = startX + (chartWidth / (data.length - 1)) * index;
                const y = startY + chartHeight - (value / maxValue) * chartHeight;
                
                if (index === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            ctx.stroke();

            // Draw points
            data.forEach((value, index) => {
                const x = startX + (chartWidth / (data.length - 1)) * index;
                const y = startY + chartHeight - (value / maxValue) * chartHeight;
                
                ctx.fillStyle = color;
                ctx.beginPath();
                ctx.arc(x, y, 5, 0, Math.PI * 2);
                ctx.fill();
                
                // White inner circle
                ctx.fillStyle = '#0f172a';
                ctx.beginPath();
                ctx.arc(x, y, 3, 0, Math.PI * 2);
                ctx.fill();
            });
        }

        // Draw lines in order (back to front)
        drawSmoothLine(resolvedData, '#22c55e', 'Selesai');
        drawSmoothLine(progressData, '#3b82f6', 'Progress');
        drawSmoothLine(openData, '#eab308', 'Open');

        // X-axis labels
        ctx.fillStyle = '#94a3b8';
        ctx.font = '11px Inter, sans-serif';
        ctx.textAlign = 'center';
        labels.forEach((label, index) => {
            const x = startX + (chartWidth / (labels.length - 1)) * index;
            ctx.fillText(label, x, canvas.height - 20);
        });

        // Legend
        const legendY = 15;
        const legendItems = [
            { color: '#22c55e', label: 'Selesai' },
            { color: '#eab308', label: 'Open' },
            { color: '#3b82f6', label: 'Progress' }
        ];

        let legendX = canvas.width - 280;
        legendItems.forEach(item => {
            // Circle
            ctx.fillStyle = item.color;
            ctx.beginPath();
            ctx.arc(legendX + 6, legendY, 5, 0, Math.PI * 2);
            ctx.fill();
            
            // Label
            ctx.fillStyle = '#e2e8f0';
            ctx.font = '12px Inter, sans-serif';
            ctx.textAlign = 'left';
            ctx.fillText(item.label, legendX + 16, legendY + 4);
            legendX += 90;
        });

        // Chart title
        ctx.fillStyle = '#e2e8f0';
        ctx.font = 'bold 13px Inter, sans-serif';
        ctx.textAlign = 'left';
        ctx.fillText('Trend Tiket', startX, 25);
    }

    // Pie Chart
    function drawPieChart(categories) {
        const canvas = document.getElementById('pieChart');
        const ctx = canvas.getContext('2d');
        const centerX = 80;
        const centerY = 80;
        const radius = 60;

        const colors = ['#ef4444', '#f97316', '#3b82f6', '#a855f7', '#22c55e', '#eab308', '#06b6d4'];
        
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        let currentAngle = -Math.PI / 2;
        
        categories.forEach((item, index) => {
            const sliceAngle = (item.percentage / 100) * 2 * Math.PI;
            ctx.beginPath();
            ctx.moveTo(centerX, centerY);
            ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
            ctx.closePath();
            ctx.fillStyle = colors[index % colors.length];
            ctx.fill();
            currentAngle += sliceAngle;
        });

        // Donut hole
        ctx.beginPath();
        ctx.arc(centerX, centerY, 35, 0, 2 * Math.PI);
        ctx.fillStyle = '#0f172a';
        ctx.fill();

        // Legend
        const legend = document.getElementById('categoryLegend');
        legend.innerHTML = categories.map((cat, index) => `
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full" style="background-color: ${colors[index % colors.length]}"></div>
                    <span class="text-xs truncate">${cat.category}</span>
                </div>
                <span class="text-xs font-semibold">${cat.percentage}%</span>
            </div>
        `).join('');
    }

    // Recent Tickets
    function renderRecentTickets(tickets) {
        const tbody = document.getElementById('recentTicketsTable');
        document.getElementById('recent-count').textContent = tickets.length;

        if (!tickets.length) {
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-400">Tidak ada tiket</td></tr>';
            return;
        }

        tbody.innerHTML = tickets.map(t => {
            const createdAt = new Date(t.created_at);
            const now = new Date();
            const diffMin = Math.floor((now - createdAt) / 60000);
            let timeAgo = diffMin < 60 ? `${diffMin} menit` : diffMin < 1440 ? `${Math.floor(diffMin/60)} jam` : `${Math.floor(diffMin/1440)} hari`;

            const priorityColors = {
                low: 'bg-blue-500/20 text-blue-500',
                medium: 'bg-yellow-500/20 text-yellow-500',
                high: 'bg-orange-500/20 text-orange-500',
                critical: 'bg-red-500/20 text-red-500'
            };

            const statusColors = {
                open: 'bg-yellow-500/20 text-yellow-500',
                in_progress: 'bg-blue-500/20 text-blue-500',
                resolved: 'bg-green-500/20 text-green-500',
                closed: 'bg-slate-500/20 text-slate-500'
            };

            const statusText = {
                open: 'Open',
                in_progress: 'Progress',
                resolved: 'Resolved',
                closed: 'Closed'
            };

            return `
                <tr class="table-row">
                    <td class="px-6 py-4">
                        <span class="text-sm font-mono text-blue-400">#TKT-${t.id}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium truncate max-w-xs">${t.title}</p>
                        <p class="text-xs text-slate-400 truncate max-w-xs">${t.description.substring(0, 50)}${t.description.length > 50 ? '...' : ''}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-300">${t.category}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 ${priorityColors[t.priority]} rounded text-xs font-medium capitalize">${t.priority}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 ${statusColors[t.status]} rounded text-xs font-medium">${statusText[t.status]}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-400">${timeAgo} lalu</span>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // Urgent Tickets
    function renderUrgentTickets(tickets) {
        const container = document.getElementById('urgentTicketsContainer');
        
        if (!tickets.length) {
            container.innerHTML = '<div class="col-span-2 text-center py-8 text-slate-400">Tidak ada tiket yang butuh aksi</div>';
            return;
        }

        const borderColors = {
            critical: 'border-red-500',
            high: 'border-orange-500',
            medium: 'border-yellow-500',
            low: 'border-blue-500'
        };

        const bgColors = {
            critical: 'bg-red-500/20',
            high: 'bg-orange-500/20',
            medium: 'bg-yellow-500/20',
            low: 'bg-blue-500/20'
        };

        const textColors = {
            critical: 'text-red-500',
            high: 'text-orange-500',
            medium: 'text-yellow-500',
            low: 'text-blue-500'
        };

        const icons = {
            critical: 'fa-exclamation-triangle',
            high: 'fa-clock',
            medium: 'fa-comment-dots',
            low: 'fa-info-circle'
        };

        container.innerHTML = tickets.map(t => {
            const createdAt = new Date(t.created_at);
            const now = new Date();
            const diffMin = Math.floor((now - createdAt) / 60000);
            let timeAgo = diffMin < 60 ? `${diffMin} menit` : diffMin < 1440 ? `${Math.floor(diffMin/60)} jam` : `${Math.floor(diffMin/1440)} hari`;

            const initials = t.user ? t.user.name.substring(0, 2).toUpperCase() : 'UN';
            const userName = t.user ? t.user.name : 'Unassigned';
            const userRole = t.user ? t.user.role : '';

            return `
                <div class="gradient-border rounded-2xl p-6 border-l-4 ${borderColors[t.priority]}">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 rounded-xl ${bgColors[t.priority]} flex items-center justify-center flex-shrink-0">
                                <i class="fas ${icons[t.priority]} ${textColors[t.priority]} text-xl"></i>
                            </div>
                            <div>
                                <div class="flex items-center space-x-2 mb-1">
                                    <span class="text-xs font-mono text-blue-400">#TKT-${t.id}</span>
                                    <span class="px-2 py-0.5 ${bgColors[t.priority]} ${textColors[t.priority]} rounded text-xs font-medium capitalize">${t.priority}</span>
                                </div>
                                <h3 class="font-semibold mb-1">${t.title}</h3>
                                <p class="text-sm text-slate-400 mb-3">${t.description.substring(0, 80)}${t.description.length > 80 ? '...' : ''}</p>
                                <div class="flex items-center space-x-4 text-xs text-slate-400">
                                    <span><i class="far fa-clock mr-1"></i>${timeAgo} lalu</span>
                                    <span><i class="fas fa-user mr-1"></i>${userName}${userRole ? ` (${userRole})` : ''}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-xs font-semibold">
                                ${initials}
                            </div>
                            <span class="text-sm text-slate-400">${userName}</span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="/tiket/${t.id}" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
                                Detail
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Load dashboard
    fetchDashboardData();
    fetchTrendData('week');

    // Auto refresh setiap 30 detik
    setInterval(() => {
        fetchDashboardData();
        fetchTrendData(currentPeriod);
    }, 30000);
});
</script>
@endpush