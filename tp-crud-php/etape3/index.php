<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP CRUD PHP/MySQL - Étape 3 (AJAX)</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        #toast-container { position: fixed; top: 1rem; right: 1rem; z-index: 1100; }
        .role-badge { font-size: .75rem; }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="bi bi-people me-2"></i>Utilisateurs <span id="count-badge" class="badge bg-secondary">0</span></h1>
        <button class="btn btn-primary" onclick="openModal()">
            <i class="bi bi-plus-lg me-1"></i>Nouvel utilisateur
        </button>
    </div>

    <div id="toast-container"></div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Créé le</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="users-tbody">
                    <tr><td colspan="5" class="text-center text-muted py-4">Chargement…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ══ Modal Create / Edit ══ -->
<div class="modal fade" id="userModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">Nouvel utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="modal-errors" class="alert alert-danger d-none"></div>
                <form id="user-form" novalidate>
                    <input type="hidden" id="user-id">
                    <div class="mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" id="f-email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" id="pwd-label">Mot de passe <span class="text-danger">*</span></label>
                        <input type="password" id="f-password" class="form-control">
                        <div class="form-text" id="pwd-hint">Minimum 6 caractères.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Rôle</label>
                        <select id="f-role" class="form-select">
                            <option value="guest">Guest</option>
                            <option value="author">Author</option>
                            <option value="editor">Editor</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-primary" id="save-btn" onclick="saveUser()">Enregistrer</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
const API = 'api.php';
const modal = new bootstrap.Modal(document.getElementById('userModal'));

const ROLE_COLORS = { guest: 'secondary', author: 'info', editor: 'primary', admin: 'danger' };

// ── Load users ────────────────────────────────────────────────────────────────
async function loadUsers() {
    const res  = await fetch(API);
    const data = await res.json();
    const tbody = document.getElementById('users-tbody');
    document.getElementById('count-badge').textContent = data.length;

    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted py-4">Aucun utilisateur.</td></tr>';
        return;
    }

    tbody.innerHTML = data.map(u => `
        <tr>
            <td>${u.id}</td>
            <td>${escHtml(u.email)}</td>
            <td><span class="badge bg-${ROLE_COLORS[u.role] ?? 'secondary'} role-badge">${u.role}</span></td>
            <td>${formatDate(u.created_at)}</td>
            <td class="text-center">
                <button class="btn btn-sm btn-info text-white" onclick="viewUser(${u.id})" title="Voir"><i class="bi bi-eye"></i></button>
                <button class="btn btn-sm btn-warning" onclick="openModal(${u.id})" title="Modifier"><i class="bi bi-pencil"></i></button>
                <button class="btn btn-sm btn-danger" onclick="deleteUser(${u.id})" title="Supprimer"><i class="bi bi-trash"></i></button>
            </td>
        </tr>`).join('');
}

// ── View user (alert) ─────────────────────────────────────────────────────────
async function viewUser(id) {
    const res  = await fetch(`${API}?id=${id}`);
    const u    = await res.json();
    if (u.error) return toast(u.error, 'danger');
    alert(`ID : ${u.id}\nEmail : ${u.email}\nRôle : ${u.role}\nCréé le : ${formatDate(u.created_at)}`);
}

// ── Open modal (create or edit) ───────────────────────────────────────────────
async function openModal(id = null) {
    document.getElementById('modal-errors').classList.add('d-none');
    document.getElementById('user-id').value    = '';
    document.getElementById('f-email').value    = '';
    document.getElementById('f-password').value = '';
    document.getElementById('f-role').value     = 'guest';

    if (id) {
        document.getElementById('modal-title').textContent = `Modifier #${id}`;
        document.getElementById('pwd-label').innerHTML = 'Nouveau mot de passe';
        document.getElementById('pwd-hint').textContent = 'Laisser vide pour conserver l\'actuel.';
        const res  = await fetch(`${API}?id=${id}`);
        const u    = await res.json();
        document.getElementById('user-id').value  = u.id;
        document.getElementById('f-email').value  = u.email;
        document.getElementById('f-role').value   = u.role;
    } else {
        document.getElementById('modal-title').textContent = 'Nouvel utilisateur';
        document.getElementById('pwd-label').innerHTML = 'Mot de passe <span class="text-danger">*</span>';
        document.getElementById('pwd-hint').textContent = 'Minimum 6 caractères.';
    }
    modal.show();
}

// ── Save (create or update) ───────────────────────────────────────────────────
async function saveUser() {
    const id       = document.getElementById('user-id').value;
    const email    = document.getElementById('f-email').value.trim();
    const password = document.getElementById('f-password').value;
    const role     = document.getElementById('f-role').value;

    const errBox = document.getElementById('modal-errors');
    errBox.classList.add('d-none');

    const payload = { email, role };
    if (password) payload.password = password;
    if (!id && !password) { showModalError('Le mot de passe est requis.'); return; }

    const url    = id ? `${API}?id=${id}` : API;
    const method = id ? 'PUT' : 'POST';

    const res  = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(payload) });
    const data = await res.json();

    if (!res.ok) { showModalError(data.error); return; }

    modal.hide();
    toast(data.message, 'success');
    loadUsers();
}

// ── Delete ────────────────────────────────────────────────────────────────────
async function deleteUser(id) {
    if (!confirm('Supprimer cet utilisateur ?')) return;
    const res  = await fetch(`${API}?id=${id}`, { method: 'DELETE' });
    const data = await res.json();
    toast(data.message ?? data.error, res.ok ? 'warning' : 'danger');
    loadUsers();
}

// ── Helpers ───────────────────────────────────────────────────────────────────
function showModalError(msg) {
    const box = document.getElementById('modal-errors');
    box.textContent = msg;
    box.classList.remove('d-none');
}

function toast(msg, type = 'success') {
    const t = document.createElement('div');
    t.className = `alert alert-${type} alert-dismissible fade show shadow`;
    t.innerHTML = `${msg}<button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;
    document.getElementById('toast-container').appendChild(t);
    setTimeout(() => t.remove(), 4000);
}

function formatDate(d) {
    if (!d) return '—';
    return new Date(d).toLocaleString('fr-FR', { day:'2-digit', month:'2-digit', year:'numeric', hour:'2-digit', minute:'2-digit' });
}

function escHtml(s) {
    return s.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Init
loadUsers();
</script>
</body>
</html>
