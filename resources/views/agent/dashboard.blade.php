@extends('layouts.app')
@section('title', 'Dashboard Agent - Helpdesk')
@section('content')
<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-headset mr-3 text-blue-500"></i>
                Dashboard Agent
            </h1>
            <p class="text-slate-400 text-sm mt-1">Kelola tiket support tim Anda</p>
        </div>
        <div class="flex items-center space-x-3">
            <div class="px-4 py-2 bg-slate-800/50 rounded-lg border border-slate-700">
                <p class="text-xs text-slate-400 mb-1">Team Anda</p>
                <p class="font-semibold text-blue-400" id="teamName">Loading...</p>
            </div>
        </div>
    </div>

    <!-- Statistik Tim -->
    <div>
        <h2 class="text-lg font-semibold mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-blue-500"></i>
            Statistik Tim Anda
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Total Tiket Tim -->
            <div class="gradient-border rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs text-slate-400 uppercase mb-2">Total Tiket Tim</p>
                        <h3 class="text-3xl font-bold mb-2" id="stat-team-total">0</h3>
                        <p class="text-xs text-slate-400">
                            <i class="fas fa-users mr-1"></i>Tim Anda
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Tiket Open -->
            <div class="gradient-border rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs text-slate-400 uppercase mb-2">Perlu Diambil</p>
                        <h3 class="text-3xl font-bold mb-2 text-yellow-500" id="stat-team-open">0</h3>
                        <p class="text-xs text-slate-400">
                            <i class="fas fa-exclamation-circle mr-1 text-yellow-500"></i>Menunggu
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
                        <p class="text-xs text-slate-400 uppercase mb-2">Sedang Dikerjakan</p>
                        <h3 class="text-3xl font-bold mb-2 text-blue-500" id="stat-team-progress">0</h3>
                        <p class="text-xs text-slate-400">
                            <i class="fas fa-spinner mr-1 text-blue-500"></i>Progress
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-tasks text-blue-500 text-xl"></i>
                    </div>
                </div>
            </div>

            <!-- Selesai -->
            <div class="gradient-border rounded-2xl p-6 card-hover">
                <div class="flex items-center justify-between">
                    <div class="flex-1">
                        <p class="text-xs text-slate-400 uppercase mb-2">Selesai</p>
                        <h3 class="text-3xl font-bold mb-2 text-green-500" id="stat-team-resolved">0</h3>
                        <p class="text-xs text-slate-400">
                            <i class="fas fa-check-circle mr-1 text-green-500"></i>Resolved
                        </p>
                    </div>
                    <div class="w-14 h-14 rounded-xl bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiket Yang Harus Dikerjakan (OPEN) -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold flex items-center">
                <i class="fas fa-clipboard-list mr-2 text-yellow-500"></i>
                Tiket Menunggu Diambil
            </h2>
            <div class="flex items-center space-x-2">
                <select id="filterPriority" class="text-sm bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                    <option value="">Semua Prioritas</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
        </div>
        <div id="openTicketsContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="col-span-2 text-center py-12 text-slate-400">
                <i class="fas fa-spinner fa-spin text-3xl mb-3 block"></i>
                <p>Memuat tiket...</p>
            </div>
        </div>
    </div>

    <!-- Tiket Terbaru Tim -->
    <div>
        <h2 class="text-lg font-semibold mb-4 flex items-center">
            <i class="fas fa-history mr-2 text-cyan-500"></i>
            Tiket Terbaru Tim
        </h2>
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Agent</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="recentTeamTicketsTable" class="divide-y divide-slate-800">
                        <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-800/30 border-t border-slate-800 flex items-center justify-between">
                <p class="text-sm text-slate-400">Menampilkan <span id="recent-count">0</span> tiket terbaru</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Ambil Tiket -->
<div id="takeTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-md animate-modal">
        <div class="p-6 border-b border-slate-800">
            <h3 class="text-xl font-bold flex items-center">
                <i class="fas fa-hand-paper mr-3 text-blue-500"></i>
                Ambil Tiket
            </h3>
        </div>
        <div class="p-6">
            <div class="mb-4 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                <p class="text-sm text-slate-300 mb-2">Anda akan mengambil tiket:</p>
                <p class="font-bold text-white" id="takeTicketTitle">-</p>
                <p class="text-xs text-slate-400 mt-1" id="takeTicketId">-</p>
            </div>
            <p class="text-sm text-slate-400 mb-4">
                <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                Setelah diambil, tiket akan menjadi tanggung jawab Anda dan statusnya akan berubah menjadi "In Progress".
            </p>
        </div>
        <div class="p-6 bg-slate-800/30 border-t border-slate-800 flex space-x-3">
            <button type="button" id="cancelTakeBtn" class="flex-1 px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="button" id="confirmTakeBtn" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-blue-500/25">
                <i class="fas fa-check mr-2"></i>Ambil Tiket
            </button>
        </div>
    </div>
</div>

<!-- Modal Detail Tiket -->
<div id="detailTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                Detail Tiket
            </h2>
            <button id="closeDetailModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[70vh]" id="detailTicketContent">
            <!-- Diisi via JS -->
        </div>
    </div>
</div>

<!-- Modal Update Progress -->
<div id="updateProgressModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-tasks mr-3 text-green-500"></i>
                Update Progress
            </h2>
            <button id="closeUpdateModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <form id="updateProgressForm" class="space-y-4">
                <input type="hidden" id="updateTicketId">
                
                <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700 mb-4">
                    <p class="text-xs text-slate-400 mb-1">Tiket</p>
                    <p class="font-semibold text-white" id="updateTicketTitle">-</p>
                    <p class="text-xs text-slate-400 mt-1" id="updateTicketIdDisplay">-</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-tasks mr-2 text-blue-500"></i>Status <span class="text-red-500">*</span>
                    </label>
                    <select id="updateStatus" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                    </select>
                    <p class="text-xs text-slate-400 mt-2">
                        <i class="fas fa-info-circle mr-1"></i>Pilih "Resolved" jika tiket sudah selesai dikerjakan
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-comment mr-2 text-yellow-500"></i>Catatan Progress (Opsional)
                    </label>
                    <textarea id="updateNote" rows="4" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" placeholder="Jelaskan update progress atau solusi yang diberikan..."></textarea>
                </div>
            </form>
        </div>
        <div class="p-6 bg-slate-800/30 border-t border-slate-800 flex justify-end space-x-3">
            <button type="button" id="cancelUpdateBtn" class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="submit" form="updateProgressForm" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-green-500/25">
                <i class="fas fa-save mr-2"></i>Update Progress
            </button>
        </div>
    </div>
</div>

<style>
@keyframes modal-in {
    from {
        opacity: 0;
        transform: scale(0.95) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.animate-modal {
    animation: modal-in 0.3s ease-out;
}

.card-hover {
    transition: all 0.3s ease;
}

.card-hover:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.gradient-border {
    background: linear-gradient(to bottom, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.95));
    border: 1px solid rgba(71, 85, 105, 0.3);
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    // ✅ SESUAIKAN DENGAN ROUTE BARU
    const API_DASHBOARD = '/api/agent/dashboard';
    const API_TEKNIK_TICKET = '/api/teknik-get'; // untuk detail full
    
    let currentTicketId = null;

    // === FETCH DASHBOARD ===
    async function fetchDashboard() {
        try {
            const res = await fetch(API_DASHBOARD);
            const result = await res.json();
            
            if (result.success) {
                const data = result.data;
                
                // Update Team Info
                document.getElementById('teamName').textContent = data.team_name || 'Tidak ada tim';
                
                // Update Stats
                updateStats(data.stats);
                
                // Render Open Tickets
                renderOpenTickets(data.open_tickets);
                
                // Render Recent Tickets
                renderRecentTickets(data.recent_tickets);
            } else {
                console.error('Gagal memuat data:', result.message);
            }
        } catch (err) {
            console.error('Error:', err);
            alert('❌ Gagal memuat data dashboard');
        }
    }

    // === UPDATE STATS ===
    function updateStats(stats) {
        document.getElementById('stat-team-total').textContent = stats.total || 0;
        document.getElementById('stat-team-open').textContent = stats.open || 0;
        document.getElementById('stat-team-progress').textContent = stats.in_progress || 0;
        document.getElementById('stat-team-resolved').textContent = stats.resolved || 0;
    }

    // === RENDER OPEN TICKETS ===
    function renderOpenTickets(tickets) {
        const container = document.getElementById('openTicketsContainer');
        
        if (!tickets.length) {
            container.innerHTML = `
                <div class="col-span-2 text-center py-12 text-slate-400">
                    <i class="fas fa-check-circle text-5xl mb-4 block text-green-500/50"></i>
                    <p class="font-medium text-lg">Tidak ada tiket yang menunggu</p>
                    <p class="text-sm mt-2">Semua tiket sudah diambil atau dikerjakan</p>
                </div>
            `;
            return;
        }

        const priorityColors = {
            critical: { bg: 'bg-red-500/20', text: 'text-red-500', border: 'border-red-500', icon: 'fa-fire' },
            high: { bg: 'bg-orange-500/20', text: 'text-orange-500', border: 'border-orange-500', icon: 'fa-exclamation-triangle' },
            medium: { bg: 'bg-yellow-500/20', text: 'text-yellow-500', border: 'border-yellow-500', icon: 'fa-clock' },
            low: { bg: 'bg-blue-500/20', text: 'text-blue-500', border: 'border-blue-500', icon: 'fa-info-circle' }
        };

        container.innerHTML = tickets.map(t => {
            const createdAt = new Date(t.created_at);
            const now = new Date();
            const diffMin = Math.floor((now - createdAt) / 60000);
            let timeAgo = diffMin < 60 ? `${diffMin} menit` : diffMin < 1440 ? `${Math.floor(diffMin/60)} jam` : `${Math.floor(diffMin/1440)} hari`;

            const priorityStyle = priorityColors[t.priority];
            const userName = t.user?.name || 'Unknown User';
            const userEmail = t.user?.email || '';

            return `
                <div class="gradient-border rounded-2xl p-6 border-l-4 ${priorityStyle.border} hover:shadow-lg transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="w-12 h-12 rounded-xl ${priorityStyle.bg} flex items-center justify-center flex-shrink-0">
                                <i class="fas ${priorityStyle.icon} ${priorityStyle.text} text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-xs font-mono text-blue-400">#TKT-${t.id}</span>
                                    <span class="px-2 py-0.5 ${priorityStyle.bg} ${priorityStyle.text} rounded text-xs font-medium uppercase">${t.priority}</span>
                                </div>
                                <h3 class="font-semibold text-lg mb-2">${t.title}</h3>
                                <p class="text-sm text-slate-400 mb-3 line-clamp-2">${t.description}</p>
                                <div class="flex flex-wrap gap-3 text-xs text-slate-400">
                                    <span><i class="fas fa-tag mr-1"></i>${t.category}</span>
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>${t.location}</span>
                                    <span><i class="far fa-clock mr-1"></i>${timeAgo} lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-slate-700">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-xs font-semibold">
                                ${userName.substring(0,2).toUpperCase()}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">${userName}</p>
                                <p class="text-xs text-slate-400">${userEmail}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="openDetailModal(${t.id})" class="px-3 py-2 bg-slate-700 hover:bg-slate-600 rounded-lg text-xs font-medium transition-colors" title="Lihat Detail">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </button>
                            <button onclick="openTakeTicketModal(${t.id}, '${t.title.replace(/'/g, "\\'")}', 'TKT-${t.id}')" class="px-3 py-2 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg text-xs font-medium transition-all shadow-lg shadow-blue-500/25">
                                <i class="fas fa-hand-paper mr-1"></i>Ambil
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // === RENDER RECENT TICKETS ===
    function renderRecentTickets(tickets) {
        const tbody = document.getElementById('recentTeamTicketsTable');
        document.getElementById('recent-count').textContent = tickets.length;

        if (!tickets.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Tidak ada tiket terbaru</td></tr>';
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
                open: 'bg-yellow-500/20 text-yellow-500 border-yellow-500/30',
                in_progress: 'bg-blue-500/20 text-blue-500 border-blue-500/30',
                resolved: 'bg-green-500/20 text-green-500 border-green-500/30',
                closed: 'bg-slate-500/20 text-slate-500 border-slate-500/30'
            };

            const statusText = {
                open: 'Open',
                in_progress: 'Progress',
                resolved: 'Resolved',
                closed: 'Closed'
            };

            const agentName = t.agent?.name || 'Belum diambil';
            const agentInitials = agentName !== 'Belum diambil' ? agentName.substring(0,2).toUpperCase() : '??';

            return `
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="text-sm font-mono text-blue-400 font-semibold">#TKT-${t.id}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-white truncate max-w-xs">${t.title}</p>
                        <p class="text-xs text-slate-400 truncate max-w-xs">${t.description.substring(0, 50)}${t.description.length > 50 ? '...' : ''}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-300">${t.category}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 ${priorityColors[t.priority]} rounded text-xs font-medium capitalize">${t.priority}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 ${statusColors[t.status]} border rounded text-xs font-medium">${statusText[t.status]}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full ${agentName === 'Belum diambil' ? 'bg-slate-700' : 'bg-gradient-to-br from-green-500 to-emerald-500'} flex items-center justify-center text-xs font-bold">
                                ${agentInitials}
                            </div>
                            <span class="text-sm text-slate-300">${agentName}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-400">${timeAgo} lalu</span>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // === MODAL FUNCTIONS ===
    window.openTakeTicketModal = function(id, title, ticketId) {
        currentTicketId = id;
        document.getElementById('takeTicketTitle').textContent = title;
        document.getElementById('takeTicketId').textContent = '#' + ticketId;
        document.getElementById('takeTicketModal').classList.remove('hidden');
        document.getElementById('takeTicketModal').classList.add('flex');
    };

    window.openDetailModal = async function(id) {
        currentTicketId = id;
        try {
            // ✅ DETAIL TETAP PAKAI API TEKNIK (FULL DATA)
            const res = await fetch(`${API_TEKNIK_TICKET}/${id}`);
            const result = await res.json();
            
            if (result.success) {
                const t = result.data;
                
                const statusColors = {
                    open: 'bg-yellow-500/20 text-yellow-500 border-yellow-500/30',
                    in_progress: 'bg-blue-500/20 text-blue-500 border-blue-500/30',
                    resolved: 'bg-green-500/20 text-green-500 border-green-500/30',
                    closed: 'bg-slate-500/20 text-slate-500 border-slate-500/30'
                };
                
                const priorityColors = {
                    low: 'bg-blue-500/20 text-blue-500 border-blue-500/30',
                    medium: 'bg-yellow-500/20 text-yellow-500 border-yellow-500/30',
                    high: 'bg-orange-500/20 text-orange-500 border-orange-500/30',
                    critical: 'bg-red-500/20 text-red-500 border-red-500/30'
                };

                document.getElementById('detailTicketContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-1">ID Tiket</p>
                            <p class="text-lg font-mono font-bold text-blue-400">#TKT-${t.id}</p>
                        </div>
                        
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Pelapor</p>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center font-bold">
                                    ${(t.user?.name || 'NA').substring(0,2).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">${t.user?.name || 'N/A'}</p>
                                    <p class="text-sm text-slate-400">${t.user?.email || ''}</p>
                                </div>
                            </div>
                        </div>

                        ${t.agent ? `
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Agent Penanggung Jawab</p>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center font-bold">
                                    ${t.agent.name.substring(0,2).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">${t.agent.name}</p>
                                    <p class="text-sm text-slate-400">${t.agent.email}</p>
                                </div>
                            </div>
                        </div>
                        ` : ''}

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Judul</p>
                            <p class="font-semibold text-white">${t.title}</p>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Deskripsi</p>
                            <p class="text-sm text-slate-300 leading-relaxed">${t.description}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Kategori</p>
                                <p class="font-medium text-white">${t.category}</p>
                            </div>
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Lokasi</p>
                                <p class="font-medium text-white">${t.location}</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Status</p>
                                <span class="inline-flex items-center px-3 py-1 ${statusColors[t.status]} border rounded-full text-xs font-semibold capitalize">
                                    ${t.status.replace('_', ' ')}
                                </span>
                            </div>
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Prioritas</p>
                                <span class="inline-flex items-center px-3 py-1 ${priorityColors[t.priority]} border rounded-full text-xs font-semibold capitalize">
                                    ${t.priority}
                                </span>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Dibuat</p>
                            <p class="text-sm text-white">
                                <i class="far fa-calendar-alt mr-2"></i>${new Date(t.created_at).toLocaleString('id-ID', { 
                                    weekday: 'long', 
                                    year: 'numeric', 
                                    month: 'long', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        </div>
                    </div>
                `;
                
                document.getElementById('detailTicketModal').classList.remove('hidden');
                document.getElementById('detailTicketModal').classList.add('flex');
            }
        } catch (err) {
            console.error('Error:', err);
            alert('❌ Gagal memuat detail tiket');
        }
    };

    window.openUpdateProgressModal = function(id, title) {
        currentTicketId = id;
        document.getElementById('updateTicketId').value = id;
        document.getElementById('updateTicketTitle').textContent = title;
        document.getElementById('updateTicketIdDisplay').textContent = '#TKT-' + id;
        document.getElementById('updateProgressModal').classList.remove('hidden');
        document.getElementById('updateProgressModal').classList.add('flex');
    };

    // === CLOSE MODALS ===
    function closeAllModals() {
        document.getElementById('takeTicketModal').classList.add('hidden');
        document.getElementById('takeTicketModal').classList.remove('flex');
        document.getElementById('detailTicketModal').classList.add('hidden');
        document.getElementById('detailTicketModal').classList.remove('flex');
        document.getElementById('updateProgressModal').classList.add('hidden');
        document.getElementById('updateProgressModal').classList.remove('flex');
    }

    document.getElementById('cancelTakeBtn').addEventListener('click', closeAllModals);
    document.getElementById('closeDetailModalBtn').addEventListener('click', closeAllModals);
    document.getElementById('closeUpdateModalBtn').addEventListener('click', closeAllModals);
    document.getElementById('cancelUpdateBtn').addEventListener('click', closeAllModals);

    // === AMBIL TIKET (SESUAIKAN DENGAN API LU) ===
    document.getElementById('confirmTakeBtn').addEventListener('click', async function() {
        if (!currentTicketId) return;

        try {
            const res = await fetch(`/api/agent/tickets/${currentTicketId}/take`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await res.json();

            if (result.success) {
                alert('✅ Tiket berhasil diambil!');
                closeAllModals();
                fetchDashboard();
            } else {
                alert('❌ Gagal: ' + (result.message || 'Error tidak diketahui'));
            }
        } catch (err) {
            console.error('Error:', err);
            alert('❌ Error: ' + err.message);
        }
    });

    // === UPDATE PROGRESS (SESUAIKAN DENGAN API LU) ===
    document.getElementById('updateProgressForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const data = {
            status: document.getElementById('updateStatus').value,
            priority: null, // opsional, bisa ditambah
            root_cause: null,
            escalated_to: null,
        };

        // Tambahkan catatan jika ada
        if (document.getElementById('updateNote').value.trim()) {
            // Kirim catatan via /notes
            try {
                await fetch(`/api/agent/tickets/${currentTicketId}/notes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ note: document.getElementById('updateNote').value.trim() })
                });
            } catch (err) {
                console.warn('Gagal kirim catatan:', err);
            }
        }

        // Update status tiket
        try {
            const res = await fetch(`/api/agent/tickets/${currentTicketId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: data.status })
            });

            const result = await res.json();

            if (result.success) {
                alert('✅ Progress berhasil diupdate!');
                closeAllModals();
                document.getElementById('updateProgressForm').reset();
                fetchDashboard();
            } else {
                alert('❌ Gagal: ' + (result.message || 'Error tidak diketahui'));
            }
        } catch (err) {
            console.error('Error:', err);
            alert('❌ Error: ' + err.message);
        }
    });

    // === INIT ===
    fetchDashboard();

    // Auto refresh setiap 30 detik
    setInterval(() => {
        fetchDashboard();
    }, 30000);
});
</script>
@endpush