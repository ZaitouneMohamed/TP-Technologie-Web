<?php
require_once '../config.php';
$pdo = getConnection();

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:540px">
    <h2 class="mb-4"><i class="bi bi-person me-2"></i>Détails de l'utilisateur</h2>

    <div class="card shadow-sm">
        <div class="card-body">
            <dl class="row mb-0">
                <dt class="col-sm-4">ID</dt>
                <dd class="col-sm-8"><?= $user['id'] ?></dd>

                <dt class="col-sm-4">Email</dt>
                <dd class="col-sm-8"><?= htmlspecialchars($user['email']) ?></dd>

                <dt class="col-sm-4">Rôle</dt>
                <dd class="col-sm-8"><span class="badge bg-secondary"><?= $user['role'] ?></span></dd>

                <dt class="col-sm-4">Créé le</dt>
                <dd class="col-sm-8"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></dd>

                <dt class="col-sm-4">Modifié le</dt>
                <dd class="col-sm-8"><?= $user['updated_at'] ? date('d/m/Y H:i', strtotime($user['updated_at'])) : '—' ?></dd>
            </dl>
        </div>
        <div class="card-footer d-flex gap-2">
            <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-warning btn-sm">
                <i class="bi bi-pencil me-1"></i>Modifier
            </a>
            <a href="index.php" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left me-1"></i>Retour
            </a>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
