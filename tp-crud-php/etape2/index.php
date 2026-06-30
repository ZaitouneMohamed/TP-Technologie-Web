<?php
require_once '../config.php';
session_start();
$pdo    = getConnection();
$action = $_GET['action'] ?? 'list';
$id     = (int)($_GET['id'] ?? 0);
$errors = [];
$data   = ['email' => '', 'role' => 'guest'];

// ── DELETE ──────────────────────────────────────────────────────────────────
if ($action === 'delete' && $id > 0) {
    $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    $_SESSION['msg'] = ['type' => 'warning', 'text' => 'Utilisateur supprimé.'];
    header('Location: index.php');
    exit;
}

// ── CREATE (POST) ────────────────────────────────────────────────────────────
if ($action === 'create' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['email'] = cleanInput($_POST['email'] ?? '');
    $data['role']  = $_POST['role'] ?? 'guest';
    $password      = $_POST['password'] ?? '';
    $confirm       = $_POST['confirm'] ?? '';

    if (!validateEmail($data['email'])) {
        $errors[] = "Email invalide.";
    } else {
        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $chk->execute([$data['email']]);
        if ($chk->fetch()) $errors[] = "Email déjà utilisé.";
    }
    if (strlen($password) < 6)    $errors[] = "Mot de passe : 6 caractères minimum.";
    elseif ($password !== $confirm) $errors[] = "Les mots de passe ne correspondent pas.";
    if (!in_array($data['role'], ['guest','author','editor','admin'])) $errors[] = "Rôle invalide.";

    if (empty($errors)) {
        $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?,?,?)")
            ->execute([$data['email'], hashPassword($password), $data['role']]);
        $_SESSION['msg'] = ['type' => 'success', 'text' => 'Utilisateur créé avec succès.'];
        header('Location: index.php');
        exit;
    }
}

// ── EDIT (POST) ──────────────────────────────────────────────────────────────
if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['email'] = cleanInput($_POST['email'] ?? '');
    $data['role']  = $_POST['role'] ?? 'guest';
    $password      = $_POST['password'] ?? '';
    $confirm       = $_POST['confirm'] ?? '';

    if (!validateEmail($data['email'])) {
        $errors[] = "Email invalide.";
    } else {
        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $chk->execute([$data['email'], $id]);
        if ($chk->fetch()) $errors[] = "Email déjà utilisé par un autre utilisateur.";
    }
    if ($password !== '') {
        if (strlen($password) < 6)    $errors[] = "Mot de passe : 6 caractères minimum.";
        elseif ($password !== $confirm) $errors[] = "Les mots de passe ne correspondent pas.";
    }
    if (!in_array($data['role'], ['guest','author','editor','admin'])) $errors[] = "Rôle invalide.";

    if (empty($errors)) {
        if ($password !== '') {
            $pdo->prepare("UPDATE users SET email=?, password=?, role=? WHERE id=?")
                ->execute([$data['email'], hashPassword($password), $data['role'], $id]);
        } else {
            $pdo->prepare("UPDATE users SET email=?, role=? WHERE id=?")
                ->execute([$data['email'], $data['role'], $id]);
        }
        $_SESSION['msg'] = ['type' => 'success', 'text' => 'Utilisateur modifié avec succès.'];
        header('Location: index.php');
        exit;
    }
}

// ── LOAD DATA FOR EDIT/VIEW ──────────────────────────────────────────────────
$user = null;
if (in_array($action, ['edit', 'view']) && $id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();
    if (!$user) { header('Location: index.php'); exit; }
    if ($action === 'edit' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
        $data = ['email' => $user['email'], 'role' => $user['role']];
    }
}

// ── LIST ─────────────────────────────────────────────────────────────────────
$users = [];
if ($action === 'list') {
    $users = $pdo->query("SELECT id, email, role, created_at FROM users ORDER BY id DESC")->fetchAll();
}

$flash = $_SESSION['msg'] ?? null;
if ($flash) unset($_SESSION['msg']);

$roles = ['guest','author','editor','admin'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP CRUD PHP/MySQL - Étape 2</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">

<?php if ($flash): ?>
    <div class="alert alert-<?= $flash['type'] ?> alert-dismissible fade show">
        <?= htmlspecialchars($flash['text']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<!-- ══════════════ LIST ══════════════ -->
<?php if ($action === 'list'): ?>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="bi bi-people me-2"></i>Utilisateurs <span class="badge bg-secondary"><?= count($users) ?></span></h1>
        <a href="?action=create" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nouvel utilisateur</a>
    </div>
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-dark">
                    <tr><th>#</th><th>Email</th><th>Rôle</th><th>Créé le</th><th class="text-center">Actions</th></tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucun utilisateur.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge bg-secondary"><?= $u['role'] ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                        <td class="text-center">
                            <a href="?action=view&id=<?= $u['id'] ?>" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i></a>
                            <a href="?action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="?action=delete&id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer cet utilisateur ?')"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

<!-- ══════════════ CREATE ══════════════ -->
<?php elseif ($action === 'create'): ?>
    <h2 class="mb-4"><i class="bi bi-person-plus me-2"></i>Nouvel utilisateur</h2>
    <div class="card shadow-sm" style="max-width:540px">
        <div class="card-body">
            <form method="post" action="?action=create" novalidate>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="password" class="form-control" required>
                    <div class="form-text">Minimum 6 caractères.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer <span class="text-danger">*</span></label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select">
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r ?>" <?= $data['role'] === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Créer</button>
                    <a href="index.php" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>

<!-- ══════════════ VIEW ══════════════ -->
<?php elseif ($action === 'view' && $user): ?>
    <h2 class="mb-4"><i class="bi bi-person me-2"></i>Utilisateur #<?= $user['id'] ?></h2>
    <div class="card shadow-sm" style="max-width:540px">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">ID</dt><dd class="col-sm-8"><?= $user['id'] ?></dd>
                <dt class="col-sm-4">Email</dt><dd class="col-sm-8"><?= htmlspecialchars($user['email']) ?></dd>
                <dt class="col-sm-4">Rôle</dt><dd class="col-sm-8"><span class="badge bg-secondary"><?= $user['role'] ?></span></dd>
                <dt class="col-sm-4">Créé le</dt><dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></dd>
                <dt class="col-sm-4">Modifié le</dt><dd class="col-sm-8"><?= $user['updated_at'] ? date('d/m/Y H:i', strtotime($user['updated_at'])) : '—' ?></dd>
            </dl>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="?action=edit&id=<?= $user['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil me-1"></i>Modifier</a>
            <a href="index.php" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i>Retour</a>
        </div>
    </div>

<!-- ══════════════ EDIT ══════════════ -->
<?php elseif ($action === 'edit' && $user): ?>
    <h2 class="mb-4"><i class="bi bi-pencil me-2"></i>Modifier #<?= $user['id'] ?></h2>
    <div class="card shadow-sm" style="max-width:540px">
        <div class="card-body">
            <form method="post" action="?action=edit&id=<?= $id ?>" novalidate>
                <div class="mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($data['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password" class="form-control">
                    <div class="form-text">Laisser vide pour conserver l'actuel.</div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirmer</label>
                    <input type="password" name="confirm" class="form-control">
                </div>
                <div class="mb-4">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select">
                        <?php foreach ($roles as $r): ?>
                            <option value="<?= $r ?>" <?= $data['role'] === $r ? 'selected' : '' ?>><?= ucfirst($r) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-warning">Enregistrer</button>
                    <a href="index.php" class="btn btn-outline-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
