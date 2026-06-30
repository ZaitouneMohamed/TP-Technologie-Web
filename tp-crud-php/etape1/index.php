<?php
require_once '../config.php';
session_start();
$pdo = getConnection();

$msg = $_SESSION['msg'] ?? null;
if ($msg) unset($_SESSION['msg']);

$stmt = $pdo->query("SELECT id, email, role, created_at FROM users ORDER BY id DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TP CRUD PHP/MySQL - Étape 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3"><i class="bi bi-people me-2"></i>Gestion des utilisateurs</h1>
        <a href="create.php" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Nouvel utilisateur</a>
    </div>

    <?php if ($msg): ?>
        <div class="alert alert-<?= $msg['type'] ?> alert-dismissible fade show">
            <?= htmlspecialchars($msg['text']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

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
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">Aucun utilisateur trouvé.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['id'] ?></td>
                        <td><?= htmlspecialchars($u['email']) ?></td>
                        <td><span class="badge bg-secondary"><?= $u['role'] ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($u['created_at'])) ?></td>
                        <td class="text-center">
                            <a href="view.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-info text-white" title="Voir"><i class="bi bi-eye"></i></a>
                            <a href="edit.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-warning" title="Modifier"><i class="bi bi-pencil"></i></a>
                            <a href="delete.php?id=<?= $u['id'] ?>" class="btn btn-sm btn-danger"
                               onclick="return confirm('Supprimer cet utilisateur ?')" title="Supprimer">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
