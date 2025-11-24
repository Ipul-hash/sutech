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
            <!-- Tambahkan tombol ini -->
            <button id="openCreateModalBtn" class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40">
                <i class="fas fa-plus"></i>
                <span class="font-medium">Buat Tiket</span>
            </button>
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
                    <textarea id="internalNote" rows="3" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" placeholder="Tambahkan catatan..."></textarea>
                </div>
            </form>
        </div>
        <div class="p-6 bg-slate-800/30 border-t border-slate-800 flex justify-end space-x-3">
            <button type="button" id="cancelUpdateBtn" class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="submit" form="updateProgressForm" class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-blue-500/25">
                <i class="fas fa-save mr-2"></i>Simpan
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

<!-- Modal Create Ticket -->
<div id="createTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-500"></i>
                Buat Tiket Baru
            </h2>
            <button id="closeCreateModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">
            <form id="createTicketForm" class="space-y-6">
                <!-- Ruangan -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select name="room_id" id="roomSelect" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="">Pilih ruangan...</option>
                    </select>
                </div>
                <!-- Judul -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-heading mr-2 text-cyan-500"></i>Judul <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="title" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required minlength="5" placeholder="Masukkan judul tiket...">
                </div>
                <!-- Kategori -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-tag mr-2 text-green-500"></i>Kategori <span class="text-red-500">*</span>
                    </label>
                    <select name="category" id="categorySelect" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="">Pilih Kategori...</option>
                    </select>
                </div>
                <!-- Prioritas -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-exclamation-circle mr-2 text-orange-500"></i>Prioritas <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="critical" class="sr-only peer" required>
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-red-500 peer-checked:bg-red-500/10 hover:border-red-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-fire text-red-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">Critical</p>
                            </div>
                        </label>
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="high" class="sr-only peer">
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-orange-500 peer-checked:bg-orange-500/10 hover:border-orange-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-arrow-up text-orange-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">High</p>
                            </div>
                        </label>
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="medium" class="sr-only peer">
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-yellow-500 peer-checked:bg-yellow-500/10 hover:border-yellow-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-minus text-yellow-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">Medium</p>
                            </div>
                        </label>
                        <label class="cursor-pointer block group">
                            <input type="radio" name="priority" value="low" class="sr-only peer">
                            <div class="p-4 text-center bg-slate-800 border-2 border-slate-700 rounded-xl peer-checked:border-blue-500 peer-checked:bg-blue-500/10 hover:border-blue-500/50 transition-all group-hover:scale-105">
                                <i class="fas fa-arrow-down text-blue-500 text-2xl mb-2 block"></i>
                                <p class="text-xs font-medium">Low</p>
                            </div>
                        </label>
                    </div>
                </div>
                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-align-left mr-2 text-yellow-500"></i>Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" rows="4" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required minlength="20" placeholder="Jelaskan detail masalah..."></textarea>
                </div>
                <!-- Upload -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-paperclip mr-2 text-slate-400"></i>Lampiran (Opsional)
                    </label>
                    <div id="dropZone" class="border-2 border-dashed border-slate-700 rounded-xl p-8 text-center hover:border-blue-500 hover:bg-blue-500/5 cursor-pointer transition-all group">
                        <i class="fas fa-cloud-upload-alt text-blue-500 text-4xl mb-3 block group-hover:scale-110 transition-transform"></i>
                        <p class="text-sm font-medium mb-1 text-white">Klik atau seret file ke sini</p>
                        <p class="text-xs text-slate-400">Maksimal 10MB per file • Mendukung gambar, PDF, dan dokumen</p>
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
                <i class="fas fa-paper-plane mr-2"></i>Buat Tiket
            </button>
        </div>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastContainer" class="fixed bottom-6 right-6 z-50 space-y-2"></div>

<style>
@keyframes modal-in { from { opacity:0; transform:scale(0.95) translateY(-20px); } to { opacity:1; transform:scale(1) translateY(0); } }
@keyframes slide-in { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
.animate-modal { animation: modal-in 0.3s ease-out; }
.toast { animation: slide-in 0.3s ease-out; padding: 12px 16px; border-radius: 8px; color: white; font-weight: 500; box-shadow: 0 4px 12px rgba(0,0,0,0.2); min-width: 280px; }
.toast-success { background: linear-gradient(to right, #10b981, #059669); }
.toast-error { background: linear-gradient(to right, #ef4444, #dc2626); }
</style>

@endsection


@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_URL = '/api/agent/tickets';
    const OPTIONS_API = '/api/options';

    // === TOAST NOTIFICATION ===
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>${message}`;
        document.getElementById('toastContainer').appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // === CREATE TICKET MODAL ===
    const createModal = document.getElementById('createTicketModal');
    document.getElementById('openCreateModalBtn')?.addEventListener('click', () => {
        createModal.classList.remove('hidden');
        createModal.classList.add('flex');
        // Load options saat buka modal
        fetchOptions();
    });

    ['closeCreateModalBtn', 'cancelCreateBtn'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
            createModal.classList.add('hidden');
            createModal.classList.remove('flex');
        });
    });

    // === LOAD OPTIONS (ROOMS, CATEGORIES) ===
    async function fetchOptions() {
        try {
            const res = await fetch(OPTIONS_API);
            const data = await res.json();
            if (data.success) {
                // Ruangan
                const roomSelect = document.getElementById('roomSelect');
                if (roomSelect) {
                    roomSelect.innerHTML = '<option value="">Pilih ruangan...</option>' + 
                        data.data.rooms.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
                }
                // Kategori
                const catSelect = document.getElementById('categorySelect');
                if (catSelect) {
                    catSelect.innerHTML = '<option value="">Pilih Kategori...</option>' + 
                        data.data.categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');
                }
            }
        } catch (err) {
            console.error('Gagal muat opsi:', err);
            showToast('Gagal memuat data', 'error');
        }
    }

    // === FILE UPLOAD ===
    let uploadedFiles = [];
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const filePreview = document.getElementById('filePreview');

    if (dropZone) {
        dropZone.addEventListener('click', () => fileInput?.click());
        ['dragenter','dragover'].forEach(e => dropZone.addEventListener(e, (ev) => {
            ev.preventDefault();
            dropZone.classList.add('border-blue-500','bg-blue-500/10');
        }));
        ['dragleave','drop'].forEach(e => dropZone.addEventListener(e, () => {
            dropZone.classList.remove('border-blue-500','bg-blue-500/10');
        }));
        dropZone.addEventListener('drop', e => {
            e.preventDefault();
            if (e.dataTransfer.files.length) {
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

    document.getElementById('createTicketForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        uploadedFiles.forEach((file, i) => {
            formData.append(`attachments[${i}]`, file);
        });

        try {
            const res = await fetch(API_URL, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            const result = await res.json();
            if (result.success) {
                showToast('Tiket berhasil dibuat!');
                createModal.classList.add('hidden');
                loadTickets();
                // Reset form
                e.target.reset();
                uploadedFiles = [];
                filePreview.innerHTML = '';
                filePreview.classList.add('hidden');
            } else {
                showToast(result.message || 'Gagal membuat tiket', 'error');
            }
        } catch (err) {
            showToast('Error: ' + err.message, 'error');
        }
    });

   // === MODAL: Detail Tiket (UPDATE dengan Ruangan & Position) ===
async function openDetailModal(id) {
    try {
        const res = await fetch(`${API_URL}/${id}`);
        const result = await res.json();
        if (result.success) {
            const t = result.data;
            
            // Status & Priority Colors
            const statusColors = {
                open: 'bg-yellow-500/20 text-yellow-500 border border-yellow-500/30',
                in_progress: 'bg-blue-500/20 text-blue-500 border border-blue-500/30',
                resolved: 'bg-green-500/20 text-green-500 border border-green-500/30',
                closed: 'bg-slate-500/20 text-slate-500 border border-slate-500/30'
            };
            
            const priorityColors = {
                low: 'bg-blue-500/20 text-blue-500 border border-blue-500/30',
                medium: 'bg-yellow-500/20 text-yellow-500 border border-yellow-500/30',
                high: 'bg-orange-500/20 text-orange-500 border border-orange-500/30',
                critical: 'bg-red-500/20 text-red-500 border border-red-500/30'
            };

            // Status Icons
            const statusIcons = {
                open: 'fa-folder-open',
                in_progress: 'fa-spinner',
                resolved: 'fa-check-circle',
                closed: 'fa-times-circle'
            };

            // Priority Icons
            const priorityIcons = {
                low: 'fa-arrow-down',
                medium: 'fa-minus',
                high: 'fa-arrow-up',
                critical: 'fa-fire'
            };

            document.getElementById('detailTicketContent').innerHTML = `
                <div class="space-y-4">
                    <!-- ID Tiket -->
                    <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 mb-1">ID Tiket</p>
                        <p class="text-lg font-mono font-bold text-blue-400">#TKT-${t.id}</p>
                    </div>

                    <!-- Pelapor & Position (Side by Side) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="fas fa-user mr-1"></i>Pelapor
                            </p>
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center font-bold text-sm">
                                    ${(t.user?.name || 'NA').substring(0,2).toUpperCase()}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">${t.user?.name || 'N/A'}</p>
                                    <p class="text-xs text-slate-400">${t.user?.email || '-'}</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="fas fa-id-badge mr-1"></i>Sebagai
                            </p>
                            <span class="inline-flex items-center px-3 py-1.5 bg-purple-500/20 text-purple-400 border border-purple-500/30 rounded-lg text-sm font-medium">
                                <i class="fas fa-briefcase mr-2"></i>
                                ${t.position?.name || '-'}
                            </span>
                        </div>
                    </div>

                    <!-- Judul -->
                    <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 mb-2">
                            <i class="fas fa-heading mr-1"></i>Judul
                        </p>
                        <p class="font-semibold text-white text-base">${t.title}</p>
                    </div>

                    <!-- Deskripsi -->
                    <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 mb-2">
                            <i class="fas fa-align-left mr-1"></i>Deskripsi
                        </p>
                        <p class="text-sm text-slate-300 leading-relaxed">${t.description || '-'}</p>
                    </div>

                    <!-- Kategori & Ruangan (Side by Side) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="fas fa-tag mr-1"></i>Kategori
                            </p>
                            <span class="inline-flex items-center px-3 py-1 bg-green-500/20 text-green-400 border border-green-500/30 rounded-full text-xs font-semibold">
                                ${t.category || '-'}
                            </span>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="fas fa-map-marker-alt mr-1"></i>Ruangan
                            </p>
                            <span class="inline-flex items-center px-3 py-1 bg-red-500/20 text-red-400 border border-red-500/30 rounded-full text-xs font-semibold">
                                <i class="fas fa-door-open mr-1"></i>
                                ${t.room?.name || '-'}
                            </span>
                        </div>
                    </div>

                    <!-- Status & Prioritas (Side by Side) -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="fas fa-tasks mr-1"></i>Status
                            </p>
                            <span class="inline-flex items-center px-3 py-1.5 ${statusColors[t.status]} rounded-full text-xs font-semibold capitalize">
                                <i class="fas ${statusIcons[t.status]} mr-1.5"></i>
                                ${t.status?.replace('_', ' ') || '-'}
                            </span>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="fas fa-exclamation-circle mr-1"></i>Prioritas
                            </p>
                            <span class="inline-flex items-center px-3 py-1.5 ${priorityColors[t.priority]} rounded-full text-xs font-semibold capitalize">
                                <i class="fas ${priorityIcons[t.priority]} mr-1.5"></i>
                                ${t.priority || '-'}
                            </span>
                        </div>
                    </div>

                    <!-- Tim Tujuan -->
                    ${t.assigned_team ? `
                    <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 mb-2">
                            <i class="fas fa-users mr-1"></i>Tim Yang Menangani
                        </p>
                        <span class="inline-flex items-center px-3 py-1.5 bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 rounded-lg text-sm font-medium">
                            <i class="fas fa-user-friends mr-2"></i>
                            ${t.assigned_team?.name || '-'}
                        </span>
                    </div>
                    ` : ''}

                    <!-- Agent yang Handle -->
                    ${t.assigned_to ? `
                    <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 mb-2">
                            <i class="fas fa-user-tie mr-1"></i>Ditangani Oleh
                        </p>
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-green-500 to-emerald-500 flex items-center justify-center font-bold text-xs">
                                ${(t.agent?.name || 'NA').substring(0,2).toUpperCase()}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">${t.agent?.name || 'N/A'}</p>
                                <p class="text-xs text-slate-400">${t.agent?.email || '-'}</p>
                            </div>
                        </div>
                    </div>
                    ` : ''}

                    <!-- Waktu Dibuat & Update -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="far fa-calendar-plus mr-1"></i>Dibuat
                            </p>
                            <p class="text-sm text-white">
                                ${new Date(t.created_at).toLocaleString('id-ID', { 
                                    weekday: 'short',
                                    year: 'numeric', 
                                    month: 'short', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        </div>

                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">
                                <i class="far fa-calendar-check mr-1"></i>Terakhir Diupdate
                            </p>
                            <p class="text-sm text-white">
                                ${new Date(t.updated_at).toLocaleString('id-ID', { 
                                    weekday: 'short',
                                    year: 'numeric', 
                                    month: 'short', 
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </p>
                        </div>
                    </div>

                    <!-- Attachments (jika ada) -->
                    ${t.attachments && t.attachments.length > 0 ? `
                    <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                        <p class="text-xs text-slate-400 mb-3">
                            <i class="fas fa-paperclip mr-1"></i>Lampiran (${t.attachments.length})
                        </p>
                        <div class="space-y-2">
                            ${t.attachments.map(att => `
                                <a href="${att.url}" target="_blank" class="flex items-center space-x-3 p-2 bg-slate-700/50 hover:bg-slate-700 rounded-lg transition-colors">
                                    <i class="fas fa-file text-blue-400"></i>
                                    <span class="text-sm text-white flex-1">${att.filename}</span>
                                    <i class="fas fa-external-link-alt text-slate-400 text-xs"></i>
                                </a>
                            `).join('')}
                        </div>
                    </div>
                    ` : ''}
                </div>
            `;
            
            document.getElementById('detailTicketModal').classList.remove('hidden');
            document.getElementById('detailTicketModal').classList.add('flex');
        }
    } catch (err) {
        showToast('Gagal memuat detail: ' + err.message, 'error');
        console.error('Error loading detail:', err);
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
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-red-500">Gagal memuat tiket: ${err.message}</td></tr>`;
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

    // === AMBIL TIKET (UBAH JADI IN_PROGRESS) ===
    async function takeTicket(id) {
        if (!confirm('Ambil tiket ini?')) return;
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
                showToast('Tiket berhasil diambil!');
                loadTickets(); // ⬅️ Refresh tabel langsung
            } else {
                showToast(result.message, 'error');
            }
        } catch (err) {
            showToast('Error: ' + err.message, 'error');
        }
    }

    // === UPDATE PROGRESS ===
    async function updateProgress(ticketId, formData) {
        try {
            const res = await fetch(`${API_URL}/${ticketId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });
            const result = await res.json();
            if (result.success) {
                showToast('Progress berhasil diperbarui!');
                loadTickets(); // ⬅️ Refresh tabel
                document.getElementById('updateProgressModal').classList.add('hidden');
            } else {
                showToast(result.message, 'error');
            }
        } catch (err) {
            showToast('Error: ' + err.message, 'error');
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
            <p>Memuat pesan...</p>
        </div>`;
        document.getElementById('internalMessageModal').classList.remove('hidden');
        document.getElementById('internalMessageModal').classList.add('flex');
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
                        <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                            <p class="text-xs text-slate-400 mb-2">Pelapor</p>
                            <p class="text-white">${t.user?.name || '-'}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Status</p>
                                <span class="px-3 py-1 ${t.status === 'open' ? 'bg-yellow-500/20 text-yellow-500' : t.status === 'in_progress' ? 'bg-blue-500/20 text-blue-500' : 'bg-green-500/20 text-green-500'} rounded text-xs font-medium capitalize">${t.status}</span>
                            </div>
                            <div class="p-4 bg-slate-800/50 rounded-lg border border-slate-700">
                                <p class="text-xs text-slate-400 mb-2">Prioritas</p>
                                <span class="px-3 py-1 ${t.priority === 'critical' ? 'bg-red-500/20 text-red-500' : t.priority === 'high' ? 'bg-orange-500/20 text-orange-500' : 'bg-blue-500/20 text-blue-500'} rounded text-xs font-medium">${t.priority}</span>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('detailTicketModal').classList.remove('hidden');
                document.getElementById('detailTicketModal').classList.add('flex');
            }
        } catch (err) {
            showToast('Gagal muat detail: ' + err.message, 'error');
        }
    }

    // === TUTUP MODAL ===
    ['closeDetailModalBtn', 'closeUpdateModalBtn', 'closeMessageModalBtn', 'cancelUpdateBtn'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', () => {
            document.getElementById('detailTicketModal')?.classList.add('hidden');
            document.getElementById('updateProgressModal')?.classList.add('hidden');
            document.getElementById('internalMessageModal')?.classList.add('hidden');
        });
    });

    // === SUBMIT: Update Progress ===
    document.getElementById('updateProgressForm')?.addEventListener('submit', async (e) => {
        e.preventDefault();
        const ticketId = document.getElementById('ticketId').value;
        const status = document.getElementById('updateStatus').value;
        const priority = document.getElementById('updatePriority').value;
        const note = document.getElementById('internalNote').value.trim();

        // Update ticket
        await updateProgress(ticketId, { status, priority });

        // Kirim catatan jika ada
        if (note) {
            try {
                await fetch(`${API_URL}/${ticketId}/notes`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ note })
                });
            } catch (err) {
                console.warn('Gagal kirim catatan:', err);
            }
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
                showToast('Pesan terkirim!');
            } else {
                showToast(result.message, 'error');
            }
        } catch (err) {
            showToast('Gagal kirim pesan: ' + err.message, 'error');
        }
    });

    // === INIT ===
    loadTickets();
});
</script>
@endpush