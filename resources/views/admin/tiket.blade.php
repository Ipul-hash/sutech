@extends('layouts.app')
@section('title', 'Manajemen Tiket - Helpdesk')
@section('content')
<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold flex items-center">
                <i class="fas fa-ticket-alt mr-3 text-blue-500"></i>
                Manajemen Tiket
            </h1>
            <p class="text-slate-400 text-sm mt-1">Kelola dan pantau semua tiket support Anda</p>
        </div>
        <button id="openModalBtn" class="flex items-center space-x-2 px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-lg transition-all shadow-lg shadow-blue-500/25 hover:shadow-blue-500/40">
            <i class="fas fa-plus"></i>
            <span class="font-medium">Buat Tiket Baru</span>
        </button>
    </div>
    <!-- Stats -->
    <div class="gradient-border rounded-2xl p-6">
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <div class="bg-slate-800/50 rounded-xl p-5 hover:bg-slate-800 transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-ticket-alt text-blue-500 text-xl"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-blue-500 mb-1" id="stat-total">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Total Tiket</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5 hover:bg-slate-800 transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                        <i class="fas fa-folder-open text-yellow-500 text-xl"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-yellow-500 mb-1" id="stat-open">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Open</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5 hover:bg-slate-800 transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                        <i class="fas fa-spinner text-blue-500 text-xl"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-blue-500 mb-1" id="stat-progress">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Progress</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5 hover:bg-slate-800 transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                        <i class="fas fa-check-circle text-green-500 text-xl"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-green-500 mb-1" id="stat-resolved">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Resolved</p>
            </div>
            <div class="bg-slate-800/50 rounded-xl p-5 hover:bg-slate-800 transition-all hover:scale-105 cursor-pointer">
                <div class="flex items-center justify-between mb-3">
                    <div class="w-12 h-12 rounded-xl bg-slate-500/20 flex items-center justify-center">
                        <i class="fas fa-times-circle text-slate-500 text-xl"></i>
                    </div>
                </div>
                <p class="text-3xl font-bold text-slate-500 mb-1" id="stat-closed">0</p>
                <p class="text-xs text-slate-400 uppercase tracking-wide">Closed</p>
            </div>
        </div>
    </div>
    <!-- Tickets Table -->
    <div class="gradient-border rounded-2xl overflow-hidden">
        <div class="p-4 bg-slate-800/30 border-b border-slate-800 flex items-center justify-between">
            <h2 class="font-semibold text-lg flex items-center">
                <i class="fas fa-list mr-2 text-blue-500"></i>
                Daftar Tiket
            </h2>
            <div class="flex items-center space-x-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" placeholder="Cari tiket..." class="bg-slate-800 border border-slate-700 rounded-lg pl-9 pr-4 py-2 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">ID Tiket</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Judul</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Pelapor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="ticketsTableBody" class="divide-y divide-slate-800">
                    <tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">
                        <i class="fas fa-spinner fa-spin text-3xl mb-3 block"></i>
                        <p>Memuat data tiket...</p>
                    </td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Create -->
<div id="createTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-3xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-plus-circle mr-3 text-blue-500"></i>
                Buat Tiket Baru
            </h2>
            <button id="closeModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 180px);">
            <form id="createTicketForm" class="space-y-6">
                <!-- User -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-user mr-2 text-blue-500"></i>Pelapor <span class="text-red-500">*</span>
                    </label>
                    <div class="relative" id="userSearchContainer">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 z-10"></i>
                        <input type="text" id="userSearch" placeholder="Cari nama atau email..." class="w-full bg-slate-800 border border-slate-700 rounded-lg pl-11 pr-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        <div id="userDropdown" class="hidden absolute z-20 w-full mt-2 bg-slate-800 border border-slate-700 rounded-lg shadow-2xl max-h-60 overflow-y-auto"></div>
                    </div>
                    <div id="selectedUserBox" class="hidden mt-3 p-4 bg-gradient-to-r from-blue-500/10 to-cyan-500/10 border border-blue-500/30 rounded-lg animate-fade-in">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <div id="selectedUserAvatar" class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center font-bold text-base shadow-lg ring-2 ring-blue-500/20">AB</div>
                                <div>
                                    <p id="selectedUserName" class="text-sm font-semibold text-white">Nama User</p>
                                    <p id="selectedUserEmail" class="text-xs text-slate-400">email@example.com</p>
                                    <span id="selectedUserRoleBadge" class="inline-block mt-1 px-2 py-0.5 bg-blue-500/20 text-blue-400 text-xs rounded font-medium">
                                        <i class="fas fa-id-badge mr-1"></i>Role
                                    </span>
                                </div>
                            </div>
                            <button type="button" id="clearUserBtn" class="p-2 text-red-500 hover:text-red-400 hover:bg-red-500/10 rounded-lg transition-colors" title="Hapus pilihan">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" name="user_id" id="selectedUserId" required>
                </div>
                <!-- Role (dari user) -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-id-badge mr-2 text-purple-500"></i>Role
                    </label>
                    <input type="text" id="selectedUserRole" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm text-slate-500 cursor-not-allowed" disabled placeholder="Role akan muncul setelah memilih user">
                </div>
                <!-- Ruangan -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-map-marker-alt mr-2 text-red-500"></i>Ruangan <span class="text-red-500">*</span>
                    </label>
                    <select name="room_id" id="roomSelect" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="">Pilih ruangan...</option>
                        <!-- Diisi via JS -->
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
                        <option value="">Memuat kategori...</option>
                    </select>
                </div>
                <!-- Tim Tujuan -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-users mr-2 text-purple-500"></i>Tim Tujuan <span class="text-red-500">*</span>
                    </label>
                    <select name="assigned_team_id" id="assignedTeamSelect" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="">Pilih tim yang akan menangani...</option>
                        <!-- Diisi via /api/options → data.data.team -->
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
                    <textarea name="description" rows="4" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required minlength="20" placeholder="Jelaskan detail masalah yang dialami..."></textarea>
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
            <button type="button" id="cancelBtn" class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="submit" form="createTicketForm" class="px-5 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-blue-500/25">
                <i class="fas fa-paper-plane mr-2"></i>Buat Tiket
            </button>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-info-circle mr-3 text-blue-500"></i>
                Detail Tiket
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

<!-- Modal Edit -->
<div id="editTicketModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-2xl max-h-[90vh] overflow-hidden animate-modal">
        <div class="p-6 flex items-center justify-between border-b border-slate-800 bg-gradient-to-r from-slate-800 to-slate-900">
            <h2 class="text-xl font-bold flex items-center">
                <i class="fas fa-edit mr-3 text-green-500"></i>
                Edit Tiket
            </h2>
            <button id="closeEditModalBtn" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                <i class="fas fa-times text-slate-400 hover:text-white"></i>
            </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[60vh]">
            <form id="editTicketForm" class="space-y-4">
                <input type="hidden" id="editTicketId">
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-heading mr-2 text-cyan-500"></i>Judul
                    </label>
                    <input type="text" id="editTitle" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-tasks mr-2 text-blue-500"></i>Status
                    </label>
                    <select id="editStatus" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
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
                    <select id="editPriority" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">
                        <i class="fas fa-align-left mr-2 text-yellow-500"></i>Deskripsi
                    </label>
                    <textarea id="editDescription" rows="4" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-3 text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all" required></textarea>
                </div>
            </form>
        </div>
        <div class="p-6 bg-slate-800/30 border-t border-slate-800 flex justify-end space-x-3">
            <button type="button" id="cancelEditBtn" class="px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                <i class="fas fa-times mr-2"></i>Batal
            </button>
            <button type="submit" form="editTicketForm" class="px-5 py-2.5 bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-green-500/25">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        </div>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteConfirmModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-slate-900 rounded-2xl shadow-2xl border border-slate-800 w-full max-w-md animate-modal">
        <div class="p-6">
            <div class="w-16 h-16 rounded-full bg-red-500/20 flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-trash-alt text-red-500 text-2xl"></i>
            </div>
            <h3 class="text-xl font-bold text-white text-center mb-2">Konfirmasi Hapus</h3>
            <p class="text-slate-400 text-center mb-6">Yakin ingin menghapus tiket ini? Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex space-x-3">
                <button type="button" id="cancelDeleteBtn" class="flex-1 px-5 py-2.5 text-sm font-medium rounded-lg border border-slate-700 hover:bg-slate-800 transition-colors">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="button" id="confirmDeleteBtn" class="flex-1 px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-sm font-medium rounded-lg transition-all shadow-lg shadow-red-500/25">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
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
@keyframes fade-in {
    from { opacity: 0; }
    to { opacity: 1; }
}
.animate-modal {
    animation: modal-in 0.3s ease-out;
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out;
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const API_BASE = '/api/teknik-get';
    const USERS_API = '/api/users';
    const OPTIONS_API = '/api/options';
    let uploadedFiles = [];
    let currentTicketId = null;

    // === TOAST NOTIFICATION ===
    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `fixed bottom-6 right-6 p-4 rounded-lg text-white font-medium shadow-lg z-50 animate-fade-in ${
            type === 'success' 
                ? 'bg-gradient-to-r from-green-500 to-emerald-600' 
                : 'bg-gradient-to-r from-red-500 to-rose-600'
        }`;
        toast.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} mr-2"></i>${message}`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 4000);
    }

    // === FETCH ALL ===
    async function fetchAll() {
        await Promise.all([fetchTickets(), fetchUsers(), fetchOptions()]);
    }

    // === TICKETS ===
    async function fetchTickets() {
        try {
            const res = await fetch(API_BASE);
            const data = await res.json();
            if (data.success) {
                renderTickets(data.data);
                updateStats(data.data);
            }
        } catch (err) {
            document.getElementById('ticketsTableBody').innerHTML = `<tr><td colspan="7" class="px-6 py-8 text-center text-red-500"><i class="fas fa-exclamation-triangle text-2xl mb-2 block"></i>Gagal memuat tiket</td></tr>`;
        }
    }

    function renderTickets(tickets) {
        const tbody = document.getElementById('ticketsTableBody');
        if (!tickets.length) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-slate-400">
                <i class="fas fa-inbox text-4xl mb-3 block opacity-50"></i>
                <p class="font-medium">Tidak ada tiket</p>
                <p class="text-xs mt-1">Buat tiket baru untuk memulai</p>
            </td></tr>`;
            return;
        }
        tbody.innerHTML = tickets.map(t => {
            const createdAt = new Date(t.created_at);
            const now = new Date();
            const diffMin = Math.floor((now - createdAt) / 60000);
            let timeAgo = diffMin < 60 ? `${diffMin} menit` : diffMin < 1440 ? `${Math.floor(diffMin/60)} jam` : `${Math.floor(diffMin/1440)} hari`;
            let statusClass = '', statusText = t.status, statusIcon = '';
            if (t.status === 'open') { 
                statusClass = 'bg-yellow-500/20 text-yellow-500 border border-yellow-500/30'; 
                statusText = 'Open';
                statusIcon = 'fa-folder-open';
            }
            else if (t.status === 'in_progress') { 
                statusClass = 'bg-blue-500/20 text-blue-500 border border-blue-500/30'; 
                statusText = 'Progress';
                statusIcon = 'fa-spinner';
            }
            else if (t.status === 'resolved') { 
                statusClass = 'bg-green-500/20 text-green-500 border border-green-500/30'; 
                statusText = 'Resolved';
                statusIcon = 'fa-check-circle';
            }
            else { 
                statusClass = 'bg-slate-500/20 text-slate-500 border border-slate-500/30'; 
                statusText = 'Closed';
                statusIcon = 'fa-times-circle';
            }
            return `
                <tr class="hover:bg-slate-800/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="text-sm font-mono text-blue-400 font-semibold">#TKT-${t.id}</span>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-medium text-white mb-1">${t.title}</p>
                        <p class="text-xs text-slate-400 line-clamp-1">${t.description.substring(0, 60)}${t.description.length > 60 ? '...' : ''}</p>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center text-xs font-bold">
                                ${(t.user?.name || 'NA').substring(0,2).toUpperCase()}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-white">${t.user?.name || 'N/A'}</p>
                                <p class="text-xs text-slate-400">${t.user?.email || ''}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-300">${t.category}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-3 py-1 ${statusClass} rounded-full text-xs font-semibold">
                            <i class="fas ${statusIcon} mr-1"></i>
                            ${statusText}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-slate-400">
                            <i class="far fa-clock mr-1"></i>${timeAgo} lalu
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex space-x-1">
                            <button data-action="detail" data-id="${t.id}" class="p-2 hover:bg-blue-500/10 rounded-lg transition-colors group" title="Detail">
                                <i class="fas fa-eye text-blue-500 group-hover:scale-110 transition-transform"></i>
                            </button>
                            <button data-action="edit" data-id="${t.id}" class="p-2 hover:bg-green-500/10 rounded-lg transition-colors group" title="Edit">
                                <i class="fas fa-edit text-green-500 group-hover:scale-110 transition-transform"></i>
                            </button>
                            <button data-action="delete" data-id="${t.id}" class="p-2 hover:bg-red-500/10 rounded-lg transition-colors group" title="Hapus">
                                <i class="fas fa-trash text-red-500 group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
        tbody.addEventListener('click', function(e) {
            const btn = e.target.closest('button[data-action]');
            if (!btn) return;
            const action = btn.dataset.action;
            const id = parseInt(btn.dataset.id);
            if (action === 'detail') openDetailModal(id);
            else if (action === 'edit') openEditModal(id);
            else if (action === 'delete') openDeleteConfirm(id);
        });
    }

    function updateStats(tickets) {
        document.getElementById('stat-total').textContent = tickets.length;
        document.getElementById('stat-open').textContent = tickets.filter(t => t.status === 'open').length;
        document.getElementById('stat-progress').textContent = tickets.filter(t => t.status === 'in_progress').length;
        document.getElementById('stat-resolved').textContent = tickets.filter(t => t.status === 'resolved').length;
        document.getElementById('stat-closed').textContent = tickets.filter(t => t.status === 'closed').length;
    }

    // === USERS ===
    async function fetchUsers() {
        try {
            const res = await fetch(USERS_API);
            const result = await res.json();
            const users = result.success ? result.data : [];
            const dropdown = document.getElementById('userDropdown');
            dropdown.innerHTML = users.map(u => `
                <div class="p-3 hover:bg-slate-700 cursor-pointer transition-colors user-item border-b border-slate-700/50 last:border-0" data-id="${u.id}" data-name="${u.name}" data-email="${u.email}" data-role="${u.role}">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-cyan-500 flex items-center justify-center font-bold text-sm shadow-lg shrink-0">
                            ${(u.name.substring(0,2) || '??').toUpperCase()}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold truncate text-white">${u.name}</p>
                            <p class="text-xs text-slate-400 truncate">${u.email}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-blue-500/20 text-blue-400 text-xs rounded">${u.role}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        } catch (err) {
            console.error('Gagal muat users:', err);
            showToast('Gagal memuat daftar user', 'error');
        }
    }

    // === OPTIONS (Kategori + Tim + Ruangan) ===
    async function fetchOptions() {
        try {
            const res = await fetch(OPTIONS_API);
            const data = await res.json();
            if (data.success) {
                // ✅ KATEGORI: ambil .name dari object
                const catSelect = document.getElementById('categorySelect');
                catSelect.innerHTML = '<option value="">Pilih Kategori...</option>' + 
                    data.data.categories.map(c => `<option value="${c.name}">${c.name}</option>`).join('');

                // ✅ TIM TUJUAN
                const teamSelect = document.getElementById('assignedTeamSelect');
                teamSelect.innerHTML = '<option value="">Pilih tim yang akan menangani...</option>' + 
                    data.data.team.map(t => `<option value="${t.id}">${t.name}</option>`).join('');

                // ✅ RUANGAN
                const roomSelect = document.getElementById('roomSelect');
                roomSelect.innerHTML = '<option value="">Pilih ruangan...</option>' + 
                    data.data.rooms.map(r => `<option value="${r.id}">${r.name}</option>`).join('');
            }
        } catch (err) {
            console.error('Gagal muat opsi:', err);
            showToast('Gagal memuat data', 'error');
        }
    }

    // === MODALS ===
    const modal = document.getElementById('createTicketModal');
    document.getElementById('openModalBtn').addEventListener('click', () => {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('overflow-hidden');
    });

    function closeModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('overflow-hidden');
        document.getElementById('createTicketForm').reset();
        document.getElementById('selectedUserBox').classList.add('hidden');
        uploadedFiles = [];
        document.getElementById('filePreview').innerHTML = '';
        document.getElementById('filePreview').classList.add('hidden');
    }

    ['closeModalBtn', 'cancelBtn'].forEach(id => {
        document.getElementById(id).addEventListener('click', closeModal);
    });

    modal.addEventListener('click', e => e.target === modal && closeModal());
    document.addEventListener('keydown', e => e.key === 'Escape' && !modal.classList.contains('hidden') && closeModal());

    // === USER SEARCH ===
    const userSearch = document.getElementById('userSearch');
    const userDropdown = document.getElementById('userDropdown');
    userSearch.addEventListener('input', () => {
        const term = userSearch.value.toLowerCase();
        document.querySelectorAll('.user-item').forEach(item => {
            const match = item.dataset.name.toLowerCase().includes(term) || item.dataset.email.toLowerCase().includes(term);
            item.classList.toggle('hidden', !match);
        });
        userDropdown.classList.remove('hidden');
    });

    document.getElementById('userSearchContainer').addEventListener('click', function(e) {
        const item = e.target.closest('.user-item');
        if (!item) return;
        const id = item.dataset.id;
        const name = item.dataset.name;
        const email = item.dataset.email;
        const role = item.dataset.role;
        const initials = name.substring(0,2).toUpperCase();
        document.getElementById('selectedUserId').value = id;
        document.getElementById('selectedUserName').textContent = name;
        document.getElementById('selectedUserEmail').textContent = email;
        document.getElementById('selectedUserRole').value = role;
        document.getElementById('selectedUserRoleBadge').innerHTML = `<i class="fas fa-id-badge mr-1"></i>${role}`;
        document.getElementById('selectedUserAvatar').textContent = initials;
        document.getElementById('selectedUserBox').classList.remove('hidden');
        userSearch.value = '';
        userDropdown.classList.add('hidden');
    });

    document.getElementById('clearUserBtn').addEventListener('click', () => {
        document.getElementById('selectedUserBox').classList.add('hidden');
        document.getElementById('selectedUserId').value = '';
        document.getElementById('selectedUserRole').value = '';
    });

    // === FILE UPLOAD ===
    const fileInput = document.getElementById('fileInput');
    const dropZone = document.getElementById('dropZone');
    const filePreview = document.getElementById('filePreview');

    dropZone.addEventListener('click', () => fileInput.click());

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

    fileInput.addEventListener('change', e => handleFiles(Array.from(e.target.files)));

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
            filePreview.appendChild(div);
        });
        if (uploadedFiles.length > 0) filePreview.classList.remove('hidden');
    }

    filePreview.addEventListener('click', e => {
        if (e.target.closest('.remove-file')) {
            const name = e.target.closest('.remove-file').dataset.name;
            uploadedFiles = uploadedFiles.filter(f => f.name !== name);
            e.target.closest('div.flex').remove();
            if (uploadedFiles.length === 0) filePreview.classList.add('hidden');
        }
    });

    // === CREATE FORM SUBMIT (SUDAH INCLUDE assigned_team_id) ===
    document.getElementById('createTicketForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        if (!document.getElementById('selectedUserId').value) {
            showToast('Pilih pelapor terlebih dahulu!', 'error');
            return;
        }
        const formData = new FormData(e.target);
        uploadedFiles.forEach((file, i) => {
            formData.append(`attachments[${i}]`, file);
        });
        try {
            const res = await fetch(API_BASE, {
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
                closeModal();
                fetchTickets();
            } else {
                showToast(result.message || 'Gagal membuat tiket', 'error');
            }
        } catch (err) {
            showToast('Error: ' + err.message, 'error');
        }
    });

    // === DETAIL MODAL ===
    function openDetailModal(id) {
        currentTicketId = id;
        fetch(`${API_BASE}/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const t = data.data;
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
                                    <p class="text-xs text-slate-400 mb-2">Ruangan</p>
                                    <p class="font-medium text-white">${t.room?.name || '-'}</p>
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
            })
            .catch(err => alert('Gagal muat detail: ' + err.message));
    }

    // === EDIT MODAL ===
    function openEditModal(id) {
        currentTicketId = id;
        fetch(`${API_BASE}/${id}`)
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    const t = data.data;
                    document.getElementById('editTicketId').value = t.id;
                    document.getElementById('editTitle').value = t.title;
                    document.getElementById('editStatus').value = t.status;
                    document.getElementById('editPriority').value = t.priority;
                    document.getElementById('editDescription').value = t.description;
                    document.getElementById('editTicketModal').classList.remove('hidden');
                    document.getElementById('editTicketModal').classList.add('flex');
                }
            })
            .catch(err => alert('Gagal muat data edit: ' + err.message));
    }

    // === DELETE MODAL ===
    function openDeleteConfirm(id) {
        currentTicketId = id;
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
        document.getElementById('deleteConfirmModal').classList.add('flex');
    }

    // === CLOSE MODALS ===
    document.getElementById('closeDetailModalBtn').addEventListener('click', () => {
        document.getElementById('detailTicketModal').classList.add('hidden');
        document.getElementById('detailTicketModal').classList.remove('flex');
    });

    document.getElementById('closeEditModalBtn').addEventListener('click', () => {
        document.getElementById('editTicketModal').classList.add('hidden');
        document.getElementById('editTicketModal').classList.remove('flex');
    });

    document.getElementById('cancelEditBtn').addEventListener('click', () => {
        document.getElementById('editTicketModal').classList.add('hidden');
        document.getElementById('editTicketModal').classList.remove('flex');
    });

    document.getElementById('cancelDeleteBtn').addEventListener('click', () => {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        document.getElementById('deleteConfirmModal').classList.remove('flex');
    });

    // === EDIT SUBMIT ===
    document.getElementById('editTicketForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = {
            title: document.getElementById('editTitle').value,
            status: document.getElementById('editStatus').value,
            priority: document.getElementById('editPriority').value,
            description: document.getElementById('editDescription').value
        };
        try {
            const res = await fetch(`${API_BASE}/${currentTicketId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.success) {
                showToast('✅ Tiket berhasil diperbarui!');
                document.getElementById('editTicketModal').classList.add('hidden');
                document.getElementById('editTicketModal').classList.remove('flex');
                fetchTickets();
            } else {
                showToast('❌ Gagal: ' + (result.message || 'Error tidak diketahui'), 'error');
            }
        } catch (err) {
            showToast('❌ Error: ' + err.message, 'error');
        }
    });

    // === DELETE CONFIRM ===
    document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
        try {
            const res = await fetch(`${API_BASE}/${currentTicketId}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                }
            });
            const result = await res.json();
            if (result.success) {
                showToast('✅ Tiket berhasil dihapus!');
                document.getElementById('deleteConfirmModal').classList.add('hidden');
                document.getElementById('deleteConfirmModal').classList.remove('flex');
                fetchTickets();
            } else {
                showToast('❌ Gagal: ' + (result.message || 'Error tidak diketahui'), 'error');
            }
        } catch (err) {
            showToast('❌ Error: ' + err.message, 'error');
        }
    });

    // === INIT ===
    fetchAll();
});
</script>
@endpush