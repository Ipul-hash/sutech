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

    let editingId = null;

    /* ============================================================
       LOAD ROLES, TEAM, POSITION
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

        // RESET
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
            const users = await res.json();

            renderUsers(users);

        } catch (err) {
            console.error(err);
            showNotification("Gagal memuat data user", "error");
        }
    }

    function renderUsers(users) {
        userTableBody.innerHTML = '';

        users.forEach(user => {
            const row = document.createElement('tr');
            row.classList.add('user-row');

            row.dataset.id = user.id;
            row.dataset.role = user.role;
            row.dataset.team = user.team_id ?? "";
            row.dataset.position = user.position_id ?? "";
            row.dataset.status = user.status;

            const initials = user.name.split(" ").map(n => n[0]).join("").substring(0, 2).toUpperCase();

            row.innerHTML = `
                <td class="px-6 py-4">
                    <input type="checkbox" class="row-checkbox" />
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center font-semibold">${initials}</div>
                        <div>
                            <p class="text-sm font-medium user-name">${user.name}</p>
                            <p class="text-xs text-slate-400">${user.role}</p>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 user-email">${user.email}</td>
                <td class="px-6 py-4">${user.role}</td>
                <td class="px-6 py-4">${user.status}</td>
                <td class="px-6 py-4">-</td>
                <td class="px-6 py-4">${user.created_at}</td>

                <td class="px-6 py-4">
                    <button class="btn-edit text-green-500 mr-2" data-id="${user.id}">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-delete text-red-500" data-id="${user.id}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            `;

            userTableBody.appendChild(row);
        });
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

        showNotification(editingId ? "User diperbarui" : "User ditambahkan", "success");
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
            }
        }
    });

    /* ============================================================
       NOTIFICATION
    ============================================================ */
    function showNotification(msg, type = "info") {
        const box = document.createElement('div');
        box.className = `fixed top-4 right-4 p-4 rounded text-white shadow-lg z-50 ${
            type === "success" ? "bg-green-500" :
            type === "error" ? "bg-red-500" : "bg-blue-500"
        }`;

        box.innerHTML = msg;

        document.body.appendChild(box);
        setTimeout(() => box.remove(), 3000);
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
