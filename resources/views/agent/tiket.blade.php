@extends('layouts.app')
@section('title', 'Manajemen Tiket Agent - Helpdesk')
@section('content')
<div class="p-6 space-y-6">

    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-ticket-alt mr-3 text-blue-500"></i>
                Manajemen Tiket Agent
            </h1>
            <p class="text-slate-400 text-sm mt-1">Kelola tiket yang ditugaskan ke tim Anda</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="gradient-border rounded-2xl p-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-slate-800/50 rounded-xl p-5">
                <p class="text-3xl font-bold text-blue-500 mb-1" id="stat-total-agent">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Total Tiket Tim</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5">
                <p class="text-3xl font-bold text-yellow-500 mb-1" id="stat-open-agent">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Open</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5">
                <p class="text-3xl font-bold text-blue-500 mb-1" id="stat-progress-agent">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Progress</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5">
                <p class="text-3xl font-bold text-green-500 mb-1" id="stat-resolved-agent">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Resolved</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5">
                <p class="text-3xl font-bold text-slate-500 mb-1" id="stat-closed-agent">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Closed</p>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="gradient-border rounded-2xl p-5">
        <h2 class="text-lg font-semibold mb-4 text-slate-200">Filter Tiket</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="text-xs text-slate-400">Status</label>
                <select id="filterStatus" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    <option value="open">Open</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Prioritas</label>
                <select id="filterPriority" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Kategori</label>
                <select id="filterCategory" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm">
                    <option value="">Semua</option>
                </select>
            </div>
            <div>
                <label class="text-xs text-slate-400">Pencarian</label>
                <input id="searchKeyword" type="text" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-3 py-2 text-sm" placeholder="Cari judul / ID...">
            </div>
        </div>
        <div class="mt-4 flex justify-end">
            <button id="applyFilterBtn" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-medium text-white shadow-lg shadow-blue-600/20">
                <i class="fas fa-filter mr-2"></i> Terapkan Filter
            </button>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="gradient-border rounded-2xl overflow-hidden">
        <div class="p-4 bg-slate-800/30 border-b border-slate-800 flex items-center justify-between">
            <h2 class="font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2 text-blue-500"></i>
                Daftar Tiket Tim
            </h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">ID Tiket</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Pelapor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody" class="divide-y divide-slate-800">
                    <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <i class="fas fa-spinner fa-spin text-3xl mb-3 block"></i>
                        <p>Memuat data tiket tim...</p>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- Modal Detail -->
<div id="detailTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-500"></i> Detail Tiket
            </h2>
            <button id="closeDetailModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]" id="detailTicketContent">
            <!-- Diisi via JS -->
        </div>
    </div>
</div>

<!-- Modal Update Progress -->
<div id="updateProgressModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-sync-alt mr-3 text-blue-500"></i> Update Progress Tiket
            </h2>
            <button id="closeUpdateModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <form id="updateProgressForm" class="space-y-4">
                <input type="hidden" id="ticketId">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-tasks mr-2 text-blue-500"></i>Status
                    </label>
                    <select id="updateStatus" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="open">Open</option>
                        <option value="in_progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-exclamation-circle mr-2 text-orange-500"></i>Prioritas
                    </label>
                    <select id="updatePriority" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-comment mr-2 text-purple-500"></i>Catatan Internal (Opsional)
                    </label>
                    <textarea id="internalNote" rows="3" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" placeholder="Tambahkan catatan untuk tim internal..."></textarea>
                </div>
            </form>
        </div>
        <div class="p-6 bg-slate-800/30 border-t border-slate-800 flex justify-end space-x-3">
            <button type="button" id="cancelUpdateBtn" class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="submit" form="updateProgressForm" class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-blue-500/25">
                <i class="fas fa-save mr-2"></i>Simpan Update
            </button>
            <!-- ✅ TOMBOL SELESAI -->
            <button type="button" id="markAsResolvedBtn" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-green-500/25">
                <i class="fas fa-check mr-2"></i>Selesai
            </button>
        </div>
    </div>
</div>

<!-- Modal Pesan Internal -->
<div id="internalMessageModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-comment-dots mr-3 text-purple-500"></i> Pesan Internal
            </h2>
            <button id="closeMessageModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh] space-y-4" id="messageThread">
            <div class="text-center text-slate-500 py-6">
                <i class="fas fa-inbox text-3xl mb-2 opacity-50"></i>
                <p>Belum ada pesan internal</p>
            </div>
        </div>
        <div class="p-6 bg-slate-800/30 border-t border-slate-800">
            <form id="sendMessageForm" class="flex space-x-3">
                <input type="hidden" id="messageTicketId">
                <input type="text" id="messageContent" class="flex-1 bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20" placeholder="Ketik pesan..." required>
                <button type="submit" class="px-4 py-2.5 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-sm font-medium rounded-lg shadow-lg shadow-purple-500/25">
                    <i class="fas fa-paper-plane mr-1"></i>Kirim
                </button>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes modal-in { from { opacity:0; transform:scale(0.95) translateY(-20px); } to { opacity:1; transform:scale(1) translateY(0); } }
.animate-modal { animation: modal-in 0.3s ease-out; }
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_URL = '/api/agent/tickets';
    const OPTIONS_API = '/api/options';

    // === LOAD KATEGORI ===
    async function loadCategories() {
        try {
            const res = await fetch(OPTIONS_API);
            const result = await res.json();
            if (result.success && result.data?.categories) {
                let options = '<option value="">Semua</option>';
                result.data.categories.forEach(cat => {
                    options += `<option value="${cat.name}">${cat.name}</option>`;
                });
                document.getElementById('filterCategory').innerHTML = options;
            }
        } catch (err) {
            console.error('Gagal muat kategori:', err);
        }
    }

    // === LOAD TIKET ===
    async function loadTickets() {
        const tbody = document.getElementById('ticketsTableBody');
        tbody.innerHTML = `
            <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">
                <i class="fas fa-spinner fa-spin text-3xl mb-3 block"></i>
                <p>Memuat tiket tim Anda...</p>
            </td></tr>
        `;

        try {
            const res = await fetch(API_URL);
            const result = await res.json();

            if (result.success) {
                renderTickets(result.data || []);
                const stats = {
                    total: result.data.length,
                    open: result.data.filter(t => t.status === 'open').length,
                    in_progress: result.data.filter(t => t.status === 'in_progress').length,
                    resolved: result.data.filter(t => t.status === 'resolved').length,
                    closed: result.data.filter(t => t.status === 'closed').length,
                };
                updateStats(stats);
            } else {
                throw new Error(result.message || 'Gagal memuat data');
            }
        } catch (err) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-red-500">${err.message}</td></tr>`;
        }
    }

    // === RENDER TABEL ===
    function renderTickets(tickets) {
        const tbody = document.getElementById('ticketsTableBody');
        if (!tickets.length) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">
                <i class="fas fa-inbox text-4xl mb-3 block opacity-50"></i>
                <p class="font-medium">Tidak ada tiket untuk tim Anda</p>
            </td></tr>`;
            return;
        }

        const prioColors = {
            low: 'bg-blue-500/20 text-blue-500 border-blue-500/30',
            medium: 'bg-yellow-500/20 text-yellow-500 border-yellow-500/30',
            high: 'bg-orange-500/20 text-orange-500 border-orange-500/30',
            critical: 'bg-red-500/20 text-red-500 border-red-500/30'
        };

        const statusColors = {
            open: 'bg-yellow-500/20 text-yellow-500 border-yellow-500/30',
            in_progress: 'bg-blue-500/20 text-blue-500 border-blue-500/30',
            resolved: 'bg-green-500/20 text-green-500 border-green-500/30',
            closed: 'bg-slate-500/20 text-slate-500 border-slate-500/30'
        };

        tbody.innerHTML = tickets.map(t => {
            const createdAt = new Date(t.created_at);
            const now = new Date();
            const diffMin = Math.floor((now - createdAt) / 60000);
            let timeAgo = diffMin < 60 ? `${diffMin} menit` : diffMin < 1440 ? `${Math.floor(diffMin/60)} jam` : `${Math.floor(diffMin/1440)} hari`;

            return `
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4"><span class="text-sm font-mono text-blue-400">#TKT-${t.id}</span></td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-white">${t.title}</p>
                        <p class="text-xs text-slate-400 line-clamp-1">${t.description?.substring(0, 60)}${t.description?.length > 60 ? '...' : ''}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-xs font-bold">
                                ${(t.user?.name || 'NA').substring(0,2).toUpperCase()}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">${t.user?.name || '-'}</p>
                                <p class="text-xs text-slate-400">${t.user?.email || ''}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-sm text-slate-300">${t.category || '-'}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 ${statusColors[t.status]} rounded-full text-xs font-semibold capitalize">
                            ${t.status?.replace('_', ' ') || '-'}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-400"><i class="far fa-clock mr-1"></i>${timeAgo} lalu</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-1">
                            <button data-action="detail" data-id="${t.id}" title="Detail" class="p-2 hover:bg-blue-500/10 rounded-lg group">
                                <i class="fas fa-eye text-blue-500 group-hover:scale-110"></i>
                            </button>
                            ${!t.assigned_to ? `
                            <button data-action="take" data-id="${t.id}" title="Ambil Tiket" class="p-2 hover:bg-green-500/10 rounded-lg group">
                                <i class="fas fa-hand-point-up text-green-500 group-hover:scale-110"></i>
                            </button>
                            ` : ''}
                            <button data-action="progress" data-id="${t.id}" title="Update Progress" class="p-2 hover:bg-cyan-500/10 rounded-lg group">
                                <i class="fas fa-tasks text-cyan-500 group-hover:scale-110"></i>
                            </button>
                            <button data-action="message" data-id="${t.id}" title="Pesan Internal" class="p-2 hover:bg-purple-500/10 rounded-lg group">
                                <i class="fas fa-comment-dots text-purple-500 group-hover:scale-110"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');

        // Event delegation
        tbody.addEventListener('click', function(e) {
            const btn = e.target.closest('button[data-action]');
            if (!btn) return;
            const id = btn.dataset.id;
            switch(btn.dataset.action) {
                case 'detail': openDetailModal(id); break;
                case 'take': takeTicket(id); break;
                case 'progress': openUpdateProgressModal(id); break;
                case 'message': openInternalMessageModal(id); break;
            }
        });
    }

    // === STATS ===
    function updateStats(stats) {
        document.getElementById('stat-total-agent').textContent = stats.total || 0;
        document.getElementById('stat-open-agent').textContent = stats.open || 0;
        document.getElementById('stat-progress-agent').textContent = stats.in_progress || 0;
        document.getElementById('stat-resolved-agent').textContent = stats.resolved || 0;
        document.getElementById('stat-closed-agent').textContent = stats.closed || 0;
    }

    // === MODAL: Detail ===
    async function openDetailModal(id) {
        try {
            const res = await fetch(`${API_URL}/${id}`);
            const result = await res.json();
            if (result.success) {
                const t = result.data;
                document.getElementById('detailTicketContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-1">ID Tiket</p>
                            <p class="text-lg font-mono font-bold text-blue-400">#TKT-${t.id}</p>
                        </div>
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Judul</p>
                            <p class="font-semibold text-white">${t.title}</p>
                            <p class="text-sm text-slate-300 mt-2">${t.description}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Status</p>
                                <span class="inline-block px-3 py-1 ${t.status === 'open' ? 'bg-yellow-500/20 text-yellow-500' : t.status === 'in_progress' ? 'bg-blue-500/20 text-blue-500' : 'bg-green-500/20 text-green-500'} rounded text-xs font-medium capitalize">${t.status}</span>
                            </div>
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Prioritas</p>
                                <span class="inline-block px-3 py-1 ${t.priority === 'critical' ? 'bg-red-500/20 text-red-500' : t.priority === 'high' ? 'bg-orange-500/20 text-orange-500' : 'bg-blue-500/20 text-blue-500'} rounded text-xs font-medium">${t.priority}</span>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Pelapor</p>
                            <p class="text-white">${t.user?.name || '-'}</p>
                        </div>
                    </div>
                `;
                document.getElementById('detailTicketModal').classList.remove('hidden');
                document.getElementById('detailTicketModal').classList.add('flex');
            }
        } catch (err) {
            alert('Gagal muat detail: ' + err.message);
        }
    }

    // === AMBIL TIKET ===
    async function takeTicket(id) {
        if (!confirm('Yakin ingin mengambil tiket ini?')) return;
        try {
            const res = await fetch(`${API_URL}/${id}/take`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const result = await res.json();
            if (result.success) {
                alert('✅ Tiket berhasil diambil!');
                loadTickets();
            } else {
                alert('❌ ' + result.message);
            }
        } catch (err) {
            alert('❌ Error: ' + err.message);
        }
    }

    // === MARK AS RESOLVED ===
    async function markAsResolved(ticketId) {
        if (!confirm('Yakin ingin menandai tiket ini sebagai "Selesai"?')) return;
        try {
            const res = await fetch(`${API_URL}/${ticketId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: 'resolved' })
            });
            const result = await res.json();
            if (result.success) {
                alert('✅ Tiket ditandai sebagai "Selesai"!');
                loadTickets();
                document.getElementById('updateProgressModal').classList.add('hidden');
            } else {
                alert('❌ Gagal: ' + result.message);
            }
        } catch (err) {
            alert('❌ Error: ' + err.message);
        }
    }

    // === MODAL: Update Progress ===
    function openUpdateProgressModal(id) {
        document.getElementById('ticketId').value = id;
        document.getElementById('updateProgressModal').classList.remove('hidden');
        document.getElementById('updateProgressModal').classList.add('flex');
    }

    // === MODAL: Pesan Internal ===
    function openInternalMessageModal(id) {
        document.getElementById('messageTicketId').value = id;
        document.getElementById('messageThread').innerHTML = `<div class="text-center text-slate-500 py-6">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Memuat pesan internal...</p>
        </div>`;
        document.getElementById('internalMessageModal').classList.remove('hidden');
        document.getElementById('internalMessageModal').classList.add('flex');
    }

    // === TUTUP MODAL ===
    ['closeDetailModalBtn', 'closeUpdateModalBtn', 'closeMessageModalBtn', 'cancelUpdateBtn'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
            document.getElementById('detailTicketModal')?.classList.add('hidden');
            document.getElementById('updateProgressModal')?.classList.add('hidden');
            document.getElementById('internalMessageModal')?.classList.add('hidden');
        });
    });

    // === SUBMIT: Update Progress (Catatan Internal) ===
    document.getElementById('updateProgressForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const ticketId = document.getElementById('ticketId').value;
        const note = document.getElementById('internalNote').value.trim();

        if (!note) {
            alert('Masukkan catatan terlebih dahulu!');
            return;
        }

        try {
            const res = await fetch(`${API_URL}/${ticketId}/notes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ note })
            });
            const result = await res.json();
            if (result.success) {
                alert('✅ Catatan berhasil ditambahkan!');
                document.getElementById('internalNote').value = '';
            } else {
                alert('❌ Gagal: ' + result.message);
            }
        } catch (err) {
            alert('❌ Error: ' + err.message);
        }
    });

    // === SUBMIT: Pesan Internal ===
    document.getElementById('sendMessageForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const ticketId = document.getElementById('messageTicketId').value;
        const message = document.getElementById('messageContent').value.trim();

        if (!message) return;

        try {
            const res = await fetch(`${API_URL}/${ticketId}/notes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ note: message })
            });
            const result = await res.json();
            if (result.success) {
                document.getElementById('messageContent').value = '';
                // Reload pesan (bisa diimplementasi lebih lanjut)
            } else {
                alert('❌ Gagal kirim pesan: ' + result.message);
            }
        } catch (err) {
            alert('❌ Error: ' + err.message);
        }
    });

    // === TOMBOL SELESAI ===
    document.getElementById('markAsResolvedBtn')?.addEventListener('click', () => {
        const ticketId = document.getElementById('ticketId').value;
        markAsResolved(ticketId);
    });

    // === INIT ===
    loadCategories();
    loadTickets();
});
</script>
@endpush