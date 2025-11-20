@extends('layouts.app')

@section('title', 'Dashboard - Helpdesk')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-home mr-3 text-blue-500"></i>
                Dashboard Saya
            </h1>
            <p class="text-slate-400 text-sm mt-1">Selamat datang kembali, <span id="userName" class="text-blue-400 font-medium">User</span></p>
        </div>
        <button id="createTicketBtn" class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40">
            <i class="fas fa-plus"></i>
            <span class="font-medium">Buat Tiket Baru</span>
        </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <!-- Total Tiket Saya -->
        <div class="gradient-border rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs text-slate-400 uppercase mb-2">Total Tiket</p>
                    <h3 class="text-3xl font-bold mb-2" id="stat-my-total">0</h3>
                    <p class="text-xs text-slate-400">
                        <i class="fas fa-ticket-alt mr-1"></i>Semua tiket
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
                    <p class="text-xs text-slate-400 uppercase mb-2">Menunggu</p>
                    <h3 class="text-3xl font-bold mb-2 text-yellow-500" id="stat-my-open">0</h3>
                    <p class="text-xs text-slate-400">
                        <i class="fas fa-clock mr-1 text-yellow-500"></i>Belum diproses
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                    <i class="fas fa-folder-open text-yellow-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tiket Progress -->
        <div class="gradient-border rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs text-slate-400 uppercase mb-2">Diproses</p>
                    <h3 class="text-3xl font-bold mb-2 text-blue-500" id="stat-my-progress">0</h3>
                    <p class="text-xs text-slate-400">
                        <i class="fas fa-spinner mr-1 text-blue-500"></i>Sedang ditangani
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <i class="fas fa-tasks text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tiket Selesai -->
        <div class="gradient-border rounded-2xl p-6 card-hover">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-xs text-slate-400 uppercase mb-2">Selesai</p>
                    <h3 class="text-3xl font-bold mb-2 text-green-500" id="stat-my-resolved">0</h3>
                    <p class="text-xs text-slate-400">
                        <i class="fas fa-check-circle mr-1 text-green-500"></i>Terselesaikan
                    </p>
                </div>
                <div class="w-14 h-14 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Tiket Aktif -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold flex items-center">
                <i class="fas fa-fire mr-2 text-orange-500"></i>
                Tiket Aktif Saya
            </h2>
            <div class="flex items-center space-x-2">
                <button id="filterAll" class="filter-btn active px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">
                    Semua
                </button>
                <button id="filterOpen" class="filter-btn px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">
                    Open
                </button>
                <button id="filterProgress" class="filter-btn px-3 py-1.5 text-xs font-medium rounded-lg transition-colors">
                    Progress
                </button>
            </div>
        </div>
        <div id="activeTicketsContainer" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="col-span-2 text-center py-12 text-slate-400">
                <i class="fas fa-spinner fa-spin text-3xl mb-3 block"></i>
                <p>Memuat tiket...</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Tiket -->
    <div>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold flex items-center">
                <i class="fas fa-history mr-2 text-cyan-500"></i>
                Riwayat Tiket
            </h2>
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                <input type="text" id="searchTicket" placeholder="Cari tiket..." class="bg-slate-800 border border-slate-700 rounded-lg pl-9 pr-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
            </div>
        </div>
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
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Waktu</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="historyTicketsTable" class="divide-y divide-slate-800">
                        <tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Memuat data...</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-slate-800/30 border-t border-slate-800 flex items-center justify-between">
                <p class="text-sm text-slate-400">Menampilkan <span id="history-count">0</span> tiket</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Create Ticket -->
<div id="createTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-500"></i>
                Buat Tiket Support
            </h2>
            <button id="closeCreateModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">
            <form id="createTicketForm" class="space-y-5">
                <!-- Lokasi -->
                <!-- Ruangan (user pilih) -->
<div>
    <label class="block text-sm font-medium text-slate-300 mb-2">
        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Ruangan <span class="text-red-500">*</span>
    </label>
    <select name="room_id" id="roomSelect" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
        <option value="">Pilih ruangan...</option>
        <!-- Diisi dari /api/options → data.data.rooms -->
    </select>
</div>



                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-heading mr-2 text-cyan-500"></i>Judul Masalah <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required minlength="5" placeholder="Contoh: Komputer tidak bisa nyala">
                </div>

                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-tag mr-2 text-green-500"></i>Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="categorySelect" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="">Memuat kategori...</option>
                    </select>
                </div>

                <!-- Prioritas -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-exclamation-circle mr-2 text-orange-500"></i>Seberapa Urgent? <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="critical" class="sr-only peer" required>
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-500/10 hover:border-red-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-fire text-red-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">Sangat Urgent</p>
                            </div>
                        </label>
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="high" class="sr-only peer">
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-orange-500 peer-checked:bg-orange-500/10 hover:border-orange-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-arrow-up text-orange-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">Urgent</p>
                            </div>
                        </label>
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="medium" class="sr-only peer">
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 hover:border-yellow-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-minus text-yellow-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">Normal</p>
                            </div>
                        </label>
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="low" class="sr-only peer">
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-500/10 hover:border-blue-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-arrow-down text-blue-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">Tidak Urgent</p>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-align-left mr-2 text-yellow-500"></i>Detail Masalah <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="4" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required minlength="20" placeholder="Jelaskan masalah yang Anda alami secara detail..."></textarea>
                    <p class="text-xs text-slate-400 mt-2">
                        <i class="fas fa-lightbulb mr-1"></i>Tip: Semakin detail penjelasan Anda, semakin cepat kami dapat membantu
                    </p>
                </div>

                <!-- Upload -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-paperclip mr-2 text-slate-400"></i>Lampiran (Opsional)
                    </label>
                    <div id="dropZone" class="border-2 border-dashed border-slate-700 rounded-xl p-8 text-center hover:border-blue-500 hover:bg-blue-500/5 cursor-pointer transition-all group">
                        <i class="fas fa-cloud-upload-alt text-blue-500 text-4xl mb-3 block group-hover:scale-110 transition-transform"></i>
                        <p class="text-sm font-medium mb-1 text-white">Klik atau seret file ke sini</p>
                        <p class="text-xs text-slate-400">Maksimal 10MB per file • Foto, PDF, atau dokumen</p>
                        <input type="file" id="fileInput" class="hidden" multiple accept="image/*,.pdf,.doc,.docx,.xls,.xlsx">
                    </div>
                    <div id="filePreview" class="mt-3 space-y-2 hidden"></div>
                </div>
            </form>
        </div>
        <div class="p-6 bg-slate-800/30 border-t border-slate-800 flex justify-end space-x-3">
            <button type="button" id="cancelCreateBtn" class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="submit" form="createTicketForm" class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-blue-500/25">
                <i class="fas fa-paper-plane mr-2"></i>Kirim Tiket
            </button>
        </div>
    </div>
</div>

<!-- Modal Detail Ticket -->
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
        <div class="p-6 bg-slate-800/30 border-t border-slate-800 flex justify-end space-x-3">
            <button type="button" id="closeDetailBtn" class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Tutup
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

.filter-btn {
    background: rgba(51, 65, 85, 0.5);
    border: 1px solid rgba(71, 85, 105, 0.3);
    color: #94a3b8;
}

.filter-btn.active {
    background: linear-gradient(to right, #3b82f6, #2563eb);
    border-color: transparent;
    color: white;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.filter-btn:hover:not(.active) {
    background: rgba(51, 65, 85, 0.8);
    border-color: rgba(59, 130, 246, 0.5);
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', async function() {
    // === API ENDPOINTS ===
    const API_MY_TICKETS = '/api/user/my-tickets';
    const API_CREATE_TICKET = '/api/user/tickets'; // ✅ Diperbaiki
    const OPTIONS_API = '/api/options';

    let allTickets = [];
    let uploadedFiles = [];
    let currentFilter = 'all';
    let currentTicketId = null;

    // === TOAST NOTIFICATION ===
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 p-4 rounded-lg text-white font-medium shadow-lg z-50 ${
            type === 'success' 
                ? 'bg-gradient-to-r from-green-500 to-emerald-600' 
                : 'bg-gradient-to-r from-red-500 to-rose-600'
        }`;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // === FETCH MY TICKETS ===
    async function fetchMyTickets() {
        try {
            const res = await fetch(API_MY_TICKETS);
            const result = await res.json();
            
            if (result.success) {
                const data = result.data;
                document.getElementById('userName').textContent = data.user_name || 'User';
                updateStats(data.stats);
                allTickets = data.tickets || [];
                renderActiveTickets();
                renderHistoryTickets(allTickets);
            } else {
                showToast(result.message || 'Gagal memuat data tiket', 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            showToast('Gagal memuat data tiket', 'error');
        }
    }

    // === UPDATE STATS ===
    function updateStats(stats) {
        document.getElementById('stat-my-total').textContent = stats.total || 0;
        document.getElementById('stat-my-open').textContent = stats.open || 0;
        document.getElementById('stat-my-progress').textContent = stats.in_progress || 0;
        document.getElementById('stat-my-resolved').textContent = stats.resolved || 0;
    }

    // === RENDER ACTIVE TICKETS ===
    function renderActiveTickets() {
        const container = document.getElementById('activeTicketsContainer');
        let filtered = allTickets.filter(t => {
            if (currentFilter === 'all') return !['closed', 'resolved'].includes(t.status);
            if (currentFilter === 'open') return t.status === 'open';
            if (currentFilter === 'progress') return t.status === 'in_progress';
            return true;
        });

        if (!filtered.length) {
            container.innerHTML = `
                <div class="col-span-2 text-center py-12 text-slate-400">
                    <i class="fas fa-inbox text-5xl mb-4 block opacity-50"></i>
                    <p class="font-medium text-lg">Tidak ada tiket aktif</p>
                    <p class="text-sm mt-2">Tiket Anda akan muncul di sini</p>
                </div>
            `;
            return;
        }

        const statusColors = {
            open: { bg: 'bg-yellow-500/20', text: 'text-yellow-500', border: 'border-yellow-500', icon: 'fa-clock', label: 'Menunggu' },
            in_progress: { bg: 'bg-blue-500/20', text: 'text-blue-500', border: 'border-blue-500', icon: 'fa-spinner', label: 'Sedang Diproses' }
        };

        const priorityColors = {
            critical: { bg: 'bg-red-500/20', text: 'text-red-500', icon: 'fa-fire' },
            high: { bg: 'bg-orange-500/20', text: 'text-orange-500', icon: 'fa-exclamation-triangle' },
            medium: { bg: 'bg-yellow-500/20', text: 'text-yellow-500', icon: 'fa-clock' },
            low: { bg: 'bg-blue-500/20', text: 'text-blue-500', icon: 'fa-info-circle' }
        };

        container.innerHTML = filtered.map(t => {
            const createdAt = new Date(t.created_at);
            const diffMin = Math.floor((new Date() - createdAt) / 60000);
            let timeAgo = diffMin < 60 ? `${diffMin} menit` : diffMin < 1440 ? `${Math.floor(diffMin/60)} jam` : `${Math.floor(diffMin/1440)} hari`;

            const statusStyle = statusColors[t.status] || statusColors.open;
            const priorityStyle = priorityColors[t.priority];
            const agentName = t.agent?.name || 'Belum ditangani';
            const agentInitials = agentName !== 'Belum ditangani' ? agentName.substring(0,2).toUpperCase() : '??';

            return `
                <div class="gradient-border rounded-2xl p-6 border-l-4 ${statusStyle.border} hover:shadow-lg transition-all">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-start space-x-4 flex-1">
                            <div class="w-12 h-12 rounded-xl ${statusStyle.bg} flex items-center justify-center flex-shrink-0">
                                <i class="fas ${statusStyle.icon} ${statusStyle.text} text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center space-x-2 mb-2">
                                    <span class="text-xs font-mono text-blue-400">#TKT-${t.id}</span>
                                    <span class="px-2 py-0.5 ${statusStyle.bg} ${statusStyle.text} rounded text-xs font-medium">${statusStyle.label}</span>
                                    <span class="px-2 py-0.5 ${priorityStyle.bg} ${priorityStyle.text} rounded text-xs font-medium uppercase">
                                        <i class="fas ${priorityStyle.icon} mr-1"></i>${t.priority}
                                    </span>
                                </div>
                                <h3 class="font-semibold text-lg mb-2">${t.title}</h3>
                                <p class="text-sm text-slate-400 mb-3 line-clamp-2">${t.description}</p>
                                <div class="flex flex-wrap gap-3 text-xs text-slate-400">
                                    <span><i class="fas fa-tag mr-1"></i>${t.category}</span>
                                    <span><i class="fas fa-map-marker-alt mr-1"></i>${t.room?.name || '-'}</span>
                                    <span><i class="far fa-clock mr-1"></i>${timeAgo} lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-4 border-t border-slate-700">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full ${agentName === 'Belum ditangani' ? 'bg-slate-700' : 'bg-gradient-to-br from-green-500 to-emerald-500'} flex items-center justify-center text-xs font-semibold">
                                ${agentInitials}
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Ditangani oleh</p>
                                <p class="text-sm font-medium text-white">${agentName}</p>
                            </div>
                        </div>
                        <button onclick="openDetailModal(${t.id})" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-eye mr-1"></i>Detail
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // === RENDER HISTORY TICKETS ===
    function renderHistoryTickets(tickets) {
        const tbody = document.getElementById('historyTicketsTable');
        document.getElementById('history-count').textContent = tickets.length;

        if (!tickets.length) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-slate-400">Tidak ada tiket</td></tr>';
            return;
        }

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
            open: 'Menunggu',
            in_progress: 'Diproses',
            resolved: 'Selesai',
            closed: 'Ditutup'
        };

        tbody.innerHTML = tickets.map(t => {
            const createdAt = new Date(t.created_at);
            const diffMin = Math.floor((new Date() - createdAt) / 60000);
            let timeAgo = diffMin < 60 ? `${diffMin} menit` : diffMin < 1440 ? `${Math.floor(diffMin/60)} jam` : `${Math.floor(diffMin/1440)} hari`;

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
                        <span class="text-sm text-slate-400">${timeAgo} lalu</span>
                    </td>
                    <td class="px-6 py-4">
                        <button onclick="openDetailModal(${t.id})" class="p-2 hover:bg-blue-500/10 rounded-lg transition-colors group" title="Lihat Detail">
                            <i class="fas fa-eye text-blue-500 group-hover:scale-110 transition-transform"></i>
                        </button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    // === SEARCH & FILTER ===
    document.getElementById('searchTicket').addEventListener('input', function(e) {
        const term = e.target.value.toLowerCase();
        const filtered = allTickets.filter(t => 
            t.title.toLowerCase().includes(term) || 
            t.description.toLowerCase().includes(term) ||
            t.category.toLowerCase().includes(term) ||
            `TKT-${t.id}`.toLowerCase().includes(term)
        );
        renderHistoryTickets(filtered);
    });

    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.id === 'filterAll' ? 'all' : this.id === 'filterOpen' ? 'open' : 'progress';
            renderActiveTickets();
        });
    });

    // === MODAL: Create Ticket ===
    const createModal = document.getElementById('createTicketModal');
    document.getElementById('createTicketBtn').addEventListener('click', () => {
        createModal.classList.remove('hidden');
        createModal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    });

    function closeCreateModal() {
        createModal.classList.add('hidden');
        createModal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('createTicketForm').reset();
        uploadedFiles = [];
        document.getElementById('filePreview').innerHTML = '';
        document.getElementById('filePreview').classList.add('hidden');
    }

    ['closeCreateModalBtn', 'cancelCreateBtn'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', closeCreateModal);
    });

    createModal.addEventListener('click', e => e.target === createModal && closeCreateModal());
    document.addEventListener('keydown', e => e.key === 'Escape' && !createModal.classList.contains('hidden') && closeCreateModal());

    // === FILE UPLOAD ===
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const filePreview = document.getElementById('filePreview');

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput?.click());
        ['dragenter','dragover'].forEach(e => dropZone.addEventListener(e, ev => {
            ev.preventDefault();
            dropZone.classList.add('border-blue-500','bg-blue-500/10');
        }));
        ['dragleave','drop'].forEach(e => dropZone.addEventListener(e, () => {
            dropZone.classList.remove('border-blue-500','bg-blue-500/10');
        }));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            if (e.dataTransfer.files?.length) {
                fileInput.files = e.dataTransfer.files;
                handleFiles(Array.from(e.dataTransfer.files));
            }
        });
        fileInput?.addEventListener('change', e => handleFiles(Array.from(e.target.files)));
    }

    function handleFiles(files) {
        files.forEach(file => {
            if (file.size > 10 * 1024 * 1024) {
                showToast(`File ${file.name} terlalu besar! Maks 10MB.`, 'error');
                return;
            }
            uploadedFiles.push(file);
            const div = document.createElement('div');
            div.className = 'flex justify-between items-center p-3 bg-slate-800 border border-slate-700 rounded-lg text-sm hover:border-blue-500 transition-colors';
            
            let icon = 'fa-file', iconColor = 'text-blue-500';
            if (file.type.includes('image')) { icon = 'fa-file-image'; iconColor = 'text-green-500'; }
            else if (file.type.includes('pdf')) { icon = 'fa-file-pdf'; iconColor = 'text-red-500'; }
            else if (file.type.includes('word')) { icon = 'fa-file-word'; iconColor = 'text-blue-600'; }
            else if (file.type.includes('excel')) { icon = 'fa-file-excel'; iconColor = 'text-green-600'; }
            
            div.innerHTML = `
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-slate-700 flex items-center justify-center">
                        <i class="fas ${icon} ${iconColor} text-lg"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium truncate text-white">${file.name}</p>
                        <p class="text-xs text-slate-400">${(file.size/1024).toFixed(1)} KB</p>
                    </div>
                </div>
                <button class="text-red-500 hover:text-red-400 remove-file p-2 hover:bg-red-500/10 rounded-lg" data-name="${file.name}">
                    <i class="fas fa-trash"></i>
                </button>
            `;
            filePreview?.appendChild(div);
        });
        if (uploadedFiles.length > 0) filePreview?.classList.remove('hidden');
    }

    filePreview?.addEventListener('click', e => {
        if (e.target.closest('.remove-file')) {
            const name = e.target.closest('.remove-file').dataset.name;
            uploadedFiles = uploadedFiles.filter(f => f.name !== name);
            e.target.closest('div.flex').remove();
            if (uploadedFiles.length === 0) filePreview?.classList.add('hidden');
        }
    });

    // === FETCH OPTIONS ===
    // === FETCH OPTIONS ===
async function fetchOptions() {
    try {
        const res = await fetch(OPTIONS_API);
        const data = await res.json();
        if (data.success) {
            // Kategori
            const catSelect = document.getElementById('categorySelect');
            if (catSelect) {
                catSelect.innerHTML = '<option value="">Pilih Kategori...</option>' + 
                    data.data.categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
            }
            // Ruangan (untuk lokasi)
            const roomSelect = document.getElementById('roomSelect');
            if (roomSelect) {
                roomSelect.innerHTML = '<option value="">Pilih Ruangan...</option>' + 
                    data.data.rooms.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
            }
        }
    } catch (err) {
        console.error('Gagal muat opsi:', err);
    }
}

    // === CREATE TICKET SUBMIT ===
    document.getElementById('createTicketForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        uploadedFiles.forEach((file, i) => {
            formData.append(`attachments[${i}]`, file);
        });

        try {
            const res = await fetch(API_CREATE_TICKET, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const result = await res.json();
            if (result.success) {
                showToast('Tiket berhasil dibuat! Tim kami akan segera menanganinya.');
                closeCreateModal();
                fetchMyTickets();
            } else {
                showToast(result.message || 'Gagal membuat tiket', 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            showToast('Gagal membuat tiket: ' + err.message, 'error');
        }
    });

    // === DETAIL MODAL ===
    window.openDetailModal = async function(id) {
        currentTicketId = id;
        try {
            // ✅ Gunakan API user, bukan API admin
            const res = await fetch(`${API_MY_TICKETS}/${id}`);
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

                const statusText = {
                    open: 'Menunggu',
                    in_progress: 'Sedang Diproses',
                    resolved: 'Selesai',
                    closed: 'Ditutup'
                };

                document.getElementById('detailTicketContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="p-4 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 border border-blue-500/30 rounded-lg">
                            <p class="text-xs text-slate-400 mb-1">ID Tiket Anda</p>
                            <p class="text-2xl font-mono font-bold text-blue-400">#TKT-${t.id}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Status</p>
                                <span class="inline-flex items-center px-3 py-1 ${statusColors[t.status]} border rounded-full text-xs font-semibold">
                                    ${statusText[t.status]}
                                </span>
                            </div>
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Prioritas</p>
                                <span class="inline-flex items-center px-3 py-1 ${priorityColors[t.priority]} border rounded-full text-xs font-semibold capitalize">
                                    ${t.priority}
                                </span>
                            </div>
                        </div>

                        <!-- ✅ TAMPILKAN POSISI USER -->
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Sebagai</p>
                            <p class="font-medium text-white">${t.position?.name || '-'}</p>
                        </div>

                        ${t.agent ? `
                        <div class="p-4 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-500/30 rounded-lg">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="fas fa-user-check mr-1"></i>Ditangani oleh
                            </p>
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center font-bold text-lg">
                                    ${t.agent.name.substring(0,2).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-semibold text-white text-lg">${t.agent.name}</p>
                                    <p class="text-sm text-slate-400">${t.agent.email}</p>
                                </div>
                            </div>
                        </div>
                        ` : `
                        <div class="p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                            <p class="text-sm text-yellow-500">
                                <i class="fas fa-clock mr-2"></i>Tiket Anda sedang menunggu untuk ditangani oleh tim kami
                            </p>
                        </div>
                        `}

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Judul Masalah</p>
                            <p class="font-semibold text-white text-lg">${t.title}</p>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Detail Masalah</p>
                            <p class="text-sm text-slate-300 leading-relaxed">${t.description}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Kategori</p>
                                <p class="font-medium text-white">
                                    <i class="fas fa-tag mr-2 text-blue-500"></i>${t.category}
                                </p>
                            </div>
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Lokasi</p>
                                <p class="font-medium text-white">
                                    <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>${t.room?.name || '-'}
                                </p>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Dibuat</p>
                            <p class="text-sm text-white">
                                <i class="far fa-calendar-alt mr-2"></i>${new Date(t.created_at).toLocaleString('id-ID')}
                            </p>
                        </div>

                        ${t.status === 'resolved' || t.status === 'closed' ? `
                        <div class="p-4 bg-gradient-to-r from-green-500/10 to-emerald-500/10 border border-green-500/30 rounded-lg">
                            <div class="flex items-center space-x-2 mb-2">
                                <i class="fas fa-check-circle text-green-500 text-xl"></i>
                                <p class="font-semibold text-green-500">Tiket Selesai</p>
                            </div>
                            <p class="text-sm text-slate-300">
                                Terima kasih! Masalah Anda telah berhasil diselesaikan oleh tim kami.
                            </p>
                        </div>
                        ` : ''}
                    </div>
                `;
                
                document.getElementById('detailTicketModal').classList.remove('hidden');
                document.getElementById('detailTicketModal').classList.add('flex');
            } else {
                showToast(result.message || 'Gagal memuat detail tiket', 'error');
            }
        } catch (err) {
            console.error('Error:', err);
            showToast('Gagal memuat detail tiket', 'error');
        }
    };

    // === CLOSE DETAIL MODAL ===
    ['closeDetailModalBtn', 'closeDetailBtn'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
            document.getElementById('detailTicketModal')?.classList.add('hidden');
            document.getElementById('detailTicketModal')?.classList.remove('flex');
        });
    });

   
    // === INIT ===
    fetchMyTickets();
    fetchOptions();
    setInterval(fetchMyTickets, 30000); // Auto refresh
});
</script>
@endpush