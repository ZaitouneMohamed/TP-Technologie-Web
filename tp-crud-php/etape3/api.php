<?php
require_once '../config.php';
header('Content-Type: application/json');

$pdo    = getConnection();
$method = $_SERVER['REQUEST_METHOD'];
$id     = (int)($_GET['id'] ?? 0);

function json($data, int $code = 200): void {
    http_response_code($code);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

switch ($method) {

    // ── GET all / GET one ────────────────────────────────────────────────────
    case 'GET':
        if ($id > 0) {
            $stmt = $pdo->prepare("SELECT id, email, role, created_at, updated_at FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            $user ? json($user) : json(['error' => 'Utilisateur introuvable'], 404);
        } else {
            $users = $pdo->query("SELECT id, email, role, created_at, updated_at FROM users ORDER BY id DESC")->fetchAll();
            json($users);
        }
        break;

    // ── POST (create) ────────────────────────────────────────────────────────
    case 'POST':
        $body = json_decode(file_get_contents('php://input'), true) ?? [];
        $email    = cleanInput($body['email'] ?? '');
        $password = $body['password'] ?? '';
        $role     = $body['role'] ?? 'guest';

        if (!validateEmail($email)) json(['error' => 'Email invalide'], 422);
        if (strlen($password) < 6)  json(['error' => 'Mot de passe trop court (min 6)'], 422);
        if (!in_array($role, ['guest','author','editor','admin'])) json(['error' => 'Rôle invalide'], 422);

        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $chk->execute([$email]);
        if ($chk->fetch()) json(['error' => 'Email déjà utilisé'], 409);

        $stmt = $pdo->prepare("INSERT INTO users (email, password, role) VALUES (?,?,?)");
        $stmt->execute([$email, hashPassword($password), $role]);
        json(['id' => (int)$pdo->lastInsertId(), 'message' => 'Utilisateur créé'], 201);
        break;

    // ── PUT (update) ─────────────────────────────────────────────────────────
    case 'PUT':
        if ($id <= 0) json(['error' => 'ID manquant'], 400);
        $body = json_decode(file_get_contents('php://input'), true) ?? [];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        if (!$user) json(['error' => 'Utilisateur introuvable'], 404);

        $email    = cleanInput($body['email'] ?? $user['email']);
        $role     = $body['role'] ?? $user['role'];
        $password = $body['password'] ?? '';

        if (!validateEmail($email)) json(['error' => 'Email invalide'], 422);
        if (!in_array($role, ['guest','author','editor','admin'])) json(['error' => 'Rôle invalide'], 422);
        if ($password !== '' && strlen($password) < 6) json(['error' => 'Mot de passe trop court (min 6)'], 422);

        $chk = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $chk->execute([$email, $id]);
        if ($chk->fetch()) json(['error' => 'Email déjà utilisé'], 409);

        if ($password !== '') {
            $pdo->prepare("UPDATE users SET email=?, password=?, role=? WHERE id=?")
                ->execute([$email, hashPassword($password), $role, $id]);
        } else {
            $pdo->prepare("UPDATE users SET email=?, role=? WHERE id=?")
                ->execute([$email, $role, $id]);
        }
        json(['message' => 'Utilisateur mis à jour']);
        break;

    // ── DELETE ───────────────────────────────────────────────────────────────
    case 'DELETE':
        if ($id <= 0) json(['error' => 'ID manquant'], 400);
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $stmt->rowCount() ? json(['message' => 'Utilisateur supprimé']) : json(['error' => 'Introuvable'], 404);
        break;

    default:
        json(['error' => 'Méthode non supportée'], 405);
}
