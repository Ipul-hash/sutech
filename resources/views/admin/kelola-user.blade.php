@extends('layouts.app')

@section('title', 'Kelola Akun - Helpdesk')

@section('content')
<div class="p-6 space-y-6">
    <!-- Header Section -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold">Kelola Akun User</h1>
            <p class="text-slate-400 text-sm mt-1">Manajemen user, role, dan permission</p>
        </div>
        <button id="btnTambahUser" class="flex items-center space-x-2 px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
            <i class="fas fa-user-plus"></i>
            <span class="font-medium">Tambah User Baru</span>
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="gradient-border rounded-2xl p-6 hover:shadow-lg transition-shadow cursor-pointer" data-filter="all">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase mb-2">Total User</p>
                    <h3 class="text-3xl font-bold" id="totalUsers">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="gradient-border rounded-2xl p-6 hover:shadow-lg transition-shadow cursor-pointer" data-filter="admin">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase mb-2">Admin</p>
                    <h3 class="text-3xl font-bold" id="totalAdmin">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <i class="fas fa-user-shield text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="gradient-border rounded-2xl p-6 hover:shadow-lg transition-shadow cursor-pointer" data-filter="agent">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-slate-400 uppercase mb-2">Agent</p>
                    <h3 class="text-3xl font-bold" id="totalAgent">0</h3>
                </div>
                <div class="w-12 h-12 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <i class="fas fa-headset text-green-500 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search Section -->
    <div class="gradient-border rounded-2xl p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <div class="relative">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                    <input 
                        type="text" 
                        id="searchInput"
                        placeholder="Cari berdasarkan nama, email, atau role..." 
                        class="w-full bg-slate-800 border border-slate-700 rounded-lg pl-11 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                </div>
            </div>

            <!-- Role Filter -->
            <div>
                <select id="roleFilter" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="agent">Agent</option>
                    <option value="user">User</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <select id="statusFilter" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Semua Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="suspended">Suspended</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="gradient-border rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-800/50">
                    <tr>
                        <th class="px-6 py-4 text-left">
                            <input type="checkbox" id="selectAll" class="rounded border-slate-600 text-blue-500 focus:ring-blue-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">User</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Role</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Tiket Handled</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Bergabung</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-400 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody id="userTableBody" class="divide-y divide-slate-800">
                    <!-- Data will be loaded from server -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah/Edit User -->
<div id="modalUser" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="gradient-border rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold" id="modalTitle">Tambah User Baru</h2>
                <button id="closeModal" class="p-2 hover:bg-slate-800 rounded-lg transition-colors">
                    <i class="fas fa-times text-slate-400"></i>
                </button>
            </div>
            <form id="formUser" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Nama Lengkap</label>
                        <input type="text" id="userName" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama lengkap" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Email</label>
                        <input type="email" id="userEmail" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="email@example.com" required>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Role</label>
                        <select id="userRole" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="agent">Agent</option>
                            <option value="user">User</option>
                        </select>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Team</label>
                        <select id="userTeam" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm">
                            <option value="">Pilih Team</option>
                        </select>
                    </div>
                        <div>
                        <label class="block text-sm font-medium mb-2">Sebagai</label>
                        <select id="userPosition" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm">
                            <option value="">Pilih Posisi</option>
                        </select>
                    </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Status</label>
                        <select id="userStatus" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="suspended">Suspended</option>
                        </select>
                    </div>
                </div>
                <div id="passwordField">
                    <label class="block text-sm font-medium mb-2">Password</label>
                    <input type="password" id="userPassword" class="w-full bg-slate-800 border border-slate-700 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Minimal 5 karakter">
                </div>
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" id="btnCancel" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 hover:bg-blue-600 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // DOM ELEMENTS
    const modal = document.getElementById('modalUser');
    const btnTambahUser = document.getElementById('btnTambahUser');
    const closeModal = document.getElementById('closeModal');
    const btnCancel = document.getElementById('btnCancel');
    const formUser = document.getElementById('formUser');
    const userTableBody = document.getElementById('userTableBody');
    const passwordField = document.getElementById('passwordField');

    const roleSelect = document.getElementById('userRole');
    const teamSelect = document.getElementById('userTeam');
    const posSelect = document.getElementById('userPosition');

    // Filter elements
    const searchInput = document.getElementById('searchInput');
    const roleFilter = document.getElementById('roleFilter');
    const statusFilter = document.getElementById('statusFilter');
    const statsCards = document.querySelectorAll('[data-filter]');

    // Stats counters
    const totalUsersEl = document.getElementById('totalUsers');
    const totalAdminEl = document.getElementById('totalAdmin');
    const totalAgentEl = document.getElementById('totalAgent');

    let editingId = null;
    let allUsers = []; // Store all users for filtering
    let activeFilter = 'all'; // Track active stats card filter

    /* ============================================================
       LOAD TEAMS & POSITIONS
    ============================================================ */
    async function loadTeamsAndPositions() {
        try {
            const res = await fetch('/api/options', {
                headers: { "Accept": "application/json" }
            });

            const json = await res.json();

            if (!json.success) throw new Error("API options gagal");

            const teams = json.data.team;
            const positions = json.data.position;

            teamSelect.innerHTML = `<option value="">Pilih Team</option>`;
            posSelect.innerHTML = `<option value="">Pilih Posisi</option>`;

            teams.forEach(t => {
                teamSelect.innerHTML += `<option value="${t.id}">${t.name}</option>`;
            });

            positions.forEach(p => {
                posSelect.innerHTML += `<option value="${p.id}">${p.name}</option>`;
            });

        } catch (err) {
            console.error(err);
            showNotification("Gagal memuat team / posisi", "error");
        }
    }

    /* ============================================================
       LOAD USERS TABLE
    ============================================================ */
    async function loadUsers() {
        try {
            const res = await fetch('/admin/kelola-user/data', {
                headers: { 'Accept': 'application/json' }
            });
            allUsers = await res.json();

            updateStats();
            applyFilters();

        } catch (err) {
            console.error(err);
            showNotification("Gagal memuat data user", "error");
        }
    }

    /* ============================================================
       UPDATE STATS COUNTERS
    ============================================================ */
    function updateStats() {
        const totalUsers = allUsers.length;
        const totalAdmin = allUsers.filter(u => u.role === 'admin').length;
        const totalAgent = allUsers.filter(u => u.role === 'agent').length;

        // Animate counter
        animateCounter(totalUsersEl, totalUsers);
        animateCounter(totalAdminEl, totalAdmin);
        animateCounter(totalAgentEl, totalAgent);
    }

    function animateCounter(element, targetValue) {
        const currentValue = parseInt(element.textContent) || 0;
        const increment = Math.ceil((targetValue - currentValue) / 10);
        
        let current = currentValue;
        const timer = setInterval(() => {
            current += increment;
            if ((increment > 0 && current >= targetValue) || (increment < 0 && current <= targetValue)) {
                element.textContent = targetValue;
                clearInterval(timer);
            } else {
                element.textContent = current;
            }
        }, 30);
    }

    /* ============================================================
       STATS CARD CLICK - FILTER BY ROLE
    ============================================================ */
    statsCards.forEach(card => {
        card.addEventListener('click', function() {
            const filterType = this.dataset.filter;
            
            // Remove active state from all cards
            statsCards.forEach(c => c.classList.remove('ring-2', 'ring-blue-500'));
            
            // Add active state to clicked card
            this.classList.add('ring-2', 'ring-blue-500');
            
            // Set active filter
            activeFilter = filterType;
            
            // Update role filter select
            if (filterType === 'all') {
                roleFilter.value = '';
            } else if (filterType === 'admin') {
                roleFilter.value = 'admin';
            } else if (filterType === 'agent') {
                roleFilter.value = 'agent';
            }
            
            applyFilters();
        });
    });

    /* ============================================================
       APPLY ALL FILTERS
    ============================================================ */
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const roleValue = roleFilter.value.toLowerCase();
        const statusValue = statusFilter.value.toLowerCase();

        let filteredUsers = allUsers.filter(user => {
            // Search filter
            const matchSearch = !searchTerm || 
                user.name.toLowerCase().includes(searchTerm) ||
                user.email.toLowerCase().includes(searchTerm) ||
                user.role.toLowerCase().includes(searchTerm);

            // Role filter (from dropdown or stats card)
            const matchRole = !roleValue || user.role.toLowerCase() === roleValue;

            // Status filter
            const matchStatus = !statusValue || user.status.toLowerCase() === statusValue;

            return matchSearch && matchRole && matchStatus;
        });

        renderUsers(filteredUsers);
    }

    /* ============================================================
       RENDER USERS TABLE
    ============================================================ */
    function renderUsers(users) {
        userTableBody.innerHTML = '';

        if (users.length === 0) {
            userTableBody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-slate-400">
                        <i class="fas fa-users text-4xl mb-3"></i>
                        <p>Tidak ada data user ditemukan</p>
                    </td>
                </tr>
            `;
            return;
        }

        users.forEach(user => {
            const row = document.createElement('tr');
            row.classList.add('user-row', 'hover:bg-slate-800/30', 'transition-colors');

            row.dataset.id = user.id;
            row.dataset.role = user.role;
            row.dataset.team = user.team_id ?? "";
            row.dataset.position = user.position_id ?? "";
            row.dataset.status = user.status;

            const initials = user.name.split(" ").map(n => n[0]).join("").substring(0, 2).toUpperCase();

            // Status badge color
            let statusBadge = '';
            if (user.status === 'active') {
                statusBadge = '<span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400">Active</span>';
            } else if (user.status === 'inactive') {
                statusBadge = '<span class="px-2 py-1 rounded-full text-xs bg-slate-500/20 text-slate-400">Inactive</span>';
            } else if (user.status === 'suspended') {
                statusBadge = '<span class="px-2 py-1 rounded-full text-xs bg-red-500/20 text-red-400">Suspended</span>';
            }

            // Role badge color
            let roleBadge = '';
            if (user.role === 'admin') {
                roleBadge = '<span class="px-2 py-1 rounded-full text-xs bg-purple-500/20 text-purple-400"><i class="fas fa-user-shield mr-1"></i>Admin</span>';
            } else if (user.role === 'agent') {
                roleBadge = '<span class="px-2 py-1 rounded-full text-xs bg-green-500/20 text-green-400"><i class="fas fa-headset mr-1"></i>Agent</span>';
            } else {
                roleBadge = '<span class="px-2 py-1 rounded-full text-xs bg-blue-500/20 text-blue-400"><i class="fas fa-user mr-1"></i>User</span>';
            }

            row.innerHTML = `
                <td class="px-6 py-4">
                    <input type="checkbox" class="row-checkbox rounded border-slate-600 text-blue-500 focus:ring-blue-500" />
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center font-semibold text-sm">${initials}</div>
                        <div>
                            <p class="text-sm font-medium user-name">${user.name}</p>
                            <p class="text-xs text-slate-400">ID: ${user.id}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <p class="text-sm user-email">${user.email}</p>
                </td>
                <td class="px-6 py-4">${roleBadge}</td>
                <td class="px-6 py-4">${statusBadge}</td>
                <td class="px-6 py-4 text-slate-400">-</td>
                <td class="px-6 py-4">
                    <p class="text-sm text-slate-400">${user.created_at}</p>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-2">
                        <button class="btn-edit p-2 hover:bg-green-500/20 text-green-500 rounded-lg transition-colors" data-id="${user.id}" title="Edit User">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn-delete p-2 hover:bg-red-500/20 text-red-500 rounded-lg transition-colors" data-id="${user.id}" title="Hapus User">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            `;

            userTableBody.appendChild(row);
        });
    }

    /* ============================================================
       FILTER EVENT LISTENERS
    ============================================================ */
    searchInput.addEventListener('input', debounce(applyFilters, 300));
    roleFilter.addEventListener('change', () => {
        // Reset stats card active state when using dropdown
        statsCards.forEach(c => c.classList.remove('ring-2', 'ring-blue-500'));
        applyFilters();
    });
    statusFilter.addEventListener('change', applyFilters);

    // Debounce function for search input
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /* ============================================================
       OPEN MODAL TAMBAH USER
    ============================================================ */
    btnTambahUser.addEventListener('click', () => {
        editingId = null;
        formUser.reset();
        passwordField.classList.remove('hidden');

        roleSelect.value = "";
        teamSelect.value = "";
        posSelect.value = "";

        document.getElementById('modalTitle').textContent = "Tambah User Baru";
        modal.classList.remove('hidden');
    });

    closeModal.addEventListener('click', () => modal.classList.add('hidden'));
    btnCancel.addEventListener('click', () => modal.classList.add('hidden'));

    /* ============================================================
       SUBMIT FORM (CREATE/UPDATE)
    ============================================================ */
    formUser.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = {
            name: document.getElementById('userName').value.trim(),
            email: document.getElementById('userEmail').value.trim(),
            role: roleSelect.value,
            status: document.getElementById('userStatus').value,
            team_id: teamSelect.value,
            position_id: posSelect.value,
            _token: document.querySelector('meta[name="csrf-token"]').content
        };

        if (!editingId) {
            formData.password = document.getElementById('userPassword').value;
        }

        const url = editingId 
            ? `/admin/kelola-user/${editingId}?_method=PUT`
            : `/admin/kelola-user`;

        const res = await fetch(url, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "Accept": "application/json"
            },
            body: JSON.stringify(formData)
        });

        const data = await res.json();

        if (!data.success) {
            showNotification(data.message || "Gagal menyimpan data", "error");
            return;
        }

        showNotification(editingId ? "User berhasil diperbarui" : "User berhasil ditambahkan", "success");
        modal.classList.add('hidden');
        loadUsers();
    });

    /* ============================================================
       EDIT USER
    ============================================================ */
    userTableBody.addEventListener('click', (e) => {
        if (e.target.closest('.btn-edit')) {
            const row = e.target.closest('tr');
            editingId = row.dataset.id;

            document.getElementById('modalTitle').textContent = "Edit User";

            document.getElementById('userName').value = row.querySelector('.user-name').textContent;
            document.getElementById('userEmail').value = row.querySelector('.user-email').textContent;

            roleSelect.value = row.dataset.role;
            teamSelect.value = row.dataset.team;
            posSelect.value = row.dataset.position;

            passwordField.classList.add('hidden');
            modal.classList.remove('hidden');
        }
    });

    /* ============================================================
       DELETE USER
    ============================================================ */
    userTableBody.addEventListener('click', async (e) => {
        if (e.target.closest('.btn-delete')) {
            const id = e.target.closest('.btn-delete').dataset.id;

            if (!confirm("Yakin hapus user ini?")) return;

            const res = await fetch(`/admin/kelola-user/${id}`, {
                method: "DELETE",
                headers: {
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
                }
            });

            const data = await res.json();
            if (data.success) {
                showNotification("User berhasil dihapus", "success");
                loadUsers();
            } else {
                showNotification(data.message || "Gagal menghapus user", "error");
            }
        }
    });

    /* ============================================================
       NOTIFICATION
    ============================================================ */
    function showNotification(msg, type = "info") {
        const box = document.createElement('div');
        box.className = `fixed top-4 right-4 p-4 rounded-lg text-white shadow-lg z-50 transition-all transform ${
            type === "success" ? "bg-green-500" :
            type === "error" ? "bg-red-500" : "bg-blue-500"
        }`;

        box.innerHTML = `
            <div class="flex items-center space-x-2">
                <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'}"></i>
                <span>${msg}</span>
            </div>
        `;

        document.body.appendChild(box);
        
        setTimeout(() => {
            box.style.opacity = '0';
            box.style.transform = 'translateX(100%)';
            setTimeout(() => box.remove(), 300);
        }, 3000);
    }

    /* ============================================================
       INIT LOAD
    ============================================================ */
    loadUsers();
    loadTeamsAndPositions();

});
</script>
@endpush
@endsection
