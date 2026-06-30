<?php
require_once '../config.php';
session_start();
$pdo = getConnection();

$errors = [];
$data = ['email' => '', 'role' => 'guest'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['email'] = cleanInput($_POST['email'] ?? '');
    $data['role']  = $_POST['role'] ?? 'guest';
    $password      = $_POST['password'] ?? '';
    $confirm       = $_POST['confirm'] ?? '';

    if (!validateEmail($data['email'])) {
        $errors[] = "L'adresse email est invalide.";
    } else {
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $check->execute([$data['email']]);
        if ($check->fetch()) $errors[] = "Cet email est déjà utilisé.";
    }

    if (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif ($password !== $confirm) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }

    if (!in_array($data['role'], ['guest', 'author', 'editor', 'admin'])) {
        $errors[] = "Rôle invalide.";
    }

    if (empty($errors)) {
        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$data['email'], hashPassword($password), $data['role']]);
        $_SESSION['msg'] = ['type' => 'success', 'text' => 'Utilisateur créé avec succès.'];
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un utilisateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-4" style="max-width:540px">
    <h2 class="mb-4"><i class="bi bi-person-plus me-2"></i>Nouvel utilisateur</h2>

    <?php if ($errors): ?>
        <div class="alert alert-danger">
            <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= $e ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" novalidate>
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
                    <label class="form-label">Confirmer le mot de passe <span class="text-danger">*</span></label>
                    <input type="password" name="confirm" class="form-control" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">Rôle</label>
                    <select name="role" class="form-select">
                        <?php foreach (['guest','author','editor','admin'] as $r): ?>
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
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
